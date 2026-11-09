<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title', 'Dashboard Owner') | Flavory.id</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @include('layouts.favicon')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            background: rgba(255,255,255,0.88);
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

        /* ─── User Dropdown ──────────────────────────────────────────── */
        .user-dropdown { position: relative; }
        .user-dropdown-trigger {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 6px 12px 6px 6px;
            border-radius: 50px;
            border: 1.5px solid transparent;
            transition: all 0.2s ease;
        }
        .user-dropdown-trigger:hover {
            background: rgba(255,106,0,0.05);
            border-color: rgba(255,106,0,0.15);
        }
        .user-dropdown.open .user-dropdown-trigger {
            background: rgba(255,106,0,0.08);
            border-color: rgba(255,106,0,0.2);
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
        .topbar-user { display: flex; align-items: center; gap: 8px; }
        .topbar-user-info { display: flex; flex-direction: column; line-height: 1.2; }
        .topbar-user-info .name { font-size: 0.82rem; font-weight: 700; color: var(--text); }
        .topbar-user-info .role { font-size: 0.68rem; font-weight: 600; color: var(--muted); }
        .dropdown-arrow { font-size: 0.7rem; color: var(--muted); transition: transform 0.2s ease; }
        .user-dropdown.open .dropdown-arrow { transform: rotate(180deg); }

        .user-dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: white;
            border-radius: 16px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.12);
            border: 1px solid var(--border);
            min-width: 220px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            z-index: 1000;
            overflow: hidden;
        }
        .user-dropdown.open .user-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .dropdown-menu-header {
            padding: 16px 18px;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(135deg, rgba(255,106,0,0.03), rgba(255,154,0,0.02));
        }
        .dropdown-menu-header .name { font-size: 0.9rem; font-weight: 700; color: var(--text); margin-bottom: 2px; }
        .dropdown-menu-header .role { font-size: 0.72rem; font-weight: 600; color: var(--muted); }
        .dropdown-menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            color: var(--text);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.15s ease;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }
        .dropdown-menu-item:hover { background: rgba(255,106,0,0.05); color: var(--orange); }
        .dropdown-menu-item i { font-size: 1.05rem; width: 20px; text-align: center; }
        .dropdown-menu-item.active-item { color: var(--orange); background: rgba(255,106,0,0.06); }
        .dropdown-menu-item.logout-item { color: #ef4444; border-top: 1px solid var(--border); }
        .dropdown-menu-item.logout-item:hover { background: #fef2f2; color: #dc2626; }

        /* ─── Bottom bar (mobile only) ───────────────────────────────── */
        .bottom-bar { display: none; }

        @media (max-width: 768px) {
            .topbar { justify-content: center; }
            .topbar-right { display: none; }
            body { padding-bottom: calc(76px + env(safe-area-inset-bottom, 0px)); }
            .bottom-bar {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 4px;
                position: fixed;
                left: 0; right: 0; bottom: 0;
                z-index: 100;
                padding: 8px 10px calc(8px + env(safe-area-inset-bottom, 0px));
                background: rgba(255,255,255,0.92);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border-top: 1px solid var(--border);
                box-shadow: 0 -8px 28px rgba(0,0,0,0.06);
            }
            .bottom-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 3px;
                padding: 7px 4px;
                border: 0;
                background: none;
                border-radius: 14px;
                font: inherit;
                font-size: 0.68rem;
                font-weight: 600;
                color: var(--muted);
                text-decoration: none;
                cursor: pointer;
                -webkit-tap-highlight-color: transparent;
                transition: background .15s ease, color .15s ease;
            }
            .bottom-item i { font-size: 1.3rem; line-height: 1; transition: transform .2s ease; }
            .bottom-item:active { background: rgba(255,106,0,0.1); }
            .bottom-item:active i { transform: scale(.88); }
            .bottom-item.active { color: var(--orange); background: rgba(255,106,0,0.08); }
            .bottom-item.logout { color: #ef4444; }
            .bottom-item.logout:active { background: #fef2f2; }
            .bottom-item:focus-visible { outline: 2px solid var(--orange); outline-offset: -2px; }
        }

        @media (max-width: 600px) {
            .topbar { padding: 0 16px; }
            .topbar-user-info { display: none; }
        }

    </style>
    @yield('styles')
</head>
<body>

{{-- SVG defs: gradient kepala robot ─────────────────────────────────────── --}}
<svg width="0" height="0" style="position:absolute;overflow:hidden" aria-hidden="true" focusable="false">
    <defs>
        <linearGradient id="fa-g-head" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="#FFB460"/>
            <stop offset="1" stop-color="#F26A0F"/>
        </linearGradient>
    </defs>
</svg>

{{-- Template wajah robot (di-hydrate via JS) ─────────────────────────────── --}}
<template id="tplRobotFace">
    <svg class="fa-svg" viewBox="18 0 164 134" xmlns="http://www.w3.org/2000/svg" focusable="false" aria-hidden="true">
        <g class="fa-tilt">
            <g class="fa-head">
                <rect x="20" y="70" width="20" height="34" rx="10" fill="#FFF4E8"/>
                <rect class="fa-ear-in" x="25" y="80" width="8" height="14" rx="4" fill="#F26A0F"/>
                <rect x="160" y="70" width="20" height="34" rx="10" fill="#FFF4E8"/>
                <rect class="fa-ear-in" x="167" y="80" width="8" height="14" rx="4" fill="#F26A0F"/>
                <rect x="97" y="18" width="6" height="20" rx="3" fill="#3A2316"/>
                <circle class="fa-orb-glow" cx="100" cy="14" r="13" fill="#FFD27A"/>
                <circle cx="100" cy="14" r="8" fill="#FFD27A"/>
                <circle cx="97.5" cy="11.5" r="2.5" fill="#fff"/>
                <rect x="34" y="34" width="132" height="98" rx="44" fill="url(#fa-g-head)"/>
                <path d="M52 52 Q60 42 82 40" fill="none" stroke="#fff" stroke-opacity=".45" stroke-width="5" stroke-linecap="round"/>
                <rect x="46" y="48" width="108" height="70" rx="32" fill="#2A1810"/>
                <rect x="46" y="48" width="108" height="70" rx="32" fill="none" stroke="#FFB067" stroke-opacity=".35" stroke-width="2"/>
                <path d="M62 56 Q76 50 96 52" fill="none" stroke="#fff" stroke-opacity=".12" stroke-width="4" stroke-linecap="round"/>
                <path class="fa-brow" d="M67 68 L89 60"/>
                <path class="fa-brow" d="M133 68 L111 60"/>
                <g class="fa-eyes">
                    <g class="fa-eye"><ellipse cx="78" cy="78" rx="9" ry="12"/><circle class="fa-eye-hl" cx="81" cy="73" r="3"/></g>
                    <g class="fa-eye"><ellipse cx="122" cy="78" rx="9" ry="12"/><circle class="fa-eye-hl" cx="125" cy="73" r="3"/></g>
                </g>
                <path class="fa-arc" d="M68 82 Q78 69 88 82"/>
                <path class="fa-arc" d="M112 82 Q122 69 132 82"/>
                <g class="fa-cheek">
                    <ellipse cx="63" cy="101" rx="10" ry="6" fill="#FF6F8A"/>
                    <ellipse cx="137" cy="101" rx="10" ry="6" fill="#FF6F8A"/>
                </g>
                <path class="fa-mouth fa-mouth--smile"  d="M88 98 Q100 110 112 98"/>
                <path class="fa-mouth fa-mouth--happy"  d="M88 96 Q100 116 112 96 Z"/>
                <path class="fa-mouth fa-mouth--think"  d="M92 104 L108 104"/>
                <ellipse class="fa-mouth fa-mouth--listen" cx="100" cy="104" rx="5.5" ry="7"/>
                <path class="fa-mouth fa-mouth--sad"    d="M90 108 Q100 98 110 108"/>
                <path class="fa-tear" d="M70 94 Q74 101 70 105 Q66 101 70 94 Z" fill="#8FD8FF"/>
            </g>
        </g>
    </svg>
</template>

{{-- Topbar ───────────────────────────────────────────────────────────────── --}}
<header class="topbar">
    <a href="{{ route('owner.dashboard') }}" class="topbar-brand">
        <img src="{{ asset('logo-512.png') }}" alt="Flavory.id" class="topbar-brand-logo" onerror="this.style.display='none'">
        <span class="topbar-brand-name">Flavory<span>.id</span></span>
    </a>

    <div class="topbar-right">
        <div class="user-dropdown" id="userDropdown">
            <div class="user-dropdown-trigger">
                <div class="topbar-user">
                    <div class="topbar-avatar">
                        {{ strtoupper(substr(Auth::user()->username ?? 'O', 0, 1)) }}
                    </div>
                    <div class="topbar-user-info">
                        <span class="name">{{ Auth::user()->username }}</span>
                        <span class="role">Owner</span>
                    </div>
                </div>
                <i class="bi bi-chevron-down dropdown-arrow"></i>
            </div>
            <div class="user-dropdown-menu">
                <div class="dropdown-menu-header">
                    <div class="name">{{ Auth::user()->username }}</div>
                    <div class="role">Owner / Manager</div>
                </div>
                <a href="{{ route('owner.dashboard') }}"
                   class="dropdown-menu-item {{ request()->routeIs('owner.dashboard') ? 'active-item' : '' }}">
                    <i class="bi bi-house-door-fill"></i><span>Dashboard</span>
                </a>
                <a href="{{ route('owner.langganan.index') }}"
                   class="dropdown-menu-item {{ request()->routeIs('owner.langganan.*') ? 'active-item' : '' }}">
                    <i class="bi bi-patch-check-fill"></i><span>Langganan</span>
                </a>
                <a href="{{ route('owner.akun.index') }}"
                   class="dropdown-menu-item {{ request()->routeIs('owner.akun.*') ? 'active-item' : '' }}">
                    <i class="bi bi-person-circle"></i><span>Akun</span>
                </a>
                <button type="button" class="dropdown-menu-item logout-item js-logout">
                    <i class="bi bi-box-arrow-right"></i><span>Keluar</span>
                </button>
            </div>
        </div>
    </div>
</header>

{{-- Logout form (tersembunyi) ────────────────────────────────────────────── --}}
<form id="logoutForm" action="{{ route('owner.logout') }}" method="POST" style="display:none;">@csrf</form>

{{-- Bottom bar (mobile) ─────────────────────────────────────────────────── --}}
<nav class="bottom-bar" aria-label="Menu utama">
    <a href="{{ route('owner.dashboard') }}"
       class="bottom-item {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
        <i class="bi bi-house-door-fill"></i><span>Beranda</span>
    </a>
    <a href="{{ route('owner.langganan.index') }}"
       class="bottom-item {{ request()->routeIs('owner.langganan.*') ? 'active' : '' }}">
        <i class="bi bi-patch-check-fill"></i><span>Langganan</span>
    </a>
    <a href="{{ route('owner.akun.index') }}"
       class="bottom-item {{ request()->routeIs('owner.akun.*') ? 'active' : '' }}">
        <i class="bi bi-person-circle"></i><span>Akun</span>
    </a>
    <button type="button" class="bottom-item logout js-logout">
        <i class="bi bi-box-arrow-right"></i><span>Keluar</span>
    </button>
</nav>

{{-- Konten halaman ──────────────────────────────────────────────────────── --}}
@yield('content')

{{-- JS bersama ──────────────────────────────────────────────────────────── --}}
<script>
    // ── Dropdown user ──────────────────────────────────────────────────
    const userDropdown = document.getElementById('userDropdown');
    if (userDropdown) {
        const trigger = userDropdown.querySelector('.user-dropdown-trigger');
        trigger.addEventListener('click', e => { e.stopPropagation(); userDropdown.classList.toggle('open'); });
        document.addEventListener('click', e => { if (!userDropdown.contains(e.target)) userDropdown.classList.remove('open'); });
    }

    // ── Konfirmasi logout ──────────────────────────────────────────────
    const logoutForm = document.getElementById('logoutForm');
    function confirmLogout(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Konfirmasi Logout',
            text: 'Apakah Anda yakin ingin keluar dari akun?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ff6a00',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-box-arrow-right me-2"></i>Ya, Keluar',
            cancelButtonText: '<i class="bi bi-x-circle me-2"></i>Batal',
            reverseButtons: true,
        }).then(r => {
            if (r.isConfirmed) {
                Swal.fire({ title: 'Logging out...', allowOutsideClick: false, allowEscapeKey: false, didOpen: () => Swal.showLoading() });
                logoutForm.submit();
            }
        });
    }
    document.querySelectorAll('.js-logout').forEach(btn => btn.addEventListener('click', confirmLogout));

    // ── Robot face utilities (dipakai halaman yang butuh) ──────────────
    function faceSvg() {
        const t = document.getElementById('tplRobotFace');
        return t ? t.innerHTML : '';
    }
    function hydrateFaces(root = document) {
        root.querySelectorAll('[data-fa-hydrate]').forEach(el => {
            el.removeAttribute('data-fa-hydrate');
            el.innerHTML = faceSvg();
            el.style.setProperty('--bd', (-Math.random() * 4.6).toFixed(2) + 's');
        });
    }
    const faceTrack = { x: 0, y: 0, raf: 0, last: Date.now() };
    const clamp = (v, a, b) => Math.max(a, Math.min(b, v));
    function lookAt(f, nx, ny) {
        f.style.setProperty('--px', (nx * 4.5).toFixed(2) + 'px');
        f.style.setProperty('--py', (ny * 3.2).toFixed(2) + 'px');
        f.style.setProperty('--tilt', (nx * 4).toFixed(1) + 'deg');
    }
    function updateFaces() {
        faceTrack.raf = 0;
        const vw = window.innerWidth, vh = window.innerHeight;
        document.querySelectorAll('.fa-face').forEach(f => {
            const r = f.getBoundingClientRect();
            if (!r.width || r.bottom < 0 || r.top > vh) return;
            lookAt(f, clamp((faceTrack.x - (r.left + r.width/2)) / 220, -1, 1),
                      clamp((faceTrack.y - (r.top  + r.height/2)) / 220, -1, 1));
        });
    }
    function onPointer(e) {
        faceTrack.x = e.clientX; faceTrack.y = e.clientY; faceTrack.last = Date.now();
        if (!faceTrack.raf) faceTrack.raf = requestAnimationFrame(updateFaces);
    }
    document.addEventListener('pointermove', onPointer, { passive: true });
    document.addEventListener('pointerdown', onPointer, { passive: true });
    setInterval(() => {
        if (Date.now() - faceTrack.last < 4000 || document.hidden) return;
        document.querySelectorAll('.fa-face[data-global]').forEach(f => {
            lookAt(f, Math.random() * 2 - 1, (Math.random() * 2 - 1) * .6);
        });
    }, 3500);
</script>

@yield('scripts')
</body>
</html>
