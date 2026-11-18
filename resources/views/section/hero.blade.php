{{-- ══════════════════════════════════════════════════
   SECTION: Hero + Trust Bar + Keunggulan
   resources/views/section/hero.blade.php
══════════════════════════════════════════════════ --}}

@push('styles')
<style>
    /* ══ HERO SECTION ══ */
    .hero-section {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, #ffffff 0%, #fff4ec 55%, #fff0f7 100%);
        overflow: hidden;
        padding: 130px 0 80px;
    }
    .hero-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle, rgba(255,106,0,.07) 1px, transparent 1px);
        background-size: 36px 36px;
        z-index: 0;
        pointer-events: none;
    }
    .hero-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(100px);
        pointer-events: none;
        z-index: 0;
    }
    .hero-orb-1 {
        width: 500px; height: 500px;
        background: radial-gradient(circle, rgba(255,106,0,.12) 0%, transparent 70%);
        top: -10%; left: -8%;
        animation: orbDrift1 20s ease-in-out infinite;
    }
    .hero-orb-2 {
        width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(238,9,121,.06) 0%, transparent 70%);
        bottom: -5%; right: 0%;
        animation: orbDrift2 25s ease-in-out infinite;
    }
    @keyframes orbDrift1 {
        0%,100% { transform: translate(0,0) scale(1); }
        50%      { transform: translate(30px,-20px) scale(1.05); }
    }
    @keyframes orbDrift2 {
        0%,100% { transform: translate(0,0) scale(1); }
        50%      { transform: translate(-20px,15px) scale(1.03); }
    }
    .hero-content-col { position: relative; z-index: 2; }

    /* Badge */
    .hero-badge-wrap {
        opacity: 0;
        filter: blur(6px);
        transform: translateY(12px);
        transition: opacity .6s ease, filter .6s ease, transform .6s ease;
        will-change: opacity, filter, transform;
        display: inline-block;
        margin-bottom: 20px;
    }
    .hero-badge-wrap.visible { opacity: 1; filter: blur(0); transform: translateY(0); }
    .hero-badge-wrap.hidden  { opacity: 0; filter: blur(4px); transform: translateY(-8px); transition: opacity .3s, filter .3s, transform .3s; }
    .hero-badge-text {
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: 1.8px;
        color: var(--primary-orange);
        text-transform: uppercase;
        display: inline-block;
    }
    .hero-badge-text .wave-char { display: inline-block; animation: waveChar 1.6s ease-in-out infinite; will-change: transform; }
    .hero-badge-text .wave-char.space { animation: none; }
    @keyframes waveChar {
        0%, 100% { transform: translateY(0);    }
        40%      { transform: translateY(-5px); }
        60%      { transform: translateY(1px);  }
    }

    /* Title */
    .hero-title {
        font-size: clamp(1.9rem, 3.5vw, 2.8rem);
        line-height: 1.2;
        font-weight: 900;
        letter-spacing: -1px;
        color: #1e293b;
        margin-bottom: 18px;
    }
    .hero-title .w, .hero-subtitle .w, .hero-cta .w {
        display: inline-block;
        opacity: 0;
        filter: blur(8px);
        transform: translateY(14px);
        transition: opacity .5s ease, filter .5s ease, transform .5s ease;
        will-change: opacity, filter, transform;
    }
    .w.visible { opacity: 1; filter: blur(0); transform: translateY(0); }
    .w.hidden  { opacity: 0; filter: blur(6px); transform: translateY(-10px); transition: opacity .3s, filter .3s, transform .3s; }
    #hero-rotating-line { display: block; min-height: 1.35em; position: relative; }
    #hero-rotating-line .rot-word {
        display: inline-block;
        opacity: 0; filter: blur(10px); transform: translateY(14px);
        transition: opacity .45s ease, filter .45s ease, transform .45s ease;
        will-change: opacity, filter, transform;
        background: linear-gradient(135deg, var(--primary-orange), #ff9a50);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    #hero-rotating-line .rot-word.rot-in  { opacity:1; filter:blur(0); transform:translateY(0); }
    #hero-rotating-line .rot-word.rot-out { opacity:0; filter:blur(8px); transform:translateY(-12px); transition: opacity .3s, filter .3s, transform .3s; }

    /* Subtitle */
    .hero-subtitle { font-size: .97rem; color: #64748b; line-height: 1.75; margin-bottom: 22px; max-width: 500px; }
    .hero-subtitle strong { color: #334155; font-weight: 700; }

    /* Trust chips */
    .hero-trust-row { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 28px; }
    .hero-trust-chip {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 13px;
        background: rgba(255,255,255,.8);
        border: 1px solid rgba(255,106,0,.13);
        border-radius: 100px;
        backdrop-filter: blur(8px);
        box-shadow: 0 2px 6px rgba(0,0,0,.04);
    }
    .hero-trust-chip i    { color: var(--primary-orange); font-size: .8rem; }
    .hero-trust-chip span { color: #64748b; font-size: .73rem; font-weight: 600; }

    /* CTA */
    .btn-orange {
        background: linear-gradient(135deg, var(--primary-orange), #ff8c33);
        color: #fff; border: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(255,106,0,.28);
    }
    .btn-orange:hover { background: linear-gradient(135deg, #e65f00, var(--primary-orange)); transform: translateY(-2px); box-shadow: 0 8px 28px rgba(255,106,0,.35); color: #fff; }
    .btn-outline-orange { border: 1.5px solid rgba(255,106,0,.4); color: var(--primary-orange); background: rgba(255,106,0,.04); transition: all 0.3s ease; }
    .btn-outline-orange:hover { background: rgba(255,106,0,.1); border-color: var(--primary-orange); transform: translateY(-2px); color: var(--primary-orange); }

    /* ══ 3D Visual Column ══ */
    .hero-visual-col { position: relative; z-index: 2; }
    .hero-3d-wrapper { animation: heroCardFloat 8s ease-in-out infinite; }
    @keyframes heroCardFloat {
        0%,100% { transform: translateY(0) perspective(1200px) rotateY(-4deg) rotateX(2deg); }
        50%      { transform: translateY(-12px) perspective(1200px) rotateY(-2deg) rotateX(1deg); }
    }
    .hero-cards-top    { display: flex; gap: 10px; margin-bottom: 10px; justify-content: flex-start; }
    .hero-cards-bottom { display: flex; gap: 10px; margin-top: 10px; justify-content: flex-end; align-items: center; }
    .hero-main-card {
        position: relative; border-radius: 20px; overflow: hidden;
        background: #fff; border: 1px solid rgba(0,0,0,.07);
        box-shadow: 0 24px 64px rgba(0,0,0,.1), 0 6px 20px rgba(255,106,0,.07);
    }
    .hero-main-card img { width: 100%; display: block; }
    .hero-card-glow {
        position: absolute; bottom: -28px; left: 50%; transform: translateX(-50%);
        width: 65%; height: 50px; background: rgba(255,106,0,.15); filter: blur(28px);
        border-radius: 50%; pointer-events: none; z-index: -1;
    }
    .hero-float-card {
        background: rgba(255,255,255,.97); border: 1px solid rgba(255,106,0,.12);
        border-radius: 14px; padding: 12px 16px; backdrop-filter: blur(16px);
        box-shadow: 0 8px 24px rgba(0,0,0,.07); flex-shrink: 0;
    }
    .hero-float-card-icon { width: 30px; height: 30px; background: rgba(255,106,0,.08); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: .8rem; color: var(--primary-orange); margin-bottom: 6px; }
    .hero-float-card-label { font-size: .58rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #94a3b8; margin-bottom: 2px; white-space: nowrap; }
    .hero-float-card-value { font-size: .95rem; font-weight: 900; color: #1e293b; line-height: 1; margin-bottom: 2px; white-space: nowrap; }
    .hero-float-card-trend { font-size: .6rem; color: #22c55e; font-weight: 600; white-space: nowrap; }
    .hero-float-card-sub   { font-size: .6rem; color: #64748b; white-space: nowrap; }
    .fcard-revenue { animation: fcardFloat 7s ease-in-out infinite; }
    .fcard-ai      { animation: fcardFloat 9s ease-in-out infinite 1.2s; }
    .fcard-orders  { animation: fcardFloat 8s ease-in-out infinite .6s; }
    .fcard-stok    { animation: fcardFloat 7.5s ease-in-out infinite .4s; }
    @keyframes fcardFloat { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-7px); } }

    @media (max-width: 991px) {
        .hero-section { min-height: auto; padding: 100px 0 56px; }
        .hero-title { font-size: 1.9rem; letter-spacing: -.8px; }
        .hero-3d-wrapper { animation: none; transform: none; }
        .hero-cards-top, .hero-cards-bottom { display: none; }
    }
    @media (max-width: 575px) {
        .hero-title { font-size: 1.75rem; letter-spacing: -.6px; }
        .hero-trust-row { gap: 7px; }
        .hero-trust-chip { padding: 5px 10px; }
    }

    /* ══ ROBOT ICON (FlavoryAI float card) ══ */
    .flavoryai-robot-icon {
        display: flex; align-items: center; justify-content: center;
        width: 22px; height: 22px;
    }
    .flavoryai-robot-icon svg { width: 100%; height: 100%; }

    /* ══ FEATURE CARDS ══ */
    .feature-icon-box {
        width: 60px; height: 60px; background-color: var(--light-orange);
        color: var(--primary-orange); border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; margin-bottom: 20px; transition: transform 0.3s ease;
    }
    .feature-card {
        border: 1px solid rgba(0,0,0,0.05); border-radius: 20px; padding: 30px;
        transition: all 0.3s ease; height: 100%; background: white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    }
    .feature-card:hover { transform: translateY(-10px); box-shadow: 0 15px 40px rgba(255, 106, 0, 0.08); }
    .feature-card:hover .feature-icon-box { transform: scale(1.1); }
</style>
@endpush

{{-- ═══ HERO ═══ --}}
<header id="home" class="hero-section">
    <div class="hero-orb hero-orb-1"></div>
    <div class="hero-orb hero-orb-2"></div>

    <div class="container">
        <div class="row align-items-center g-5">

            {{-- LEFT: Content --}}
            <div class="col-lg-6 order-2 order-lg-1 hero-content-col hero-reveal-col">
                <div class="hero-badge-wrap">
                    <span class="hero-badge-text">APLIKASI POS AI TERBAIK DI INDONESIA</span>
                </div>

                <h1 class="hero-title">
                    <span class="w">Bukan</span> <span class="w">Sekadar</span> <span class="w">POS</span> <span class="w">Biasa.</span>
                    <span id="hero-rotating-line" class="d-block"></span>
                </h1>

                <p class="hero-subtitle">
                    <strong>Aplikasi kasir all-in-one</strong> untuk mencatat pesanan dan mengelola operasional dengan mudah, kini dilengkapi <strong>FlavoryAI</strong> — asisten kecerdasan buatan yang memberi saran strategi bisnis berdasarkan data penjualan nyata Anda.
                </p>

                <div class="hero-trust-row">
                    <div class="hero-trust-chip"><i class="bi bi-wifi-off"></i><span>Works Offline</span></div>
                    <div class="hero-trust-chip"><i class="bi bi-robot"></i><span>FlavoryAI Powered</span></div>
                    <div class="hero-trust-chip"><i class="bi bi-shield-check-fill"></i><span>Aman & Terpercaya</span></div>
                </div>

                <div class="d-flex flex-wrap gap-3 hero-cta">
                    <button type="button"
                        onclick="document.getElementById('modalCobaGratis').showModal()"
                        class="btn btn-orange btn-lg px-4 fw-bold shadow-sm">
                        <i class="bi bi-rocket-takeoff-fill me-2"></i> Coba Gratis Sekarang
                    </button>
                    <a href="{{ route('panduan.daftar') }}" class="btn btn-outline-orange btn-lg px-4 fw-bold">
                        <i class="bi bi-play-circle me-1"></i> Lihat Cara Kerjanya
                    </a>
                </div>
            </div>

            {{-- RIGHT: 3D Visual --}}
            <div class="col-lg-6 order-1 order-lg-2 hero-visual-col">
                <div class="hero-3d-wrapper">

                    <div class="hero-cards-top">
                        <div class="hero-float-card fcard-revenue">
                            <div class="hero-float-card-icon"><i class="bi bi-graph-up-arrow"></i></div>
                            <div class="hero-float-card-label">Omzet Hari Ini</div>
                            <div class="hero-float-card-value">Rp 12,8 Jt</div>
                            <div class="hero-float-card-trend"><i class="bi bi-arrow-up"></i> +32%</div>
                        </div>
                        {{-- FlavoryAI float card — robot wajah interaktif kecil --}}
                        <div class="hero-float-card fcard-ai">
                            <div class="hero-float-card-icon">
                                <div class="flavoryai-robot-icon">
                                    <svg viewBox="0 0 200 300" xmlns="http://www.w3.org/2000/svg" focusable="false">
                                        <defs>
                                            <linearGradient id="hfc-g-head" x1="0" y1="0" x2="0" y2="1">
                                                <stop offset="0" stop-color="#FFB460"/>
                                                <stop offset="1" stop-color="#F26A0F"/>
                                            </linearGradient>
                                        </defs>
                                        {{-- antena --}}
                                        <rect x="97" y="18" width="6" height="20" rx="3" fill="#3A2316"/>
                                        <circle cx="100" cy="14" r="8" fill="#FFD27A"/>
                                        {{-- kepala --}}
                                        <rect x="34" y="34" width="132" height="98" rx="44" fill="url(#hfc-g-head)"/>
                                        {{-- layar wajah --}}
                                        <rect x="46" y="48" width="108" height="70" rx="32" fill="#2A1810"/>
                                        {{-- mata --}}
                                        <ellipse cx="78" cy="78" rx="9" ry="12" fill="#FFE9CC"/>
                                        <circle cx="81" cy="73" r="3" fill="#fff"/>
                                        <ellipse cx="122" cy="78" rx="9" ry="12" fill="#FFE9CC"/>
                                        <circle cx="125" cy="73" r="3" fill="#fff"/>
                                        {{-- senyum --}}
                                        <path d="M88 98 Q100 110 112 98" fill="none" stroke="#FFB067" stroke-width="4.5" stroke-linecap="round"/>
                                        {{-- badan kecil --}}
                                        <rect x="86" y="126" width="28" height="14" rx="4" fill="#3A2316"/>
                                        <path d="M60 134 L140 134 Q152 134 152 146 L148 180 Q144 208 120 212 L80 212 Q56 208 52 180 L48 146 Q48 134 60 134 Z" fill="#FFA24A"/>
                                        {{-- panel dada --}}
                                        <rect x="72" y="148" width="56" height="34" rx="12" fill="#FFF4E8"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="hero-float-card-label">FlavoryAI</div>
                            <div class="hero-float-card-value">95%</div>
                            <div class="hero-float-card-sub">Akurasi prediksi</div>
                        </div>
                    </div>

                    <div class="hero-main-card">
                        <img src="{{ asset('assets/hero.png') }}"
                             onerror="this.src='https://placehold.co/700x470/fff4ec/ff6a00?text=Dashboard+Flavory.id'"
                             alt="Tampilan Dashboard Manajemen Bisnis Restoran Flavory.id">
                        <div class="hero-card-glow"></div>
                    </div>

                    <div class="hero-cards-bottom">
                        <div class="hero-float-card fcard-orders">
                            <div class="hero-float-card-icon"><i class="bi bi-receipt"></i></div>
                            <div class="hero-float-card-label">Total Order</div>
                            <div class="hero-float-card-value">247</div>
                            <div class="hero-float-card-sub">Pesanan hari ini</div>
                        </div>
                        <div class="hero-float-card fcard-stok">
                            <div class="hero-float-card-icon"><i class="bi bi-boxes"></i></div>
                            <div class="hero-float-card-label">Manajemen Stok</div>
                            <div class="hero-float-card-value">Real-time</div>
                            <div class="hero-float-card-sub">Update Otomatis</div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</header>

{{-- ═══ TRUST BAR ═══ --}}
<section class="py-4 border-bottom bg-white animate-slide-up">
    <div class="container text-center">
        <p class="mb-0 fw-bold text-muted" style="letter-spacing: 1px; text-transform: uppercase; font-size: 0.9rem;">
            <i class="bi bi-shield-check text-success me-2"></i> Dipercaya Ratusan Pemilik Usaha Kuliner di Seluruh Indonesia
        </p>
    </div>
</section>

{{-- ═══ KEUNGGULAN ═══ --}}
<section id="keuntungan" class="py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5 mx-auto animate-slide-up" style="max-width: 700px;">
            <h2 class="fw-bold">Mengapa Aplikasi Kasir Kuliner Kami Berbeda?</h2>
            <p class="text-muted">Kami mendesain Flavory.id agar mudah dipahami oleh pemula, namun memiliki fitur canggih layaknya restoran profesional kelas atas.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3 animate-slide-up" style="transition-delay: 0.1s;">
                <article class="feature-card text-center">
                    <div class="feature-icon-box mx-auto"><i class="bi bi-lightning-charge-fill"></i></div>
                    <h3 class="h5 fw-bold">Sangat Mudah Dipakai</h3>
                    <p class="text-muted small mb-0">Tampilan kasir bersih dan tidak ribet. Siapapun bisa langsung menggunakannya dalam 5 menit pertama.</p>
                </article>
            </div>
            <div class="col-md-6 col-lg-3 animate-slide-up" style="transition-delay: 0.2s;">
                <article class="feature-card text-center">
                    <div class="feature-icon-box mx-auto"><i class="bi bi-robot"></i></div>
                    <h3 class="h5 fw-bold">Ditenagai FlavoryAI</h3>
                    <p class="text-muted small mb-0">Satu-satunya sistem yang memberikan pandangan objektif dan saran bisnis dari hasil analisa laporan outlet Anda.</p>
                </article>
            </div>
            <div class="col-md-6 col-lg-3 animate-slide-up" style="transition-delay: 0.3s;">
                <article class="feature-card text-center">
                    <div class="feature-icon-box mx-auto"><i class="bi bi-graph-up-arrow"></i></div>
                    <h3 class="h5 fw-bold">Bisnis Terpantau Penuh</h3>
                    <p class="text-muted small mb-0">Tinggalkan buku manual. Pantau penjualan, stok dapur, hingga rugi-laba secara real-time dari HP Anda.</p>
                </article>
            </div>
            <div class="col-md-6 col-lg-3 animate-slide-up" style="transition-delay: 0.4s;">
                <article class="feature-card text-center">
                    <div class="feature-icon-box mx-auto"><i class="bi bi-wifi-off"></i></div>
                    <h3 class="h5 fw-bold">Anti Gagal Offline</h3>
                    <p class="text-muted small mb-0">Internet mati? Tidak masalah. Aplikasi tetap berjalan normal dan otomatis sinkronisasi saat online.</p>
                </article>
            </div>
        </div>
    </div>
</section>
