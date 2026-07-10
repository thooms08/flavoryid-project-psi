<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#fd7e14">
    <link rel="apple-touch-icon" href="/logo-192.png">
    
    <title>@yield('title', 'Aplikasi Flavory.id - Solusi Digitalisasi Usaha Kuliner & Point of Sale (POS)')</title>
    
    <meta name="description" content="@yield('meta_description', 'Flavory.id membantu digitalisasi usaha kuliner Anda. Nikmati fitur kasir offline/online, cetak struk dapur, manajemen stok, hingga laporan & prediksi performa bisnis secara real-time.')">
    <meta name="keywords" content="Aplikasi kasir, POS kuliner, software F&B, digitalisasi UMKM, kasir offline, kasir online, Aplikasi Flavory.id">
    <meta name="author" content="Aplikasi Flavory.id">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Flavory.id - Solusi Digitalisasi Usaha Kuliner & Point of Sale (POS)')">
    <meta property="og:description" content="@yield('meta_description', 'Tinggalkan cara manual! Flavory.id hadir untuk mengelola bisnis kuliner Anda. Nikmati fitur kasir pintar, laporan otomatis, dan prediksi omzet.')">
    <meta property="og:image" content="@yield('meta_image', asset('assets/preview.jpeg'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="Aplikasi Flavory.id">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('title', 'Flavory.id - Solusi Digitalisasi Usaha Kuliner')">
    <meta name="twitter:description" content="@yield('meta_description', 'Tinggalkan cara manual! Flavory.id hadir untuk mengelola bisnis kuliner Anda. Nikmati fitur kasir pintar dan laporan otomatis.')">
    <meta name="twitter:image" content="@yield('meta_image', asset('assets/preview.jpeg'))">

    @include('layouts.favicon')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-VF266XQCWN"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-VF266XQCWN');
    </script>

    <style>
        :root {
            --primary-orange: #ff6a00;
            --dark-orange: #e65f00;
            --light-orange: #fff4e6;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* Typography & Warna Dasar */
        h1, h2, h3, h4, h5, h6 { font-weight: 700; }
        .text-orange { color: var(--primary-orange) !important; }
        .bg-orange { background-color: var(--primary-orange) !important; color: white; }
        .bg-light-orange { background-color: var(--light-orange) !important; }

        /* Global Buttons */
       /* Global Buttons */
        .btn-orange {
            background-color: var(--primary-orange);
            color: white;
            font-weight: 600;
            padding: 8px 24px; /* Kurangi padding atas-bawah sedikit biar gak terlalu gemuk */
            border-radius: 50px;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 106, 0, 0.3);
            white-space: nowrap; /* KUNCI: Biar teks "Coba Gratis" gak turun ke baris baru */
        }
        .btn-orange:hover {
            background-color: var(--dark-orange);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 106, 0, 0.4);
        }
        .btn-outline-orange {
            border: 2px solid var(--primary-orange);
            color: var(--primary-orange);
            font-weight: 600;
            padding: 8px 24px; /* Samakan dengan btn-orange */
            border-radius: 50px;
            transition: all 0.3s ease;
            background: transparent;
            white-space: nowrap; /* KUNCI: Biar teks "Masuk" konsisten sebaris */
        }
        .btn-outline-orange:hover {
            background-color: var(--primary-orange);
            color: white;
        }
        /* Navbar */
        .navbar {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .navbar-brand img { 
            height: 42px;
            width: auto;
        }
        .navbar-brand-text {
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--text-dark);
            letter-spacing: -0.5px;
        }
        .navbar-brand-text .text-orange {
            color: var(--primary-orange);
        }
        .navbar-brand:hover .navbar-brand-text {
            color: var(--primary-orange);
        }
        
        .nav-link { 
            font-weight: 500; 
            color: var(--text-dark) !important; 
            transition: color 0.2s, font-weight 0.2s; 
            margin: 0 4px;
            padding: 6px 12px !important;
            border-radius: 8px;
        }
        .nav-link:hover {
            color: var(--primary-orange) !important;
            background: var(--light-orange);
        }
        .nav-link.active { 
            color: var(--primary-orange) !important; 
            font-weight: 700; 
        }

        /* Dropdown Produk */
        .navbar .dropdown-menu {
            border: 1px solid rgba(0,0,0,.07);
            border-radius: 16px;
            padding: 8px;
            box-shadow: 0 12px 40px rgba(0,0,0,.1);
            min-width: 240px;
            margin-top: 8px !important;
        }
        .navbar .dropdown-item {
            border-radius: 10px;
            padding: 10px 14px;
            font-size: .88rem;
            font-weight: 500;
            color: var(--text-dark);
            display: flex;
            align-items: flex-start;
            gap: 12px;
            transition: background .15s;
        }
        .navbar .dropdown-item:hover {
            background: var(--light-orange);
            color: var(--primary-orange);
        }
        .navbar .dropdown-item:hover .dd-icon { background: var(--primary-orange); color: #fff; }
        .dd-icon {
            width: 32px; height: 32px; flex-shrink: 0;
            background: #f1f5f9;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: .9rem;
            color: var(--primary-orange);
            transition: background .15s, color .15s;
        }
        .dd-label strong { display: block; font-size: .85rem; font-weight: 700; line-height: 1.2; }
        .dd-label span   { font-size: .75rem; color: #94a3b8; line-height: 1.3; }
        .dropdown-toggle::after { display: none; } /* hilangkan caret default */
        .nav-chevron {
            font-size: .7rem;
            margin-left: 3px;
            transition: transform .25s ease;
            display: inline-block;
            vertical-align: middle;
        }
        .nav-item.dropdown:hover .nav-chevron,
        .nav-item.dropdown .nav-link[aria-expanded="true"] .nav-chevron {
            transform: rotate(-180deg);
        }

        /* Badge NEW */
        .badge-new {
            background: var(--primary-orange);
            color: #fff;
            font-size: .6rem;
            font-weight: 800;
            padding: 2px 6px;
            border-radius: 20px;
            vertical-align: middle;
            margin-left: 4px;
            letter-spacing: .3px;
        }

        .navbar-toggler:focus { box-shadow: none; }
        .hamburger-icon { transition: transform 0.4s ease-in-out; display: inline-block; }
        .navbar-toggler[aria-expanded="true"] .hamburger-icon { transform: rotate(180deg); }

        /* CSS Banner Install PWA */
        .pwa-install-banner {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background-color: white;
            border: 2px solid var(--primary-orange);
            box-shadow: 0 10px 30px rgba(255, 106, 0, 0.2);
            padding: 16px 24px;
            border-radius: 16px;
            z-index: 10000;
            display: flex;
            align-items: center;
            gap: 16px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            width: 90%;
            max-width: 400px;
        }
        
        .pwa-install-banner.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
            visibility: visible;
        }

        .pwa-icon {
            width: 45px;
            height: 45px;
            background-color: var(--light-orange);
            color: var(--primary-orange);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .pwa-text { flex-grow: 1; }
        .pwa-text h6 { margin: 0; font-weight: 700; font-size: 14px; }
        .pwa-text p { margin: 0; font-size: 12px; color: var(--text-muted); }
        
        .btn-close-pwa {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 1.2rem;
            padding: 0;
            margin-left: 8px;
        }
    </style>

    @stack('styles')
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('assets/logo-bg.png') }}" alt="Flavory.id Logo"
                     onerror="this.style.display='none'">
                <span class="navbar-brand-text">Flavory<span class="text-orange">.id</span></span>
            </a>
            
            <button class="navbar-toggler border-0 shadow-none p-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list fs-1 hamburger-icon text-dark"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 mt-3 mt-lg-0 text-center text-lg-start align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                           href="{{ route('home') }}">
                            Beranda
                        </a>
                    </li>
                    {{-- Dropdown: Produk (gabungan anchor home) --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link d-flex align-items-center gap-1
                            {{ request()->is('/') || request()->routeIs('tentang') ? 'active' : '' }}"
                           href="#" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            Produk
                            <i class="bi bi-chevron-down nav-chevron"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ request()->is('/') ? '#fitur' : route('home').'#fitur' }}">
                                    <span class="dd-icon"><i class="bi bi-stars"></i></span>
                                    <span class="dd-label">
                                        <strong>Fitur Utama</strong>
                                        <span>Kasir, stok, laporan & AI</span>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ request()->is('/') ? '#keuntungan' : route('home').'#keuntungan' }}">
                                    <span class="dd-icon"><i class="bi bi-trophy"></i></span>
                                    <span class="dd-label">
                                        <strong>Keunggulan</strong>
                                        <span>Kenapa pilih Flavory.id</span>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ request()->is('/') ? '#paket' : route('home').'#paket' }}">
                                    <span class="dd-icon"><i class="bi bi-tag"></i></span>
                                    <span class="dd-label">
                                        <strong>Harga & Paket</strong>
                                        <span>Mulai gratis 30 hari</span>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Event — halaman standalone, relevan untuk akuisisi --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('event.*') ? 'active' : '' }}"
                           href="{{ route('event.index') }}">
                            Event
                            <span class="badge-new">BARU</span>
                        </a>
                    </li>
                   <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('tentang') ? 'active' : '' }}"
                           href="{{ route('tentang') }}">
                            Tentang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('panduan.*') ? 'active' : '' }}"
                           href="{{ route('panduan.daftar') }}">
                            Panduan
                        </a>
                    </li>
                    {{-- Blog --}}
                    <li class="nav-item">
                        <a class="nav-link" href="https://blog.flavory.id" target="_blank" rel="noopener noreferrer">
                            Blog
                        </a>
                    </li>

                </ul>
                
                <div class="d-flex flex-column flex-lg-row align-items-center gap-2 gap-lg-3 mt-3 mt-lg-0">
                    @auth
                        <a href="{{ route('kasir.dashboard') }}" class="btn btn-orange w-100 w-lg-auto">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-orange w-100 w-lg-auto">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-orange w-100 w-lg-auto">Coba Gratis</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-dark text-white pt-5 pb-4">
        <div class="container">
            <div class="row g-4 border-bottom border-secondary pb-4 mb-4">
                <div class="col-lg-5 text-center text-lg-start">
                    <h3 class="fw-bold text-orange mb-3">Flavory.id</h3>
                    <p class="text-secondary pe-lg-5">Aplikasi kasir cerdas terbaik untuk UMKM kuliner di Indonesia. Membantu usaha F&B untuk tumbuh, terorganisir, dan untung maksimal.</p>
                </div>
                <div class="col-lg-3 text-center text-lg-start">
                    <h5 class="fw-bold mb-3 text-white">Tautan Cepat</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('tentang') }}" class="text-secondary text-decoration-none">Tentang</a></li>
                        <li class="mb-2"><a href="{{ route('event.index') }}" class="text-secondary text-decoration-none">Event</a></li>
                        <li class="mb-2"><a href="{{ route('panduan.daftar') }}" class="text-secondary text-decoration-none">Panduan</a></li>
                        <li class="mb-2"><a href="{{ route('faq') }}" class="text-secondary text-decoration-none">FAQ</a></li>
                        <li class="mb-2"><a href="{{ route('kebijakan.privasi') }}" class="text-secondary text-decoration-none">Kebijakan & Privasi</a></li>
                        <li class="mb-2"><a href="{{ route('syarat.ketentuan') }}" class="text-secondary text-decoration-none">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
                <div id="kontak" class="col-lg-4 text-center text-lg-start">
                    <h5 class="fw-bold mb-3 text-white">Kontak Kami</h5>
                    <ul class="list-unstyled text-secondary">
                       <li class="mb-2">
                            <a href="mailto:adminku@flavory.id" class="text-decoration-none text-reset">
                                <i class="bi bi-envelope-fill text-orange me-2"></i> adminku@flavory.id
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="https://wa.me/6285797574754?text=Hallo!%20kak%20admin,%20aku%20mau%20informasi%20lebih%20lanjut%20soal%20flavory.id%20donk..." target="_blank" rel="noopener noreferrer" class="text-decoration-none text-reset">
                                <i class="bi bi-whatsapp text-orange me-2"></i> +62 8579 7574 754
                            </a>
                        </li>
                        <li class="mb-2"><i class="bi bi-geo-alt-fill text-orange me-2"></i> Karawang, Jawa Barat, Indonesia</li>
                    </ul>
                </div>
            </div>
            <div class="text-center text-secondary small">
                &copy; {{ date('Y') }} Flavory.id. All Rights Reserved.
            </div>
        </div>
    </footer>

    <div id="installPwaBanner" class="pwa-install-banner">
        <div class="pwa-icon">
            <i class="bi bi-phone-vibrate"></i>
        </div>
        <div class="pwa-text">
            <h6>Install Flavory.id</h6>
            <p>Akses kasir lebih cepat & offline!</p>
        </div>
        <button id="btnInstallPwa" class="btn btn-orange btn-sm px-3 py-1" style="font-size: 12px;">Install</button>
        <button id="btnClosePwa" class="btn-close-pwa"><i class="bi bi-x"></i></button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // --- 1. Logika ScrollSpy Navbar ---
        const sections = document.querySelectorAll("header[id], section[id]");
        const navLinks = document.querySelectorAll(".navbar-nav .nav-link");

        function activateNavLink() {
            let current = "";
            sections.forEach((section) => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= sectionTop - 120) {
                    current = section.getAttribute("id");
                }
            });

            navLinks.forEach((link) => {
                if (link.getAttribute("href").includes("#")) {
                    link.classList.remove("active");
                    if (current && link.getAttribute("href").endsWith("#" + current)) {
                        link.classList.add("active");
                    }
                }
            });
        }

        activateNavLink();
        window.addEventListener("scroll", activateNavLink);

        const toggler = document.querySelector('.navbar-toggler');
        const icon = toggler.querySelector('.hamburger-icon');
        if(toggler && icon) {
            toggler.addEventListener('click', function() {
                if (toggler.getAttribute('aria-expanded') === 'true') {
                    icon.classList.remove('bi-list');
                    icon.classList.add('bi-x');
                } else {
                    setTimeout(() => {
                        icon.classList.remove('bi-x');
                        icon.classList.add('bi-list');
                    }, 150);
                }
            });
        }

        // --- 2. Registrasi Service Worker PWA ---
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('ServiceWorker terdaftar sukses dengan scope: ', registration.scope);
                    })
                    .catch(err => {
                        console.log('ServiceWorker gagal mendaftar: ', err);
                    });
            });
        }

        // --- 3. Logika Install PWA (Prompt) ---
        let deferredPrompt;
        const installBanner = document.getElementById('installPwaBanner');
        const btnInstall = document.getElementById('btnInstallPwa');
        const btnClose = document.getElementById('btnClosePwa');

        // Menangkap event sebelum prompt install muncul
        window.addEventListener('beforeinstallprompt', (e) => {
            // Mencegah mini-infobar bawaan browser muncul (khusus mobile)
            e.preventDefault();
            // Simpan event agar bisa dipicu nanti
            deferredPrompt = e;
            // Tampilkan custom banner kita
            installBanner.classList.add('show');
        });

        // Saat tombol "Install" diklik
        btnInstall.addEventListener('click', async () => {
            // Sembunyikan banner
            installBanner.classList.remove('show');
            if (deferredPrompt) {
                // Tampilkan prompt instalasi bawaan browser
                deferredPrompt.prompt();
                // Tunggu respon pengguna (apakah mengklik Install atau Cancel)
                const { outcome } = await deferredPrompt.userChoice;
                console.log(`Respon pengguna terkait instalasi: ${outcome}`);
                // Hapus prompt karena hanya bisa dipakai sekali
                deferredPrompt = null;
            }
        });

        // Saat tombol silang (Close) diklik
        btnClose.addEventListener('click', () => {
            installBanner.classList.remove('show');
        });

        // Deteksi jika aplikasi berhasil diinstal
        window.addEventListener('appinstalled', () => {
            installBanner.classList.remove('show');
            deferredPrompt = null;
            console.log('PWA Flavory.id berhasil diinstal!');
        });
    });
    </script>
    
    @stack('scripts')
    @include('layouts.wa')
    @include('layouts.loading')
</body>
</html>