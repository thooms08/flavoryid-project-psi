<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Flavory.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    @include('layouts.favicon')
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .bg-mesh {
            background-color: #fff7ed;
            background-image:
                radial-gradient(at 0% 0%, rgba(251,146,60,.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(251,146,60,.1) 0px, transparent 50%);
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .animate-spin { animation: spin .8s linear infinite; }
    </style>
</head>
<body class="bg-mesh min-h-screen flex items-center justify-center p-4 md:p-8">

<div class="w-full max-w-[420px]" x-data="{ showPass: false }">

    {{-- Kembali ke beranda --}}
    <div class="mb-6 flex justify-start">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white/60 hover:bg-white text-slate-600 hover:text-orange-600 font-bold text-sm rounded-xl shadow-sm backdrop-blur-md border border-white/50 transition-all">
            <i class="bi bi-arrow-left"></i> Beranda
        </a>
    </div>

    {{-- Header --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-orange-500 rounded-2xl shadow-xl shadow-orange-200 mb-4">
            <i class="bi bi-person-badge text-white text-3xl"></i>
        </div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
            Flavory<span class="text-orange-600">.id</span>
        </h1>
        <p class="text-slate-500 text-sm mt-2 font-medium">Selamat datang kembali, Partner!</p>
    </div>

    <div class="bg-white/80 backdrop-blur-xl p-8 md:p-10 rounded-[2.5rem] shadow-2xl shadow-orange-100 border border-white">

        {{-- Error dari server --}}
        @if ($errors->any())
            <div class="mb-5 p-4 bg-red-50 border-l-4 border-red-500 rounded-xl">
                <div class="flex items-center gap-3">
                    <i class="bi bi-exclamation-circle-fill text-red-500"></i>
                    <p class="text-xs font-bold text-red-700">{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

        {{-- Error offline --}}
        <div id="offlineError" class="hidden mb-5 p-4 bg-red-50 border-l-4 border-red-500 rounded-xl">
            <div class="flex items-center gap-3">
                <i class="bi bi-wifi-off text-red-500"></i>
                <p class="text-xs font-bold text-red-700">Username atau password salah.</p>
            </div>
        </div>

        {{-- Banner offline --}}
        <div id="offlineBanner" class="hidden mb-5 p-3 bg-blue-50 border border-blue-200 rounded-xl">
            <div class="flex items-center gap-2">
                <i class="bi bi-wifi-off text-blue-500 flex-shrink-0"></i>
                <p class="text-[11px] font-semibold text-blue-700">
                    Mode offline - login dengan data tersimpan di perangkat ini.
                </p>
            </div>
        </div>

        <form id="loginForm" action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Banner akun demo (tampil jika ada ?demo=1) --}}
            <div id="demoBanner" class="{{ ($demoMode ?? false) ? '' : 'hidden' }} mb-1 p-4 rounded-2xl border border-orange-200 bg-orange-50">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-xl bg-orange-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="bi bi-person-badge-fill text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black text-orange-700 uppercase tracking-wide mb-1">Akun Demo Gratis</p>
                        <p class="text-[11px] text-orange-600 leading-relaxed mb-2">
                            Gunakan kredensial berikut untuk menjelajahi fitur Flavory.id tanpa daftar.
                        </p>
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold text-slate-500 uppercase w-16">Username</span>
                                <code class="text-xs font-black text-orange-700 bg-white border border-orange-200 px-2 py-0.5 rounded-lg">contoh-kasir</code>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold text-slate-500 uppercase w-16">Password</span>
                                <code class="text-xs font-black text-orange-700 bg-white border border-orange-200 px-2 py-0.5 rounded-lg">password123</code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Username --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest ml-1">Username</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-orange-500 transition-colors">
                        <i class="bi bi-person-circle"></i>
                    </span>
                    <input type="text" name="username" id="inputUsername"
                        value="{{ old('username') }}" required
                        placeholder="Masukkan username"
                        class="block w-full pl-11 pr-4 py-3.5 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-100 focus:border-orange-500 outline-none transition-all">
                </div>
            </div>

            {{-- Password --}}
            <div class="space-y-1.5">
                <div class="flex justify-between items-center">
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-widest ml-1">Password</label>
                    <a href="{{ route('lupa-password') }}" class="text-[10px] font-bold text-orange-600 uppercase">Lupa Password?</a>
                </div>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-orange-500 transition-colors">
                        <i class="bi bi-shield-lock"></i>
                    </span>
                    <input :type="showPass ? 'text' : 'password'" name="password" id="inputPassword"
                        required placeholder="••••••••"
                        class="block w-full pl-11 pr-12 py-3.5 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-100 focus:border-orange-500 outline-none transition-all">
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

            {{-- Tombol submit --}}
            <div class="pt-2">
                <button type="submit" id="btnLogin"
                    class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-orange-200 transition-all active:scale-95 flex items-center justify-center gap-2 tracking-widest text-sm">
                    <span id="btnText">MASUK SEKARANG</span>
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
                Belum punya akun partner?
                <a href="{{ route('register') }}" class="text-orange-600 font-bold hover:underline">Daftar Di Sini</a>
            </p>
        </div>
    </div>
</div>

<script>
// ── Auto-fill akun demo jika demoMode aktif ──
(function() {
    @if($demoMode ?? false)
        const uEl = document.getElementById('inputUsername');
        const pEl = document.getElementById('inputPassword');
        if (uEl) uEl.value = 'contoh-kasir';
        if (pEl) pEl.value = 'password123';
    @endif
})();
</script>

<script src="{{ asset('pwa-auth.js') }}"></script>
<script>
// ── Service Worker ──
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .catch(err => console.log('SW Reg Failed:', err));
    });
}

