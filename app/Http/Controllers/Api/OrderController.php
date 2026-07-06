<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // 1. AMBIL DAFTAR PESANAN PENDING
    public function index(Request $request)
    {
        try {
            $partnerId = $request->user()->partner->id;
            $orders = Order::with('orderDetails.menu')
                ->where('partner_id', $partnerId)
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();
            return response()->json(['success' => true, 'data' => $orders], 200);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // 2. SIMPAN PESANAN BARU (Online)
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'cart'           => 'required|array|min:1',
            'nominal_diskon' => 'nullable|numeric|min:0',
            'jenis_diskon'   => 'nullable|in:Rp,%',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $partnerId = Auth::user()->partner->id;
                $now = \Carbon\Carbon::now();

                $lastOrder = Order::where('partner_id', $partnerId)
                    ->whereYear('created_at', $now->year)
                    ->whereMonth('created_at', $now->month)
                    ->lockForUpdate()
                    ->orderBy('nomor_pesanan', 'desc')
                    ->first();
                $nextNumber = $lastOrder ? (int)$lastOrder->nomor_pesanan + 1 : 1;

                [$subTotal, $orderDetailsData] = $this->processCartForSync($cart);
                [$nominalDiskon, $jenisDiskon, $nilaiDiskon] = $this->calculateDiscount(
                    $request->nominal_diskon ?? 0, $request->jenis_diskon ?? 'Rp', $subTotal
                );

                $order = Order::create([
                    'partner_id'     => $partnerId,
                    'nomor_pesanan'  => $nextNumber,
                    'nama_pelanggan' => $request->nama_pelanggan,
                    'no_whatsapp'    => $request->no_whatsapp,
                    'no_meja'        => $request->no_meja,
                    'keterangan'     => $request->keterangan,
                    'total_harga'    => $subTotal - $nilaiDiskon,
                    'nominal_diskon' => $nominalDiskon,
                    'jenis_diskon'   => $jenisDiskon,
                    'status'         => 'pending',
                ]);

                foreach ($orderDetailsData as $detail) {
                    $order->orderDetails()->create($detail);
                }
                $order->load('orderDetails.menu');

                return response()->json([
                    'success' => true, 'message' => 'Pesanan berhasil disimpan', 'data' => $order
                ], 201);
            });
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // 3. EDIT PESANAN
    public function update(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $partnerId = Auth::user()->partner->id;
                $order = Order::where('partner_id', $partnerId)->findOrFail($id);

                $this->restoreStock($order);
                $order->orderDetails()->delete();

                [$subTotal, $newDetails] = $this->processCartForSync($cart);
                [$nominalDiskon, $jenisDiskon, $nilaiDiskon] = $this->calculateDiscount(
                    $request->nominal_diskon ?? 0, $request->jenis_diskon ?? 'Rp', $subTotal
                );

                $order->update([
                    'nama_pelanggan' => $request->nama_pelanggan,
                    'no_whatsapp'    => $request->no_whatsapp,
                    'no_meja'        => $request->no_meja,
                    'keterangan'     => $request->keterangan,
                    'total_harga'    => $subTotal - $nilaiDiskon,
                    'nominal_diskon' => $nominalDiskon,
                    'jenis_diskon'   => $jenisDiskon,
                ]);

                foreach ($newDetails as $detail) {
                    $order->orderDetails()->create($detail);
                }

                return response()->json(['success' => true, 'message' => "Pesanan #{$order->nomor_pesanan} Diperbarui!"]);
            });
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // 4. BATALKAN/HAPUS PESANAN
    public function destroy(Request $request, $id)
    {
        try {
            $order = Order::where('partner_id', $request->user()->partner->id)->findOrFail($id);
            foreach ($order->orderDetails as $detail) {
                $menu = Menu::find($detail->menu_id);
                if ($menu && !is_null($menu->stok_menu)) {
                    $menu->stok_menu += $detail->qty;
                    if ($menu->stok_menu > 0) $menu->status_menu = 'tersedia';
                    $menu->save();
                }
            }
            $order->update(['status' => 'fail']);
            return response()->json(['success' => true, 'message' => 'Pesanan dibatalkan & stok dikembalikan.']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 5. SYNC OFFLINE ORDERS — DIPERBAIKI LENGKAP
    // ─────────────────────────────────────────────────────────────────────────
    public function syncOffline(Request $request)
    {
        $request->validate(['orders' => 'required|array']);

        try {
            return DB::transaction(function () use ($request) {
                $partnerId = Auth::user()->partner->id;
                $syncedIds = [];

                foreach ($request->orders as $offlineOrder) {

                    // ── KASUS A: Pesanan online yang diedit saat offline ──────────────
                    if (!empty($offlineOrder['id'])) {
                        $order = Order::where('partner_id', $partnerId)
                            ->find($offlineOrder['id']);

                        if ($order && $order->status === 'pending') {
                            $this->restoreStock($order);
                            $order->orderDetails()->delete();

                            $cart = $offlineOrder['cart'] ?? $offlineOrder['items'] ?? [];
                            [$subTotal, $newDetails] = $this->processCart($cart);
                            [$nominalDiskon, $jenisDiskon, $nilaiDiskon] = $this->calculateDiscount(
                                $offlineOrder['nominal_diskon'] ?? 0,
                                $offlineOrder['jenis_diskon']   ?? 'Rp',
                                $subTotal
                            );

                            $order->update([
                                'nama_pelanggan' => $offlineOrder['nama_pelanggan'],
                                'no_whatsapp'    => $offlineOrder['no_whatsapp']    ?? $order->no_whatsapp,
                                'no_meja'        => $offlineOrder['no_meja']        ?? $order->no_meja,
                                'keterangan'     => $offlineOrder['keterangan']     ?? $order->keterangan,
                                'total_harga'    => $subTotal - $nilaiDiskon,
                                'nominal_diskon' => $nominalDiskon,
                                'jenis_diskon'   => $jenisDiskon,
                            ]);

                            foreach ($newDetails as $detail) {
                                $order->orderDetails()->create($detail);
                            }

                            $syncedIds[] = $offlineOrder['offline_id'] ?? $offlineOrder['id'];
                            continue;
                        }
                    }

                    // ── KASUS B: Pesanan BARU yang dibuat saat offline ────────────────

                    $offlineId = $offlineOrder['offline_id'] ?? null;

                    // Cegah duplikasi sync
                    if ($offlineId && Order::where('partner_id', $partnerId)
                            ->where('offline_id', $offlineId)->exists()) {
                        $syncedIds[] = $offlineId;
                        continue;
                    }

                    // Lewati jika data tidak lengkap
                    $cart = $offlineOrder['cart'] ?? $offlineOrder['items'] ?? [];
                    if (empty($offlineOrder['nama_pelanggan']) || empty($cart)) {
                        continue;
                    }

                    $now = \Carbon\Carbon::now();
                    $lastOrder = Order::where('partner_id', $partnerId)
                        ->whereYear('created_at', $now->year)
                        ->whereMonth('created_at', $now->month)
                        ->lockForUpdate()
                        ->orderBy('nomor_pesanan', 'desc')
                        ->first();
                    $nextNumber = $lastOrder ? (int)$lastOrder->nomor_pesanan + 1 : 1;

                    [$subTotal, $orderDetailsData] = $this->processCart($cart);
                    [$nominalDiskon, $jenisDiskon, $nilaiDiskon] = $this->calculateDiscount(
                        $offlineOrder['nominal_diskon'] ?? 0,
                        $offlineOrder['jenis_diskon']   ?? 'Rp',
                        $subTotal
                    );

                    // Pakai created_at dari Flutter agar urutan waktu akurat
                    $createdAt = isset($offlineOrder['created_at'])
                        ? \Carbon\Carbon::parse($offlineOrder['created_at'])
                        : $now;

                    $order = Order::create([
                        'partner_id'     => $partnerId,
                        'offline_id'     => $offlineId,
                        'nomor_pesanan'  => $nextNumber,
                        'nama_pelanggan' => $offlineOrder['nama_pelanggan'],
                        'no_whatsapp'    => $offlineOrder['no_whatsapp'] ?? null,
                        'no_meja'        => $offlineOrder['no_meja']     ?? null,
                        'keterangan'     => $offlineOrder['keterangan']  ?? null,
                        'total_harga'    => $subTotal - $nilaiDiskon,
                        'nominal_diskon' => $nominalDiskon,
                        'jenis_diskon'   => $jenisDiskon,
                        'status'         => 'pending',
                        'created_at'     => $createdAt,
                        'updated_at'     => $createdAt,
                    ]);

                    foreach ($orderDetailsData as $detail) {
                        $order->orderDetails()->create($detail);
                    }

                    $syncedIds[] = $offlineId ?? $order->id;
                }

                return response()->json([
                    'success'    => true,
                    'message'    => 'Sync berhasil',
                    'synced_ids' => $syncedIds,
                ]);
            });
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal sync: ' . $e->getMessage()
            ], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // HELPER METHODS
    // ─────────────────────────────────────────────────────────────────────────

   // Versi STRICT — untuk store() dan update()
// Melempar exception jika menu tidak ditemukan
private function processCart(array $cart): array
{
    $subTotal = 0;
    $orderDetailsData = [];

    foreach ($cart as $item) {
        $menu = Menu::findOrFail($item['id']); // ← tetap findOrFail

        if (!is_null($menu->stok_menu)) {
            if ($item['qty'] > $menu->stok_menu) {
                throw new \Exception("Stok {$menu->nama_menu} tidak cukup.");
            }
            $menu->stok_menu -= $item['qty'];
            $menu->status_menu = ($menu->stok_menu <= 0) ? 'habis' : 'tersedia';
            $menu->save();
        }

        $hargaSatuan = $menu->harga + ($item['variant_harga'] ?? 0);
        $subTotal   += ($hargaSatuan * $item['qty']);

        $orderDetailsData[] = [
            'menu_id' => $menu->id,
            'qty'     => $item['qty'],
            'harga'   => $hargaSatuan,
            'variant' => $item['variant_name'] ?? null,
        ];
    }

    return [$subTotal, $orderDetailsData];
}

// Versi TOLERAN — khusus untuk syncOffline()
// Lewati item jika menu sudah dihapus, jangan batalkan sync
private function processCartForSync(array $cart): array
{
    $subTotal = 0;
    $orderDetailsData = [];

    foreach ($cart as $item) {
        $menu = Menu::find($item['id']); // ← find, bukan findOrFail

        if (!$menu) {
            // Menu sudah dihapus dari database — lewati item ini
            // Pesanan tetap diproses dengan item yang tersisa
            continue;
        }

        if (!is_null($menu->stok_menu)) {
            if ($item['qty'] > $menu->stok_menu) {
                // Saat sync, potong maksimal stok yang tersedia
                // daripada batalkan seluruh pesanan
                $item['qty'] = $menu->stok_menu;
                if ($item['qty'] <= 0) continue;
            }
            $menu->stok_menu -= $item['qty'];
            $menu->status_menu = ($menu->stok_menu <= 0) ? 'habis' : 'tersedia';
            $menu->save();
        }

        $hargaSatuan = $menu->harga + ($item['variant_harga'] ?? 0);
        $subTotal   += ($hargaSatuan * $item['qty']);

        $orderDetailsData[] = [
            'menu_id' => $menu->id,
            'qty'     => $item['qty'],
            'harga'   => $hargaSatuan,
            'variant' => $item['variant_name'] ?? null,
        ];
    }

    // Jika setelah difilter tidak ada item valid sama sekali, lempar exception
    if (empty($orderDetailsData)) {
        throw new \Exception("Semua item dalam pesanan ini sudah tidak tersedia.");
    }

    return [$subTotal, $orderDetailsData];
}

    private function calculateDiscount($nominalDiskon, string $jenisDiskon, float $subTotal): array
    {
        $nominalDiskon = (float) ($nominalDiskon ?? 0);
        $nilaiDiskon   = 0;

        if ($nominalDiskon > 0) {
            if ($jenisDiskon === '%') {
                $nominalDiskon = min($nominalDiskon, 100);
                $nilaiDiskon   = ($subTotal * $nominalDiskon) / 100;
            } else {
                $nominalDiskon = min($nominalDiskon, $subTotal);
                $nilaiDiskon   = $nominalDiskon;
            }
        }

        return [$nominalDiskon, $jenisDiskon, $nilaiDiskon];
    }

    private function restoreStock(Order $order): void
    {
        foreach ($order->orderDetails as $detail) {
            $menu = Menu::find($detail->menu_id);
            if ($menu && !is_null($menu->stok_menu)) {
                $menu->stok_menu += $detail->qty;
                if ($menu->stok_menu > 0) $menu->status_menu = 'tersedia';
                $menu->save();
            }
        }
    }
}
