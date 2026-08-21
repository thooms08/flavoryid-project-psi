<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Menu;
use App\Models\Modal;
use App\Models\Pengeluaran;
use App\Exports\LaporanKeuanganExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class KasirLaporanBisnisController extends Controller
{
    // Data historis (bukan hari ini) di-cache 10 menit,
    // data hari ini 2 menit agar tetap near-realtime.
    const CACHE_TTL_HISTORY = 600;
    const CACHE_TTL_TODAY   = 120;

    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user || !$user->partner) {
            abort(403, 'Akses ditolak: Akun ini tidak terhubung dengan data Partner manapun.');
        }

        $partnerId = $user->partner->id;

        $startDate = $request->input('start_date', Carbon::today('Asia/Jakarta')->toDateString());
        $endDate   = $request->input('end_date', Carbon::today('Asia/Jakarta')->toDateString());

        if ($request->has('quick_filter')) {
            $filter    = $request->quick_filter;
            $endDate   = Carbon::today('Asia/Jakarta')->toDateString();
            if ($filter == '1_hari')   $startDate = Carbon::today('Asia/Jakarta')->toDateString();
            if ($filter == '1_minggu') $startDate = Carbon::today('Asia/Jakarta')->subWeek()->toDateString();
            if ($filter == '1_bulan')  $startDate = Carbon::today('Asia/Jakarta')->subMonth()->toDateString();
            if ($filter == '1_tahun')  $startDate = Carbon::today('Asia/Jakarta')->subYear()->toDateString();
        }

        $today    = Carbon::today('Asia/Jakarta')->toDateString();
        $ttl      = ($endDate === $today) ? self::CACHE_TTL_TODAY : self::CACHE_TTL_HISTORY;
        $cacheKey = "laporan_bisnis_{$partnerId}_{$startDate}_{$endDate}";

        $data = Cache::remember($cacheKey, $ttl, function () use ($partnerId, $startDate, $endDate) {

            $startTs = $startDate . ' 00:00:00';
            $endTs   = $endDate   . ' 23:59:59';

            // --- Summary Keuangan: 3 query agregat ringan ---
            $totalModal = Modal::where('partner_id', $partnerId)
                ->whereBetween('created_at', [$startTs, $endTs])
                ->sum('nominal');

            $totalOmzet = Order::where('partner_id', $partnerId)
                ->where('status', 'success')
                ->whereBetween('created_at', [$startTs, $endTs])
                ->sum('total_harga');

            $totalPengeluaran = Pengeluaran::where('partner_id', $partnerId)
                ->whereBetween('created_at', [$startTs, $endTs])
                ->sum('total');

            $untungBersih = $totalOmzet - $totalPengeluaran;

            // --- Top 10 Menu Paling Laku: JOIN bersih, 1 query ---
            $topMenus = OrderDetail::selectRaw('menus.nama_menu, SUM(order_details.qty) as total_qty')
                ->join('orders', 'orders.id', '=', 'order_details.order_id')
                ->join('menus',  'menus.id',  '=', 'order_details.menu_id')
                ->where('orders.partner_id', $partnerId)
                ->where('orders.status', 'success')
                ->whereBetween('orders.created_at', [$startTs, $endTs])
                ->groupBy('menus.id', 'menus.nama_menu')
                ->orderByDesc('total_qty')
                ->limit(10)
                ->get();

            // --- 5 Menu Paling Tidak Laku: LEFT JOIN ganti correlated subquery ---
            // Subquery correlated sebelumnya mengeksekusi SELECT per baris menu (N kali).
            // LEFT JOIN mengeksekusi 1 query saja.
            $leastMenus = Menu::select('menus.id', 'menus.nama_menu', DB::raw('MAX(orders.created_at) as last_sold_at'))
                ->leftJoin('order_details', 'order_details.menu_id', '=', 'menus.id')
                ->leftJoin('orders', function ($join) use ($partnerId, $endTs) {
                    $join->on('orders.id', '=', 'order_details.order_id')
                         ->where('orders.status', '=', 'success')
                         ->where('orders.created_at', '<=', $endTs);
                })
                ->where('menus.partner_id', $partnerId)
                ->groupBy('menus.id', 'menus.nama_menu')
                ->orderBy('last_sold_at', 'asc')
                ->limit(5)
                ->get();

            // --- Tabel Laporan: hanya kolom yang dibutuhkan di view ---
            // Eager load tetap ada (orderDetails.menu) agar tidak N+1 saat render view.
            // select() pada Order membatasi kolom yang ditarik dari tabel orders.
            $orders = Order::with(['orderDetails.menu'])
                ->select([
                    'id', 'partner_id', 'uuid', 'nomor_pesanan',
                    'nama_pelanggan', 'no_whatsapp', 'no_meja',
                    'metode_bayar', 'total_harga', 'nominal_diskon',
                    'jenis_diskon', 'kode_voucher', 'nilai_voucher',
                    'uang_bayar', 'kembalian',
                    'bukti_bayar', 'keterangan', 'created_at',
                ])
                ->where('partner_id', $partnerId)
                ->where('status', 'success')
                ->whereBetween('created_at', [$startTs, $endTs])
                ->orderBy('created_at', 'desc')
                ->get();

            return compact(
                'totalModal', 'totalOmzet', 'totalPengeluaran',
                'untungBersih', 'topMenus', 'leastMenus', 'orders'
            );
        });

        $totalUntung     = $data['untungBersih'] > 0 ? $data['untungBersih'] : 0;
        $totalRugi       = $data['untungBersih'] < 0 ? abs($data['untungBersih']) : 0;
        // Filter di Collection — tidak perlu query tambahan
        $ordersWithBukti = $data['orders']->whereNotNull('bukti_bayar');

        return view('kasir.laporan-bisnis', [
            'startDate'        => $startDate,
            'endDate'          => $endDate,
            'topMenus'         => $data['topMenus'],
            'leastMenus'       => $data['leastMenus'],
            'orders'           => $data['orders'],
            'ordersWithBukti'  => $ordersWithBukti,
            'totalModal'       => $data['totalModal'],
            'totalOmzet'       => $data['totalOmzet'],
            'totalPengeluaran' => $data['totalPengeluaran'],
            'totalUntung'      => $totalUntung,
            'totalRugi'        => $totalRugi,
        ]);
    }

    // ── Chart Data (AJAX) ──
    public function getChartData(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->partner) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $partnerId = $user->partner->id;
        $filter    = $request->input('filter', '1_bulan');
        $endDate   = Carbon::today('Asia/Jakarta')->toDateString();

        if ($filter == '1_hari')        $startDate = Carbon::today('Asia/Jakarta')->toDateString();
        elseif ($filter == '1_minggu')  $startDate = Carbon::today('Asia/Jakarta')->subWeek()->toDateString();
        elseif ($filter == '1_bulan')   $startDate = Carbon::today('Asia/Jakarta')->subMonth()->toDateString();
        elseif ($filter == '1_tahun')   $startDate = Carbon::today('Asia/Jakarta')->subYear()->toDateString();
        else {
            $startDate = $request->input('start_date', Carbon::today('Asia/Jakarta')->subMonth()->toDateString());
            $endDate   = $request->input('end_date', Carbon::today('Asia/Jakarta')->toDateString());
        }

        $today    = Carbon::today('Asia/Jakarta')->toDateString();
        $ttl      = ($endDate === $today) ? self::CACHE_TTL_TODAY : self::CACHE_TTL_HISTORY;
        $cacheKey = "chart_bisnis_{$partnerId}_{$startDate}_{$endDate}";

        $chartData = Cache::remember($cacheKey, $ttl, function () use ($partnerId, $startDate, $endDate) {
            $period   = CarbonPeriod::create($startDate, $endDate);
            $startTs  = $startDate . ' 00:00:00';
            $endTs    = $endDate   . ' 23:59:59';

            // 3 query agregat per tanggal — masing-masing sudah di-index partner_id + created_at
            $modals = Modal::where('partner_id', $partnerId)
                ->whereBetween('created_at', [$startTs, $endTs])
                ->selectRaw('DATE(CONVERT_TZ(created_at, "+00:00", "+07:00")) as date, SUM(nominal) as total')
                ->groupBy('date')
                ->pluck('total', 'date');

            $omzets = Order::where('partner_id', $partnerId)
                ->where('status', 'success')
                ->whereBetween('created_at', [$startTs, $endTs])
                ->selectRaw('DATE(CONVERT_TZ(created_at, "+00:00", "+07:00")) as date, SUM(total_harga) as total')
                ->groupBy('date')
                ->pluck('total', 'date');

            $pengeluarans = Pengeluaran::where('partner_id', $partnerId)
                ->whereBetween('created_at', [$startTs, $endTs])
                ->selectRaw('DATE(CONVERT_TZ(created_at, "+00:00", "+07:00")) as date, SUM(total) as total')
                ->groupBy('date')
                ->pluck('total', 'date');

            $labels = $dataModal = $dataOmzet = $dataPengeluaran = $dataUntung = $dataRugi = [];

            foreach ($period as $date) {
                $dateStr = $date->format('Y-m-d');
                $modal   = (float) ($modals[$dateStr] ?? 0);
                $omzet   = (float) ($omzets[$dateStr] ?? 0);
                $peng    = (float) ($pengeluarans[$dateStr] ?? 0);
                $untung  = $omzet - $peng;

                $labels[]          = $date->format('d M');
                $dataModal[]       = $modal;
                $dataOmzet[]       = $omzet;
                $dataPengeluaran[] = $peng;
                $dataUntung[]      = $untung > 0 ? $untung : 0;
                $dataRugi[]        = $untung < 0 ? abs($untung) : 0;
            }

            return compact('labels', 'dataModal', 'dataOmzet', 'dataPengeluaran', 'dataUntung', 'dataRugi');
        });

        return response()->json([
            'labels'   => $chartData['labels'],
            'datasets' => [
                ['label' => 'Rugi',        'data' => $chartData['dataRugi'],        'borderColor' => '#dc3545', 'backgroundColor' => 'rgba(220,53,69,0.1)',   'borderWidth' => 2, 'tension' => 0.4, 'fill' => true],
                ['label' => 'Pengeluaran', 'data' => $chartData['dataPengeluaran'], 'borderColor' => '#ffc107', 'backgroundColor' => 'rgba(255,193,7,0.1)',   'borderWidth' => 2, 'tension' => 0.4, 'fill' => true],
                ['label' => 'Untung',      'data' => $chartData['dataUntung'],      'borderColor' => '#28a745', 'backgroundColor' => 'rgba(40,167,69,0.1)',   'borderWidth' => 2, 'tension' => 0.4, 'fill' => true],
                ['label' => 'Omzet',       'data' => $chartData['dataOmzet'],       'borderColor' => '#6f42c1', 'backgroundColor' => 'rgba(111,66,193,0.1)',  'borderWidth' => 2, 'tension' => 0.4, 'fill' => true],
                ['label' => 'Modal',       'data' => $chartData['dataModal'],       'borderColor' => '#6c757d', 'backgroundColor' => 'rgba(108,117,125,0.1)', 'borderWidth' => 2, 'tension' => 0.4, 'fill' => true],
            ],
        ]);
    }

    public function export(Request $request)
    {
        $user = $request->user();

        if (!$user || !$user->partner) {
            abort(403, 'Akses ditolak: Akun ini tidak terhubung dengan data Partner manapun.');
        }

        $partnerId = $user->partner->id;
        $startDate = $request->input('start_date', Carbon::today('Asia/Jakarta')->toDateString());
        $endDate   = $request->input('end_date', Carbon::today('Asia/Jakarta')->toDateString());
        $mode      = $request->input('mode', 'lengkap');
        $fileName  = 'Laporan_keuangan_' . date('d-m-Y') . '.xlsx';

        return Excel::download(new LaporanKeuanganExport($partnerId, $startDate, $endDate, $mode), $fileName);
    }
}
