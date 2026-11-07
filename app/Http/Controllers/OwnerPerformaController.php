<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class OwnerPerformaController extends Controller
{
    /**
     * Ambil partner kasir yang terhubung ke owner yang sedang login.
     */
    private function getKasirPartner(): \App\Models\Partner
    {
        $owner      = Auth::user();
        $ownerKasir = \App\Models\OwnerKasir::where('owner_id', $owner->id)->first();

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
        $timezone = 'Asia/Jakarta';
        Carbon::setLocale('id');
        $partnerId = $this->getKasirPartner()->id;

        // ══════════════════════════════════════════════════
        // 1. FILTER WAKTU DINAMIS
        // ══════════════════════════════════════════════════
        $filter     = $request->input('quick_filter', '1_bulan');
        $endDateStr = $request->input('end_date', Carbon::today($timezone)->toDateString());
        $startDateStr = $request->input('start_date');

        if ($request->has('quick_filter')) {
            $endDate = Carbon::today($timezone);
            $startDate = match ($filter) {
                '1_hari'   => Carbon::today($timezone),
                '1_minggu' => Carbon::today($timezone)->subDays(6),
                '1_tahun'  => Carbon::today($timezone)->subYear(),
                default    => Carbon::today($timezone)->subMonth(),
            };
        } else {
            $endDate   = Carbon::parse($endDateStr, $timezone);
            $startDate = $startDateStr
                ? Carbon::parse($startDateStr, $timezone)
                : Carbon::today($timezone)->subMonth();
            $filter = 'custom';
        }

        $currentStart = $startDate->copy()->startOfDay();
        $currentEnd   = $endDate->copy()->endOfDay();
        $diffInDays   = max(1, $currentStart->diffInDays($currentEnd));

        $prevEnd   = $currentStart->copy()->subSecond();
        $prevStart = $prevEnd->copy()->subDays($diffInDays)->startOfDay();

        $labelCurrent = 'Periode Ini';
        $labelPrev    = 'Periode Sebelumnya';
        if ($filter === '1_hari')   { $labelCurrent = 'Hari Ini';        $labelPrev = 'Kemarin'; }
        elseif ($filter === '1_minggu') { $labelCurrent = '7 Hari Terakhir'; $labelPrev = '7 Hari Sebelumnya'; }
        elseif ($filter === '1_bulan')  { $labelCurrent = 'Bulan Ini';       $labelPrev = 'Bulan Lalu'; }
        elseif ($filter === '1_tahun')  { $labelCurrent = 'Tahun Ini';       $labelPrev = 'Tahun Lalu'; }

        // ══════════════════════════════════════════════════
        // 2. QUERY — cache agar tidak hit MySQL tiap request
        // ══════════════════════════════════════════════════
        $today    = Carbon::today($timezone)->toDateString();
        $ttl      = ($currentEnd->toDateString() === $today) ? 120 : 600;
        $cacheKey = "performa_{$partnerId}_{$currentStart->toDateString()}_{$currentEnd->toDateString()}";

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

        $currentOrders = $allOrders->filter(fn($o) =>
            Carbon::parse($o->created_at)->timezone($timezone)->between($currentStart, $currentEnd)
        )->values();

        $prevOrders = $allOrders->filter(fn($o) =>
            Carbon::parse($o->created_at)->timezone($timezone)->between($prevStart, $prevEnd)
        )->values();

        // ══════════════════════════════════════════════════
        // 3. STATISTIK KARAKTERISTIK
        // ══════════════════════════════════════════════════
        $byDay = []; $byHour = []; $byDate = []; $byMonth = [];

        foreach ($currentOrders as $order) {
            $d = Carbon::parse($order->created_at)->timezone($timezone);
            $byDay[$d->translatedFormat('l')]      = ($byDay[$d->translatedFormat('l')]      ?? 0) + 1;
            $byHour[$d->format('H:00')]            = ($byHour[$d->format('H:00')]            ?? 0) + 1;
            $byDate[$d->translatedFormat('d M Y')] = ($byDate[$d->translatedFormat('d M Y')] ?? 0) + 1;
            $byMonth[$d->translatedFormat('F Y')]  = ($byMonth[$d->translatedFormat('F Y')]  ?? 0) + 1;
        }

        $getExtremes = function (array $arr) {
            if (empty($arr)) return ['max' => '-', 'min' => '-'];
            $maxVal = max($arr); $minVal = min($arr);
            return [
                'max' => array_keys($arr, $maxVal)[0] . ' (' . $maxVal . ' trx)',
                'min' => array_keys($arr, $minVal)[0] . ' (' . $minVal . ' trx)',
            ];
        };

        $hariStats    = $getExtremes($byDay);
        $jamStats     = $getExtremes($byHour);
        $tanggalStats = $getExtremes($byDate);
        $bulanStats   = $getExtremes($byMonth);

        // ══════════════════════════════════════════════════
        // 4. METRIK INTI
        // ══════════════════════════════════════════════════
        $omzetCurrent = (float) $currentOrders->sum('total_harga');
        $omzetPrev    = (float) $prevOrders->sum('total_harga');
        $countCurrent = (int)   $currentOrders->count();
        $countPrev    = (int)   $prevOrders->count();

        $avgTicketCurrent = $countCurrent > 0 ? $omzetCurrent / $countCurrent : 0;
        $avgTicketPrev    = $countPrev    > 0 ? $omzetPrev    / $countPrev    : 0;

        $growthRate = 0;
        if ($omzetPrev > 0) {
            $growthRate = (($omzetCurrent - $omzetPrev) / $omzetPrev) * 100;
        } elseif ($omzetCurrent > 0) {
            $growthRate = 100;
        }

        $discountCurrent = (float) $currentOrders->sum('nominal_diskon');
        $discountRatio   = $omzetCurrent > 0 ? ($discountCurrent / $omzetCurrent) * 100 : 0;

        $ordersPerDay = $diffInDays > 0 ? ($countCurrent / ($diffInDays + 1)) : $countCurrent;

        // Best day & hour
        $bestDay  = $this->pickBest($byDay);
        $bestHour = $this->pickBest($byHour);

        // ══════════════════════════════════════════════════
        // 5. ANALISIS MENU
        // ══════════════════════════════════════════════════
        $menuStats = [];
        foreach ($currentOrders as $order) {
            foreach ($order->orderDetails as $detail) {
                $name = $detail->menu->nama_menu ?? 'Menu Terhapus';
                $key  = $name . '|' . ($detail->variant ?? '');
                if (!isset($menuStats[$key])) {
                    $menuStats[$key] = ['nama' => $name, 'qty' => 0, 'revenue' => 0];
                }
                $menuStats[$key]['qty']     += (int)   $detail->qty;
                $menuStats[$key]['revenue'] += (float) $detail->qty * $detail->harga;
            }
        }
        usort($menuStats, fn($a, $b) => $b['qty'] <=> $a['qty']);
        $topMenu        = $menuStats[0] ?? ['nama' => 'Belum Ada Data', 'qty' => 0, 'revenue' => 0];
        $totalQty       = array_sum(array_column($menuStats, 'qty'));
        $top3Qty        = array_sum(array_column(array_slice($menuStats, 0, 3), 'qty'));
        $top3Share      = $totalQty > 0 ? ($top3Qty / $totalQty) * 100 : 0;
        $topMenuName    = $topMenu['nama'];
        $topMenuQty     = $topMenu['qty'];
        $topMenuRevenue = $topMenu['revenue'];

        // ══════════════════════════════════════════════════
        // 6. CONFIDENCE SCORE
        // ══════════════════════════════════════════════════
        $confidence = 50;
        $confidence += min(20, (int) round($countCurrent * 1.5));
        $confidence += min(10, (int) round($ordersPerDay));
        if ($countCurrent < 10) $confidence -= 15;
        if ($countPrev > 0)     $confidence += 5;
        $confidence = max(30, min(97, $confidence));

        // ══════════════════════════════════════════════════
        // 7. MINEAI INSIGHT ENGINE — logika berlapis
        // ══════════════════════════════════════════════════
        $insight = $this->buildMineAIInsight([
            'growthRate'       => $growthRate,
            'omzetCurrent'     => $omzetCurrent,
            'omzetPrev'        => $omzetPrev,
            'countCurrent'     => $countCurrent,
            'countPrev'        => $countPrev,
            'avgTicketCurrent' => $avgTicketCurrent,
            'avgTicketPrev'    => $avgTicketPrev,
            'discountRatio'    => $discountRatio,
            'ordersPerDay'     => $ordersPerDay,
            'top3Share'        => $top3Share,
            'topMenuName'      => $topMenuName,
            'topMenuQty'       => $topMenuQty,
            'topMenuRevenue'   => $topMenuRevenue,
            'bestDay'          => $bestDay,
            'bestHour'         => $bestHour,
            'labelCurrent'     => $labelCurrent,
            'labelPrev'        => $labelPrev,
            'confidence'       => $confidence,
            'diffInDays'       => $diffInDays,
        ]);

        $chartData  = ['labels' => [$labelPrev, $labelCurrent], 'omzet' => [$omzetPrev, $omzetCurrent]];
        $startInput = $currentStart->format('Y-m-d');
        $endInput   = $currentEnd->format('Y-m-d');

        return view('owner.performa-bisnis', compact(
            'hariStats', 'jamStats', 'tanggalStats', 'bulanStats',
            'omzetCurrent', 'omzetPrev', 'countCurrent', 'countPrev',
            'growthRate', 'chartData', 'labelCurrent', 'labelPrev',
            'startInput', 'endInput', 'confidence',
            'topMenuName', 'topMenuQty', 'topMenuRevenue',
            'bestDay', 'bestHour', 'ordersPerDay', 'discountRatio',
            'avgTicketCurrent', 'avgTicketPrev', 'top3Share',
            'insight'
        ));
    }

    // ──────────────────────────────────────────────────
    //  MINEAI INSIGHT ENGINE
    //  Menghasilkan analisis berlapis dari sinyal data
    // ──────────────────────────────────────────────────
    private function buildMineAIInsight(array $m): array
    {
        $g          = (float) $m['growthRate'];
        $omzetC     = (float) $m['omzetCurrent'];
        $omzetP     = (float) $m['omzetPrev'];
        $countC     = (int)   $m['countCurrent'];
        $countP     = (int)   $m['countPrev'];
        $avgC       = (float) $m['avgTicketCurrent'];
        $avgP       = (float) $m['avgTicketPrev'];
        $disc       = (float) $m['discountRatio'];
        $opd        = (float) $m['ordersPerDay'];
        $top3       = (float) $m['top3Share'];
        $topName    = (string)$m['topMenuName'];
        $topQty     = (int)   $m['topMenuQty'];
        $topRev     = (float) $m['topMenuRevenue'];
        $bestDay    = (string)$m['bestDay'];
        $bestHour   = (string)$m['bestHour'];
        $labelC     = (string)$m['labelCurrent'];
        $labelP     = (string)$m['labelPrev'];
        $conf       = (int)   $m['confidence'];
        $days       = (int)   $m['diffInDays'];

        // Tentukan tren utama
        $trend = 'stabil';
        if ($g >= 10)  $trend = 'naik';
        if ($g <= -10) $trend = 'turun';

        // Signal tambahan
        $avgGrowth     = $avgP > 0 ? (($avgC - $avgP) / $avgP) * 100 : 0;
        $volumeGrowth  = $countP > 0 ? (($countC - $countP) / $countP) * 100 : 0;
        $isHighDisc    = $disc > 12;
        $isMenuConc    = $top3 >= 65;
        $isSlowDay     = $opd < 3;

        // ── Mengapa Demikian ──
        $mengapa = [];
        if ($countC === 0) {
            $mengapa[] = "Tidak ada transaksi pada periode {$labelC}. MineAI tidak dapat melakukan analisis mendalam.";
        } else {
            $mengapa[] = "Selama {$labelC}, tercatat {$countC} transaksi dengan omzet " . $this->rp($omzetC) . ".";

            if ($omzetP > 0) {
                $dir = $g >= 0 ? "naik" : "turun";
                $mengapa[] = "Dibanding {$labelP} (" . $this->rp($omzetP) . "), omzet {$dir} " . number_format(abs($g), 1) . "%.";
            }

            if ($avgC > 0) {
                $avgNote = $avgP > 0 && abs($avgGrowth) >= 5
                    ? " (" . ($avgGrowth > 0 ? "naik" : "turun") . " " . number_format(abs($avgGrowth), 1) . "% dari " . $this->rp($avgP) . ")"
                    : "";
                $mengapa[] = "Rata-rata nilai per transaksi: " . $this->rp($avgC) . "{$avgNote}.";
            }

            if ($topQty > 0) {
                $mengapa[] = "Menu paling diminati: \"{$topName}\" — {$topQty} porsi, kontribusi " . $this->rp($topRev) . ".";
            }

            if ($isMenuConc) {
                $mengapa[] = "Top 3 menu menyumbang " . number_format($top3, 1) . "% dari total penjualan — ketergantungan pada menu inti cukup tinggi.";
            }

            if ($bestDay !== 'Belum ada') {
                $mengapa[] = "Puncak transaksi terjadi di {$bestDay}.";
            }
        }

        // ── Risiko / Potensi ──
        $risiko = [];
        if ($trend === 'naik') {
            $risiko[] = "Momentum positif ini berisiko terhenti jika stok menu terlaris ({$topName}) tidak diamankan lebih awal.";
            if ($isHighDisc) {
                $risiko[] = "Diskon mencapai " . number_format($disc, 1) . "% — pertumbuhan omzet masih sehat, namun margin perlu diawasi agar tidak tergerus.";
            } else {
                $risiko[] = "Tekanan diskon terkendali (" . number_format($disc, 1) . "%), ruang profit masih lebar untuk dioptimalkan.";
            }
            $risiko[] = "Lonjakan permintaan cepat sering diikuti penurunan kualitas layanan jika persiapan tim kurang.";
        } elseif ($trend === 'turun') {
            $risiko[] = "Penurunan " . number_format(abs($g), 1) . "% mengancam arus kas dalam " . ($days <= 30 ? "beberapa minggu" : "beberapa bulan") . " ke depan jika tidak diintervensi.";
            if ($volumeGrowth < -10) {
                $risiko[] = "Volume transaksi juga turun " . number_format(abs($volumeGrowth), 1) . "% — bukan sekadar masalah harga, tapi ada potensi penurunan traffic pelanggan.";
            }
            if ($isMenuConc) {
                $risiko[] = "Ketergantungan tinggi pada sedikit menu membuat pendapatan rentan jika menu tersebut mengalami masalah ketersediaan.";
            }
            $risiko[] = "Risiko food waste meningkat jika bahan baku sudah disiapkan tapi permintaan terus melemah.";
        } else {
            $risiko[] = "Bisnis stabil, namun stagnan di angka yang sama membuat Anda rentan disalip kompetitor yang lebih agresif bereksperimen.";
            if ($isSlowDay) {
                $risiko[] = "Rata-rata hanya " . number_format($opd, 1) . " transaksi/hari — ada celah besar untuk menaikkan frekuensi kunjungan.";
            }
            $risiko[] = "Tanpa inovasi menu atau promosi baru, pelanggan lama bisa kehilangan alasan untuk kembali lebih sering.";
        }

        // ── Solusi & Eksekusi ──
        $solusi = [];
        if ($countC === 0) {
            $solusi[] = "Perluas rentang filter waktu untuk mendapatkan data yang bisa dianalisis.";
            $solusi[] = "Pastikan setiap transaksi tercatat di Flavory.id agar MineAI dapat memberikan rekomendasi yang akurat.";
        } elseif ($trend === 'naik') {
            $solusi[] = "Amankan stok \"{$topName}\" 2–3 hari ke depan agar momentum tidak terhenti karena kehabisan bahan baku.";
            if ($bestHour !== 'Belum ada') {
                $solusi[] = "Fokuskan promo upsell dan add-on di jam puncak {$bestHour} untuk memaksimalkan nilai per transaksi.";
            }
            $solusi[] = "Buat loyalty program sederhana (stampcard/poin) agar pelanggan baru dari periode ini menjadi pelanggan tetap.";
            if ($isHighDisc) {
                $solusi[] = "Geser strategi dari diskon harga ke bundling nilai (contoh: \"Beli 2 gratis minuman\") agar margin lebih terjaga.";
            }
        } elseif ($trend === 'turun') {
            if ($bestDay !== 'Belum ada') {
                $solusi[] = "Luncurkan promo flash sale di luar {$bestDay} untuk mengangkat hari-hari yang paling sepi.";
            }
            $solusi[] = "Evaluasi menu dengan penjualan terendah — ubah menjadi paket combo atau pertimbangkan untuk dirotasi sementara.";
            $solusi[] = "Aktifkan promosi di media sosial dengan konten \"behind the scenes\" atau menu spesial mingguan untuk memancing traffic baru.";
            if ($avgC < $avgP) {
                $solusi[] = "Rata-rata transaksi turun — pertimbangkan bundling hemat yang menaikkan nilai order minimal tanpa menaikkan persepsi harga.";
            }
        } else {
            $solusi[] = "Gunakan periode stabil ini untuk uji coba 1–2 menu baru dengan bahan baku murah — riset selera pasar tanpa risiko besar.";
            if ($bestHour !== 'Belum ada') {
                $solusi[] = "Optimalkan promosi di jam {$bestHour} karena itulah titik konversi paling efisien saat ini.";
            }
            $solusi[] = "Dorong repeat order dengan voucher transaksi berikutnya (\"Hemat Rp 5.000 untuk kunjungan berikutnya\").";
            $solusi[] = "Perkuat kehadiran online — foto menu berkualitas di sosial media dapat menaikkan traffic tanpa biaya besar.";
        }

        // ── Headline ──
        $headline = match($trend) {
            'naik'   => "MineAI mendeteksi momentum positif yang perlu segera diperkuat.",
            'turun'  => "MineAI mendeteksi perlambatan — diperlukan intervensi strategis segera.",
            default  => "MineAI melihat bisnis Anda stabil dan siap dioptimalkan lebih lanjut.",
        };

        // ── Prediksi ──
        [$prediksiText, $prediksiClass, $prediksiIcon] = match(true) {
            $g > 0   => ["Tren Naik " . number_format($g, 1) . "%",        "text-success", "bi-graph-up-arrow"],
            $g < 0   => ["Tren Turun " . number_format(abs($g), 1) . "%",  "text-danger",  "bi-graph-down-arrow"],
            default  => ["Performa Stabil",                                 "text-white",   "bi-arrow-right-circle"],
        };

        if ($countC === 0) {
            $prediksiText  = "Data Belum Cukup";
            $prediksiClass = "text-white-50";
            $prediksiIcon  = "bi-dash-circle";
        }

        return [
            'headline'      => $headline,
            'prediksiText'  => $prediksiText,
            'prediksiClass' => $prediksiClass,
            'prediksiIcon'  => $prediksiIcon,
            'mengapa'       => implode(' ', $mengapa),
            'risiko'        => implode(' ', $risiko),
            'solusi'        => implode(' ', $solusi),
            'confidence'    => $conf,
            'trend'         => $trend,
            'signals'       => [
                'avg_growth'    => $avgGrowth,
                'volume_growth' => $volumeGrowth,
                'high_discount' => $isHighDisc,
                'menu_conc'     => $isMenuConc,
            ],
        ];
    }

    private function pickBest(array $arr): string
    {
        if (empty($arr)) return 'Belum ada';
        arsort($arr);
        $label = array_key_first($arr);
        return $label . ' (' . $arr[$label] . ' trx)';
    }

    private function rp(float $val): string
    {
        return 'Rp ' . number_format($val, 0, ',', '.');
    }
}