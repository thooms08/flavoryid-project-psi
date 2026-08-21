/**
 * loading.js — Flavory.id
 * Loading overlay modern bertema oranye dengan logo "F" Flavory.id.
 * Pure CSS + Vanilla JS — tanpa library eksternal.
 * API: window.showLoading(label?) / window.hideLoading()
 */
(function () {
  'use strict';

  /* ─────────────────────────────────────────
     1. CSS
  ───────────────────────────────────────── */
  const STYLE_ID = '__fl_loading_style__';
  if (!document.getElementById(STYLE_ID)) {
    const style = document.createElement('style');
    style.id = STYLE_ID;
    style.textContent = `
      #fl-loading-overlay {
        position: fixed;
        inset: 0;
        z-index: 99999;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.25s ease, visibility 0.25s ease;
        pointer-events: none;
      }
      #fl-loading-overlay.fl-active {
        opacity: 1;
        visibility: visible;
        pointer-events: all;
      }

      /* Card */
      .fl-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
        background: #ffffff;
        border-radius: 28px;
        padding: 40px 52px;
        box-shadow:
          0 0 0 1px rgba(255,106,0,0.08),
          0 8px 32px rgba(255,106,0,0.12),
          0 24px 64px rgba(0,0,0,0.06);
      }

      /* Logo wrapper: ring berputar + logo di tengah */
      .fl-logo-wrap {
        position: relative;
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      /* Ring luar berputar */
      .fl-ring {
        position: absolute;
        inset: 0;
        border-radius: 50%;
        border: 3px solid #ffe0c2;
        border-top-color: #ff6a00;
        animation: fl-spin 0.9s linear infinite;
        will-change: transform;
      }

      /* Logo SVG di tengah ring */
      .fl-logo-svg {
        width: 46px;
        height: 46px;
        animation: fl-breathe 2s ease-in-out infinite;
        will-change: transform;
        filter: drop-shadow(0 3px 8px rgba(255,106,0,0.3));
      }

      /* Tiga dot */
      .fl-dots {
        display: flex;
        gap: 8px;
        align-items: center;
      }
      .fl-dots span {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #ff6a00;
        animation: fl-bounce 1.2s ease-in-out infinite;
        will-change: transform;
      }
      .fl-dots span:nth-child(2) { animation-delay: 0.18s; background: #ff8533; }
      .fl-dots span:nth-child(3) { animation-delay: 0.36s; background: #ffaa66; }

      /* Label */
      .fl-label {
        font-family: 'Poppins', system-ui, sans-serif;
        font-size: 12.5px;
        font-weight: 600;
        color: #cc4400;
        letter-spacing: 0.5px;
        animation: fl-fade-pulse 2s ease-in-out infinite;
      }

      /* Progress bar atas halaman */
      #fl-progress-bar {
        position: fixed;
        top: 0; left: 0;
        height: 3px;
        width: 0%;
        background: linear-gradient(90deg, #ff6a00, #ffaa44);
        z-index: 100000;
        opacity: 0;
        border-radius: 0 3px 3px 0;
        pointer-events: none;
        box-shadow: 0 0 8px rgba(255,106,0,0.5);
        transition: width 0.35s ease, opacity 0.25s ease;
      }

      @keyframes fl-spin {
        to { transform: rotate(360deg); }
      }
      @keyframes fl-breathe {
        0%, 100% { transform: scale(1);    }
        50%       { transform: scale(1.1); }
      }
      @keyframes fl-bounce {
        0%, 80%, 100% { transform: translateY(0);    opacity: 0.4; }
        40%           { transform: translateY(-7px); opacity: 1;   }
      }
      @keyframes fl-fade-pulse {
        0%, 100% { opacity: 0.65; }
        50%       { opacity: 1;   }
      }
    `;
    document.head.appendChild(style);
  }

  /* ─────────────────────────────────────────
     2. SVG logo "F" Flavory.id
        Satu path bersih: batang vertikal +
        dua bar horizontal melengkung ke kanan
        persis seperti logo asli.
  ───────────────────────────────────────── */
  const FL_SVG = `<svg class="fl-logo-svg" viewBox="0 0 100 130" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <!-- Batang vertikal -->
    <rect x="10" y="10" width="22" height="115" rx="11" fill="#ff6a00"/>
    <!-- Bar atas: melengkung ke kanan lalu memutar balik (dua lengkung logo F) -->
    <!-- Arm atas -->
    <path d="M 21 10 L 68 10 Q 90 10 90 28 Q 90 46 68 46 L 32 46 Q 22 46 22 58 L 22 46 L 21 46 Z" fill="#ff6a00"/>
    <!-- Arm tengah -->
    <path d="M 21 58 L 58 58 Q 78 58 78 74 Q 78 90 58 90 L 32 90 Q 22 90 22 102 L 22 90 L 21 90 Z" fill="#ff6a00"/>
  </svg>`;

  /* ─────────────────────────────────────────
     3. Bangun overlay
  ───────────────────────────────────────── */
  function buildOverlay() {
    const el = document.createElement('div');
    el.id = 'fl-loading-overlay';
    el.setAttribute('role', 'status');
    el.setAttribute('aria-live', 'polite');
    el.setAttribute('aria-label', 'Memuat…');
    el.innerHTML = `
      <div class="fl-card">
        <div class="fl-logo-wrap">
          <div class="fl-ring"></div>
          ${FL_SVG}
        </div>
        <div class="fl-dots">
          <span></span><span></span><span></span>
        </div>
        <span class="fl-label">Memuat…</span>
      </div>`;
    document.body.appendChild(el);
    return el;
  }

  /* ─────────────────────────────────────────
     4. Progress bar
  ───────────────────────────────────────── */
  function buildProgressBar() {
    const bar = document.createElement('div');
    bar.id = 'fl-progress-bar';
    document.body.appendChild(bar);
    return bar;
  }

  function getOverlay() {
    return document.getElementById('fl-loading-overlay') || buildOverlay();
  }
  function getBar() {
    return document.getElementById('fl-progress-bar') || buildProgressBar();
  }

  /* ─────────────────────────────────────────
     5. State
  ───────────────────────────────────────── */
  let _hideTimer  = null;
  let _barTimer   = null;
  let _refCount   = 0;
  let _barWidth   = 0;

  function startBar() {
    const bar = getBar();
    _barWidth = 8;
    bar.style.opacity = '1';
    bar.style.width   = _barWidth + '%';
    clearInterval(_barTimer);
    _barTimer = setInterval(() => {
      if (_barWidth < 88) {
        _barWidth += Math.random() * 6 + 1;
        bar.style.width = Math.min(_barWidth, 88) + '%';
      }
    }, 300);
  }

  function finishBar() {
    clearInterval(_barTimer);
    const bar = getBar();
    bar.style.width = '100%';
    setTimeout(() => {
      bar.style.opacity = '0';
      setTimeout(() => { bar.style.width = '0%'; }, 300);
    }, 300);
  }

  /* ─────────────────────────────────────────
     6. API publik
  ───────────────────────────────────────── */
  window.showLoading = function (label) {
    clearTimeout(_hideTimer);
    _refCount++;
    const overlay = getOverlay();
    const lbl = overlay.querySelector('.fl-label');
    if (lbl) lbl.textContent = label || 'Memuat…';
    overlay.classList.add('fl-active');
    startBar();
  };

  window.hideLoading = function () {
    _refCount = Math.max(0, _refCount - 1);
    if (_refCount > 0) return;
    finishBar();
    _hideTimer = setTimeout(() => {
      const ov = document.getElementById('fl-loading-overlay');
      if (ov) ov.classList.remove('fl-active');
    }, 200);
  };

  /* ─────────────────────────────────────────
     7. Auto-trigger navigasi
  ───────────────────────────────────────── */
  document.addEventListener('DOMContentLoaded', function () {

    document.addEventListener('click', function (e) {
      const a = e.target.closest('a[href]');
      if (!a) return;
      const href = a.getAttribute('href') || '';
      if (
        a.target === '_blank'             ||
        href.startsWith('#')              ||
        href.startsWith('javascript:')   ||
        href.startsWith('mailto:')        ||
        href.startsWith('tel:')           ||
        a.hasAttribute('data-bs-toggle') ||
        a.hasAttribute('data-bs-dismiss')
      ) return;
      if (_refCount === 0) window.showLoading('Memuat halaman…');
    });

    document.addEventListener('submit', function (e) {
      if (e.target && e.target.hasAttribute('data-no-loading')) return;
      if (_refCount === 0) window.showLoading('Memproses…');
    });

    window.addEventListener('pageshow', function (e) {
      if (e.persisted) { _refCount = 0; window.hideLoading(); }
    });
  });

  /* ─────────────────────────────────────────
     8. Hilang saat halaman selesai load
  ───────────────────────────────────────── */
  window.addEventListener('load', function () {
    setTimeout(() => {
      _refCount = 0;
      finishBar();
      const ov = document.getElementById('fl-loading-overlay');
      if (ov) ov.classList.remove('fl-active');
    }, 200);
  });

})();
