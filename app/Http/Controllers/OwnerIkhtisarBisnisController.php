<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Partner;
use App\Models\OwnerKasir;

/**
 * OwnerIkhtisarBisnisController
 * ──────────────────────────────────────────────────────────────────────────
 * Menangani halaman Ikhtisar Bisnis di sisi owner/manager.
 *
 * Fungsi utama yang ditambahkan:
 *   - toggleVisibilitas() → sembunyikan / tampilkan menu "Ikhtisar Bisnis"
 *     di halaman kasir yang terhubung dengan owner ini.
 *
 * Fungsi lain (index, export, getChartData) didelegasikan langsung ke
 * KasirIkhtisarBisnisController agar tidak ada duplikasi logika bisnis.
 * ──────────────────────────────────────────────────────────────────────────
 */
class OwnerIkhtisarBisnisController extends Controller
{
    // ── Delegasi ke KasirIkhtisarBisnisController ─────────────────────────

    /**
     * Tampilkan halaman Ikhtisar Bisnis untuk owner.
     * Data diambil dari KasirIkhtisarBisnisController, lalu dirender
     * dengan view khusus owner (owner.laporan-bisnis) yang memiliki
     * tombol toggle visibilitas kasir.
     */
    public function index(Request $request)
    {
        $owner = Auth::user();

        // Set user request ke kasir agar KasirIkhtisarBisnisController
        // dapat menemukan partner dan memproses data dengan benar.
        $this->impersonateKasir($request);

        $kasirUser = $request->user(); // sudah di-set ke kasir oleh impersonateKasir()

        $response = app(KasirIkhtisarBisnisController::class)->index($request);
        $viewData = $response->getData();

        // Ambil status toggle dari partner kasir
        $partner            = Partner::where('user_id', $kasirUser->id)->first();
        $showIkhtisarBisnis = $partner ? (bool) $partner->show_ikhtisar_bisnis : true;

        return view('owner.laporan-bisnis', array_merge($viewData, [
            'showIkhtisarBisnis' => $showIkhtisarBisnis,
        ]));
    }

    /**
     * Ambil data chart (AJAX).
     */
    public function getChartData(Request $request)
    {
        $this->impersonateKasir($request);
        return app(KasirIkhtisarBisnisController::class)->getChartData($request);
    }

    /**
     * Export Excel laporan keuangan.
     */
    public function export(Request $request)
    {
        $this->impersonateKasir($request);
        return app(KasirIkhtisarBisnisController::class)->export($request);
    }

    // ── Helper: set user request ke kasir yang terhubung ─────────────────

    /**
     * Ganti user di $request menjadi kasir yang terhubung dengan owner,
     * agar controller kasir bisa menemukan partner dengan benar.
     */
    private function impersonateKasir(Request $request): void
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

        $request->setUserResolver(fn() => $kasirUser);
    }

    // ── Fitur baru: Toggle visibilitas Ikhtisar Bisnis di sisi kasir ──────

    /**
     * Toggle kolom show_ikhtisar_bisnis pada partner yang terhubung
     * dengan kasir milik owner yang sedang login.
     *
     * Alur:
     *   owner (auth) → owner_kasir.owner_id → kasir_id → partner.user_id
     *
     * Response: JSON (agar bisa dipakai oleh fetch/AJAX dari view).
     */
    public function toggleVisibilitas(Request $request)
    {
        $owner = Auth::user();

        // Ambil relasi kasir pertama yang dimiliki owner ini
        $ownerKasir = OwnerKasir::where('owner_id', $owner->id)->first();

        if (!$ownerKasir) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada akun kasir yang terhubung dengan akun owner ini.',
            ], 404);
        }

        // Cari partner berdasarkan kasir_id
        $partner = Partner::where('user_id', $ownerKasir->kasir_id)->first();

        if (!$partner) {
            return response()->json([
                'success' => false,
                'message' => 'Data partner tidak ditemukan.',
            ], 404);
        }

        // Toggle nilai boolean
        $partner->show_ikhtisar_bisnis = !$partner->show_ikhtisar_bisnis;
        $partner->save();

        $status = $partner->show_ikhtisar_bisnis ? 'ditampilkan' : 'disembunyikan';

        return response()->json([
            'success'    => true,
            'show'       => $partner->show_ikhtisar_bisnis,
            'message'    => "Menu Ikhtisar Bisnis berhasil {$status} di halaman kasir.",
        ]);
    }
}
