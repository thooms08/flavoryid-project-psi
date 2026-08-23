<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Menu;
use App\Models\Modal;
use App\Models\Pengeluaran;
use App\Models\Partner;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InternalLaporanController extends Controller
{
    const CACHE_TTL_TODAY   = 120;
    const CACHE_TTL_HISTORY = 600;

    private function verifyApiKey(Request $request): bool
    {
        $secret = env('INTERNAL_API_KEY');
        if (!$secret) return false;
        return $request->header('X-Internal-Key') === $secret;
    }

    /**
     * GET /api/v1/internal/laporan-bisnis
     *
     * Query params:
     *   partner_uuid, start_date, end_date, quick_filter
     */
    public function index(Request $request)
    {
        if (!$this->verifyApiKey($request)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $partnerUuid = $request->query('partner_uuid');
        if (!$partnerUuid) {
            return response()->json(['success' => false, 'message' => 'partner_uuid wajib diisi.'], 422);
        }

        $partner = Partner::where('uuid', $partnerUuid)->first();
        if (!$partner) {
            return response()->json(['success' => false, 'message' => 'Partner tidak ditemukan.'], 404);
        }

        $partnerId = $partner->id;
        $today     = Carbon::today('Asia/Jakarta')->toDateString();

        [$startDate, $endDate] = $this->resolveDates($request, $today);

        $ttl      = ($endDate === $today) ? self::CACHE_TTL_TODAY : self::CACHE_TTL_HISTORY;
        $cacheKey = "internal_laporan_{$partnerId}_{$startDate}_{$endDate}";

        $data = Cache::remember($cacheKey, $ttl, function () use ($partnerId, $startDate, $endDate) {
            $startTs = $startDate . ' 00:00:00';
            $endTs   = $endDate   . ' 23:59:59';

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

            $orders = Order::with(['orderDetails.menu'])
                ->select([
                    'id', 'partner_id', 'uuid', 'nomor_pesanan',
                    'nama_pelanggan', 'metode_bayar', 'total_harga',
                    'nominal_diskon', 'jenis_diskon', 'kode_voucher',
                    'nilai_voucher', 'uang_bayar', 'kembalian',
                    'bukti_bayar', 'created_at',
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

        return response()->json([
            'success'          => true,
            'startDate'        => $startDate,
            'endDate'          => $endDate,
            'totalModal'       => $data['totalModal'],
            'totalOmzet'       => $data['totalOmzet'],
            'totalPengeluaran' => $data['totalPengeluaran'],
            'totalUntung'      => $data['untungBersih'] > 0 ? $data['untungBersih'] : 0,
            'totalRugi'        => $data['untungBersih'] < 0 ? abs($data['untungBersih']) : 0,
            'topMenus'         => $data['topMenus'],
            'leastMenus'       => $data['leastMenus']->map(fn($m) => [
                'nama_menu'    => $m->nama_menu,
                'last_sold_at' => $m->last_sold_at,
            ]),
            'orders'           => $data['orders']->map(fn($o) => [
                'nomor_pesanan'   => $o->nomor_pesanan,
                'nama_pelanggan'  => $o->nama_pelanggan,
                'metode_bayar'    => $o->metode_bayar,
                'total_harga'     => $o->total_harga,
                'nominal_diskon'  => $o->nominal_diskon,
                'jenis_diskon'    => $o->jenis_diskon,
                'kode_voucher'    => $o->kode_voucher,
                'nilai_voucher'   => $o->nilai_voucher,
                'uang_bayar'      => $o->uang_bayar,
                'kembalian'       => $o->kembalian,
                'created_at'      => $o->created_at,
                'orderDetails'    => $o->orderDetails->map(fn($d) => [
                    'nama_menu' => $d->menu->nama_menu ?? '-',
                    'variant'   => $d->variant ?? null,
                    'qty'       => $d->qty,
                ]),
            ]),
        ]);
    }

    /**
     * GET /api/v1/internal/laporan-bisnis/chart
     *
     * Query params: partner_uuid, filter, start_date, end_date
     */
    public function chart(Request $request)
    {
        if (!$this->verifyApiKey($request)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $partnerUuid = $request->query('partner_uuid');
        if (!$partnerUuid) {
            return response()->json(['success' => false, 'message' => 'partner_uuid wajib diisi.'], 422);
        }

        $partner = Partner::where('uuid', $partnerUuid)->first();
        if (!$partner) {
            return response()->json(['success' => false, 'message' => 'Partner tidak ditemukan.'], 404);
        }

        $partnerId = $partner->id;
        $today     = Carbon::today('Asia/Jakarta')->toDateString();
        $filter    = $request->input('filter', '1_bulan');

        $endDate   = $today;
        $startDate = match($filter) {
            '1_hari'   => $today,
            '1_minggu' => Carbon::today('Asia/Jakarta')->subWeek()->toDateString(),
            '1_tahun'  => Carbon::today('Asia/Jakarta')->subYear()->toDateString(),
            'custom'   => $request->input('start_date', Carbon::today('Asia/Jakarta')->subMonth()->toDateString()),
            default    => Carbon::today('Asia/Jakarta')->subMonth()->toDateString(),
        };
        if ($filter === 'custom') {
            $endDate = $request->input('end_date', $today);
        }

        $ttl      = ($endDate === $today) ? self::CACHE_TTL_TODAY : self::CACHE_TTL_HISTORY;
        $cacheKey = "internal_chart_{$partnerId}_{$startDate}_{$endDate}";

        $chartData = Cache::remember($cacheKey, $ttl, function () use ($partnerId, $startDate, $endDate) {
            $period  = CarbonPeriod::create($startDate, $endDate);
            $startTs = $startDate . ' 00:00:00';
            $endTs   = $endDate   . ' 23:59:59';

            $modals = Modal::where('partner_id', $partnerId)
                ->whereBetween('created_at', [$startTs, $endTs])
                ->selectRaw('DATE(CONVERT_TZ(created_at, "+00:00", "+07:00")) as date, SUM(nominal) as total')
                ->groupBy('date')->pluck('total', 'date');

            $omzets = Order::where('partner_id', $partnerId)->where('status', 'success')
                ->whereBetween('created_at', [$startTs, $endTs])
                ->selectRaw('DATE(CONVERT_TZ(created_at, "+00:00", "+07:00")) as date, SUM(total_harga) as total')
                ->groupBy('date')->pluck('total', 'date');

            $pengeluarans = Pengeluaran::where('partner_id', $partnerId)
                ->whereBetween('created_at', [$startTs, $endTs])
                ->selectRaw('DATE(CONVERT_TZ(created_at, "+00:00", "+07:00")) as date, SUM(total) as total')
                ->groupBy('date')->pluck('total', 'date');

            $labels = $dataModal = $dataOmzet = $dataPengeluaran = $dataUntung = $dataRugi = [];
            foreach ($period as $date) {
                $d      = $date->format('Y-m-d');
                $modal  = (float)($modals[$d] ?? 0);
                $omzet  = (float)($omzets[$d] ?? 0);
                $peng   = (float)($pengeluarans[$d] ?? 0);
                $untung = $omzet - $peng;

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

    private function resolveDates(Request $request, string $today): array
    {
        $startDate = $request->query('start_date', $today);
        $endDate   = $request->query('end_date', $today);

        if ($request->has('quick_filter')) {
            $endDate = $today;
            $startDate = match($request->quick_filter) {
                '1_hari'   => $today,
                '1_minggu' => Carbon::today('Asia/Jakarta')->subWeek()->toDateString(),
                '1_bulan'  => Carbon::today('Asia/Jakarta')->subMonth()->toDateString(),
                '1_tahun'  => Carbon::today('Asia/Jakarta')->subYear()->toDateString(),
                default    => $today,
            };
        }

        return [$startDate, $endDate];
    }
}
