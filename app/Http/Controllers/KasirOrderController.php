<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PengaturanForm;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KasirOrderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $partner = \App\Models\Partner::where('user_id', $user->id)->first();

        if (!$partner) {
            return "Error: User ID {$user->id} tidak ditemukan di tabel partners.";
        }

        $partnerId = $partner->id;
        $pengaturan = PengaturanForm::firstOrCreate(
            ['partner_id' => $partnerId],
            [
                'nama' => true,
                'whatsapp' => true,
                'meja' => true,
                'keterangan' => true,
            ]
        );

        $menus = Menu::with('variants')
                     ->where('partner_id', $partnerId)
                     ->whereIn('status_menu', ['tersedia', 'habis'])
                     ->orderBy('created_at', 'desc')
                     ->get();

        $editOrder = null;
        if ($request->has('edit_id')) {
            $editOrder = Order::with('orderDetails.menu')->find($request->edit_id);
        }

        return view('kasir.order', compact('menus', 'editOrder', 'partnerId', 'pengaturan'));
    }

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
                $user = Auth::user();
                $partner = \App\Models\Partner::where('user_id', $user->id)->first();

                if (!$partner) {
                    return response()->json(['success' => false, 'message' => 'Akun belum terhubung.'], 404);
                }

                $partnerId = $partner->id;
                $now = \Carbon\Carbon::now();
                
                $lastOrder = Order::where('partner_id', $partnerId)
                    ->whereYear('created_at', $now->year)
                    ->whereMonth('created_at', $now->month)
                    ->lockForUpdate() 
                    ->orderBy('nomor_pesanan', 'desc')
                    ->first();

                $nextNumber = $lastOrder ? (int)$lastOrder->nomor_pesanan + 1 : 1;

                $subTotal = 0;
                $orderDetailsData = [];
                
                foreach ($request->cart as $item) {
                    $menu = Menu::findOrFail($item['id']);

                    if (!is_null($menu->stok_menu)) {
                        if ($item['qty'] > $menu->stok_menu) {
                            throw new \Exception("Stok menu {$menu->nama_menu} tidak mencukupi.");
                        }
                        $menu->stok_menu -= $item['qty'];
                        if ($menu->stok_menu <= 0) {
                            $menu->stok_menu = 0;
                            $menu->status_menu = 'habis';
                        }
                        $menu->save();
                    }
                    
                    $hargaVariant = isset($item['variant_harga']) ? $item['variant_harga'] : 0;
                    $hargaSatuan = $menu->harga + $hargaVariant;
                    
                    $subTotal += ($hargaSatuan * $item['qty']);
                    
                    $orderDetailsData[] = [
                        'menu_id' => $menu->id,
                        'qty'     => $item['qty'],
                        'harga'   => $hargaSatuan,
                        'variant' => $item['variant_name'] ?? null
                    ];
                }

                // --- LOGIKA DISKON ---
                $nominalDiskon = isset($request->nominal_diskon) ? (float) $request->nominal_diskon : 0;
                $jenisDiskon   = $request->jenis_diskon ?? 'Rp';
                $nilaiDiskon   = 0;

                if ($nominalDiskon > 0) {
                    if ($jenisDiskon === '%') {
                        if ($nominalDiskon > 100) $nominalDiskon = 100; // Maksimal 100%
                        $nilaiDiskon = ($subTotal * $nominalDiskon) / 100;
                    } else {
                        if ($nominalDiskon > $subTotal) $nominalDiskon = $subTotal; // Maksimal nominal
                        $nilaiDiskon = $nominalDiskon;
                    }
                }

                $totalBayarAkhir = $subTotal - $nilaiDiskon;

                $order = Order::create([
                    'partner_id'     => $partnerId, 
                    'nomor_pesanan'  => $nextNumber,
                    'nama_pelanggan' => $request->nama_pelanggan,
                    'no_whatsapp'    => $request->no_whatsapp,
                    'no_meja'        => $request->no_meja,
                    'keterangan'     => $request->keterangan,
                    'total_harga'    => $totalBayarAkhir, // Harga setelah diskon
                    'nominal_diskon' => $nominalDiskon,
                    'jenis_diskon'   => $jenisDiskon,
                    'status'         => 'pending',
                    'metode_bayar'   => $request->metode_bayar ?? null,
                    'uang_bayar'     => $request->uang_bayar ?? null,
                    'kembalian'      => $request->kembalian ?? null,
                ]);

                foreach ($orderDetailsData as $detail) {
                    $order->orderDetails()->create($detail);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil disimpan',
                    'data'    => [
                        'id' => $order->id,
                        'nomor_pesanan' => $order->nomor_pesanan,
                        'uuid' => $order->uuid
                    ]
                ]);
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi Kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string',
            'cart'           => 'required|array|min:1',
            'nominal_diskon' => 'nullable|numeric|min:0',
            'jenis_diskon'   => 'nullable|in:Rp,%',
        ]);

        try {
            return DB::transaction(function () use ($request, $order) {
                $user = Auth::user();
                $partner = \App\Models\Partner::where('user_id', $user->id)->first();
                
                if (!$partner || $order->partner_id != $partner->id) {
                        return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
                    }

                // Kembalikan Stok Lama
                foreach ($order->orderDetails as $oldDetail) {
                    $oldMenu = Menu::find($oldDetail->menu_id);
                    if ($oldMenu && !is_null($oldMenu->stok_menu)) {
                        $oldMenu->stok_menu += $oldDetail->qty;
                        if ($oldMenu->stok_menu > 0) {
                            $oldMenu->status_menu = 'tersedia';
                        }
                        $oldMenu->save();
                    }
                }

                $order->orderDetails()->delete();

                $subTotal = 0;
                $newDetails = [];

                foreach ($request->cart as $item) {
                    $menu = Menu::findOrFail($item['id']);

                    if (!is_null($menu->stok_menu)) {
                        if ($item['qty'] > $menu->stok_menu) {
                            throw new \Exception("Stok menu {$menu->nama_menu} tidak mencukupi.");
                        }

                        $menu->stok_menu -= $item['qty'];
                        if ($menu->stok_menu <= 0) {
                            $menu->stok_menu = 0;
                            $menu->status_menu = 'habis';
                        } else {
                            $menu->status_menu = 'tersedia';
                        }
                        $menu->save();
                    }

                    $hargaVariant = isset($item['variant_harga']) ? $item['variant_harga'] : 0;
                    $hargaSatuan = $menu->harga + $hargaVariant;
                    
                    $subTotal += ($hargaSatuan * $item['qty']);
                    
                    $newDetails[] = [
                        'menu_id' => $menu->id,
                        'qty'     => $item['qty'],
                        'harga'   => $hargaSatuan,
                        'variant' => $item['variant_name'] ?? null
                    ];
                }

                // --- LOGIKA DISKON ---
                $nominalDiskon = isset($request->nominal_diskon) ? (float) $request->nominal_diskon : 0;
                $jenisDiskon   = $request->jenis_diskon ?? 'Rp';
                $nilaiDiskon   = 0;

                if ($nominalDiskon > 0) {
                    if ($jenisDiskon === '%') {
                        if ($nominalDiskon > 100) $nominalDiskon = 100;
                        $nilaiDiskon = ($subTotal * $nominalDiskon) / 100;
                    } else {
                        if ($nominalDiskon > $subTotal) $nominalDiskon = $subTotal;
                        $nilaiDiskon = $nominalDiskon;
                    }
                }

                $totalBayarAkhir = $subTotal - $nilaiDiskon;

                $order->update([
                    'nama_pelanggan' => $request->nama_pelanggan,
                    'no_whatsapp'    => $request->no_whatsapp,
                    'no_meja'        => $request->no_meja,
                    'keterangan'     => $request->keterangan,
                    'total_harga'    => $totalBayarAkhir,
                    'nominal_diskon' => $nominalDiskon,
                    'jenis_diskon'   => $jenisDiskon,
                ]);

                foreach ($newDetails as $detail) {
                    $order->orderDetails()->create($detail);
                }

                return response()->json([
                    'success' => true, 
                    'message' => 'Pesanan #' . $order->nomor_pesanan . ' Berhasil Diperbarui!',
                    'data' => [
                        'id' => $order->id,
                        'uuid' => $order->uuid
                    ]
                ]);
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal update: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Order $order)
    {
        try {
            $order->update(['status' => 'fail']);
            return response()->json(['success' => true, 'message' => 'Pesanan dibatalkan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function syncOffline(Request $request)
    {
        $request->validate(['orders' => 'required|array']);

        try {
            return DB::transaction(function () use ($request) {
                $user = Auth::user();
                $partner = \App\Models\Partner::where('user_id', $user->id)->first();
                $partnerId = $partner->id;
                $now = \Carbon\Carbon::now();
                $syncedIds = [];

                foreach ($request->orders as $offlineOrder) {
                    if (isset($offlineOrder['id'])) { 
                        $exists = Order::where('offline_id', $offlineOrder['id'])->first();
                        if ($exists) continue; 
                    }

                    $lastOrder = Order::where('partner_id', $partnerId)
                        ->whereYear('created_at', $now->year)
                        ->whereMonth('created_at', $now->month)
                        ->lockForUpdate()
                        ->orderBy('nomor_pesanan', 'desc')
                        ->first();

                    $nextNumber = $lastOrder ? (int)$lastOrder->nomor_pesanan + 1 : 1;

                    $subTotal = 0;
                    $orderDetailsData = [];

                    foreach ($offlineOrder['cart'] as $item) {
                        $menu = Menu::find($item['id']);
                        if (!$menu) continue; 

                        if (!is_null($menu->stok_menu)) {
                            $menu->stok_menu -= $item['qty'];
                            if ($menu->stok_menu <= 0) {
                                $menu->stok_menu = 0;
                                $menu->status_menu = 'habis';
                            }
                            $menu->save();
                        }

                        $hargaSatuan = $menu->harga + ($item['variant_harga'] ?? 0);
                        $subTotal += ($hargaSatuan * $item['qty']);

                        $orderDetailsData[] = [
                            'menu_id' => $menu->id,
                            'qty'     => $item['qty'],
                            'harga'   => $hargaSatuan,
                            'variant' => $item['variant_name'] ?? null
                        ];
                    }

                    if(empty($orderDetailsData)) continue; 

                    // --- LOGIKA DISKON ---
                    $nominalDiskon = isset($offlineOrder['nominal_diskon']) ? (float) $offlineOrder['nominal_diskon'] : 0;
                    $jenisDiskon   = $offlineOrder['jenis_diskon'] ?? 'Rp';
                    $nilaiDiskon   = 0;

                    if ($nominalDiskon > 0) {
                        if ($jenisDiskon === '%') {
                            if ($nominalDiskon > 100) $nominalDiskon = 100;
                            $nilaiDiskon = ($subTotal * $nominalDiskon) / 100;
                        } else {
                            if ($nominalDiskon > $subTotal) $nominalDiskon = $subTotal;
                            $nilaiDiskon = $nominalDiskon;
                        }
                    }

                    $totalBayarAkhir = $subTotal - $nilaiDiskon;

                    $order = Order::create([
                        'partner_id'     => $partnerId,
                        'offline_id'     => $offlineOrder['id'] ?? null,
                        'nomor_pesanan'  => $nextNumber,
                        'nama_pelanggan' => $offlineOrder['nama_pelanggan'],
                        'no_whatsapp'    => $offlineOrder['no_whatsapp'] ?? null,
                        'no_meja'        => $offlineOrder['no_meja'] ?? null,
                        'keterangan'     => ($offlineOrder['keterangan'] ?? '') . ' (Order via PWA Offline)',
                        'total_harga'    => $totalBayarAkhir,
                        'nominal_diskon' => $nominalDiskon,
                        'jenis_diskon'   => $jenisDiskon,
                        'status'         => 'pending',
                    ]);

                    foreach ($orderDetailsData as $detail) {
                        $order->orderDetails()->create($detail);
                    }

                    $syncedIds[] = $order->id;
                }

                return response()->json([
                    'success' => true,
                    'message' => count($syncedIds) . ' pesanan berhasil disinkronisasi.',
                    'synced_ids' => $syncedIds
                ]);
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal sync: ' . $e->getMessage()], 500);
        }
    }
}