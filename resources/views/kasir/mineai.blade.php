<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MineAI — Konsultan Bisnis Kuliner | Flavory.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @include('kasir.layouts.favicon-mineai')
    @include('layouts.loading')
    <style>
        /* ── Tokens ── */
        :root{
            --acc1:#ff6a00; --acc2:#ee0979;
            --sidebar-w:270px;
            --bg:#f8f9fc;
            --surface:#ffffff;
            --border:#e8eaf0;
            --text:#1e1e2e;
            --muted:#7b7f93;
            --light:#f3f4f8;
        }
        *{ box-sizing:border-box; margin:0; padding:0; }
        html,body{ height:100%; font-family:'Poppins',sans-serif; overflow:hidden; background:var(--bg); }

        /* ── Soft bg with very subtle gradient orbs ── */
        #bgCanvas{
            position:fixed; inset:0; z-index:0;
            background: var(--bg);
        }
        .orb{
            position:fixed; border-radius:50%;
            filter:blur(100px); opacity:.07;
            pointer-events:none; z-index:1;
        }
        .orb1{ width:700px; height:700px;
               background:radial-gradient(circle,#ff6a00,transparent 65%);
               top:-20%; left:-15%; }
        .orb2{ width:500px; height:500px;
               background:radial-gradient(circle,#ee0979,transparent 65%);
               bottom:-15%; right:-10%; }
        .orb3{ width:400px; height:400px;
               background:radial-gradient(circle,#ff9a00,transparent 65%);
               top:35%; right:5%; }

        /* ── Shell ── */
        #shell{
            position:relative; z-index:2;
            height:100vh; display:flex; flex-direction:column;
        }

        /* ── Topbar ── */
        .topbar{
            display:flex; align-items:center; justify-content:space-between;
            padding:12px 20px; flex-shrink:0;
            background:var(--surface);
            border-bottom:1px solid var(--border);
            box-shadow:0 1px 4px rgba(0,0,0,.05);
        }
        .topbar-left{ display:flex; align-items:center; gap:10px; }
        .logo-box{
            width:32px; height:32px; border-radius:9px; flex-shrink:0;
            display:flex; align-items:center; justify-content:center;
            overflow:hidden;
        }
        .brand-name{ font-size:.9rem; font-weight:800; color:var(--text); letter-spacing:-.3px; }
        .brand-name em{
            font-style:normal;
            background:linear-gradient(135deg,var(--acc1),var(--acc2));
            -webkit-background-clip:text; -webkit-text-fill-color:transparent;
            background-clip:text;
        }
        .btn-back{
            display:flex; align-items:center; gap:5px;
            color:var(--muted); font-size:.8rem; font-weight:600;
            text-decoration:none; background:var(--light);
            border:1px solid var(--border); border-radius:9px;
            padding:5px 12px; transition:all .2s;
        }
        .btn-back:hover{ background:var(--border); color:var(--text); }
        #btnToggleSidebar{
            display:flex; align-items:center; justify-content:center;
            width:32px; height:32px; border-radius:8px; border:1px solid var(--border);
            cursor:pointer; background:var(--light); color:var(--muted);
            font-size:.95rem; transition:all .2s;
        }
        #btnToggleSidebar:hover{ background:var(--border); color:var(--text); }
    </style>
    <style>
        /* ── Body row ── */
        #bodyRow{ flex:1; display:flex; overflow:hidden; min-height:0; }

        /* ── Sidebar ── */
        #sidebar{
            width:var(--sidebar-w); flex-shrink:0;
            background:var(--surface);
            border-right:1px solid var(--border);
            display:flex; flex-direction:column;
            transition:width .3s ease, opacity .3s ease;
            overflow:hidden;
        }
        #sidebar.collapsed{ width:0; opacity:0; pointer-events:none; }
        .sidebar-head{
            padding:14px 14px 10px;
            border-bottom:1px solid var(--border);
            display:flex; align-items:center; justify-content:space-between; flex-shrink:0;
        }
        .sidebar-head-label{
            font-size:.72rem; font-weight:700; color:var(--muted);
            text-transform:uppercase; letter-spacing:1px;
        }
        .btn-new-chat{
            display:flex; align-items:center; gap:5px;
            padding:6px 12px; border-radius:8px; border:none; cursor:pointer;
            background:linear-gradient(135deg,var(--acc1),var(--acc2));
            color:white; font-size:.75rem; font-weight:700;
            box-shadow:0 3px 10px rgba(255,106,0,.25);
            transition:opacity .2s; white-space:nowrap;
        }
        .btn-new-chat:hover{ opacity:.88; }

        #sessionList{
            flex:1; overflow-y:auto; padding:8px 6px;
            scrollbar-width:none; -ms-overflow-style:none;
        }
        #sessionList::-webkit-scrollbar{ display:none; }

        .session-item{
            display:flex; align-items:center; gap:8px;
            padding:9px 10px; border-radius:10px; cursor:pointer;
            transition:background .15s; margin-bottom:3px;
            border:1px solid transparent; position:relative;
        }
        .session-item:hover{ background:var(--light); }
        .session-item.active{
            background:linear-gradient(135deg,rgba(255,106,0,.08),rgba(238,9,121,.06));
            border-color:rgba(255,106,0,.2);
        }
        .session-icon{
            width:28px; height:28px; border-radius:7px; flex-shrink:0;
            background:var(--light); border:1px solid var(--border);
            display:flex; align-items:center; justify-content:center;
            color:var(--muted); font-size:.78rem;
        }
        .session-item.active .session-icon{
            background:linear-gradient(135deg,var(--acc1),var(--acc2));
            border-color:transparent; color:white;
        }
        .session-info{ flex:1; min-width:0; }
        .session-title{
            font-size:.78rem; font-weight:600; color:var(--text);
            white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
        }
        .session-date{ font-size:.65rem; color:var(--muted); margin-top:1px; }

        /* Delete button — selalu visible */
        .btn-del-session{
            width:26px; height:26px; border-radius:7px; border:1px solid var(--border);
            background:var(--surface); color:var(--muted); font-size:.75rem;
            display:flex; align-items:center; justify-content:center;
            cursor:pointer; flex-shrink:0; transition:all .15s;
        }
        .btn-del-session:hover{
            background:#fff1f2; border-color:#fecdd3; color:#e11d48;
        }

        .sidebar-empty{
            text-align:center; padding:40px 16px;
            color:var(--muted); font-size:.78rem;
        }
        .sidebar-empty i{ font-size:1.6rem; display:block; margin-bottom:8px; opacity:.35; }
    </style>
    <style>
        /* ── Main column ── */
        #mainCol{
            flex:1; display:flex; flex-direction:column; align-items:center;
            overflow-y:auto; overflow-x:hidden;
            padding:36px 24px 80px;
            scrollbar-width:none; -ms-overflow-style:none; min-width:0;
        }
        #mainCol::-webkit-scrollbar{ display:none; }

        /* Hero */
        #heroStage{ width:100%; max-width:580px; text-align:center; }
        .hero-badge{
            display:inline-flex; align-items:center; gap:6px;
            padding:5px 14px; border-radius:50px;
            background:linear-gradient(135deg,rgba(255,106,0,.1),rgba(238,9,121,.08));
            border:1px solid rgba(255,106,0,.2);
            font-size:.72rem; font-weight:700; color:var(--acc1);
            text-transform:uppercase; letter-spacing:1px; margin-bottom:20px;
        }
        .hero-title{
            font-size:clamp(1.5rem,3.5vw,2.1rem); font-weight:900;
            color:var(--text); line-height:1.25;
        }
        .hero-title .grad{
            background:linear-gradient(135deg,var(--acc1),var(--acc2));
            -webkit-background-clip:text; -webkit-text-fill-color:transparent;
            background-clip:text;
        }
        .hero-sub{ font-size:.87rem; color:var(--muted); margin-top:10px; line-height:1.6; }

        /* ── Animated gradient for buttons ── */
        @keyframes btnShift {
            0%  { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100%{ background-position: 0% 50%; }
        }

        .btn-mine-main{
            display:inline-flex; align-items:center; gap:10px;
            margin-top:28px; padding:14px 36px; border-radius:50px; border:none;
            font-weight:800; font-size:.95rem; color:white; cursor:pointer;
            background:linear-gradient(135deg,var(--acc1),var(--acc2),#ff9a00,var(--acc1));
            background-size:300% 300%;
            animation:btnShift 3s ease infinite;
            box-shadow:0 8px 28px rgba(255,106,0,.3);
            transition:transform .25s, box-shadow .25s;
        }
        .btn-mine-main:hover{ transform:translateY(-2px); box-shadow:0 12px 36px rgba(238,9,121,.35); }
        .btn-mine-main:active{ transform:scale(.97); }
        .btn-mine-main .spin{
            width:16px; height:16px; border:2px solid rgba(255,255,255,.4);
            border-top-color:white; border-radius:50%;
            animation:spin .7s linear infinite; display:none;
        }
        @keyframes spin{ to{ transform:rotate(360deg) } }
        @keyframes fadeUp{
            from{ opacity:0; transform:translateY(18px) }
            to  { opacity:1; transform:translateY(0)    }
        }

        /* Insight card */
        #insightCard{
            width:100%;
            background:var(--surface);
            border:1px solid var(--border);
            border-radius:20px;
            padding:24px; display:none;
            animation:fadeUp .4s ease both;
            box-shadow:0 4px 20px rgba(0,0,0,.06);
        }
        .insight-text{ color:#374151; font-size:.88rem; line-height:1.9; }
        .insight-text strong{ color:var(--text); font-weight:700; }

        .btn-chat-start{
            display:inline-flex; align-items:center; gap:8px;
            margin-top:18px; padding:11px 28px; border-radius:50px; border:none;
            font-weight:700; font-size:.88rem; color:white; cursor:pointer;
            background:linear-gradient(135deg,var(--acc1),var(--acc2),#ff9a00,var(--acc1));
            background-size:300% 300%;
            animation:btnShift 3s ease infinite;
            box-shadow:0 6px 20px rgba(255,106,0,.28);
            transition:transform .25s, box-shadow .25s;
        }
        .btn-chat-start:hover{ transform:translateY(-2px); box-shadow:0 10px 28px rgba(238,9,121,.35); }

        /* Chatbox */
        #chatBox{
            width:100%; background:var(--surface);
            border:1px solid var(--border); border-radius:20px;
            display:none; flex-direction:column; overflow:hidden;
            animation:fadeUp .4s ease both;
            box-shadow:0 4px 20px rgba(0,0,0,.06);
            max-height:calc(100vh - 150px);
        }
        /* Chat header */
        .chat-header{
            display:flex; align-items:center; justify-content:space-between;
            padding:14px 18px; border-bottom:1px solid var(--border);
            flex-shrink:0;
        }
        .chat-header-left{ display:flex; align-items:center; gap:10px; }
        .chat-ai-dot{
            width:32px; height:32px; border-radius:9px; flex-shrink:0;
            background:linear-gradient(135deg,var(--acc1),var(--acc2));
            display:flex; align-items:center; justify-content:center;
            color:white; font-size:.85rem;
        }
        .chat-ai-name{ font-weight:800; color:var(--text); font-size:.88rem; }
        .chat-ai-status{
            font-size:.68rem; color:var(--muted); display:flex; align-items:center; gap:4px;
        }
        .status-dot{
            width:6px; height:6px; border-radius:50%; background:#22c55e;
            display:inline-block; animation:pulse 2s ease-in-out infinite;
        }
        @keyframes pulse{ 0%,100%{ opacity:1 } 50%{ opacity:.4 } }

        /* Messages */
        #chatMessages{
            flex:1; overflow-y:auto; padding:18px 18px 8px;
            display:flex; flex-direction:column; gap:14px;
            min-height:220px; max-height:52vh;
            scrollbar-width:none; -ms-overflow-style:none;
        }
        #chatMessages::-webkit-scrollbar{ display:none; }

        .bubble{ max-width:80%; display:flex; flex-direction:column; gap:3px; }
        .bubble.user{ align-self:flex-end; align-items:flex-end; }
        .bubble.ai  { align-self:flex-start; align-items:flex-start; }
        .bubble-inner{
            padding:11px 15px; border-radius:16px;
            font-size:.86rem; line-height:1.72; word-break:break-word;
        }
        .bubble.user .bubble-inner{
            background:linear-gradient(135deg,rgba(255,106,0,.12),rgba(238,9,121,.08));
            border:1px solid rgba(255,106,0,.18);
            color:var(--text); border-bottom-right-radius:4px;
        }
        .bubble.ai .bubble-inner{
            background:var(--light); border:1px solid var(--border);
            color:#374151; border-bottom-left-radius:4px;
        }
        .bubble-meta{ font-size:.66rem; color:var(--muted); padding:0 3px; }
        .bubble.user .bubble-meta{ text-align:right; }
        .ai-avatar{
            width:26px; height:26px; border-radius:50%; flex-shrink:0;
            overflow:hidden;
            display:flex; align-items:center; justify-content:center;
        }
        .bubble-row{ display:flex; align-items:flex-end; gap:7px; }
        .bubble.user .bubble-row{ flex-direction:row-reverse; }

        .typing-dots{ display:flex; gap:4px; padding:6px 2px; }
        .typing-dots span{
            width:6px; height:6px; border-radius:50%;
            background:var(--muted);
            animation:typB 1.2s ease-in-out infinite;
        }
        .typing-dots span:nth-child(2){ animation-delay:.2s; }
        .typing-dots span:nth-child(3){ animation-delay:.4s; }
        @keyframes typB{
            0%,80%,100%{ transform:translateY(0); opacity:.35 }
            40%         { transform:translateY(-6px); opacity:1  }
        }

        /* Input area */
        #chatInputArea{
            padding:12px 16px; border-top:1px solid var(--border);
            background:var(--light); display:flex; gap:9px; align-items:flex-end;
        }
        #chatInput{
            flex:1; background:var(--surface); border:1px solid var(--border);
            border-radius:12px; padding:10px 14px; color:var(--text); font-size:.86rem;
            font-family:'Poppins',sans-serif; resize:none; outline:none;
            max-height:120px; line-height:1.5;
            overflow-y:hidden; scrollbar-width:none; -ms-overflow-style:none;
            transition:border-color .2s;
        }
        #chatInput::-webkit-scrollbar{ display:none; }
        #chatInput::placeholder{ color:var(--muted); }
        #chatInput:focus{ border-color:var(--acc1); box-shadow:0 0 0 3px rgba(255,106,0,.1); }
        .btn-send{
            width:40px; height:40px; border-radius:10px; border:none; cursor:pointer; flex-shrink:0;
            background:linear-gradient(135deg,var(--acc1),var(--acc2),#ff9a00,var(--acc1));
            background-size:300% 300%;
            animation:btnShift 3s ease infinite;
            color:white; font-size:.9rem;
            display:flex; align-items:center; justify-content:center;
            transition:transform .2s; box-shadow:0 4px 12px rgba(255,106,0,.28);
        }
        .btn-send:hover{ transform:scale(1.07); }
        .btn-send:disabled{ opacity:.45; cursor:not-allowed; transform:none; animation:none; }

        /* ── Responsive ── */
        @media(max-width:768px){
            :root{ --sidebar-w:260px; }
            #sidebar{
                position:fixed; top:0; left:0; height:100%; z-index:200;
                width:var(--sidebar-w); transform:translateX(-100%);
                transition:transform .3s ease; box-shadow:4px 0 20px rgba(0,0,0,.12);
            }
            #sidebar.mobile-open{
                transform:translateX(0)!important; opacity:1!important;
                pointer-events:auto!important; width:var(--sidebar-w)!important;
            }
            #sidebar.collapsed{ transform:translateX(-100%); width:var(--sidebar-w); }
            #sidebarOverlay{
                position:fixed; inset:0; background:rgba(0,0,0,.3);
                z-index:199; display:none; backdrop-filter:blur(2px);
            }
            #sidebarOverlay.show{ display:block; }
            .topbar{ padding:10px 14px; }
            #mainCol{ padding:20px 12px 70px; }
            .bubble{ max-width:92%; }
        }
    </style>
