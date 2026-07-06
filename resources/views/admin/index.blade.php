@extends('admin.layouts.app')

@section('content')
<style>
    /* === IMPORT CUSTOM FONT DIGITAL === */
    @font-face {
      font-family: 'digital';
      src: url('https://cdn.jsdelivr.net/gh/saintplay/digital-font/digital-7.ttf') format('truetype');
    }

    /* === VARIABEL WARNA FALLBACK === */
    :root {
        --bg-dark: #0B1120;
        --accent-cyan: #00E5FF;
        --accent-blue: #3B82F6;
        --text-primary: #F8FAFC;
    }

    /* === BACKGROUND LAYER === */
    .cyber-grid-bg {
        position: fixed;
        top: 0; left: 0; width: 100vw; height: 100vh;
        z-index: -3; 
        background-color: var(--bg-dark);
        background-image: 
            linear-gradient(rgba(0, 229, 255, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 229, 255, 0.05) 1px, transparent 1px);
        background-size: 50px 50px;
        animation: gridMove 30s linear infinite;
        pointer-events: none;
    }

    .cyber-grid-overlay {
        position: fixed;
        top: 0; left: 0; width: 100vw; height: 100vh;
        z-index: -2;
        background: radial-gradient(circle at center, transparent 10%, var(--bg-dark) 100%);
        pointer-events: none;
    }

    /* Container untuk partikel Anime.js */
    #particles-container {
        position: fixed;
        top: 0; left: 0; width: 100vw; height: 100vh;
        z-index: -1;
        pointer-events: none;
        overflow: hidden;
    }

    .cyber-particle {
        position: absolute;
        background: var(--accent-cyan);
        border-radius: 50%;
        box-shadow: 0 0 12px var(--accent-cyan);
        opacity: 0;
    }

    @keyframes gridMove {
        0% { background-position: 0 0; }
        100% { background-position: 50px 50px; }
    }

    /* === CARD & KONTEN === */
    .cyber-anim-container {
        position: relative;
        z-index: 10;
    }

    .cyber-card {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        padding: 2.5rem;
        position: relative;
        overflow: hidden;
        cursor: pointer;
        transition: border-color 0.3s ease;
    }
    
    .cyber-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 3px;
        background: linear-gradient(90deg, var(--accent-cyan), var(--accent-blue));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.5s ease;
    }

    .cyber-card:hover::before {
        transform: scaleX(1);
    }
    
    .clock-display {
        font-size: clamp(3rem, 5vw, 4.5rem); /* Sedikit diperbesar karena font digital cenderung lebih ramping */
        font-weight: normal; /* Font digital lebih bagus tanpa bold paksaan */
        color: var(--accent-cyan);
        font-family: 'digital', 'Courier New', Courier, monospace; /* Terapkan font digital di sini */
        line-height: 1;
        letter-spacing: 2px; /* Memberi jarak agar mirip layar kalkulator asli */
    }
    
    .date-display {
        font-size: 1.1rem;
        color: #94A3B8;
        text-transform: capitalize;
        margin-top: 0.5rem;
    }
    
    .motivation-text {
        font-style: italic;
        color: #E2E8F0;
        border-left: 4px solid var(--accent-blue);
        padding-left: 1.2rem;
        line-height: 1.6;
    }
</style>

<div class="cyber-grid-bg"></div>
<div class="cyber-grid-overlay"></div>
<div id="particles-container"></div> 

<div class="row cyber-anim-container">
    <div class="col-12 mb-4 cyber-header">
        <h2 class="fw-bold" style="color: var(--text-primary);">
            Selamat Datang, <span class="text-gradient">{{ Auth::user()->username ?? 'Thomas' }}</span>!
        </h2>
        <p class="text-secondary">Monitor sistem dan kelola partner Anda dari panel kendali ini.</p>
    </div>

    <div class="col-lg-6 mb-4 cyber-item">
        <div class="cyber-card h-100 d-flex flex-column justify-content-center">
            <h5 class="mb-3 text-uppercase" style="color: var(--accent-blue); font-size: 0.85rem; letter-spacing: 2px; font-weight: 600;">
                Waktu Sistem (Asia/Jakarta)
            </h5>
            <div class="clock-display" id="live-time">00:00:00</div>
            <div class="date-display" id="live-date">Memuat kalender...</div>
        </div>
    </div>

    <div class="col-lg-6 mb-4 cyber-item">
        <div class="cyber-card h-100 d-flex flex-column justify-content-center">
            <h5 class="mb-3 text-uppercase" style="color: var(--accent-blue); font-size: 0.85rem; letter-spacing: 2px; font-weight: 600;">
                Daily Insight
            </h5>
            <div class="motivation-text fs-5">
                "Inovasi membedakan antara seorang pemimpin dan pengikut. Teruslah membangun ekosistem digital F&B yang luar biasa bersama Flavory.id."
            </div>
        </div>
    </div>
