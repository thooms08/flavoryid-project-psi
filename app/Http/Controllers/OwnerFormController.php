<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{Partner, PengaturanForm, OwnerKasir};

class OwnerFormController extends Controller
{
    protected KasirFormController $kasir;

    public function __construct(KasirFormController $kasir)
    {
        $this->kasir = $kasir;
    }

    /**
     * Ambil partner kasir yang terhubung ke owner yang sedang login.
     */
    private function getKasirPartner(): Partner
    {
        $owner      = Auth::user();
        $ownerKasir = OwnerKasir::where('owner_id', $owner->id)->first();

        if (!$ownerKasir) {
            abort(403, 'Tidak ada akun kasir yang terhubung dengan akun owner ini.');
        }

        $kasirUser = \App\Models\User::find($ownerKasir->kasir_id);

        if (!$kasirUser || !$kasirUser->partner) {
            abort(403, 'Data partner kasir tidak ditemukan.');
        }

        return $kasirUser->partner;
    }

    public function index(Request $request)
    {
        $partner   = $this->getKasirPartner();
        $partnerId = $partner->id;

        // Ambil atau buat pengaturan form untuk partner kasir tersebut
        $pengaturan = PengaturanForm::firstOrCreate(
            ['partner_id' => $partnerId],
            [
                'nama'             => true,
                'whatsapp'         => true,
                'meja'             => true,
                'keterangan'       => true,
                'diskon'           => true,
                'bukti_pembayaran' => true,
            ]
        );

        return view('owner.pengaturan-form', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'field'  => 'required|string|in:nama,whatsapp,meja,keterangan,diskon,bukti_pembayaran,voucher',
            'status' => 'required|boolean',
        ]);

        $partner   = $this->getKasirPartner();
        $partnerId = $partner->id;

        $pengaturan = PengaturanForm::where('partner_id', $partnerId)->firstOrFail();
        $pengaturan->update([
            $validated['field'] => $validated['status'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan ' . ucfirst(str_replace('_', ' ', $validated['field'])) . ' berhasil diperbarui.',
        ]);
    }
}
