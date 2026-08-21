<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Terjadi Kesalahan' }} - Flavory.id</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    @include('layouts.favicon')

    <style>
        :root {
            --primary-orange : #ff6a00;
            --dark-orange    : #e65f00;
            --light-orange   : #fff4e6;
            --text-dark      : #1e293b;
            --text-muted     : #64748b;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ffffff 0%, #fff4ec 55%, #fff0f7 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* ── dot-grid background ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,106,0,.07) 1px, transparent 1px);
            background-size: 36px 36px;
            pointer-events: none;
            z-index: 0;
        }

        /* ── soft orbs ── */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            pointer-events: none;
            z-index: 0;
        }
        .orb-1 {
            width: 480px; height: 480px;
            background: radial-gradient(circle, rgba(255,106,0,.13) 0%, transparent 70%);
            top: -10%; left: -8%;
            animation: orbDrift 20s ease-in-out infinite;
        }
        .orb-2 {
            width: 360px; height: 360px;
            background: radial-gradient(circle, rgba(238,9,121,.07) 0%, transparent 70%);
            bottom: -5%; right: 0%;
            animation: orbDrift 25s ease-in-out infinite reverse;
        }
        @keyframes orbDrift {
            0%,100% { transform: translate(0,0); }
            50%      { transform: translate(30px,-20px); }
        }

        /* ── navbar minimal ── */
        .err-nav {
            position: relative;
            z-index: 10;
            padding: 18px 0;
            background: rgba(255,255,255,.92);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,.05);
        }
        .err-nav-inner {
            max-width: 1140px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .err-brand {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--text-dark);
            text-decoration: none;
            letter-spacing: -.5px;
        }
        .err-brand span { color: var(--primary-orange); }
        .btn-home {
            background-color: var(--primary-orange);
            color: #fff;
            font-weight: 600;
            font-size: .85rem;
            padding: 9px 22px;
            border-radius: 50px;
            border: none;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(255,106,0,.3);
            transition: all .3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-home:hover {
            background-color: var(--dark-orange);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255,106,0,.4);
        }

        /* ── main content ── */
        .err-main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 24px;
            position: relative;
            z-index: 1;
        }

        .err-card {
            background: rgba(255,255,255,.97);
            border: 1px solid rgba(255,106,0,.12);
            border-radius: 28px;
            box-shadow: 0 24px 64px rgba(0,0,0,.08), 0 4px 20px rgba(255,106,0,.06);
            padding: 56px 48px;
            text-align: center;
            max-width: 560px;
            width: 100%;
            animation: cardIn .5s cubic-bezier(.22,.68,0,1.2) both;
        }
        @keyframes cardIn {
            from { opacity:0; transform: translateY(28px) scale(.97); }
            to   { opacity:1; transform: translateY(0) scale(1); }
        }

        /* ── error code big number ── */
        .err-code {
            font-size: clamp(5rem, 18vw, 8rem);
            font-weight: 900;
            line-height: 1;
            letter-spacing: -4px;
            background: linear-gradient(135deg, var(--primary-orange), #ff9a50);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
            user-select: none;
        }

        /* ── icon badge ── */
        .err-icon-wrap {
            width: 72px; height: 72px;
            background: var(--light-orange);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--primary-orange);
            margin-bottom: 20px;
            box-shadow: 0 4px 16px rgba(255,106,0,.15);
        }

        /* ── title & message ── */
        .err-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 12px;
            line-height: 1.25;
        }
        .err-message {
            font-size: .95rem;
            color: var(--text-muted);
            line-height: 1.7;
            margin-bottom: 32px;
        }
        .err-message strong { color: var(--text-dark); font-weight: 600; }

        /* ── action buttons ── */
        .err-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
        }
        .btn-primary-err {
            background-color: var(--primary-orange);
            color: #fff;
            font-weight: 600;
            font-size: .9rem;
            padding: 12px 28px;
            border-radius: 50px;
            border: none;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(255,106,0,.3);
            transition: all .3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-primary-err:hover {
            background-color: var(--dark-orange);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 22px rgba(255,106,0,.4);
        }
        .btn-secondary-err {
            border: 1.5px solid rgba(255,106,0,.35);
            color: var(--primary-orange);
            background: transparent;
            font-weight: 600;
            font-size: .9rem;
            padding: 11px 26px;
            border-radius: 50px;
            text-decoration: none;
            transition: all .3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-secondary-err:hover {
            background: var(--light-orange);
            border-color: var(--primary-orange);
            color: var(--primary-orange);
            transform: translateY(-2px);
        }

        /* ── divider ── */
        .err-divider {
            border: none;
            border-top: 1px solid rgba(0,0,0,.07);
            margin: 28px 0 20px;
        }

        /* ── contact hint ── */
        .err-contact {
            font-size: .8rem;
            color: #94a3b8;
        }
        .err-contact a {
            color: var(--primary-orange);
            text-decoration: none;
            font-weight: 600;
        }
        .err-contact a:hover { text-decoration: underline; }

        /* ── footer ── */
        .err-footer {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 18px 24px;
            font-size: .78rem;
            color: #94a3b8;
            border-top: 1px solid rgba(0,0,0,.06);
            background: rgba(255,255,255,.6);
            backdrop-filter: blur(8px);
        }

        @media (max-width: 575px) {
            .err-card { padding: 36px 24px; border-radius: 20px; }
            .err-actions { flex-direction: column; align-items: stretch; }
            .btn-primary-err, .btn-secondary-err { justify-content: center; }
        }
    </style>
</head>
<body>

    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    {{-- ── Navbar minimal ── --}}
    <nav class="err-nav">
        <div class="err-nav-inner">
            <a href="{{ url('/') }}" class="err-brand">
                Flavory<span>.id</span>
            </a>
            <a href="{{ url('/') }}" class="btn-home">
                <i class="bi bi-house-fill"></i> Beranda
            </a>
        </div>
    </nav>

    {{-- ── Konten error ── --}}
    <main class="err-main">
        <div class="err-card">

            {{-- Kode error besar --}}
            <div class="err-code">{{ $code ?? '?' }}</div>

            {{-- Ikon --}}
            <div class="err-icon-wrap">
                <i class="bi {{ $icon ?? 'bi-exclamation-triangle-fill' }}"></i>
            </div>

            {{-- Judul --}}
            <h1 class="err-title">{{ $title ?? 'Terjadi Kesalahan' }}</h1>

            {{-- Pesan --}}
            <p class="err-message">{!! $description ?? 'Terjadi sesuatu yang tidak terduga. Silakan coba lagi atau hubungi admin kami.' !!}</p>

            {{-- Tombol aksi --}}
            <div class="err-actions">
                <a href="{{ url('/') }}" class="btn-primary-err">
                    <i class="bi bi-house-fill"></i> Kembali ke Beranda
                </a>
                @if(isset($showBack) && $showBack)
                <a href="javascript:history.back()" class="btn-secondary-err">
                    <i class="bi bi-arrow-left"></i> Halaman Sebelumnya
                </a>
                @endif
            </div>

            <hr class="err-divider">

            {{-- Kontak admin --}}
            <p class="err-contact">
                Butuh bantuan? Hubungi kami di
                <a href="https://wa.me/6285797574754?text=Hallo%20admin%20Flavory.id,%20saya%20menemukan%20masalah%20(error%20{{ $code ?? '' }})%20di%20website.%20Bisa%20dibantu?">
                    WhatsApp
                </a>
                atau email ke
                <a href="mailto:adminku@flavory.id">adminku@flavory.id</a>
            </p>
        </div>
    </main>

    {{-- ── Footer ── --}}
    <footer class="err-footer">
        &copy; {{ date('Y') }} Flavory.id. All Rights Reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
