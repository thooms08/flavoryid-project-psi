@extends('owner.layouts.app')

@section('title', 'Dashboard Owner')

@section('styles')
<style>
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
    .hero-orb-1 { width: 400px; height: 400px; background: radial-gradient(circle, rgba(255,106,0,.12), transparent 65%); top: -30%; right: -5%; }
    .hero-orb-2 { width: 280px; height: 280px; background: radial-gradient(circle, rgba(255,154,0,.1), transparent 65%); bottom: -20%; left: 10%; }
    .hero-content { position: relative; z-index: 2; max-width: 900px; margin: 0 auto; text-align: center; }
    .hero-greeting { font-size: 0.72rem; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; color: var(--orange); margin-bottom: 6px; }
    .hero-title { font-size: clamp(1.5rem, 3vw, 2.2rem); font-weight: 900; color: var(--text); line-height: 1.2; margin-bottom: 8px; }
    .hero-title span { background: linear-gradient(135deg, var(--orange), var(--orange-mid)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .hero-sub { font-size: 0.87rem; color: var(--muted); line-height: 1.6; max-width: 620px; margin: 0 auto; }

    /* ─── Main layout ────────────────────────────────────────────── */
    .main-wrap { max-width: 1200px; margin: 0 auto; padding: 40px 24px 80px; }
    .section-label { font-size: 0.68rem; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; color: var(--muted); margin-bottom: 20px; }

    /* ─── Menu Grid ──────────────────────────────────────────────── */
    .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px; }
    @media (min-width: 1024px) { .menu-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 640px)  { .menu-grid { grid-template-columns: 1fr; gap: 14px; } }

    /* ─── Menu Card ──────────────────────────────────────────────── */
    .menu-card {
        display: flex; flex-direction: column;
        background: var(--card-bg); border-radius: 22px; padding: 28px 24px 22px;
        border: 1px solid var(--border); box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        text-decoration: none; color: var(--text);
        transition: transform 0.22s cubic-bezier(.34,1.56,.64,1), box-shadow 0.22s ease, border-color 0.2s ease;
        position: relative; overflow: hidden;
    }
    .menu-card::before { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(255,106,0,0), rgba(255,106,0,0)); opacity: 0; transition: opacity 0.3s ease; }
    .menu-card:hover { transform: translateY(-6px); box-shadow: 0 16px 40px rgba(255,106,0,0.12); border-color: rgba(255,106,0,0.2); color: var(--text); text-decoration: none; }
    .menu-card:hover::before { opacity: 1; }
    .menu-card-icon { width: 52px; height: 52px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; margin-bottom: 18px; flex-shrink: 0; transition: transform 0.2s ease; }
    .menu-card:hover .menu-card-icon { transform: scale(1.08) rotate(-3deg); }
    .menu-card-title { font-size: 1rem; font-weight: 800; color: var(--text); margin-bottom: 6px; line-height: 1.3; }
    .menu-card-desc  { font-size: 0.78rem; color: var(--muted); line-height: 1.6; flex: 1; }
    .menu-card-arrow { display: inline-flex; align-items: center; gap: 5px; margin-top: 18px; font-size: 0.75rem; font-weight: 700; color: var(--orange); transition: gap 0.2s ease; }
    .menu-card:hover .menu-card-arrow { gap: 8px; }

    /* icon colors */
    .icon-orange { background: #fff4ec; color: var(--orange); }
    .icon-red    { background: #fef2f2; color: #ef4444; }
    .icon-green  { background: #f0fdf4; color: #22c55e; }
    .icon-blue   { background: #eff6ff; color: #3b82f6; }
    .icon-purple { background: #faf5ff; color: #a855f7; }
    .icon-slate  { background: #f1f5f9; color: #64748b; }

    /* ─── Outlet info bar ────────────────────────────────────────── */
    .outlet-bar { display: flex; align-items: center; gap: 16px; background: var(--card-bg); border: 1px solid var(--border); border-radius: 18px; padding: 16px 20px; margin-bottom: 36px; box-shadow: 0 2px 12px rgba(0,0,0,0.03); }
    .outlet-bar-icon { width: 44px; height: 44px; border-radius: 12px; background: #fff4ec; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: var(--orange); flex-shrink: 0; }
    .outlet-bar-info .label { font-size: 0.68rem; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; color: var(--muted); margin-bottom: 2px; }
    .outlet-bar-info .value { font-size: 0.95rem; font-weight: 800; color: var(--text); }
    .outlet-bar-badge { margin-left: auto; padding: 4px 12px; border-radius: 100px; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.5px; }
    .badge-owner { background: linear-gradient(135deg, var(--orange), var(--orange-mid)); color: #fff; }
    @media (max-width: 600px) { .outlet-bar { flex-wrap: wrap; gap: 12px; } .outlet-bar-badge { margin-left: 0; } .hero-banner { padding: 28px 20px 24px; } .main-wrap { padding: 28px 16px 64px; } }

    /* ─── Card AI ────────────────────────────────────────────────── */
    @keyframes aiShift { 0%,100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }
    @keyframes aiDot   { 0% { box-shadow: 0 0 0 0 rgba(255,255,255,.7); } 100% { box-shadow: 0 0 0 8px rgba(255,255,255,0); } }

    .menu-card-ai {
        background: linear-gradient(135deg, #ff6a00, #ff9a00, #ffb300, #ff7a1a, #ff6a00);
        background-size: 300% 300%;
        animation: aiShift 6s ease infinite;
        border: none;
        box-shadow: 0 8px 32px rgba(255,106,0,0.35);
        transform: perspective(900px) rotateX(var(--rx,0deg)) rotateY(var(--ry,0deg));
        transition: transform .18s ease-out, box-shadow .22s ease;
        will-change: transform;
    }
    .menu-card-ai:hover { transform: perspective(900px) rotateX(var(--rx,0deg)) rotateY(var(--ry,0deg)) translateY(-6px); box-shadow: 0 22px 52px rgba(255,106,0,0.5); border: none; }
    .menu-card-ai:active { transform: perspective(900px) scale(.985); }
    .menu-card-ai::before { display: none; }
    .menu-card-ai::after { content: ''; position: absolute; inset: 0; background: radial-gradient(260px circle at var(--mx,50%) var(--my,0%), rgba(255,255,255,.38), transparent 62%); opacity: 0; transition: opacity .25s ease; pointer-events: none; }
    .menu-card-ai:hover::after { opacity: 1; }
    .menu-card-ai .menu-card-title { color: #fff; }
    .menu-card-ai .menu-card-desc  { color: rgba(255,255,255,.92); }
    .ai-status { position: absolute; top: 16px; right: 16px; display: inline-flex; align-items: center; gap: 7px; padding: 4px 11px 4px 9px; border-radius: 100px; background: rgba(255,255,255,.2); border: 1px solid rgba(255,255,255,.3); color: #fff; font-size: .66rem; font-weight: 700; backdrop-filter: blur(6px); }
    .ai-status i { width: 7px; height: 7px; border-radius: 50%; background: #fff; animation: aiDot 1.8s ease-out infinite; }
    .ai-stage { display: flex; align-items: center; gap: 12px; margin: 14px 0 18px; min-height: 96px; }
    .ai-robot { width: 96px; height: 96px; flex-shrink: 0; padding: 10px 8px 6px; border-radius: 28px; background: linear-gradient(160deg, #ffffff, #fff1df); border: 1px solid rgba(255,255,255,.7); display: flex; align-items: center; justify-content: center; box-shadow: inset 0 1px 0 #fff, 0 12px 26px rgba(160,60,0,.3); transition: transform .25s cubic-bezier(.34,1.56,.64,1); }
    .menu-card-ai:hover .ai-robot { transform: scale(1.07) rotate(-2deg); }
    .ai-bubble { position: relative; background: #fff; color: var(--text); border-radius: 16px 16px 16px 4px; padding: 10px 14px; font-size: .76rem; font-weight: 600; line-height: 1.4; box-shadow: 0 6px 18px rgba(160,60,0,.2); min-width: 0; }
    .ai-bubble::before { content: ''; position: absolute; left: -6px; bottom: 10px; width: 12px; height: 12px; background: #fff; transform: rotate(45deg); border-radius: 2px; }
    .ai-bubble span { position: relative; display: block; transition: opacity .25s ease, transform .25s ease; }
    .ai-bubble span.swap { opacity: 0; transform: translateY(4px); }
    .menu-card-ai .menu-card-arrow { align-self: flex-start; background: #fff; color: var(--orange); padding: 9px 16px; border-radius: 100px; box-shadow: 0 6px 16px rgba(160,60,0,.22); }
    .menu-card-ai:hover .menu-card-arrow { gap: 10px; }
    @media (max-width: 768px) { .ai-stage { min-height: 0; } }

    /* ─── Robot face CSS ─────────────────────────────────────────── */
    .fa-face { --px:0px; --py:0px; --ex:0px; --ey:0px; --sx:1; --sy:1; --tilt:0deg; display:block; width:100%; height:100%; line-height:0; user-select:none; -webkit-user-select:none; }
    .fa-face[data-global] { cursor:pointer; }
    .fa-svg { display:block; width:100%; height:100%; overflow:visible; }
    .fa-tilt { transform-box:fill-box; transform-origin:50% 100%; transform:rotate(var(--tilt)); transition:transform .25s ease; }
    .fa-head { transform-box:fill-box; transform-origin:50% 100%; }
    .fa-face[data-global] .fa-head { animation:fa-float 3.2s ease-in-out infinite; }
    .fa-orb-glow { transform-box:fill-box; transform-origin:center; animation:fa-pulse 1.8s ease-in-out infinite; }
    .fa-ear-in { transition:fill .2s; }
    .fa-eyes { transform-box:fill-box; transform-origin:center; animation:fa-blink 4.6s infinite; animation-delay:var(--bd,0s); }
    .fa-eye { fill:#FFE9CC; transform-box:fill-box; transform-origin:center; transform:translate(calc(var(--px)+var(--ex)),calc(var(--py)+var(--ey))) scale(var(--sx),var(--sy)); transition:transform .18s ease, opacity .15s ease; }
    .fa-eye-hl { fill:#fff; }
    .fa-arc { fill:none; stroke:#FFE9CC; stroke-width:5; stroke-linecap:round; opacity:0; transition:opacity .15s; }
    .fa-brow { fill:none; stroke:#FFB067; stroke-width:4; stroke-linecap:round; opacity:0; transition:opacity .15s; }
    .fa-mouth { opacity:0; transition:opacity .12s; fill:none; stroke:#FFB067; stroke-width:4.5; stroke-linecap:round; stroke-linejoin:round; }
    .fa-mouth--happy  { fill:#FF9B4A; stroke:#FF9B4A; stroke-width:3; }
    .fa-mouth--listen { fill:#2A1810; stroke-width:4; }
    .fa-cheek { opacity:0; transition:opacity .25s ease; }
    .fa-tear  { opacity:0; }
    .fa-face[data-exp="smile"]  .fa-mouth--smile  { opacity:1; }
    .fa-face[data-exp="happy"]  .fa-mouth--happy  { opacity:1; }
    .fa-face[data-exp="talk"]   .fa-mouth--happy  { opacity:1; }
    .fa-face[data-exp="think"]  .fa-mouth--think  { opacity:1; }
    .fa-face[data-exp="listen"] .fa-mouth--listen { opacity:1; }
    .fa-face[data-exp="sad"]    .fa-mouth--sad    { opacity:1; }
    .fa-face[data-exp="smile"]  .fa-cheek { opacity:.18; }
    .fa-face[data-exp="talk"]   .fa-cheek { opacity:.25; }
    .fa-face[data-exp="happy"]  .fa-cheek { opacity:.6; }
    .fa-face[data-exp="happy"]  .fa-eye   { opacity:0; }
    .fa-face[data-exp="happy"]  .fa-arc   { opacity:1; }
    .fa-face[data-exp="happy"]  .fa-head  { animation:fa-hop .45s ease-out 2; }
    .fa-face[data-exp="talk"]   .fa-mouth--happy { transform-box:fill-box; transform-origin:50% 0; animation:fa-talk .26s ease-in-out infinite alternate; }
    .fa-face[data-exp="think"]  { --ex:3px; --ey:-4px; }
    .fa-face[data-exp="think"]  .fa-head      { animation:fa-sway 1.6s ease-in-out infinite alternate; }
    .fa-face[data-exp="think"]  .fa-orb-glow  { animation-duration:.9s; }
    .fa-face[data-exp="listen"] { --sx:1.12; --sy:1.2; }
    .fa-face[data-exp="listen"] .fa-orb-glow  { animation-duration:.8s; }
    .fa-face[data-exp="listen"] .fa-ear-in    { animation:fa-ear .55s ease-in-out infinite alternate; }
    .fa-face[data-exp="sad"]    { --sy:.86; --ey:3px; }
    .fa-face[data-exp="sad"]    .fa-brow { opacity:1; }
    .fa-face[data-exp="sad"]    .fa-tear { animation:fa-drip 1.6s ease-in infinite; }
    @keyframes fa-float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-3px)} }
    @keyframes fa-pulse { 0%,100%{opacity:.22;transform:scale(.9)} 50%{opacity:.55;transform:scale(1.18)} }
    @keyframes fa-blink { 0%,92%,100%{transform:scaleY(1)} 95%{transform:scaleY(.08)} }
    @keyframes fa-hop   { 0%,100%{transform:translateY(0)} 40%{transform:translateY(-9px) scale(1.04)} }
    @keyframes fa-talk  { from{transform:scaleY(.3)} to{transform:scaleY(1)} }
    @keyframes fa-sway  { from{transform:rotate(-3deg)} to{transform:rotate(3deg)} }
    @keyframes fa-ear   { from{fill:#F26A0F} to{fill:#FFD27A} }
    @keyframes fa-drip  { 0%{transform:translateY(0);opacity:0} 20%{opacity:1} 100%{transform:translateY(14px);opacity:0} }
    @media (prefers-reduced-motion: reduce) { .fa-face *,.fa-face *::before,.fa-face *::after { animation:none !important; } .menu-card-ai,.ai-status i { animation:none !important; } .menu-card-ai,.menu-card-ai:hover { transform:none; } }
</style>
@endsection

@section('content')

{{-- ── Hero / Welcome Banner ───────────────────────────────────────────── --}}
<section class="hero-banner">
    <div class="hero-orb hero-orb-1"></div>
    <div class="hero-orb hero-orb-2"></div>
    <div class="hero-content">
        <p class="hero-greeting">Selamat Datang</p>
        <h1 class="hero-title">
            Halo, <span>{{ Auth::user()->username }}</span>!<br>
            Dashboard Owner
        </h1>
        <p class="hero-sub">
            Pantau dan kelola semua aspek bisnis Anda dari satu tempat.
            @if($outlet)
                Outlet aktif: <strong>{{ $outlet->nama_outlet ?? 'Outlet Saya' }}</strong>.
            @endif
        </p>
    </div>
</section>

{{-- ── Main Content ────────────────────────────────────────────────────── --}}
<main class="main-wrap">

    @if($outlet)
    <div class="outlet-bar">
        <div class="outlet-bar-icon"><i class="bi bi-shop"></i></div>
        <div class="outlet-bar-info">
            <div class="label">Outlet Aktif</div>
            <div class="value">{{ $outlet->nama_outlet ?? '-' }}</div>
        </div>
        <span class="outlet-bar-badge badge-owner">Owner</span>
    </div>
    @endif

    <p class="section-label">Menu Utama</p>

    <div class="menu-grid">

        {{-- AI Consultant --}}
        <a href="{{ route('owner.ai') }}" class="menu-card menu-card-ai" id="aiCard">
            <div class="ai-status"><i></i> AKTIF</div>
            <div class="ai-stage">
                <div class="ai-robot">
                    <span class="fa-face" data-global data-fa-hydrate data-exp="smile"></span>
                </div>
                <div class="ai-bubble" aria-hidden="true">
                    <span id="aiBubbleText">Menu apa yang paling laris minggu ini?</span>
                </div>
            </div>
            <div class="menu-card-title">Flavory AI Consultant</div>
            <div class="menu-card-desc">Konsultan bisnis berbasis AI. Tanya apa saja soal performa outlet — dijawab berdasarkan data nyata Anda.</div>
            <div class="menu-card-arrow">Mulai Chat AI <i class="bi bi-arrow-right"></i></div>
        </a>

        {{-- Ikhtisar Bisnis --}}
        <a href="{{ route('owner.laporan.index') }}" class="menu-card">
            <div class="menu-card-icon icon-orange"><i class="bi bi-bar-chart-line-fill"></i></div>
            <div class="menu-card-title">Ikhtisar Bisnis</div>
            <div class="menu-card-desc">Lihat ringkasan omzet, total order, keuntungan harian, serta analisis menu terlaris dan terendah.</div>
            <div class="menu-card-arrow">Buka Ikhtisar <i class="bi bi-arrow-right"></i></div>
        </a>

        {{-- Laporan Pengeluaran --}}
        <a href="{{ route('owner.pengeluaran.index') }}" class="menu-card">
            <div class="menu-card-icon icon-red"><i class="bi bi-receipt-cutoff"></i></div>
            <div class="menu-card-title">Laporan Pengeluaran</div>
            <div class="menu-card-desc">Catat dan pantau semua pengeluaran operasional bisnis. Tersedia fitur export ke Excel.</div>
            <div class="menu-card-arrow">Buka Laporan <i class="bi bi-arrow-right"></i></div>
        </a>

        {{-- Laporan Modal --}}
        <a href="{{ route('owner.modal.index') }}" class="menu-card">
            <div class="menu-card-icon icon-green"><i class="bi bi-wallet2"></i></div>
            <div class="menu-card-title">Laporan Modal Usaha</div>
            <div class="menu-card-desc">Kelola setoran modal dan pantau riwayat perputaran modal usaha secara lengkap.</div>
            <div class="menu-card-arrow">Buka Modal <i class="bi bi-arrow-right"></i></div>
        </a>

        {{-- Performa Bisnis --}}
        <a href="{{ route('owner.performa') }}" class="menu-card">
            <div class="menu-card-icon icon-blue"><i class="bi bi-graph-up-arrow"></i></div>
            <div class="menu-card-title">Performa Bisnis</div>
            <div class="menu-card-desc">Analisis tren performa bisnis berdasarkan periode waktu, kategori menu, dan jam sibuk.</div>
            <div class="menu-card-arrow">Buka Performa <i class="bi bi-arrow-right"></i></div>
        </a>

        {{-- Pengaturan Form Pesanan --}}
        <a href="{{ route('owner.pengaturan.index') }}" class="menu-card">
            <div class="menu-card-icon icon-purple"><i class="bi bi-sliders"></i></div>
            <div class="menu-card-title">Pengaturan Form Pesanan</div>
            <div class="menu-card-desc">Konfigurasi tampilan & kolom form pesanan online: tipe layanan, tabel, catatan, dan lainnya.</div>
            <div class="menu-card-arrow">Buka Pengaturan <i class="bi bi-arrow-right"></i></div>
        </a>

    </div>
</main>

@endsection

@section('scripts')
<script>
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Hydrate robot faces lalu aktifkan tracking kursor
    hydrateFaces(document);

    // ── Interaksi card AI ──────────────────────────────────────────
    const aiCard = document.getElementById('aiCard');
    if (aiCard) {
        const face = aiCard.querySelector('.fa-face');
        const setExp = v => { if (face) face.dataset.exp = v; };
        let hovering = false;

        aiCard.addEventListener('pointerenter',  e => { hovering = true; if (e.pointerType === 'mouse') setExp('happy'); });
        aiCard.addEventListener('pointerleave',  () => { hovering = false; setExp('smile'); aiCard.style.setProperty('--rx','0deg'); aiCard.style.setProperty('--ry','0deg'); });
        aiCard.addEventListener('pointerdown',   () => setExp('listen'));
        aiCard.addEventListener('pointerup',     e => setExp(hovering && e.pointerType === 'mouse' ? 'happy' : 'smile'));
        aiCard.addEventListener('pointercancel', () => setExp('smile'));

        aiCard.addEventListener('pointermove', e => {
            if (e.pointerType !== 'mouse') return;
            const r = aiCard.getBoundingClientRect();
            const x = e.clientX - r.left, y = e.clientY - r.top;
            aiCard.style.setProperty('--mx', x + 'px');
            aiCard.style.setProperty('--my', y + 'px');
            if (!reduceMotion) {
                aiCard.style.setProperty('--rx', ((0.5 - y/r.height) * 7).toFixed(2) + 'deg');
                aiCard.style.setProperty('--ry', ((x/r.width - 0.5) * 7).toFixed(2) + 'deg');
            }
        });

        // Gelembung contoh pertanyaan berganti-ganti
        const bubble = document.getElementById('aiBubbleText');
        const tips = [
            'Menu apa yang paling laris minggu ini?',
            'Kenapa omzet kemarin turun?',
            'Jam berapa outlet paling ramai?',
            'Menu mana yang margin untungnya paling tipis?',
        ];
        let tipIdx = 0;
        if (!reduceMotion) {
            setInterval(() => {
                bubble.classList.add('swap');
                setTimeout(() => {
                    tipIdx = (tipIdx + 1) % tips.length;
                    bubble.textContent = tips[tipIdx];
                    bubble.classList.remove('swap');
                }, 250);
            }, 3800);
        }
    }
</script>
@endsection
