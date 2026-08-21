<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KasirVoucherController extends Controller
{
    /**
     * Ambil semua voucher milik partner yang sedang login.
     */
    public function index()
    {
        $partner = Partner::where('user_id', Auth::id())->first();

        if (!$partner) {
            return response()->json(['success' => false, 'message' => 'Partner tidak ditemukan.'], 403);
        }

        $vouchers = Voucher::where('partner_id', $partner->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $vouchers,
            'total'   => $vouchers->count(),
        ]);
    }

    /**
     * Simpan voucher baru.
     */
    public function store(Request $request)
    {
        $partner = Partner::where('user_id', Auth::id())->first();

        if (!$partner) {
            return response()->json(['success' => false, 'message' => 'Partner tidak ditemukan.'], 403);
        }

        // Cek batas maksimal 30 voucher per partner
        $totalVoucher = Voucher::where('partner_id', $partner->id)->count();
        if ($totalVoucher >= 30) {
            return response()->json([
                'success' => false,
                'message' => 'Batas maksimal voucher (30) sudah tercapai.',
            ], 422);
        }

        $validated = $request->validate([
            'kode_voucher'     => 'required|string|size:6',
            'jenis_potongan'   => 'required|in:%,Rp',
            'nominal_potongan' => 'required|numeric|min:0',
        ]);

        // Paksa uppercase
        $kode = strtoupper($validated['kode_voucher']);

        // Cek unik per partner
        $exists = Voucher::where('partner_id', $partner->id)
            ->where('kode_voucher', $kode)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Kode voucher <strong>' . $kode . '</strong> sudah digunakan pada outlet ini.',
            ], 422);
        }

        $voucher = Voucher::create([
            'partner_id'       => $partner->id,
            'kode_voucher'     => $kode,
            'jenis_potongan'   => $validated['jenis_potongan'],
            'nominal_potongan' => $validated['nominal_potongan'],
            'is_active'        => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Voucher <strong>' . $kode . '</strong> berhasil disimpan.',
            'data'    => $voucher,
        ], 201);
    }

    /**
     * Hapus voucher.
     */
    public function destroy(Voucher $voucher)
    {
        $partner = Partner::where('user_id', Auth::id())->first();

        if (!$partner || $voucher->partner_id !== $partner->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $kode = $voucher->kode_voucher;
        $voucher->delete();

        return response()->json([
            'success' => true,
            'message' => 'Voucher ' . $kode . ' berhasil dihapus.',
        ]);
    }

    /**
     * Apply voucher saat form order (kasir iframe) — kembalikan data potongan.
     * Dipanggil via AJAX saat kasir input kode di form order.
     */
    public function applyVoucher(Request $request)
    {
        $partner = Partner::where('user_id', Auth::id())->first();

        if (!$partner) {
            return response()->json(['valid' => false, 'message' => 'Partner tidak ditemukan.'], 403);
        }

        $kode = strtoupper(trim($request->input('kode_voucher', '')));

        if (strlen($kode) !== 6) {
            return response()->json(['valid' => false, 'message' => 'Kode voucher harus 6 karakter.']);
        }

        $voucher = Voucher::where('partner_id', $partner->id)
            ->where('kode_voucher', $kode)
            ->where('is_active', true)
            ->first();

        if (!$voucher) {
            return response()->json([
                'valid'   => false,
                'message' => 'Kode voucher <strong>' . $kode . '</strong> tidak ditemukan atau tidak aktif.',
            ]);
        }

        return response()->json([
            'valid'            => true,
            'message'          => 'Voucher <strong>' . $kode . '</strong> berhasil diterapkan!',
            'kode_voucher'     => $voucher->kode_voucher,
            'jenis_potongan'   => $voucher->jenis_potongan,
            'nominal_potongan' => (float) $voucher->nominal_potongan,
        ]);
    }

    /**
     * Cek unik kode voucher (dipakai di halaman pengaturan atur voucher).
     */
    public function checkKode(Request $request)
    {
        $partner = Partner::where('user_id', Auth::id())->first();

        if (!$partner) {
            return response()->json(['success' => false, 'message' => 'Partner tidak ditemukan.'], 403);
        }

        $kode = strtoupper(trim($request->input('kode_voucher', '')));

        if (strlen($kode) !== 6) {
            return response()->json(['available' => false, 'message' => 'Kode harus tepat 6 karakter.']);
        }

        $exists = Voucher::where('partner_id', $partner->id)
            ->where('kode_voucher', $kode)
            ->exists();

        if ($exists) {
            return response()->json([
                'available' => false,
                'message'   => 'Kode <strong>' . $kode . '</strong> sudah digunakan pada outlet ini, gunakan kode lain.',
            ]);
        }

        return response()->json([
            'available' => true,
            'message'   => 'Kode <strong>' . $kode . '</strong> tersedia.',
        ]);
    }
}
