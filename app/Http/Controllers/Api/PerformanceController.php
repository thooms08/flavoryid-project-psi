<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PerformanceController extends Controller
{
    public function index(Request $request)
    {
        $timezone = 'Asia/Jakarta';
        Carbon::setLocale('id');

        $user = $request->user();
        if (!$user || !$user->partner) {
            return response()->json([
                'status' => 'error',
                'message' => 'Partner tidak ditemukan',
            ], 403);
        }

        $partnerId = $user->partner->id;

        // ======================================================
        // 1. FILTER WAKTU DINAMIS
        // ======================================================
        $filter = $request->input('quick_filter', '1_bulan');
        $endDateStr = $request->input('end_date', Carbon::today($timezone)->toDateString());
        $startDateStr = $request->input('start_date');

        if ($request->has('quick_filter') && $filter !== 'custom') {
            $endDate = Carbon::today($timezone);

            if ($filter === '1_hari') {
                $startDate = Carbon::today($timezone);
            } elseif ($filter === '1_minggu') {
                $startDate = Carbon::today($timezone)->subDays(6);
            } elseif ($filter === '1_bulan') {
                $startDate = Carbon::today($timezone)->subMonth();
            } elseif ($filter === '1_tahun') {
                $startDate = Carbon::today($timezone)->subYear();
            } else {
                $startDate = Carbon::today($timezone)->subMonth();
            }
        } else {
            $endDate = Carbon::parse($endDateStr, $timezone);
            $startDate = $startDateStr
                ? Carbon::parse($startDateStr, $timezone)
                : Carbon::today($timezone)->subMonth();

            $filter = 'custom';
        }

        $currentStart = $startDate->copy()->startOfDay();
        $currentEnd = $endDate->copy()->endOfDay();

        $diffInDays = max(1, $currentStart->diffInDays($currentEnd));
        $prevEnd = $currentStart->copy()->subSecond();
        $prevStart = $prevEnd->copy()->subDays($diffInDays)->startOfDay();

        $labelCurrent = 'Periode Ini';
        $labelPrev = 'Periode Sebelumnya';

        if ($filter === '1_hari') {
            $labelCurrent = 'Hari Ini';
            $labelPrev = 'Kemarin';
        } elseif ($filter === '1_minggu') {
            $labelCurrent = '7 Hari Terakhir';
            $labelPrev = '7 Hari Sebelumnya';
        } elseif ($filter === '1_bulan') {
            $labelCurrent = 'Bulan Ini';
            $labelPrev = 'Bulan Lalu';
        } elseif ($filter === '1_tahun') {
            $labelCurrent = 'Tahun Ini';
            $labelPrev = 'Tahun Lalu';
        }

        // ======================================================
        // 2. QUERY DATA SUCCESS DI RENTANG RELEVAN — dengan cache
        // ======================================================
        $today    = Carbon::today($timezone)->toDateString();
        $ttl      = ($currentEnd->toDateString() === $today) ? 120 : 600;
        $cacheKey = "api_performa_{$partnerId}_{$currentStart->toDateString()}_{$currentEnd->toDateString()}";

        // Search tidak di-cache karena hasilnya unik per keyword
        if ($request->filled('search')) {
            $search = trim($request->search);
            $allOrders = Order::with(['orderDetails.menu'])
                ->where('partner_id', $partnerId)
                ->where('status', 'success')
                ->whereBetween('created_at', [
                    $prevStart->copy()->startOfDay(),
                    $currentEnd->copy()->endOfDay(),
                ])
                ->where(function ($q) use ($search) {
                    $q->where('nomor_pesanan', 'like', "%{$search}%")
                      ->orWhere('nama_pelanggan', 'like', "%{$search}%")
                      ->orWhere('no_whatsapp', 'like', "%{$search}%");
                })
                ->get();
        } else {
            $allOrders = Cache::remember($cacheKey, $ttl, function () use (
                $partnerId, $prevStart, $currentEnd
            ) {
                return Order::with(['orderDetails.menu'])
                    ->where('partner_id', $partnerId)
                    ->where('status', 'success')
                    ->whereBetween('created_at', [
                        $prevStart->copy()->startOfDay(),
                        $currentEnd->copy()->endOfDay(),
                    ])
                    ->get();
            });
        }

        $currentOrders = $allOrders->filter(function ($order) use ($currentStart, $currentEnd, $timezone) {
            $createdAt = Carbon::parse($order->created_at)->timezone($timezone);
            return $createdAt->between($currentStart, $currentEnd);
        })->values();

        $prevOrders = $allOrders->filter(function ($order) use ($prevStart, $prevEnd, $timezone) {
            $createdAt = Carbon::parse($order->created_at)->timezone($timezone);
            return $createdAt->between($prevStart, $prevEnd);
        })->values();

        // ======================================================
        // 3. POLA TRANSAKSI PER HARI / JAM / TANGGAL / BULAN
        // ======================================================
        $byDay = [];
        $byHour = [];
        $byDate = [];
        $byMonth = [];

        foreach ($currentOrders as $order) {
            $date = Carbon::parse($order->created_at)->timezone($timezone);
            $dayLabel = $date->translatedFormat('l');
            $hourLabel = $date->format('H:00');
            $dateLabel = $date->translatedFormat('d M Y');
            $monthLabel = $date->translatedFormat('F Y');

            $byDay[$dayLabel] = ($byDay[$dayLabel] ?? 0) + 1;
            $byHour[$hourLabel] = ($byHour[$hourLabel] ?? 0) + 1;
            $byDate[$dateLabel] = ($byDate[$dateLabel] ?? 0) + 1;
            $byMonth[$monthLabel] = ($byMonth[$monthLabel] ?? 0) + 1;
        }

        $getExtremes = function (array $array) {
            if (empty($array)) {
                return ['max' => 'Belum ada', 'min' => 'Belum ada'];
            }

            $maxVal = max($array);
            $minVal = min($array);

            return [
                'max' => array_keys($array, $maxVal)[0] . ' (' . $maxVal . ' trx)',
                'min' => array_keys($array, $minVal)[0] . ' (' . $minVal . ' trx)',
            ];
        };

        // ======================================================
        // 4. METRIK INTI
        // ======================================================
        $omzetCurrent = (float) $currentOrders->sum('total_harga');
        $omzetPrev = (float) $prevOrders->sum('total_harga');

        $countCurrent = (int) $currentOrders->count();
        $countPrev = (int) $prevOrders->count();

        $avgTicketCurrent = $countCurrent > 0 ? $omzetCurrent / $countCurrent : 0;
        $avgTicketPrev = $countPrev > 0 ? $omzetPrev / $countPrev : 0;

        $growthRate = 0;
        if ($omzetPrev > 0) {
            $growthRate = (($omzetCurrent - $omzetPrev) / $omzetPrev) * 100;
        } elseif ($omzetCurrent > 0) {
            $growthRate = 100;
        }

        $discountCurrent = (float) $currentOrders->sum('nominal_diskon');
        $discountRatio = $omzetCurrent > 0 ? ($discountCurrent / $omzetCurrent) * 100 : 0;

        $offlineCount = $currentOrders->filter(function ($order) {
            return $this->isOfflineOrder($order);
        })->count();

        $offlineShare = $countCurrent > 0 ? ($offlineCount / $countCurrent) * 100 : 0;

        $ordersPerDay = $diffInDays > 0 ? ($countCurrent / ($diffInDays + 1)) : $countCurrent;

        // ======================================================
        // 5. ANALISIS MENU
        // ======================================================
        $menuAnalytics = $this->buildMenuAnalytics($currentOrders, $timezone);
        $topMenus = $menuAnalytics['top_menus'];
        $leastMenus = $menuAnalytics['least_menus'];
        $top3Share = $menuAnalytics['top3_share'];
        $topMenuName = $menuAnalytics['top_menu_name'];
        $topMenuRevenue = $menuAnalytics['top_menu_revenue'];
        $topMenuQty = $menuAnalytics['top_menu_qty'];

        // ======================================================
        // 6. CONTOH DETEKSI JAM TERBAIK / HARI TERBAIK
        // ======================================================
        $bestDay = $this->pickBestLabel($byDay);
        $bestHour = $this->pickBestLabel($byHour);

        // ======================================================
        // 7. NOVITA AI INSIGHT YANG LEBIH STRATEGIS
        // ======================================================
        $novita = $this->buildNovitaInsight([
            'labelCurrent' => $labelCurrent,
            'labelPrev' => $labelPrev,
            'growthRate' => $growthRate,
            'omzetCurrent' => $omzetCurrent,
            'omzetPrev' => $omzetPrev,
            'countCurrent' => $countCurrent,
            'countPrev' => $countPrev,
            'avgTicketCurrent' => $avgTicketCurrent,
            'avgTicketPrev' => $avgTicketPrev,
            'discountRatio' => $discountRatio,
            'offlineShare' => $offlineShare,
            'ordersPerDay' => $ordersPerDay,
            'top3Share' => $top3Share,
            'bestDay' => $bestDay,
            'bestHour' => $bestHour,
            'topMenuName' => $topMenuName,
            'topMenuQty' => $topMenuQty,
            'topMenuRevenue' => $topMenuRevenue,
            'countCurrent' => $countCurrent,
            'countPrev' => $countPrev,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'stats' => [
                    'hari' => $getExtremes($byDay),
                    'jam' => $getExtremes($byHour),
                    'tanggal' => $getExtremes($byDate),
                    'bulan' => $getExtremes($byMonth),
                ],
                'komparasi' => [
                    'omzet_current' => $omzetCurrent,
                    'omzet_prev' => $omzetPrev,
                    'count_current' => $countCurrent,
                    'count_prev' => $countPrev,
                    'growth_rate' => $growthRate,
                    'label_current' => $labelCurrent,
                    'label_prev' => $labelPrev,
                    'avg_ticket_current' => $avgTicketCurrent,
                    'avg_ticket_prev' => $avgTicketPrev,
                    'discount_ratio' => $discountRatio,
                    'offline_share' => $offlineShare,
                    'orders_per_day' => $ordersPerDay,
                    'top3_share' => $top3Share,
                    'best_day' => $bestDay,
                    'best_hour' => $bestHour,
                    'top_menu_name' => $topMenuName,
                    'top_menu_qty' => $topMenuQty,
                    'top_menu_revenue' => $topMenuRevenue,
                ],
                'insight' => [
                    'text' => $novita['headline'],
                    'class' => $novita['class'],
                    'icon' => $novita['icon'],
                    'mengapa' => $novita['mengapa'],
                    'risiko' => $novita['risiko'],
                    'solusi' => $novita['solusi'],
                    'confidence' => $novita['confidence'],
                ],
                'novita' => $novita,
                'chart' => [
                    'labels' => [$labelPrev, $labelCurrent],
                    'omzet' => [$omzetPrev, $omzetCurrent],
                ],
                'filter' => [
                    'start' => $currentStart->format('Y-m-d'),
                    'end' => $currentEnd->format('Y-m-d'),
                    'quick_filter' => $filter,
                ],
                'meta' => [
                    'generated_at' => Carbon::now($timezone)->toIso8601String(),
                ],
            ],
        ]);
    }

    private function isOfflineOrder($order): bool
    {
        $isOfflineFlag = filter_var($order->is_offline ?? false, FILTER_VALIDATE_BOOLEAN);
        $offlineId = $order->offline_id ?? null;
        $rawNomor = strtolower(trim((string) ($order->nomor_pesanan ?? '')));
        $keterangan = strtolower((string) ($order->keterangan ?? ''));

        return $isOfflineFlag
            || !empty($offlineId)
            || $rawNomor === 'null'
            || $rawNomor === ''
            || str_contains($keterangan, 'offline');
    }

    private function buildMenuAnalytics($orders, string $timezone): array
    {
        $menuStats = [];

        foreach ($orders as $order) {
            if (empty($order->orderDetails)) {
                continue;
            }

            foreach ($order->orderDetails as $detail) {
                $menuName = $detail->menu->nama_menu ?? $detail->nama_menu ?? 'Menu Terhapus';
                $key = $menuName . '|' . ($detail->variant ?? '');

                $qty = (int) ($detail->qty ?? 0);
                $harga = (float) ($detail->harga ?? 0);
                $revenue = $qty * $harga;

                if (!isset($menuStats[$key])) {
                    $menuStats[$key] = [
                        'nama_menu' => $menuName,
                        'variant' => $detail->variant ?? '',
                        'total_qty' => 0,
                        'total_revenue' => 0,
                        'orders_count' => 0,
                        'last_sold_at' => null,
                    ];
                }

                $menuStats[$key]['total_qty'] += $qty;
                $menuStats[$key]['total_revenue'] += $revenue;
                $menuStats[$key]['orders_count'] += 1;

                $createdAt = Carbon::parse($order->created_at)->timezone($timezone);
                if (
                    $menuStats[$key]['last_sold_at'] === null ||
                    Carbon::parse($menuStats[$key]['last_sold_at'])->lt($createdAt)
                ) {
                    $menuStats[$key]['last_sold_at'] = $createdAt->toIso8601String();
                }
            }
        }

        $all = array_values($menuStats);

        usort($all, function ($a, $b) {
            if ($b['total_qty'] !== $a['total_qty']) {
                return $b['total_qty'] <=> $a['total_qty'];
            }

            return $b['total_revenue'] <=> $a['total_revenue'];
        });

        $least = $all;
        usort($least, function ($a, $b) {
            if ($a['total_qty'] !== $b['total_qty']) {
                return $a['total_qty'] <=> $b['total_qty'];
            }

            return $a['total_revenue'] <=> $b['total_revenue'];
        });

        $totalQty = array_sum(array_map(function ($item) {
            return (int) $item['total_qty'];
        }, $all));

        $top3Qty = array_sum(array_map(function ($item) {
            return (int) $item['total_qty'];
        }, array_slice($all, 0, 3)));

        $topMenu = $all[0] ?? [
            'nama_menu' => 'Belum Ada Data',
            'total_qty' => 0,
            'total_revenue' => 0,
        ];

        return [
            'top_menus' => array_slice($all, 0, 10),
            'least_menus' => array_slice($least, 0, 5),
            'top3_share' => $totalQty > 0 ? ($top3Qty / $totalQty) * 100 : 0,
            'top_menu_name' => $topMenu['nama_menu'] ?? 'Belum Ada Data',
            'top_menu_qty' => (int) ($topMenu['total_qty'] ?? 0),
            'top_menu_revenue' => (float) ($topMenu['total_revenue'] ?? 0),
        ];
    }

    private function pickBestLabel(array $groups): string
    {
        if (empty($groups)) {
            return 'Belum ada';
        }

        arsort($groups);
        $label = array_key_first($groups);
        $value = $groups[$label];

        return $label . ' (' . $value . ' trx)';
    }

    private function buildNovitaInsight(array $metrics): array
    {
        $growthRate = (float) $metrics['growthRate'];
        $omzetCurrent = (float) $metrics['omzetCurrent'];
        $omzetPrev = (float) $metrics['omzetPrev'];
        $countCurrent = (int) $metrics['countCurrent'];
        $countPrev = (int) $metrics['countPrev'];
        $avgTicketCurrent = (float) $metrics['avgTicketCurrent'];
        $avgTicketPrev = (float) $metrics['avgTicketPrev'];
        $discountRatio = (float) $metrics['discountRatio'];
        $offlineShare = (float) $metrics['offlineShare'];
        $ordersPerDay = (float) $metrics['ordersPerDay'];
        $top3Share = (float) $metrics['top3Share'];
        $bestDay = (string) $metrics['bestDay'];
        $bestHour = (string) $metrics['bestHour'];
        $topMenuName = (string) $metrics['topMenuName'];
        $topMenuQty = (int) $metrics['topMenuQty'];
        $topMenuRevenue = (float) $metrics['topMenuRevenue'];

        $trend = 'stabil';
        if ($growthRate >= 15) {
            $trend = 'naik';
        } elseif ($growthRate <= -15) {
            $trend = 'turun';
        }

        $confidence = 55;
        $confidence += min(15, (int) round($countCurrent * 1.5));
        $confidence += min(10, (int) round($ordersPerDay));
        if ($countCurrent < 10) {
            $confidence -= 12;
        }
        if ($countPrev > 0) {
            $confidence += 5;
        }
        $confidence = max(35, min(95, $confidence));

        $why = [];
        $risk = [];
        $action = [];
        $recommendations = [];

        $why[] = 'Perbandingan ' . $metrics['labelCurrent'] . ' vs ' . $metrics['labelPrev'] . ' menunjukkan arah bisnis yang sedang Anda hadapi.';
        $why[] = 'Rata-rata nilai transaksi saat ini berada di ' . $this->formatRp($avgTicketCurrent) . ', sedangkan periode sebelumnya ' . $this->formatRp($avgTicketPrev) . '.';
        $why[] = 'Menu pendorong utama saat ini adalah ' . $topMenuName . ' dengan volume ' . $topMenuQty . ' porsi dan omzet kontribusi ' . $this->formatRp($topMenuRevenue) . '.';

        if ($growthRate > 0) {
            $why[] = 'Omzet naik ' . number_format($growthRate, 1) . '% sehingga momentum penjualan sedang aktif dan layak diperkuat.';
        } elseif ($growthRate < 0) {
            $why[] = 'Omzet turun ' . number_format(abs($growthRate), 1) . '% sehingga ada potensi kehilangan traffic atau penurunan konversi.';
        } else {
            $why[] = 'Omzet relatif stabil, artinya bisnis cukup terkendali tetapi belum menunjukkan percepatan pertumbuhan.';
        }

        if ($top3Share >= 60) {
            $why[] = 'Tiga menu teratas menyumbang ' . number_format($top3Share, 1) . '% dari total item, artinya bisnis cukup bergantung pada menu inti.';
        }

        if ($discountRatio > 10) {
            $risk[] = 'Porsi diskon mencapai ' . number_format($discountRatio, 1) . '% dari omzet. Ini efektif untuk mendorong transaksi, tetapi margin perlu diawasi.';
        } else {
            $risk[] = 'Tekanan diskon masih relatif aman, sehingga ruang profit belum terlalu terkikis.';
        }

        if ($offlineShare > 0) {
            $risk[] = 'Transaksi offline yang tersinkron masih berkontribusi sekitar ' . number_format($offlineShare, 1) . '% pada periode ini. Pastikan sinkronisasi dan pencatatan tetap konsisten.';
        }

        if ($trend === 'naik') {
            $risk[] = 'Pertumbuhan cepat perlu dijaga agar tidak kehilangan momentum ketika stok, layanan, atau antrian mulai padat.';
            $action[] = 'Perkuat stok menu terlaris dan naikkan target upsell pada jam paling ramai.';
            $action[] = 'Buat bundling premium untuk menaikkan average ticket order tanpa menunggu jumlah pelanggan bertambah.';
            $recommendations[] = [
                'title' => 'Amankan momentum',
                'priority' => 'Tinggi',
                'action' => 'Fokuskan promosi pada jam terbaik ' . $bestHour . ' dan dorong add-on bernilai tinggi.',
            ];
        } elseif ($trend === 'turun') {
            $risk[] = 'Jika penurunan berlanjut, arus kas bisa melemah dan stok menu tertentu berisiko menumpuk terlalu lama.';
            $action[] = 'Luncurkan promo singkat di hari dengan performa terendah untuk mengangkat traffic cepat.';
            $action[] = 'Evaluasi menu yang lambat terjual lalu ubah menjadi paket hemat atau cross-sell.';
            $recommendations[] = [
                'title' => 'Pulihkan traffic',
                'priority' => 'Tinggi',
                'action' => 'Gunakan promo berbasis waktu di ' . $bestDay . ' untuk menghidupkan penjualan pada titik lemah.',
            ];
        } else {
            $risk[] = 'Bisnis stabil, tetapi stabil saja belum cukup untuk mengungguli kompetitor yang lebih agresif.';
            $action[] = 'Manfaatkan periode stabil untuk uji menu baru, promo bundling, atau program loyalitas.';
            $action[] = 'Dorong repeat order dengan voucher transaksi berikutnya agar customer lebih sering kembali.';
            $recommendations[] = [
                'title' => 'Naikkan konversi',
                'priority' => 'Sedang',
                'action' => 'Optimalkan menu andalan pada jam ramai dan perbesar nilai transaksi rata-rata.',
            ];
        }

        if ($ordersPerDay > 0) {
            $action[] = 'Rata-rata transaksi harian berada di ' . number_format($ordersPerDay, 1) . ' order/hari. Ini cocok untuk penyusunan target shift dan stok.';
        }

        $recommendations[] = [
            'title' => 'Fokus jam puncak',
            'priority' => 'Tinggi',
            'action' => 'Prioritaskan promosi dan staf di ' . $bestHour . ' karena itulah titik paling efisien untuk mengubah kunjungan menjadi penjualan.',
        ];

        $recommendations[] = [
            'title' => 'Pertahankan menu inti',
            'priority' => 'Sedang',
            'action' => 'Perkuat menu ' . $topMenuName . ' sebagai anchor product sambil menambahkan upsell kecil untuk menaikkan margin.',
        ];

        if ($countCurrent < 5) {
            $confidence -= 10;
        }

        return [
            'headline' => $trend === 'naik'
                ? 'Novita melihat momentum bisnis sedang menguat.'
                : ($trend === 'turun'
                    ? 'Novita mendeteksi perlambatan yang perlu segera ditangani.'
                    : 'Novita melihat bisnis Anda stabil dan siap dioptimalkan.'),
            'summary' => 'Omzet ' . $metrics['labelCurrent'] . ' adalah ' . $this->formatRp($omzetCurrent) .
                ' dari ' . number_format($countCurrent) . ' transaksi, dibanding periode sebelumnya ' .
                $this->formatRp($omzetPrev) . ' dari ' . number_format($countPrev) . ' transaksi.',
            'class' => $growthRate > 0 ? 'text-success' : ($growthRate < 0 ? 'text-danger' : 'text-info'),
            'icon' => $growthRate > 0 ? 'bi-graph-up-arrow' : ($growthRate < 0 ? 'bi-graph-down-arrow' : 'bi-arrow-right-circle'),
            'mengapa' => implode("\n", $why),
            'risiko' => implode("\n", $risk),
            'solusi' => implode("\n", $action),
            'confidence' => $confidence,
            'trend' => $trend,
            'recommendations' => $recommendations,
            'signals' => [
                'avg_ticket_current' => $avgTicketCurrent,
                'avg_ticket_prev' => $avgTicketPrev,
                'discount_ratio' => $discountRatio,
                'offline_share' => $offlineShare,
                'orders_per_day' => $ordersPerDay,
                'top3_share' => $top3Share,
                'best_day' => $bestDay,
                'best_hour' => $bestHour,
            ],
            'summary_cards' => [
                'top_menu' => $topMenuName,
                'top_menu_qty' => $topMenuQty,
                'top_menu_revenue' => $topMenuRevenue,
                'avg_ticket_growth' => $avgTicketPrev > 0
                    ? (($avgTicketCurrent - $avgTicketPrev) / $avgTicketPrev) * 100
                    : 0,
            ],
        ];
    }

    private function formatRp($value): string
    {
        $number = (float) $value;
        return 'Rp ' . number_format($number, 0, ',', '.');
    }
}