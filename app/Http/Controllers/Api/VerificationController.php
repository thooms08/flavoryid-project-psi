<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Partner, Verifikasi, InformasiOutlet, MediaQr};
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verify(Request $request, $uuid)
    {
        $request->validate([
            'kode' => 'required|string',
        ]);

        $partner = Partner::where('uuid', $uuid)->first();

        if (! $partner) {
            return response()->json([
                'success' => false,
                'message' => 'Akses tidak sah!',
            ], 404);
        }

        $kode = strtoupper($request->kode);

        $verif = Verifikasi::where('partner_id', $partner->id)
            ->where('kode_verifikasi', $kode)
            ->where('konfirmasi', false)
            ->first();

        if (! $verif) {
            return response()->json([
                'success' => false,
                'message' => 'Kode verifikasi tidak valid atau sudah kedaluwarsa.',
            ], 400);
        }

        $verif->update(['konfirmasi' => true]);

        $outlet = InformasiOutlet::where('partner_id', $partner->id)->first();

        if ($outlet) {
            MediaQr::where('informasi_id', $outlet->id)->update([
                'status' => 'aktif',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Verifikasi berhasil. Anda bisa login sekarang.',
        ]);
    }
}