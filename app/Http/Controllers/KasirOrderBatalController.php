<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class KasirOrderBatalController extends Controller
{
    public function index(Request $request)
    {
        $partnerId = Auth::user()->partner->id;

        $query = Order::with(['orderDetails.menu'])
                      ->where('partner_id', $partnerId)
                      ->where('status', 'fail')
                      ->latest();

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

        // Gabungkan COUNT + SUM dalam 1 query — ganti 2 query terpisah
        $aggQuery  = clone $query;
        $aggregate = $aggQuery->selectRaw('COUNT(*) as total_transaksi, COALESCE(SUM(total_harga),0) as total_pendapatan')
                              ->reorder()
                              ->first();

        $stats = [
            'total_transaksi'  => (int) ($aggregate->total_transaksi ?? 0),
            'total_pendapatan' => (float) ($aggregate->total_pendapatan ?? 0),
        ];

        $limit = $request->get('limit', 30);
        if ($limit == 'all') {
            $orders = $query->get();
        } else {
            $orders = $query->paginate($limit)->withQueryString();
        }

        if ($request->ajax()) {
            return response()->json([
                'html'    => view('kasir.partials.fail-order-cards', compact('orders'))->render(),
                'stats'   => [
                    'total_transaksi'  => number_format($stats['total_transaksi']),
                    'total_pendapatan' => 'Rp ' . number_format($stats['total_pendapatan'], 0, ',', '.'),
                ],
                'hasMore' => $orders instanceof \Illuminate\Pagination\LengthAwarePaginator
                                ? $orders->hasMorePages()
                                : false,
            ]);
        }

        return view('kasir.order-batal', compact('orders', 'stats'));
    }

    public function restore(Order $order)
    {
        $partnerId = Auth::user()->partner->id;

        if ($order->partner_id != $partnerId) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk memulihkan pesanan ini.',
            ], 403);
        }

        try {
            DB::beginTransaction();

            // Eager load orderDetails — hindari lazy load di dalam loop
            $order->load('orderDetails');

            // Batch load semua menu terkait — 1 query ganti N+N query (cek + potong stok)
            $menuIds = $order->orderDetails->pluck('menu_id')->unique()->all();
            $menuMap = Menu::whereIn('id', $menuIds)->get()->keyBy('id');

            // Pass 1: validasi stok semua item sebelum ada yang diubah
            foreach ($order->orderDetails as $detail) {
                $menu = $menuMap->get($detail->menu_id);
                if ($menu && !is_null($menu->stok_menu)) {
                    if ($menu->stok_menu < $detail->qty) {
                        throw new \Exception(
                            "Stok menu {$menu->nama_menu} tidak mencukupi untuk dipulihkan. " .
                            "Sisa stok saat ini: {$menu->stok_menu}"
                        );
                    }
                }
            }

            // Pass 2: potong stok (semua sudah aman dari Pass 1)
            foreach ($order->orderDetails as $detail) {
                $menu = $menuMap->get($detail->menu_id);
                if ($menu && !is_null($menu->stok_menu)) {
                    $menu->stok_menu -= $detail->qty;
                    if ($menu->stok_menu <= 0) {
                        $menu->stok_menu   = 0;
                        $menu->status_menu = 'habis';
                    }
                    $menu->save();
                }
            }

            $order->update(['status' => 'pending']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dipulihkan ke antrean aktif.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
