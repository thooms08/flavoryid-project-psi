<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Owner | Flavory.id</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @include('layouts.favicon')

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --orange:     #ff6a00;
            --orange-mid: #ff9a00;
            --orange-lt:  #ffb300;
            --bg:         #f8f9fb;
            --card-bg:    #ffffff;
            --text:       #1e293b;
            --muted:      #64748b;
            --border:     rgba(0,0,0,0.06);
        }

        body {
            font-family: 'Poppins', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* ─── Topbar ─────────────────────────────────────────────────── */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            padding: 0 24px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        .topbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .topbar-brand-logo {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            object-fit: contain;
        }
        .topbar-brand-name {
            font-size: 1.15rem;
            font-weight: 900;
            color: var(--text);
            letter-spacing: -0.5px;
        }
        .topbar-brand-name span { color: var(--orange); }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .topbar-user {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .topbar-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--orange), var(--orange-mid));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            flex-shrink: 0;
        }
        .topbar-user-info {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }
        .topbar-user-info .name {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--text);
        }
        .topbar-user-info .role {
            font-size: 0.68rem;
            font-weight: 600;
            color: var(--muted);
        }
        .btn-logout {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 0.78rem;
            font-weight: 700;
            color: #ef4444;
            background: #fef2f2;
            border: 1px solid #fee2e2;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s, transform 0.15s;
        }
        .btn-logout:hover {
            background: #fee2e2;
            transform: translateY(-1px);
            color: #dc2626;
        }

        /* ─── Hero / welcome banner ──────────────────────────────────── */
        .hero-banner {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #fff4e0 0%, #fff8ed 50%, #fffdf8 100%);
            border-bottom: 1px solid rgba(255,106,0,0.1);
            padding: 40px 32px 36px;
        }
        .hero-banner::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,106,0,.06) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }
        .hero-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
        }
        .hero-orb-1 {
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(255,106,0,.12), transparent 65%);
            top: -30%; right: -5%;
        }
        .hero-orb-2 {
            width: 280px; height: 280px;
            background: radial-gradient(circle, rgba(255,154,0,.1), transparent 65%);
            bottom: -20%; left: 10%;
        }
        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 900px;
        }
        .hero-greeting {
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--orange);
            margin-bottom: 6px;
        }
        .hero-title {
            font-size: clamp(1.5rem, 3vw, 2.2rem);
            font-weight: 900;
            color: var(--text);
            line-height: 1.2;
            margin-bottom: 8px;
        }
        .hero-title span {
            background: linear-gradient(135deg, var(--orange), var(--orange-mid));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-sub {
            font-size: 0.87rem;
            color: var(--muted);
            line-height: 1.6;
        }
        .hero-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 18px;
        }
        .hero-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 100px;
            background: rgba(255,255,255,0.85);
            border: 1px solid rgba(255,106,0,0.15);
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--muted);
            backdrop-filter: blur(8px);
        }
        .hero-chip i { color: var(--orange); }

        /* ─── Main layout ────────────────────────────────────────────── */
        .main-wrap {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 24px 80px;
        }
        .section-label {
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 20px;
        }

        /* ─── Menu Grid ──────────────────────────────────────────────── */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 20px;
        }
        @media (min-width: 1024px) {
            .menu-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 640px) {
            .menu-grid { grid-template-columns: 1fr; gap: 14px; }
        }

        /* ─── Menu Card (base) ───────────────────────────────────────── */
        .menu-card {
            display: flex;
            flex-direction: column;
            background: var(--card-bg);
            border-radius: 22px;
            padding: 28px 24px 22px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            text-decoration: none;
            color: var(--text);
            transition: transform 0.22s cubic-bezier(.34,1.56,.64,1),
                        box-shadow 0.22s ease,
                        border-color 0.2s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        .menu-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,106,0,0) 0%, rgba(255,106,0,0) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .menu-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(255,106,0,0.12);
            border-color: rgba(255,106,0,0.2);
            color: var(--text);
            text-decoration: none;
        }
        .menu-card:hover::before { opacity: 1; }

        .menu-card-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 18px;
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }
        .menu-card:hover .menu-card-icon {
            transform: scale(1.08) rotate(-3deg);
        }
        .menu-card-title {
            font-size: 1rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 6px;
            line-height: 1.3;
        }
        .menu-card-desc {
            font-size: 0.78rem;
            color: var(--muted);
            line-height: 1.6;
            flex: 1;
        }
        .menu-card-arrow {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 18px;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--orange);
            transition: gap 0.2s ease;
        }
        .menu-card:hover .menu-card-arrow { gap: 8px; }

        /* ─── Warna icon per card ────────────────────────────────────── */
        .icon-orange  { background: #fff4ec; color: var(--orange); }
        .icon-red     { background: #fef2f2; color: #ef4444; }
        .icon-green   { background: #f0fdf4; color: #22c55e; }
        .icon-blue    { background: #eff6ff; color: #3b82f6; }
        .icon-purple  { background: #faf5ff; color: #a855f7; }
        .icon-slate   { background: #f1f5f9; color: #64748b; }

        /* ─── Card AI Consultant (gradasi oranye animasi) ────────────── */
        @keyframes aiShift {
            0%   { background-position: 0%   50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0%   50%; }
        }
        .menu-card-ai {
            background: linear-gradient(135deg, #ff6a00, #ff9a00, #ffb300, #ff7a1a, #ff6a00);
            background-size: 300% 300%;
            animation: aiShift 4s ease infinite;
            border: none;
            box-shadow: 0 8px 32px rgba(255,106,0,0.35);
        }
        .menu-card-ai .menu-card-title,
        .menu-card-ai .menu-card-desc { color: rgba(255,255,255,0.95); }
        .menu-card-ai .menu-card-arrow { color: rgba(255,255,255,0.9); }
        .menu-card-ai:hover {
            box-shadow: 0 20px 48px rgba(255,106,0,0.45);
            border: none;
        }
        .menu-card-ai:hover .menu-card-title,
        .menu-card-ai:hover .menu-card-desc { color: #fff; }
        .menu-card-ai::before { display: none; }

        /* icon AI: white background semi-transparan */
        .icon-ai {
            background: rgba(255,255,255,0.22);
            color: #fff;
            backdrop-filter: blur(4px);
        }

        /* sparkle decoration on AI card */
        .ai-sparkle {
            position: absolute;
            top: 16px;
            right: 20px;
            font-size: 1.4rem;
            opacity: 0.55;
            animation: aiShift 3s ease infinite, aiSparkle 2.4s ease-in-out infinite;
        }
        @keyframes aiSparkle {
            0%, 100% { transform: scale(1) rotate(0deg); opacity: 0.55; }
            50%       { transform: scale(1.25) rotate(15deg); opacity: 0.9; }
        }

        /* ─── Outlet info bar ────────────────────────────────────────── */
        .outlet-bar {
            display: flex;
            align-items: center;
            gap: 16px;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 16px 20px;
            margin-bottom: 36px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        }
        .outlet-bar-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #fff4ec;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--orange);
            flex-shrink: 0;
        }
        .outlet-bar-info .label {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 2px;
        }
        .outlet-bar-info .value {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--text);
        }
        .outlet-bar-badge {
            margin-left: auto;
            padding: 4px 12px;
            border-radius: 100px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .badge-owner {
            background: linear-gradient(135deg, var(--orange), var(--orange-mid));
            color: #fff;
        }

        /* ─── Responsive tweaks ──────────────────────────────────────── */
        @media (max-width: 600px) {
            .topbar { padding: 0 16px; }
            .topbar-user-info { display: none; }
            .hero-banner { padding: 28px 20px 24px; }
            .main-wrap { padding: 28px 16px 64px; }
            .outlet-bar { flex-wrap: wrap; gap: 12px; }
            .outlet-bar-badge { margin-left: 0; }
        }
    </style>
</head>
<body>

{{-- ── Topbar ───────────────────────────────────────────────────────────── --}}
<header class="topbar">
    <a href="{{ route('owner.dashboard') }}" class="topbar-brand">
        <img src="{{ asset('logo-512.png') }}"
             alt="Flavory.id"
             class="topbar-brand-logo"
             onerror="this.style.display='none'">
        <span class="topbar-brand-name">Flavory<span>.id</span></span>
    </a>

    <div class="topbar-right">
        <div class="topbar-user">
            <div class="topbar-avatar">
                {{ strtoupper(substr(Auth::user()->username ?? 'O', 0, 1)) }}
            </div>
            <div class="topbar-user-info">
                <span class="name">{{ Auth::user()->username }}</span>
                <span class="role">Owner / Manager</span>
            </div>
        </div>

        <form action="{{ route('owner.logout') }}" method="POST" style="margin:0;">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="bi bi-box-arrow-right"></i>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</header>

{{-- ── Hero / Welcome Banner ───────────────────────────────────────────── --}}
<section class="hero-banner">
    <div class="hero-orb hero-orb-1"></div>
    <div class="hero-orb hero-orb-2"></div>
    <div class="hero-content">
        <p class="hero-greeting">Selamat Datang</p>
        <h1 class="hero-title">
            Halo, <span>{{ Auth::user()->username }}</span>! 👋<br>
            Dashboard Owner & Manager
        </h1>
        <p class="hero-sub">
            Pantau dan kelola semua aspek bisnis Anda dari satu tempat.
            @if($outlet)
                Outlet aktif: <strong>{{ $outlet->nama_outlet ?? 'Outlet Saya' }}</strong>.
            @endif
        </p>
        <div class="hero-chips">
            <div class="hero-chip"><i class="bi bi-shield-check-fill"></i> Akses Owner</div>
            <div class="hero-chip"><i class="bi bi-clock-history"></i> Real-time Data</div>
            <div class="hero-chip"><i class="bi bi-bar-chart-fill"></i> Laporan Lengkap</div>
            <div class="hero-chip"><i class="bi bi-robot"></i> AI Consultant</div>
        </div>
    </div>
</section>

{{-- ── Main Content ────────────────────────────────────────────────────── --}}
<main class="main-wrap">

    {{-- Outlet info bar --}}
    @if($outlet)
    <div class="outlet-bar">
        <div class="outlet-bar-icon">
            <i class="bi bi-shop"></i>
        </div>
        <div class="outlet-bar-info">
            <div class="label">Outlet Aktif</div>
            <div class="value">{{ $outlet->nama_outlet ?? '-' }}</div>
        </div>
        <span class="outlet-bar-badge badge-owner">Owner</span>
    </div>
    @endif

    <p class="section-label">Menu Utama</p>

    {{-- ── Menu Grid ────────────────────────────────────────────────── --}}
    <div class="menu-grid">

        {{-- 1. Ikhtisar Bisnis --}}
        <a href="{{ route('owner.laporan.index') }}" class="menu-card">
            <div class="menu-card-icon icon-orange">
                <i class="bi bi-bar-chart-line-fill"></i>
            </div>
            <div class="menu-card-title">Ikhtisar Bisnis</div>
            <div class="menu-card-desc">
                Lihat ringkasan omzet, total order, keuntungan harian, serta analisis menu terlaris dan terendah.
            </div>
            <div class="menu-card-arrow">
                Buka Ikhtisar <i class="bi bi-arrow-right"></i>
            </div>
        </a>

        {{-- 2. Laporan Pengeluaran --}}
        <a href="{{ route('owner.pengeluaran.index') }}" class="menu-card">
            <div class="menu-card-icon icon-red">
                <i class="bi bi-receipt-cutoff"></i>
            </div>
            <div class="menu-card-title">Laporan Pengeluaran</div>
            <div class="menu-card-desc">
                Catat dan pantau semua pengeluaran operasional bisnis. Tersedia fitur export ke Excel.
            </div>
            <div class="menu-card-arrow">
                Buka Laporan <i class="bi bi-arrow-right"></i>
            </div>
        </a>

        {{-- 3. Laporan Modal Usaha --}}
        <a href="{{ route('owner.modal.index') }}" class="menu-card">
            <div class="menu-card-icon icon-green">
                <i class="bi bi-wallet2"></i>
            </div>
            <div class="menu-card-title">Laporan Modal Usaha</div>
            <div class="menu-card-desc">
                Kelola setoran modal dan pantau riwayat perputaran modal usaha secara lengkap.
            </div>
            <div class="menu-card-arrow">
                Buka Modal <i class="bi bi-arrow-right"></i>
            </div>
        </a>

        {{-- 4. Performa Bisnis --}}
        <a href="{{ route('owner.performa') }}" class="menu-card">
            <div class="menu-card-icon icon-blue">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div class="menu-card-title">Performa Bisnis</div>
            <div class="menu-card-desc">
                Analisis tren performa bisnis berdasarkan periode waktu, kategori menu, dan jam sibuk.
            </div>
            <div class="menu-card-arrow">
                Buka Performa <i class="bi bi-arrow-right"></i>
            </div>
        </a>

        {{-- 5. Flavory AI Consultant (special animated card) --}}
        <a href="{{ route('owner.ai') }}" class="menu-card menu-card-ai">
            <span class="ai-sparkle">✨</span>
            <div class="menu-card-icon icon-ai">
                <i class="bi bi-stars"></i>
            </div>
            <div class="menu-card-title">Flavory AI Consultant</div>
            <div class="menu-card-desc">
                Konsultan bisnis berbasis AI. Tanya apa saja soal performa outlet — dijawab berdasarkan data nyata Anda.
            </div>
            <div class="menu-card-arrow">
                Mulai Chat AI <i class="bi bi-arrow-right"></i>
            </div>
        </a>

        {{-- 6. Pengaturan Form Pesanan --}}
        <a href="{{ route('owner.pengaturan.index') }}" class="menu-card">
            <div class="menu-card-icon icon-purple">
                <i class="bi bi-sliders"></i>
            </div>
            <div class="menu-card-title">Pengaturan Form Pesanan</div>
            <div class="menu-card-desc">
                Konfigurasi tampilan & kolom form pesanan online: tipe layanan, tabel, catatan, dan lainnya.
            </div>
            <div class="menu-card-arrow">
                Buka Pengaturan <i class="bi bi-arrow-right"></i>
            </div>
        </a>

        {{-- 7. Langganan --}}
        <a href="{{ route('owner.langganan.index') }}" class="menu-card">
            <div class="menu-card-icon icon-slate">
                <i class="bi bi-patch-check-fill"></i>
            </div>
            <div class="menu-card-title">Langganan</div>
            <div class="menu-card-desc">
                Kelola status langganan Flavory.id. Cek tanggal jatuh tempo dan perpanjang paket Anda.
            </div>
            <div class="menu-card-arrow">
                Kelola Langganan <i class="bi bi-arrow-right"></i>
            </div>
        </a>

    </div>{{-- /menu-grid --}}

</main>

</body>
</html>
