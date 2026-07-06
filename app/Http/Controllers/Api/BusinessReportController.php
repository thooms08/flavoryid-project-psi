<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

class BusinessReportController extends Controller
{
    const CACHE_TTL_HISTORY = 600; // 10 menit untuk data historis
    const CACHE_TTL_TODAY   = 120; // 2 menit untuk data hari ini

    /**
     * Mengambil ringkasan laporan bisnis, top menu, least menu, dan daftar transaksi.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user || !$user->partner) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Akses ditolak: Akun ini tidak terhubung dengan data Partner manapun.',
            ], 403);
        }

        $partnerId = $user->partner->id;

        // ── Resolusi tanggal ──
        $startDate = $request->input('start_date', Carbon::today('Asia/Jakarta')->toDateString());
        $endDate   = $request->input('end_date',   Carbon::today('Asia/Jakarta')->toDateString());

        if ($request->has('quick_filter') && !empty($request->quick_filter)) {
            $filter  = $request->quick_filter;
            $endDate = Carbon::today('Asia/Jakarta')->toDateString();
            if ($filter == '1_hari')   $startDate = Carbon::today('Asia/Jakarta')->toDateString();
            if ($filter == '1_minggu') $startDate = Carbon::today('Asia/Jakarta')->subWeek()->toDateString();
            if ($filter == '1_bulan')  $startDate = Carbon::today('Asia/Jakarta')->subMonth()->toDateString();
            if ($filter == '1_tahun')  $startDate = Carbon::today('Asia/Jakarta')->subYear()->toDateString();
        }

        $today    = Carbon::today('Asia/Jakarta')->toDateString();
        $ttl      = ($endDate === $today) ? self::CACHE_TTL_TODAY : self::CACHE_TTL_HISTORY;
        $cacheKey = "api_laporan_bisnis_{$partnerId}_{$startDate}_{$endDate}";

        $data = Cache::remember($cacheKey, $ttl, function () use ($partnerId, $startDate, $endDate) {

            $totalModal = Modal::where('partner_id', $partnerId)
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->sum('nominal');

            $totalOmzet = Order::where('partner_id', $partnerId)
                ->where('status', 'success')
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->sum('total_harga');

            $totalPengeluaran = Pengeluaran::where('partner_id', $partnerId)
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->sum('total');

            $untungBersih = $totalOmzet - $totalPengeluaran;

            $topMenus = OrderDetail::selectRaw('menus.nama_menu, SUM(order_details.qty) as total_qty')
                ->join('orders', 'orders.id', '=', 'order_details.order_id')
                ->join('menus',  'menus.id',  '=', 'order_details.menu_id')
                ->where('orders.partner_id', $partnerId)
                ->where('orders.status', 'success')
                ->whereBetween('orders.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->groupBy('menus.id', 'menus.nama_menu')
                ->orderByDesc('total_qty')
                ->limit(10)
                ->get();

            $leastMenus = Menu::select('menus.id', 'menus.nama_menu')
                ->selectRaw('(
                    SELECT MAX(orders.created_at)
                    FROM order_details
                    JOIN orders ON orders.id = order_details.order_id
                    WHERE order_details.menu_id = menus.id
                    AND orders.status = "success"
                    AND orders.created_at <= ?
                ) as last_sold_at', [$endDate . ' 23:59:59'])
                ->where('menus.partner_id', $partnerId)
                ->orderBy('last_sold_at', 'asc')
                ->limit(5)
                ->get();

            $orders = Order::with(['orderDetails.menu'])
                ->where('partner_id', $partnerId)
                ->where('status', 'success')
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->orderBy('created_at', 'desc')
                ->get();

            return compact(
                'totalModal', 'totalOmzet', 'totalPengeluaran',
                'untungBersih', 'topMenus', 'leastMenus', 'orders'
            );
        });

        $totalUntung = $data['untungBersih'] > 0 ? $data['untungBersih'] : 0;
        $totalRugi   = $data['untungBersih'] < 0 ? abs($data['untungBersih']) : 0;

        return response()->json([
            'status' => 'success',
            'data'   => [
                'start_date' => $startDate,
                'end_date'   => $endDate,
                'summary'    => [
                    'total_modal'       => (float) $data['totalModal'],
                    'total_omzet'       => (float) $data['totalOmzet'],
                    'total_pengeluaran' => (float) $data['totalPengeluaran'],
                    'total_keuntungan'  => (float) $totalUntung,
                    'total_kerugian'    => (float) $totalRugi,
                ],
                'top_menus'   => $data['topMenus'],
                'least_menus' => $data['leastMenus'],
                'orders'      => $data['orders'],
            ],
        ], 200);
    }

    /**
     * Mendapatkan data format chart koordinat/line.
     */
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
        $cacheKey = "api_chart_bisnis_{$partnerId}_{$startDate}_{$endDate}";

        $result = Cache::remember($cacheKey, $ttl, function () use ($partnerId, $startDate, $endDate) {
            $period = CarbonPeriod::create($startDate, $endDate);

            $modals = Modal::where('partner_id', $partnerId)
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->selectRaw('DATE(CONVERT_TZ(created_at, "+00:00", "+07:00")) as date, SUM(nominal) as total')
                ->groupBy('date')->pluck('total', 'date');

            $omzets = Order::where('partner_id', $partnerId)
                ->where('status', 'success')
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->selectRaw('DATE(CONVERT_TZ(created_at, "+00:00", "+07:00")) as date, SUM(total_harga) as total')
                ->groupBy('date')->pluck('total', 'date');

            $pengeluarans = Pengeluaran::where('partner_id', $partnerId)
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->selectRaw('DATE(CONVERT_TZ(created_at, "+00:00", "+07:00")) as date, SUM(total) as total')
                ->groupBy('date')->pluck('total', 'date');

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
            'labels'   => $result['labels'],
            'datasets' => [
                ['label' => 'Rugi',        'data' => $result['dataRugi'],        'color' => '#dc3545'],
                ['label' => 'Pengeluaran', 'data' => $result['dataPengeluaran'], 'color' => '#ffc107'],
                ['label' => 'Untung',      'data' => $result['dataUntung'],      'color' => '#28a745'],
                ['label' => 'Omzet',       'data' => $result['dataOmzet'],       'color' => '#6f42c1'],
                ['label' => 'Modal',       'data' => $result['dataModal'],       'color' => '#6c757d'],
            ],
        ]);
    }

    /**
     * Endpoint untuk trigger download berkas Excel laporan keuangan.
     */
    public function export(Request $request)
    {
        $user = $request->user();

        if (!$user && $request->has('token')) {
            $token = \Laravel\Sanctum\PersonalAccessToken::findToken($request->token);
            if ($token) {
                $user = $token->tokenable;
            }
        }

        if (!$user || !$user->partner) {
            return response()->json(['message' => 'Unauthorized / Token Tidak Valid'], 403);
        }

        $partnerId = $user->partner->id;
        $startDate = $request->input('start_date', Carbon::today('Asia/Jakarta')->toDateString());
        $endDate = $request->input('end_date', Carbon::today('Asia/Jakarta')->toDateString());
        $mode = $request->input('mode', 'lengkap');

        $fileName = 'Laporan_keuangan_' . date('d-m-Y') . '.xlsx';

        return Excel::download(new LaporanKeuanganExport($partnerId, $startDate, $endDate, $mode), $fileName);
    }
}