<?php

namespace App\Http\Controllers;

use App\Models\StokBarang;
use App\Models\StokHarian;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KasirStokController extends Controller
{
    private function getPartnerId()
    {
        $partner = Partner::where('user_id', Auth::id())->first();
        if (!$partner) abort(403, 'Akun Anda belum memiliki profil Partner.');
        return $partner->id;
    }

    public function index(Request $request)
    {
        $partnerId = $this->getPartnerId();
        
        // Ambil filter dari request (jika ada), jika tidak gunakan bulan & tahun saat ini
        $bulanIni = $request->bulan ?? Carbon::now()->month;
        $tahunIni = $request->tahun ?? Carbon::now()->year;
        $search = $request->search;

        // Query dasar
        $query = StokBarang::with('stokHarian')
                    ->where('partner_id', $partnerId);

        // Jika ada pencarian, tambahkan filter nama_barang
        if ($search) {
            $query->where('nama_barang', 'like', '%' . $search . '%');
        }

        $stokBarang = $query->orderBy('created_at', 'desc')->get();
        
        // Hitung jumlah hari di bulan dan tahun yang dipilih
        $jumlahHari = Carbon::createFromDate($tahunIni, $bulanIni, 1)->daysInMonth;

        // JIKA REQUEST BERASAL DARI AJAX (Pencarian / Filter Realtime)
        if ($request->ajax()) {
            // Render dan kembalikan HANYA potongan HTML list-stok saja
            return view('kasir.partials.list-stok', compact('stokBarang', 'jumlahHari', 'bulanIni', 'tahunIni'))->render();
        }

        // Jika request normal (akses halaman pertama kali)
        return view('kasir.stok-management', compact('stokBarang', 'jumlahHari', 'bulanIni', 'tahunIni'));
    }

    public function store(Request $request)
{
    $request->validate([
        'nama_barang' => 'required|string|max:255',
        'satuan'      => 'required|string|max:50',
    ]);

    // Mengambil ID Partner (sesuaikan jika ada fungsi bawaan di kode Anda)
    $partnerId = \App\Models\Partner::where('user_id', \Illuminate\Support\Facades\Auth::id())->first()->id;

    StokBarang::create([
        'partner_id'  => $partnerId,
        'nama_barang' => $request->nama_barang,
        'satuan'      => $request->satuan,
        // Ambil data dari form modal, jika kosong (null), maka gunakan default angka di sebelahnya
        'min_hijau'   => $request->min_hijau ?? 10,
        'max_hijau'   => $request->max_hijau ?? 9999,
        'min_kuning'  => $request->min_kuning ?? 3,
        'max_kuning'  => $request->max_kuning ?? 9.99,
        'min_merah'   => $request->min_merah ?? 0,
        'max_merah'   => $request->max_merah ?? 2.99,
    ]);

    return redirect()->back()->with('success', 'Barang berhasil ditambahkan beserta pengaturan warnanya.');
}

    public function update(Request $request, StokBarang $stok)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'satuan'      => 'required|string|max:50',
        ]);

        // PERBAIKAN: Cast ke integer di sini
        if ((int) $stok->partner_id !== (int) $this->getPartnerId()) {
            abort(403);
        }

        $stok->update($request->only('nama_barang', 'satuan'));
        return redirect()->back()->with('success', 'Barang berhasil diperbarui.');
    }
    public function destroy(StokBarang $stok)
    {
        if ($stok->partner_id !== $this->getPartnerId()) abort(403);
        $stok->delete();
        return redirect()->back()->with('success', 'Barang berhasil dihapus.');
    }

    public function updateWarna(Request $request, StokBarang $stok)
    {
        $request->validate([
            'min_hijau' => 'required|numeric', 'max_hijau' => 'required|numeric',
            'min_kuning' => 'required|numeric', 'max_kuning' => 'required|numeric',
            'min_merah' => 'required|numeric', 'max_merah' => 'required|numeric',
        ]);

        // PERBAIKAN: Cast ke integer (int) agar tipe datanya pasti sama
        if ((int) $stok->partner_id !== (int) $this->getPartnerId()) {
            abort(403, 'Akses ditolak: Barang ini bukan milik partner Anda.');
        }

        $stok->update($request->all());
        return redirect()->back()->with('success', 'Range warna berhasil diperbarui.');
    }

    public function aturStok(Request $request, StokBarang $stok)
{
    // Validasi input
    $request->validate([
        'input_stok'   => 'required|array',
        'input_stok.*' => 'nullable|numeric', 
        'bulan'        => 'required|integer',
        'tahun'        => 'required|integer',
    ]);

    // PERBAIKAN DI SINI: Paksa keduanya menjadi integer (int) agar pasti sama
    if ((int) $stok->partner_id !== (int) $this->getPartnerId()) {
        abort(403, 'Akses ditolak: Barang ini bukan milik partner Anda.');
    }

    // Perulangan untuk menyimpan data harian
    foreach ($request->input_stok as $hari => $jumlah) {
        if ($jumlah !== null) {
            $tanggal = \Carbon\Carbon::createFromDate($request->tahun, $request->bulan, $hari)->format('Y-m-d');
            
            \App\Models\StokHarian::updateOrCreate(
                ['stok_barang_id' => $stok->id, 'tanggal' => $tanggal],
                ['jumlah' => $jumlah]
            );
        }
    }

    // Redirect kembali dan tampilkan pesan sukses (akan memicu alert jika sudah kamu buat di Blade)
    return redirect()->back()->with('success', 'Stok harian berhasil disimpan.');
}
}