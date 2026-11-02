<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{User, Partner, InformasiOutlet, MediaQr, Verifikasi, OwnerKasir};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Mail\VerifikasiEmail;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            // Username unik per pasangan (username, role): cek apakah sudah ada di kasir atau owner
            'username' => [
                'required', 'string', 'max:50',
                function ($attribute, $value, $fail) {
                    if (User::usernameExistsForRegistration($value)) {
                        $fail('Username sudah digunakan. Silakan pilih username lain.');
                    }
                },
            ],
            'password' => 'required|min:8|confirmed',
            'nama_manager' => 'required|string|max:255',
            'no_wa' => 'required|string|max:25',
            'email' => 'required|email|max:255',
            'nama_outlet' => 'required|string|max:255',
            'jenis_outlet' => 'required|string',
            'jenis_lainnya' => 'required_if:jenis_outlet,lainnya|string|max:255',
            'deskripsi' => 'required|string',
            'alamat_lengkap' => 'required|string',
            'kota' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'foto_outlet' => 'required|image|max:2048',
            'page_name' => 'required|alpha_dash|unique:informasi_outlet,page_name',
            'paket' => 'required|in:gratis,eksklusif,super',
            'method' => 'required|in:QRIS,BCA Virtual Account,BRI Virtual Account,BNI Virtual Account,BSI Virtual Account,Mandiri Virtual Account',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'username_youtube' => 'nullable|string|max:255',
            'username_x' => 'nullable|string|max:255',
            'maps' => 'nullable|string|max:1000',
        ]);

        try {
                DB::beginTransaction();
        
                $user = User::create([
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                    'role' => 'kasir',
                ]);

                // ── Otomatis buat akun Owner dengan username & password yang sama ──
                $ownerUser = User::create([
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                    'role'     => 'owner',
                ]);

                // ── Hubungkan akun kasir & owner via tabel pivot ──
                OwnerKasir::create([
                    'kasir_id' => $user->id,
                    'owner_id' => $ownerUser->id,
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
                    $this->compressAndStore($file, 'outlet', $fileName, 60);
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
        
                $kodeVerif = $this->generateMixedAlphaNumericCode(6);
                Verifikasi::create([
                    'partner_id' => $partner->id,
                    'kode_verifikasi' => $kodeVerif,
                    'konfirmasi' => false,
                ]);
        
                $invoiceNumber = 'FLY-' . time() . '-' . strtoupper(Str::random(4));
                $amount = 0;
        
                if ($request->paket === 'eksklusif') {
                    $amount = 70000;
                } elseif ($request->paket === 'super') {
                    $amount = 800000;
                }
        
                $paymentStatus = $amount === 0 ? 'success' : 'pending';
        
                $mediaQr = MediaQr::create([
                    'informasi_id' => $outlet->id,
                    'facebook' => $request->facebook,
                    'instagram' => $request->instagram,
                    'tiktok' => $request->tiktok,
                    'youtube' => $request->username_youtube,
                    'x' => $request->username_x,
                    'paket' => $request->paket,
                    'invoice_number' => $invoiceNumber,
                    'payment_status' => $paymentStatus,
                ]);
        
                DB::commit();
        
                if ($amount === 0) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Pendaftaran berhasil. Salin kode verifikasi di layar dan simpan untuk proses selanjutnya.',
                        'uuid' => $partner->uuid,
                        'is_paid' => false,
                        'verification_code' => $kodeVerif,
                    ]);
                }
        
                $method = $request->input('method', 'QRIS');
                $paymentData = $this->generateWijayaPayCheckout($invoiceNumber, $amount, $partner, $outlet, $method);
        
                if ($paymentData) {
                    $identifier = $paymentData['checkout_url'] ?? $paymentData['nomor_va'] ?? $paymentData['qr_image'] ?? null;
                    if ($identifier) {
                        $mediaQr->update(['payment_url' => $identifier]);
                    }
        
                    return response()->json([
                        'success' => true,
                        'message' => 'Pendaftaran berhasil. Silakan bayar tagihan Anda.',
                        'uuid' => $partner->uuid,
                        'is_paid' => true,
                        'payment_data' => $paymentData,
                        'invoice' => $invoiceNumber,
                        'verification_code' => $kodeVerif,
                    ]);
                }
        
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memproses ke gerbang pembayaran.',
                ], 500);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('API Register Error: ' . $e->getMessage());
        
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem.',
                ], 500);
            }
        }

    public function checkAvailability(Request $request)
    {
        $type = $request->query('type');
        $value = $request->query('value');

        if ($type === 'username') {
            // Username dianggap tidak tersedia jika sudah ada di role kasir atau owner
            // (karena keduanya dibuat bersamaan saat registrasi)
            $exists = User::usernameExistsForRegistration($value);
            return response()->json(['available' => !$exists]);
        }

        if ($type === 'page_name') {
            $exists = InformasiOutlet::where('page_name', $value)->exists();
            return response()->json(['available' => !$exists]);
        }

        return response()->json(['error' => 'Invalid type'], 400);
    }

    private function generateMixedAlphaNumericCode(int $length = 6): string
        {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $code = '';
        
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[random_int(0, strlen($characters) - 1)];
            }
        
            return $code;
        }

    private function generateWijayaPayCheckout($invoiceNumber, $amount, $partner, $outlet, $method)
    {
        $paymentCodes = [
            'QRIS' => 'QRIS',
            'BCA Virtual Account' => 'BCA_VA',
            'BRI Virtual Account' => 'BRI_VA',
            'BNI Virtual Account' => 'BNI_VA',
            'BSI Virtual Account' => 'BSI_VA',
            'Mandiri Virtual Account' => 'MANDIRI_VA',
        ];

        $codePayment = $paymentCodes[$method] ?? 'QRIS';

        $apiKey = env('WIJAYAPAY_API_KEY');
        $merchantCode = env('WIJAYAPAY_MERCHANT_CODE');
        $url = "https://gateway.wijayapay.com/api/transaction/create";
        $signature = md5($merchantCode . $apiKey . $invoiceNumber);

        $response = Http::withHeaders([
            'X-Signature' => $signature,
            'Accept' => 'application/json',
        ])->asForm()->post($url, [
            'code_merchant' => $merchantCode,
            'api_key' => $apiKey,
            'ref_id' => $invoiceNumber,
            'nominal' => (int) $amount,
            'code_payment' => $codePayment,
            'customer_name' => $partner->nama_manager,
            'customer_email' => $partner->email,
            'customer_phone' => $partner->no_wa,
            'callback_url' => url('/api/wijayapay/notification'),
            'return_url' => route('verifikasi.show', ['id' => $partner->uuid]),
        ]);

        $res = $response->json();
        if ($response->successful() && isset($res['success']) && $res['success'] === true) {
            return $res['data'];
        }

        return null;
    }
}