// ── Elemen ──
const loginForm   = document.getElementById('loginForm');
const btnLogin    = document.getElementById('btnLogin');
const btnText     = document.getElementById('btnText');
const btnIcon     = document.getElementById('btnIcon');
const btnSpinner  = document.getElementById('btnSpinner');
const offlineErr  = document.getElementById('offlineError');
const offlineBan  = document.getElementById('offlineBanner');

// ── Update UI berdasarkan status jaringan ──
function updateNetworkUI() {
    if (navigator.onLine) {
        offlineBan.classList.add('hidden');
    } else {
        offlineBan.classList.remove('hidden');
    }
}
updateNetworkUI();
window.addEventListener('online',  updateNetworkUI);
window.addEventListener('offline', updateNetworkUI);

// ── Loading state ──
function setLoading(on) {
    btnLogin.disabled = on;
    if (on) {
        btnText.textContent = 'Memproses...';
        btnIcon.classList.add('hidden');
        btnSpinner.classList.remove('hidden');
    } else {
        btnText.textContent = 'MASUK SEKARANG';
        btnIcon.classList.remove('hidden');
        btnSpinner.classList.add('hidden');
    }
}

// ── Submit handler ──
loginForm.addEventListener('submit', async function(e) {
    const user = document.getElementById('inputUsername').value.trim();
    const pass = document.getElementById('inputPassword').value;

    if (navigator.onLine) {
        // ── ONLINE: tampilkan loading, simpan credential, lanjutkan submit ke server ──
        setLoading(true);
        try { await saveCredential(user, pass); } catch (_) {}
        // Biarkan form submit native ke server - tidak ada e.preventDefault()
        return;
    }

    // ── OFFLINE: cegah submit ke server, pakai IndexedDB ──
    e.preventDefault();
    offlineErr.classList.add('hidden');
    setLoading(true);

    try {
        const isValid = await checkCredentialOffline(user, pass);
        if (isValid) {
            window.location.href = "{{ route('kasir.dashboard') }}";
        } else {
            offlineErr.classList.remove('hidden');
            setLoading(false);
        }
    } catch (_) {
        offlineErr.classList.remove('hidden');
        setLoading(false);
    }
});
</script>

@if (session('unverified'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'warning',
            title: 'Akses Ditolak!',
            text: 'Akun Anda belum terverifikasi.',
            confirmButtonColor: '#ea580c',
            confirmButtonText: 'Oke, Mengerti'
        });
    });
</script>
@endif

</body>
</html>
