<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AccountController extends Controller
{
    public function index()
    {
        // Load user beserta data partner agar query lebih optimal
        $user = User::with('partner')->find(Auth::id());
        
        return response()->json([
            'success' => true,
            'message' => 'Data akun berhasil dimuat.',
            'data'    => $user
        ], 200);
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

            return response()->json([
                'success' => true,
                'message' => 'Profil Manager berhasil diperbarui!',
                'data'    => $user->load('partner')
            ], 200);
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

            return response()->json([
                'success' => true,
                'message' => 'Keamanan akun (Username/Password) berhasil diperbarui!',
                'data'    => $user->load('partner')
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Jenis formulir tidak dikenali.'
        ], 400);
    }

    public function checkUsername(Request $request)
    {
        $username = $request->input('username');
        $userId = Auth::id();

        // Cek apakah username sudah ada dan bukan milik user yang sedang login
        $exists = User::where('username', $username)->where('id', '!=', $userId)->exists();

        return response()->json([
            'available' => !$exists
        ], 200);
    }
}