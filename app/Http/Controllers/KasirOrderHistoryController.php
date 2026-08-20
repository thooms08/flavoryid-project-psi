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
    /**
     * Pastikan order memiliki kode_struk.
     * Di-extract agar printStruk dan printBluetooth tidak duplikat kode yang sama.
     */
    private function ensureKodeStruk(Order $order): void
    {
        if (!empty($order->kode_struk)) {
            return;
        }

        // Generate kode unik — loop maksimal 10 kali untuk menghindari potensi infinite loop
        $attempts = 0;
        do {
            $kode   = Str::lower(Str::random(8));
            $exists = Order::where('kode_struk', $kode)->exists();
            $attempts++;
        } while ($exists && $attempts < 10);

        $order->kode_struk = $kode;
        $order->save();
    }

    /**
     * Ambil outlet + catatan struk untuk partner tertentu — 2 query ringan.
     */
    private function getStrukAssets(int $partnerId): array
    {
        $outlet = DB::table('informasi_outlet')
            ->where('partner_id', $partnerId)
            ->first();

        $activeNote = Note::where('partner_id', $partnerId)
            ->where('status', 'aktif')
            ->value('note'); // Ambil nilai kolom saja, lebih ringan dari ->first()

        return [
            'outlet'       => $outlet,
            'teksCatatan'  => $activeNote ?? 'Terima Kasih Atas Kunjungan Anda',
        ];
    }

    public function index(Request $request)
    {
        $partnerId = Auth::user()->partner->id;

        $query = Order::with(['orderDetails.menu'])
                      ->where('partner_id', $partnerId)
                      ->where('status', 'success')
                      ->orderBy('created_at', 'desc'); // Pastikan terbaru di atas

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_pesanan', 'like', "%$search%")
                  ->orWhere('nama_pelanggan', 'like', "%$search%")
                  ->orWhere('no_whatsapp', 'like', "%$search%");
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end   = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }

        $limit = $request->get('limit', 30);

        if ($limit == 'all') {
            $orders = $query->get();
            $totalTransaksi = $orders->count(); // sudah di memory, gratis
        } else {
            $orders = $query->paginate($limit)->withQueryString();
            // LengthAwarePaginator sudah menyimpan total dari query COUNT-nya sendiri
            // — tidak perlu query COUNT terpisah lagi
            $totalTransaksi = $orders->total();
        }

        $stats = ['total_transaksi' => $totalTransaksi];

        if ($request->ajax()) {
            return response()->json([
                'html'    => view('kasir.partials.order-history-cards', compact('orders'))->render(),
                'stats'   => [
                    'total_transaksi' => number_format($stats['total_transaksi']),
                ],
                'hasMore' => $orders instanceof \Illuminate\Pagination\LengthAwarePaginator
                                ? $orders->hasMorePages()
                                : false,
            ]);
        }

        return view('kasir.riwayat-order', compact('orders', 'stats'));
    }

    public function showQrStruk($uuid)
    {
        $order = Order::with(['orderDetails.menu'])
                      ->where('uuid', $uuid)
                      ->firstOrFail();

        $this->ensureKodeStruk($order);

        $assets = $this->getStrukAssets($order->partner_id);

        return view('kasir.qr-struk', [
            'order'       => $order,
            'outlet'      => $assets['outlet'],
            'teksCatatan' => $assets['teksCatatan'],
        ]);
    }

    public function sendWhatsapp($uuid)
    {
        $partnerId = Auth::user()->partner->id;

        $order = Order::where('uuid', $uuid)
                      ->where('partner_id', $partnerId)
                      ->firstOrFail();

        $no_hp = $order->no_whatsapp;
        if (str_starts_with($no_hp, '0')) {
            $no_hp = '62' . substr($no_hp, 1);
        }
        $no_hp = preg_replace('/[^0-9]/', '', $no_hp);

        $urlStruk = route('kasir.qr-struk', $order->uuid);
        $pesan    = "Halo *{$order->nama_pelanggan}*, \n\nTerima kasih telah berkunjung. " .
                    "Berikut adalah struk digital untuk pesanan *#{$order->nomor_pesanan}* Anda: " .
                    "\n\n{$urlStruk}\n\nSemoga hari Anda menyenangkan!";

        return redirect()->away("https://api.whatsapp.com/send?phone={$no_hp}&text=" . urlencode($pesan));
    }

    public function printStruk($uuid)
    {
        $partnerId = Auth::user()->partner->id;

        $order = Order::with(['orderDetails.menu'])
                      ->where('uuid', $uuid)
                      ->where('partner_id', $partnerId)
                      ->firstOrFail();

        $this->ensureKodeStruk($order);

        $assets = $this->getStrukAssets($partnerId);

        return view('kasir.struk-usb', [
            'order'       => $order,
            'outlet'      => $assets['outlet'],
            'teksCatatan' => $assets['teksCatatan'],
        ]);
    }

    public function printBluetooth($uuid)
    {
        $partnerId = Auth::user()->partner->id;

        $order = Order::with(['orderDetails.menu'])
                      ->where('uuid', $uuid)
                      ->where('partner_id', $partnerId)
                      ->firstOrFail();

        $this->ensureKodeStruk($order);

        $assets = $this->getStrukAssets($partnerId);

        return view('kasir.struk-bt', [
            'order'       => $order,
            'outlet'      => $assets['outlet'],
            'teksCatatan' => $assets['teksCatatan'],
        ]);
    }
}
