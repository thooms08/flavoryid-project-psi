<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Note;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OrderHistoryController extends Controller
{
    public function index(Request $request)
    {
        try {
            $partnerId = Auth::user()->partner->id;

            // Base Query
            $query = Order::with(['orderDetails.menu'])
                          ->where('partner_id', $partnerId)
                          ->where('status', 'success')
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

            // --- PERBAIKAN: HANYA ANGKA BERURUTAN (01, 02, 03, dst) ---
            $orders->getCollection()->transform(function ($order) {
                // Ekstrak HANYA angka resmi dari database backend
                $nomorAngka = preg_replace('/[^0-9]/', '', $order->nomor_pesanan);
                
                if (empty($nomorAngka)) {
                    $nomorAngka = $order->id; 
                }
                
                // TIMPA field 'nomor_pesanan' aslinya
                $order->nomor_pesanan = str_pad($nomorAngka, 2, '0', STR_PAD_LEFT);
                
                return $order;
            });
            // ---------------------------------------------------------------

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
    
    // METHOD UNTUK DATA STRUK BLUETOOTH THERMAL
    public function receiptBtData(Request $request, $uuid)
    {
        try {
            $partner = $request->user()->partner;
            $partnerId = $partner->id;

            // Load order dengan detail menu, informasi outlet, dan partner berdasarkan UUID
            $order = Order::with(['orderDetails.menu'])
                ->where('partner_id', $partnerId)
                ->where('uuid', $uuid)
                ->firstOrFail();

            // 1. Ambil Informasi Outlet dan Kolom Logo
            $outlet = $partner->outlet; 
            $informasiOutlet = $outlet ? $outlet->informasiOutlet : null;
            
            // Format URL logo penuh jika ada data di database
            $logoUrl = null;
            if ($informasiOutlet && !empty($informasiOutlet->logo)) {
                $logoUrl = str_starts_with($informasiOutlet->logo, 'http') 
                    ? $informasiOutlet->logo 
                    : 'https://flavory.id/' . ltrim($informasiOutlet->logo, '/');
            }

            // 2. Logika Notes Struk (Ambil 1 yang aktif jika ada)
            $activeNote = Note::where('partner_id', $partnerId)
                ->where('status', 'aktif')
                ->first();

            $teksCatatan = $activeNote 
                ? $activeNote->note 
                : 'Terima Kasih Atas Kunjungan Anda!';

            // 3. Format nomor pesanan dua digit (01, 02, dst) sesuai aturan backend Anda
            $nomorAngka = preg_replace('/[^0-9]/', '', $order->nomor_pesanan);
            if (empty($nomorAngka)) {
                $nomorAngka = $order->id; 
            }
            $formattedNomorPesanan = str_pad($nomorAngka, 2, '0', STR_PAD_LEFT);

            return response()->json([
                'success' => true,
                'data' => [
                    'outlet' => [
                        'nama_outlet' => $outlet->nama_outlet ?? 'OUTLET FLAVORY',
                        'alamat' => $outlet->alamat ?? '',
                        'logo_url' => $logoUrl
                    ],
                    'order' => [
                        'id' => $order->id,
                        'uuid' => $order->uuid,
                        'nomor_pesanan' => $formattedNomorPesanan,
                        'created_at' => $order->created_at->toIso8601String(),
                        'nama_pelanggan' => $order->nama_pelanggan,
                        'total_harga' => (double) $order->total_harga,
                        'uang_bayar' => (double) $order->uang_bayar,
                        'no_whatsapp' => $order->no_whatsapp,
                        'kembalian' => (double) $order->kembalian,
                        'jenis_diskon' => $order->jenis_diskon,
                        'nominal_diskon' => (double) $order->nominal_diskon,
                        'keterangan' => trim(str_replace('(Order via PWA Offline)', '', $order->keterangan ?? '')),
                        'order_details' => $order->orderDetails->map(function($detail) {
                            return [
                                'nama_menu' => $detail->menu->nama_menu ?? 'Menu Terhapus',
                                'variant' => $detail->variant,
                                'qty' => (int) $detail->qty,
                                'harga' => (double) $detail->harga,
                            ];
                        })
                    ],
                    'teks_catatan' => $teksCatatan
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data struk: pesanan tidak ditemukan atau terjadi kesalahan server.'
            ], 500);
        }
    }

    // METHOD BARU UNTUK DATA STRUK DIGITAL (QR CODE)
    public function receiptDigitalData(Request $request, $uuid)
    {
        try {
            $partnerId = $request->user()->partner->id;

            // Cari order berdasarkan UUID dan Partner ID (Keamanan)
            $order = Order::where('partner_id', $partnerId)
                ->where('uuid', $uuid)
                ->firstOrFail();
            
            // Generate URL untuk ke versi Web (QR Struk)
            $url = route('kasir.qr-struk', ['uuid' => $uuid, 'mode' => 'view']);

            return response()->json([
                'success' => true,
                'data' => [
                    'url' => $url,
                    'kode_struk' => strtolower(trim($order->kode_struk ?? '')),
                    'nomor_pesanan' => $order->nomor_pesanan
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data struk digital: pesanan tidak ditemukan.'
            ], 404);
        }
    }
}