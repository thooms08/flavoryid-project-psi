<?php

namespace App\Http\Controllers;

use App\Models\{Partner, Verifikasi, InformasiOutlet, MediaQr}; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifikasiController extends Controller {
    
    public function show($id) {
        $partner = Partner::where('uuid', $id)->first();

        if (!$partner) {
            return redirect()->route('register')->with('error', 'Link verifikasi tidak valid. Silakan daftar ulang.');
        }

        $kodeVerif = Verifikasi::where('partner_id', $partner->id)
            ->value('kode_verifikasi');

        return view('verifikasi', compact('id', 'kodeVerif'));
    }

    public function verify(Request $request, $id) {
        // 1. TERJEMAHAN: Cari partner berdasarkan UUID yang dilempar dari
        $partner = Partner::where('uuid', $id)->first();

        // Kalau iseng ganti URL ngawur, tolak!
        if (!$partner) {
            return response()->json(['success' => false, 'message' => 'Akses tidak sah!'], 404);
        }

        // 2. Gunakan ID angka asli dari $partner yang ditemukan untuk cek verifikasi
        $verif = Verifikasi::where('partner_id', $partner->id)
                    ->where('kode_verifikasi', $request->kode)
                    ->first();

        if ($verif) {
            $verif->update(['konfirmasi' => true]);

            $outlet = InformasiOutlet::where('partner_id', $partner->id)->first();

            if ($outlet) {
                MediaQr::where('informasi_id', $outlet->id)->update([
                    'status' => 'aktif'
                ]);
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 400);
    }
}