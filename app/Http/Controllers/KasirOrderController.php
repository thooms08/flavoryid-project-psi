<?php
namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PengaturanForm;
use App\Models\Menu;
use App\Models\Voucher;
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

    // ─── Helper: hitung nilai voucher dari subtotal ─────────────────────────
    private function hitungVoucher(int $partnerId, ?string $kode, float $subTotal): array
    {
        if (!$kode) {
            return ['kode_voucher' => null, 'nilai_voucher' => 0];
        }

        $kode = strtoupper(trim($kode));

        $voucher = Voucher::where('partner_id', $partnerId)
            ->where('kode_voucher', $kode)
            ->where('is_active', true)
            ->first();

        if (!$voucher) {
            return ['kode_voucher' => null, 'nilai_voucher' => 0];
        }

        $nilaiVoucher = 0;
        if ($voucher->jenis_potongan === '%') {
            $persen = min((float) $voucher->nominal_potongan, 100);
            $nilaiVoucher = ($subTotal * $persen) / 100;
        } else {
            $nilaiVoucher = min((float) $voucher->nominal_potongan, $subTotal);
        }

        return [
            'kode_voucher' => $kode,
            'nilai_voucher' => round($nilaiVoucher, 2),
        ];
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'cart'           => 'required|array|min:1',
            'nominal_diskon' => 'nullable|numeric|min:0',
            'jenis_diskon'   => 'nullable|in:Rp,%',
            'kode_voucher'   => 'nullable|string|size:6',
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
                        if ($nominalDiskon > 100) $nominalDiskon = 100;
                        $nilaiDiskon = ($subTotal * $nominalDiskon) / 100;
                    } else {
                        if ($nominalDiskon > $subTotal) $nominalDiskon = $subTotal;
                        $nilaiDiskon = $nominalDiskon;
                    }
                }

                // --- LOGIKA VOUCHER ---
                $afterDiskon = $subTotal - $nilaiDiskon;
                $voucherData = $this->hitungVoucher($partnerId, $request->kode_voucher, $afterDiskon);
                $totalBayarAkhir = $afterDiskon - $voucherData['nilai_voucher'];

                $order = Order::create([
                    'partner_id'     => $partnerId,
                    'nomor_pesanan'  => $nextNumber,
                    'nama_pelanggan' => $request->nama_pelanggan,
                    'no_whatsapp'    => $request->no_whatsapp,
                    'no_meja'        => $request->no_meja,
                    'keterangan'     => $request->keterangan,
                    'total_harga'    => $totalBayarAkhir,
                    'nominal_diskon' => $nominalDiskon,
                    'jenis_diskon'   => $jenisDiskon,
                    'kode_voucher'   => $voucherData['kode_voucher'],
                    'nilai_voucher'  => $voucherData['nilai_voucher'] > 0 ? $voucherData['nilai_voucher'] : null,
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
                        'id'            => $order->id,
                        'nomor_pesanan' => $order->nomor_pesanan,
                        'uuid'          => $order->uuid
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
            'kode_voucher'   => 'nullable|string|size:6',
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

                // --- LOGIKA VOUCHER ---
                $afterDiskon = $subTotal - $nilaiDiskon;
                $voucherData = $this->hitungVoucher($partner->id, $request->kode_voucher, $afterDiskon);
                $totalBayarAkhir = $afterDiskon - $voucherData['nilai_voucher'];

                $order->update([
                    'nama_pelanggan' => $request->nama_pelanggan,
                    'no_whatsapp'    => $request->no_whatsapp,
                    'no_meja'        => $request->no_meja,
                    'keterangan'     => $request->keterangan,
                    'total_harga'    => $totalBayarAkhir,
                    'nominal_diskon' => $nominalDiskon,
                    'jenis_diskon'   => $jenisDiskon,
                    'kode_voucher'   => $voucherData['kode_voucher'],
                    'nilai_voucher'  => $voucherData['nilai_voucher'] > 0 ? $voucherData['nilai_voucher'] : null,
                ]);
                foreach ($newDetails as $detail) {
                    $order->orderDetails()->create($detail);
                }
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan #' . $order->nomor_pesanan . ' Berhasil Diperbarui!',
                    'data' => [
                        'id'   => $order->id,
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
                    if (empty($orderDetailsData)) continue;

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

                    // --- LOGIKA VOUCHER ---
                    $afterDiskon = $subTotal - $nilaiDiskon;
                    $voucherData = $this->hitungVoucher(
                        $partnerId,
                        $offlineOrder['kode_voucher'] ?? null,
                        $afterDiskon
                    );
                    $totalBayarAkhir = $afterDiskon - $voucherData['nilai_voucher'];

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
                        'kode_voucher'   => $voucherData['kode_voucher'],
                        'nilai_voucher'  => $voucherData['nilai_voucher'] > 0 ? $voucherData['nilai_voucher'] : null,
                        'status'         => 'pending',
                    ]);
                    foreach ($orderDetailsData as $detail) {
                        $order->orderDetails()->create($detail);
                    }
                    $syncedIds[] = $order->id;
                }
                return response()->json([
                    'success'    => true,
                    'message'    => count($syncedIds) . ' pesanan berhasil disinkronisasi.',
                    'synced_ids' => $syncedIds
                ]);
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal sync: ' . $e->getMessage()], 500);
        }
    }

    // ─── Method tambahan yang dipakai oleh pwa-kasir.js ─────────────────────

    public function checkOfflineSynced(Request $request)
    {
        $ids = $request->input('ids', []);
        $synced = Order::whereIn('offline_id', $ids)->pluck('offline_id');
        return response()->json(['synced' => $synced]);
    }
}
