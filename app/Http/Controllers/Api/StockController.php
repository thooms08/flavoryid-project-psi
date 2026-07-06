<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StokBarang;
use App\Models\StokHarian;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StockController extends Controller
{
    private function getPartnerId(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->partner) {
            abort(403, 'Akun Anda belum terhubung dengan Partner.');
        }
        return $user->partner->id;
    }

    public function index(Request $request)
    {
        $partnerId = $this->getPartnerId($request);
        
        $bulanIni = $request->bulan ?? Carbon::now()->month;
        $tahunIni = $request->tahun ?? Carbon::now()->year;
        $search = $request->search;

        $query = StokBarang::with(['stokHarian' => function($q) use ($bulanIni, $tahunIni) {
            $q->whereMonth('tanggal', $bulanIni)->whereYear('tanggal', $tahunIni);
        }])->where('partner_id', $partnerId);

        if ($search) {
            $query->where('nama_barang', 'like', '%' . $search . '%');
        }

        $stokBarang = $query->orderBy('created_at', 'desc')->get();
        $jumlahHari = Carbon::createFromDate($tahunIni, $bulanIni, 1)->daysInMonth;

        // Transformasi data untuk mempermudah Flutter
        $stokBarang->transform(function ($item) {
            $harianMap = [];
            foreach ($item->stokHarian as $sh) {
                $day = (int) Carbon::parse($sh->tanggal)->format('d');
                $harianMap[$day] = (float) $sh->jumlah;
            }

            // Cari stok terakhir secara keseluruhan (bukan cuma bulan ini) untuk pewarnaan
            $stokTerakhir = StokHarian::where('stok_barang_id', $item->id)
                            ->orderBy('tanggal', 'desc')->first();

            $item->stok_terakhir_val = $stokTerakhir ? (float) $stokTerakhir->jumlah : 0;
            $item->has_stok = $stokTerakhir ? true : false;
            $item->harian_map = $harianMap;
            
            return $item;
        });

        return response()->json([
            'status' => 'success',
            'data' => $stokBarang,
            'meta' => [
                'bulan' => (int) $bulanIni,
                'tahun' => (int) $tahunIni,
                'jumlah_hari' => $jumlahHari
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'satuan'      => 'required|string|max:50',
        ]);

        StokBarang::create([
            'partner_id'  => $this->getPartnerId($request),
            'nama_barang' => $request->nama_barang,
            'satuan'      => $request->satuan,
            'min_hijau'   => $request->min_hijau ?? 10,
            'max_hijau'   => $request->max_hijau ?? 9999,
            'min_kuning'  => $request->min_kuning ?? 3,
            'max_kuning'  => $request->max_kuning ?? 9.99,
            'min_merah'   => $request->min_merah ?? 0,
            'max_merah'   => $request->max_merah ?? 2.99,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Barang berhasil ditambahkan']);
    }

    public function update(Request $request, StokBarang $stok)
    {
        $request->validate(['nama_barang' => 'required', 'satuan' => 'required']);
        if ((int) $stok->partner_id !== (int) $this->getPartnerId($request)) abort(403);

        $stok->update($request->only('nama_barang', 'satuan'));
        return response()->json(['status' => 'success', 'message' => 'Barang diperbarui']);
    }

    public function destroy(Request $request, StokBarang $stok)
    {
        if ((int) $stok->partner_id !== (int) $this->getPartnerId($request)) abort(403);
        $stok->delete();
        return response()->json(['status' => 'success']);
    }

    public function updateWarna(Request $request, StokBarang $stok)
    {
        if ((int) $stok->partner_id !== (int) $this->getPartnerId($request)) abort(403);
        $stok->update($request->all());
        return response()->json(['status' => 'success', 'message' => 'Range warna diperbarui']);
    }

    public function aturStok(Request $request, StokBarang $stok)
    {
        $request->validate([
            'input_stok' => 'required|array',
            'bulan'      => 'required|integer',
            'tahun'      => 'required|integer',
        ]);

        if ((int) $stok->partner_id !== (int) $this->getPartnerId($request)) abort(403);

        foreach ($request->input_stok as $hari => $jumlah) {
            if ($jumlah !== null && $jumlah !== "") {
                $tanggal = Carbon::createFromDate($request->tahun, $request->bulan, $hari)->format('Y-m-d');
                StokHarian::updateOrCreate(
                    ['stok_barang_id' => $stok->id, 'tanggal' => $tanggal],
                    ['jumlah' => $jumlah]
                );
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Stok harian disimpan']);
    }
}