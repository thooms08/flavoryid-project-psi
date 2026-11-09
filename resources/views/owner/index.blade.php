<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Dashboard Owner | Flavory.id</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @include('layouts.favicon')

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
            background: rgba(255,255,255,0.85);
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
        .user-dropdown {
            position: relative;
        }
        
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
            background: rgba(255, 106, 0, 0.05);
            border-color: rgba(255, 106, 0, 0.15);
        }
        
        .user-dropdown.open .user-dropdown-trigger {
            background: rgba(255, 106, 0, 0.08);
            border-color: rgba(255, 106, 0, 0.2);
        }
        
        .topbar-user {
            display: flex;
            align-items: center;
            gap: 8px;
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
        .topbar-user-info {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }
        .topbar-user-info .name {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--text);
        }
        .topbar-user-info .role {
            font-size: 0.68rem;
            font-weight: 600;
            color: var(--muted);
        }
        
        .dropdown-arrow {
            font-size: 0.7rem;
            color: var(--muted);
            transition: transform 0.2s ease;
        }
        
        .user-dropdown.open .dropdown-arrow {
            transform: rotate(180deg);
        }
        
        /* Dropdown Menu */
        .user-dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: white;
            border-radius: 16px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
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
            background: linear-gradient(135deg, rgba(255, 106, 0, 0.03), rgba(255, 154, 0, 0.02));
        }
        
        .dropdown-menu-header .name {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 2px;
        }
        
        .dropdown-menu-header .role {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--muted);
        }
        
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
        
        .dropdown-menu-item:hover {
            background: rgba(255, 106, 0, 0.05);
            color: var(--orange);
        }
        
        .dropdown-menu-item i {
            font-size: 1.05rem;
            width: 20px;
            text-align: center;
        }
        
        .dropdown-menu-item.logout-item {
            color: #ef4444;
            border-top: 1px solid var(--border);
        }
        
        .dropdown-menu-item.logout-item:hover {
            background: #fef2f2;
            color: #dc2626;
        }
        
        /* Hide old logout button */
        .btn-logout {
            display: none;
        }

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
        .hero-orb-1 {
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(255,106,0,.12), transparent 65%);
            top: -30%; right: -5%;
        }
        .hero-orb-2 {
            width: 280px; height: 280px;
            background: radial-gradient(circle, rgba(255,154,0,.1), transparent 65%);
            bottom: -20%; left: 10%;
        }
        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 900px;
            margin: 0 auto;
            text-align: center;
        }
        .hero-greeting {
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--orange);
            margin-bottom: 6px;
        }
        .hero-title {
            font-size: clamp(1.5rem, 3vw, 2.2rem);
            font-weight: 900;
            color: var(--text);
            line-height: 1.2;
            margin-bottom: 8px;
        }
        .hero-title span {
            background: linear-gradient(135deg, var(--orange), var(--orange-mid));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-sub {
            font-size: 0.87rem;
            color: var(--muted);
            line-height: 1.6;
            max-width: 620px;
            margin: 0 auto;
        }
        .hero-chips {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
            margin-top: 18px;
        }
        .hero-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 100px;
            background: rgba(255,255,255,0.85);
            border: 1px solid rgba(255,106,0,0.15);
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--muted);
            backdrop-filter: blur(8px);
        }
        .hero-chip i { color: var(--orange); }

        /* ─── Main layout ────────────────────────────────────────────── */
        .main-wrap {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 24px 80px;
        }
        .section-label {
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 20px;
        }

        /* ─── Menu Grid ──────────────────────────────────────────────── */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 20px;
        }
        @media (min-width: 1024px) {
            .menu-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 640px) {
            .menu-grid { grid-template-columns: 1fr; gap: 14px; }
        }

        /* ─── Menu Card (base) ───────────────────────────────────────── */
        .menu-card {
            display: flex;
            flex-direction: column;
            background: var(--card-bg);
            border-radius: 22px;
            padding: 28px 24px 22px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            text-decoration: none;
            color: var(--text);
            transition: transform 0.22s cubic-bezier(.34,1.56,.64,1),
                        box-shadow 0.22s ease,
                        border-color 0.2s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        .menu-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,106,0,0) 0%, rgba(255,106,0,0) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .menu-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(255,106,0,0.12);
            border-color: rgba(255,106,0,0.2);
            color: var(--text);
            text-decoration: none;
        }
        .menu-card:hover::before { opacity: 1; }

        .menu-card-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 18px;
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }
        .menu-card:hover .menu-card-icon {
            transform: scale(1.08) rotate(-3deg);
        }
        .menu-card-title {
            font-size: 1rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 6px;
            line-height: 1.3;
        }
        .menu-card-desc {
            font-size: 0.78rem;
            color: var(--muted);
            line-height: 1.6;
            flex: 1;
        }
        .menu-card-arrow {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 18px;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--orange);
            transition: gap 0.2s ease;
        }
        .menu-card:hover .menu-card-arrow { gap: 8px; }

        /* ─── Warna icon per card ────────────────────────────────────── */
        .icon-orange  { background: #fff4ec; color: var(--orange); }
        .icon-red     { background: #fef2f2; color: #ef4444; }
        .icon-green   { background: #f0fdf4; color: #22c55e; }
        .icon-blue    { background: #eff6ff; color: #3b82f6; }
        .icon-purple  { background: #faf5ff; color: #a855f7; }
        .icon-slate   { background: #f1f5f9; color: #64748b; }

        /* ─── Card AI Consultant (interaktif) ────────────────────────── */
        @keyframes aiShift {
            0%   { background-position: 0%   50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0%   50%; }
        }
        @keyframes aiDot {
            0%   { box-shadow: 0 0 0 0 rgba(255,255,255,.7); }
            100% { box-shadow: 0 0 0 8px rgba(255,255,255,0); }
        }

        .menu-card-ai {
            background: linear-gradient(135deg, #ff6a00, #ff9a00, #ffb300, #ff7a1a, #ff6a00);
            background-size: 300% 300%;
            animation: aiShift 6s ease infinite;
            border: none;
            box-shadow: 0 8px 32px rgba(255,106,0,0.35);
            transform: perspective(900px) rotateX(var(--rx, 0deg)) rotateY(var(--ry, 0deg));
            transition: transform .18s ease-out, box-shadow .22s ease;
            will-change: transform;
        }
        .menu-card-ai:hover {
            transform: perspective(900px) rotateX(var(--rx, 0deg)) rotateY(var(--ry, 0deg)) translateY(-6px);
            box-shadow: 0 22px 52px rgba(255,106,0,0.5);
            border: none;
        }
        .menu-card-ai:active { transform: perspective(900px) scale(.985); }
        .menu-card-ai:focus-visible { outline: 3px solid #fff; outline-offset: -6px; }
        .menu-card-ai::before { display: none; }

        /* sorotan cahaya mengikuti kursor */
        .menu-card-ai::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(260px circle at var(--mx, 50%) var(--my, 0%), rgba(255,255,255,.38), transparent 62%);
            opacity: 0;
            transition: opacity .25s ease;
            pointer-events: none;
        }
        .menu-card-ai:hover::after { opacity: 1; }

        .menu-card-ai .menu-card-title { color: #fff; }
        .menu-card-ai .menu-card-desc  { color: rgba(255,255,255,.92); }

        /* status */
        .ai-status {
            position: absolute;
            top: 16px;
            right: 16px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 4px 11px 4px 9px;
            border-radius: 100px;
            background: rgba(255,255,255,.2);
            border: 1px solid rgba(255,255,255,.3);
            color: #fff;
            font-size: .66rem;
            font-weight: 700;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }
        .ai-status i {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: #fff;
            animation: aiDot 1.8s ease-out infinite;
        }

        /* robot + gelembung chat */
        .ai-stage {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 14px 0 18px;
            min-height: 96px;
        }
        .ai-robot {
            width: 96px;
            height: 96px;
            flex-shrink: 0;
            padding: 10px 8px 6px;
            border-radius: 28px;
            background: linear-gradient(160deg, #ffffff, #fff1df);
            border: 1px solid rgba(255,255,255,.7);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: inset 0 1px 0 #fff, 0 12px 26px rgba(160,60,0,.3);
            transition: transform .25s cubic-bezier(.34,1.56,.64,1);
        }
        .menu-card-ai:hover .ai-robot { transform: scale(1.07) rotate(-2deg); }
        .ai-bubble {
            position: relative;
            background: #fff;
            color: var(--text);
            border-radius: 16px 16px 16px 4px;
            padding: 10px 14px;
            font-size: .76rem;
            font-weight: 600;
            line-height: 1.4;
            box-shadow: 0 6px 18px rgba(160,60,0,.2);
            min-width: 0;
        }
        .ai-bubble::before {
            content: '';
            position: absolute;
            left: -6px; bottom: 10px;
            width: 12px; height: 12px;
            background: #fff;
            transform: rotate(45deg);
            border-radius: 2px;
        }
        .ai-bubble span { position: relative; display: block; transition: opacity .25s ease, transform .25s ease; }
        .ai-bubble span.swap { opacity: 0; transform: translateY(4px); }

        .menu-card-ai .menu-card-arrow {
            align-self: flex-start;
            background: #fff;
            color: var(--orange);
            padding: 9px 16px;
            border-radius: 100px;
            box-shadow: 0 6px 16px rgba(160,60,0,.22);
        }
        .menu-card-ai:hover .menu-card-arrow { gap: 10px; }

        /* ─── Wajah robot FlavoryAI (disalin dari ai/index.blade.php) ── */
        .fa-face {
            --px: 0px;
            --py: 0px;
            /* arah pandang mengikuti kursor */
            --ex: 0px;
            --ey: 0px;
            /* offset pandangan dari ekspresi  */
            --sx: 1;
            --sy: 1;
            /* skala mata dari ekspresi        */
            --tilt: 0deg;
            /* kepala sedikit miring ke kursor */
            display: block;
            width: 100%;
            height: 100%;
            line-height: 0;
            user-select: none;
            -webkit-user-select: none;
        }

        .fa-face[data-global] {
            cursor: pointer;
        }

        .fa-svg {
            display: block;
            width: 100%;
            height: 100%;
            overflow: visible;
        }

        .fa-tilt {
            transform-box: fill-box;
            transform-origin: 50% 100%;
            transform: rotate(var(--tilt));
            transition: transform .25s ease;
        }

        .fa-head {
            transform-box: fill-box;
            transform-origin: 50% 100%;
        }

        .fa-face[data-global] .fa-head {
            animation: fa-float 3.2s ease-in-out infinite;
        }

        .fa-orb-glow {
            transform-box: fill-box;
            transform-origin: center;
            animation: fa-pulse 1.8s ease-in-out infinite;
        }

        .fa-ear-in {
            transition: fill .2s;
        }

        /* mata */
        .fa-eyes {
            transform-box: fill-box;
            transform-origin: center;
            animation: fa-blink 4.6s infinite;
            animation-delay: var(--bd, 0s);
        }

        .fa-eye {
            fill: #FFE9CC;
            transform-box: fill-box;
            transform-origin: center;
            transform: translate(calc(var(--px) + var(--ex)), calc(var(--py) + var(--ey))) scale(var(--sx), var(--sy));
            transition: transform .18s ease, opacity .15s ease;
        }

        .fa-eye-hl {
            fill: #fff;
        }

        .fa-arc {
            fill: none;
            stroke: #FFE9CC;
            stroke-width: 5;
            stroke-linecap: round;
            opacity: 0;
            transition: opacity .15s;
        }

        .fa-brow {
            fill: none;
            stroke: #FFB067;
            stroke-width: 4;
            stroke-linecap: round;
            opacity: 0;
            transition: opacity .15s;
        }

        /* mulut */
        .fa-mouth {
            opacity: 0;
            transition: opacity .12s;
            fill: none;
            stroke: #FFB067;
            stroke-width: 4.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .fa-mouth--happy {
            fill: #FF9B4A;
            stroke: #FF9B4A;
            stroke-width: 3;
        }

        .fa-mouth--listen {
            fill: #2A1810;
            stroke-width: 4;
        }

        /* pipi & air mata */
        .fa-cheek {
            opacity: 0;
            transition: opacity .25s ease;
        }

        .fa-tear {
            opacity: 0;
        }

        /* ── ekspresi ── */
        .fa-face[data-exp="smile"] .fa-mouth--smile,
        .fa-face[data-exp="happy"] .fa-mouth--happy,
        .fa-face[data-exp="talk"] .fa-mouth--happy,
        .fa-face[data-exp="think"] .fa-mouth--think,
        .fa-face[data-exp="listen"] .fa-mouth--listen,
        .fa-face[data-exp="sad"] .fa-mouth--sad {
            opacity: 1;
        }

        .fa-face[data-exp="smile"] .fa-cheek {
            opacity: .18;
        }

        .fa-face[data-exp="talk"] .fa-cheek {
            opacity: .25;
        }

        .fa-face[data-exp="happy"] .fa-cheek {
            opacity: .6;
        }

        .fa-face[data-exp="happy"] .fa-eye {
            opacity: 0;
        }

        .fa-face[data-exp="happy"] .fa-arc {
            opacity: 1;
        }

        .fa-face[data-exp="happy"] .fa-head {
            animation: fa-hop .45s ease-out 2;
        }

        .fa-face[data-exp="talk"] .fa-mouth--happy {
            transform-box: fill-box;
            transform-origin: 50% 0;
            animation: fa-talk .26s ease-in-out infinite alternate;
        }

        .fa-face[data-exp="think"] {
            --ex: 3px;
            --ey: -4px;
        }

        .fa-face[data-exp="think"] .fa-head {
            animation: fa-sway 1.6s ease-in-out infinite alternate;
        }

        .fa-face[data-exp="think"] .fa-orb-glow {
            animation-duration: .9s;
        }

        .fa-face[data-exp="listen"] {
            --sx: 1.12;
            --sy: 1.2;
        }

        .fa-face[data-exp="listen"] .fa-orb-glow {
            animation-duration: .8s;
        }

        .fa-face[data-exp="listen"] .fa-ear-in {
            animation: fa-ear .55s ease-in-out infinite alternate;
        }

        .fa-face[data-exp="sad"] {
            --sy: .86;
            --ey: 3px;
        }

        .fa-face[data-exp="sad"] .fa-brow {
            opacity: 1;
        }

        .fa-face[data-exp="sad"] .fa-tear {
            animation: fa-drip 1.6s ease-in infinite;
        }

        @keyframes fa-float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-3px);
            }
        }

        @keyframes fa-pulse {

            0%,
            100% {
                opacity: .22;
                transform: scale(.9);
            }

            50% {
                opacity: .55;
                transform: scale(1.18);
            }
        }

        @keyframes fa-blink {

            0%,
            92%,
            100% {
                transform: scaleY(1);
            }

            95% {
                transform: scaleY(.08);
            }
        }

        @keyframes fa-hop {

            0%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-9px) scale(1.04);
            }
        }

        @keyframes fa-talk {
            from {
                transform: scaleY(.3);
            }

            to {
                transform: scaleY(1);
            }
        }

        @keyframes fa-sway {
            from {
                transform: rotate(-3deg);
            }

            to {
                transform: rotate(3deg);
            }
        }

        @keyframes fa-ear {
            from {
                fill: #F26A0F;
            }

            to {
                fill: #FFD27A;
            }
        }

        @keyframes fa-drip {
            0% {
                transform: translateY(0);
                opacity: 0;
            }

            20% {
                opacity: 1;
            }

            100% {
                transform: translateY(14px);
                opacity: 0;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .fa-face *,
            .fa-face *::before,
            .fa-face *::after {
                animation: none !important;
            }
        }


        /* ─── Outlet info bar ────────────────────────────────────────── */
        .outlet-bar {
            display: flex;
            align-items: center;
            gap: 16px;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 16px 20px;
            margin-bottom: 36px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        }
        .outlet-bar-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #fff4ec;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--orange);
            flex-shrink: 0;
        }
        .outlet-bar-info .label {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 2px;
        }
        .outlet-bar-info .value {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--text);
        }
        .outlet-bar-badge {
            margin-left: auto;
            padding: 4px 12px;
            border-radius: 100px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .badge-owner {
            background: linear-gradient(135deg, var(--orange), var(--orange-mid));
            color: #fff;
        }

        /* ─── Responsive tweaks ──────────────────────────────────────── */
        @media (max-width: 600px) {
            .topbar { padding: 0 16px; }
            .topbar-user-info { display: none; }
            .hero-banner { padding: 28px 20px 24px; }
            .main-wrap { padding: 28px 16px 64px; }
            .outlet-bar { flex-wrap: wrap; gap: 12px; }
            .outlet-bar-badge { margin-left: 0; }
        }

        /* ─── Bottom bar (hanya handphone) ───────────────────────────── */
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
            .ai-stage { min-height: 0; }
        }

        @media (prefers-reduced-motion: reduce) {
            .menu-card-ai, .ai-status i { animation: none !important; }
            .menu-card-ai, .menu-card-ai:hover { transform: none; }
        }
    </style>
</head>
<body>

<svg width="0" height="0" style="position:absolute;overflow:hidden" aria-hidden="true" focusable="false">
        <defs>
            <linearGradient id="fa-g-head" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0" stop-color="#FFB460" />
                <stop offset="1" stop-color="#F26A0F" />
            </linearGradient>
        </defs>
    </svg>

<template id="tplRobotFace"><svg class="fa-svg" viewBox="18 0 164 134" xmlns="http://www.w3.org/2000/svg"
            focusable="false" aria-hidden="true">
            <g class="fa-tilt">
                <g class="fa-head">
                    {{-- telinga --}}
                    <rect x="20" y="70" width="20" height="34" rx="10" fill="#FFF4E8" />
                    <rect class="fa-ear-in" x="25" y="80" width="8" height="14" rx="4" fill="#F26A0F" />
                    <rect x="160" y="70" width="20" height="34" rx="10" fill="#FFF4E8" />
                    <rect class="fa-ear-in" x="167" y="80" width="8" height="14" rx="4"
                        fill="#F26A0F" />
                    {{-- antena --}}
                    <rect x="97" y="18" width="6" height="20" rx="3" fill="#3A2316" />
                    <circle class="fa-orb-glow" cx="100" cy="14" r="13" fill="#FFD27A" />
                    <circle cx="100" cy="14" r="8" fill="#FFD27A" />
                    <circle cx="97.5" cy="11.5" r="2.5" fill="#fff" />
                    {{-- cangkang kepala --}}
                    <rect x="34" y="34" width="132" height="98" rx="44" fill="url(#fa-g-head)" />
                    <path d="M52 52 Q60 42 82 40" fill="none" stroke="#fff" stroke-opacity=".45" stroke-width="5"
                        stroke-linecap="round" />
                    {{-- layar wajah --}}
                    <rect x="46" y="48" width="108" height="70" rx="32" fill="#2A1810" />
                    <rect x="46" y="48" width="108" height="70" rx="32" fill="none" stroke="#FFB067"
                        stroke-opacity=".35" stroke-width="2" />
                    <path d="M62 56 Q76 50 96 52" fill="none" stroke="#fff" stroke-opacity=".12"
                        stroke-width="4" stroke-linecap="round" />
                    {{-- alis (sedih) --}}
                    <path class="fa-brow" d="M67 68 L89 60" />
                    <path class="fa-brow" d="M133 68 L111 60" />
                    {{-- mata --}}
                    <g class="fa-eyes">
                        <g class="fa-eye">
                            <ellipse cx="78" cy="78" rx="9" ry="12" />
                            <circle class="fa-eye-hl" cx="81" cy="73" r="3" />
                        </g>
                        <g class="fa-eye">
                            <ellipse cx="122" cy="78" rx="9" ry="12" />
                            <circle class="fa-eye-hl" cx="125" cy="73" r="3" />
                        </g>
                    </g>
                    {{-- mata senang (^ ^) --}}
                    <path class="fa-arc" d="M68 82 Q78 69 88 82" />
                    <path class="fa-arc" d="M112 82 Q122 69 132 82" />
                    {{-- pipi --}}
                    <g class="fa-cheek">
                        <ellipse cx="63" cy="101" rx="10" ry="6" fill="#FF6F8A" />
                        <ellipse cx="137" cy="101" rx="10" ry="6" fill="#FF6F8A" />
                    </g>
                    {{-- mulut --}}
                    <path class="fa-mouth fa-mouth--smile" d="M88 98 Q100 110 112 98" />
                    <path class="fa-mouth fa-mouth--happy" d="M88 96 Q100 116 112 96 Z" />
                    <path class="fa-mouth fa-mouth--think" d="M92 104 L108 104" />
                    <ellipse class="fa-mouth fa-mouth--listen" cx="100" cy="104" rx="5.5"
                        ry="7" />
                    <path class="fa-mouth fa-mouth--sad" d="M90 108 Q100 98 110 108" />
                    <path class="fa-tear" d="M70 94 Q74 101 70 105 Q66 101 70 94 Z" fill="#8FD8FF" />
                </g>
            </g>
        </svg></template>

{{-- ── Topbar ───────────────────────────────────────────────────────────── --}}
<header class="topbar">
    <a href="{{ route('owner.dashboard') }}" class="topbar-brand">
        <img src="{{ asset('logo-512.png') }}"
             alt="Flavory.id"
             class="topbar-brand-logo"
             onerror="this.style.display='none'">
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
                
                <a href="{{ route('owner.langganan.index') }}" class="dropdown-menu-item">
                    <i class="bi bi-patch-check-fill"></i>
                    <span>Langganan</span>
                </a>
                
                <a href="{{ route('owner.akun.index') }}" class="dropdown-menu-item">
                    <i class="bi bi-person-circle"></i>
                    <span>Akun</span>
                </a>
                
                <button type="button" class="dropdown-menu-item logout-item js-logout" id="btnLogout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Keluar</span>
                </button>
            </div>
        </div>
    </div>
</header>

<form id="logoutForm" action="{{ route('owner.logout') }}" method="POST" style="display:none;">
    @csrf
</form>

{{-- ── Bottom bar (muncul di handphone, menggantikan menu di topbar) ───── --}}
<nav class="bottom-bar" aria-label="Menu utama">
    <a href="{{ route('owner.dashboard') }}" class="bottom-item {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
        <i class="bi bi-house-door-fill"></i>
        <span>Beranda</span>
    </a>
    <a href="{{ route('owner.langganan.index') }}" class="bottom-item {{ request()->routeIs('owner.langganan.*') ? 'active' : '' }}">
        <i class="bi bi-patch-check-fill"></i>
        <span>Langganan</span>
    </a>
    <a href="{{ route('owner.akun.index') }}" class="bottom-item {{ request()->routeIs('owner.akun.*') ? 'active' : '' }}">
        <i class="bi bi-person-circle"></i>
        <span>Akun</span>
    </a>
    <button type="button" class="bottom-item logout js-logout">
        <i class="bi bi-box-arrow-right"></i>
        <span>Keluar</span>
    </button>
</nav>

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

    {{-- Outlet info bar --}}
    @if($outlet)
    <div class="outlet-bar">
        <div class="outlet-bar-icon">
            <i class="bi bi-shop"></i>
        </div>
        <div class="outlet-bar-info">
            <div class="label">Outlet Aktif</div>
            <div class="value">{{ $outlet->nama_outlet ?? '-' }}</div>
        </div>
        <span class="outlet-bar-badge badge-owner">Owner</span>
    </div>
    @endif

    <p class="section-label">Menu Utama</p>

    {{-- ── Menu Grid ────────────────────────────────────────────────── --}}
    <div class="menu-grid">
        
        <a href="{{ route('owner.ai') }}" class="menu-card menu-card-ai" id="aiCard">
            <div class="ai-stage">
                <div class="ai-robot">
                    <span class="fa-face" data-global data-fa-hydrate data-exp="smile"></span>
                </div>
                <div class="ai-bubble" aria-hidden="true"><span id="aiBubbleText">Menu apa yang paling laris minggu ini?</span></div>
            </div>

            <div class="menu-card-title">Flavory AI Consultant</div>
            <div class="menu-card-desc">
                Konsultan bisnis berbasis AI. Tanya apa saja soal performa outlet — dijawab berdasarkan data nyata Anda.
            </div>
            <div class="menu-card-arrow">
                Mulai Chat AI <i class="bi bi-arrow-right"></i>
            </div>
        </a>

        {{-- 1. Ikhtisar Bisnis --}}
        <a href="{{ route('owner.laporan.index') }}" class="menu-card">
            <div class="menu-card-icon icon-orange">
                <i class="bi bi-bar-chart-line-fill"></i>
            </div>
            <div class="menu-card-title">Ikhtisar Bisnis</div>
            <div class="menu-card-desc">
                Lihat ringkasan omzet, total order, keuntungan harian, serta analisis menu terlaris dan terendah.
            </div>
            <div class="menu-card-arrow">
                Buka Ikhtisar <i class="bi bi-arrow-right"></i>
            </div>
        </a>

        {{-- 2. Laporan Pengeluaran --}}
        <a href="{{ route('owner.pengeluaran.index') }}" class="menu-card">
            <div class="menu-card-icon icon-red">
                <i class="bi bi-receipt-cutoff"></i>
            </div>
            <div class="menu-card-title">Laporan Pengeluaran</div>
            <div class="menu-card-desc">
                Catat dan pantau semua pengeluaran operasional bisnis. Tersedia fitur export ke Excel.
            </div>
            <div class="menu-card-arrow">
                Buka Laporan <i class="bi bi-arrow-right"></i>
            </div>
        </a>

        {{-- 3. Laporan Modal Usaha --}}
        <a href="{{ route('owner.modal.index') }}" class="menu-card">
            <div class="menu-card-icon icon-green">
                <i class="bi bi-wallet2"></i>
            </div>
            <div class="menu-card-title">Laporan Modal Usaha</div>
            <div class="menu-card-desc">
                Kelola setoran modal dan pantau riwayat perputaran modal usaha secara lengkap.
            </div>
            <div class="menu-card-arrow">
                Buka Modal <i class="bi bi-arrow-right"></i>
            </div>
        </a>

        {{-- 4. Performa Bisnis --}}
        <a href="{{ route('owner.performa') }}" class="menu-card">
            <div class="menu-card-icon icon-blue">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div class="menu-card-title">Performa Bisnis</div>
            <div class="menu-card-desc">
                Analisis tren performa bisnis berdasarkan periode waktu, kategori menu, dan jam sibuk.
            </div>
            <div class="menu-card-arrow">
                Buka Performa <i class="bi bi-arrow-right"></i>
            </div>
        </a>

        {{-- 6. Pengaturan Form Pesanan --}}
        <a href="{{ route('owner.pengaturan.index') }}" class="menu-card">
            <div class="menu-card-icon icon-purple">
                <i class="bi bi-sliders"></i>
            </div>
            <div class="menu-card-title">Pengaturan Form Pesanan</div>
            <div class="menu-card-desc">
                Konfigurasi tampilan & kolom form pesanan online: tipe layanan, tabel, catatan, dan lainnya.
            </div>
            <div class="menu-card-arrow">
                Buka Pengaturan <i class="bi bi-arrow-right"></i>
            </div>
        </a>

    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // ============================================
    // User Dropdown Toggle
    // ============================================
    const userDropdown = document.getElementById('userDropdown');
    const dropdownTrigger = userDropdown.querySelector('.user-dropdown-trigger');
    
    dropdownTrigger.addEventListener('click', function(e) {
        e.stopPropagation();
        userDropdown.classList.toggle('open');
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!userDropdown.contains(e.target)) {
            userDropdown.classList.remove('open');
        }
    });
    
    // ============================================
    // SweetAlert Logout Confirmation
    // ============================================
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
            customClass: {
                popup: 'rounded-4',
                confirmButton: 'px-4 py-2 fw-bold',
                cancelButton: 'px-4 py-2 fw-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Logging out...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit logout form
                logoutForm.submit();
            }
        });
    }
    // tombol Keluar di dropdown (desktop) & bottom bar (handphone)
    document.querySelectorAll('.js-logout').forEach(function (btn) {
        btn.addEventListener('click', confirmLogout);
    });
