<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PartnerResetPasswordController extends Controller
{
    // Menampilkan halaman form ubah password
    public function showResetForm(Request $request, $token)
    {
        $username = $request->query('username');
        
        // Cek apakah token dan username valid di database
        $record = DB::table('password_reset_tokens')->where('email', $username)->first();
        
        if (!$record || !Hash::check($token, $record->token)) {
            abort(403, 'Link reset password tidak valid atau sudah kedaluwarsa.');
        }

        return view('auth.ubah-password', ['token' => $token, 'username' => $username]);
    }

    // Memproses perubahan password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'username' => 'required',
            'password' => 'required|min:8|confirmed', // Pastikan password konfirmasi cocok
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        // Verifikasi ulang token (Keamanan Ganda)
        $record = DB::table('password_reset_tokens')
            ->where('email', $request->username)
            ->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors(['username' => 'Token reset password tidak valid atau sudah kedaluwarsa.']);
        }

        // Cari user dan Update password
        $user = User::where('username', $request->username)->first();
        if (!$user) {
            return back()->withErrors(['username' => 'User tidak ditemukan.']);
        }

        $user->update([
            'password' => $request->password // Akan di-hash otomatis karena fitur casts di Model User
        ]);

        // Hapus token agar link tidak bisa dipakai lagi (One-time use)
        DB::table('password_reset_tokens')->where('email', $request->username)->delete();

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan login dengan password baru Anda.');
    }
}