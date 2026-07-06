<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PengaturanForm;
use Illuminate\Http\Request;

class SettingFormController extends Controller
{
    // Mengambil status pengaturan saat ini
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user || !$user->partner) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data partner tidak ditemukan.'
                ], 404);
            }

            $partnerId = $user->partner->id;

            // Ambil atau buat default pengaturan
            $pengaturan = PengaturanForm::firstOrCreate(
                ['partner_id' => $partnerId],
                [
                    'nama' => true,
                    'whatsapp' => true,
                    'meja' => true,
                    'keterangan' => true,
                    'diskon' => true,
                    'bukti_pembayaran' => true,
                ]
            );

            return response()->json([
                'success' => true,
                'data' => $pengaturan
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Mengupdate salah satu toggle (Dipanggil otomatis setiap kali switch ditekan)
    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'field'  => 'required|string|in:nama,whatsapp,meja,keterangan,diskon,bukti_pembayaran',
                'status' => 'required|boolean', // 1 atau 0 (true/false)
            ]);

            $user = $request->user();
            if (!$user || !$user->partner) {
                return response()->json(['success' => false, 'message' => 'Partner tidak ditemukan.'], 403);
            }

            $pengaturan = PengaturanForm::where('partner_id', $user->partner->id)->firstOrFail();
            
            // Update field yang spesifik
            $pengaturan->update([
                $validated['field'] => $validated['status']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil diperbarui'
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi Error: ' . $e->getMessage()
            ], 500);
        }
    }
}