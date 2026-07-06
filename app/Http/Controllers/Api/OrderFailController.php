<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderFailController extends Controller
{
    public function index(Request $request)
    {
        try {
            $partnerId = Auth::user()->partner->id;

            // Base Query: Status fail & Milik Partner
            $query = Order::with(['orderDetails.menu'])
                          ->where('partner_id', $partnerId)
                          ->where('status', 'fail')
                          ->latest();

            // Filter Pencarian
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nomor_pesanan', 'like', "%$search%")
                      ->orWhere('nama_pelanggan', 'like', "%$search%")
                      ->orWhere('no_whatsapp', 'like', "%$search%");
                });
            }

            // Filter Tanggal
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $start = Carbon::parse($request->start_date)->startOfDay();
                $end = Carbon::parse($request->end_date)->endOfDay();
                $query->whereBetween('created_at', [$start, $end]);
            }

            // Hitung Statistik
            $stats = [
                'total_transaksi' => $query->count(),
                'total_pendapatan' => (double) $query->sum('total_harga')
            ];

            $limit = $request->get('limit', 20);
            $orders = $query->paginate($limit);

            // Format Nomor Pesanan agar berurutan (sama seperti order history)
            $orders->getCollection()->transform(function ($order) {
                $nomorAngka = preg_replace('/[^0-9]/', '', $order->nomor_pesanan);
                if (empty($nomorAngka)) {
                    $nomorAngka = $order->id; 
                }
                $order->nomor_pesanan = str_pad($nomorAngka, 2, '0', STR_PAD_LEFT);
                return $order;
            });

            return response()->json([
                'success' => true,
                'data' => $orders->items(),
                'stats' => $stats,
                'meta' => [
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'has_more' => $orders->hasMorePages()
                ]
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function restore($id)
    {
        try {
            DB::beginTransaction();

            $partnerId = Auth::user()->partner->id;
            $order = Order::with('orderDetails')->findOrFail($id);

            // Keamanan Ganda
            if ($order->partner_id != $partnerId) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Anda tidak memiliki akses untuk memulihkan pesanan ini.'
                ], 403);
            }

            // Cek ketersediaan stok
            foreach ($order->orderDetails as $detail) {
                $menu = Menu::find($detail->menu_id);
                if ($menu && !is_null($menu->stok_menu)) {
                    if ($menu->stok_menu < $detail->qty) {
                        throw new \Exception("Stok menu {$menu->nama_menu} tidak mencukupi untuk dipulihkan. Sisa stok saat ini: {$menu->stok_menu}");
                    }
                }
            }

            // Potong stok karena dikembalikan ke antrean
            foreach ($order->orderDetails as $detail) {
                $menu = Menu::find($detail->menu_id);
                if ($menu && !is_null($menu->stok_menu)) {
                    $menu->stok_menu -= $detail->qty;
                    
                    if ($menu->stok_menu <= 0) {
                        $menu->stok_menu = 0;
                        $menu->status_menu = 'habis';
                    }
                    $menu->save();
                }
            }

            // Ubah status
            $order->update(['status' => 'pending']);

            DB::commit();
            return response()->json([
                'success' => true, 
                'message' => 'Pesanan berhasil dipulihkan ke antrean aktif.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => $e->getMessage()
            ], 500);
        }
    }
}