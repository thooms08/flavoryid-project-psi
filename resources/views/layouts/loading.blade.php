{{-- views/layouts/loading.blade.php --}}
{{-- Di-include di dalam <body> layouts/app.blade.php — partial, bukan standalone --}}

<div id="loading_mask" aria-hidden="true">
    <div id="loading_inner">

        <svg id="loading-logo" viewBox="0 0 200 220" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Flavory">
            <path class="f-part f-stem"
                d="M 38 30 C 38 18, 48 12, 58 12 L 58 208 C 58 208, 38 208, 38 195 Z"
                fill="white"/>
            <path class="f-part f-top"
                d="M 54 14 L 148 14 C 182 14, 190 30, 182 52 C 174 72, 148 80, 120 80
                   L 58 80 L 58 55 L 112 55 C 134 55, 155 50, 160 38
                   C 165 26, 152 36, 130 36 L 58 36 L 58 14 Z"
                fill="white"/>
            <path class="f-part f-mid"
                d="M 58 88 L 130 88 C 158 88, 168 100, 162 118
                   C 156 136, 136 144, 112 144 L 58 144 L 58 120
                   L 104 120 C 122 120, 138 116, 142 106
                   C 146 96, 134 110, 116 110 L 58 110 Z"
                fill="white"/>
        </svg>

        <span class="loading-wordmark">flavory.id</span>
        <span class="loading-dots">
            <span></span><span></span><span></span>
        </span>
    </div>
</div>

<style>
    /* ══ Backdrop semi-transparan ══ */
    #loading_mask {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.35);
        backdrop-filter: blur(3px);
        -webkit-backdrop-filter: blur(3px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 99999;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity .3s ease, visibility .3s;
    }

    /* State visible: muncul */
    #loading_mask.visible {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    /* State hidden: fade out (dipakai saat dismiss) */
    #loading_mask.hiding {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }

    /* ══ Kotak oranye di tengah ══ */
    #loading_inner {
        background: #FF6600;
        border-radius: 28px;
        width: 160px;
        height: 160px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 20px 60px rgba(255,102,0,.45), 0 8px 24px rgba(0,0,0,.25);
        animation: card-in .35s cubic-bezier(.34,1.56,.64,1) both;
    }

    @keyframes card-in {
        from { opacity: 0; transform: scale(.72); }
        to   { opacity: 1; transform: scale(1);   }
    }

    /* ══ Logo ══ */
    #loading-logo {
        width: 68px;
        height: auto;
        animation: logo-pulse 1.6s ease-in-out infinite;
    }
    .f-part { opacity: 0; animation: part-in .4s ease forwards; }
    .f-stem { animation-delay: .05s; }
    .f-top  { animation-delay: .18s; }
    .f-mid  { animation-delay: .32s; }

    @keyframes part-in {
        from { opacity: 0; transform: translateX(-8px); }
        to   { opacity: 1; transform: translateX(0);    }
    }
    @keyframes logo-pulse {
        0%, 100% { transform: scale(1);    }
        50%       { transform: scale(1.08); }
    }

    /* ══ Wordmark ══ */
    .loading-wordmark {
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        font-size: .9rem;
        letter-spacing: 3px;
        color: rgba(255,255,255,.9);
        text-transform: lowercase;
        animation: fade-up .4s .5s ease both;
    }

    /* ══ Titik bouncing ══ */
    .loading-dots { display: flex; gap: 4px; animation: fade-up .4s .6s ease both; }
    .loading-dots span {
        width: 5px; height: 5px;
        background: rgba(255,255,255,.85);
        border-radius: 50%;
        display: inline-block;
        animation: dot-bounce 1.1s ease-in-out infinite;
    }
    .loading-dots span:nth-child(2) { animation-delay: .18s; }
    .loading-dots span:nth-child(3) { animation-delay: .36s; }

    @keyframes dot-bounce {
        0%, 80%, 100% { transform: translateY(0);   opacity: .45; }
        40%            { transform: translateY(-6px); opacity: 1;   }
    }
    @keyframes fade-up {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0);   }
    }
</style>

<script>
(function () {
    var mask   = document.getElementById('loading_mask');
    var _timer = null;

    /* ─── API publik global ─────────────────────────────── */

    /**
     * Tampilkan loading.
     * Dipanggil otomatis oleh sistem, atau manual: window.showLoading()
     */
    window.showLoading = function () {
        if (!mask) return;
        // Reset animasi inner agar card-in jalan ulang
        var inner = document.getElementById('loading_inner');
        if (inner) {
            inner.style.animation = 'none';
            // Trigger reflow untuk restart animation
            void inner.offsetHeight;
            inner.style.animation = '';
        }
        // Reset animasi per-bagian F
        document.querySelectorAll('.f-part').forEach(function(el) {
            el.style.animation = 'none';
            void el.offsetHeight;
            el.style.animation = '';
        });

        mask.classList.remove('hiding');
        mask.classList.add('visible');

        // Fallback paksa hide setelah 8 detik (antisipasi fetch yang hang)
        clearTimeout(_timer);
        _timer = setTimeout(function() { window.hideLoading(); }, 8000);
    };

    /**
     * Sembunyikan loading.
     * Dipanggil otomatis, atau manual: window.hideLoading()
     */
    window.hideLoading = function () {
        if (!mask) return;
        clearTimeout(_timer);
        mask.classList.remove('visible');
        mask.classList.add('hiding');
    };

    /* ─── 1. Muncul saat halaman pertama dibuka ────────── */
    window.showLoading();

    if (document.readyState === 'complete') {
        setTimeout(window.hideLoading, 350);
    } else {
        window.addEventListener('load', function () {
            setTimeout(window.hideLoading, 250);
        });
    }

    /* ─── 2. Muncul saat navigasi antar halaman (klik link) ── */
    document.addEventListener('click', function (e) {
        // Cari elemen <a> terdekat dari target klik
        var anchor = e.target.closest('a[href]');
        if (!anchor) return;

        var href = anchor.getAttribute('href');

        // Abaikan: link kosong, hash, javascript:, target blank, download
        if (
            !href ||
            href === '#' ||
            href.startsWith('#') ||
            href.startsWith('javascript') ||
            href.startsWith('mailto') ||
            href.startsWith('tel') ||
            anchor.getAttribute('target') === '_blank' ||
            anchor.hasAttribute('download') ||
            e.ctrlKey || e.metaKey || e.shiftKey  // buka tab baru
        ) return;

        window.showLoading();
    });

    /* ─── 3. Muncul saat form di-submit ────────────────── */
    document.addEventListener('submit', function (e) {
        var form = e.target;

        // Abaikan form AJAX (yang punya data-ajax atau data-no-loading)
        if (
            form.hasAttribute('data-ajax') ||
            form.hasAttribute('data-no-loading')
        ) return;

        window.showLoading();
    });

    /* ─── 4. Muncul saat browser navigasi (tombol back/forward) ── */
    window.addEventListener('beforeunload', function () {
        window.showLoading();
    });

    /* ─── 5. Hide saat halaman sudah siap (kembali dari navigasi) ── */
    window.addEventListener('pageshow', function (e) {
        // pageshow dipanggil saat halaman diambil dari bfcache (tombol back)
        if (e.persisted) {
            window.hideLoading();
        }
    });

})();
</script>
