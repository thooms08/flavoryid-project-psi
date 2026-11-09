<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FlavoryAI | Flavory.id</title>
    @include('layouts.favicon')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    {{-- Markdown renderer --}}
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* ═══════════════════════════════════════════════════════════════
   RESET & ROOT
═══════════════════════════════════════════════════════════════ */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --or1: #ff6a00;
            --or2: #ff9a00;
            --or3: #ffb300;
            --or4: #ff7a1a;
            --bg: #f8f9fb;
            /* sama persis dengan beranda owner */
            --card: #ffffff;
            --txt: #1e293b;
            --mut: #64748b;
            --brd: rgba(0, 0, 0, .06);
            --sb-w: 280px;
            --topbar-h: 60px;
        }

        html,
        body {
            height: 100%;
            font-family: 'Poppins', system-ui, sans-serif;
            background: var(--bg);
            color: var(--txt);
            overflow: hidden;
            /* biarkan inner scroll per-panel */
        }

        /* ═══════════════════════════════════════════════════════════════
   ANIMASI GRADASI BERGERAK
═══════════════════════════════════════════════════════════════ */
        @keyframes orShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        @keyframes pulse-ring {
            0% {
                transform: scale(1);
                opacity: .6;
            }

            50% {
                transform: scale(1.18);
                opacity: .3;
            }

            100% {
                transform: scale(1);
                opacity: .6;
            }
        }

        @keyframes blink-dot {

            0%,
            80%,
            100% {
                opacity: 0;
            }

            40% {
                opacity: 1;
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes sparkle {

            0%,
            100% {
                transform: scale(1) rotate(0deg);
                opacity: .7;
            }

            50% {
                transform: scale(1.3) rotate(20deg);
                opacity: 1;
            }
        }

        .ai-gradient-bg {
            background: linear-gradient(135deg, var(--or1), var(--or2), var(--or3), var(--or4), var(--or1));
            background-size: 300% 300%;
            animation: orShift 5s ease infinite;
        }

        .ai-gradient-text {
            background: linear-gradient(135deg, var(--or1), var(--or2), var(--or3));
            background-size: 200% 200%;
            animation: orShift 4s ease infinite;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ═══════════════════════════════════════════════════════════════
   LAYOUT UTAMA  (sidebar + main)
═══════════════════════════════════════════════════════════════ */
        .ai-shell {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* ── SIDEBAR ──────────────────────────────────────────────── */
        .ai-sidebar {
            width: var(--sb-w);
            flex-shrink: 0;
            background: var(--card);
            border-right: 1px solid var(--brd);
            display: flex;
            flex-direction: column;
            height: 100vh;
            transition: transform .3s cubic-bezier(.4, 0, .2, 1), margin-left .3s cubic-bezier(.4, 0, .2, 1);
            z-index: 200;
        }

        /* Desktop: sembunyikan sidebar ke kiri dengan margin negatif,
   konten sidebar tetap ada di DOM — tidak rusak */
        .ai-sidebar.collapsed {
            margin-left: calc(-1 * var(--sb-w));
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 18px 16px 14px;
            border-bottom: 1px solid var(--brd);
            flex-shrink: 0;
        }

        .sidebar-brand-icon {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2px;
            flex-shrink: 0;
            /* bingkai gradasi oranye tetap ada, isinya wajah robot di atas krem */
            border: 2px solid transparent;
            background:
                linear-gradient(#fff4e8, #fff4e8) padding-box,
                linear-gradient(135deg, var(--or1), var(--or2), var(--or3), var(--or1)) border-box;
            background-size: 100% 100%, 300% 300%;
            animation: orShift 4s ease infinite;
        }

        .sidebar-brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .sidebar-brand-name {
            font-size: .9rem;
            font-weight: 900;
            color: var(--txt);
            letter-spacing: -.3px;
        }

        .sidebar-brand-sub {
            font-size: .62rem;
            font-weight: 600;
            color: var(--mut);
            text-transform: uppercase;
            letter-spacing: .8px;
        }

        .sidebar-close-btn {
            margin-left: auto;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--mut);
            font-size: 1.1rem;
            padding: 4px;
            border-radius: 8px;
            transition: background .15s;
            display: flex;
            /* tampil di desktop (collapse) maupun mobile (tutup) */
        }

        .sidebar-close-btn:hover {
            background: #f1f5f9;
        }

        .sidebar-new-chat-wrap {
            padding: 14px 12px 8px;
            flex-shrink: 0;
        }

        .btn-new-chat {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 14px;
            border: none;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-size: .82rem;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, var(--or1), var(--or2), var(--or3), var(--or1));
            background-size: 300% 300%;
            animation: orShift 5s ease infinite;
            box-shadow: 0 4px 16px rgba(255, 106, 0, .3);
            transition: transform .15s, box-shadow .15s;
        }

        .btn-new-chat:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255, 106, 0, .4);
        }

        .btn-new-chat i {
            font-size: 1rem;
        }

        .sidebar-history-label {
            padding: 10px 16px 4px;
            font-size: .62rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--mut);
            flex-shrink: 0;
        }

        .sidebar-sessions {
            flex: 1;
            overflow-y: auto;
            padding: 4px 8px 8px;
        }

        .sidebar-sessions::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-sessions::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 4px;
        }

        .session-item {
            display: flex;
            align-items: center;
            gap: 0;
            padding: 9px 8px 9px 10px;
            border-radius: 12px;
            cursor: pointer;
            margin-bottom: 3px;
            transition: background .15s;
            position: relative;
        }

        .session-item:hover {
            background: #f8fafc;
        }

        .session-item.active {
            background: linear-gradient(135deg, rgba(255, 106, 0, .08), rgba(255, 154, 0, .06));
            border: 1px solid rgba(255, 106, 0, .15);
        }

        .session-item-body {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            min-width: 0;
        }

        .session-icon {
            color: var(--mut);
            font-size: .85rem;
            flex-shrink: 0;
        }

        .session-item.active .session-icon {
            color: var(--or1);
        }

        .session-title {
            font-size: .78rem;
            font-weight: 500;
            color: var(--txt);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .session-item.active .session-title {
            font-weight: 700;
            color: var(--or1);
        }

        .session-delete-btn {
            flex-shrink: 0;
            opacity: 0;
            background: none;
            border: none;
            cursor: pointer;
            color: #ef4444;
            font-size: .8rem;
            padding: 4px 6px;
            border-radius: 8px;
            transition: opacity .15s, background .15s;
        }

        .session-item:hover .session-delete-btn {
            opacity: 1;
        }

        .session-delete-btn:hover {
            background: #fef2f2;
        }

        .session-item:focus-visible {
            outline: 2px solid var(--or1);
            outline-offset: 1px;
        }

        /* Perangkat sentuh tidak punya hover → tombol hapus harus selalu terlihat */
        @media (hover: none) {
            .session-delete-btn {
                opacity: .7;
            }
        }

        .sessions-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            padding: 32px 16px;
            color: var(--mut);
            font-size: .78rem;
            text-align: center;
        }

        .sessions-empty i {
            font-size: 2rem;
            opacity: .3;
        }

        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid var(--brd);
            flex-shrink: 0;
        }

        .sidebar-back-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 12px;
            border-radius: 12px;
            text-decoration: none;
            color: var(--mut);
            font-size: .78rem;
            font-weight: 600;
            transition: background .15s, color .15s;
        }

        .sidebar-back-btn:hover {
            background: #f1f5f9;
            color: var(--or1);
        }

        .sidebar-back-btn i {
            font-size: 1rem;
        }

        /* ── MAIN PANEL ────────────────────────────────────────────── */
        .ai-main {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        /* Topbar */
        .ai-topbar {
            height: var(--topbar-h);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            background: rgba(255, 255, 255, .9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--brd);
            flex-shrink: 0;
            gap: 12px;
        }

        .ai-topbar-left {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            min-width: 0;
        }

        .btn-sidebar-toggle {
            display: flex;
            /* tampil di semua ukuran layar */
            background: none;
            border: none;
            cursor: pointer;
            color: var(--mut);
            font-size: 1.3rem;
            padding: 4px;
            border-radius: 8px;
            transition: background .15s, color .15s;
        }

        .btn-sidebar-toggle:hover {
            background: #f1f5f9;
            color: var(--or1);
        }

        .ai-topbar-title {
            flex: 1 1 0;
            min-width: 0;
            font-size: .95rem;
            font-weight: 800;
            color: var(--txt);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 300px;
        }

        .ai-topbar-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 100px;
            font-size: .62rem;
            font-weight: 800;
            letter-spacing: .5px;
            color: #fff;
            background: linear-gradient(135deg, var(--or1), var(--or2), var(--or3), var(--or1));
            background-size: 300% 300%;
            animation: orShift 4s ease infinite;
            box-shadow: 0 2px 8px rgba(255, 106, 0, .25);
            flex-shrink: 0;
        }

        .ai-topbar-badge i {
            animation: sparkle 2s ease-in-out infinite;
        }

        .ai-topbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .btn-topbar-icon {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--mut);
            font-size: 1.1rem;
            padding: 6px 8px;
            border-radius: 10px;
            transition: background .15s, color .15s;
        }

        .btn-topbar-icon:hover {
            background: #f1f5f9;
            color: var(--or1);
        }

        /* Chat area */
        .ai-chat-area {
            flex: 1;
            overflow-y: auto;
            padding: 24px 20px 8px;
            display: flex;
            flex-direction: column;
            gap: 0;
            scroll-behavior: smooth;
        }

        .ai-chat-area::-webkit-scrollbar {
            width: 5px;
        }

        .ai-chat-area::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 4px;
        }

        /* Inner wrapper — batasi lebar konten percakapan */
        .chat-inner {
            width: 100%;
            max-width: 780px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        /* ── Welcome screen ── */
        .ai-welcome {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex: 1;
            padding: 40px 20px;
            text-align: center;
            gap: 20px;
            animation: fadeUp .5s ease;
        }

        .ai-welcome-orb {
            width: 92px;
            height: 92px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            border: 3px solid transparent;
            background:
                linear-gradient(#fff4e8, #fff4e8) padding-box,
                linear-gradient(135deg, var(--or1), var(--or2), var(--or3), var(--or1)) border-box;
            background-size: 100% 100%, 300% 300%;
            animation: orShift 4s ease infinite;
            box-shadow: 0 8px 32px rgba(255, 106, 0, .35);
            position: relative;
        }

        .ai-welcome-orb::after {
            content: '';
            position: absolute;
            inset: -12px;
            border-radius: 40px;
            border: 2px solid rgba(255, 106, 0, .2);
            animation: pulse-ring 2.5s ease-in-out infinite;
            pointer-events: none;
        }

        .ai-welcome-title {
            font-size: clamp(1.3rem, 3vw, 1.9rem);
            font-weight: 900;
            line-height: 1.2;
        }

        .ai-welcome-sub {
            font-size: .85rem;
            color: var(--mut);
            max-width: 440px;
            line-height: 1.7;
        }

        .ai-welcome-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
            max-width: 520px;
        }

        .ai-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            /* ← tambahan */
            padding: 6px 14px;
            border-radius: 100px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            /* ← tambahan */
            font-size: .75rem;
            font-weight: 600;
            border: 1.5px solid rgba(255, 106, 0, .2);
            background: rgba(255, 106, 0, .05);
            color: var(--or1);
            transition: all .2s;
        }

        .ai-chip i {
            font-size: .9rem;
        }

        .ai-chip:hover {
            background: var(--or1);
            color: #fff;
            box-shadow: 0 4px 12px rgba(255, 106, 0, .25);
            transform: translateY(-2px);
        }

        /* ── Pesan ── */
        .msg-wrap {
            display: flex;
            gap: 10px;
            margin-bottom: 18px;
            animation: fadeUp .3s ease;
        }

        .msg-wrap.user {
            flex-direction: row-reverse;
        }

        .msg-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .85rem;
            font-weight: 700;
        }

        .msg-avatar.ai-av {
            background: #fff4e8;
            box-shadow: 0 0 0 1.5px rgba(255, 106, 0, .4);
            padding: 3px;
            color: #fff;
        }

        .msg-avatar.user-av {
            background: #f1f5f9;
            color: var(--mut);
            font-size: 1rem;
        }

        .msg-bubble {
            max-width: min(68%, 620px);
            padding: 12px 16px;
            border-radius: 18px;
            font-size: .88rem;
            line-height: 1.7;
            word-break: break-word;
        }

        .msg-wrap.ai .msg-bubble {
            background: var(--card);
            border: 1px solid var(--brd);
            border-top-left-radius: 4px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .04);
            color: var(--txt);
        }

        .msg-wrap.user .msg-bubble {
            background: linear-gradient(135deg, var(--or1), var(--or2));
            color: #fff;
            border-top-right-radius: 4px;
            box-shadow: 0 4px 16px rgba(255, 106, 0, .25);
        }

        /* Markdown dalam bubble AI */
        .msg-bubble p {
            margin-bottom: .5em;
        }

        .msg-bubble p:last-child {
            margin-bottom: 0;
        }

        .msg-bubble ul,
        .msg-bubble ol {
            padding-left: 1.4em;
            margin-bottom: .5em;
        }

        .msg-bubble li {
            margin-bottom: .2em;
        }

        .msg-bubble strong {
            font-weight: 700;
        }

        .msg-bubble code {
            background: #f1f5f9;
            padding: 1px 5px;
            border-radius: 5px;
            font-size: .82em;
            font-family: 'Courier New', monospace;
        }

        .msg-bubble pre {
            background: #1e293b;
            color: #e2e8f0;
            padding: 12px 14px;
            border-radius: 10px;
            overflow-x: auto;
            font-size: .8em;
            margin: .5em 0;
        }

        .msg-bubble pre code {
            background: none;
            padding: 0;
            color: inherit;
        }

        .msg-bubble h1,
        .msg-bubble h2,
        .msg-bubble h3 {
            font-weight: 700;
            margin: .6em 0 .3em;
        }

        .msg-bubble hr {
            border-color: var(--brd);
            margin: .6em 0;
        }

        /* Typing indicator */
        .typing-indicator {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 14px 16px;
        }

        .typing-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--or2);
            animation: blink-dot 1.4s ease-in-out infinite;
        }

        .typing-dot:nth-child(2) {
            animation-delay: .2s;
        }

        .typing-dot:nth-child(3) {
            animation-delay: .4s;
        }

        /* ── Input area ── */
        .ai-input-area {
            position: relative;
            flex-shrink: 0;
            padding: 12px 20px 18px;
            background: rgba(255, 255, 255, .9);
            backdrop-filter: blur(12px);
            border-top: 1px solid var(--brd);
        }

        .ai-input-wrap {
            display: flex;
            align-items: flex-end;
            gap: 10px;
            background: var(--card);
            border: 1.5px solid var(--brd);
            border-radius: 20px;
            padding: 10px 14px;
            box-shadow: 0 2px 16px rgba(0, 0, 0, .06);
            transition: border-color .2s, box-shadow .2s;
            max-width: 780px;
            margin: 0 auto;
        }

        .ai-input-wrap:focus-within {
            border-color: var(--or1);
            box-shadow: 0 0 0 3px rgba(255, 106, 0, .1), 0 4px 20px rgba(0, 0, 0, .08);
        }

        #chatInput {
            flex: 1;
            border: none;
            outline: none;
            resize: none;
            overflow: hidden;
            background: transparent;
            font-family: 'Poppins', sans-serif;
            font-size: .88rem;
            line-height: 1.6;
            color: var(--txt);
            max-height: 200px;
            /* batas tinggi sebelum scroll internal */
            min-height: 24px;
        }

        #chatInput::placeholder {
            color: #94a3b8;
        }

        .btn-send {
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            border-radius: 14px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            color: #fff;
            background: linear-gradient(135deg, var(--or1), var(--or2), var(--or3), var(--or1));
            background-size: 300% 300%;
            animation: orShift 5s ease infinite;
            box-shadow: 0 4px 12px rgba(255, 106, 0, .3);
            transition: transform .15s, box-shadow .15s, opacity .15s;
        }

        .btn-send:hover {
            transform: scale(1.08);
            box-shadow: 0 6px 18px rgba(255, 106, 0, .4);
        }

        .btn-send:disabled {
            opacity: .45;
            cursor: not-allowed;
            transform: none;
        }

        .input-hint {
            text-align: center;
            font-size: .67rem;
            color: #94a3b8;
            margin-top: 7px;
            letter-spacing: .2px;
            max-width: 780px;
            margin-left: auto;
            margin-right: auto;
        }

        /* ── Overlay sidebar mobile ── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .35);
            z-index: 150;
            backdrop-filter: blur(2px);
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* ═══════════════════════════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════════════════════════ */

        /* ── Topbar: pastikan kanan tidak terkubur di layar sempit ── */

        @media (max-width: 768px) {

            /* Sidebar jadi overlay mobile */
            .ai-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                width: var(--sb-w);
                transform: translateX(-100%);
                margin-left: 0 !important;
                /* override collapsed desktop */
                box-shadow: 4px 0 24px rgba(0, 0, 0, .12);
                z-index: 200;
                transition: transform .3s cubic-bezier(.4, 0, .2, 1);
            }

            /* Saat JS menambahkan .open */
            .ai-sidebar.open {
                transform: translateX(0);
            }

            /* Jangan terapkan .collapsed di mobile */
            .ai-sidebar.collapsed {
                transform: translateX(-100%);
                margin-left: 0 !important;
            }

            .btn-sidebar-toggle {
                display: flex;
            }

            .sidebar-close-btn {
                display: flex;
            }

            /* Topbar sempit */
            .ai-topbar {
                padding: 0 12px;
                gap: 8px;
            }

            .ai-topbar-title {
                font-size: .82rem;
                max-width: 120px;
            }

            .ai-topbar-badge {
                display: none;
            }

            /* sembunyikan badge agar tidak berdesakan */
            .btn-topbar-icon {
                padding: 4px 6px;
                font-size: 1rem;
            }

            .msg-bubble {
                max-width: 85%;
            }
        }

        @media (max-width: 480px) {
            .ai-chat-area {
                padding: 16px 12px 8px;
            }

            .ai-input-area {
                padding: 10px 12px 14px;
            }

            .ai-welcome-title {
                font-size: 1.2rem;
            }

            .ai-topbar-title {
                max-width: 90px;
                font-size: .78rem;
            }

            .btn-sidebar-toggle {
                font-size: 1.1rem;
            }
        }

        /* ═══════════════════════════════════════════════════════════════
   WAJAH ROBOT FLAVORY (konsisten dengan components/robot.blade.php)
   Semua class diawali "fa-" agar tidak bentrok.
═══════════════════════════════════════════════════════════════ */
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

        /* ═══════════════════════════════════════════════════════════════
   VOICE-TO-TEXT
═══════════════════════════════════════════════════════════════ */
        .btn-mic {
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            border-radius: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: var(--or1);
            border: 1.5px solid rgba(255, 106, 0, .25);
            background: rgba(255, 106, 0, .06);
            transition: transform .15s, background .15s, box-shadow .15s, color .15s, opacity .15s;
        }

        .btn-mic:hover {
            background: rgba(255, 106, 0, .13);
            transform: scale(1.08);
        }

        .btn-mic:disabled {
            opacity: .45;
            cursor: not-allowed;
            transform: none;
        }

        .btn-mic:focus-visible {
            outline: 2px solid var(--or1);
            outline-offset: 2px;
        }

        .btn-mic.listening {
            color: #fff;
            border-color: transparent;
            background: linear-gradient(135deg, var(--or1), var(--or2), var(--or3), var(--or1));
            background-size: 300% 300%;
            animation: orShift 3s ease infinite, micPulse 1.5s ease-out infinite;
        }

        @keyframes micPulse {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 106, 0, .45);
            }

            100% {
                box-shadow: 0 0 0 14px rgba(255, 106, 0, 0);
            }
        }

        .voice-status {
            position: absolute;
            left: 50%;
            bottom: calc(100% + 10px);
            transform: translate(-50%, 8px);
            display: flex;
            align-items: center;
            gap: 10px;
            max-width: calc(100% - 24px);
            padding: 8px 8px 8px 14px;
            border-radius: 100px;
            background: var(--card);
            border: 1.5px solid rgba(255, 106, 0, .25);
            box-shadow: 0 8px 28px rgba(255, 106, 0, .18), 0 2px 8px rgba(0, 0, 0, .06);
            font-size: .76rem;
            font-weight: 600;
            color: var(--txt);
            opacity: 0;
            pointer-events: none;
            z-index: 5;
            transition: opacity .2s ease, transform .25s cubic-bezier(.34, 1.56, .64, 1);
        }

        .voice-status.show {
            opacity: 1;
            transform: translate(-50%, 0);
            pointer-events: auto;
        }

        .voice-wave {
            display: flex;
            align-items: center;
            gap: 3px;
            height: 20px;
            flex-shrink: 0;
        }

        .voice-wave span {
            width: 3px;
            height: 16px;
            border-radius: 3px;
            background: linear-gradient(180deg, var(--or1), var(--or3));
            transform: scaleY(.3);
            animation: voiceBar 1.1s ease-in-out infinite;
        }

        .voice-wave span:nth-child(2) {
            animation-delay: .12s;
        }

        .voice-wave span:nth-child(3) {
            animation-delay: .24s;
        }

        .voice-wave span:nth-child(4) {
            animation-delay: .36s;
        }

        .voice-wave span:nth-child(5) {
            animation-delay: .48s;
        }

        .voice-status.hearing .voice-wave span {
            animation-duration: .45s;
        }

        @keyframes voiceBar {

            0%,
            100% {
                transform: scaleY(.3);
            }

            50% {
                transform: scaleY(1);
            }
        }

        .voice-status-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            min-width: 0;
        }

        .voice-done {
            flex-shrink: 0;
            border: none;
            cursor: pointer;
            padding: 5px 12px;
            border-radius: 100px;
            font-family: 'Poppins', sans-serif;
            font-size: .7rem;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, var(--or1), var(--or2));
            transition: transform .15s;
        }

        .voice-done:hover {
            transform: scale(1.05);
        }

        @media (prefers-reduced-motion: reduce) {

            .voice-wave span,
            .btn-mic.listening {
                animation: none !important;
            }

            .voice-wave span {
                transform: scaleY(.7);
            }
        }

        .swal-flavory {
            font-family: 'Poppins', sans-serif;
            border-radius: 20px;
        }

        .swal2-title {
            font-weight: 800;
        }
    </style>
