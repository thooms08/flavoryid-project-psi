<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Partner;
use App\Models\InformasiOutlet;

class OwnerController extends Controller
{
    // ─── Halaman login owner ──────────────────────────────────────────────────
    public function showLogin()
    {
        // Sudah login sebagai owner → langsung ke dashboard
        if (Auth::check() && Auth::user()->role === 'owner') {
            return redirect()->route('owner.dashboard');
        }

        // Sudah login dengan role lain → tolak
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.login-owner');
    }

    // ─── Proses login owner ───────────────────────────────────────────────────
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Cari user berdasarkan username dengan role owner
        $user = User::where('username', $request->input('username'))
            ->where('role', 'owner')
            ->first();

        if (!$user) {
            return back()->withErrors([
                'username' => 'Username tidak ditemukan atau bukan akun Owner.',
            ])->withInput();
        }

        if (!\Illuminate\Support\Facades\Hash::check($request->input('password'), $user->password)) {
            return back()->withErrors([
                'username' => 'Password salah.',
            ])->withInput();
        }

        // Cek hubungan owner_kasir dan verifikasi partner kasir
        $ownerKasir = \App\Models\OwnerKasir::where('owner_id', $user->id)->first();
        
        if (!$ownerKasir) {
            return back()->with('unverified', 'Akun Owner Anda belum terhubung dengan akun kasir. Hubungi administrator.')->withInput();
        }

        // Ambil kasir user dan partnernya
        $kasirUser = User::find($ownerKasir->kasir_id);
        
        if (!$kasirUser) {
            return back()->with('unverified', 'Akun kasir yang terhubung tidak ditemukan.')->withInput();
        }

        $partner = Partner::where('user_id', $kasirUser->id)->first();
        
        if ($partner) {
            $isVerified = $partner->verifikasi?->konfirmasi ?? false;
            if (!$isVerified) {
                return back()->with('unverified', 'Partner belum terverifikasi. Hubungi administrator.')->withInput();
            }
        } else {
            return back()->with('unverified', 'Data partner tidak ditemukan.')->withInput();
        }

        // Login terlebih dahulu, baru regenerate session
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route('owner.dashboard');
    }

    // ─── Logout owner ─────────────────────────────────────────────────────────
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('owner.login');
    }

    // ─── Dashboard utama owner ────────────────────────────────────────────────
    public function index()
    {
        $user = Auth::user();
        
        // Ambil partner kasir yang terhubung dengan owner ini
        $ownerKasir = \App\Models\OwnerKasir::where('owner_id', $user->id)->first();
        
        if (!$ownerKasir) {
            abort(403, 'Tidak ada akun kasir yang terhubung dengan akun owner ini.');
        }

        $kasirUser = User::find($ownerKasir->kasir_id);
        
        if (!$kasirUser || !$kasirUser->partner) {
            abort(403, 'Data partner kasir tidak ditemukan.');
        }

        $partner = $kasirUser->partner;
        $outlet  = $partner ? InformasiOutlet::where('partner_id', $partner->id)->first() : null;

        return view('owner.index', compact('user', 'partner', 'outlet'));
    }
}
