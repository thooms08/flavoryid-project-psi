<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use App\Models\BuktiPengeluaran;
use App\Exports\PengeluaranExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class KasirLaporanPengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->partner) {
            abort(403, 'Akses ditolak: Akun ini tidak terhubung dengan data Partner.');
        }
        $partnerId = $user->partner->id;

        // Ambil status dari request (default: save)
        $status = $request->input('status', 'save');

        // Filter Waktu (Default Hari Ini, Zona Waktu Jakarta)
        $startDate = $request->input('start_date', Carbon::today('Asia/Jakarta')->toDateString());
        $endDate = $request->input('end_date', Carbon::today('Asia/Jakarta')->toDateString());

        // Data Tabel Pengeluaran - Ditambahkan filter status
        $pengeluarans = Pengeluaran::with('buktiPengeluaran')
            ->where('partner_id', $partnerId)
            ->where('status', $status) // Filter status baru
            ->whereBetween('created_at', [
                Carbon::parse($startDate, 'Asia/Jakarta')->startOfDay(), 
                Carbon::parse($endDate, 'Asia/Jakarta')->endOfDay()
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Data Gallery Bukti
        $galleryBukti = BuktiPengeluaran::whereIn('pengeluaran_id', $pengeluarans->pluck('id'))
            ->get()
            ->unique('path_bukti');

        return view('kasir.laporan-pengeluaran', compact('pengeluarans', 'startDate', 'endDate', 'galleryBukti', 'status'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string',
            'items' => 'required|array|min:1',
            'hargas' => 'required|array|min:1',
            'qtys' => 'required|array|min:1',
            'totals' => 'required|array|min:1',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $partnerId = $request->user()->partner->id;
        $kategori = $request->kategori;
        $keterangan = ($kategori === 'lainnya') ? $request->keterangan : null;

        $pathBukti = null;
        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            // Disk 'local' root-nya sudah storage/app/private — cukup subfolder langsung
            $file->storeAs('pengeluaran', $filename);
            $pathBukti = $filename;
        }

        foreach ($request->items as $index => $item) {
            if (!empty($item)) {
                $pengeluaran = Pengeluaran::create([
                    'partner_id' => $partnerId,
                    'kategori' => $kategori,
                    'keterangan' => $keterangan,
                    'item' => $item,
                    'harga' => $request->hargas[$index] ?? 0,
                    'qty' => $request->qtys[$index] ?? 1,
                    'total' => $request->totals[$index] ?? 0,
                    'status' => 'save' // Explicitly set status save
                ]);

                if ($pathBukti) {
                    BuktiPengeluaran::create([
                        'pengeluaran_id' => $pengeluaran->id,
                        'path_bukti' => $pathBukti
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Data pengeluaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        
        $pengeluaran->update([
            'item' => $request->item,
            'harga' => $request->harga,
            'qty' => $request->qty,
            'total' => $request->total,
        ]);

        return redirect()->back()->with('success', 'Data pengeluaran berhasil diubah.');
    }

    /**
     * Mengubah status data menjadi 'delete' (Pindahkan ke Tong Sampah)
     */
    public function destroy($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        
        // Logika diubah: Bukan menghapus permanen, tapi ubah status ke delete
        $pengeluaran->update(['status' => 'delete']);
        
        return redirect()->back()->with('success', 'Data dipindahkan ke tab Data Terhapus.');
    }

    /**
     * Memulihkan data dari 'delete' kembali ke 'save'
     */
    public function restore($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        
        $pengeluaran->update(['status' => 'save']);
        
        return redirect()->back()->with('success', 'Data pengeluaran berhasil dipulihkan.');
    }

    public function export(Request $request)
    {
        $partnerId = $request->user()->partner->id;
        $startDate = $request->input('start_date', Carbon::today('Asia/Jakarta')->toDateString());
        $endDate = $request->input('end_date', Carbon::today('Asia/Jakarta')->toDateString());
        
        $fileName = 'laporan_pengeluaran_' . date('d-m-Y') . '.xlsx';
        
        ob_end_clean(); 
        ob_start();

        return Excel::download(new PengeluaranExport($partnerId, $startDate, $endDate), $fileName);
    }
}