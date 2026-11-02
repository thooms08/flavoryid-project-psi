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

        // Cari user
        $user = User::where('username', $request->input('username'))->first();

        if (!$user) {
            return back()->withErrors([
                'username' => 'Username tidak ditemukan.',
            ])->withInput();
        }

        if (!\Illuminate\Support\Facades\Hash::check($request->input('password'), $user->password)) {
            return back()->withErrors([
                'username' => 'Password salah.',
            ])->withInput();
        }

        // Pastikan role adalah owner
        if ($user->role !== 'owner') {
            return back()->withErrors([
                'username' => 'Akun ini bukan akun Owner/Manager. Gunakan halaman login yang sesuai.',
            ])->withInput();
        }

        // Cek verifikasi partner (jika owner punya partner)
        $partner = Partner::where('user_id', $user->id)->first();
        if ($partner) {
            $isVerified = $partner->verifikasi?->konfirmasi ?? false;
            if (!$isVerified) {
                return back()->with('unverified', 'Akun Anda belum terverifikasi. Hubungi administrator.')->withInput();
            }
        }

        $request->session()->regenerate();
        Auth::login($user, $request->boolean('remember'));

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
        $user    = Auth::user();
        $partner = Partner::where('user_id', $user->id)->first();
        $outlet  = $partner ? InformasiOutlet::where('partner_id', $partner->id)->first() : null;

        return view('owner.index', compact('user', 'partner', 'outlet'));
    }
}