</div>
@endsection

@stack('scripts')
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // ==========================================
        // 1. LOGIKA JAM REAL-TIME
        // ==========================================
        function updateClock() {
            const now = new Date();
            const timeOptions = { timeZone: 'Asia/Jakarta', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
            const dateOptions = { timeZone: 'Asia/Jakarta', weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

            const formatterTime = new Intl.DateTimeFormat('id-ID', timeOptions);
            const formatterDate = new Intl.DateTimeFormat('id-ID', dateOptions);

            document.getElementById('live-time').innerText = formatterTime.format(now).replace(/\./g, ':');
            document.getElementById('live-date').innerText = formatterDate.format(now);
        }
        setInterval(updateClock, 1000);
        updateClock();

        // ==========================================
        // 2. GENERATE PARTIKEL BACKGROUND (INTERAKTIF)
        // ==========================================
        const particleCount = 30;
        const container = document.getElementById('particles-container');
        
        for (let i = 0; i < particleCount; i++) {
            let p = document.createElement('div');
            p.classList.add('cyber-particle');
            
            // Ukuran dan posisi acak
            let size = Math.random() * 4 + 1;
            p.style.width = size + 'px';
            p.style.height = size + 'px';
            p.style.left = Math.random() * 100 + 'vw';
            p.style.top = Math.random() * 100 + 'vh';
            
            container.appendChild(p);
        }

        // Animasi partikel mengambang acak
        anime({
            targets: '.cyber-particle',
            translateX: () => anime.random(-50, 50),
            translateY: () => anime.random(-50, 50),
            scale: () => anime.random(0.5, 2),
            opacity: () => anime.random(0.1, 0.6),
            duration: () => anime.random(3000, 7000),
            delay: anime.stagger(200),
            direction: 'alternate',
            loop: true,
            easing: 'easeInOutSine'
        });

        // Efek Parallax Halus saat Mouse Bergerak
        document.addEventListener('mousemove', (e) => {
            const mouseX = (e.clientX / window.innerWidth) * 2 - 1;
            const mouseY = (e.clientY / window.innerHeight) * 2 - 1;

            anime({
                targets: '.cyber-particle',
                translateX: `+=${mouseX * 2}`,
                translateY: `+=${mouseY * 2}`,
                duration: 1000,
                easing: 'easeOutExpo'
            });
        });

        // ==========================================
        // 3. ANIMASI ELEMEN UI UTAMA
        // ==========================================
        
        // Animasi Masuk (Entrance) Header
        anime({
            targets: '.cyber-header h2, .cyber-header p',
            translateY: [20, 0],
            opacity: [0, 1],
            delay: anime.stagger(200),
            easing: 'easeOutQuint',
            duration: 1000
        });

        // Animasi Masuk (Entrance) Card
        anime({
            targets: '.cyber-item',
            translateY: [40, 0],
            opacity: [0, 1],
            delay: anime.stagger(150, {start: 300}),
            easing: 'easeOutElastic(1, .6)',
            duration: 1500
        });

        // Efek Pulse Neon pada Jam Digital
        anime({
            targets: '.clock-display',
            textShadow: [
                '0 0 10px rgba(0, 229, 255, 0.2)',
                '0 0 30px rgba(0, 229, 255, 0.8)',
                '0 0 10px rgba(0, 229, 255, 0.2)'
            ],
            loop: true,
            easing: 'easeInOutSine',
            duration: 2500
        });

        // ==========================================
        // 4. ANIMASI HOVER CARD (Interaktif)
        // ==========================================
        const cards = document.querySelectorAll('.cyber-card');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                anime({
                    targets: card,
                    scale: 1.03,
                    translateY: -8,
                    boxShadow: '0 12px 40px rgba(0, 229, 255, 0.2)',
                    duration: 400,
                    easing: 'easeOutExpo'
                });
            });

            card.addEventListener('mouseleave', () => {
                anime({
                    targets: card,
                    scale: 1,
                    translateY: 0,
                    boxShadow: '0 8px 32px rgba(0, 0, 0, 0.3)',
                    duration: 600,
                    easing: 'easeOutElastic(1, .8)'
                });
            });
        });

    });
</script>
@endpush