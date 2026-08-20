<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Models\InformasiOutlet;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

class KasirController extends Controller
{
    /**
     * Hapus semua cache laporan bisnis milik partner ini agar data terbaru
     * langsung tampil tanpa menunggu TTL habis.
     * Dipanggil setiap kali ada transaksi yang berubah ke status success.
     */
    private function bustLaporanCache(int $partnerId): void
    {
        $today = \Carbon\Carbon::today('Asia/Jakarta')->toDateString();

        // Hapus cache laporan bisnis hari ini (paling umum dibuka kasir)
        Cache::forget("laporan_bisnis_{$partnerId}_{$today}_{$today}");

        // Hapus cache chart hari ini
        Cache::forget("chart_bisnis_{$partnerId}_{$today}_{$today}");

        // Hapus cache rentang yang mencakup hari ini (misalnya 1 minggu, 1 bulan)
        // dengan menghapus berdasarkan tag jika driver mendukung, atau pola di bawah ini.
        // Untuk shared hosting (file/database driver), kita cukup hapus yang paling kritis.
        $ranges = [
            \Carbon\Carbon::today('Asia/Jakarta')->subWeek()->toDateString(),
            \Carbon\Carbon::today('Asia/Jakarta')->subMonth()->toDateString(),
            \Carbon\Carbon::today('Asia/Jakarta')->subYear()->toDateString(),
        ];

        foreach ($ranges as $start) {
            Cache::forget("laporan_bisnis_{$partnerId}_{$start}_{$today}");
            Cache::forget("chart_bisnis_{$partnerId}_{$start}_{$today}");
        }
    }

