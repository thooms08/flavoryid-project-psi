<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class KasirOrderHistoryController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil partner_id dari user yang sedang login
        $partnerId = Auth::user()->partner->id;

        // 2. Base Query (Hanya data milik partner ini, status success, dan Eager Loading)
        $query = Order::with(['orderDetails.menu'])
                      ->where('partner_id', $partnerId) // KUNCI KEAMANAN DI SINI
                      ->where('status', 'success')
                      ->latest();

        // 3. Filter Search (Nama, WA, No Pesanan)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_pesanan', 'like', "%$search%")
                  ->orWhere('nama_pelanggan', 'like', "%$search%")
                  ->orWhere('no_whatsapp', 'like', "%$search%");
            });
        }

        // 4. Filter Range Tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }

        // 5. Hitung Statistik (Sebelum Pagination)
        $stats = [
            'total_transaksi' => $query->count(),
            'total_pendapatan' => $query->sum('total_harga')
        ];

        // 6. Pagination / Limit
        $limit = $request->get('limit', 30);
        if ($limit == 'all') {
            $orders = $query->get();
        } else {
            $orders = $query->paginate($limit)->withQueryString();
        }

        // 7. Response untuk AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('kasir.partials.order-cards', compact('orders'))->render(),
                'stats' => [
                    'total_transaksi' => number_format($stats['total_transaksi']),
                    'total_pendapatan' => 'Rp ' . number_format($stats['total_pendapatan'], 0, ',', '.')
                ],
                'hasMore' => $orders instanceof \Illuminate\Pagination\LengthAwarePaginator ? $orders->hasMorePages() : false
            ]);
        }

        return view('kasir.riwayat-order', compact('orders', 'stats'));
    }

    public function showQrStruk($uuid)
    {
        // 1. Cari Order HANYA berdasarkan UUID (Karena yang buka ini Pelanggan, bukan Kasir yang sedang login)
        $order = \App\Models\Order::with(['orderDetails.menu'])
                      ->where('uuid', $uuid)
                      ->firstOrFail();

        // 2. Cek & Generate Kode Struk
        if (empty($order->kode_struk)) {
            do {
                $randomCode = \Illuminate\Support\Str::lower(\Illuminate\Support\Str::random(6));
                $exists = \App\Models\Order::where('kode_struk', $randomCode)->exists();
            } while ($exists);

            $order->kode_struk = $randomCode;
            $order->save();
        }

        // 3. Ambil informasi outlet berdasarkan partner_id yang nempel di data $order
        $outlet = \Illuminate\Support\Facades\DB::table('informasi_outlet')
                    ->where('partner_id', $order->partner_id)
                    ->first();

        // 4. Ambil catatan struk
        $activeNote = \App\Models\Note::where('partner_id', $order->partner_id)
                                      ->where('status', 'aktif')
                                      ->first();

        $teksCatatan = $activeNote ? $activeNote->note : "Terima kasih atas kunjungan Anda";

        return view('kasir.qr-struk', compact('order', 'outlet', 'teksCatatan'));
    }

    public function sendWhatsapp($uuid)
    {
        $partnerId = Auth::user()->partner->id;

        $order = Order::where('uuid', $uuid)
                      ->where('partner_id', $partnerId) // Keamanan tambahan
                      ->firstOrFail();
        
        // 1. Ambil nomor WA pelanggan
        $no_hp = $order->no_whatsapp;

        // 2. Sanitasi nomor (Ubah 08... menjadi 628...)
        if (str_starts_with($no_hp, '0')) {
            $no_hp = '62' . substr($no_hp, 1);
        }
        // Hapus karakter non-digit jika ada (spasi, strip, dll)
        $no_hp = preg_replace('/[^0-9]/', '', $no_hp);

        // 3. Buat Tautan Struk Digital
        $urlStruk = route('kasir.qr-struk', $order->uuid);

        // 4. Susun Pesan
        $pesan = "Halo *{$order->nama_pelanggan}*, \n\nTerima kasih telah berkunjung. Berikut adalah struk digital untuk pesanan *#{$order->nomor_pesanan}* Anda: \n\n{$urlStruk}\n\nSemoga hari Anda menyenangkan!";

        // 5. Redirect ke API WhatsApp
        $waUrl = "https://api.whatsapp.com/send?phone={$no_hp}&text=" . urlencode($pesan);

        return redirect()->away($waUrl);
    }

    public function printStruk($uuid)
    {
        $partnerId = Auth::user()->partner->id;

        $order = \App\Models\Order::with(['orderDetails.menu'])
                      ->where('uuid', $uuid)
                      ->where('partner_id', $partnerId) // Keamanan tambahan
                      ->firstOrFail();

        // Pastikan kode struk terisi sebelum dicetak
        if (empty($order->kode_struk)) {
            do {
                $randomCode = \Illuminate\Support\Str::lower(\Illuminate\Support\Str::random(6));
                $exists = \App\Models\Order::where('kode_struk', $randomCode)->exists();
            } while ($exists);

            $order->kode_struk = $randomCode;
            $order->save();
        }

        // Ambil informasi outlet HANYA untuk partner ini
        $outlet = \Illuminate\Support\Facades\DB::table('informasi_outlet')
                    ->where('partner_id', $partnerId)
                    ->first();

        // 1. Ambil catatan struk yang statusnya aktif untuk partner ini
        $activeNote = \App\Models\Note::where('partner_id', $order->partner_id)
                                      ->where('status', 'aktif')
                                      ->first();

        // 2. Tentukan teks yang akan dicetak (Gunakan note aktif, jika tidak ada gunakan default)
        $teksCatatan = $activeNote ? $activeNote->note : "Terima Kasih Atas Kunjungan Anda";

        // Kirim variabel $teksCatatan ke view struk-usb
        return view('kasir.struk-usb', compact('order', 'outlet', 'teksCatatan'));
    }

    public function printBluetooth($uuid)
    {
        $partnerId = Auth::user()->partner->id;

        $order = \App\Models\Order::with(['orderDetails.menu'])
                      ->where('uuid', $uuid)
                      ->where('partner_id', $partnerId) // Keamanan tambahan
                      ->firstOrFail();

        // Pastikan kode struk tersedia
        if (empty($order->kode_struk)) {
            do {
                $randomCode = \Illuminate\Support\Str::lower(\Illuminate\Support\Str::random(6));
                $exists = \App\Models\Order::where('kode_struk', $randomCode)->exists();
            } while ($exists);
            $order->kode_struk = $randomCode;
            $order->save();
        }

        // Ambil informasi outlet HANYA untuk partner ini
        $outlet = \Illuminate\Support\Facades\DB::table('informasi_outlet')
                    ->where('partner_id', $partnerId)
                    ->first();

        $activeNote = \App\Models\Note::where('partner_id', $order->partner_id)
                                      ->where('status', 'aktif')
                                      ->first();
                                      
        $teksCatatan = $activeNote ? $activeNote->note : "Terimakasih Atas Kunjungan Anda";
        
        return view('kasir.struk-bt', compact('order', 'outlet', 'teksCatatan'));
    }
}