{{-- resources/views/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Aplikasi Kasir All-in-One Terbaik & Manajemen Bisnis Restoran | Flavory.id')
@section('meta_description', 'Tingkatkan keuntungan dengan Flavory.id, aplikasi kasir all-in-one dan manajemen bisnis restoran terbaik. Dilengkapi FlavoryAI, analisa performa bisnis, dan POS kuliner lengkap.')
@section('meta_og_type', 'website')
@section('meta_og_url', url('/'))
@section('meta_image', secure_url('assets/preview.jpg'))

@push('styles')
<style>
    :root {
        --primary-orange: #ff6a00;
        --light-orange:   #fff4ec;
        --text-muted:     #64748b;
    }
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
    .text-orange { color: var(--primary-orange) !important; }
</style>
@endpush

@section('content')

{{-- Robot FlavoryAI --}}
<x-robot
    greeting="Halo! Aku FlavoryAI — ada yang bisa kubantu?"
    :interval="60"
    :delay="1.2"
    size="clamp(140px, 18vw, 240px)"
/>

<main>
    @include('section.hero')
    @include('section.tentang')
    @include('section.flavoryai')
    @include('section.paket')
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
                background: linear-gradient(135deg, #ff6a00, #ff9a00);
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

        {{-- Divider --}}
        <div style="display:flex; align-items:center; gap:10px; margin: 2px 0;">
            <div style="flex:1; height:1px; background: rgba(0,0,0,0.07);"></div>
            <span style="font-size:0.68rem; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px;">atau</span>
            <div style="flex:1; height:1px; background: rgba(0,0,0,0.07);"></div>
        </div>

        {{-- Pilihan 3: Login owner --}}
        <a href="{{ route('owner.login') }}"
           style="
               display:flex; align-items:center; gap:14px;
               padding:16px 18px; border-radius:16px;
               background: #fff; border: 1.5px solid rgba(100,116,139,0.2);
               box-shadow: 0 4px 14px rgba(0,0,0,0.04);
               text-decoration:none; transition: transform .2s, box-shadow .2s, border-color .2s;
           "
           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 22px rgba(100,116,139,0.14)'; this.style.borderColor='rgba(100,116,139,0.45)';"
           onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 14px rgba(0,0,0,0.04)'; this.style.borderColor='rgba(100,116,139,0.2)';">
            <div style="width:40px; height:40px; border-radius:10px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="bi bi-person-badge-fill" style="color:#475569; font-size:1.1rem;"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:0.9rem; font-weight:800; color:#1e293b; margin-bottom:2px;">Log-In sebagai Manager / Owner</div>
                <div style="font-size:0.73rem; color:#64748b;">Akses dasbor laporan & manajemen bisnis</div>
            </div>
            <i class="bi bi-arrow-right" style="color:#475569; font-size:1rem;"></i>
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
    document.getElementById('modalCobaGratis').addEventListener('click', function(e) {
        const rect = this.getBoundingClientRect();
        const inside = e.clientX >= rect.left && e.clientX <= rect.right
                    && e.clientY >= rect.top  && e.clientY <= rect.bottom;
        if (!inside) this.close();
    });
</script>

<script src="{{ asset('js/robot.js') }}" defer></script>
<script>
    window.FlavoryRobotConfig = {
        greeting: 'Halo! Aku FlavoryAI — senang bertemu denganmu!',
        interval: 60,
        firstDelay: 1.2,
        timing: { greetHold: 4000, dash: 1100 }
    };
</script>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    // ═══════════════════════════════════════════════════════
    //  WORD BLUR REVEAL  +  ROTATING PHRASES  — looping
    // ═══════════════════════════════════════════════════════

    const col      = document.querySelector('.hero-reveal-col');
    const badge    = col && col.querySelector('.hero-badge-wrap');
    const subtitle = col && col.querySelector('.hero-subtitle');
    const ctaWrap  = col && col.querySelector('.hero-cta');
    const rotLine  = col && col.querySelector('#hero-rotating-line');

    if (!col) return;

    const PHRASES = [
        'Bangun Bisnis Kuliner Bersama <span style="color:inherit">Flavory.id</span>',
        'Kelola Pesanan Lebih Cepat &amp; Mudah',
        'Pantau Keuangan Secara Real-Time',
        '<span style="background:linear-gradient(135deg,#ff6a00,#ff9a00);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">FlavoryAI</span> &ndash; Konsultan Bisnis AI Kamu',
    ];
    let phraseIndex = 0;

    function renderPhrase(html) {
        rotLine.innerHTML = '';
        const tmp = document.createElement('span');
        tmp.innerHTML = html;

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
        setTimeout(cb, (words.length - 1) * 35 + 300);
    }

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

    wrapWords(subtitle);
    ctaWrap.querySelectorAll('a, button').forEach(a => a.classList.add('w'));

    // Wave badge
    (function initWaveBadge() {
        const badgeText = badge && badge.querySelector('.hero-badge-text');
        if (!badgeText) return;
        const chars = badgeText.textContent;
        badgeText.textContent = '';
        chars.split('').forEach((ch, i) => {
            const s = document.createElement('span');
            s.className = 'wave-char' + (ch === ' ' ? ' space' : '');
            s.textContent = ch === ' ' ? '\u00A0' : ch;
            s.style.animationDelay = (i * 0.055) + 's';
            badgeText.appendChild(s);
        });
    })();

    const staticWords = [
        badge,
        ...col.querySelectorAll('.hero-title > .w'),
        ...col.querySelectorAll('.hero-subtitle .w'),
        ...col.querySelectorAll('.hero-cta .w'),
    ].filter(Boolean);

    const IN_STAG = 55;
    const IN_DUR  = 500;
    const PAUSE   = 3500;

    function totalEnterMs() { return (staticWords.length - 1) * IN_STAG + IN_DUR; }

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

    function runLoop() {
        renderPhrase(PHRASES[phraseIndex]);
        phraseIndex = (phraseIndex + 1) % PHRASES.length;

        setTimeout(() => {
            enterStaticOnce();
            rotWordsIn();

            setTimeout(() => {
                rotWordsOut(() => {
                    setTimeout(runLoop, 80);
                });
            }, totalEnterMs() + PAUSE);
        }, 100);
    }

    runLoop();

    // ═══════════════════════════════════════════════════════
    //  SCROLL ANIMATION
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

    // ═══════════════════════════════════════════════════════
    //  POPUP EVENT DINAMIS
    // ═══════════════════════════════════════════════════════
    (function () {
        const overlay = document.getElementById('eventPopupOverlay');
        if (!overlay) return;

        const STORAGE_KEY = 'flavory_popup_dismissed';
        const now         = Date.now();
        const dismissed   = localStorage.getItem(STORAGE_KEY);

        if (dismissed && (now - parseInt(dismissed, 10)) < 86400000) return;

        const showTimer = setTimeout(() => {
            overlay.style.display = 'flex';
        }, 1200);

        const closeBtn = document.getElementById('eventPopupClose');
        function closePopup() {
            overlay.style.opacity = '0';
            overlay.style.transition = 'opacity .25s';
            setTimeout(() => { overlay.style.display = 'none'; }, 250);
            localStorage.setItem(STORAGE_KEY, now.toString());
        }

        if (closeBtn) closeBtn.addEventListener('click', closePopup);
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) closePopup();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && overlay.style.display !== 'none') closePopup();
        });
    })();
});
</script>
@endpush