    public function index()
    {
        // 1. Ambil data partner dari user yang sedang login
        $partner = Auth::user()->partner;
        $partnerId = $partner->id;

        // 2. Ambil data pesanan
        $orders = Order::with(['orderDetails.menu'])
            ->where('partner_id', $partnerId)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Ambil semua daftar menu
        $allMenus = Menu::where('partner_id', $partnerId)
            ->where('status_menu', 'tersedia') 
            ->orderBy('nama_menu', 'asc')
            ->get();

        $toggleSound = \App\Models\ToggleSound::firstOrCreate(
            ['partner_id' => $partnerId],
            ['is_sound_enabled' => false]
        );

        // --- TAMBAHAN BARU: Ambil data Pengaturan Form ---
        $pengaturan = \App\Models\PengaturanForm::firstOrCreate(
            ['partner_id' => $partnerId],
            [
                'nama' => true,
                'whatsapp' => true,
                'meja' => true,
                'keterangan' => true,
                'diskon' => true,
                'bukti_pembayaran' => true, // default true
            ]
        );
        // ------------------------------------------------

        // 4. Kirim data ke view (Pastikan 'pengaturan' ditambahkan ke dalam compact)
        return view('kasir.index', compact('orders', 'allMenus' , 'toggleSound', 'pengaturan'));
    }
    /**
     * Memproses pembayaran pesanan via AJAX
     * Mengupdate status dari pending ke success
     */
    public function bayar(Request $request, $id)
    {
        // 1. Cari data order terlebih dahulu
        $order = Order::findOrFail($id);

        // 2. Ambil pengaturan partner ini dari database SEBELUM melakukan validasi
        $pengaturan = \App\Models\PengaturanForm::where('partner_id', $order->partner_id)->first();
        $isKameraAktif = $pengaturan ? $pengaturan->bukti_pembayaran : true;

        // 3. Tentukan aturan validasi dasar
        $rules = [
            'uang_bayar' => 'required|numeric|min:' . $order->total_harga,
            'metode_bayar' => 'required|in:qris,transfer,cash',
        ];

        // 4. Sesuaikan aturan bukti bayar berdasarkan status toggle
        if ($isKameraAktif) {
            // Jika aktif, maka wajib upload gambar
            $rules['bukti_bayar'] = 'required_if:metode_bayar,qris,transfer|file|mimes:jpeg,png,jpg|max:2048';
        } else {
            // Jika mati, file opsional (nullable), tapi kalau dikirim formatnya harus gambar
            $rules['bukti_bayar'] = 'nullable|file|mimes:jpeg,png,jpg|max:2048';
        }

        // 5. Jalankan Validasi
        $request->validate($rules, [
            'uang_bayar.min' => 'Uang bayar tidak mencukupi tagihan.',
            'metode_bayar.in' => 'Metode pembayaran tidak valid.',
            'bukti_bayar.required_if' => 'Bukti pembayaran wajib diunggah untuk metode QRIS / Transfer.',
            'bukti_bayar.mimes' => 'Format file harus berupa gambar (jpeg, png, jpg).',
            'bukti_bayar.max' => 'Ukuran file terlalu besar.'
        ]);

        // 6. Jika lolos validasi, Lanjutkan Proses Pembayaran
        try {
            DB::beginTransaction();

            $kembalian = $request->uang_bayar - $order->total_harga;
            $namaFileBukti = null;

            // Proses upload bukti bayar jika ada file yang dikirim
            if ($request->hasFile('bukti_bayar')) {
                $file = $request->file('bukti_bayar');
                $namaFileBukti = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                // Disk 'local' root-nya sudah storage/app/private — cukup subfolder langsung
                $file->storeAs('bukti_bayar', $namaFileBukti);
            }

            // Update data pesanan
            $order->update([
                'uang_bayar'   => $request->uang_bayar,
                'metode_bayar' => $request->metode_bayar,
                'kembalian'    => $kembalian,
                'bukti_bayar'  => $namaFileBukti, // Akan bernilai null jika fitur dinonaktifkan
                'status'       => 'success'
            ]);

            DB::commit();

            // Bust cache laporan bisnis agar data langsung tampil tanpa tunggu TTL
            $this->bustLaporanCache($order->partner_id);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran Berhasil dikonfirmasi!',
                'data' => [
                    'nomor_pesanan' => $order->nomor_pesanan,
                    'kembalian'     => $kembalian
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }
    // Tambahkan ini di dalam class KasirController
public function update(Request $request, Order $order)
{
    try {
        DB::beginTransaction();
        $order->update([
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_whatsapp'    => $request->no_whatsapp,
            'no_meja'        => $request->no_meja,
            'keterangan'     => $request->keterangan,
        ]);
        DB::commit();
        return response()->json(['success' => true, 'message' => 'Pesanan berhasil diupdate']);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

public function destroy(Order $order)
    {
        try {
            DB::beginTransaction();

            // 1. Ubah status pesanan menjadi fail
            $order->update(['status' => 'fail']);

            // 2. Kembalikan stok untuk setiap item di dalam pesanan ini
            foreach ($order->orderDetails as $detail) {
                // Gunakan model Menu untuk mengambil data menu terkait
                $menu = Menu::find($detail->menu_id);
                
                // Pastikan menu ditemukan dan memiliki sistem stok (bukan null)
                if ($menu && !is_null($menu->stok_menu)) {
                    // Tambahkan kembali kuantitas yang dibatalkan ke stok menu
                    $menu->stok_menu += $detail->qty;
                    
                    // Jika sebelumnya menu habis (stok 0), kembalikan statusnya menjadi tersedia
                    if ($menu->stok_menu > 0) {
                        $menu->status_menu = 'tersedia';
                    }
                    
                    $menu->save();
                }
            }

            DB::commit();
            return response()->json([
                'success' => true, 
                'message' => 'Pesanan dibatalkan dan stok menu telah dikembalikan.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'Gagal membatalkan pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

public function printKitchenBluetooth(Order $order)
{
    // Eager load details dan menu
    $order->load('orderDetails.menu');
    
    return view('kasir.struk-kitchen-bt', compact('order'));
}

public function updateSound(Request $request)
    {
        $partnerId = Auth::user()->partner->id;
        
        $toggle = \App\Models\ToggleSound::updateOrCreate(
            ['partner_id' => $partnerId],
            ['is_sound_enabled' => $request->is_sound_enabled]
        );

        return response()->json(['success' => true, 'message' => 'Pengaturan suara disimpan']);
    }

    /**
     * Memproses sinkronisasi aksi kasir (Delete, Update, Pay) dari kondisi Offline
     */
    public function syncActions(Request $request)
    {
        // Pastikan menerima array kosong jika tidak ada data dari request
        $actions = $request->input('actions', []);

        if (empty($actions)) {
            return response()->json(['success' => true, 'message' => 'Tidak ada aksi untuk disinkronkan.']);
        }

        try {
            DB::beginTransaction();

            $partnerIdsToBust = [];

            foreach ($actions as $act) {
                $orderId = $act['order_id'] ?? null;
                $actionType = $act['action'] ?? null;
                $payload = $act['payload'] ?? []; // Pastikan ini array kosong jika null

                if (!$orderId || !$actionType) continue;

                // Jika pesanan aslinya adalah pesanan offline yang belum sinkron, 
                // ID-nya mengandung prefix 'offline-'. Kita skip karena datanya belum ada di DB Server.
                if (is_string($orderId) && str_starts_with($orderId, 'offline-')) {
                    continue; 
                }

                $order = Order::find($orderId);
                if (!$order) continue;

                // Ambil status dari DB dan jadikan huruf kecil semua untuk menghindari masalah Case Sensitive
                $dbStatus = strtolower($order->status);

                switch ($actionType) {
                    case 'delete':
                        // Logika Hapus (Ubah status ke fail dan kembalikan stok)
                        if ($dbStatus !== 'fail') {
                            $order->update(['status' => 'fail']);
                            
                            foreach ($order->orderDetails as $detail) {
                                $menu = Menu::find($detail->menu_id);
                                if ($menu && !is_null($menu->stok_menu)) {
                                    $menu->stok_menu += $detail->qty;
                                    if ($menu->stok_menu > 0) {
                                        $menu->status_menu = 'tersedia';
                                    }
                                    $menu->save();
                                }
                            }
                        }
                        break;

                    case 'pay':
                        // Logika Bayar (Ubah status ke success)
                        if ($dbStatus === 'pending') {
                            $uangBayar = $payload['uang_bayar'] ?? 0;
                            $kembalian = $uangBayar - $order->total_harga;
                            
                            $order->update([
                                'uang_bayar'   => $uangBayar,
                                'metode_bayar' => $payload['metode_bayar'] ?? 'cash',
                                'kembalian'    => $kembalian,
                                'status'       => 'success'
                            ]);

                            // Tandai partner ini untuk bust cache setelah commit
                            $partnerIdsToBust[] = $order->partner_id;
                        }
                        break;

                    case 'update':
                        // Logika Update data pesanan dan keranjang (cart)
                        if ($dbStatus === 'pending' && !empty($payload['cart'])) {
                            // 1. Kembalikan stok lama ke menu
                            foreach ($order->orderDetails as $oldDetail) {
                                $oldMenu = Menu::find($oldDetail->menu_id);
                                if ($oldMenu && !is_null($oldMenu->stok_menu)) {
                                    $oldMenu->stok_menu += $oldDetail->qty;
                                    $oldMenu->save();
                                }
                            }
                            
                            // Hapus detail pesanan yang lama
                            $order->orderDetails()->delete();

                            // 2. Hitung & Masukkan detail pesanan yang baru
                            $subTotal = 0;
                            foreach ($payload['cart'] as $item) {
                                $menu = Menu::find($item['id']);
                                if (!$menu) continue;

                                // Kurangi stok menu dengan kuantitas yang baru
                                if (!is_null($menu->stok_menu)) {
                                    $menu->stok_menu -= $item['qty'];
                                    $menu->stok_menu = max(0, $menu->stok_menu);
                                    if ($menu->stok_menu <= 0) $menu->status_menu = 'habis';
                                    $menu->save();
                                }

                                $hargaSatuan = $menu->harga + ($item['variant_harga'] ?? 0);
                                $subTotal += ($hargaSatuan * $item['qty']);

                                $order->orderDetails()->create([
                                    'menu_id' => $menu->id,
                                    'qty'     => $item['qty'],
                                    'harga'   => $hargaSatuan,
                                    'variant' => $item['variant_name'] ?? null
                                ]);
                            }

                            // 3. Hitung ulang diskon dari payload (BUG #4 fix)
                            $nominalDiskon = isset($payload['nominal_diskon']) ? (float) $payload['nominal_diskon'] : 0;
                            $jenisDiskon   = $payload['jenis_diskon'] ?? 'Rp';
                            $nilaiDiskon   = 0;

                            if ($nominalDiskon > 0) {
                                if ($jenisDiskon === '%') {
                                    $pct = min($nominalDiskon, 100);
                                    $nilaiDiskon = ($subTotal * $pct) / 100;
                                } else {
                                    $nilaiDiskon = min($nominalDiskon, $subTotal);
                                }
                            }

                            $totalHarga = $subTotal - $nilaiDiskon;

                            // 4. Update data utama pesanan pelanggan
                            $order->update([
                                'nama_pelanggan' => $payload['nama_pelanggan'] ?? $order->nama_pelanggan,
                                'no_meja'        => $payload['no_meja'] ?? $order->no_meja,
                                'no_whatsapp'    => $payload['no_whatsapp'] ?? $order->no_whatsapp,
                                'keterangan'     => $payload['keterangan'] ?? $order->keterangan,
                                'nominal_diskon' => $nominalDiskon,
                                'jenis_diskon'   => $jenisDiskon,
                                'total_harga'    => $totalHarga,
                            ]);
                        }
                        break;
                }
            }

            DB::commit();

            // Bust cache laporan untuk semua partner yang transaksinya berubah ke success
            foreach (array_unique($partnerIdsToBust) as $pid) {
                $this->bustLaporanCache($pid);
            }

            return response()->json(['success' => true, 'message' => 'Sinkronisasi berhasil.']);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Catat error ke file storage/logs/laravel.log agar mudah dilacak jika terjadi kegagalan
            Log::error('Kasir Sync Error: ' . $e->getMessage() . ' di baris ' . $e->getLine());
            
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sinkronisasi.'], 500);
        }
    }

    public function bulkKonfirmasi(Request $request)
    {
        $request->validate([
            'order_ids'   => 'required|array|min:1',
            'order_ids.*' => 'exists:orders,id'
        ]);

        try {
            DB::beginTransaction();

            $partnerId = Auth::user()->partner->id;

            // Pastikan hanya mengambil order yang valid, milik partner ini, dan statusnya pending
            $orders = Order::whereIn('id', $request->order_ids)
                ->where('partner_id', $partnerId)
                ->where('status', 'pending')
                ->get();

            if ($orders->isEmpty()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Tidak ada pesanan valid yang bisa dikonfirmasi.'
                ]);
            }

            foreach ($orders as $order) {
                $order->update([
                    'uang_bayar'   => $order->total_harga, // Otomatis dianggap uang pas
                    'metode_bayar' => 'cash',
                    'kembalian'    => 0,
                    'status'       => 'success'
                ]);
            }

            DB::commit();

            // Bust cache laporan bisnis untuk semua order yang baru saja dikonfirmasi
            if ($orders->isNotEmpty()) {
                $this->bustLaporanCache($orders->first()->partner_id);
            }

            return response()->json([
                'success' => true,
                'message' => count($orders) . ' pesanan berhasil dikonfirmasi secara massal!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk Confirm Error: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Gagal memproses konfirmasi massal: ' . $e->getMessage()
            ], 500);
        }
    }
}