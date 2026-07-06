<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\{Partner, InformasiOutlet, MediaQr, Subscription, HentiLangganan, Order, Pengeluaran};
use Carbon\Carbon;

class SubscribeController extends Controller
{
    // Mengambil data relasi user kasir yang sedang login via token
    private function getKasirData() {
        $partner = Partner::where('user_id', Auth::id())->firstOrFail();
        $outlet = InformasiOutlet::where('partner_id', $partner->id)->firstOrFail();
        $mediaQr = MediaQr::where('informasi_id', $outlet->id)->firstOrFail();
        
        return (object) ['partner' => $partner, 'outlet' => $outlet, 'mediaQr' => $mediaQr];
    }

    public function index() {
        if (Auth::user()->role !== 'kasir') {
            return response()->json(['message' => 'Akses ditolak. Hanya untuk kasir.'], 403);
        }

        $data = $this->getKasirData();
        
        // --- LOGIKA AKUMULASI 1 BULAN ---
        $startOfMonth = Carbon::now('Asia/Jakarta')->startOfMonth();
        $endOfMonth = Carbon::now('Asia/Jakarta')->endOfMonth();
        $namaBulan = Carbon::now('Asia/Jakarta')->translatedFormat('F');

        $totalOmzet = Order::where('partner_id', $data->partner->id)
            ->where('status', 'success')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('total_harga');

        $totalPengeluaran = Pengeluaran::where('partner_id', $data->partner->id)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('total');

        $untungBersih = $totalOmzet - $totalPengeluaran;
        $totalUntung = $untungBersih > 0 ? $untungBersih : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'status' => $data->mediaQr->status,
                'paket' => $data->mediaQr->paket,
                'outlet_name' => $data->outlet->nama_outlet,
                'total_untung' => (int)$totalUntung,
                'nama_bulan' => $namaBulan
            ]
        ], 200);
    }

    public function subscribe(Request $request) {
        if (Auth::user()->role !== 'kasir') {
            return response()->json(['message' => 'Hanya role kasir yang dapat memproses pembayaran langganan.'], 403);
        }

        $request->validate([
            'paket' => 'required|in:eksklusif,super',
            'metode_pembayaran' => 'required|string'
        ]);

        $data = $this->getKasirData();
        $amount = ($request->paket === 'eksklusif') ? 67000 : 880000;
        $invoiceNumber = 'FYS-' . time() . '-' . strtoupper(Str::random(4));

        // Hitung/Generate data transaksi dari WijayaPay
        $paymentData = $this->generateWijayaPay($invoiceNumber, $amount, $data->partner, $request->metode_pembayaran);

        if ($paymentData) {
            Subscription::create([
                'partner_id' => $data->partner->id,
                'invoice_number' => $invoiceNumber,
                'amount' => $amount,
                'paket' => $request->paket,
                'payment_url' => $paymentData['nomor_va'] ?? $paymentData['qr_image'] ?? $paymentData['pay_code'] ?? '-',
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Invoice berhasil dibuat.',
                'data' => [
                    'invoice' => $invoiceNumber,
                    'payment' => $paymentData
                ]
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal memproses pembayaran ke Payment Gateway. Silakan coba lagi.'
        ], 500);
    }

    public function unsubscribe(Request $request) {
        if (Auth::user()->role !== 'kasir') {
            return response()->json(['message' => 'Hanya role kasir yang dapat menghentikan langganan.'], 403);
        }

        $request->validate([
            'alasan' => 'required|string|min:10'
        ]);

        $data = $this->getKasirData();

        // 1. Simpan Alasan
        HentiLangganan::create([
            'partner_id' => $data->partner->id,
            'alasan' => $request->alasan
        ]);

        // 2. Ubah Status jadi Nonaktif
        $data->mediaQr->update(['status' => 'nonaktif']);

        // 3. Revoke Token Aplikasi Mobile (Logout)
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berlangganan telah dihentikan. Sesi token Anda telah dicabut.'
        ], 200);
    }

    private function generateWijayaPay($invoiceNumber, $amount, $partner, $method) {
        $apiKey = env('WIJAYAPAY_API_KEY');
        $merchantCode = env('WIJAYAPAY_MERCHANT_CODE');
        $url = "https://wijayapay.com/api/transaction/create";

        $signature = md5($merchantCode . $apiKey . $invoiceNumber);

        try {
            $response = Http::withHeaders([
                'X-Signature' => $signature,
                'Accept' => 'application/json',
            ])->asForm()->post($url, [
                'code_merchant' => $merchantCode,
                'api_key'       => $apiKey,
                'ref_id'        => $invoiceNumber,
                'nominal'       => (int)$amount,
                'code_payment'  => $method,
                'customer_name' => $partner->nama_manager,
                'customer_email'=> $partner->email,
                'customer_phone'=> $partner->no_wa,
                'callback_url'  => url('/api/wijayapay/notification'),
                'return_url'    => route('kasir.langganan.sukses'),
            ]);

            $res = $response->json();
            
            if ($response->successful() && isset($res['success']) && $res['success'] == true) {
                return $res['data']; 
            }

            Log::error('WijayaPay Error API Subscription: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('WijayaPay Connection Error: ' . $e->getMessage());
            return null;
        }
    }
}