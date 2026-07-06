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

class KasirLaporanBisnisController extends Controller
{
    // TTL cache: data historis (bukan hari ini) di-cache 10 menit,
    // data hari ini 2 menit agar tetap near-realtime.
    const CACHE_TTL_HISTORY = 600;  // 10 menit
    const CACHE_TTL_TODAY   = 120;  // 2 menit

    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user || !$user->partner) {
            abort(403, 'Akses ditolak: Akun ini tidak terhubung dengan data Partner manapun.');
        }

        $partnerId = $user->partner->id;

        // ── Resolusi tanggal ──
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

        // TTL lebih pendek jika range mencakup hari ini
        $today = Carbon::today('Asia/Jakarta')->toDateString();
        $ttl   = ($endDate === $today) ? self::CACHE_TTL_TODAY : self::CACHE_TTL_HISTORY;

        // ── Cache key unik per partner + rentang tanggal ──
        $cacheKey = "laporan_bisnis_{$partnerId}_{$startDate}_{$endDate}";

        $data = Cache::remember($cacheKey, $ttl, function () use ($partnerId, $startDate, $endDate) {

            // --- Summary Keuangan ---
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

            // --- Top 10 Menu Paling Laku ---
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

            // --- 5 Menu Paling Tidak Laku ---
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

            // --- Tabel Laporan ---
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

        // Kalkulasi di luar cache agar tidak menyimpan koleksi filter turunan
        $totalUntung     = $data['untungBersih'] > 0 ? $data['untungBersih'] : 0;
        $totalRugi       = $data['untungBersih'] < 0 ? abs($data['untungBersih']) : 0;
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

        if ($filter == '1_hari')   $startDate = Carbon::today('Asia/Jakarta')->toDateString();
        elseif ($filter == '1_minggu') $startDate = Carbon::today('Asia/Jakarta')->subWeek()->toDateString();
        elseif ($filter == '1_bulan')  $startDate = Carbon::today('Asia/Jakarta')->subMonth()->toDateString();
        elseif ($filter == '1_tahun')  $startDate = Carbon::today('Asia/Jakarta')->subYear()->toDateString();
        else {
            $startDate = $request->input('start_date', Carbon::today('Asia/Jakarta')->subMonth()->toDateString());
            $endDate   = $request->input('end_date', Carbon::today('Asia/Jakarta')->toDateString());
        }

        $today    = Carbon::today('Asia/Jakarta')->toDateString();
        $ttl      = ($endDate === $today) ? self::CACHE_TTL_TODAY : self::CACHE_TTL_HISTORY;
        $cacheKey = "chart_bisnis_{$partnerId}_{$startDate}_{$endDate}";

        $chartData = Cache::remember($cacheKey, $ttl, function () use ($partnerId, $startDate, $endDate) {
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

                $labels[]           = $date->format('d M');
                $dataModal[]        = $modal;
                $dataOmzet[]        = $omzet;
                $dataPengeluaran[]  = $peng;
                $dataUntung[]       = $untung > 0 ? $untung : 0;
                $dataRugi[]         = $untung < 0 ? abs($untung) : 0;
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
