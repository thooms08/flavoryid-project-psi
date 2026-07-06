<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AdminAkunPartnerController extends Controller
{
    public function index()
    {
        // Hanya ambil partner yang usernya ber-role kasir
        $partners = Partner::whereHas('user', function($q) {
            $q->where('role', 'kasir');
        })->with('user')->get();

        return view('admin.akun-partner', compact('partners'));
    }

    public function generateLink($uuid)
    {
        $partner = Partner::where('uuid', $uuid)->firstOrFail();
        $user = $partner->user;

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan.']);
        }

        // 1. Generate token acak yang kuat
        $token = Str::random(64);

        // 2. Simpan token ke tabel bawaan 'password_reset_tokens'
        // Karena tabel ini wajib pakai kolom 'email', kita isi dengan 'username' 
        // sebagai identifier agar sistem tetap berjalan mulus tanpa error.
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->username], // Identifier (kunci pencarian nanti)
            [
                'token' => Hash::make($token), // Token wajib di-hash di database
                'created_at' => now()
            ]
        );

        // 3. Buat link reset. 
        // Kita sertakan 'username' ke dalam parameter URL agar halaman reset 
        // nantinya tahu siapa yang sedang melakukan pergantian password.
        $link = url('/reset-password/' . $token . '?username=' . urlencode($user->username));

        return response()->json([
            'success' => true,
            'link' => $link
        ]);
    }
}