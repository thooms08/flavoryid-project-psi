<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\NomorMeja;
use App\Models\OwnerKasir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KasirMejaController extends Controller
{
    /**
     * Ambil partner berdasarkan user yang login.
     * - Kasir langsung: Partner::where('user_id', Auth::id())
     * - Owner: lewat relasi OwnerKasir → Partner
     */
    private function getPartner(): ?Partner
    {
        $user = Auth::user();

        // Coba langsung sebagai kasir
        $partner = Partner::where('user_id', $user->id)->first();
        if ($partner) return $partner;

        // Coba sebagai owner → cari kasir partner
        $ownerKasir = OwnerKasir::where('owner_id', $user->id)->first();
        if ($ownerKasir) {
            return Partner::where('user_id', $ownerKasir->kasir_id)->first();
        }

        return null;
    }

    /**
     * Ambil semua nomor meja milik partner yang sedang login.
     */
    public function index()
    {
        $partner = $this->getPartner();

        if (!$partner) {
            return response()->json(['success' => false, 'message' => 'Partner tidak ditemukan.'], 403);
        }

        $meja = NomorMeja::where('partner_id', $partner->id)
            ->orderBy('nama_meja', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $meja,
            'total'   => $meja->count(),
        ]);
    }

    /**
     * Simpan nomor meja baru — support multiple (array nama_meja).
     */
    public function store(Request $request)
    {
        $partner = $this->getPartner();

        if (!$partner) {
            return response()->json(['success' => false, 'message' => 'Partner tidak ditemukan.'], 403);
        }

        $validated = $request->validate([
            'nama_meja' => 'required|array|min:1',
            'nama_meja.*' => 'required|string|max:100|distinct',
        ]);

        $created = [];
        $skipped = [];
        $existing = NomorMeja::where('partner_id', $partner->id)
            ->whereIn('nama_meja', $validated['nama_meja'])
            ->pluck('nama_meja')
            ->map(fn($n) => strtolower(trim($n)))
            ->toArray();

        foreach ($validated['nama_meja'] as $nama) {
            $nama = trim($nama);
            if ($nama === '') continue;

            if (in_array(strtolower($nama), $existing)) {
                $skipped[] = $nama;
                continue;
            }

            $meja = NomorMeja::create([
                'partner_id' => $partner->id,
                'nama_meja'   => $nama,
                'is_active'   => true,
            ]);
            $created[] = $meja;
            $existing[] = strtolower($nama);
        }

        $msg = count($created) . ' meja berhasil ditambahkan.';
        if (!empty($skipped)) {
            $msg .= ' ' . count($skipped) . ' meja sudah ada (dilewati).';
        }

        return response()->json([
            'success' => true,
            'message' => $msg,
            'data'    => $created,
            'skipped' => $skipped,
        ], 201);
    }

    /**
     * Hapus nomor meja.
     */
    public function destroy(NomorMeja $meja)
    {
        $partner = $this->getPartner();

        if (!$partner || $meja->partner_id !== $partner->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $nama = $meja->nama_meja;
        $meja->delete();

        return response()->json([
            'success' => true,
            'message' => 'Meja "' . $nama . '" berhasil dihapus.',
        ]);
    }
}