</script>

<script>
    // ============================================
    // Wajah Robot FlavoryAI (logika sama dengan ai/index.blade.php)
    // ============================================
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function faceSvg() {
        return document.getElementById('tplRobotFace').innerHTML;
    }

    // Isi placeholder <span data-fa-hydrate>
    function hydrateFaces(root = document) {
        root.querySelectorAll('[data-fa-hydrate]').forEach(el => {
            el.removeAttribute('data-fa-hydrate');
            el.innerHTML = faceSvg();
            el.style.setProperty('--bd', (-Math.random() * 4.6).toFixed(2) + 's'); // kedip tidak serempak
        });
    }

    // Mata + kepala mengikuti kursor / sentuhan
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
        const batch = [];
        document.querySelectorAll('.fa-face').forEach(f => {
            const r = f.getBoundingClientRect();
            if (!r.width || r.bottom < 0 || r.top > vh || r.right < 0 || r.left > vw) return;
            batch.push([f, r]);
        });
        batch.forEach(([f, r]) => {
            const nx = clamp((faceTrack.x - (r.left + r.width / 2)) / 220, -1, 1);
            const ny = clamp((faceTrack.y - (r.top + r.height / 2)) / 220, -1, 1);
            lookAt(f, nx, ny);
        });
    }

    function onPointer(e) {
        faceTrack.x = e.clientX;
        faceTrack.y = e.clientY;
        faceTrack.last = Date.now();
        if (!faceTrack.raf) faceTrack.raf = requestAnimationFrame(updateFaces);
    }
    document.addEventListener('pointermove', onPointer, { passive: true });
    document.addEventListener('pointerdown', onPointer, { passive: true });

    // Saat tidak ada gerakan: wajah melirik ke sekitar
    setInterval(() => {
        if (Date.now() - faceTrack.last < 4000 || document.hidden) return;
        document.querySelectorAll('.fa-face[data-global]').forEach(f => {
            lookAt(f, Math.random() * 2 - 1, (Math.random() * 2 - 1) * .6);
        });
    }, 3500);

    hydrateFaces(document);

    // ============================================
    // Interaksi card AI
    // ============================================
    const aiCard = document.getElementById('aiCard');
    if (aiCard) {
        const face = aiCard.querySelector('.fa-face');
        const setExp = v => { if (face) face.dataset.exp = v; };
        let hovering = false;

        aiCard.addEventListener('pointerenter', e => { hovering = true; if (e.pointerType === 'mouse') setExp('happy'); });
        aiCard.addEventListener('pointerleave', () => {
            hovering = false; setExp('smile');
            aiCard.style.setProperty('--rx', '0deg');
            aiCard.style.setProperty('--ry', '0deg');
        });
        aiCard.addEventListener('pointerdown', () => setExp('listen'));
        aiCard.addEventListener('pointerup', e => setExp(hovering && e.pointerType === 'mouse' ? 'happy' : 'smile'));
        aiCard.addEventListener('pointercancel', () => setExp('smile'));

        // Sorotan cahaya + tilt 3D (khusus mouse)
        aiCard.addEventListener('pointermove', e => {
            if (e.pointerType !== 'mouse') return;
            const r = aiCard.getBoundingClientRect();
            const x = e.clientX - r.left, y = e.clientY - r.top;
            aiCard.style.setProperty('--mx', x + 'px');
            aiCard.style.setProperty('--my', y + 'px');
            if (!reduceMotion) {
                aiCard.style.setProperty('--rx', ((0.5 - y / r.height) * 7).toFixed(2) + 'deg');
                aiCard.style.setProperty('--ry', ((x / r.width - 0.5) * 7).toFixed(2) + 'deg');
            }
        });

        // Gelembung contoh pertanyaan: robot "berpikir" lalu "bicara" saat ganti
        const bubble = document.getElementById('aiBubbleText');
        const tips = [
            'Menu apa yang paling laris minggu ini?',
            'Kenapa omzet kemarin turun?',
            'Jam berapa outlet paling ramai?',
            'Menu mana yang margin untungnya paling tipis?'
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

</body>
</html>