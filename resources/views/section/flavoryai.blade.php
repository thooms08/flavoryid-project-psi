{{-- ══════════════════════════════════════════════════
   SECTION: FlavoryAI Spotlight
   resources/views/section/flavoryai.blade.php
══════════════════════════════════════════════════ --}}

@push('styles')
<style>
    @keyframes flavoryAiShift {
        0%   { background-position: 0% 50%; }
        50%  { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    @keyframes flavoryAiOrbFloat {
        0%,100% { transform: translate(0,0) scale(1); }
        33%     { transform: translate(30px,-25px) scale(1.06); }
        66%     { transform: translate(-20px,18px) scale(.95); }
    }
    @keyframes flavoryAiScreenFloat {
        0%,100% { transform: translateY(0) rotate(-1.5deg); }
        50%     { transform: translateY(-12px) rotate(-1.5deg); }
    }

    .flavoryai-section {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #fff4e0 0%, #fff0d0 30%, #ffe8b0 65%, #ffd98a 100%);
        background-size: 400% 400%;
        animation: flavoryAiShift 10s ease infinite;
        padding: 80px 0;
        border-top: 3px solid transparent;
        border-image: linear-gradient(135deg, #ff6a00, #ff9a00) 1;
    }

    .flavoryai-orb {
        position: absolute; border-radius: 50%;
        filter: blur(80px); pointer-events: none; z-index: 1;
    }
    .flavoryai-orb1 {
        width: 600px; height: 600px;
        background: radial-gradient(circle, rgba(255,106,0,.18), transparent 65%);
        top: -20%; left: -10%;
        animation: flavoryAiOrbFloat 11s ease-in-out infinite;
    }
    .flavoryai-orb2 {
        width: 450px; height: 450px;
        background: radial-gradient(circle, rgba(255,154,0,.15), transparent 65%);
        bottom: -15%; right: -8%;
        animation: flavoryAiOrbFloat 13s ease-in-out infinite reverse;
    }
    .flavoryai-orb3 {
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(255,180,0,.12), transparent 65%);
        top: 40%; left: 35%;
        animation: flavoryAiOrbFloat 8s ease-in-out infinite 2s;
    }

    /* Logo badge */
    .flavoryai-logo-badge {
        width: 52px; height: 52px;
        border-radius: 14px;
        object-fit: cover;
        box-shadow: 0 8px 24px rgba(255,106,0,.4);
    }

    /* Tag */
    .flavoryai-tag {
        font-size: .68rem; font-weight: 800; letter-spacing: 1.5px;
        text-transform: uppercase;
        background: linear-gradient(135deg, #ff6a00, #ff9a00);
        background-size: 200% 200%;
        animation: flavoryAiShift 3s ease infinite;
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 2px; display: block;
    }
    .flavoryai-name-text {
        font-size: 1.6rem; font-weight: 900; color: #1e293b; line-height: 1;
    }
    .flavoryai-name-text span {
        background: linear-gradient(135deg, #ff6a00, #ff9a00);
        background-size: 200% 200%;
        animation: flavoryAiShift 3s ease infinite;
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Headline */
    .flavoryai-headline {
        font-size: clamp(1.8rem, 3.5vw, 2.6rem);
        font-weight: 900; color: #1e1e2e; line-height: 1.2; margin-bottom: 1.2rem;
    }
    .flavoryai-grad-text {
        background: linear-gradient(135deg, #ff6a00, #ff9a00, #ffb300);
        background-size: 300% 300%;
        animation: flavoryAiShift 4s ease infinite;
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .flavoryai-desc { color: #475569; font-size: .93rem; line-height: 1.75; margin-bottom: 1.5rem; }

    .flavoryai-features li {
        color: #334155; font-size: .88rem; margin-bottom: .6rem;
        display: flex; align-items: center; gap: 10px;
    }
    .flavoryai-features li i {
        background: linear-gradient(135deg, #ff6a00, #ff9a00);
        background-size: 200%; animation: flavoryAiShift 3s ease infinite;
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        background-clip: text; font-size: 1rem; flex-shrink: 0;
    }

    .btn-flavoryai-cta {
        display: inline-flex; align-items: center;
        padding: 13px 28px; border-radius: 50px; border: none;
        font-weight: 800; font-size: .9rem; color: white;
        text-decoration: none; cursor: pointer;
        background: linear-gradient(135deg, #ff6a00, #ff9a00, #ffb300, #ff6a00);
        background-size: 300% 300%;
        animation: flavoryAiShift 3s ease infinite;
        box-shadow: 0 8px 28px rgba(255,106,0,.4);
        transition: transform .25s, box-shadow .25s;
    }
    .btn-flavoryai-cta:hover { transform: translateY(-2px); box-shadow: 0 12px 36px rgba(255,154,0,.5); color: white; }

    .btn-flavoryai-ghost {
        display: inline-flex; align-items: center;
        padding: 13px 24px; border-radius: 50px;
        border: 1.5px solid rgba(255,106,0,.4);
        font-weight: 700; font-size: .88rem; color: #ff6a00;
        text-decoration: none; background: rgba(255,106,0,.06); transition: all .25s;
    }
    .btn-flavoryai-ghost:hover { border-color: #ff9a00; background: rgba(255,154,0,.08); color: #ff7800; }

    .flavoryai-screen-wrap { position: relative; padding: 4px; border-radius: 24px; }
    .flavoryai-screen-glow {
        position: absolute; inset: -20px;
        background: radial-gradient(ellipse at center, rgba(255,154,0,.22) 0%, transparent 70%);
        border-radius: 40px; pointer-events: none;
        animation: flavoryAiOrbFloat 7s ease-in-out infinite;
    }
    .flavoryai-screenshot {
        width: 100%; border-radius: 20px;
        box-shadow: 0 20px 60px rgba(255,106,0,.2), 0 4px 20px rgba(255,154,0,.15), 0 0 0 1px rgba(255,106,0,.15);
        display: block;
        animation: flavoryAiScreenFloat 6s ease-in-out infinite;
    }

    @media (max-width: 991px) {
        .flavoryai-section { padding: 60px 0; }
        .flavoryai-headline { font-size: 1.9rem; }
        .flavoryai-screenshot { animation: none; }
    }
</style>
@endpush

<section id="mineai" class="py-0 overflow-hidden">
    <div class="flavoryai-section">

        <div class="flavoryai-orb flavoryai-orb1"></div>
        <div class="flavoryai-orb flavoryai-orb2"></div>
        <div class="flavoryai-orb flavoryai-orb3"></div>

        <div class="container py-5 position-relative" style="z-index:2;">
            <div class="row align-items-center g-5">

                {{-- Kiri: teks --}}
                <div class="col-lg-5 text-center text-lg-start animate-slide-up">

                    {{-- Logo + badge --}}
                    <div class="d-flex align-items-center justify-content-center justify-content-lg-start gap-3 mb-4">
                        <img src="{{ asset('assets/logo-512.png') }}"
                             alt="FlavoryAI – Asisten Bisnis AI Flavory.id"
                             class="flavoryai-logo-badge"
                             onerror="this.style.display='none'">
                        <div>
                            <span class="flavoryai-tag">Fitur Eksklusif</span>
                            <h2 class="flavoryai-name-text mb-0">Flavory<span>AI</span></h2>
                        </div>
                    </div>

                    <h3 class="flavoryai-headline">
                        Konsultan Bisnis<br>
                        <span class="flavoryai-grad-text">Berbasis AI</span><br>
                        Eksklusif di Flavory.id
                    </h3>

                    <p class="flavoryai-desc">
                        FlavoryAI membaca data transaksi nyata outlet kamu, memberikan insight otomatis, dan bisa diajak ngobrol layaknya konsultan bisnis pribadi — kapan saja, langsung dari dashboard kasir.
                    </p>

                    <ul class="flavoryai-features list-unstyled">
                        <li><i class="bi bi-check-circle-fill"></i> Analisis omzet & tren 30 hari otomatis</li>
                        <li><i class="bi bi-check-circle-fill"></i> Saran strategi berbasis data real outlet</li>
                        <li><i class="bi bi-check-circle-fill"></i> Chat AI yang ingat konteks bisnis kamu</li>
                        <li><i class="bi bi-check-circle-fill"></i> Deteksi stok tipis & peringatan dini</li>
                    </ul>

                    <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start mt-4">
                        <a href="{{ route('register') }}" class="btn-flavoryai-cta">
                            <i class="bi bi-stars me-2"></i> Coba FlavoryAI Gratis
                        </a>
                        <a href="{{ route('panduan.daftar') }}" class="btn-flavoryai-ghost">
                            Pelajari Lebih Lanjut <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                {{-- Kanan: screenshot --}}
                <div class="col-lg-7 animate-fade-in">
                    <div class="flavoryai-screen-wrap">
                        <div class="flavoryai-screen-glow"></div>
                        <img src="{{ asset('assets/ss-mineai.jpg') }}"
                             alt="Tampilan FlavoryAI, Fitur Konsultasi Bisnis AI Flavory.id"
                             class="flavoryai-screenshot"
                             onerror="this.src='https://placehold.co/900x560/1e1e2e/ff6a00?text=FlavoryAI+Preview'">
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
