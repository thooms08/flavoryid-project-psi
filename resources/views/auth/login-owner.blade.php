<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Owner / Manager | Flavory.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('layouts.favicon')
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .bg-mesh {
            background-color: #fff7ed;
            background-image:
                radial-gradient(at 0% 0%,   rgba(251,146,60,.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(251,146,60,.1) 0px, transparent 50%);
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .animate-spin { animation: spin .8s linear infinite; }
    </style>
</head>
<body class="bg-mesh min-h-screen flex items-center justify-center p-4 md:p-8">

<div class="w-full max-w-[420px]" x-data="{ showPass: false }">

    {{-- Kembali ke beranda --}}
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-white/60 hover:bg-white text-slate-600 hover:text-orange-600 font-bold text-sm rounded-xl shadow-sm backdrop-blur-md border border-white/50 transition-all">
            <i class="bi bi-arrow-left"></i> Beranda
        </a>
        <a href="{{ route('login') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-white/60 hover:bg-white text-slate-600 hover:text-orange-600 font-bold text-sm rounded-xl shadow-sm backdrop-blur-md border border-white/50 transition-all">
            <i class="bi bi-person-circle"></i> Login Kasir
        </a>
    </div>

    {{-- Header --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-orange-500 rounded-2xl shadow-xl shadow-orange-200 mb-4">
            <i class="bi bi-person-badge-fill text-white text-3xl"></i>
        </div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
            Flavory<span class="text-orange-600">.id</span>
        </h1>
        <p class="text-slate-500 text-sm mt-1 font-medium">Portal Owner &amp; Manager</p>
        <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-bold tracking-widest uppercase"
              style="background: linear-gradient(135deg, #ff6a00, #ff9a00); color: #fff; letter-spacing: 1.5px;">
            Akses Terbatas
        </span>
    </div>

    {{-- Card --}}
    <div class="owner-card bg-white/80 backdrop-blur-xl p-8 md:p-10 rounded-[2.5rem] shadow-2xl shadow-orange-100 border border-white">

        {{-- Pesan error server --}}
        @if ($errors->any())
            <div class="mb-5 p-4 bg-red-50 border-l-4 border-red-500 rounded-xl">
                <div class="flex items-center gap-3">
                    <i class="bi bi-exclamation-circle-fill text-red-500"></i>
                    <p class="text-xs font-bold text-red-700">{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-5 p-4 bg-red-50 border-l-4 border-red-500 rounded-xl">
                <div class="flex items-center gap-3">
                    <i class="bi bi-exclamation-circle-fill text-red-500"></i>
                    <p class="text-xs font-bold text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <form id="ownerLoginForm" action="{{ route('owner.login.post') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Info panel --}}
            <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50 flex items-start gap-3">
                <i class="bi bi-info-circle-fill text-slate-400 mt-0.5 flex-shrink-0"></i>
                <p class="text-[11px] text-slate-500 leading-relaxed font-medium">
                    Halaman ini khusus untuk akun dengan role <strong class="text-slate-700">Owner</strong>.
                    Jika Anda kasir, gunakan <a href="{{ route('login') }}" class="text-orange-600 font-bold hover:underline">halaman login kasir</a>.
                </p>
            </div>

            {{-- Username --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest ml-1">Username</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-orange-500 transition-colors">
                        <i class="bi bi-person-badge"></i>
                    </span>
                    <input type="text" name="username" id="inputUsername"
                        value="{{ old('username') }}" required autocomplete="username"
                        placeholder="Masukkan username owner"
                        class="block w-full pl-11 pr-4 py-3.5 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-100 focus:border-orange-500 outline-none transition-all text-sm">
                </div>
            </div>

            {{-- Password --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest ml-1">Password</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-orange-500 transition-colors">
                        <i class="bi bi-shield-lock"></i>
                    </span>
                    <input :type="showPass ? 'text' : 'password'" name="password" id="inputPassword"
                        required autocomplete="current-password" placeholder="••••••••"
                        class="block w-full pl-11 pr-12 py-3.5 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-100 focus:border-orange-500 outline-none transition-all text-sm">
                    <button type="button" @click="showPass = !showPass"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-orange-600">
                        <i class="bi" :class="showPass ? 'bi-eye-slash' : 'bi-eye'"></i>
                    </button>
                </div>
            </div>

            {{-- Remember --}}
            <div class="flex items-center ml-1">
                <input type="checkbox" name="remember" id="remember"
                    class="w-4 h-4 text-orange-500 border-slate-300 rounded focus:ring-orange-500">
                <label for="remember" class="ml-2 text-xs font-semibold text-slate-500">Tetap masuk selama 7 hari</label>
            </div>

            {{-- Submit --}}
            <div class="pt-2">
                <button type="submit" id="btnLogin"
                    class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-orange-200 transition-all active:scale-95 flex items-center justify-center gap-2 tracking-widest text-sm">
                    <span id="btnText">MASUK SEBAGAI OWNER</span>
                    <i class="bi bi-box-arrow-in-right text-lg" id="btnIcon"></i>
                    <svg id="btnSpinner" class="hidden w-5 h-5 animate-spin"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                </button>
            </div>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-100 text-center">
            <p class="text-slate-400 text-xs font-medium">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-orange-600 font-bold hover:underline">Daftar Di Sini</a>
            </p>
        </div>
    </div>

</div>

<script>
const ownerLoginForm  = document.getElementById('ownerLoginForm');
const btnLogin   = document.getElementById('btnLogin');
const btnText    = document.getElementById('btnText');
const btnIcon    = document.getElementById('btnIcon');
const btnSpinner = document.getElementById('btnSpinner');

ownerLoginForm.addEventListener('submit', function() {
    btnLogin.disabled = true;
    btnText.textContent = 'Memproses...';
    btnIcon.classList.add('hidden');
    btnSpinner.classList.remove('hidden');
});

// ── Auto-refresh halaman saat koneksi pulih ──────────────────────────────
// Jika halaman ini diambil dari cache SW (CSRF token kadaluarsa),
// reload otomatis ketika internet kembali agar token selalu fresh.
let wasOffline = !navigator.onLine;

window.addEventListener('online', function () {
    if (wasOffline) {
        // Paksa reload dari network, abaikan cache browser & SW
        window.location.reload(true);
    }
});

window.addEventListener('offline', function () {
    wasOffline = true;
});
</script>

@if (session('unverified'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'warning',
            title: 'Akses Ditolak!',
            text: '{{ session("unverified") }}',
            confirmButtonColor: '#ea580c',
            confirmButtonText: 'Oke, Mengerti'
        });
    });
</script>
@endif

</body>
</html>
