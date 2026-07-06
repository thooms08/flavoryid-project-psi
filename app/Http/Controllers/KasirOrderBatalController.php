<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; // Tambahkan ini untuk akses user login

class KasirOrderBatalController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil partner_id dari user yang sedang login
        $partnerId = Auth::user()->partner->id;

        // 2. Base Query (Hanya status fail DAN milik partner_id yang login)
        $query = Order::with(['orderDetails.menu'])
                      ->where('partner_id', $partnerId) // KUNCI KEAMANAN DI SINI
                      ->where('status', 'fail')
                      ->latest();

        // 3. Filter Search (Nomor Pesanan, Nama, WA)
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

        // 5. Hitung Statistik (Nilai pesanan yang gagal)
        $stats = [
            'total_transaksi' => $query->count(),
            'total_pendapatan' => $query->sum('total_harga') 
        ];

        // 6. Pagination
        $limit = $request->get('limit', 30);
        if ($limit == 'all') {
            $orders = $query->get();
        } else {
            $orders = $query->paginate($limit)->withQueryString();
        }

        // 7. Response AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('kasir.partials.fail-order-cards', compact('orders'))->render(),
                'stats' => [
                    'total_transaksi' => number_format($stats['total_transaksi']),
                    'total_pendapatan' => 'Rp ' . number_format($stats['total_pendapatan'], 0, ',', '.')
                ],
                'hasMore' => $orders instanceof \Illuminate\Pagination\LengthAwarePaginator ? $orders->hasMorePages() : false
            ]);
        }

        return view('kasir.order-batal', compact('orders', 'stats'));
    }

    /**
     * Memulihkan pesanan yang batal kembali ke status pending
     */
    public function restore(Order $order)
    {
        // 1. KEAMANAN GANDA: Pastikan order yang akan di-restore benar milik partner yang login
        $partnerId = Auth::user()->partner->id;
        
        // UBAH BAGIAN INI (Hapus satu tanda =)
        if ($order->partner_id != $partnerId) {
            return response()->json([
                'success' => false, 
                'message' => 'Anda tidak memiliki akses untuk memulihkan pesanan ini.'
            ], 403);
        }

        try {
            DB::beginTransaction();

            // 2. Cek ketersediaan stok terlebih dahulu untuk semua item
            foreach ($order->orderDetails as $detail) {
                $menu = Menu::find($detail->menu_id);
                if ($menu && !is_null($menu->stok_menu)) {
                    if ($menu->stok_menu < $detail->qty) {
                        throw new \Exception("Stok menu {$menu->nama_menu} tidak mencukupi untuk dipulihkan. Sisa stok saat ini: {$menu->stok_menu}");
                    }
                }
            }

            // 3. Jika semua stok aman, potong stoknya
            foreach ($order->orderDetails as $detail) {
                $menu = Menu::find($detail->menu_id);
                if ($menu && !is_null($menu->stok_menu)) {
                    $menu->stok_menu -= $detail->qty;
                    
                    // Update status menu jika stok habis
                    if ($menu->stok_menu <= 0) {
                        $menu->stok_menu = 0;
                        $menu->status_menu = 'habis';
                    }
                    $menu->save();
                }
            }

            // 4. Ubah status pesanan kembali menjadi pending
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