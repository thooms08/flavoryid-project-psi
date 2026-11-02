<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Models\{User, Partner, InformasiOutlet, MediaQr, Verifikasi};

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user()->role);
        }

        return view('auth.login');
    }

    /**
     * Halaman login dengan akun demo — hanya bisa diakses via token yang valid.
     * Token = 16 karakter pertama dari SHA-256(APP_KEY + 'demo-access').
     */
    public function showDemoLogin(string $token)
    {
        $expected = substr(hash('sha256', config('app.key') . 'demo-access'), 0, 16);

        if (!hash_equals($expected, $token)) {
            abort(404);
        }

        return view('auth.login', ['demoMode' => true]);
    }

    public function login(Request $request): RedirectResponse
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $remember = $request->has('remember');

        // 2. Cek Kredensial
        $user = User::where('username', $request->input('username'))->first();
        if (!$user) {
            // Username salah. Cek apakah password yang diketik cocok dengan user lain di DB.
            $anyUserWithThisPassword = false;
            $allUsers = User::all();
            foreach ($allUsers as $u) {
                if (\Illuminate\Support\Facades\Hash::check($request->input('password'), $u->password)) {
                    $anyUserWithThisPassword = true;
                    break;
                }
            }

            if ($anyUserWithThisPassword) {
                // Password benar (punya user lain), tapi username salah.
                return back()->withErrors([
                    'username' => 'Username salah.',
                ])->withInput();
            } else {
                // Dua-duanya salah.
                return back()->withErrors([
                    'username' => 'Username & password salah.',
                ])->withInput();
            }
        }

        if (!\Illuminate\Support\Facades\Hash::check($request->input('password'), $user->password)) {
            // Password salah tapi username benar.
            return back()->withErrors([
                'username' => 'Password salah.',
            ])->withInput();
        }

        // 4. Proses Login
        $request->session()->regenerate();
        Auth::login($user, $remember);

        // 5. Cek Verifikasi
        if ($user->role !== 'admin') {
            $isVerified = $user->partner?->verifikasi?->konfirmasi ?? false;

            if (!$isVerified) {
                Auth::logout(); // Logout paksa karena belum verifikasi
                return back()->with('unverified', 'Akun Anda belum terverifikasi!')->withInput();
            }
        }

        // 6. LOGIKA CEK STATUS LANGGANAN
        if ($user->role === 'kasir') {
            $partner = Partner::where('user_id', $user->id)->first();
            $outlet = $partner ? InformasiOutlet::where('partner_id', $partner->id)->first() : null;
            $mediaQr = $outlet ? MediaQr::where('informasi_id', $outlet->id)->first() : null;

            // Jika status nonaktif, paksa ke halaman langganan
            if ($mediaQr && $mediaQr->status === 'nonaktif') {
                return redirect()->route('kasir.langganan.index');
            }
        }

        return $this->redirectUser($user->role);
    }

    // ==========================================
    // FITUR LUPA PASSWORD & VERIFIKASI (BARU)
    // ==========================================

    public function showLupaPassword()
    {
        return view('auth.lupa-password');
    }

    public function kirimKodeVerifikasi(Request $request)
    {
        $request->validate([
            'username' => 'required|string'
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return back()->with('error', 'Username tidak ditemukan di sistem kami.');
        }

        $partner = Partner::where('user_id', $user->id)->first();
        if (!$partner) {
            return back()->with('error', 'Akun partner tidak ditemukan.');
        }

        $verifikasi = Verifikasi::where('partner_id', $partner->id)->first();
        if (!$verifikasi) {
            return back()->with('error', 'Kode verifikasi untuk akun ini tidak ditemukan. Hubungi admin.');
        }

        session([
            'reset_user_id' => $user->id,
            'reset_username' => $user->username,
        ]);

        return redirect()->route('password.verifikasi')->with('success', 'Masukkan kode verifikasi yang sama seperti saat pendaftaran. Kode tidak dikirim melalui email.');
    }

    public function showVerifikasi()
    {
        if (!session('reset_user_id')) {
            return redirect()->route('lupa-password')->with('error', 'Sesi telah habis. Silakan ulangi proses.');
        }
        return view('auth.verifikasi');
    }

    public function verifikasiKode(Request $request)
    {
        $request->validate([
            'code' => 'required|alpha_num|size:6'
        ]);

        $userId = session('reset_user_id');

        if (!$userId) {
            return redirect()->route('lupa-password')->with('error', 'Sesi telah habis. Silakan ulangi proses.');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('lupa-password')->with('error', 'User tidak ditemukan.');
        }

        $partner = Partner::where('user_id', $userId)->first();
        if (!$partner) {
            return back()->with('error', 'Akun partner tidak ditemukan untuk verifikasi.');
        }

        $verifikasi = Verifikasi::where('partner_id', $partner->id)->first();
        if (!$verifikasi) {
            return back()->with('error', 'Kode verifikasi tidak valid.');
        }

        if (strtoupper($request->code) !== strtoupper($verifikasi->kode_verifikasi)) {
            return back()->with('error', 'Kode verifikasi salah.');
        }

        Auth::login($user);
        session()->forget(['reset_user_id', 'reset_username']);

        return redirect()->intended($this->getRedirectUrl($user->role))->with('success', 'Berhasil masuk! Jangan lupa ubah password Anda di Pengaturan Akun.');
    }

    // ==========================================

    private function getRedirectUrl(string $role): string
    {
        return match ($role) {
            'kasir'  => route('kasir.dashboard'),
            'admin'  => route('admin.dashboard'),
            'owner'  => route('owner.dashboard'),
            default  => '/'
        };
    }

    private function redirectUser(string $role): RedirectResponse
    {
        return redirect()->intended($this->getRedirectUrl($role));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    /**
     * AJAX: generate captcha baru tanpa reload halaman
     */
    public function refreshCaptcha(): \Illuminate\Http\JsonResponse
    {
        $chars   = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $captcha = '';
        for ($i = 0; $i < 5; $i++) {
            $captcha .= $chars[random_int(0, strlen($chars) - 1)];
        }
        session(['login_captcha' => $captcha]);
        // Paksa session tersimpan ke DB sebelum response dikirim
        session()->save();

        $svgLetters = '';
        $colors = ['#f97316', '#ea580c', '#c2410c', '#9a3412', '#fb923c'];
        $x = 18;
        for ($i = 0; $i < strlen($captcha); $i++) {
            $rotate = random_int(-18, 18);
            $dy     = random_int(-4, 4);
            $color  = $colors[$i % count($colors)];
            $svgLetters .= "<text x=\"{$x}\" y=\"" . (32 + $dy) . "\" "
                . "font-family=\"'Courier New',monospace\" "
                . "font-size=\"26\" font-weight=\"bold\" fill=\"{$color}\" "
                . "transform=\"rotate({$rotate} {$x} " . (32 + $dy) . ")\">"
                . htmlspecialchars($captcha[$i])
                . "</text>";
            $x += 22;
        }
        $noise = '';
        for ($n = 0; $n < 4; $n++) {
            $y1 = random_int(10, 50); $y2 = random_int(10, 50);
            $noise .= "<line x1=\"0\" y1=\"{$y1}\" x2=\"130\" y2=\"{$y2}\" "
                . "stroke=\"#f9731640\" stroke-width=\"1.5\"/>";
        }
        $svg = "<svg viewBox=\"0 0 130 60\" xmlns=\"http://www.w3.org/2000/svg\" "
            . "style=\"width:160px;height:70px;background:#fffbf5;border-radius:10px;\">"
            . "<rect width=\"130\" height=\"60\" fill=\"#fffbf5\"/>"
            . $noise . $svgLetters . "</svg>";

        return response()->json(['svg' => $svg]);
    }
}