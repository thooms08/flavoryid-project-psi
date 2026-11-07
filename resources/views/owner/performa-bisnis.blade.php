<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performa Bisnis - Flavory.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    @include('layouts.favicon')
    <script src="{{ asset('js/loading.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-orange: #fd7e14;
            --soft-orange: #fff4e6;
            --dark-orange: #e86c00;
            --bg-body: #fff7ed;
            --mine-1: #ff6a00;
            --mine-2: #ee0979;
            --mine-3: #c850c0;
        }
        body { background-color: var(--bg-body); font-family: 'Poppins', sans-serif; color: #333; overflow-x: hidden; }

        /* ── MineAI Gradient Animated ── */
        @keyframes mineShift {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Orb background animation */
        @keyframes orbFloat {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33%      { transform: translate(30px, -20px) scale(1.08); }
            66%      { transform: translate(-20px, 15px) scale(0.95); }
        }

        .mine-gradient {
            background: linear-gradient(135deg, var(--mine-1), var(--mine-2), var(--mine-3), var(--mine-1));
            background-size: 300% 300%;
            animation: mineShift 5s ease infinite;
        }

        /* ── MineAI Card - warm gradient light style ── */
        .mine-card {
            border-radius: 28px;
            background: linear-gradient(135deg, #fff5f0 0%, #fff0f6 40%, #fdf0ff 100%);
            position: relative;
            /* overflow: hidden DIHAPUS - ini yang menyebabkan konten terpotong saat reveal */
            box-shadow: 0 0 0 2px rgba(255,106,0,0.2),
                        0 20px 60px rgba(238,9,121,0.12),
                        0 4px 24px rgba(255,106,0,0.08);
            transition: box-shadow 0.5s ease;
        }
        .mine-card:hover {
            box-shadow: 0 0 0 2px rgba(238,9,121,0.35),
                        0 24px 64px rgba(238,9,121,0.18),
                        0 4px 24px rgba(255,106,0,0.12);
        }

        /* Orb wrapper - overflow hidden ditaruh di sini saja, bukan di card */
        .mine-orb-wrap {
            position: absolute;
            inset: 0;
            border-radius: 28px;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }

        /* Animated orb blobs */
        .mine-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(70px);
            opacity: 0.45;
            pointer-events: none;
        }
        .mine-orb-1 {
            width: 380px; height: 380px;
            background: radial-gradient(circle, rgba(255,106,0,0.55), transparent 65%);
            top: -120px; left: -80px;
            animation: orbFloat 9s ease-in-out infinite;
        }
        .mine-orb-2 {
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(238,9,121,0.5), transparent 65%);
            bottom: -80px; right: 8%;
            animation: orbFloat 11s ease-in-out infinite reverse;
        }
        .mine-orb-3 {
            width: 220px; height: 220px;
            background: radial-gradient(circle, rgba(200,80,192,0.4), transparent 65%);
            top: 30%; right: -50px;
            animation: orbFloat 8s ease-in-out infinite 1.5s;
        }

        /* Content di dalam mine-card harus di atas orbs */
        .mine-card-inner { position: relative; z-index: 2; }

        /* Logo MineAI - background warm semi-transparan */
        .mine-logo-wrap {
            width: 48px; height: 48px;
            border-radius: 14px;
            background: rgba(255,255,255,0.75);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,106,0,0.2);
            box-shadow: 0 4px 12px rgba(255,106,0,0.15);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .mine-logo-wrap img {
            width: 30px; height: 30px;
            object-fit: contain;
        }
        .mine-logo-wrap .mine-logo-fallback {
            font-size: 1.3rem;
            background: linear-gradient(135deg, var(--mine-1), var(--mine-2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Tombol Minta Insight */
        .btn-mine {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 32px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            color: white;
            border: none;
            background: linear-gradient(135deg, var(--mine-1), var(--mine-2), var(--mine-3));
            background-size: 200% 200%;
            animation: mineShift 4s ease infinite;
            box-shadow: 0 8px 30px rgba(238,9,121,0.4);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
        }
        .btn-mine:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 12px 40px rgba(238,9,121,0.55);
            color: white;
        }
        .btn-mine:active { transform: scale(0.98); }
        .btn-mine .spinner-border { width: 1.1rem; height: 1.1rem; border-width: 2px; }

        /* MineAI badge di header halaman */
        .mine-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.8rem;
            color: white;
            letter-spacing: 0.3px;
        }
        .mine-badge img { width: 20px; height: 20px; object-fit: contain; }

        /* Confidence bar */
        .conf-bar-track {
            height: 5px; border-radius: 6px;
            background: rgba(238,9,121,0.12);
            overflow: hidden;
        }
        .conf-bar-fill {
            height: 100%; border-radius: 6px;
            background: linear-gradient(90deg, var(--mine-1), var(--mine-2), var(--mine-3));
            background-size: 200% 100%;
            animation: mineShift 3s ease infinite;
            transition: width 1.2s cubic-bezier(0.4,0,0.2,1);
        }

        /* Insight panels - light warm theme */
        .insight-panel {
            border-radius: 16px;
            padding: 1.2rem 1.4rem;
            background: rgba(255,255,255,0.72);
            border: 1px solid rgba(255,106,0,0.1);
            border-left: 3px solid transparent;
            backdrop-filter: blur(6px);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            height: 100%;
        }
        .insight-panel:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 28px rgba(238,9,121,0.1);
        }
        .insight-panel.p-mengapa       { border-left-color: var(--mine-1); }
        .insight-panel.p-risiko-down   { border-left-color: #e11d48; }
        .insight-panel.p-risiko-up     { border-left-color: #059669; }
        .insight-panel.p-risiko-stable { border-left-color: #64748b; }
        .insight-panel.p-solusi        { border-left-color: var(--mine-3); }
        .insight-panel h6 { font-size: 0.85rem; color: #1e1e2e; }
        .insight-panel p  { color: #4a4a6a !important; font-size: 0.82rem !important; }

        /* Signal chips - warm light */
        .mine-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 13px;
            border-radius: 50px;
            background: rgba(255,255,255,0.7);
            border: 1px solid rgba(255,106,0,0.18);
            color: #4a2020;
            font-size: 0.72rem;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(255,106,0,0.08);
        }

        /* Divider */
        .mine-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(238,9,121,0.25), rgba(255,106,0,0.25), transparent);
            margin: 1.5rem 0;
        }

        /* Insight area - pakai visibility+max-height bukan display:none
           agar tidak ada layout collapse saat reveal */
        #mineInsightArea {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.6s cubic-bezier(0.4, 0, 0.2, 1),
                        opacity 0.5s ease;
            opacity: 0;
        }
        #mineInsightArea.revealed {
            max-height: 3000px; /* nilai besar agar semua konten muat */
            opacity: 1;
        }

        /* Analytic cards */
        .analytic-card {
            background: white; border-radius: 20px;
            border: 1px solid rgba(253,126,20,0.1);
            box-shadow: 0 8px 24px rgba(0,0,0,0.03);
            transition: all 0.3s ease; height: 100%;
            display: flex; flex-direction: column; justify-content: center;
        }
        .analytic-card:hover { transform: translateY(-4px); box-shadow: 0 14px 32px rgba(253,126,20,0.09); }
        .icon-wrapper {
            width: 46px; height: 46px; border-radius: 13px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; background: var(--soft-orange); color: var(--primary-orange); flex-shrink: 0;
        }
        .stat-label { font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; color: #888; }
        .stat-value { font-size: 0.95rem; font-weight: 700; color: #222; word-break: break-word; }

        /* Growth card */
        .growth-card {
            background: linear-gradient(135deg, var(--primary-orange), var(--dark-orange));
            color: white; border-radius: 20px; padding: 1.5rem;
            box-shadow: 0 10px 25px rgba(253,126,20,0.3); position: relative; overflow: hidden;
        }
        .growth-card::before {
            content: ""; position: absolute; top: -20%; right: -10%;
            width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%;
        }

        .btn-orange { background-color: var(--primary-orange); color: white; border: 1px solid var(--primary-orange); }
        .btn-orange:hover { background-color: var(--dark-orange); color: white; }
        .btn-outline-orange { color: var(--primary-orange); border: 1px solid var(--primary-orange); background: transparent; }
        .btn-outline-orange:hover { background: var(--primary-orange); color: white; }

        /* Fix responsif Mobile */
        @media (max-width: 768px) {
            .stat-value { font-size: 0.85rem; }
            .analytic-card { padding: 1rem !important; }
            
            /* Orb resize buat hp biar gak luber nutupin teks */
            .mine-orb-1 { width: 200px; height: 200px; top: -50px; left: -30px; }
            .mine-orb-2 { width: 150px; height: 150px; bottom: -30px; right: -10px; }
            .mine-orb-3 { width: 100px; height: 100px; top: 10%; right: -20px; }
            
            /* Tombol insight penuhi layar hp */
            .btn-mine { width: 100%; }
        }
    </style>
</head>
<body>
<div class="container-fluid px-3 px-md-5">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-2 mt-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('owner.dashboard') }}" class="btn btn-light border-warning-subtle text-secondary rounded-4 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>
            <h1 class="h4 mb-0 fw-black text-uppercase" style="font-weight:900;letter-spacing:-0.5px;">Performa Bisnis</h1>
        </div>
        <span class="mine-badge mine-gradient d-none d-md-inline-flex">
            <img src="{{ asset('assets/logo-mine-ai.png') }}" alt="MineAI"
                 onerror="this.style.display='none';this.insertAdjacentHTML('afterend','<i class=\'bi bi-cpu-fill\'></i>')">
            Data Analisis MineAI
        </span>
    </div>

    {{-- Filter --}}
    <div class="card shadow-sm border-0 mb-4 rounded-4 bg-white">
        <div class="card-body p-4">
            <form action="{{ route('owner.performa') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted small">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control rounded-3" value="{{ $startInput }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted small">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control rounded-3" value="{{ $endInput }}">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-orange w-100 fw-bold rounded-3 shadow-sm">
                        <i class="bi bi-search me-1"></i> Terapkan
                    </button>
                    <a href="{{ route('owner.performa') }}" class="btn btn-light fw-bold rounded-3 border shadow-sm px-3" title="Reset">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
                @php $qf = request('quick_filter'); $isDefault = !$qf && !request('start_date'); @endphp
                <div class="col-12 mt-2 pt-3 border-top">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach(['1_hari'=>'1 Hari','1_minggu'=>'1 Minggu','1_bulan'=>'1 Bulan','1_tahun'=>'1 Tahun'] as $val=>$label)
                            <button type="submit" name="quick_filter" value="{{ $val }}"
                                class="btn flex-fill rounded-3 border fw-medium {{ ($qf==$val || ($isDefault && $val=='1_bulan')) ? 'btn-orange text-white shadow-sm' : 'btn-outline-orange' }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Info alert --}}
    <div class="alert border-0 shadow-sm rounded-4 mb-4" style="background:#fff4e6;border-left:5px solid #fd7e14 !important;padding:1.25rem;">
        <div class="d-flex align-items-start mb-3">
            <i class="bi bi-pie-chart-fill fs-4 me-3 mt-1" style="color:#fd7e14;"></i>
            <div class="small">
                <h6 class="fw-bold mb-1" style="font-size:.9rem;color:#e8590c;">1. Informasi Data Karakteristik</h6>
                <p class="mb-0" style="opacity:.9;color:#862e01;">Statistik <b>Hari, Jam, Tanggal, dan Bulan</b> bersifat <b>dinamis</b> mengikuti filter waktu yang diterapkan.</p>
            </div>
        </div>
        <hr class="my-0 opacity-10" style="border-top:1px solid #fd7e14;">
        <div class="d-flex align-items-start mt-3">
            <i class="bi bi-calculator-fill fs-4 me-3 mt-1" style="color:#fd7e14;"></i>
            <div class="small">
                <h6 class="fw-bold mb-1" style="font-size:.9rem;color:#e8590c;">2. Rumus Komparasi & Prediksi</h6>
                <p class="mb-0" style="opacity:.9;color:#862e01;">Sistem membandingkan <b>{{ $labelCurrent }}</b> dengan <b>{{ $labelPrev }}</b> secara adil (Apple-to-Apple).</p>
            </div>
        </div>
    </div>

    {{-- Stat cards --}}
    <div class="row g-3 mb-4">
        @foreach([
            ['icon'=>'bi-calendar-day',  'label'=>'Hari Teramai', 'stats'=>$hariStats,    'maxLabel'=>'Puncak',   'minLabel'=>'Terendah'],
            ['icon'=>'bi-clock-history', 'label'=>'Jam',          'stats'=>$jamStats,     'maxLabel'=>'Ramai',    'minLabel'=>'Sepi'],
            ['icon'=>'bi-calendar-check','label'=>'Tanggal',      'stats'=>$tanggalStats, 'maxLabel'=>'Tertinggi','minLabel'=>'Terendah'],
            ['icon'=>'bi-calendar-month','label'=>'Bulan',        'stats'=>$bulanStats,   'maxLabel'=>'Terbaik',  'minLabel'=>'Terburuk'],
        ] as $card)
        <div class="col-6 col-xl-3">
            <div class="analytic-card p-3 p-md-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-wrapper me-2 me-md-3"><i class="bi {{ $card['icon'] }}"></i></div>
                    <h6 class="fw-bold mb-0 small">{{ $card['label'] }}</h6>
                </div>
                <div class="border-start border-3 border-success ps-2 mb-2">
                    <span class="stat-label d-block text-success">{{ $card['maxLabel'] }}</span>
                    <div class="stat-value">{{ $card['stats']['max'] }}</div>
                </div>
                <div class="border-start border-3 border-danger ps-2">
                    <span class="stat-label d-block text-danger">{{ $card['minLabel'] }}</span>
                    <div class="stat-value">{{ $card['stats']['min'] }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Chart + Prediksi --}}
    <div class="row g-4 mb-4 align-items-stretch">
        <div class="col-lg-8">
            <div class="analytic-card p-4" style="height:100%;">
                <h6 class="fw-bold mb-4 d-flex align-items-center">
                    <i class="bi bi-graph-up me-2" style="color:var(--primary-orange)"></i> Perbandingan Omzet
                </h6>
                <div style="position:relative;height:350px;">
                    <canvas id="omzetChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 d-flex flex-column gap-4">
            <div class="growth-card">
                <h6 class="text-white-50 text-uppercase fw-bold mb-3" style="font-size:.75rem;letter-spacing:1px;">Prediksi Performa</h6>
                <div class="d-flex align-items-center mb-3">
                    <i class="bi {{ $insight['prediksiIcon'] }} display-4 me-3 text-white"></i>
                    <h3 class="fw-bold mb-0 {{ $insight['prediksiClass'] }}" style="font-size:1.4rem;">{{ $insight['prediksiText'] }}</h3>
                </div>
                <p class="small text-white-50 mb-0">Berdasarkan komparasi dengan {{ strtolower($labelPrev) }}.</p>
            </div>
            <div class="analytic-card p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Detail Komparasi</h6>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Transaksi {{ $labelCurrent }}</span>
                    <span class="fw-bold">{{ $countCurrent }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                    <span class="text-muted small">Transaksi {{ $labelPrev }}</span>
                    <span class="fw-bold">{{ $countPrev }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Omzet {{ $labelCurrent }}</span>
                    <span class="fw-bold text-success">Rp {{ number_format($omzetCurrent,0,',','.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                    <span class="text-muted small">Omzet {{ $labelPrev }}</span>
                    <span class="fw-bold text-secondary">Rp {{ number_format($omzetPrev,0,',','.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted small">Avg. Ticket</span>
                    <span class="fw-bold" style="color:var(--primary-orange)">Rp {{ number_format($avgTicketCurrent,0,',','.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Menu Terlaris</span>
                    <span class="fw-bold text-dark" style="font-size:.85rem;max-width:55%;text-align:right;">{{ $topMenuName }}</span>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- MineAI Section --}}
<div class="container-fluid px-3 px-md-5 pb-5">
    <div class="mine-card p-4 p-md-5">

        {{-- Orb backgrounds - dibungkus wrapper agar overflow:hidden tidak potong konten card --}}
        <div class="mine-orb-wrap">
            <div class="mine-orb mine-orb-1"></div>
            <div class="mine-orb mine-orb-2"></div>
            <div class="mine-orb mine-orb-3"></div>
        </div>

        <div class="mine-card-inner">

            {{-- ── Heading utama - Flexbox Fix untuk Mobile ── --}}
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 text-center text-md-start">
                
                <div class="d-flex flex-column flex-md-row align-items-center gap-3">
                    {{-- Logo --}}
                    <div class="mine-logo-wrap">
                        <img src="{{ asset('assets/logo-mine-ai.png') }}" alt="MineAI"
                             id="mineLogoImg"
                             onerror="this.style.display='none';document.getElementById('mineLogoFallback').style.display='block'">
                        <span id="mineLogoFallback" class="mine-logo-fallback" style="display:none;">
                            <i class="bi bi-cpu-fill"></i>
                        </span>
                    </div>
                    <div>
                        <div class="d-flex flex-column flex-md-row align-items-center gap-2 mb-1">
                            <h5 class="fw-bold mb-0" style="color:#1e1e2e;letter-spacing:-0.3px;">
                                Insight &amp; Saran Strategi
                            </h5>
                            <span style="
                                background: linear-gradient(135deg, var(--mine-1), var(--mine-2), var(--mine-3));
                                background-size: 200% 200%;
                                animation: mineShift 4s ease infinite;
                                -webkit-background-clip: text;
                                -webkit-text-fill-color: transparent;
                                background-clip: text;
                                font-weight: 900;
                                font-size: 1.15rem;
                            ">MineAI</span>
                        </div>
                        <p style="color:rgba(30,30,46,0.5);font-size:0.8rem;margin:0;">
                            Analisis cerdas berbasis data transaksi outlet Anda
                        </p>
                    </div>
                </div>

                {{-- Tombol utama - redirect ke halaman MineAI --}}
                <a href="{{ route('owner.ai') }}" class="btn-mine" id="btnMineInsight">
                    <i class="bi bi-stars"></i>
                    <span>Minta Insight MineAI</span>
                </a>
            </div>

            {{-- ── Insight area - tersembunyi sampai tombol diklik ── --}}
            <div id="mineInsightArea">
                <div class="mine-divider"></div>

                {{-- Warning --}}
                <div class="d-flex align-items-start gap-3 mb-4 p-3 rounded-3"
                     style="background:rgba(255,165,0,0.1);border:1px solid rgba(255,106,0,0.2);">
                    <i class="bi bi-exclamation-triangle-fill mt-1" style="color:#f97316;flex-shrink:0;font-size:1.1rem;"></i>
                    <p class="mb-0 small" style="color:#7c3a1a;line-height:1.6;">
                        Analisa ini di-generate berdasarkan kalkulasi matematis transaksi.
                        <b style="color:#5c2a0e;">Sistem tidak mengetahui 100% faktor eksternal</b>.
                        Manajer diwajibkan untuk analisis ulang sesuai kondisi faktual.
                    </p>
                </div>

                {{-- Confidence + headline --}}
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 mb-4 text-center text-md-start">
                    <p style="color:#5a2060;font-size:0.88rem;margin:0;font-style:italic;">
                        "{{ $insight['headline'] }}"
                    </p>
                    <div style="min-width:140px; width: 100%; max-width: 200px;">
                        <div class="d-flex justify-content-between mb-1">
                            <span style="color:rgba(30,30,46,0.45);font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Confidence</span>
                            <span style="font-size:.82rem;font-weight:700;background:linear-gradient(135deg,var(--mine-1),var(--mine-2));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">{{ $confidence }}%</span>
                        </div>
                        <div class="conf-bar-track">
                            <div class="conf-bar-fill" id="confBar" style="width:0%"></div>
                        </div>
                        <p style="color:rgba(30,30,46,0.4);font-size:.63rem;margin-top:4px;margin-bottom:0;">{{ $countCurrent }} transaksi dianalisis</p>
                    </div>
                </div>

                {{-- 3 Insight panels --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="insight-panel p-mengapa">
                            <h6 class="fw-bold mb-2" style="color:var(--mine-1);">
                                <i class="bi bi-search me-1"></i> Mengapa Demikian?
                            </h6>
                            <p class="small mb-0" style="line-height:1.7;">{{ $insight['mengapa'] }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        @php
                            $risikoClass = $growthRate < 0 ? 'p-risiko-down' : ($growthRate > 0 ? 'p-risiko-up' : 'p-risiko-stable');
                            $risikoColor = $growthRate < 0 ? '#ff4d6d' : ($growthRate > 0 ? '#2dd4bf' : '#94a3b8');
                            $risikoIcon  = $growthRate < 0 ? 'bi-shield-exclamation' : ($growthRate > 0 ? 'bi-graph-up-arrow' : 'bi-dash-circle');
                            $risikoLabel = $growthRate < 0 ? 'Risiko Terburuk' : ($growthRate > 0 ? 'Potensi & Keuntungan' : 'Kondisi Saat Ini');
                        @endphp
                        <div class="insight-panel {{ $risikoClass }}">
                            <h6 class="fw-bold mb-2" style="color:{{ $risikoColor }};">
                                <i class="bi {{ $risikoIcon }} me-1"></i> {{ $risikoLabel }}
                            </h6>
                            <p class="small mb-0" style="line-height:1.7;">{{ $insight['risiko'] }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="insight-panel p-solusi">
                            <h6 class="fw-bold mb-2" style="color:var(--mine-3);">
                                <i class="bi bi-lightbulb-fill me-1"></i> Solusi & Eksekusi
                            </h6>
                            <p class="small mb-0" style="line-height:1.7;">{{ $insight['solusi'] }}</p>
                        </div>
                    </div>
                </div>

                {{-- Signal chips --}}
                <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-2 pt-3" style="border-top:1px solid rgba(238,9,121,0.15);">
                    <span class="mine-chip"><i class="bi bi-lightning-charge" style="color:var(--mine-1)"></i> Avg/Trx: Rp {{ number_format($avgTicketCurrent,0,',','.') }}</span>
                    <span class="mine-chip"><i class="bi bi-calendar3" style="color:var(--mine-2)"></i> {{ number_format($ordersPerDay,1) }} order/hari</span>
                    <span class="mine-chip"><i class="bi bi-percent" style="color:var(--mine-3)"></i> Diskon: {{ number_format($discountRatio,1) }}%</span>
                    <span class="mine-chip"><i class="bi bi-trophy" style="color:var(--mine-1)"></i> Top3 Menu: {{ number_format($top3Share,1) }}%</span>
                    @if($bestHour !== 'Belum ada')
                    <span class="mine-chip"><i class="bi bi-clock" style="color:var(--mine-2)"></i> Jam Puncak: {{ explode(' ', $bestHour)[0] }}</span>
                    @endif
                </div>
            </div>

        </div>{{-- /.mine-card-inner --}}
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── Chart ──
    const ctx = document.getElementById('omzetChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartData['labels']),
            datasets: [{
                label: 'Total Omzet (Rp)',
                data: @json($chartData['omzet']),
                backgroundColor: ['rgba(108,117,125,0.35)', 'rgba(253,126,20,0.8)'],
                borderColor: ['rgb(108,117,125)', 'rgb(253,126,20)'],
                borderWidth: 2, borderRadius: 12, barPercentage: 0.5
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    padding: 12, backgroundColor: 'rgba(0,0,0,0.85)',
                    titleFont: { size: 14, family: 'Poppins' },
                    bodyFont:  { size: 13, family: 'Poppins' },
                    callbacks: { label: ctx => ' Rp ' + new Intl.NumberFormat('id-ID').format(ctx.parsed.y) }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.04)' },
                    ticks: { font: { family:'Poppins', size:11 }, callback: v => v>=1e6 ? 'Rp '+(v/1e6)+' Jt' : v>=1e3 ? 'Rp '+(v/1e3)+' Rb' : 'Rp '+v }
                },
                x: { grid: { display: false }, ticks: { font: { family:'Poppins', size:11, weight:'600' } } }
            }
        }
    });
});

// ── MineAI Reveal - dipanggil saat tombol diklik ──
function revealInsight() {
    const btn     = document.getElementById('btnMineInsight');
    const btnText = document.getElementById('btnMineText');
    const spinner = document.getElementById('btnMineSpinner');
    const area    = document.getElementById('mineInsightArea');

    // Jika sudah terbuka, tidak ada aksi
    if (area.classList.contains('revealed')) return;

    // Tampilkan loading sebentar (simulasi "thinking")
    btnText.textContent  = 'Sedang Menganalisis...';
    spinner.classList.remove('d-none');
    btn.style.pointerEvents = 'none';

    setTimeout(() => {
        // Reveal dengan class - CSS transition yang handle animasi, BUKAN display:none toggle
        area.classList.add('revealed');

        // Animate confidence bar
        setTimeout(() => {
            const bar = document.getElementById('confBar');
            if (bar) bar.style.width = '{{ $confidence }}%';
        }, 300);

        // Ubah tombol jadi sudah selesai
        btnText.textContent = 'Insight Ditampilkan ✓';
        spinner.classList.add('d-none');
        btn.style.opacity = '0.65';
        btn.style.cursor  = 'default';

        // Scroll ke insight area setelah transisi selesai
        setTimeout(() => {
            area.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 400);
    }, 1400);
}
</script>
</body>
</html>