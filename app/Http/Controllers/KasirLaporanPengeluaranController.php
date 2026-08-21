<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use App\Models\BuktiPengeluaran;
use App\Exports\PengeluaranExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class KasirLaporanPengeluaranController extends Controller
{
    /**
     * Helper: ambil Pengeluaran milik partner dengan scope keamanan.
     * Mencegah IDOR dan tidak perlu query ulang partner_id di setiap method.
     */
    private function findOwnedPengeluaran(int $id, int $partnerId): Pengeluaran
    {
        return Pengeluaran::where('id', $id)
            ->where('partner_id', $partnerId)
            ->firstOrFail();
    }

    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->partner) {
            abort(403, 'Akses ditolak: Akun ini tidak terhubung dengan data Partner.');
        }
        $partnerId = $user->partner->id;

        $status      = $request->input('status', 'save');
        $quickFilter = $request->input('quick_filter');

        // Tentukan startDate & endDate berdasarkan quick_filter atau input manual
        if ($quickFilter) {
            $endDate   = Carbon::today('Asia/Jakarta')->toDateString();
            $startDate = match($quickFilter) {
                '1_hari'   => Carbon::today('Asia/Jakarta')->toDateString(),
                '1_minggu' => Carbon::today('Asia/Jakarta')->subWeek()->toDateString(),
                '1_bulan'  => Carbon::today('Asia/Jakarta')->subMonth()->toDateString(),
                '1_tahun'  => Carbon::today('Asia/Jakarta')->subYear()->toDateString(),
                default    => Carbon::today('Asia/Jakarta')->toDateString(),
            };
        } else {
            $startDate = $request->input('start_date', Carbon::today('Asia/Jakarta')->toDateString());
            $endDate   = $request->input('end_date', Carbon::today('Asia/Jakarta')->toDateString());
        }

        // Bangun range waktu — pakai string langsung agar konsisten dengan laporan bisnis
        $startTs = $startDate . ' 00:00:00';
        $endTs   = $endDate   . ' 23:59:59';

        // Eager load buktiPengeluaran — mencegah N+1 saat view mengakses relasi
        $pengeluarans = Pengeluaran::with('buktiPengeluaran')
            ->where('partner_id', $partnerId)
            ->where('status', $status)
            ->whereBetween('created_at', [$startTs, $endTs])
            ->orderBy('created_at', 'desc')
            ->get();

        // De-duplikasi berdasarkan path_bukti — 1 foto = 1 card di folder bukti.
        $galleryBukti = $pengeluarans
            ->pluck('buktiPengeluaran')
            ->flatten()
            ->unique('path_bukti')
            ->values();

        // --- Summary & Chart (selalu dari status 'save' agar angka akurat) ---
        $summaryBase = Pengeluaran::where('partner_id', $partnerId)
            ->where('status', 'save')
            ->whereBetween('created_at', [$startTs, $endTs]);

        $totalPengeluaran = (clone $summaryBase)->sum('total');

        $totalPerKategori = (clone $summaryBase)
            ->selectRaw('kategori, SUM(total) as jumlah')
            ->groupBy('kategori')
            ->pluck('jumlah', 'kategori')
            ->toArray();

        // Chart: groupBy hari / minggu / bulan sesuai panjang range
        $diffDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;

        if ($diffDays <= 31) {
            $chartRaw = (clone $summaryBase)
                ->selectRaw("DATE(created_at) as label, SUM(total) as jumlah")
                ->groupByRaw("DATE(created_at)")
                ->orderByRaw("label ASC")
                ->get();
            $chartLabels = $chartRaw->map(fn($r) =>
                Carbon::parse($r->label)->translatedFormat('d M')
            )->toArray();
        } elseif ($diffDays <= 366) {
            $chartRaw = (clone $summaryBase)
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%u') as label, SUM(total) as jumlah, MIN(DATE(created_at)) as first_day")
                ->groupByRaw("DATE_FORMAT(created_at, '%Y-%u')")
                ->orderByRaw("label ASC")
                ->get();
            $chartLabels = $chartRaw->map(fn($r) =>
                'Minggu ' . Carbon::parse($r->first_day)->translatedFormat('d M')
            )->toArray();
        } else {
            $chartRaw = (clone $summaryBase)
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as label, SUM(total) as jumlah")
                ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
                ->orderByRaw("label ASC")
                ->get();
            $chartLabels = $chartRaw->map(fn($r) =>
                Carbon::parse($r->label . '-01')->translatedFormat('M Y')
            )->toArray();
        }
        $chartData = $chartRaw->pluck('jumlah')->map(fn($v) => (float) $v)->toArray();

        return view('kasir.laporan-pengeluaran', compact(
            'pengeluarans', 'startDate', 'endDate', 'galleryBukti', 'status',
            'totalPengeluaran', 'totalPerKategori', 'chartLabels', 'chartData',
            'quickFilter'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string',
            'items'    => 'required|array|min:1',
            'hargas'   => 'required|array|min:1',
            'qtys'     => 'required|array|min:1',
            'totals'   => 'required|array|min:1',
            'bukti'    => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $partnerId  = $request->user()->partner->id;
        $kategori   = $request->kategori;
        $keterangan = ($kategori === 'lainnya') ? $request->keterangan : null;
        $now        = now();

        $pathBukti = null;
        $kodeBukti = null;
        if ($request->hasFile('bukti')) {
            $file      = $request->file('bukti');
            $filename  = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('pengeluaran', $filename);
            $pathBukti = $filename;
            // Generate kode unik 6 karakter (alfanumerik uppercase) sebagai label penanda
            $kodeBukti = strtoupper(substr(str_replace(['+', '/', '='], '', base64_encode(random_bytes(6))), 0, 6));
        }

        // Kumpulkan semua baris valid terlebih dahulu
        $pengeluaranRows = [];
        foreach ($request->items as $index => $item) {
            if (!empty($item)) {
                $pengeluaranRows[] = [
                    'partner_id'  => $partnerId,
                    'kategori'    => $kategori,
                    'keterangan'  => $keterangan,
                    'item'        => $item,
                    'harga'       => $request->hargas[$index] ?? 0,
                    'qty'         => $request->qtys[$index] ?? 1,
                    'total'       => $request->totals[$index] ?? 0,
                    'status'      => 'save',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];
            }
        }

        if (!empty($pengeluaranRows)) {
            // Bulk insert semua pengeluaran — 1 query ganti N query
            Pengeluaran::insert($pengeluaranRows);

            // Jika ada bukti, lampirkan ke semua baris yang baru dibuat
            if ($pathBukti) {
                // Ambil ID yang baru di-insert berdasarkan partner + created_at
                $newIds = Pengeluaran::where('partner_id', $partnerId)
                    ->where('created_at', $now)
                    ->pluck('id')
                    ->all();

                // Setiap pengeluaran dapat 1 record bukti dengan kode yang sama
                // sehingga di folder bukti, foto ini tampil 1x dengan label kode tersebut
                $buktiRows = array_map(fn($id) => [
                    'pengeluaran_id' => $id,
                    'path_bukti'     => $pathBukti,
                    'kode'           => $kodeBukti,
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ], $newIds);

                BuktiPengeluaran::insert($buktiRows);
            }
        }

        return redirect()->back()->with('success', 'Data pengeluaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $partnerId   = $request->user()->partner->id;
        // Scope partner_id — cegah IDOR
        $pengeluaran = $this->findOwnedPengeluaran($id, $partnerId);

        $pengeluaran->update([
            'item'  => $request->item,
            'harga' => $request->harga,
            'qty'   => $request->qty,
            'total' => $request->total,
        ]);

        return redirect()->back()->with('success', 'Data pengeluaran berhasil diubah.');
    }

    /**
     * Pindahkan ke tong sampah (soft-delete via status).
     */
    public function destroy($id)
    {
        $partnerId   = request()->user()->partner->id;
        $pengeluaran = $this->findOwnedPengeluaran($id, $partnerId);

        $pengeluaran->update(['status' => 'delete']);

        return redirect()->back()->with('success', 'Data dipindahkan ke tab Data Terhapus.');
    }

    /**
     * Pulihkan dari tong sampah ke status aktif.
     */
    public function restore($id)
    {
        $partnerId   = request()->user()->partner->id;
        $pengeluaran = $this->findOwnedPengeluaran($id, $partnerId);

        $pengeluaran->update(['status' => 'save']);

        return redirect()->back()->with('success', 'Data pengeluaran berhasil dipulihkan.');
    }

    public function export(Request $request)
    {
        $partnerId = $request->user()->partner->id;
        $startDate = $request->input('start_date', Carbon::today('Asia/Jakarta')->toDateString());
        $endDate   = $request->input('end_date', Carbon::today('Asia/Jakarta')->toDateString());
        $fileName  = 'laporan_pengeluaran_' . date('d-m-Y') . '.xlsx';

        ob_end_clean();
        ob_start();

        return Excel::download(new PengeluaranExport($partnerId, $startDate, $endDate), $fileName);
    }
}
