<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, Partner, Verifikasi};
use Exception;

class ForgotPasswordController extends Controller
{
    
    public function sendResetCode(Request $request)
    {
        if ($request->isJson()) {
            $json = json_decode($request->getContent(), true);
            if (is_array($json)) {
                $request->merge($json);
            }
        }
    
        $request->validate([
            'username' => 'required|string',
        ]);
    
        $user = User::where('username', $request->username)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Username tidak ditemukan di sistem kami.'
            ], 404);
        }
    
        $partner = Partner::where('user_id', $user->id)->first();
        if (!$partner) {
            return response()->json([
                'success' => false,
                'message' => 'Akun partner tidak ditemukan. Hubungi Admin.'
            ], 404);
        }

        $verifikasi = Verifikasi::where('partner_id', $partner->id)->first();
        if (!$verifikasi) {
            return response()->json([
                'success' => false,
                'message' => 'Kode verifikasi untuk akun ini tidak ditemukan. Hubungi admin.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Silakan gunakan kode verifikasi yang sama seperti saat pendaftaran.',
            'data' => [
                'user_id' => $user->id,
                'username' => $user->username,
            ],
        ]);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'code' => 'required|alpha_num|size:6'
        ]);

        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 404);
        }

        $partner = Partner::where('user_id', $request->user_id)->first();
        if (!$partner) {
            return response()->json(['success' => false, 'message' => 'Akun partner tidak ditemukan.'], 404);
        }

        $verifikasi = Verifikasi::where('partner_id', $partner->id)->first();
        if (!$verifikasi) {
            return response()->json(['success' => false, 'message' => 'Kode verifikasi tidak valid.'], 400);
        }

        if (strtoupper($request->code) !== strtoupper($verifikasi->kode_verifikasi)) {
            return response()->json(['success' => false, 'message' => 'Kode verifikasi salah.'], 400);
        }

        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 404);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Berhasil masuk! Jangan lupa ubah password Anda di Pengaturan Akun.',
            'data' => [
                'token' => $token,
                'role' => $user->role,
                'user' => $user
            ]
        ]);
    }
}