</head>
<body>
<div id="bgCanvas"></div>
<div class="orb orb1"></div>
<div class="orb orb2"></div>
<div class="orb orb3"></div>
<div id="sidebarOverlay" onclick="closeMobileSidebar()"></div>

<div id="shell">

    {{-- Topbar --}}
    <div class="topbar">
        <div class="topbar-left">
            <button id="btnToggleSidebar" title="Riwayat Chat" onclick="toggleSidebar()">
                <i class="bi bi-layout-sidebar-inset"></i>
            </button>
            <div class="logo-box ms-2">
                <img src="{{ asset('assets/logo-mineai.jpg') }}"
                     style="width:32px;height:32px;object-fit:cover;border-radius:9px;"
                     onerror="this.style.display='none';this.parentElement.innerHTML='<i class=\'bi bi-cpu-fill\' style=\'color:var(--acc1);font-size:.9rem\'></i>'">
            </div>
            <span class="brand-name ms-2">Mine<em>AI</em></span>
        </div>
        <a href="{{ route('kasir.dashboard') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i>
            <span class="d-none d-sm-inline">Kembali</span>
        </a>
    </div>

    <div id="bodyRow">

        {{-- Sidebar --}}
        <div id="sidebar">
            <div class="sidebar-head">
                <span class="sidebar-head-label">Riwayat Chat</span>
                <button class="btn-new-chat" onclick="startNewChat()">
                    <i class="bi bi-plus-lg"></i> Baru
                </button>
            </div>
            <div id="sessionList">
                @forelse($sessions as $s)
                <div class="session-item" data-uuid="{{ $s->uuid }}" onclick="loadSession('{{ $s->uuid }}')">
                    <div class="session-icon"><i class="bi bi-chat-dots"></i></div>
                    <div class="session-info">
                        <div class="session-title">{{ $s->title }}</div>
                        <div class="session-date">{{ $s->updated_at->diffForHumans() }}</div>
                    </div>
                    <button class="btn-del-session"
                            onclick="deleteSession(event,'{{ $s->uuid }}')"
                            title="Hapus sesi ini">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>
                @empty
                <div class="sidebar-empty">
                    <i class="bi bi-chat-square-dots"></i>
                    Belum ada riwayat chat
                </div>
                @endforelse
            </div>
        </div>

        {{-- Main col --}}
        <div id="mainCol">

            {{-- Stage 1: Hero --}}
            <div id="heroStage">
                <div class="hero-badge"><i class="bi bi-stars"></i> Konsultan Bisnis AI</div>
                <h1 class="hero-title">
                    Halo Bos! 👋<br>
                    Mau tau kondisi<br>
                    <span class="grad">bisnis kamu sekarang?</span>
                </h1>
                <p class="hero-sub">MineAI siap analisis data 30 hari terakhir & kasih saran strategis buat kamu.</p>
                <button class="btn-mine-main" id="btnGetInsight" onclick="requestInsight()">
                    <i class="bi bi-stars" id="btnInsightIcon"></i>
                    <span id="btnInsightText">Minta Insight MineAI</span>
                    <div class="spin" id="btnInsightSpin"></div>
                </button>
            </div>

            {{-- Stage 2: Insight card --}}
            <div id="insightCard">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
                    <div style="width:36px;height:36px;border-radius:50%;overflow:hidden;flex-shrink:0;">
                        <img src="{{ asset('assets/logo-mineai.jpg') }}"
                             style="width:36px;height:36px;object-fit:cover;"
                             onerror="this.parentElement.style.background='linear-gradient(135deg,var(--acc1),var(--acc2))';this.style.display='none';this.parentElement.innerHTML='<i class=\'bi bi-cpu-fill\' style=\'color:white;font-size:.95rem\'></i>'">
                    </div>
                    <div>
                        <div class="chat-ai-name">MineAI</div>
                        <div style="font-size:.68rem;color:var(--muted);">Analisis 30 Hari Terakhir</div>
                    </div>
                </div>
                <div class="insight-text" id="insightText"></div>
                <div style="text-align:center;margin-top:20px;">
                    <button class="btn-chat-start" onclick="openChat()">
                        <i class="bi bi-chat-dots-fill"></i> Mulai Ngobrol dengan MineAI
                    </button>
                </div>
            </div>

            {{-- Stage 3: Chatbox --}}
            <div id="chatBox">
                <div class="chat-header">
                    <div class="chat-header-left">
                        <div style="width:34px;height:34px;border-radius:50%;overflow:hidden;flex-shrink:0;">
                            <img src="{{ asset('assets/logo-mineai.jpg') }}"
                                 style="width:34px;height:34px;object-fit:cover;"
                                 onerror="this.parentElement.style.background='linear-gradient(135deg,var(--acc1),var(--acc2))';this.style.display='none';this.parentElement.innerHTML='<i class=\'bi bi-cpu-fill\' style=\'color:white;font-size:.85rem\'></i>'">
                        </div>
                        <div>
                            <div class="chat-ai-name">MineAI</div>
                            <div class="chat-ai-status">
                                <span class="status-dot"></span> Siap membantu
                            </div>
                        </div>
                    </div>
                    <button onclick="startNewChat()" style="display:flex;align-items:center;gap:5px;padding:5px 11px;border-radius:8px;border:1px solid var(--border);background:var(--light);color:var(--muted);font-size:.75rem;font-weight:600;cursor:pointer;">
                        <i class="bi bi-plus-lg"></i> Chat Baru
                    </button>
                </div>
                <div id="chatMessages"></div>
                <div id="chatInputArea">
                    <textarea id="chatInput" rows="1"
                        placeholder="Tanya apa saja ke MineAI..."
                        maxlength="2000"></textarea>
                    <button class="btn-send" id="btnSend" onclick="sendMessage()">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </div>
            </div>

        </div>{{-- /#mainCol --}}
    </div>{{-- /#bodyRow --}}
</div>{{-- /#shell --}}

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
let chatHistory=[], businessContext={}, insightText='', currentSession=null;
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

const md = t => t
    .replace(/\*\*(.*?)\*\*/g,'<strong>$1</strong>')
    .replace(/\*(.*?)\*/g,'<em>$1</em>')
    .replace(/\n/g,'<br>');
const nowStr = () => new Date().toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'});
const scrollBottom = () => { const el=document.getElementById('chatMessages'); el.scrollTop=el.scrollHeight; };

/* ── Sidebar ── */
let sidebarOpen = window.innerWidth > 768;
function toggleSidebar(){
    const sb=document.getElementById('sidebar'), ov=document.getElementById('sidebarOverlay');
    if(window.innerWidth<=768){ sb.classList.toggle('mobile-open'); ov.classList.toggle('show'); }
    else{ sidebarOpen=!sidebarOpen; sb.classList.toggle('collapsed',!sidebarOpen); }
}
function closeMobileSidebar(){
    document.getElementById('sidebar').classList.remove('mobile-open');
    document.getElementById('sidebarOverlay').classList.remove('show');
}
document.addEventListener('DOMContentLoaded',()=>{
    if(window.innerWidth<=768) document.getElementById('sidebar').classList.add('collapsed');
});

/* ── New chat ── */
function startNewChat(){
    currentSession=null; chatHistory=[]; businessContext={}; insightText='';
    document.getElementById('chatBox').style.display='none';
    document.getElementById('insightCard').style.display='none';
    document.getElementById('heroStage').style.cssText='display:block;opacity:1';
    document.getElementById('btnGetInsight').disabled=false;
    document.getElementById('btnInsightIcon').style.display='';
    document.getElementById('btnInsightText').textContent='Minta Insight MineAI';
    document.getElementById('btnInsightSpin').style.display='none';
    document.querySelectorAll('.session-item').forEach(el=>el.classList.remove('active'));
    closeMobileSidebar();
}

/* ── Stage 1 ── */
async function requestInsight(){
    const btn=document.getElementById('btnGetInsight');
    document.getElementById('btnInsightIcon').style.display='none';
    document.getElementById('btnInsightText').textContent='Menganalisis data...';
    document.getElementById('btnInsightSpin').style.display='block';
    btn.disabled=true;
    try{
        const res=await fetch('{{ route("kasir.mineai.insight") }}',{headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}});
        const data=await res.json();
        insightText=data.insight_awal||''; businessContext=data.business_context||{};
        document.getElementById('heroStage').style.opacity='0';
        setTimeout(()=>{
            document.getElementById('heroStage').style.display='none';
            const card=document.getElementById('insightCard');
            card.style.display='block';
            document.getElementById('insightText').innerHTML=md(insightText);
            document.getElementById('mainCol').scrollTop=0;
        },300);
    }catch(e){
        document.getElementById('btnInsightText').textContent='Minta Insight MineAI';
        document.getElementById('btnInsightIcon').style.display='';
        document.getElementById('btnInsightSpin').style.display='none';
        btn.disabled=false;
        alert('Gagal ambil insight. Cek koneksi ya Bos!');
    }
}

/* ── Stage 2 ── */
function openChat(){
    document.getElementById('insightCard').style.display='none';
    const cb=document.getElementById('chatBox'); cb.style.display='flex';
    appendBubble('ai',insightText);
    chatHistory.push({role:'assistant',content:insightText});
    document.getElementById('chatInput').focus();
    scrollBottom();
}

/* ── Bubble ── */
function appendBubble(role,content,isTyping=false){
    const msgs=document.getElementById('chatMessages');
    const div=document.createElement('div');
    div.className=`bubble ${role}`;
    if(isTyping) div.id='typingBubble';
    if(role==='ai'){
        div.innerHTML=`<div class="bubble-row">
            <div class="ai-avatar"><img src="{{ asset('assets/logo-mineai.jpg') }}" style="width:26px;height:26px;object-fit:cover;border-radius:50%;" onerror="this.parentElement.style.background='linear-gradient(135deg,#ff6a00,#ee0979)';this.style.display='none';this.parentElement.innerHTML='<i class=\'bi bi-cpu-fill\' style=\'color:white;font-size:.7rem\'></i>'"></div>
            <div>
                <div class="bubble-inner">${isTyping?'<div class="typing-dots"><span></span><span></span><span></span></div>':md(content)}</div>
                ${isTyping?'':`<div class="bubble-meta">MineAI · ${nowStr()}</div>`}
            </div></div>`;
    }else{
        div.innerHTML=`<div class="bubble-row"><div>
            <div class="bubble-inner">${content.replace(/</g,'&lt;').replace(/>/g,'&gt;')}</div>
            <div class="bubble-meta">Kamu · ${nowStr()}</div>
        </div></div>`;
    }
    msgs.appendChild(div); scrollBottom(); return div;
}

/* ── Send ── */
async function sendMessage(){
    const input=document.getElementById('chatInput'), btnSend=document.getElementById('btnSend');
    const msg=input.value.trim(); if(!msg) return;
    input.value=''; input.style.height=''; btnSend.disabled=true;
    appendBubble('user',msg);
    chatHistory.push({role:'user',content:msg});
    appendBubble('ai','',true);
    try{
        const res=await fetch('{{ route("kasir.mineai.chat") }}',{
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
            body:JSON.stringify({message:msg,chat_history:chatHistory.slice(0,-1),business_context:businessContext,session_uuid:currentSession}),
        });
        const data=await res.json();
        const reply=data.reply||'Hmm, gak dapat jawaban.';
        document.getElementById('typingBubble')?.remove();
        appendBubble('ai',reply);
        chatHistory.push({role:'assistant',content:reply});
        if(data.session_uuid){
            currentSession=data.session_uuid;
            upsertSidebarItem(data.session_uuid,data.session_title||msg.substring(0,40));
        }
    }catch(e){
        document.getElementById('typingBubble')?.remove();
        appendBubble('ai','Bos, ada gangguan koneksi. Coba lagi! 🙏');
    }finally{ btnSend.disabled=false; input.focus(); }
}

/* ── Sidebar helpers ── */
function upsertSidebarItem(uuid,title){
    const list=document.getElementById('sessionList');
    list.querySelector('.sidebar-empty')?.remove();
    let item=list.querySelector(`[data-uuid="${uuid}"]`);
    if(item){
        item.querySelector('.session-title').textContent=title;
        item.querySelector('.session-date').textContent='Baru saja';
        list.prepend(item);
    }else{
        const div=document.createElement('div');
        div.className='session-item active'; div.dataset.uuid=uuid;
        div.onclick=()=>loadSession(uuid);
        div.innerHTML=`<div class="session-icon"><i class="bi bi-chat-dots"></i></div>
            <div class="session-info">
                <div class="session-title">${title}</div>
                <div class="session-date">Baru saja</div>
            </div>
            <button class="btn-del-session" onclick="deleteSession(event,'${uuid}')" title="Hapus sesi ini">
                <i class="bi bi-trash3"></i>
            </button>`;
        list.prepend(div);
    }
    list.querySelectorAll('.session-item').forEach(el=>el.classList.remove('active'));
    list.querySelector(`[data-uuid="${uuid}"]`)?.classList.add('active');
}

async function loadSession(uuid){
    closeMobileSidebar();
    document.querySelectorAll('.session-item').forEach(el=>el.classList.remove('active'));
    document.querySelector(`[data-uuid="${uuid}"]`)?.classList.add('active');
    try{
        const res=await fetch(`/kasir/mineai/session/${uuid}`,{headers:{'Accept':'application/json'}});
        const data=await res.json();
        currentSession=data.uuid; businessContext=data.business_context||{}; chatHistory=data.messages||[];
        document.getElementById('heroStage').style.display='none';
        document.getElementById('insightCard').style.display='none';
        const cb=document.getElementById('chatBox'); cb.style.display='flex';
        document.getElementById('chatMessages').innerHTML='';
        chatHistory.forEach(m=>appendBubble(m.role,m.content));
        scrollBottom();
    }catch(e){ alert('Gagal memuat sesi. Coba lagi!'); }
}

async function deleteSession(e,uuid){
    e.stopPropagation();

    const result = await Swal.fire({
        title: 'Hapus riwayat ini?',
        text: 'Seluruh percakapan dalam sesi ini akan dihapus permanen.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ee0979',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="bi bi-trash3 me-1"></i> Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-4',
            confirmButton: 'fw-bold',
            cancelButton: 'fw-semibold',
        }
    });

    if (!result.isConfirmed) return;

    try{
        const res=await fetch(`/kasir/mineai/session/${uuid}`,{
            method:'DELETE',
            headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
        });
        if(!res.ok) throw new Error();

        document.querySelector(`[data-uuid="${uuid}"]`)?.remove();
        if(!document.getElementById('sessionList').querySelector('.session-item')){
            document.getElementById('sessionList').innerHTML=
                '<div class="sidebar-empty"><i class="bi bi-chat-square-dots"></i>Belum ada riwayat chat</div>';
        }
        if(currentSession===uuid) startNewChat();

        Swal.fire({
            toast: true, position: 'top-end', icon: 'success',
            title: 'Riwayat dihapus',
            showConfirmButton: false, timer: 2000, timerProgressBar: true
        });
    }catch(e){
        Swal.fire({
            toast: true, position: 'top-end', icon: 'error',
            title: 'Gagal menghapus sesi',
            showConfirmButton: false, timer: 2500
        });
    }
}

/* ── Input handlers ── */
document.getElementById('chatInput').addEventListener('keydown',function(e){
    if(e.key==='Enter'&&!e.shiftKey){e.preventDefault();sendMessage();}
});
document.getElementById('chatInput').addEventListener('input',function(){
    this.style.height='';
    this.style.height=Math.min(this.scrollHeight,120)+'px';
});
</script>
</body>
</html>
