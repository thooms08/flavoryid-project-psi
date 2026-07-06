<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class KasirAkunController extends Controller
{
    public function index()
    {
        // Load user berserta data partner agar query lebih optimal
        $user = User::with('partner')->find(Auth::id());
        
        return view('kasir.pengaturan-akun', compact('user'));
    }

    public function update(Request $request)
    {
        $userId = Auth::id();
        $user = User::find($userId);

        // ==========================================
        // 1. LOGIKA UPDATE PROFIL MANAGER
        // ==========================================
        if ($request->input('form_type') === 'profile') {
            
            $validated = $request->validate([
                'nama_manager' => 'required|string|max:255',
                'email'        => 'required|email|max:255',
                'no_wa'        => 'required|string|max:20',
            ], [
                'nama_manager.required' => 'Nama Manager tidak boleh kosong.',
                'email.required'        => 'Email tidak boleh kosong.',
                'email.email'           => 'Format email tidak valid.',
                'no_wa.required'        => 'Nomor WhatsApp tidak boleh kosong.',
            ]);

            // Pastikan relasi partner ada sebelum di-update
            if ($user->partner) {
                $user->partner->update($validated);
            }

            return redirect()->route('kasir.akun.index')->with('success', 'Profil Manager berhasil diperbarui!');
        }

        // ==========================================
        // 2. LOGIKA UPDATE KEAMANAN (USER & PASSWORD)
        // ==========================================
        if ($request->input('form_type') === 'security') {
            
            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:users,username,' . $userId,
                'password' => 'nullable|string|min:6|confirmed',
            ], [
                'username.required'  => 'Username tidak boleh kosong.',
                'username.unique'    => 'Username sudah digunakan oleh akun lain.',
                'password.min'       => 'Password minimal harus 6 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.'
            ]);

            $user->username = $validated['username'];
            
            // Update password hanya jika diisi oleh user
            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            return redirect()->route('kasir.akun.index')->with('success', 'Keamanan akun (Username/Password) berhasil diperbarui!');
        }

        // Fallback jika tidak ada form_type yang cocok
        return redirect()->back();
    }

    public function checkUsername(Request $request)
    {
        $username = $request->input('username');
        $userId = Auth::id();

        // Cek apakah username sudah ada dan bukan milik user yang sedang login
        $exists = User::where('username', $username)->where('id', '!=', $userId)->exists();

        return response()->json([
            'available' => !$exists
        ]);
    }
}