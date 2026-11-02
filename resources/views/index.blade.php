{{-- resources/views/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Aplikasi Kasir All-in-One Terbaik & Manajemen Bisnis Restoran | Flavory.id')
@section('meta_description', 'Tingkatkan keuntungan dengan Flavory.id, aplikasi kasir all-in-one dan manajemen bisnis restoran terbaik. Dilengkapi asisten AI, analisa performa bisnis, dan POS kuliner lengkap.')
@section('meta_og_type', 'website')
@section('meta_og_url', url('/'))
@section('meta_image', secure_url('assets/preview.jpg'))

@push('styles')
<style>
    /* Variabel Warna Utama */
    :root {
        --primary-orange: #ff6a00;
        --light-orange: #fff4ec;
        --text-muted: #64748b;
    }

    /* Animasi Dasar */
    .animate-slide-up {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.8s ease-out, transform 0.8s ease-out;
    }
    .animate-fade-in {
        opacity: 0;
        transition: opacity 1s ease-out;
    }
    .in-view {
        opacity: 1;
        transform: translateY(0);
    }

    /* ══ HERO SECTION - Light Premium 3D ══ */
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

    /* Badge pill */
    .hero-badge-wrap {
        opacity: 0;
        filter: blur(6px);
        transform: translateY(12px);
        transition: opacity .6s ease, filter .6s ease, transform .6s ease;
        will-change: opacity, filter, transform;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 16px;
        background: rgba(255,106,0,.07);
        border: 1.5px solid rgba(255,106,0,.2);
        border-radius: 100px;
        margin-bottom: 20px;
    }
    .hero-badge-wrap.visible { opacity: 1; filter: blur(0); transform: translateY(0); }
    .hero-badge-wrap.hidden  {
        opacity: 0; filter: blur(4px); transform: translateY(-8px);
        transition: opacity .3s, filter .3s, transform .3s;
    }
    .hero-badge-dot {
        width: 7px; height: 7px;
        background: var(--primary-orange);
        border-radius: 50%;
        flex-shrink: 0;
        animation: dotPulse 2.5s ease-in-out infinite;
    }
    @keyframes dotPulse {
        0%,100% { box-shadow: 0 0 0 0 rgba(255,106,0,.4); }
        50%      { box-shadow: 0 0 0 5px rgba(255,106,0,0); }
    }
    .hero-badge-text { font-size: .72rem; font-weight: 800; letter-spacing: .4px; color: var(--primary-orange); }

    /* Title - proporsional */
    .hero-title {
        font-size: clamp(1.9rem, 3.5vw, 2.8rem);
        line-height: 1.2;
        font-weight: 900;
        letter-spacing: -1px;
        color: #1e293b;
        margin-bottom: 18px;
    }
    .hero-title .w,
    .hero-subtitle .w,
    .hero-cta .w {
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
        color: #fff;
        border: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(255,106,0,.28);
    }
    .btn-orange:hover {
        background: linear-gradient(135deg, #e65f00, var(--primary-orange));
        transform: translateY(-2px);
        box-shadow: 0 8px 28px rgba(255,106,0,.35);
        color: #fff;
    }
    .btn-outline-orange {
        border: 1.5px solid rgba(255,106,0,.4);
        color: var(--primary-orange);
        background: rgba(255,106,0,.04);
        transition: all 0.3s ease;
    }
    .btn-outline-orange:hover {
        background: rgba(255,106,0,.1);
        border-color: var(--primary-orange);
        transform: translateY(-2px);
        color: var(--primary-orange);
    }

    /* ══ 3D Visual Column ══ */
    .hero-visual-col { position: relative; z-index: 2; }
    .hero-3d-wrapper {
        animation: heroCardFloat 8s ease-in-out infinite;
    }
    @keyframes heroCardFloat {
        0%,100% { transform: translateY(0) perspective(1200px) rotateY(-4deg) rotateX(2deg); }
        50%      { transform: translateY(-12px) perspective(1200px) rotateY(-2deg) rotateX(1deg); }
    }

    /* Baris cards di atas gambar */
    .hero-cards-top {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
        justify-content: flex-start;
    }

    /* Baris cards di bawah gambar */
    .hero-cards-bottom {
        display: flex;
        gap: 10px;
        margin-top: 10px;
        justify-content: flex-end;
        align-items: center;
    }

    /* Gambar/GIF - penuh, tidak ada overlay */
    .hero-main-card {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        background: #fff;
        border: 1px solid rgba(0,0,0,.07);
        box-shadow: 0 24px 64px rgba(0,0,0,.1), 0 6px 20px rgba(255,106,0,.07);
    }
    .hero-main-card img { width: 100%; display: block; }
    .hero-card-glow {
        position: absolute;
        bottom: -28px; left: 50%;
        transform: translateX(-50%);
        width: 65%; height: 50px;
        background: rgba(255,106,0,.15);
        filter: blur(28px);
        border-radius: 50%;
        pointer-events: none;
        z-index: -1;
    }

    /* Float cards */
    .hero-float-card {
        background: rgba(255,255,255,.97);
        border: 1px solid rgba(255,106,0,.12);
        border-radius: 14px;
        padding: 12px 16px;
        backdrop-filter: blur(16px);
        box-shadow: 0 8px 24px rgba(0,0,0,.07);
        flex-shrink: 0;
    }
    .hero-float-card-icon {
        width: 30px; height: 30px;
        background: rgba(255,106,0,.08);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: .8rem; color: var(--primary-orange);
        margin-bottom: 6px;
    }
    .hero-float-card-label { font-size: .58rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #94a3b8; margin-bottom: 2px; white-space: nowrap; }
    .hero-float-card-value { font-size: .95rem; font-weight: 900; color: #1e293b; line-height: 1; margin-bottom: 2px; white-space: nowrap; }
    .hero-float-card-trend { font-size: .6rem; color: #22c55e; font-weight: 600; white-space: nowrap; }
    .hero-float-card-sub   { font-size: .6rem; color: #64748b; white-space: nowrap; }

    .fcard-revenue { animation: fcardFloat 7s ease-in-out infinite; }
    .fcard-ai      { animation: fcardFloat 9s ease-in-out infinite 1.2s; }
    .fcard-orders  { animation: fcardFloat 8s ease-in-out infinite .6s; }
    .fcard-stok    { animation: fcardFloat 7.5s ease-in-out infinite .4s; }
    @keyframes fcardFloat {
        0%,100% { transform: translateY(0); }
        50%      { transform: translateY(-7px); }
    }

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

    /* Features Box */
    .feature-icon-box {
        width: 60px; height: 60px;
        background-color: var(--light-orange);
        color: var(--primary-orange);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; margin-bottom: 20px;
        transition: transform 0.3s ease;
    }
    .feature-card {
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 20px;
        padding: 30px;
        transition: all 0.3s ease;
        height: 100%;
        background: white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    }
    .feature-card:hover { 
        transform: translateY(-10px); 
        box-shadow: 0 15px 40px rgba(255, 106, 0, 0.08); 
    }
    .feature-card:hover .feature-icon-box {
        transform: scale(1.1);
    }

    /* Highlight Fitur AI & Prediksi */
    .feature-highlight-card {
        background: #fff;
        border-left: 4px solid var(--primary-orange);
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        transition: all 0.3s ease;
    }
    .feature-highlight-card:hover {
        box-shadow: 0 8px 25px rgba(255, 106, 0, 0.1);
        transform: translateX(5px);
    }

    /* Pricing Box */
    .pricing-card {
        border-radius: 24px;
        border: 2px solid #f1f5f9;
        padding: 40px 30px;
        background: white;
        transition: all 0.3s;
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .pricing-card.popular {
        border-color: var(--primary-orange);
        box-shadow: 0 20px 40px rgba(255, 106, 0, 0.15);
        transform: scale(1.05);
        z-index: 10;
        background: linear-gradient(to bottom, #ffffff, #fffdfa);
    }
    .badge-popular {
        position: absolute; top: -15px; left: 50%; transform: translateX(-50%);
        background: var(--primary-orange); color: white;
        padding: 6px 24px; border-radius: 20px; font-weight: 700; font-size: 0.85rem;
        box-shadow: 0 4px 10px rgba(255, 106, 0, 0.3);
        letter-spacing: 0.5px;
    }
    .price-strike { text-decoration: line-through; color: #94a3b8; font-size: 1.1rem; font-weight: 500; margin-right: 8px; }
    .promo-tag { background: #fee2e2; color: #ef4444; font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 6px; }

    /* Check List */
    .check-list { list-style: none; padding-left: 0; flex-grow: 1; }
    .check-list li { margin-bottom: 12px; display: flex; align-items: flex-start; }
    .check-list i { color: var(--primary-orange); margin-right: 12px; font-size: 1.2rem; margin-top: -2px; }

    /* Bonus Banner */
    .bonus-banner {
        background: linear-gradient(45deg, var(--primary-orange), #ff8c33);
        border-radius: 24px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    @media (max-width: 991px) {
        .hero-title { font-size: 2.5rem; }
        .pricing-card.popular { transform: scale(1); margin-top: 2rem; margin-bottom: 2rem; }
    }

    /* Blog Section */
    .blog-card {
        border: 1px solid rgba(0,0,0,0.06);
        border-radius: 20px;
        overflow: hidden;
        background: #fff;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }
    .blog-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 40px rgba(255, 106, 0, 0.1);
        border-color: rgba(255, 106, 0, 0.2);
    }
    .blog-card-img {
        aspect-ratio: 16/9;
        object-fit: cover;
        width: 100%;
        background: #f8f9fa;
    }
    .blog-card-body {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .blog-category-badge {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        background: var(--light-orange);
        color: var(--primary-orange);
        padding: 4px 10px;
        border-radius: 6px;
        display: inline-block;
        margin-bottom: 10px;
    }
    .blog-card-title {
        font-size: 0.97rem;
        font-weight: 700;
        line-height: 1.45;
        color: #1a202c;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .blog-card-excerpt {
        font-size: 0.82rem;
        color: #64748b;
        line-height: 1.6;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 16px;
    }
    .blog-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px solid #f1f5f9;
        padding-top: 12px;
        margin-top: auto;
    }
    .blog-read-link {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--primary-orange);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: gap 0.2s ease;
    }
    .blog-read-link:hover { gap: 8px; color: #e65f00; }
    .blog-date {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    /* ===== MINEAI SPOTLIGHT SECTION ===== */
    @keyframes mineShift {
        0%   { background-position: 0% 50%; }
        50%  { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    @keyframes mineOrbFloat {
        0%,100% { transform: translate(0,0) scale(1); }
        33%     { transform: translate(30px,-25px) scale(1.06); }
        66%     { transform: translate(-20px,18px) scale(.95); }
    }
    @keyframes mineScreenFloat {
        0%,100% { transform: translateY(0) rotate(-1.5deg); }
        50%     { transform: translateY(-12px) rotate(-1.5deg); }
    }

    .mineai-section {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #fff4ec 0%, #fdf0f8 40%, #fff8f0 70%, #fdeef8 100%);
        background-size: 400% 400%;
        animation: mineShift 10s ease infinite;
        padding: 80px 0;
        border-top: 3px solid transparent;
        border-image: linear-gradient(135deg, #ff6a00, #ee0979) 1;
    }

    /* Orbs */
    .mineai-orb {
        position: absolute; border-radius: 50%;
        filter: blur(80px); pointer-events: none; z-index: 1;
    }
    .mineai-orb1 {
        width: 600px; height: 600px;
        background: radial-gradient(circle, rgba(255,106,0,.18), transparent 65%);
        top: -20%; left: -10%;
        animation: mineOrbFloat 11s ease-in-out infinite;
    }
    .mineai-orb2 {
        width: 450px; height: 450px;
        background: radial-gradient(circle, rgba(238,9,121,.14), transparent 65%);
        bottom: -15%; right: -8%;
        animation: mineOrbFloat 13s ease-in-out infinite reverse;
    }
    .mineai-orb3 {
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(255,154,0,.12), transparent 65%);
        top: 40%; left: 35%;
        animation: mineOrbFloat 8s ease-in-out infinite 2s;
    }

    /* Logo badge */
    .mineai-logo-badge {
        width: 52px; height: 52px;
        border-radius: 14px;
        object-fit: cover;
        box-shadow: 0 8px 24px rgba(255,106,0,.4);
    }

    /* Tag */
    .mineai-tag {
        display: inline-block;
        font-size: .68rem; font-weight: 800; letter-spacing: 1.5px;
        text-transform: uppercase;
        background: linear-gradient(135deg, #ff6a00, #ee0979);
        background-size: 200% 200%;
        animation: mineShift 3s ease infinite;
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 2px; display: block;
    }
    .mineai-name-text {
        font-size: 1.6rem; font-weight: 900; color: var(--text-dark); line-height: 1;
    }
    .mineai-name-text span {
        background: linear-gradient(135deg, #ff6a00, #ee0979);
        background-size: 200% 200%;
        animation: mineShift 3s ease infinite;
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Headline */
    .mineai-headline {
        font-size: clamp(1.8rem, 3.5vw, 2.6rem);
        font-weight: 900;
        color: #1e1e2e;
        line-height: 1.2;
        margin-bottom: 1.2rem;
    }
    .mineai-grad-text {
        background: linear-gradient(135deg, #ff6a00, #ee0979, #ff9a00);
        background-size: 300% 300%;
        animation: mineShift 4s ease infinite;
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Desc */
    .mineai-desc {
        color: #475569;
        font-size: .93rem;
        line-height: 1.75;
        margin-bottom: 1.5rem;
    }

    /* Feature list */
    .mineai-features li {
        color: #334155;
        font-size: .88rem;
        margin-bottom: .6rem;
        display: flex; align-items: center; gap: 10px;
    }
    .mineai-features li i {
        background: linear-gradient(135deg, #ff6a00, #ee0979);
        background-size: 200%; animation: mineShift 3s ease infinite;
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        background-clip: text;
        font-size: 1rem; flex-shrink: 0;
    }

    /* CTA buttons */
    .btn-mineai-cta {
        display: inline-flex; align-items: center;
        padding: 13px 28px; border-radius: 50px; border: none;
        font-weight: 800; font-size: .9rem; color: white;
        text-decoration: none; cursor: pointer;
        background: linear-gradient(135deg, #ff6a00, #ee0979, #ff9a00, #ff6a00);
        background-size: 300% 300%;
        animation: mineShift 3s ease infinite;
        box-shadow: 0 8px 28px rgba(255,106,0,.4);
        transition: transform .25s, box-shadow .25s;
    }
    .btn-mineai-cta:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 36px rgba(238,9,121,.5);
        color: white;
    }
    .btn-mineai-ghost {
        display: inline-flex; align-items: center;
        padding: 13px 24px; border-radius: 50px;
        border: 1.5px solid rgba(255,106,0,.4);
        font-weight: 700; font-size: .88rem;
        color: #ff6a00;
        text-decoration: none;
        background: rgba(255,106,0,.06);
        transition: all .25s;
    }
    .btn-mineai-ghost:hover {
        border-color: #ee0979;
        background: rgba(238,9,121,.08);
        color: #ee0979;
    }

    /* Screenshot */
    .mineai-screen-wrap {
        position: relative;
        padding: 4px;
        border-radius: 24px;
    }
    .mineai-screen-glow {
        position: absolute; inset: -20px;
        background: radial-gradient(ellipse at center, rgba(238,9,121,.25) 0%, transparent 70%);
        border-radius: 40px;
        pointer-events: none;
        animation: mineOrbFloat 7s ease-in-out infinite;
    }
    .mineai-screenshot {
        width: 100%;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(255,106,0,.2), 0 4px 20px rgba(238,9,121,.1), 0 0 0 1px rgba(255,106,0,.15);
        display: block;
        animation: mineScreenFloat 6s ease-in-out infinite;
    }

    @media (max-width: 991px) {
        .mineai-section { padding: 60px 0; }
        .mineai-headline { font-size: 1.9rem; }
        .mineai-screenshot { animation: none; }
    }
</style>
@endpush

@section('content')
    <main>
        <header id="home" class="hero-section">
            {{-- Soft orbs --}}
            <div class="hero-orb hero-orb-1"></div>
            <div class="hero-orb hero-orb-2"></div>

            <div class="container">
                <div class="row align-items-center g-5">

                    {{-- ═══ LEFT: Content ═══ --}}
                    <div class="col-lg-6 order-2 order-lg-1 hero-content-col hero-reveal-col">

                        <div class="hero-badge-wrap">
                            <div class="hero-badge-dot"></div>
                            <span class="hero-badge-text">APLIKASI POS AI TERBAIK DI INDONESIA</span>
                        </div>

                        <h1 class="hero-title">
                            <span class="w">Bukan</span> <span class="w">Sekadar</span> <span class="w">POS</span> <span class="w">Biasa.</span>
                            <span id="hero-rotating-line" class="d-block"></span>
                        </h1>

                        <p class="hero-subtitle">
                            <strong>Aplikasi kasir all-in-one</strong> untuk mencatat pesanan dan mengelola operasional dengan mudah, yang kini dilengkapi asisten kecerdasan buatan (AI) untuk memberikan saran strategi bisnis berdasarkan data penjualan Anda.
                        </p>

                        <div class="hero-trust-row">
                            <div class="hero-trust-chip">
                                <i class="bi bi-wifi-off"></i>
                                <span>Works Offline</span>
                            </div>
                            <div class="hero-trust-chip">
                                <i class="bi bi-robot"></i>
                                <span>AI Powered</span>
                            </div>
                            <div class="hero-trust-chip">
                                <i class="bi bi-shield-check-fill"></i>
                                <span>Aman & Terpercaya</span>
                            </div>
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

                    {{-- ═══ RIGHT: 3D Visual ═══ --}}
                    <div class="col-lg-6 order-1 order-lg-2 hero-visual-col">
                        <div class="hero-3d-wrapper">

                            {{-- Baris atas: cards di ATAS gambar --}}
                            <div class="hero-cards-top">
                                <div class="hero-float-card fcard-revenue">
                                    <div class="hero-float-card-icon"><i class="bi bi-graph-up-arrow"></i></div>
                                    <div class="hero-float-card-label">Omzet Hari Ini</div>
                                    <div class="hero-float-card-value">Rp 12,8 Jt</div>
                                    <div class="hero-float-card-trend"><i class="bi bi-arrow-up"></i> +32%</div>
                                </div>
                                <div class="hero-float-card fcard-ai">
                                    <div class="hero-float-card-icon">
                                        {{-- Logo M MineAI --}}
                                        <svg width="18" height="18" viewBox="0 0 120 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <defs>
                                                <linearGradient id="hero_mineai_grad" x1="0%" y1="0%" x2="100%" y2="100%">
                                                    <stop offset="0%" style="stop-color:#ff6a00;" />
                                                    <stop offset="100%" style="stop-color:#ee0979;" />
                                                </linearGradient>
                                            </defs>
                                            <path d="M 20,85 L 20,25 L 60,65 L 100,25 L 100,85"
                                                  stroke="url(#hero_mineai_grad)"
                                                  stroke-width="26"
                                                  stroke-linecap="round"
                                                  stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <div class="hero-float-card-label">MineAI</div>
                                    <div class="hero-float-card-value">95%</div>
                                    <div class="hero-float-card-sub">Akurasi prediksi</div>
                                </div>
                            </div>

                            {{-- Gambar/GIF - BERSIH, tidak ada overlay apapun --}}
                            <div class="hero-main-card">
                                <img src="{{asset('assets/hero.png')}}"
                                     onerror="this.src='https://placehold.co/700x470/fff4ec/ff6a00?text=Dashboard+Flavory.id'"
                                     alt="Tampilan Dashboard Manajemen Bisnis Restoran Flavory.id">
                                <div class="hero-card-glow"></div>
                            </div>

                            {{-- Baris bawah: cards di BAWAH gambar --}}
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

        <section class="py-4 border-bottom bg-white animate-slide-up">
            <div class="container text-center">
                <p class="mb-0 fw-bold text-muted" style="letter-spacing: 1px; text-transform: uppercase; font-size: 0.9rem;">
                    <i class="bi bi-shield-check text-success me-2"></i> Dipercaya Ratusan Pemilik Usaha Kuliner di Seluruh Indonesia
                </p>
            </div>
        </section>

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
                            <h3 class="h5 fw-bold">Ditenagai MineAI</h3>
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

        <section id="fitur" class="py-5" style="background-color: #f8fafc;">
            <div class="container py-5">
                <div class="text-center mb-5 animate-slide-up">
                    <span class="text-orange fw-bold tracking-wider text-uppercase small"><i class="bi bi-stars"></i> Fitur Andalan Flavory.id</span>
                    <h2 class="fw-bold mt-2">Semua yang Anda Butuhkan dalam Satu Aplikasi</h2>
                    <div class="mx-auto mt-3 mb-4" style="width: 80px; height: 4px; background-color: var(--primary-orange); border-radius: 2px;"></div>
                </div>

                    <div class="col-lg-6 ps-lg-5 animate-fade-in">
                        <img src="{{asset('assets/analisis.png')}}" onerror="this.src='https://placehold.co/620x420/fff4ec/ff6a00?text=Laporan+%26+Analisa'" class="img-fluid rounded-4 shadow-lg border" alt="Fitur Analisa Performa Bisnis dan Prediksi Flavory.id">
                    </div>
                </div>

                <div class="row align-items-center mb-5 pb-5 border-bottom">
                    <div class="col-lg-6 order-2 order-lg-1 pe-lg-5 animate-fade-in">
                        <img src="{{asset('assets/order.png')}}" onerror="this.src='https://placehold.co/620x420/fff4ec/ff6a00?text=Sistem+Kasir+%26+Order'" class="img-fluid rounded-4 shadow-lg border" alt="Aplikasi Kasir Kuliner POS Flavory.id">
                    </div>
                    <div class="col-lg-6 order-1 order-lg-2 mb-4 mb-lg-0 animate-slide-up">
                        <h3 class="fw-bold mb-3"><i class="bi bi-shop-window text-orange me-2"></i> Sistem Kasir & Manajemen Pesanan</h3>
                        <p class="text-muted mb-4">Transaksi secepat kilat, baik untuk makan di tempat (dine-in), bungkus (take-away), atau order online secara mandiri melalui QR code.</p>
                        
                        <ul class="check-list mt-3">
                            <li>
                                <i class="bi bi-check-circle-fill"></i> 
                                <div><strong>Pencatatan Pesanan Fleksibel</strong><br><span class="small text-muted">Mendukung input varian menu, level pedas/topping (harga dinamis), hingga split bill dengan sangat mudah.</span></div>
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill"></i> 
                                <div><strong>Database Pelanggan & Struk Digital</strong><br><span class="small text-muted">Kirim struk langsung ke WhatsApp pelanggan beserta catatan kustom (Cth: Password WiFi/Promo).</span></div>
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill"></i> 
                                <div><strong>Multi-Printer Support</strong><br><span class="small text-muted">Cetak struk fisik (via printer bluethoot maupun USB), dan struk digital via QR code maupun tautan whatsapp, serta mendukung struk dapur.</span></div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0 animate-slide-up">
                        <h3 class="fw-bold mb-3"><i class="bi bi-boxes text-orange me-2"></i> Kendali Finansial & Stok Dapur</h3>
                        <p class="text-muted mb-4">Cegah kebocoran dana dan kehabisan bahan baku secara mendadak. Semua aktivitas stok dan keuangan outlet tercatat rapi.</p>
                        
                        <ul class="check-list">
                            <li>
                                <i class="bi bi-check-circle-fill"></i> 
                                <div><strong>Manajemen Stok Akurat</strong><br><span class="small text-muted">Kontrol penuh atas ketersediaan barang. Indikator pintar akan memberi warna merah saat bahan baku menipis atau habis.</span></div>
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill"></i> 
                                <div><strong>Laporan Arus Kas Otomatis</strong><br><span class="small text-muted">Sistem merekap pemasukan dan pengeluaran, serta menghitung keuntungan bersih harian langsung di dalam aplikasi.</span></div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-6 ps-lg-5 animate-fade-in">
                        <img src="{{asset('assets/stok.png')}}" onerror="this.src='https://placehold.co/620x420/fff4ec/ff6a00?text=Manajemen+Stok'" class="img-fluid rounded-4 shadow-lg border" alt="Fitur Manajemen Stok Barang Restoran Flavory.id">
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== SECTION HRMS (KEY.FLAVORY.ID) ===== --}}
        <section id="hrms" class="py-5 bg-white overflow-hidden">
            @push('styles')
            <style>
            /* ── HRMS Section ── */
            .hrms-badge {
                display:inline-flex; align-items:center; gap:6px;
                padding:5px 14px; border-radius:50px;
                background:linear-gradient(135deg,rgba(25,135,84,.08),rgba(20,108,67,.05));
                border:1px solid rgba(25,135,84,.2);
                font-size:.72rem; font-weight:800; letter-spacing:.8px;
                color:#198754; text-transform:uppercase; margin-bottom:16px;
            }
            .hrms-headline { font-size:clamp(1.7rem,3vw,2.4rem); font-weight:900; color:#1e293b; line-height:1.2; }
            .hrms-headline .hl { color:#198754; }
            .hrms-desc { font-size:.95rem; color:#64748b; line-height:1.75; }
            .hrms-link-btn {
                display:inline-flex; align-items:center; gap:8px;
                padding:12px 28px; border-radius:50px; border:none; cursor:pointer;
                font-weight:800; font-size:.9rem; color:#fff; text-decoration:none;
                background:linear-gradient(135deg,#198754,#20c997);
                box-shadow:0 8px 24px rgba(25,135,84,.3);
                transition:transform .25s, box-shadow .25s;
            }
            .hrms-link-btn:hover { transform:translateY(-2px); box-shadow:0 12px 32px rgba(25,135,84,.4); color:#fff; }
            .hrms-link-ghost {
                display:inline-flex; align-items:center; gap:8px;
                padding:12px 24px; border-radius:50px;
                border:1.5px solid rgba(25,135,84,.4); font-weight:700; font-size:.88rem;
                color:#198754; text-decoration:none; background:rgba(25,135,84,.05);
                transition:all .25s;
            }
            .hrms-link-ghost:hover { border-color:#198754; background:rgba(25,135,84,.12); color:#198754; }

            /* ── Carousel strip ── */
            .hrms-carousel-outer {
                overflow:hidden; position:relative;
                mask-image:linear-gradient(to right, transparent 0%, black 8%, black 92%, transparent 100%);
                -webkit-mask-image:linear-gradient(to right, transparent 0%, black 8%, black 92%, transparent 100%);
            }
            .hrms-carousel-track {
                display:flex; gap:16px; width:max-content;
                animation:hrmsScroll 28s linear infinite;
            }
            .hrms-carousel-track:hover { animation-play-state:paused; }
            @keyframes hrmsScroll {
                from { transform:translateX(0); }
                to   { transform:translateX(-50%); }
            }
            .hrms-card {
                flex-shrink:0; width:230px;
                background:#fff; border:1.5px solid rgba(25,135,84,.15);
                border-radius:18px; padding:22px 18px;
                box-shadow:0 4px 16px rgba(25,135,84,.07);
                transition:box-shadow .2s, border-color .2s;
                cursor:default;
            }
            .hrms-card:hover { border-color:rgba(25,135,84,.4); box-shadow:0 8px 28px rgba(25,135,84,.14); }
            .hrms-card-icon {
                width:42px; height:42px; border-radius:12px;
                background:linear-gradient(135deg,rgba(25,135,84,.1),rgba(32,201,151,.08));
                display:flex; align-items:center; justify-content:center;
                font-size:1.2rem; color:#198754; margin-bottom:12px;
            }
            .hrms-card h4 { font-size:.88rem; font-weight:800; color:#1e293b; margin-bottom:5px; }
            .hrms-card p  { font-size:.75rem; color:#64748b; margin:0; line-height:1.5; }
            </style>
            @endpush

            <div class="container py-4">
                <div class="row align-items-center g-5">

                    {{-- Teks kiri --}}
                    <div class="col-lg-5 animate-slide-up">
                        <div class="hrms-badge">
                            <i class="bi bi-people-fill"></i> Fitur HRMS Eksklusif
                        </div>
                        <h2 class="hrms-headline mb-3">
                            Kelola Tim Karyawan<br>dengan <span class="hl">Key.Flavory.id</span>
                        </h2>
                        <p class="hrms-desc mb-3">
                            Flavory.id kini hadir dengan modul <strong>Human Resource Management System (HRMS)</strong> melalui platform <strong>Key.Flavory.id</strong> - solusi lengkap bagi owner & manager untuk mengelola tim, shift, absensi, dan performa outlet dari satu dasbor terpusat.
                        </p>
                        <p class="hrms-desc mb-4">
                            Tidak perlu aplikasi HR terpisah. Semua data karyawan, jadwal kerja, rekap absensi, hingga laporan bisnis outlet terintegrasi langsung dengan data kasir Flavory.id secara <em>real-time</em>.
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ rtrim(config('app.key_flavory_url', env('KEYFLAVORYID_URL','http://127.0.0.1:8001')),'/') }}/login"
                               class="hrms-link-btn">
                                <i class="bi bi-person-badge-fill"></i> Buka Dasbor Manager
                            </a>
                            <a href="{{ route('panduan.hrms') }}" class="hrms-link-ghost">
                                Panduan HRMS <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Kanan: stats + tag --}}
                    <div class="col-lg-7 animate-fade-in">
                        <div class="row g-3 mb-4">
                            <div class="col-6 col-md-3">
                                <div class="text-center p-3 rounded-3 border" style="border-color:rgba(25,135,84,.15)!important;background:#f0fdf4;">
                                    <div class="fw-900 text-success" style="font-size:1.6rem;font-weight:900;">∞</div>
                                    <div class="text-muted small fw-600">Karyawan</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-center p-3 rounded-3 border" style="border-color:rgba(25,135,84,.15)!important;background:#f0fdf4;">
                                    <div class="fw-900 text-success" style="font-size:1.6rem;font-weight:900;">5+</div>
                                    <div class="text-muted small fw-600">Modul HRMS</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-center p-3 rounded-3 border" style="border-color:rgba(25,135,84,.15)!important;background:#f0fdf4;">
                                    <div class="fw-900 text-success" style="font-size:1.6rem;font-weight:900;">24/7</div>
                                    <div class="text-muted small fw-600">Akses Online</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-center p-3 rounded-3 border" style="border-color:rgba(25,135,84,.15)!important;background:#f0fdf4;">
                                    <div class="fw-900 text-success" style="font-size:1.6rem;font-weight:900;">Free</div>
                                    <div class="text-muted small fw-600">Termasuk Paket</div>
                                </div>
                            </div>
                        </div>

                        {{-- Carousel card fitur HRMS --}}
                        <div class="hrms-carousel-outer py-2">
                            <div class="hrms-carousel-track" id="hrmsTrack">
                                {{-- 8 card asli --}}
                                <div class="hrms-card">
                                    <div class="hrms-card-icon"><i class="bi bi-person-lines-fill"></i></div>
                                    <h4>Data Karyawan</h4>
                                    <p>Kelola profil, biodata, dan arsip karyawan aktif maupun nonaktif.</p>
                                </div>
                                <div class="hrms-card">
                                    <div class="hrms-card-icon"><i class="bi bi-calendar-week-fill"></i></div>
                                    <h4>Manajemen Shift</h4>
                                    <p>Buat kategori shift, atur rotasi, dan pantau jadwal tim harian.</p>
                                </div>
                                <div class="hrms-card">
                                    <div class="hrms-card-icon"><i class="bi bi-fingerprint"></i></div>
                                    <h4>Rekap Absensi</h4>
                                    <p>Lacak kehadiran, izin, dan keterlambatan karyawan secara otomatis.</p>
                                </div>
                                <div class="hrms-card">
                                    <div class="hrms-card-icon"><i class="bi bi-wallet2"></i></div>
                                    <h4>Modal Usaha</h4>
                                    <p>Catat modal yang disetorkan dan pantau riwayatnya dengan grafik.</p>
                                </div>
                                <div class="hrms-card">
                                    <div class="hrms-card-icon"><i class="bi bi-bar-chart-line-fill"></i></div>
                                    <h4>Ikhtisar Bisnis</h4>
                                    <p>Pantau omzet, untung-rugi, dan pengeluaran outlet secara real-time.</p>
                                </div>
                                <div class="hrms-card">
                                    <div class="hrms-card-icon"><i class="bi bi-graph-up-arrow"></i></div>
                                    <h4>Performa Bisnis</h4>
                                    <p>Analisis komparasi omzet & prediksi tren berbasis data transaksi.</p>
                                </div>
                                <div class="hrms-card">
                                    <div class="hrms-card-icon"><i class="bi bi-shield-lock-fill"></i></div>
                                    <h4>Akses Akun Kasir</h4>
                                    <p>Buat dan kelola akun login sistem untuk masing-masing karyawan.</p>
                                </div>
                                <div class="hrms-card">
                                    <div class="hrms-card-icon"><i class="bi bi-stars"></i></div>
                                    <h4>MineAIr</h4>
                                    <p>Konsultasi bisnis AI khusus dasbor manager dengan data outlet.</p>
                                </div>
                                {{-- Duplikat untuk seamless loop --}}
                                <div class="hrms-card">
                                    <div class="hrms-card-icon"><i class="bi bi-person-lines-fill"></i></div>
                                    <h4>Data Karyawan</h4>
                                    <p>Kelola profil, biodata, dan arsip karyawan aktif maupun nonaktif.</p>
                                </div>
                                <div class="hrms-card">
                                    <div class="hrms-card-icon"><i class="bi bi-calendar-week-fill"></i></div>
                                    <h4>Manajemen Shift</h4>
                                    <p>Buat kategori shift, atur rotasi, dan pantau jadwal tim harian.</p>
                                </div>
                                <div class="hrms-card">
                                    <div class="hrms-card-icon"><i class="bi bi-fingerprint"></i></div>
                                    <h4>Rekap Absensi</h4>
                                    <p>Lacak kehadiran, izin, dan keterlambatan karyawan secara otomatis.</p>
                                </div>
                                <div class="hrms-card">
                                    <div class="hrms-card-icon"><i class="bi bi-wallet2"></i></div>
                                    <h4>Modal Usaha</h4>
                                    <p>Catat modal yang disetorkan dan pantau riwayatnya dengan grafik.</p>
                                </div>
                                <div class="hrms-card">
                                    <div class="hrms-card-icon"><i class="bi bi-bar-chart-line-fill"></i></div>
                                    <h4>Ikhtisar Bisnis</h4>
                                    <p>Pantau omzet, untung-rugi, dan pengeluaran outlet secara real-time.</p>
                                </div>
                                <div class="hrms-card">
                                    <div class="hrms-card-icon"><i class="bi bi-graph-up-arrow"></i></div>
                                    <h4>Performa Bisnis</h4>
                                    <p>Analisis komparasi omzet & prediksi tren berbasis data transaksi.</p>
                                </div>
                                <div class="hrms-card">
                                    <div class="hrms-card-icon"><i class="bi bi-shield-lock-fill"></i></div>
                                    <h4>Akses Akun Karyawanr</h4>
                                    <p>Buat dan kelola akun login sistem untuk masing-masing karyawan.</p>
                                </div>
                                <div class="hrms-card">
                                    <div class="hrms-card-icon"><i class="bi bi-stars"></i></div>
                                    <h4>MineAI</h4>
                                    <p>Konsultasi bisnis AI khusus dasbor manager dengan data outlet.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        {{-- ===== END SECTION HRMS ===== --}}

        {{-- ===== SECTION MINEAI SPOTLIGHT ===== --}}
        <section id="mineai" class="py-0 overflow-hidden">
            <div class="mineai-section">

                {{-- Animated orb blobs --}}
                <div class="mineai-orb mineai-orb1"></div>
                <div class="mineai-orb mineai-orb2"></div>
                <div class="mineai-orb mineai-orb3"></div>

                <div class="container py-5 position-relative" style="z-index:2;">
                    <div class="row align-items-center g-5">

                        {{-- Kiri: teks --}}
                        <div class="col-lg-5 text-center text-lg-start animate-slide-up">

                            {{-- Logo + badge --}}
                            <div class="d-flex align-items-center justify-content-center justify-content-lg-start gap-3 mb-4">
                                <img src="{{ asset('assets/logo-mineai.jpg') }}"
                                     alt="MineAI"
                                     class="mineai-logo-badge"
                                     onerror="this.style.display='none'">
                                <div>
                                    <span class="mineai-tag">Fitur Eksklusif</span>
                                    <h2 class="mineai-name-text mb-0">Mine<span>AI</span></h2>
                                </div>
                            </div>

                            <h3 class="mineai-headline">
                                Konsultan Bisnis<br>
                                <span class="mineai-grad-text">Berbasis AI</span><br>
                                Pertama di POS Kuliner
                            </h3>

                            <p class="mineai-desc">
                                MineAI membaca data transaksi nyata outlet kamu, memberikan insight otomatis, dan bisa diajak ngobrol layaknya konsultan bisnis pribadi, kapan saja, langsung dari dashboard kasir.
                            </p>

                            <ul class="mineai-features list-unstyled">
                                <li><i class="bi bi-check-circle-fill"></i> Analisis omzet & tren 30 hari otomatis</li>
                                <li><i class="bi bi-check-circle-fill"></i> Saran strategi berbasis data real outlet</li>
                                <li><i class="bi bi-check-circle-fill"></i> Chat AI yang ingat konteks bisnis kamu</li>
                                <li><i class="bi bi-check-circle-fill"></i> Deteksi stok tipis & peringatan dini</li>
                            </ul>

                            <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start mt-4">
                                <a href="{{ route('register') }}" class="btn-mineai-cta">
                                    <i class="bi bi-stars me-2"></i> Coba MineAI Gratis
                                </a>
                                <a href="{{ route('panduan.hrms.mineai-manager') }}" class="btn-mineai-ghost">
                                    Pelajari Lebih Lanjut <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>

                        {{-- Kanan: screenshot --}}
                        <div class="col-lg-7 animate-fade-in">
                            <div class="mineai-screen-wrap">
                                <div class="mineai-screen-glow"></div>
                                <img src="{{ asset('assets/ss-mineai.jpg') }}"
                                     alt="Tampilan MineAI, Konsultan Bisnis AI Flavory.id"
                                     class="mineai-screenshot"
                                     onerror="this.src='https://placehold.co/900x560/1e1e2e/ff6a00?text=MineAI+Preview'">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        {{-- ===== END SECTION MINEAI ===== --}}

        <section id="paket" class="py-5 bg-white">
            <div class="container py-5">
                <div class="text-center mb-5 mx-auto animate-slide-up" style="max-width:760px;">
                    <span class="text-orange fw-bold text-uppercase small"><i class="bi bi-tag-fill me-1"></i> Harga & Paket</span>
                    <h2 class="fw-bold mt-2">Investasi Terbaik untuk Masa Depan Bisnis Anda</h2>
                    <p class="text-muted">Semua paket mendapatkan <strong class="text-dark">SELURUH FITUR LENGKAP</strong> - POS Kasir, HRMS (key.flavory.id), MineAI, Prediksi Bisnis, dan masih banyak lagi. Yang berbeda hanya durasi & harga.</p>
                </div>

                <div class="row justify-content-center align-items-stretch g-4">

                    {{-- Paket Trial --}}
                    <div class="col-md-6 col-lg-4 animate-slide-up" style="transition-delay:.1s">
                        <article class="pricing-card text-center">
                            <h3 class="h4 fw-bold text-dark mb-1">Paket Gratis</h3>
                            <p class="text-muted small mb-4">Buktikan sendiri kemudahan aplikasi kami</p>
                            <div class="mb-4">
                                <span class="fw-bold text-dark display-6">Rp 0</span>
                                <span class="text-muted d-block" style="font-size:.85rem;">/ 30 Hari</span>
                            </div>

                            <div class="text-start border-top pt-4 mb-4">
                                <p class="fw-bold text-dark small mb-2" style="letter-spacing:.3px;text-transform:uppercase;"><i class="bi bi-shop me-1 text-orange"></i> Fitur POS Kasir</p>
                                    <li><i class="bi bi-check2"></i> Kasir POS (dine-in, take away, QR order)</li>
                                    <li><i class="bi bi-check2"></i> Diskon nominal/persen + Kode Voucher</li>
                                    <li><i class="bi bi-check2"></i> Fitur E-Commerce (customer pesen dari rumah)</li>
                                    <li><i class="bi bi-check2"></i> Cetak struk 5 jalur: bluethoot, USB, QR & WhatsApp</li>
                                    <li><i class="bi bi-check2"></i> Pesanan offline &amp; sinkronisasi otomatis</li>
                                    <li><i class="bi bi-check2"></i> Manajemen stok real-time + indikator tipis</li>
                                    <li><i class="bi bi-check2"></i> Laporan pengeluaran operasional</li>
                                    <li><i class="bi bi-check2"></i> Ikhtisar bisnis harian (omzet, order, untung)</li>
                                    <li><i class="bi bi-check2"></i> Analisis menu paling laris &amp; tidak laris</li>
                                    <li><i class="bi bi-check2"></i> Modal Usaha &amp; riwayat setoran</li>
                                    <li><i class="bi bi-check2"></i> Pembaruan sistem otomatis (free update)</li>
                                </ul>
                                <p class="fw-bold text-dark small mb-2 mt-3" style="letter-spacing:.3px;text-transform:uppercase;"><i class="bi bi-people-fill me-1 text-success"></i> Fitur HRMS (key.flavory.id)</p>
                                <ul class="check-list small">
                                    <li><i class="bi bi-check2"></i> Manajemen data karyawan (aktif & nonaktif)</li>
                                    <li><i class="bi bi-check2"></i> Manajemen shift &amp; rotasi jadwal</li>
                                    <li><i class="bi bi-check2"></i> Rekap absensi &amp; izin karyawan</li>
                                    <li><i class="bi bi-check2"></i> Modal usaha &amp; riwayat setoran</li>
                                    <li><i class="bi bi-check2"></i> Ikhtisar bisnis &amp; performa outlet</li>
                                    <li><i class="bi bi-check2"></i> Akun akses per karyawan</li>
                                    <li><i class="bi bi-check2"></i> MineAI (Fitur AI konsultan)</li>
                                </ul>
                            </div>
                            <div class="mt-auto">
                                <a href="{{ route('register') }}" class="btn btn-outline-dark w-100 fw-bold py-2 rounded-pill">Mulai Gratis Sekarang</a>
                            </div>
                        </article>
                    </div>

                    {{-- Paket Bulanan --}}
                    <div class="col-md-6 col-lg-4 animate-slide-up" style="transition-delay:.2s">
                        <article class="pricing-card popular text-center shadow-lg">
                            <span class="badge-popular">PALING DIMINATI</span>
                            <h3 class="h4 fw-bold text-dark mb-1">Paket Ekslusif</h3>
                            <p class="text-muted small mb-3">Solusi cerdas dengan harga sangat terjangkau</p>
                            <div class="mb-2">
                                <span class="promo-tag mb-2 d-inline-block shadow-sm"><i class="bi bi-lightning-charge-fill me-1"></i> PROMO TERBATAS: DISKON 30%</span>
                            </div>
                            <div class="mb-4">
                                <span class="price-strike">Rp 100.000</span>
                                <span class="fw-bolder text-orange display-5 d-block" style="letter-spacing:-1px;">Rp 70.000</span>
                                <span class="text-muted fw-medium">/ Bulan</span>
                            </div>

                            <div class="text-start border-top pt-4 mb-4">
                                <p class="fw-bold text-dark small mb-2" style="letter-spacing:.3px;text-transform:uppercase;">Fitur POS Kasir</p>
                                <ul class="check-list small">
                                    <li><i class="bi bi-check-circle-fill text-orange"></i> Kasir POS (dine-in, take away, QR order)</li>
                                    <li><i class="bi bi-check-circle-fill text-orange"></i> Diskon nominal/persen + Kode Voucher</li>
                                    <li><i class="bi bi-check-circle-fill text-orange"></i> Fitur E-Commerce (customer pesen dari rumah)</li>
                                    <li><i class="bi bi-check-circle-fill text-orange"></i> Cetak struk 5 jalur: bluethoot, USB, QR & WhatsApp</li>
                                    <li><i class="bi bi-check-circle-fill text-orange"></i> Pesanan offline &amp; sinkronisasi otomatis</li>
                                    <li><i class="bi bi-check-circle-fill text-orange"></i> Manajemen stok real-time + indikator tipis</li>
                                    <li><i class="bi bi-check-circle-fill text-orange"></i> Laporan pengeluaran operasional</li>
                                    <li><i class="bi bi-check-circle-fill text-orange"></i> Ikhtisar bisnis harian (omzet, order, untung)</li>
                                    <li><i class="bi bi-check-circle-fill text-orange"></i> Analisis menu paling laris &amp; tidak laris</li>
                                    <li><i class="bi bi-check-circle-fill text-orange"></i> Modal Usaha &amp; riwayat setoran</li>
                                    <li><i class="bi bi-check-circle-fill text-orange"></i> Pembaruan sistem otomatis (free update)</li>
                                </ul>
                                <p class="fw-bold text-dark small mb-2 mt-3" style="letter-spacing:.3px;text-transform:uppercase;">Fitur HRMS (key.flavory.id)</p>
                                <ul class="check-list small">
                                    <li><i class="bi bi-check-circle-fill text-orange"></i> Manajemen data karyawan (aktif & nonaktif)</li>
                                    <li><i class="bi bi-check-circle-fill text-orange"></i> Manajemen shift &amp; rotasi jadwal</li>
                                    <li><i class="bi bi-check-circle-fill text-orange"></i> Rekap absensi &amp; izin karyawan</li>
                                    <li><i class="bi bi-check-circle-fill text-orange"></i> Modal usaha &amp; riwayat setoran</li>
                                    <li><i class="bi bi-check-circle-fill text-orange"></i> Ikhtisar bisnis &amp; performa outlet</li>
                                    <li><i class="bi bi-check-circle-fill text-orange"></i> Akun akses per karyawan</li>
                                    <li><i class="bi bi-check-circle-fill text-orange"></i> MineAI (Fitur AI konsultan)</li>
                                </ul>
                            </div>
                            <div class="mt-auto">
                                <a href="{{ route('register') }}" class="btn btn-orange w-100 py-3 fs-5 shadow-sm fw-bold rounded-pill">Ambil Promo Bulanan</a>
                            </div>
                        </article>
                    </div>

                    {{-- Paket Selamanya --}}
                    <div class="col-md-6 col-lg-4 animate-slide-up" style="transition-delay:.3s">
                        <article class="pricing-card text-center">
                            <h3 class="h4 fw-bold text-dark mb-1">Paket Super</h3>
                            <p class="text-muted small mb-4">Bayar sekali, aktif selamanya</p>
                            <div class="mb-4">
                                <span class="fw-bold text-dark display-6 d-block">Rp 689.000</span>
                                <span class="text-muted">/ Selamanya <span class="badge bg-success-subtle text-success fw-bold ms-1" style="font-size:.65rem;">Bayar Sekali</span></span>
                            </div>

                            <div class="text-start border-top pt-4 mb-4">
                                <p class="fw-bold text-dark small mb-2" style="letter-spacing:.3px;text-transform:uppercase;"><i class="bi bi-shop me-1 text-orange"></i> Fitur POS Kasir</p>
                                <ul class="check-list small">
                                    <li><i class="bi bi-check2 text-success"></i> Kasir POS (dine-in, take away, QR order)</li>
                                    <li><i class="bi bi-check2 text-success"></i> Diskon nominal/persen + Kode Voucher</li>
                                    <li><i class="bi bi-check2 text-success"></i> Fitur E-Commerce (customer pesen dari rumah)</li>
                                    <li><i class="bi bi-check2 text-success"></i> Cetak struk 5 jalur:  bluethoot, USB, QR & WhatsApp</li>
                                    <li><i class="bi bi-check2 text-success"></i> Pesanan offline &amp; sinkronisasi otomatis</li>
                                    <li><i class="bi bi-check2 text-success"></i> Manajemen stok real-time + indikator tipis</li>
                                    <li><i class="bi bi-check2 text-success"></i> Laporan pengeluaran operasional</li>
                                    <li><i class="bi bi-check2 text-success"></i> Ikhtisar bisnis harian (omzet, order, untung)</li>
                                    <li><i class="bi bi-check2 text-success"></i> Analisis menu paling laris &amp; tidak laris</li>
                                    <li><i class="bi bi-check2 text-success"></i> Modal Usaha &amp; riwayat setoran</li>
                                    <li><i class="bi bi-check2 text-success"></i> Pembaruan sistem otomatis (free update)</li>
                                </ul>
                                <p class="fw-bold text-dark small mb-2 mt-3" style="letter-spacing:.3px;text-transform:uppercase;">Fitur HRMS (key.flavory.id)</p>
                                <ul class="check-list small">
                                    <li><i class="bi bi-check2 text-success"></i> Manajemen data karyawan (aktif & nonaktif)</li>
                                    <li><i class="bi bi-check2 text-success"></i> Manajemen shift &amp; rotasi jadwal</li>
                                    <li><i class="bi bi-check2 text-success"></i> Rekap absensi &amp; izin karyawan</li>
                                    <li><i class="bi bi-check2 text-success"></i> Modal usaha &amp; riwayat setoran</li>
                                    <li><i class="bi bi-check2 text-success"></i> Ikhtisar Bisnis &amp; performa outlet</li>
                                    <li><i class="bi bi-check2 text-success"></i> Akun akses per karyawan</li>
                                    <li><i class="bi bi-check2 text-success"></i> MineAI (Fitur AI konsultan)</li>
                                </ul>
                            </div>
                            <div class="mt-auto">
                                <a href="{{ route('register') }}" class="btn btn-outline-success w-100 fw-bold py-2 rounded-pill">Pilih Paket Selamanya</a>
                            </div>
                        </article>
                    </div>

                </div>
            </div>
        </section>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== SECTION BLOG FLAVORY ===== --}}
        @php
            // Gunakan data dummy sementara jika blog belum live
            $blogDisplay = !empty($blogPosts) ? $blogPosts : [
                [
                    'title'        => 'Tips Meningkatkan Omzet Kafe di Era Digital',
                    'excerpt'      => 'Pelajari strategi jitu yang digunakan ratusan pemilik kafe sukses untuk menggandakan pendapatan mereka menggunakan teknologi POS modern.',
                    'category'     => 'Bisnis',
                    'published_at' => '25 Jun 2026',
                    'cover_image'  => 'https://placehold.co/640x360/fff4ec/ff6a00?text=Flavory+Blog',
                    'url'          => 'https://blog.flavory.id',
                ],
                [
                    'title'        => 'Cara Kelola Stok Dapur agar Tidak Boros & Rugi',
                    'excerpt'      => 'Manajemen stok yang buruk adalah penyebab utama kebocoran dana di restoran kecil. Simak panduan praktis ini untuk mulai kontrol stok dengan benar.',
                    'category'     => 'Operasional',
                    'published_at' => '20 Jun 2026',
                    'cover_image'  => 'https://placehold.co/640x360/fff4ec/ff6a00?text=Kelola+Stok',
                    'url'          => 'https://blog.flavory.id',
                ],
                [
                    'title'        => 'Kenapa Bisnis Kuliner Anda Butuh Aplikasi Kasir Digital?',
                    'excerpt'      => 'Masih pakai buku manual? Saatnya beralih ke sistem kasir digital. Temukan keuntungan nyata yang langsung bisa Anda rasakan sejak hari pertama.',
                    'category'     => 'Teknologi',
                    'published_at' => '15 Jun 2026',
                    'cover_image'  => 'https://placehold.co/640x360/fff4ec/ff6a00?text=Kasir+Digital',
                    'url'          => 'https://blog.flavory.id',
                ],
            ];
        @endphp
        @if(!empty($blogDisplay))
        <section id="blog" class="py-5 bg-white">
            <div class="container py-4">
                <div class="text-center mb-5 animate-slide-up">
                    <span class="text-orange fw-bold text-uppercase small tracking-wider">
                        <i class="bi bi-journal-richtext me-1"></i> Blog Flavory
                    </span>
                    <h2 class="fw-bold mt-2">Tips & Insight untuk Bisnis Kuliner Anda</h2>
                    <p class="text-muted">Artikel terbaru dari tim Flavory, khusus untuk pemilik usaha F&B Indonesia.</p>
                </div>

                <div class="row g-4 justify-content-center">
                    @foreach($blogDisplay as $post)
                    <div class="col-md-6 col-lg-4 animate-slide-up">
                        <article class="blog-card">
                            <a href="{{ $post['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="Baca artikel: {{ $post['title'] }}">
                                <img
                                    src="{{ $post['cover_image'] }}"
                                    alt="{{ $post['title'] }}"
                                    class="blog-card-img"
                                    loading="lazy"
                                    onerror="this.src='https://placehold.co/640x360/fff4ec/ff6a00?text=Flavory+Blog'"
                                >
                            </a>
                            <div class="blog-card-body">
                                @if(!empty($post['category']))
                                <span class="blog-category-badge">{{ $post['category'] }}</span>
                                @endif
                                <h3 class="blog-card-title">
                                    <a href="{{ $post['url'] }}" target="_blank" rel="noopener noreferrer"
                                       class="text-decoration-none text-dark stretched-link-off">
                                        {{ $post['title'] }}
                                    </a>
                                </h3>
                                <p class="blog-card-excerpt">{{ $post['excerpt'] }}</p>
                                <div class="blog-card-footer">
                                    <span class="blog-date">
                                        <i class="bi bi-calendar3 me-1"></i>{{ $post['published_at'] }}
                                    </span>
                                    <a href="{{ $post['url'] }}" target="_blank" rel="noopener noreferrer"
                                       class="blog-read-link">
                                        Baca <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                    @endforeach
                </div>

                <div class="text-center mt-5 animate-slide-up">
                    <a href="https://blog.flavory.id" target="_blank" rel="noopener noreferrer"
                       class="btn btn-outline-orange px-5 py-2 fw-bold rounded-pill">
                        <i class="bi bi-journal-richtext me-2"></i> Lihat Semua Artikel
                    </a>
                </div>
            </div>
        </section>
        @endif
        {{-- ===== END SECTION BLOG ===== --}}

        <section class="py-5" style="background-color: #f8fafc;">
            <div class="container">
                <div class="bonus-banner p-4 p-md-5 text-center shadow-lg animate-fade-in">
                    <i class="bi bi-gift-fill display-4 mb-3 d-block opacity-75"></i>
                    <h2 class="fw-bold text-white mb-3">BONUS SPESIAL: PANDUAN PENGGUNAAN GRATIS!</h2>
                    <p class="fs-5 mb-4 opacity-100">Kami tidak akan membiarkan Anda kebingungan. Tim kami siap memastikan usaha kuliner Anda beroperasi maksimal dengan Flavory.id.</p>
                    
                    <div class="row justify-content-center g-4 mb-4">
                        <div class="col-md-5">
                            <article class="bg-white text-dark p-3 rounded-4 shadow-sm h-100 d-flex align-items-center justify-content-center transition-hover">
                                <h3 class="h5 fw-bold m-0"><i class="bi bi-laptop text-orange me-2"></i> PENDAMPINGAN ONLINE<br><span class="small text-muted fw-normal fs-6">Praktis untuk seluruh area Indonesia</span></h3>
                            </article>
                        </div>
                        <div class="col-md-5">
                            <article class="bg-white text-dark p-3 rounded-4 shadow-sm h-100 d-flex align-items-center justify-content-center transition-hover">
                                <h3 class="h5 fw-bold m-0"><i class="bi bi-people-fill text-orange me-2"></i> TRAINING TATAP MUKA<br><span class="small text-muted fw-normal fs-6">Layanan eksklusif untuk area Karawang</span></h3>
                            </article>
                        </div>
                    </div>

                    <a href="{{ route('register') ?? '#' }}" class="btn btn-light btn-lg fw-bold text-orange rounded-pill px-5 mt-3 shadow-lg" style="transform: scale(1.05);" aria-label="Daftar Aplikasi Manajemen Restoran Flavory.id">Daftar Sekarang & Klaim Bonus</a>
                </div>
            </div>
        </section>
    </main>

{{-- ═══ MODAL: COBA GRATIS ═══ --}}
<dialog id="modalCobaGratis" style="
    border: none;
    border-radius: 24px;
    padding: 0;
    max-width: 460px;
    width: calc(100% - 32px);
    box-shadow: 0 24px 80px rgba(0,0,0,0.18), 0 4px 20px rgba(255,106,0,0.12);
    background: #fff;
    overflow: hidden;
">
    <div style="background: linear-gradient(135deg, #fff4ec 0%, #fdf0f8 100%); padding: 28px 28px 20px;">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
            <div style="
                width:44px; height:44px; border-radius:12px;
                background: linear-gradient(135deg, #ff6a00, #ee0979);
                display:flex; align-items:center; justify-content:center;
                box-shadow: 0 6px 16px rgba(255,106,0,0.3); flex-shrink:0;
            ">
                <i class="bi bi-rocket-takeoff-fill" style="color:#fff; font-size:1.1rem;"></i>
            </div>
            <div>
                <p style="font-size:0.68rem; font-weight:800; letter-spacing:1px; text-transform:uppercase; color:#ff6a00; margin:0 0 2px;">Mulai Sekarang</p>
                <h2 style="font-size:1.15rem; font-weight:900; color:#1e293b; margin:0; line-height:1.2;">Pilih cara terbaik<br>untuk Anda!</h2>
            </div>
        </div>
        <p style="font-size:0.82rem; color:#64748b; line-height:1.65; margin:0;">
            Anda bisa langsung <strong style="color:#1e293b;">mendaftar akun baru</strong> dengan paket gratis, atau <strong style="color:#1e293b;">coba dulu</strong> aplikasinya lewat akun demo tanpa perlu daftar.
        </p>
    </div>

    <div style="padding: 20px 28px 28px; display:flex; flex-direction:column; gap:12px;">

        {{-- Pilihan 1: Daftar gratis --}}
        <a href="{{ route('register') }}"
           style="
               display:flex; align-items:center; gap:14px;
               padding:16px 18px; border-radius:16px;
               background: linear-gradient(135deg, #ff6a00, #ff8c33);
               box-shadow: 0 6px 20px rgba(255,106,0,0.28);
               text-decoration:none; transition: transform .2s, box-shadow .2s;
           "
           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 28px rgba(255,106,0,0.38)';"
           onmouseout="this.style.transform=''; this.style.boxShadow='0 6px 20px rgba(255,106,0,0.28)';">
            <div style="width:40px; height:40px; border-radius:10px; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="bi bi-person-plus-fill" style="color:#fff; font-size:1.1rem;"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:0.9rem; font-weight:800; color:#fff; margin-bottom:2px;">Daftar Akun Gratis</div>
                <div style="font-size:0.73rem; color:rgba(255,255,255,0.8);">Buat akun baru dengan paket gratis, mulai dalam 2 menit</div>
            </div>
            <i class="bi bi-arrow-right" style="color:rgba(255,255,255,0.7); font-size:1rem;"></i>
        </a>

        {{-- Pilihan 2: Login demo --}}
        <a href="{{ route('demo.login', substr(hash('sha256', config('app.key') . 'demo-access'), 0, 16)) }}"
           style="
               display:flex; align-items:center; gap:14px;
               padding:16px 18px; border-radius:16px;
               background: #fff; border: 1.5px solid rgba(255,106,0,0.2);
               box-shadow: 0 4px 14px rgba(0,0,0,0.05);
               text-decoration:none; transition: transform .2s, box-shadow .2s, border-color .2s;
           "
           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 22px rgba(255,106,0,0.12)'; this.style.borderColor='rgba(255,106,0,0.45)';"
           onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 14px rgba(0,0,0,0.05)'; this.style.borderColor='rgba(255,106,0,0.2)';">
            <div style="width:40px; height:40px; border-radius:10px; background:#fff4ec; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="bi bi-play-circle-fill" style="color:#ff6a00; font-size:1.1rem;"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:0.9rem; font-weight:800; color:#1e293b; margin-bottom:2px;">Coba Akun Demo Gratis</div>
                <div style="font-size:0.73rem; color:#64748b;">Login otomatis ke akun demo, tanpa perlu daftar</div>
            </div>
            <i class="bi bi-arrow-right" style="color:#ff6a00; font-size:1rem;"></i>
        </a>

        {{-- Tutup --}}
        <button onclick="document.getElementById('modalCobaGratis').close()"
            style="
                background:none; border:none; padding:8px;
                font-size:0.78rem; font-weight:600; color:#94a3b8;
                cursor:pointer; transition:color .2s;
            "
            onmouseover="this.style.color='#1e293b';"
            onmouseout="this.style.color='#94a3b8';">
            Nanti saja
        </button>

    </div>
</dialog>

<script>
    // Tutup modal jika klik backdrop (luar dialog)
    document.getElementById('modalCobaGratis').addEventListener('click', function(e) {
        const rect = this.getBoundingClientRect();
        const inside = e.clientX >= rect.left && e.clientX <= rect.right
                    && e.clientY >= rect.top  && e.clientY <= rect.bottom;
        if (!inside) this.close();
    });
</script>

{{-- ══ POPUP EVENT DINAMIS ══ --}}
@if(isset($popupEvent) && $popupEvent)
<div id="eventPopupOverlay"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.55); z-index:99990;
            align-items:center; justify-content:center; padding:16px;">
    <div id="eventPopupCard"
         style="position:relative; max-width:340px; width:100%; background:#fff;
                border-radius:18px; overflow:hidden; box-shadow:0 24px 64px rgba(0,0,0,.22);
                animation: popupIn .4s cubic-bezier(.34,1.56,.64,1) both;">

        {{-- Tombol tutup --}}
        <button id="eventPopupClose"
                aria-label="Tutup popup"
                style="position:absolute; top:10px; right:10px; z-index:10;
                       width:32px; height:32px; border-radius:50%; border:none;
                       background:rgba(0,0,0,.45); color:#fff; font-size:1rem;
                       display:flex; align-items:center; justify-content:center;
                       cursor:pointer; transition:background .2s; backdrop-filter:blur(4px);"
                onmouseover="this.style.background='rgba(0,0,0,.7)'"
                onmouseout="this.style.background='rgba(0,0,0,.45)'">
            <i class="bi bi-x-lg"></i>
        </button>

        {{-- Poster / gambar event --}}
        @if($popupEvent->image)
            <div style="position:relative; background:#f8f9fa; overflow:hidden; max-height:52vh;">
                <img src="{{ asset('assets/event/' . $popupEvent->image) }}"
                     alt="{{ $popupEvent->event_name }}"
                     style="width:100%; height:auto; display:block;"
                     onerror="this.parentElement.style.display='none'">
                {{-- Label "Segera Hadir" di atas poster --}}
                <span style="position:absolute; top:12px; left:12px; background:#ff6a00; color:#fff;
                             font-size:.7rem; font-weight:800; padding:5px 12px; border-radius:20px;
                             letter-spacing:.4px; box-shadow:0 4px 12px rgba(255,106,0,.4);">
                    <i class="bi bi-calendar-event-fill me-1"></i> Segera Hadir
                </span>
            </div>
        @endif

        {{-- Konten --}}
        <div style="padding:14px 16px 16px;">
            <p style="font-size:.62rem; font-weight:700; letter-spacing:1px; text-transform:uppercase;
                      color:#ff6a00; margin-bottom:3px;">Event Flavory.id</p>
            <h3 style="font-size:.92rem; font-weight:800; color:#1e293b; line-height:1.35; margin-bottom:8px;">
                {{ $popupEvent->event_name }}
            </h3>

            {{-- Meta: tanggal & lokasi --}}
            <div style="display:flex; flex-direction:column; gap:4px; margin-bottom:13px;">
                <div style="font-size:.72rem; color:#64748b; display:flex; align-items:center; gap:6px;">
                    <i class="bi bi-calendar3" style="color:#ff6a00; flex-shrink:0;"></i>
                    <span>
                        {{ \Carbon\Carbon::parse($popupEvent->event_start_date)->translatedFormat('d M Y') }}
                        @if($popupEvent->event_start_date !== $popupEvent->event_end_date)
                            &ndash; {{ \Carbon\Carbon::parse($popupEvent->event_end_date)->translatedFormat('d M Y') }}
                        @endif
                        @if($popupEvent->event_start_time)
                            &bull; {{ \Carbon\Carbon::parse($popupEvent->event_start_time)->format('H:i') }} WIB
                        @endif
                    </span>
                </div>
                @if($popupEvent->location)
                    <div style="font-size:.72rem; color:#64748b; display:flex; align-items:center; gap:6px;">
                        <i class="bi bi-geo-alt-fill" style="color:#ff6a00; flex-shrink:0;"></i>
                        <span>{{ $popupEvent->location }}</span>
                    </div>
                @endif
            </div>

            {{-- Tombol CTA --}}
            <a href="{{ route('event.show', $popupEvent->slug ?? $popupEvent->uuid) }}"
               style="display:flex; align-items:center; justify-content:center; gap:7px;
                      background:linear-gradient(135deg,#ff6a00,#ff8c33); color:#fff;
                      border-radius:50px; padding:9px 16px; font-size:.8rem; font-weight:700;
                      text-decoration:none; box-shadow:0 5px 16px rgba(255,106,0,.35);
                      transition:all .25s; width:100%;"
               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 28px rgba(255,106,0,.45)'"
               onmouseout="this.style.transform=''; this.style.boxShadow='0 5px 16px rgba(255,106,0,.35)'">
                <i class="bi bi-pencil-square"></i> Daftar Sekarang
            </a>
            <p style="font-size:.63rem; color:#94a3b8; text-align:center; margin-top:8px; margin-bottom:0;">
                Klik × untuk menutup. Popup tidak muncul lagi hari ini.
            </p>
        </div>
    </div>
</div>

<style>
@keyframes popupIn {
    from { opacity: 0; transform: scale(.88) translateY(24px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}
</style>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    // ═══════════════════════════════════════════════════════
    //  WORD BLUR REVEAL  +  ROTATING PHRASES  - looping
    // ═══════════════════════════════════════════════════════

    const col      = document.querySelector('.hero-reveal-col');
    const badge    = col && col.querySelector('.hero-badge-wrap');
    const subtitle = col && col.querySelector('.hero-subtitle');
    const ctaWrap  = col && col.querySelector('.hero-cta');
    const rotLine  = col && col.querySelector('#hero-rotating-line');

    if (!col) return;

    // ── Daftar frasa yang akan bergiliran di rotating line ──
    const PHRASES = [
        'Bangun Bisnis Kuliner Bersama <span style="color:inherit">Flavory.id</span>',
        'Kelola Pesanan Lebih Cepat &amp; Mudah',
        'Pantau Keuangan Secara Real-Time',
        '<span style="background:linear-gradient(135deg,#ff6a00,#ee0979);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">MineAI</span> &ndash; Konsultan Bisnis AI Kamu',
    ];
    let phraseIndex = 0;

    // ── Render kata-kata rotating phrase ke dalam rotLine ──
    function renderPhrase(html) {
        rotLine.innerHTML = '';
        // Buat dummy element untuk split kata dari HTML
        const tmp = document.createElement('span');
        tmp.innerHTML = html;

        // Ambil semua node (text + element)
        tmp.childNodes.forEach(node => {
            if (node.nodeType === Node.TEXT_NODE) {
                node.textContent.split(/(\s+)/).forEach(part => {
                    if (/^\s+$/.test(part)) {
                        rotLine.appendChild(document.createTextNode(' '));
                    } else if (part.length) {
                        const s = document.createElement('span');
                        s.className = 'rot-word';
                        s.textContent = part;
                        rotLine.appendChild(s);
                    }
                });
            } else if (node.nodeType === Node.ELEMENT_NODE) {
                // Misal <span> untuk warna - bungkus seluruh elemennya
                node.textContent.split(/(\s+)/).forEach(part => {
                    if (/^\s+$/.test(part)) {
                        rotLine.appendChild(document.createTextNode(' '));
                    } else if (part.length) {
                        const s = document.createElement('span');
                        s.className = 'rot-word';
                        s.style.cssText = node.getAttribute('style') || '';
                        s.textContent = part;
                        rotLine.appendChild(s);
                    }
                });
            }
        });
    }

    function rotWordsIn() {
        const words = rotLine.querySelectorAll('.rot-word');
        words.forEach((w, i) => {
            setTimeout(() => w.classList.add('rot-in'), i * 60);
        });
    }

    function rotWordsOut(cb) {
        const words = [...rotLine.querySelectorAll('.rot-word')].reverse();
        words.forEach((w, i) => {
            setTimeout(() => {
                w.classList.remove('rot-in');
                w.classList.add('rot-out');
            }, i * 35);
        });
        const total = (words.length - 1) * 35 + 300;
        setTimeout(cb, total);
    }

    // ── wrapWords: pecah text node biasa jadi <span class="w"> ──
    // Tidak skip BR - cukup skip elemen yang sudah punya class .w atau #hero-rotating-line
    function wrapWords(el) {
        [...el.childNodes].forEach(node => {
            if (node.nodeType === Node.TEXT_NODE) {
                const parts = node.textContent.split(/(\s+)/);
                const frag  = document.createDocumentFragment();
                parts.forEach(part => {
                    if (/^\s+$/.test(part)) {
                        frag.appendChild(document.createTextNode(part));
                    } else if (part.length) {
                        const s = document.createElement('span');
                        s.className = 'w';
                        s.textContent = part;
                        frag.appendChild(s);
                    }
                });
                node.replaceWith(frag);
            } else if (
                node.nodeType === Node.ELEMENT_NODE &&
                node.tagName !== 'BR' &&
                node.id !== 'hero-rotating-line'
            ) {
                wrapWords(node);
            }
        });
    }

    // Wrap subtitle
    wrapWords(subtitle);

    // CTA: tiap tombol = 1 unit .w
    ctaWrap.querySelectorAll('a').forEach(a => a.classList.add('w'));

    // Kumpulkan semua elemen untuk animasi masuk/keluar global
    const staticWords = [
        badge,
        ...col.querySelectorAll('.hero-title > .w'),
        ...col.querySelectorAll('.hero-subtitle .w'),
        ...col.querySelectorAll('.hero-cta .w'),
    ].filter(Boolean);

    // ── Timing ──
    const IN_STAG   = 55;
    const IN_DUR    = 500;
    const PAUSE     = 3500; // diam lebih lama agar teks terbaca

    function totalEnterMs() { return (staticWords.length - 1) * IN_STAG + IN_DUR; }

    // Static words hanya masuk SEKALI, tidak pernah keluar lagi
    let staticEntered = false;
    function enterStaticOnce() {
        if (staticEntered) return;
        staticEntered = true;
        staticWords.forEach((el, i) => {
            setTimeout(() => {
                el.classList.remove('hidden');
                el.classList.add('visible');
            }, i * IN_STAG);
        });
    }

    function resetStatic() {
        // Tidak reset static words - hanya rotating line yang berubah
    }

    // ── Loop utama - hanya rotating line yang berganti ──
    function runLoop() {
        // Render phrase berikutnya
        renderPhrase(PHRASES[phraseIndex]);
        phraseIndex = (phraseIndex + 1) % PHRASES.length;

        setTimeout(() => {
            // Static words masuk sekali di awal
            enterStaticOnce();
            // Rotating phrase masuk
            rotWordsIn();

            // Setelah pause → hanya rotating line yang keluar dan berganti
            setTimeout(() => {
                rotWordsOut(() => {
                    setTimeout(runLoop, 80);
                });
            }, totalEnterMs() + PAUSE);

        }, 100);
    }

    runLoop();


    // ═══════════════════════════════════════════════════════
    //  SCROLL ANIMATION - section di bawah hero
    // ═══════════════════════════════════════════════════════
    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });

    document.querySelectorAll('.animate-slide-up, .animate-fade-in').forEach(el => {
        observer.observe(el);
    });

    // ══════════════════════════════════════════════════════════
    //  POPUP EVENT DINAMIS - tampil 1.2 detik setelah halaman siap
    //  Disimpan di sessionStorage: tidak muncul lagi 1 hari penuh
    // ══════════════════════════════════════════════════════════
    (function () {
        const overlay = document.getElementById('eventPopupOverlay');
        if (!overlay) return; // tidak ada popup event

        const STORAGE_KEY = 'flavory_popup_dismissed';
        const now         = Date.now();
        const dismissed   = localStorage.getItem(STORAGE_KEY);

        // Cek apakah sudah ditutup dalam 24 jam terakhir
        if (dismissed && (now - parseInt(dismissed, 10)) < 86400000) return;

        // Tampilkan setelah 1.2 detik agar halaman sudah render sempurna
        const showTimer = setTimeout(() => {
            overlay.style.display = 'flex';
        }, 1200);

        // Tombol tutup
        const closeBtn = document.getElementById('eventPopupClose');
        function closePopup() {
            overlay.style.opacity = '0';
            overlay.style.transition = 'opacity .25s';
            setTimeout(() => { overlay.style.display = 'none'; }, 250);
            localStorage.setItem(STORAGE_KEY, now.toString());
        }

        if (closeBtn) closeBtn.addEventListener('click', closePopup);

        // Klik overlay (di luar card) juga menutup
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) closePopup();
        });

        // ESC menutup
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && overlay.style.display !== 'none') closePopup();
        });
    })();
});
</script>
@endpush