<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\{Modal, OwnerKasir, Partner};
use App\Exports\ModalExport;
use Maatwebsite\Excel\Facades\Excel;

class OwnerModalController extends Controller
{
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

        $status      = $request->input('status', 'save');
        $quickFilter = $request->input('quick_filter');

        if ($quickFilter) {
            $endDate   = Carbon::today('Asia/Jakarta')->toDateString();
            $startDate = match ($quickFilter) {
                '1_hari'   => Carbon::today('Asia/Jakarta')->toDateString(),
                '1_minggu' => Carbon::today('Asia/Jakarta')->subWeek()->toDateString(),
                '1_bulan'  => Carbon::today('Asia/Jakarta')->subMonth()->toDateString(),
                '1_tahun'  => Carbon::today('Asia/Jakarta')->subYear()->toDateString(),
                default    => Carbon::today('Asia/Jakarta')->toDateString(),
            };
        } else {
            $startDate = $request->input('start_date', Carbon::today('Asia/Jakarta')->toDateString());
            $endDate   = $request->input('end_date', Carbon::today('Asia/Jakarta')->toDateString());
        }

        $startTs = $startDate . ' 00:00:00';
        $endTs   = $endDate   . ' 23:59:59';

        $modals = Modal::where('partner_id', $partnerId)
            ->where('status', $status)
            ->whereBetween('created_at', [$startTs, $endTs])
            ->orderBy('created_at', 'desc')
            ->get();

        // Summary
        $totalModal = Modal::where('partner_id', $partnerId)
            ->where('status', 'save')
            ->whereBetween('created_at', [$startTs, $endTs])
            ->sum('nominal');

        // Chart
        $diffDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;

        $summaryBase = Modal::where('partner_id', $partnerId)
            ->where('status', 'save')
            ->whereBetween('created_at', [$startTs, $endTs]);

        if ($diffDays <= 31) {
            $chartRaw = (clone $summaryBase)
                ->selectRaw("DATE(created_at) as label, SUM(nominal) as jumlah")
                ->groupByRaw("DATE(created_at)")
                ->orderByRaw("label ASC")
                ->get();
            $chartLabels = $chartRaw->map(fn($r) =>
                Carbon::parse($r->label)->translatedFormat('d M')
            )->toArray();
        } elseif ($diffDays <= 366) {
            $chartRaw = (clone $summaryBase)
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%u') as label, SUM(nominal) as jumlah, MIN(DATE(created_at)) as first_day")
                ->groupByRaw("DATE_FORMAT(created_at, '%Y-%u')")
                ->orderByRaw("label ASC")
                ->get();
            $chartLabels = $chartRaw->map(fn($r) =>
                'Minggu ' . Carbon::parse($r->first_day)->translatedFormat('d M')
            )->toArray();
        } else {
            $chartRaw = (clone $summaryBase)
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as label, SUM(nominal) as jumlah")
                ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
                ->orderByRaw("label ASC")
                ->get();
            $chartLabels = $chartRaw->map(fn($r) =>
                Carbon::parse($r->label . '-01')->translatedFormat('M Y')
            )->toArray();
        }
        $chartData = $chartRaw->pluck('jumlah')->map(fn($v) => (float) $v)->toArray();

        return view('owner.modal', compact(
            'modals', 'startDate', 'endDate', 'status',
            'totalModal', 'chartLabels', 'chartData', 'quickFilter'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nominal'     => 'required|numeric|min:1',
            'keterangan'  => 'nullable|string|max:255',
        ]);

        $partner = $this->getKasirPartner();

        Modal::create([
            'partner_id'  => $partner->id,
            'nominal'     => $request->nominal,
            'keterangan'  => $request->keterangan,
            'status'      => 'save',
        ]);

        return redirect()->back()->with('success', 'Modal usaha berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $partner = $this->getKasirPartner();

        $modal = Modal::where('id', $id)
            ->where('partner_id', $partner->id)
            ->firstOrFail();

        $modal->update(['status' => 'delete']);

        return redirect()->back()->with('success', 'Data dipindahkan ke tab Data Terhapus.');
    }

    public function restore($id)
    {
        $partner = $this->getKasirPartner();

        $modal = Modal::where('id', $id)
            ->where('partner_id', $partner->id)
            ->firstOrFail();

        $modal->update(['status' => 'save']);

        return redirect()->back()->with('success', 'Data modal berhasil dipulihkan.');
    }

    public function export(Request $request)
    {
        $partner   = $this->getKasirPartner();
        $startDate = $request->input('start_date', Carbon::today('Asia/Jakarta')->toDateString());
        $endDate   = $request->input('end_date', Carbon::today('Asia/Jakarta')->toDateString());
        $fileName  = 'laporan_modal_usaha_' . date('d-m-Y') . '.xlsx';

        ob_end_clean();
        ob_start();

        return Excel::download(new ModalExport($partner->id, $startDate, $endDate), $fileName);
    }
}
