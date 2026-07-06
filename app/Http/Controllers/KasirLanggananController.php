<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\{Partner, InformasiOutlet, MediaQr, Subscription, HentiLangganan,  Order, Pengeluaran};

class KasirLanggananController extends Controller
{
    // Mengambil data relasi user yang sedang login
    private function getKasirData() {
        $partner = Partner::where('user_id', Auth::id())->firstOrFail();
        $outlet = InformasiOutlet::where('partner_id', $partner->id)->firstOrFail();
        $mediaQr = MediaQr::where('informasi_id', $outlet->id)->firstOrFail();
        
        return (object) ['partner' => $partner, 'outlet' => $outlet, 'mediaQr' => $mediaQr];
    }

    public function index() {
    if (Auth::user()->role !== 'kasir') {
        abort(403, 'Akses ditolak.');
    }

    $data = $this->getKasirData();
    
    // --- LOGIKA AKUMULASI 1 BULAN (START) ---
    $startOfMonth = \Carbon\Carbon::now('Asia/Jakarta')->startOfMonth();
    $endOfMonth = \Carbon\Carbon::now('Asia/Jakarta')->endOfMonth();
    $namaBulan = \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('F'); // Contoh: April

    $totalOmzet = Order::where('partner_id', $data->partner->id)
        ->where('status', 'success')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->sum('total_harga');

    $totalPengeluaran = Pengeluaran::where('partner_id', $data->partner->id)
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->sum('total');

    $untungBersih = $totalOmzet - $totalPengeluaran;
    $totalUntung = $untungBersih > 0 ? $untungBersih : 0;
    // --- LOGIKA AKUMULASI 1 BULAN (END) ---

    return view('kasir.langganan', [
        'status' => $data->mediaQr->status,
        'partner' => $data->partner,
        'outlet' => $data->outlet,
        'paket' => $data->mediaQr->paket,
        'totalUntung' => $totalUntung,
        'namaBulan' => $namaBulan, // <-- Kirim nama bulan ke view
    ]);
}


    public function subscribe(Request $request) {
        // Blokir jika selain kasir mencoba mengirim request pembayaran
        if (Auth::user()->role !== 'kasir') {
            return back()->with('error', 'Hanya role kasir yang dapat memproses pembayaran langganan.');
        }

        $request->validate([
            'paket' => 'required|in:eksklusif,super',
            'metode_pembayaran' => 'required|string'
        ]);

        $data = $this->getKasirData();
        $amount = ($request->paket === 'eksklusif') ? 70000 : 880000;
        $invoiceNumber = 'FYS-' . time() . '-' . strtoupper(Str::random(4));

        // Generate data mentah dari WijayaPay
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

            // Tampilkan halaman instruksi bayar bikinan kita sendiri
            return view('kasir.langganan-bayar', [
                'payment' => $paymentData,
                'invoice' => $invoiceNumber
            ]);
        }

        return back()->with('error', 'Gagal memproses pembayaran ke Payment Gateway. Silakan coba lagi.');
    }

    public function unsubscribe(Request $request) {
        // Opsional: Blokir jika selain kasir mencoba berhenti langganan
        if (Auth::user()->role !== 'kasir') {
            return back()->with('error', 'Hanya role kasir yang dapat menghentikan langganan.');
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

        // 3. Logout dan bersihkan session
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berlangganan telah dihentikan. Anda telah dikeluarkan dari sistem.');
    }

    public function sukses() {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return view('kasir.langganan-sukses');
    }

    private function generateWijayaPay($invoiceNumber, $amount, $partner, $method) {
        $apiKey = env('WIJAYAPAY_API_KEY');
        $merchantCode = env('WIJAYAPAY_MERCHANT_CODE');
        $url = "https://wijayapay.com/api/transaction/create";

        $signature = md5($merchantCode . $apiKey . $invoiceNumber);

        $response = \Illuminate\Support\Facades\Http::withHeaders([
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

        \Illuminate\Support\Facades\Log::error('WijayaPay Error Subscription: ' . $response->body());
        return null;
    }

    public function checkStatus($invoice)
    {
        $subscription = Subscription::where('invoice_number', $invoice)->first();

        if ($subscription) {
            return response()->json(['status' => $subscription->status]);
        }

        return response()->json(['status' => 'pending']);
    }
}