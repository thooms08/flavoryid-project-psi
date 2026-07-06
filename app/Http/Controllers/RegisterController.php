<?php

namespace App\Http\Controllers;

use App\Models\{User, Partner, InformasiOutlet, MediaQr, Verifikasi};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RegisterController extends Controller {
    
    public function showRegister() {
        return view('register');
    }

    public function register(Request $request) {
        $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|min:8|confirmed',
            'nama_outlet' => 'required|string|max:255',
            'jenis_outlet' => 'required',
            'jenis_lainnya' => 'required_if:jenis_outlet,lainnya', 
            'deskripsi' => 'required|string',
            'alamat_lengkap' => 'required|string',
            'kota' => 'required|string',
            'provinsi' => 'required|string',
            'foto_outlet' => 'required|image|max:2048', 
            'page_name' => 'required|unique:informasi_outlet',
            'paket' => 'required|in:gratis,eksklusif,super',
            'username_youtube' => 'nullable|string|max:255',
            'username_x' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => 'kasir',
            ]);

            $partner = Partner::create([
                'user_id' => $user->id,
                'uuid' => (string) Str::uuid(),
                'nama_manager' => $request->nama_manager,
                'no_wa' => $request->no_wa,
                'email' => $request->email,
            ]);

            $fotoPath = null;
            if ($request->hasFile('foto_outlet')) {
                $file = $request->file('foto_outlet');
                $fileName = time() . '_' . Str::slug($request->nama_outlet) . '.' . $file->extension();
                // Disk 'public' root-nya storage/app/public — pakai disk eksplisit
                $file->storeAs('outlet', $fileName, 'public');
                $fotoPath = $fileName;
            }
            
            $outlet = InformasiOutlet::create([
                'partner_id' => $partner->id,
                'jenis_outlet' => $request->jenis_outlet,
                'jenis_lainnya' => $request->jenis_lainnya,
                'nama_outlet' => $request->nama_outlet,
                'foto_outlet' => $fotoPath,
                'page_name' => $request->page_name,
                'deskripsi' => $request->deskripsi,
                'alamat_lengkap' => $request->alamat_lengkap,
                'kota' => $request->kota,
                'provinsi' => $request->provinsi,
                'maps' => $request->maps,
            ]);

            Verifikasi::create([
                'partner_id' => $partner->id,
                'kode_verifikasi' => $this->generateMixedAlphaNumericCode(6),
                'konfirmasi'      => false,
            ]);

            // Setup Harga & Invoice
            $invoiceNumber = 'FLY-' . time() . '-' . strtoupper(Str::random(4));
            $amount = 0;
            if ($request->paket === 'eksklusif') $amount = 500;
            if ($request->paket === 'super') $amount = 880000;

            $mediaQr = MediaQr::create([
                'informasi_id' => $outlet->id,
                'facebook' => $request->facebook,
                'instagram' => $request->instagram,
                'tiktok' => $request->tiktok,
                'youtube' => $request->username_youtube,
                'x' => $request->username_x,
                'paket' => $request->paket,
                'invoice_number' => $invoiceNumber,
                'payment_status' => $amount == 0 ? 'success' : 'pending',
            ]);

            DB::commit();

            // Jika Gratis, langsung ke verifikasi tanpa email
            if ($amount == 0) {
                return redirect()->route('verifikasi.show', ['id' => $partner->uuid]);
            }

            // Jika Berbayar, request ke DOKU (tidak langsung kirim email, nunggu DOKU)
            $method = $request->input('method'); // Ambil pilihan dari dropdown
            $paymentData = $this->generateWijayaPayCheckout($invoiceNumber, $amount, $partner, $outlet, $method);
            
            if ($paymentData) {
                // Ambil identifier pembayaran (URL atau Nomor VA) untuk disimpan
                $identifier = $paymentData['checkout_url'] ?? $paymentData['nomor_va'] ?? $paymentData['qr_image'] ?? '-';
                $mediaQr->update(['payment_url' => $identifier]);

                session([
                    'waiting_payment' => [
                        'payment' => $paymentData,
                        'invoice' => $invoiceNumber,
                        'partner_id' => $partner->uuid,
                    ],
                ]);

                return redirect()->route('waiting.payment', [
                    'invoice' => $invoiceNumber,
                    'partner_uuid' => $partner->uuid,
                ]);
            }

            return back()->with('error', 'Gagal memproses ke gerbang pembayaran. Hubungi admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Register Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    public function showWaitingPayment($invoice, $partnerUuid)
    {
        $partner = Partner::where('uuid', $partnerUuid)->first();
        if (!$partner) {
            return redirect()->route('register')->with('error', 'Partner tidak ditemukan. Silakan coba daftar ulang.');
        }

        $mediaQr = MediaQr::where('invoice_number', $invoice)
            ->whereHas('outlet', function ($query) use ($partner) {
                $query->where('partner_id', $partner->id);
            })->first();

        if (!$mediaQr) {
            return redirect()->route('register')->with('error', 'Data pembayaran tidak ditemukan.');
        }

        $sessionData = session('waiting_payment');
        $paymentData = null;

        if ($sessionData && $sessionData['invoice'] === $invoice && $sessionData['partner_id'] === $partnerUuid) {
            $paymentData = $sessionData['payment'];
        }

        if (!$paymentData) {
            $paymentData = [
                'payment_name' => 'Pembayaran Pendaftaran',
                'tutorial_pembayaran' => 'Silakan selesaikan pembayaran melalui aplikasi Anda. Jika sudah membayar, tunggu beberapa detik agar status terupdate.',
                'total_bayar' => 0,
                'checkout_url' => $mediaQr->payment_url,
            ];
        }

        return view('waiting-payment', [
            'payment' => $paymentData,
            'invoice' => $invoice,
            'partner_id' => $partner->uuid,
        ]);
    }

    private function generateMixedAlphaNumericCode(int $length = 6): string
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $digits = '0123456789';
        $all = $letters . $digits;

        $code = '';
        $code .= $letters[random_int(0, strlen($letters) - 1)];
        $code .= $digits[random_int(0, strlen($digits) - 1)];

        for ($i = 2; $i < $length; $i++) {
            $code .= $all[random_int(0, strlen($all) - 1)];
        }

        return str_shuffle($code);
    }

    public function checkAvailability(Request $request)
{
    $type = $request->query('type'); // 'username' atau 'page_name'
    $value = $request->query('value');

    if ($type === 'username') {
        $exists = \App\Models\User::where('username', $value)->exists();
        return response()->json(['available' => !$exists]);
    }

    if ($type === 'page_name') {
        $exists = \App\Models\InformasiOutlet::where('page_name', $value)->exists();
        return response()->json(['available' => !$exists]);
    }

    return response()->json(['error' => 'Invalid type'], 400);
}

   private function generateWijayaPayCheckout($invoiceNumber, $amount, $partner, $outlet, $method)
{
    $apiKey = env('WIJAYAPAY_API_KEY');
    $merchantCode = env('WIJAYAPAY_MERCHANT_CODE');
    $url = "https://wijayapay.com/api/transaction/create";

    $signature = md5($merchantCode . $apiKey . $invoiceNumber);

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
        'return_url'    => route('verifikasi.show', ['id' => $partner->uuid]),
    ]);

    $res = $response->json();

    if ($response->successful() && isset($res['success']) && $res['success'] == true) {
        return $res['data']; // Kembalikan SEMUA data (array), bukan cuma string URL
    }

    Log::error('WijayaPay Real Error: ' . $response->body());
    return null;
}

   public function wijayapayNotification(Request $request)
{
    $merchantCode = env('WIJAYAPAY_MERCHANT_CODE');
    $apiKey = env('WIJAYAPAY_API_KEY');
    
    // 1. Ambil Signature dari Header
    $incomingSignature = $request->header('X-Signature') ?? $request->header('x-signature') ?? $request->header('Signature') ?? $request->header('signature');
        
        // 2. Ambil data dari JSON (Data ada di dalam objek 'data')
        $refId = $request->input('data.ref_id') ?? $request->input('ref_id');
        $status = $request->input('status') ?? $request->input('data.status');

        Log::info('WijayaPay Webhook received', [
            'ref_id' => $refId,
            'status' => $status,
            'signature' => $incomingSignature,
            'payload' => $request->all(),
        ]);

        if (!$refId) {
            Log::warning('WijayaPay Webhook missing ref_id', ['payload' => $request->all()]);
            return response()->json(['status' => false, 'message' => 'ref_id tidak ditemukan'], 400);
        }

    // 3. Validasi Keamanan Signature
    $mySignature = md5($merchantCode . $apiKey . $refId);

    if ($incomingSignature !== $mySignature) {
        Log::warning('WijayaPay Webhook: Signature Invalid', [
            'incoming' => $incomingSignature,
            'expected' => $mySignature,
            'payload' => $request->all(),
        ]);
        return response()->json(['status' => false, 'message' => 'Signature mismatch'], 403);
    }

    // 4. Jika Pembayaran Sukses (status: paid)
    if (strtolower($status) === 'paid') {
        
        // --- SKENARIO 1: PEMBAYARAN PENDAFTARAN AWAL ---
        $mediaQr = \App\Models\MediaQr::where('invoice_number', $refId)->first();
        if ($mediaQr && $mediaQr->payment_status !== 'success') {
            $mediaQr->update(['payment_status' => 'success']);
            Log::info('WijayaPay Webhook updated payment_status success', ['invoice' => $refId]);
            
            $outlet = \App\Models\InformasiOutlet::find($mediaQr->informasi_id);
            if ($outlet) {
                $partner = \App\Models\Partner::find($outlet->partner_id);
                $kodeData = \App\Models\Verifikasi::where('partner_id', $partner->id)->first();
                
                if ($partner && $kodeData) {
                    Log::info('WijayaPay Webhook registration payment success, no verification email sent because code will display on screen', [
                        'invoice' => $refId,
                        'partner_id' => $partner->id,
                    ]);
                }
            }
        }

                // --- SKENARIO 2: PEMBAYARAN PERPANJANG LANGGANAN ---
        if (\Illuminate\Support\Str::startsWith($refId, 'FYS-')) { 
            $subscription = \App\Models\Subscription::where('invoice_number', $refId)->first();
            
            if ($subscription && $subscription->status !== 'success') {
                $subscription->update(['status' => 'success']);
                
                $outlet = \App\Models\InformasiOutlet::where('partner_id', $subscription->partner_id)->first();
                
                if ($outlet) {
                    // UPDATE: Sekarang kita update 'status' DAN 'paket' sekaligus!
                    \App\Models\MediaQr::where('informasi_id', $outlet->id)->update([
                        'status' => 'aktif',
                        'paket'  => $subscription->paket 
                    ]);
                }
            }
        }

    } 

    return response()->json(['status' => true]);
}

    public function paymentFinish()
    {
        return redirect('/')->with('success', 'Pendaftaran dan pembayaran sedang diproses. Silakan login.');
    }

}