<?php

namespace App\Http\Controllers;

use App\Models\PengaturanForm;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KasirFormController extends Controller
{
    public function index()
    {
        // Cari partner yang dimiliki oleh user yang sedang login
        $partner = Partner::where('user_id', Auth::id())->first();

        // Jika user tidak punya data di tabel partners, cegah akses
        if (!$partner) {
            abort(403, 'Akun Anda belum memiliki profil Partner.');
        }

        $partnerId = $partner->id;

        // Ambil atau buat pengaturan form untuk partner tersebut
        $pengaturan = PengaturanForm::firstOrCreate(
            ['partner_id' => $partnerId],
            [
                'nama' => true,
                'whatsapp' => true,
                'meja' => true,
                'keterangan' => true,
                'diskon' => true,
                'bukti_pembayaran' => true, // <-- Tambahan Field Baru
            ]
        );

        return view('kasir.pengaturan-form', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            // <-- Tambahkan 'bukti_pembayaran' di dalam array validasi 'in'
            'field'  => 'required|string|in:nama,whatsapp,meja,keterangan,diskon,bukti_pembayaran',
            'status' => 'required|boolean',
        ]);

        // Cari partner yang dimiliki oleh user yang sedang login
        $partner = Partner::where('user_id', Auth::id())->first();

        if (!$partner) {
            return response()->json([
                'success' => false,
                'message' => 'Partner tidak ditemukan.'
            ], 403);
        }

        $partnerId = $partner->id;

        // Update pengaturan
        $pengaturan = PengaturanForm::where('partner_id', $partnerId)->firstOrFail();
        
        $pengaturan->update([
            $validated['field'] => $validated['status']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan ' . ucfirst(str_replace('_', ' ', $validated['field'])) . ' berhasil diperbarui.'
        ]);
    }
}