</head>

<body>

    {{-- Gradien wajah robot (dipakai bersama oleh semua <svg class="fa-svg">) --}}
    <svg width="0" height="0" style="position:absolute;overflow:hidden" aria-hidden="true" focusable="false">
        <defs>
            <linearGradient id="fa-g-head" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0" stop-color="#FFB460" />
                <stop offset="1" stop-color="#F26A0F" />
            </linearGradient>
        </defs>
    </svg>

    {{-- Template wajah robot — di-clone oleh JS untuk avatar, welcome & sidebar --}}
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

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="ai-shell">

        {{-- ══ SIDEBAR ══ --}}
        @include('owner.ai.sidebar', [
            'sessions' => $sessions,
            'activeSession' => $activeSession,
        ])

        {{-- ══ MAIN ══ --}}
        <div class="ai-main">

            {{-- Topbar --}}
            <header class="ai-topbar">
                <div class="ai-topbar-left">
                    <button class="btn-sidebar-toggle" id="btnSidebarToggle" aria-label="Buka sidebar">
                        <i class="bi bi-layout-sidebar"></i>
                    </button>
                    <span class="ai-topbar-title" id="topbarTitle">
                        {{ $activeSession ? $activeSession->title : 'FlavoryAI' }}
                    </span>
                </div>
                <div class="ai-topbar-right">
                    <span class="ai-topbar-badge">
                        <i class="bi bi-stars"></i> FlavoryAI
                    </span>
                    <button class="btn-topbar-icon" id="btnScrollDown" title="Ke bawah">
                        <i class="bi bi-arrow-down-circle"></i>
                    </button>
                </div>
            </header>

            {{-- Chat area --}}
            <div class="ai-chat-area" id="chatArea">
                <div class="chat-inner" id="chatInner">

                    @if (!$activeSession)
                        {{-- Welcome screen --}}
                        <div class="ai-welcome" id="welcomeScreen">
                            <div class="ai-welcome-orb"><span class="fa-face" data-global data-fa-hydrate
                                    data-exp="smile"></span></div>
                            <div>
                                <h1 class="ai-welcome-title">
                                    Halo, Bos <span class="ai-gradient-text">{{ $ownerName }}</span>!
                                </h1>
                                <p class="ai-welcome-sub">
                                    Saya <strong>FlavoryAI</strong> | siap bantu analisis bisnis outlet Anda
                                    secara real-time.
                                    Tanya apa saja, dari menu terlaris hingga strategi promosi!
                                </p>
                            </div>
                            <div class="ai-welcome-chips">
                                <button type="button" class="ai-chip"
                                    data-prompt="Menu apa yang paling laris bulan ini, Bos?">
                                    <i class="bi bi-cup-hot"></i> Menu terlaris bulan ini
                                </button>
                                <button type="button" class="ai-chip"
                                    data-prompt="Bagaimana performa penjualan 30 hari terakhir?">
                                    <i class="bi bi-bar-chart-line"></i> Performa penjualan 30 hari
                                </button>
                                <button type="button" class="ai-chip"
                                    data-prompt="Apa saran kamu untuk meningkatkan omzet outlet saya?">
                                    <i class="bi bi-rocket-takeoff"></i> Saran tingkatkan omzet
                                </button>
                                <button type="button" class="ai-chip"
                                    data-prompt="Apakah ada menu yang perlu saya pertimbangkan untuk dihapus atau direvisi harganya?">
                                    <i class="bi bi-lightbulb"></i> Evaluasi menu &amp; harga
                                </button>
                                <button type="button" class="ai-chip"
                                    data-prompt="Bagaimana kondisi stok barang saya sekarang?">
                                    <i class="bi bi-box-seam"></i> Cek kondisi stok
                                </button>
                                <button type="button" class="ai-chip"
                                    data-prompt="Berikan ringkasan keuangan outlet saya bulan ini.">
                                    <i class="bi bi-cash-coin"></i> Ringkasan keuangan
                                </button>
                            </div>
                        </div>
                    @else
                        {{-- Muat pesan sesi aktif via JS setelah load --}}
                        <div id="welcomeScreen" style="display:none;"></div>
                    @endif

                </div>{{-- /chat-inner --}}
            </div>{{-- /chat-area --}}

            {{-- Input area --}}
            <div class="ai-input-area">
                {{-- Indikator voice-to-text (melayang di atas kolom input, tidak menggeser layout) --}}
                <div class="voice-status" id="voiceStatus" role="status" aria-live="polite">
                    <div class="voice-wave" aria-hidden="true">
                        <span></span><span></span><span></span><span></span><span></span></div>
                    <span class="voice-status-text" id="voiceStatusText">Mendengarkan… silakan bicara</span>
                    <button type="button" class="voice-done" id="btnVoiceDone">Selesai</button>
                </div>
                <div class="ai-input-wrap">
                    <textarea id="chatInput" rows="1" placeholder="Tanya apa saja tentang bisnis Anda, Bos…" autocomplete="off"
                        aria-label="Tulis pesan"></textarea>
                    <button type="button" class="btn-mic" id="btnMic" aria-label="Bicara (voice to text)"
                        aria-pressed="false" title="Bicara (voice to text)">
                        <i class="bi bi-mic-fill"></i>
                    </button>
                    <button class="btn-send" id="btnSend" aria-label="Kirim">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </div>
                <p class="input-hint">Tekan <kbd>Enter</kbd> kirim · <kbd>Shift+Enter</kbd> baris baru · FlavoryAI
                    membaca data outlet Anda secara real-time</p>
            </div>

        </div>{{-- /ai-main --}}
    </div>{{-- /ai-shell --}}

    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        const CHAT_URL = @json(route('owner.ai.chat'));
        const SESSIONS_URL = @json(route('owner.ai.sessions'));
        const LOAD_URL = @json(url('owner/ai/session'));
        const DEL_URL = @json(url('owner/ai/session'));
        const OWNER_NAME = @json($ownerName);

        let currentSessionUuid = @json($activeSession ? $activeSession->uuid : null);
        let isStreaming = false;
        let loadToken = 0; // mencegah balapan jika user klik sesi berkali-kali

        /* ═══════════════════════════════════════════════════════════════
           DOM REFS
        ═══════════════════════════════════════════════════════════════ */
        const chatArea = document.getElementById('chatArea');
        const chatInner = document.getElementById('chatInner');
        const chatInput = document.getElementById('chatInput');
        const btnSend = document.getElementById('btnSend');
        const topbarTitle = document.getElementById('topbarTitle');
        const sidebarEl = document.getElementById('aiSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const sidebarSess = document.getElementById('sidebarSessions');

        function getWelcome() {
            return document.getElementById('welcomeScreen');
        }

        /* ═══════════════════════════════════════════════════════════════
           WAJAH ROBOT: hydrate, ekspresi, mata mengikuti kursor
        ═══════════════════════════════════════════════════════════════ */
        const uiState = {
            listening: false,
            phase: 'idle',
            flash: null,
            flashT: 0
        };

        function faceSvg() {
            return document.getElementById('tplRobotFace').innerHTML;
        }

        // Markup wajah untuk dipakai di innerHTML (avatar pesan, typing, dll)
        function faceHTML(exp = 'smile') {
            const bd = (-Math.random() * 4.6).toFixed(2); // kedip tidak serempak
            return `<span class="fa-face" data-exp="${exp}" style="--bd:${bd}s">${faceSvg()}</span>`;
        }

        // Isi placeholder <span data-fa-hydrate> (sidebar & welcome screen)
        function hydrateFaces(root = document) {
            root.querySelectorAll('[data-fa-hydrate]').forEach(el => {
                el.removeAttribute('data-fa-hydrate');
                el.innerHTML = faceSvg();
                el.style.setProperty('--bd', (-Math.random() * 4.6).toFixed(2) + 's');
            });
            applyGlobal();
        }

        // Ekspresi wajah "global" (welcome + sidebar) mengikuti status aplikasi
        function applyGlobal() {
            const exp = uiState.listening ? 'listen' :
                uiState.phase === 'think' ? 'think' :
                uiState.phase === 'talk' ? 'talk' :
                (uiState.flash || 'smile');
            document.querySelectorAll('.fa-face[data-global]').forEach(f => {
                f.dataset.exp = exp;
            });
        }

        function setPhase(p) {
            uiState.phase = p;
            applyGlobal();
        }

        function setFaceExp(face, exp) {
            if (face) face.dataset.exp = exp;
        }

        // Ekspresi sesaat lalu kembali normal
        function poke(face, exp = 'happy', ms = 1500) {
            if (!face) return;
            if (face.hasAttribute('data-global')) {
                flashGlobal(exp, ms);
                return;
            }
            clearTimeout(face._t);
            face.dataset.exp = exp;
            face._t = setTimeout(() => {
                face.dataset.exp = 'smile';
            }, ms);
        }

        function flashGlobal(exp = 'happy', ms = 1500) {
            clearTimeout(uiState.flashT);
            uiState.flash = exp;
            applyGlobal();
            uiState.flashT = setTimeout(() => {
                uiState.flash = null;
                applyGlobal();
            }, ms);
        }

        // Klik / tap wajah robot → senang
        document.addEventListener('click', e => {
            const f = e.target.closest('.fa-face');
            if (f) poke(f, 'happy', 1500);
        });

        // Mata + kepala mengikuti kursor / sentuhan
        const faceTrack = {
            x: 0,
            y: 0,
            raf: 0,
            last: Date.now()
        };
        const clamp = (v, a, b) => Math.max(a, Math.min(b, v));

        function lookAt(f, nx, ny) {
            f.style.setProperty('--px', (nx * 4.5).toFixed(2) + 'px');
            f.style.setProperty('--py', (ny * 3.2).toFixed(2) + 'px');
            f.style.setProperty('--tilt', (nx * 4).toFixed(1) + 'deg');
        }

        function updateFaces() {
            faceTrack.raf = 0;
            const vw = window.innerWidth,
                vh = window.innerHeight;
            const batch = [];
            document.querySelectorAll('.fa-face').forEach(f => { // baca semua dulu…
                const r = f.getBoundingClientRect();
                if (!r.width || r.bottom < 0 || r.top > vh || r.right < 0 || r.left > vw) return;
                batch.push([f, r]);
            });
            batch.forEach(([f, r]) => { // …baru tulis
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
        document.addEventListener('pointermove', onPointer, {
            passive: true
        });
        document.addEventListener('pointerdown', onPointer, {
            passive: true
        });

        // Saat tidak ada gerakan: wajah utama melirik ke sekitar (terasa "hidup")
        setInterval(() => {
            if (Date.now() - faceTrack.last < 4000 || document.hidden) return;
            document.querySelectorAll('.fa-face[data-global]').forEach(f => {
                lookAt(f, Math.random() * 2 - 1, (Math.random() * 2 - 1) * .6);
            });
        }, 3500);

        hydrateFaces(document);


        /* ═══════════════════════════════════════════════════════════════
           SIDEBAR TOGGLE
        ═══════════════════════════════════════════════════════════════ */
        const isMobile = () => window.innerWidth <= 768;

        function openSidebar() {
            sidebarEl.classList.add('open');
            overlay.classList.add('show');
        }

        function closeSidebar() {
            sidebarEl.classList.remove('open');
            overlay.classList.remove('show');
        } // khusus mobile

        function setCollapsed(state) {
            sidebarEl.classList.toggle('collapsed', state);
            try {
                localStorage.setItem('flavoryAiSidebarCollapsed', state ? '1' : '0');
            } catch (e) {}
        }

        function toggleSidebar() {
            if (isMobile()) {
                sidebarEl.classList.contains('open') ? closeSidebar() : openSidebar();
            } else {
                setCollapsed(!sidebarEl.classList.contains('collapsed'));
            }
        }

        // Tombol X di sidebar: mobile → tutup panel, desktop → collapse
        function hideSidebarByButton() {
            if (isMobile()) closeSidebar();
            else setCollapsed(true);
        }

        document.getElementById('btnSidebarToggle').addEventListener('click', toggleSidebar);
        document.getElementById('btnCloseSidebar').addEventListener('click', hideSidebarByButton);
        overlay.addEventListener('click', closeSidebar);
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && isMobile()) closeSidebar();
        });

        // Pulihkan status collapse (desktop saja — mobile tidak pakai collapsed)
        try {
            if (!isMobile() && localStorage.getItem('flavoryAiSidebarCollapsed') === '1') {
                sidebarEl.classList.add('collapsed');
            }
        } catch (e) {}

        // Jika layar diubah ukurannya
        window.addEventListener('resize', () => {
            if (!isMobile()) {
                // Pindah ke desktop: hilangkan state mobile (open/overlay)
                sidebarEl.classList.remove('open');
                overlay.classList.remove('show');
            } else {
                // Pindah ke mobile: hilangkan state desktop (collapsed) agar CSS override bekerja
                sidebarEl.classList.remove('collapsed');
                overlay.classList.remove('show');
            }
        });

        /* ═══════════════════════════════════════════════════════════════
           AUTO-RESIZE TEXTAREA (tanpa scrollbar)
        ═══════════════════════════════════════════════════════════════ */
        chatInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 200) + 'px';
        });

        /* ═══════════════════════════════════════════════════════════════
           KIRIM DENGAN ENTER
        ═══════════════════════════════════════════════════════════════ */
        chatInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (!isStreaming) sendMessage();
            }
        });
        btnSend.addEventListener('click', () => {
            if (!isStreaming) sendMessage();
        });

        /* ═══════════════════════════════════════════════════════════════
           SCROLL TO BOTTOM
        ═══════════════════════════════════════════════════════════════ */
        function scrollBottom(smooth = true) {
            chatArea.scrollTo({
                top: chatArea.scrollHeight,
                behavior: smooth ? 'smooth' : 'instant'
            });
        }
        document.getElementById('btnScrollDown').addEventListener('click', () => scrollBottom(true));

        /* ═══════════════════════════════════════════════════════════════
           RENDER MARKDOWN AMAN
        ═══════════════════════════════════════════════════════════════ */
        function renderMd(text) {
            if (!text) return '';
            if (typeof marked === 'undefined') {
                // marked belum load — fallback ke plain text dengan newline
                return text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\n/g, '<br>');
            }
            try {
                return marked.parse(text, {
                    breaks: true,
                    gfm: true
                });
            } catch (e) {
                return text.replace(/\n/g, '<br>');
            }
        }

        /* ═══════════════════════════════════════════════════════════════
           BUAT ELEMEN PESAN
        ═══════════════════════════════════════════════════════════════ */
        function createMsgEl(role, content = '', exp = 'smile') {
            const wrap = document.createElement('div');
            wrap.className = `msg-wrap ${role}`;

            const avatar = document.createElement('div');
            avatar.className = `msg-avatar ${role === 'ai' ? 'ai-av' : 'user-av'}`;
            avatar.innerHTML = role === 'ai' ? faceHTML(exp) : '<i class="bi bi-person-fill"></i>';

            const bubble = document.createElement('div');
            bubble.className = 'msg-bubble';
            if (role === 'ai') {
                bubble.innerHTML = content ? renderMd(content) : '';
            } else {
                bubble.textContent = content;
            }

            // Untuk kedua role, urutan DOM selalu avatar → bubble.
            // CSS flex-direction: row-reverse pada .msg-wrap.user akan membalik tampilannya
            // menjadi bubble (kiri) → avatar (kanan) secara visual.
            wrap.appendChild(avatar);
            wrap.appendChild(bubble);
            return {
                wrap,
                bubble
            };
        }

        /* ═══════════════════════════════════════════════════════════════
           TYPING INDICATOR
        ═══════════════════════════════════════════════════════════════ */
        function createTypingEl() {
            const wrap = document.createElement('div');
            wrap.className = 'msg-wrap ai';
            wrap.id = 'typingIndicator';

            const avatar = document.createElement('div');
            avatar.className = 'msg-avatar ai-av';
            avatar.innerHTML = faceHTML('think');

            const bubble = document.createElement('div');
            bubble.className = 'msg-bubble typing-indicator';
            bubble.innerHTML = '<div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div>';

            wrap.appendChild(avatar);
            wrap.appendChild(bubble);
            return wrap;
        }

        /* ═══════════════════════════════════════════════════════════════
           KIRIM PESAN & STREAM SSE
        ═══════════════════════════════════════════════════════════════ */
        async function sendMessage() {
            const text = chatInput.value.trim();
            if (!text || isStreaming) return;
            stopVoice(true); // hentikan perekaman suara bila masih aktif

            // Hapus welcome screen (selalu cari ulang — elemennya bisa dibuat ulang oleh "Chat Baru")
            const ws = getWelcome();
            if (ws) ws.remove();

            // Tampilkan pesan user
            const {
                wrap: userWrap
            } = createMsgEl('user', text);
            chatInner.appendChild(userWrap);

            // Reset input
            chatInput.value = '';
            chatInput.style.height = 'auto';
            scrollBottom();

            // Set state loading
            isStreaming = true;
            btnSend.disabled = true;
            chatInput.disabled = true;
            btnMic.disabled = true;
            setPhase('think');

            // Typing indicator
            const typingEl = createTypingEl();
            chatInner.appendChild(typingEl);
            scrollBottom();

            // Buat elemen bubble AI (kosong dulu)
            let aiText = '';
            let aiBubble = null;
            let aiWrap = null;
            let firstDelta = true;
            let aiFace = null;
            let hadError = false;

            try {
                const resp = await fetch(CHAT_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'text/event-stream',
                    },
                    body: JSON.stringify({
                        message: text,
                        session_uuid: currentSessionUuid,
                    }),
                });

                if (!resp.ok) throw new Error('HTTP ' + resp.status);

                const reader = resp.body.getReader();
                const dec = new TextDecoder();
                let buf = '';
                let streamDone = false;

                while (!streamDone) {
                    const {
                        done,
                        value
                    } = await reader.read();
                    if (done) break;

                    buf += dec.decode(value, {
                        stream: true
                    });
                    const lines = buf.split('\n');
                    buf = lines.pop(); // simpan baris tidak lengkap

                    for (const line of lines) {
                        const trimmed = line.trim();
                        if (!trimmed.startsWith('data:')) continue;

                        const raw = trimmed.slice(5).trim();
                        if (raw === '[DONE]') {
                            streamDone = true;
                            break; // keluar dari for
                        }

                        let parsed;
                        try {
                            parsed = JSON.parse(raw);
                        } catch {
                            continue;
                        }

                        // UUID sesi dari server — simpan, update URL, dan refresh sidebar SEKALI saja
                        if (parsed.session_uuid && parsed.session_uuid !== currentSessionUuid) {
                            currentSessionUuid = parsed.session_uuid;
                            // Update URL agar reload halaman tetap kembali ke sesi ini
                            history.replaceState({
                                    sessionUuid: currentSessionUuid
                                }, '',
                                `${window.location.pathname}?session=${currentSessionUuid}`);
                            // Defer refresh agar tidak ganggu loop render
                            setTimeout(refreshSidebarSessions, 0);
                            if (parsed.delta === undefined && !parsed.error) continue; // packet hanya berisi uuid
                        }

                        if (parsed.error) {
                            if (typingEl.parentNode) typingEl.remove();
                            const {
                                wrap
                            } = createMsgEl('ai', '⚠️ ' + parsed.error, 'sad');
                            hadError = true;
                            chatInner.appendChild(wrap);
                            scrollBottom();
                            streamDone = true;
                            break;
                        }

                        if (parsed.delta !== undefined) {
                            // Pertama kali: hapus typing, buat bubble AI
                            if (firstDelta) {
                                if (typingEl.parentNode) typingEl.remove();
                                const els = createMsgEl('ai', '');
                                aiWrap = els.wrap;
                                aiBubble = els.bubble;
                                chatInner.appendChild(aiWrap);
                                aiFace = aiWrap.querySelector('.fa-face');
                                setFaceExp(aiFace, 'talk');
                                setPhase('talk');
                                firstDelta = false;
                            }
                            aiText += parsed.delta;
                            aiBubble.innerHTML = renderMd(aiText);
                            scrollBottom(false);
                        }
                    }
                }

                // Pastikan typing indicator dihapus jika AI tidak pernah balas
                if (typingEl.parentNode) typingEl.remove();
            } catch (err) {
                typingEl.remove();
                const {
                    wrap
                } = createMsgEl('ai', '⚠️ Gagal terhubung ke FlavoryAI. Pastikan koneksi internet aktif dan coba lagi.',
                    'sad');
                hadError = true;
                chatInner.appendChild(wrap);
                scrollBottom();
                console.error('FlavoryAI stream error:', err);
            } finally {
                isStreaming = false;
                btnSend.disabled = false;
                chatInput.disabled = false;
                btnMic.disabled = false;
                setPhase('idle');
                if (!hadError) {
                    if (aiFace) poke(aiFace, 'happy', 1600);
                    else setFaceExp(aiFace, 'smile');
                    flashGlobal('happy', 1600);
                } else if (aiFace) {
                    setFaceExp(aiFace, 'sad');
                }
                chatInput.focus();
                scrollBottom();
            }
        }

        /* ═══════════════════════════════════════════════════════════════
           MUAT SESI DARI SIDEBAR / SAAT RELOAD
        ═══════════════════════════════════════════════════════════════ */
        async function loadSession(uuid) {
            if (isStreaming) return; // jangan ganggu stream yang sedang berjalan
            closeSidebar();

            const myToken = ++loadToken;

            // Langsung tandai aktif & tampilkan loading supaya user tahu klik berhasil
            document.querySelectorAll('.session-item').forEach(el => {
                el.classList.toggle('active', el.dataset.uuid === uuid);
            });
            chatInner.innerHTML = '';
            chatInner.appendChild(createTypingEl());

            try {
                const resp = await fetch(`${LOAD_URL}/${uuid}`, {
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                });

                if (myToken !== loadToken) return; // sudah ada klik yang lebih baru

                if (resp.status === 404) {
                    resetToNewChat();
                    return;
                } // sesi sudah tidak ada
                if (!resp.ok) throw new Error('HTTP ' + resp.status);

                const data = await resp.json();
                if (myToken !== loadToken) return;

                chatInner.innerHTML = '';
                currentSessionUuid = data.uuid;
                topbarTitle.textContent = data.title;
                history.replaceState({
                        sessionUuid: data.uuid
                    }, '',
                    `${window.location.pathname}?session=${data.uuid}`);

                // role dari DB bisa 'user' atau 'assistant' → selain 'user' dianggap AI
                (data.messages || []).forEach(m => {
                    const role = m.role === 'user' ? 'user' : 'ai';
                    const {
                        wrap
                    } = createMsgEl(role, m.content);
                    chatInner.appendChild(wrap);
                });

                scrollBottom(false);
            } catch (e) {
                if (myToken !== loadToken) return;
                console.error('Load session error:', e);
                chatInner.innerHTML = '';
                const {
                    wrap
                } = createMsgEl('ai', '⚠️ Gagal memuat percakapan. Coba klik sesi dari daftar riwayat.');
                chatInner.appendChild(wrap);
            }
        }

        /* ═══════════════════════════════════════════════════════════════
           HAPUS SESI
        ═══════════════════════════════════════════════════════════════ */
        async function deleteSession(uuid, itemEl) {
            const judul = itemEl?.dataset.title || 'sesi ini';

            const result = await Swal.fire({
                title: 'Hapus riwayat obrolan?',
                text: `"${judul}" akan dihapus permanen dan tidak bisa dikembalikan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    popup: 'swal-flavory'
                },
            });

            if (!result.isConfirmed) return;

            try {
                const resp = await fetch(`${DEL_URL}/${uuid}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                });
                if (!resp.ok) throw new Error('HTTP ' + resp.status);

                itemEl.remove();

                // Jika sesi yang dihapus adalah sesi aktif, reset chat
                if (currentSessionUuid === uuid) resetToNewChat();

                // Tampilkan empty state jika tidak ada lagi sesi
                if (!sidebarSess.querySelector('.session-item')) {
                    sidebarSess.innerHTML = `
                <div class="sessions-empty" id="sessionsEmpty">
                    <i class="bi bi-chat-square-dots"></i>
                    <span>Belum ada riwayat chat</span>
                </div>`;
                }

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Riwayat berhasil dihapus',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                });
            } catch (e) {
                console.error('Delete session error:', e);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal menghapus',
                    text: 'Terjadi kesalahan saat menghapus sesi. Coba lagi.',
                    confirmButtonColor: '#ff6a00',
                });
            }
        }

        /* ═══════════════════════════════════════════════════════════════
           REFRESH SIDEBAR (setelah chat baru dibuat)
        ═══════════════════════════════════════════════════════════════ */
        async function refreshSidebarSessions() {
            try {
                const resp = await fetch(SESSIONS_URL, {
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                });
                const data = await resp.json();

                sidebarSess.innerHTML = '';

                if (!data.sessions.length) {
                    sidebarSess.innerHTML = `
                <div class="sessions-empty">
                    <i class="bi bi-chat-square-dots"></i>
                    <span>Belum ada riwayat chat</span>
                </div>`;
                    return;
                }

                data.sessions.forEach(s => {
                    const item = document.createElement('div');
                    item.className = `session-item${s.uuid === currentSessionUuid ? ' active' : ''}`;
                    item.setAttribute('role', 'button');
                    item.tabIndex = 0;
                    item.dataset.uuid = s.uuid;
                    item.dataset.title = s.title;
                    item.innerHTML = `
                <div class="session-item-body">
                    <i class="bi bi-chat-left-text session-icon"></i>
                    <span class="session-title">${escHtml(s.title)}</span>
                </div>
                <button class="session-delete-btn" data-uuid="${escHtml(s.uuid)}" title="Hapus" aria-label="Hapus">
                    <i class="bi bi-trash3"></i>
                </button>`;
                    sidebarSess.appendChild(item);
                });

                // Update topbar title
                const active = data.sessions.find(s => s.uuid === currentSessionUuid);
                if (active) topbarTitle.textContent = active.title;

            } catch (e) {
                console.error('Refresh sidebar error:', e);
            }
        }

        function escHtml(str) {
            return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        /* ═══════════════════════════════════════════════════════════════
           CHAT BARU
        ═══════════════════════════════════════════════════════════════ */
        function resetToNewChat() {
            loadToken++; // batalkan load sesi yang masih berjalan
            currentSessionUuid = null;
            chatInner.innerHTML = '';
            topbarTitle.textContent = 'FlavoryAI';
            history.replaceState({}, '', window.location.pathname); // buang ?session=
            chatInner.appendChild(createWelcomeScreenEl());
            hydrateFaces(chatInner);
            document.querySelectorAll('.session-item').forEach(el => el.classList.remove('active'));
        }

        document.getElementById('btnNewChat').addEventListener('click', () => {
            if (isStreaming) return;
            resetToNewChat();
            closeSidebar();
            chatInput.focus();
        });

        function createWelcomeScreenEl() {
            const div = document.createElement('div');
            div.className = 'ai-welcome';
            div.id = 'welcomeScreen';
            div.innerHTML = `
        <div class="ai-welcome-orb"><span class="fa-face" data-global data-fa-hydrate data-exp="smile"></span></div>
        <div>
            <h1 class="ai-welcome-title">
                Halo, Bos <span class="ai-gradient-text">${escHtml(OWNER_NAME || '')}</span>!
            </h1>
            <p class="ai-welcome-sub">
                Saya <strong>FlavoryAI</strong> — siap bantu analisis bisnis outlet Anda secara real-time.
                Tanya apa saja, dari menu terlaris hingga strategi promosi!
            </p>
        </div>
        <div class="ai-welcome-chips">
            <button type="button" class="ai-chip" data-prompt="Menu apa yang paling laris bulan ini, Bos?"><i class="bi bi-cup-hot"></i> Menu terlaris bulan ini</button>
            <button type="button" class="ai-chip" data-prompt="Bagaimana performa penjualan 30 hari terakhir?"><i class="bi bi-bar-chart-line"></i> Performa penjualan 30 hari</button>
            <button type="button" class="ai-chip" data-prompt="Apa saran kamu untuk meningkatkan omzet outlet saya?"><i class="bi bi-rocket-takeoff"></i> Saran tingkatkan omzet</button>
            <button type="button" class="ai-chip" data-prompt="Apakah ada menu yang perlu saya pertimbangkan untuk dihapus atau direvisi harganya?"><i class="bi bi-lightbulb"></i> Evaluasi menu &amp; harga</button>
            <button type="button" class="ai-chip" data-prompt="Bagaimana kondisi stok barang saya sekarang?"><i class="bi bi-box-seam"></i> Cek kondisi stok</button>
            <button type="button" class="ai-chip" data-prompt="Berikan ringkasan keuangan outlet saya bulan ini."><i class="bi bi-cash-coin"></i> Ringkasan keuangan</button>
        </div>`;
            return div;
        }

        /* ═══════════════════════════════════════════════════════════════
           EVENT DELEGATION
           Satu listener di container → tetap berfungsi untuk item yang
           dibuat ulang oleh refreshSidebarSessions() dan chip welcome
           yang dibuat ulang oleh "Chat Baru".
        ═══════════════════════════════════════════════════════════════ */
        sidebarSess.addEventListener('click', function(e) {
            const delBtn = e.target.closest('.session-delete-btn');
            if (delBtn) {
                e.stopPropagation();
                const item = delBtn.closest('.session-item');
                if (item) deleteSession(item.dataset.uuid, item);
                return;
            }
            const item = e.target.closest('.session-item');
            if (item && item.dataset.uuid) loadSession(item.dataset.uuid);
        });

        sidebarSess.addEventListener('keydown', function(e) {
            if (e.key !== 'Enter' && e.key !== ' ') return;
            if (e.target.closest('.session-delete-btn')) return;
            const item = e.target.closest('.session-item');
            if (item && item.dataset.uuid) {
                e.preventDefault();
                loadSession(item.dataset.uuid);
            }
        });

        chatInner.addEventListener('click', function(e) {
            const chip = e.target.closest('.ai-chip');
            if (!chip || isStreaming) return;
            chatInput.value = chip.dataset.prompt;
            chatInput.dispatchEvent(new Event('input'));
            sendMessage();
        });

        /* ═══════════════════════════════════════════════════════════════
           VOICE-TO-TEXT (Web Speech API, Bahasa Indonesia)
           - klik mikrofon: mulai / berhenti
           - teks muncul langsung (sementara) di kolom input, bisa diedit sebelum dikirim
           - berhenti otomatis setelah 10 dtk tanpa suara, Esc, atau saat pesan dikirim
        ═══════════════════════════════════════════════════════════════ */
        const SpeechRec = window.SpeechRecognition || window.webkitSpeechRecognition;
        const btnMic = document.getElementById('btnMic');
        const voiceStatus = document.getElementById('voiceStatus');
        const voiceStatusText = document.getElementById('voiceStatusText');
        const VOICE_LANG = 'id-ID';
        const VOICE_SILENCE_MS = 10000;

        let voiceRec = null,
            voiceWanted = false,
            voiceBase = '',
            voiceTimer = 0,
            voiceHearT = 0,
            voiceGot = false;

        function voiceToast(icon, msg) {
            if (typeof Swal === 'undefined') {
                alert(msg);
                return;
            }
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon,
                title: msg,
                showConfirmButton: false,
                timer: 4200,
                timerProgressBar: true
            });
        }

        function setVoiceUI(on) {
            btnMic.classList.toggle('listening', on);
            btnMic.setAttribute('aria-pressed', on ? 'true' : 'false');
            const label = on ? 'Berhenti merekam' : 'Bicara (voice to text)';
            btnMic.title = label;
            btnMic.setAttribute('aria-label', label);
            voiceStatus.classList.toggle('show', on);
            if (!on) voiceStatus.classList.remove('hearing');
            uiState.listening = on;
            applyGlobal();
        }

        function resetVoiceTimer() {
            clearTimeout(voiceTimer);
            voiceTimer = setTimeout(() => {
                if (!voiceWanted) return;
                const nothing = !voiceGot;
                stopVoice();
                if (nothing) voiceToast('info', 'Tidak ada suara terdeteksi, mikrofon dimatikan.');
            }, VOICE_SILENCE_MS);
        }

        // Android/iOS: Chrome & Safari mengirim hasil kumulatif yang bertumpuk
        // (contoh: "Halo", "Halo kamu", "Halo kamu apa") → rawan duplikat.
        const VOICE_IS_MOBILE = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);

        // Gabungkan potongan hasil tanpa duplikasi:
        // - seg kumulatif (diawali isi acc)  → ganti acc dengan seg
        // - seg sudah termasuk di akhir acc → abaikan
        // - ada tumpang tindih kata di ujung → sambung hanya bagian barunya
        function mergeVoiceSegment(acc, seg) {
            seg = (seg || '').trim();
            if (!seg) return acc;
            if (!acc) return seg;
            const a = acc.toLowerCase(), s = seg.toLowerCase();
            if (s.startsWith(a)) return seg;
            if (a.endsWith(s)) return acc;
            const aw = acc.split(/\s+/), sw = seg.split(/\s+/);
            const max = Math.min(aw.length, sw.length);
            for (let k = max; k > 0; k--) {
                const tail = aw.slice(-k).join(' ').toLowerCase();
                const head = sw.slice(0, k).join(' ').toLowerCase();
                if (tail === head) return acc + ' ' + sw.slice(k).join(' ');
            }
            return acc + ' ' + seg;
        }

        function commitVoiceBase() {
            voiceBase = chatInput.value;
            if (voiceBase && !/\s$/.test(voiceBase)) voiceBase += ' ';
        }

        function endVoice() {
            clearTimeout(voiceTimer);
            voiceWanted = false;
            voiceRec = null;
            setVoiceUI(false);
            if (!chatInput.disabled) {
                chatInput.focus();
                const n = chatInput.value.length;
                try {
                    chatInput.setSelectionRange(n, n);
                } catch (e) {}
            }
        }

        function startVoice() {
            if (isStreaming || voiceWanted) return;
            if (!SpeechRec) {
                voiceToast('info', 'Browser ini belum mendukung voice-to-text. Gunakan Chrome, Edge, atau Safari terbaru.');
                return;
            }
            const rec = new SpeechRec();
            rec.lang = VOICE_LANG;
            rec.continuous = !VOICE_IS_MOBILE; // mobile: sesi pendek + auto-restart (anti duplikat)
            rec.interimResults = true;
            rec.maxAlternatives = 1;
            voiceRec = rec;
            voiceWanted = true;
            voiceGot = false;
            commitVoiceBase();

            rec.onstart = () => {
                voiceStatusText.textContent = 'Mendengarkan… silakan bicara';
                setVoiceUI(true);
            };

            rec.onresult = e => {
                if (voiceRec !== rec) return; // abaikan sesi lama / sudah dibatalkan

                // Susun teks sesi ini dari semua hasil, dengan penghapusan duplikat
                let sessionText = '';
                for (let i = 0; i < e.results.length; i++) {
                    sessionText = mergeVoiceSegment(sessionText, e.results[i][0].transcript);
                }

                const combinedText = (voiceBase + sessionText).trim();

                if (combinedText) {
                    voiceGot = true;
                    chatInput.value = combinedText;
                    chatInput.dispatchEvent(new Event('input')); // auto-resize textarea
                    chatInput.scrollTop = chatInput.scrollHeight;
                    voiceStatusText.textContent = 'Menangkap suara Anda…';
                    voiceStatus.classList.add('hearing');
                    clearTimeout(voiceHearT);
                    voiceHearT = setTimeout(() => {
                        voiceStatus.classList.remove('hearing');
                        voiceStatusText.textContent = 'Mendengarkan… lanjutkan atau tekan Selesai';
                    }, 900);
                    resetVoiceTimer();
                }
            };

            rec.onerror = e => {
                switch (e.error) {
                    case 'not-allowed':
                    case 'service-not-allowed':
                        voiceWanted = false;
                        voiceToast('error',
                            'Izin mikrofon ditolak. Aktifkan izin mikrofon di pengaturan browser (situs harus HTTPS), lalu coba lagi.'
                            );
                        break;
                    case 'audio-capture':
                        voiceWanted = false;
                        voiceToast('error', 'Mikrofon tidak ditemukan. Periksa perangkat Anda.');
                        break;
                    case 'network':
                        voiceWanted = false;
                        voiceToast('error',
                        'Layanan pengenalan suara tidak dapat dihubungi. Periksa koneksi internet.');
                        break;
                    case 'language-not-supported':
                        voiceWanted = false;
                        voiceToast('error', 'Bahasa Indonesia belum didukung di perangkat ini.');
                        break;
                    default:
                        break; // no-speech / aborted → ditangani onend
                }
            };

            rec.onend = () => {
                if (voiceRec !== rec) return;
                if (voiceWanted) { // sesi berakhir sendiri → sambung lagi dengan basis teks terbaru
                    commitVoiceBase();
                    setTimeout(() => {
                        if (voiceRec !== rec || !voiceWanted) return;
                        try {
                            rec.start();
                        } catch (err) {
                            endVoice();
                        }
                    }, 250);
                    return;
                }
                endVoice();
            };

            try {
                rec.start();
                resetVoiceTimer();
            } catch (err) {
                console.error('Voice start error:', err);
                endVoice();
            }
        }

        // abort = true → buang hasil yang belum selesai (dipakai saat pesan dikirim)
        function stopVoice(abort = false) {
            if (!voiceWanted && !voiceRec) return;
            const rec = voiceRec;
            voiceWanted = false;
            clearTimeout(voiceTimer);
            setVoiceUI(false); // UI langsung kembali normal
            if (!rec) return;
            try {
                if (abort) {
                    rec.onresult = null;
                    rec.abort();
                } else {
                    rec.stop();
                }
            } catch (e) {}
        }

        btnMic.addEventListener('click', () => {
            voiceWanted ? stopVoice() : startVoice();
        });
        document.getElementById('btnVoiceDone').addEventListener('click', () => stopVoice());
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && voiceWanted) stopVoice();
        });
        document.addEventListener('visibilitychange', () => {
            if (document.hidden && voiceWanted) stopVoice();
        });

        /* ═══════════════════════════════════════════════════════════════
           INIT: muat riwayat pesan sesi aktif saat reload
        ═══════════════════════════════════════════════════════════════ */
        if (currentSessionUuid) {
            loadSession(currentSessionUuid);
        }

        // Fokus input saat halaman load
        chatInput.focus();
    </script>
</body>

</html>