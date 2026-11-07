<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\{Partner, InformasiOutlet, MediaQr, Subscription, HentiLangganan, Order, Pengeluaran, OwnerKasir};

class OwnerLanggananController extends Controller
{
    protected KasirLanggananController $kasir;

    public function __construct(KasirLanggananController $kasir)
    {
        $this->kasir = $kasir;
    }

    /**
     * Ganti user di $request menjadi kasir yang terhubung dengan owner,
     * agar semua query berbasis partner_id bekerja dengan benar.
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

    /**
     * Helper ambil data kasir yang terhubung ke owner ini.
     */
    private function getKasirData()
    {
        $owner      = Auth::user();
        $ownerKasir = OwnerKasir::where('owner_id', $owner->id)->first();

        if (!$ownerKasir) {
            abort(403, 'Tidak ada akun kasir yang terhubung dengan akun owner ini.');
        }

        $partner = Partner::where('user_id', $ownerKasir->kasir_id)->first();
        if (!$partner) {
            abort(403, 'Data partner kasir tidak ditemukan.');
        }

        $outlet  = InformasiOutlet::where('partner_id', $partner->id)->firstOrFail();
        $mediaQr = MediaQr::where('informasi_id', $outlet->id)->firstOrFail();

        return (object) ['partner' => $partner, 'outlet' => $outlet, 'mediaQr' => $mediaQr];
    }

    public function index(Request $request)
    {
        $data = $this->getKasirData();

        $startOfMonth = \Carbon\Carbon::now('Asia/Jakarta')->startOfMonth();
        $endOfMonth   = \Carbon\Carbon::now('Asia/Jakarta')->endOfMonth();
        $namaBulan    = \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('F');

        $totalOmzet = Order::where('partner_id', $data->partner->id)
            ->where('status', 'success')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('total_harga');

        $totalPengeluaran = Pengeluaran::where('partner_id', $data->partner->id)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('total');

        $untungBersih = $totalOmzet - $totalPengeluaran;
        $totalUntung  = $untungBersih > 0 ? $untungBersih : 0;

        return view('owner.langganan', [
            'status'      => $data->mediaQr->status,
            'partner'     => $data->partner,
            'outlet'      => $data->outlet,
            'paket'       => $data->mediaQr->paket,
            'totalUntung' => $totalUntung,
            'namaBulan'   => $namaBulan,
        ]);
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'paket'             => 'required|in:eksklusif,super',
            'metode_pembayaran' => 'required|string',
        ]);

        $data          = $this->getKasirData();
        $amount        = ($request->paket === 'eksklusif') ? 70000 : 689000;
        $invoiceNumber = 'FYS-' . time() . '-' . strtoupper(Str::random(4));

        $paymentData = $this->callGenerateWijayaPay($invoiceNumber, $amount, $data->partner, $request->metode_pembayaran);

        if ($paymentData) {
            Subscription::create([
                'partner_id'     => $data->partner->id,
                'invoice_number' => $invoiceNumber,
                'amount'         => $amount,
                'paket'          => $request->paket,
                'payment_url'    => $paymentData['nomor_va'] ?? $paymentData['qr_image'] ?? $paymentData['pay_code'] ?? '-',
                'status'         => 'pending',
            ]);

            return view('owner.langganan-bayar', [
                'payment' => $paymentData,
                'invoice' => $invoiceNumber,
            ]);
        }

        return back()->with('error', 'Gagal memproses pembayaran ke Payment Gateway. Silakan coba lagi.');
    }

    public function unsubscribe(Request $request)
    {
        $request->validate([
            'alasan' => 'required|string|min:10',
        ]);

        $data = $this->getKasirData();

        HentiLangganan::create([
            'partner_id' => $data->partner->id,
            'alasan'     => $request->alasan,
        ]);

        $data->mediaQr->update(['status' => 'nonaktif']);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('owner.login')->with('success', 'Berlangganan telah dihentikan. Anda telah dikeluarkan dari sistem.');
    }

    public function sukses()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return view('owner.langganan-sukses');
    }

    /**
     * Akses method generateWijayaPay (private) dari KasirLanggananController
     * via PHP Reflection agar tidak duplikasi kode payment gateway.
     */
    private function callGenerateWijayaPay($invoiceNumber, $amount, $partner, $method)
    {
        $reflection = new \ReflectionMethod($this->kasir, 'generateWijayaPay');
        $reflection->setAccessible(true);
        return $reflection->invoke($this->kasir, $invoiceNumber, $amount, $partner, $method);
    }
}
