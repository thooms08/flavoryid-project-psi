<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OwnerKasir;

class OwnerLaporanPengeluaranController extends Controller
{
    protected KasirLaporanPengeluaranController $kasir;

    public function __construct(KasirLaporanPengeluaranController $kasir)
    {
        $this->kasir = $kasir;
    }

    /**
     * Ganti user di $request menjadi kasir yang terhubung dengan owner,
     * agar controller kasir dapat menemukan partner dengan benar.
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

    public function index(Request $request)
    {
        $this->impersonateKasir($request);

        $kasirResponse = $this->kasir->index($request);
        $viewData      = $kasirResponse->getData();

        return view('owner.laporan-pengeluaran', $viewData);
    }

    public function store(Request $request)
    {
        $this->impersonateKasir($request);
        return $this->kasir->store($request);
    }

    public function update(Request $request, $id)
    {
        $this->impersonateKasir($request);
        return $this->kasir->update($request, $id);
    }

    public function destroy($id)
    {
        $this->impersonateKasir(request());
        return $this->kasir->destroy($id);
    }

    public function restore($id)
    {
        $this->impersonateKasir(request());
        return $this->kasir->restore($id);
    }

    public function export(Request $request)
    {
        $this->impersonateKasir($request);
        return $this->kasir->export($request);
    }
}
