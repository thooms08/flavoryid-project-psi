@extends('layouts.app')

@section('title', 'Panduan Pendaftaran & Berlangganan | Flavory.id')

@push('styles')
<meta name="description" content="Panduan lengkap cara mendaftar, membuat akun kasir, dan berlangganan di Flavory.id. Dapatkan paket gratis 7 hari untuk digitalisasi bisnis kuliner Anda.">
<meta name="keywords" content="cara daftar flavory, daftar aplikasi kasir, langganan kasir online, panduan pendaftaran POS, aplikasi kasir kuliner">
<meta name="author" content="Flavory.id">

<style>
    /* Tema Warna */
    :root {
        --primary-orange: #ff6a00;
        --dark-orange: #e65f00;
        --light-orange: #fff4e6;
        --text-dark: #1e293b;
        --text-muted: #64748b;
    }

    /* Latar Belakang Halaman */
    .guide-page {
        background-color: #f8fafc;
        min-height: 100vh;
        padding: 120px 0 80px;
    }

    /* Header Panduan */
    .guide-header {
        margin-bottom: 40px;
    }
    .guide-header h1 {
        font-weight: 800;
        color: var(--text-dark);
        letter-spacing: -0.5px;
    }
    .guide-header p {
        color: var(--text-muted);
        font-size: 1.1rem;
        max-width: 700px;
    }

    /* Desain Timeline Vertikal */
    .timeline {
        position: relative;
        padding-left: 2rem;
        margin-bottom: 3rem;
        list-style: none; /* Menghilangkan list bawaan browser */
    }
    
    /* Garis vertikal timeline */
    .timeline::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 15px; /* Menyelaraskan dengan icon */
        width: 3px;
        background: linear-gradient(to bottom, var(--primary-orange), rgba(255, 106, 0, 0.1));
        border-radius: 5px;
    }

    /* Item Timeline */
    .timeline-item {
        position: relative;
        margin-bottom: 2.5rem;
        padding-left: 2.5rem;
    }
    .timeline-item:last-child {
        margin-bottom: 0;
    }

    /* Ikon pada Timeline */
    .timeline-icon {
        position: absolute;
        left: -1.7rem;
        top: 0;
        width: 40px;
        height: 40px;
        background-color: var(--primary-orange);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        font-weight: bold;
        border: 4px solid #f8fafc;
        box-shadow: 0 4px 10px rgba(255, 106, 0, 0.3);
        z-index: 2;
    }

    /* Kartu Konten Panduan */
    .guide-card {
        background: #ffffff;
        border: none;
        border-radius: 1.2rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.04);
        padding: 1.5rem 2rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid rgba(0,0,0,0.02);
    }
    .guide-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(255, 106, 0, 0.08);
        border-color: rgba(255, 106, 0, 0.2);
    }

    /* SEO Fix: Mengganti styling h4 menjadi h2 agar hierarki heading benar */
    .guide-card h2 {
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1rem;
        font-size: 1.25rem; /* Menyesuaikan ukuran visual seperti h4 sebelumnya */
    }

    /* List dalam card */
    .step-list {
        list-style: none;
        padding-left: 0;
        margin-bottom: 0;
    }
    .step-list li {
        position: relative;
        padding-left: 1.8rem;
        margin-bottom: 0.8rem;
        color: var(--text-muted);
    }
    .step-list li::before {
        content: '\F26E'; /* Bootstrap icon check-circle-fill */
        font-family: "bootstrap-icons";
        position: absolute;
        left: 0;
        top: 2px;
        color: var(--primary-orange);
        font-size: 1.1rem;
    }

    /* Alert Trial Khusus */
    .alert-trial {
        background-color: var(--light-orange);
        border: 1px dashed var(--primary-orange);
        border-radius: 0.8rem;
        padding: 1rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-top: 1.2rem;
    }
    .alert-trial i {
        color: var(--primary-orange);
        font-size: 1.5rem;
    }
    .alert-trial p {
        margin: 0;
        color: var(--dark-orange);
        font-weight: 600;
        font-size: 0.95rem;
    }

    /* Penutup */
    .closing-box {
        background: linear-gradient(135deg, var(--primary-orange) 0%, #e65f00 100%);
        border-radius: 1.5rem;
        padding: 2.5rem 2rem;
        text-align: center;
        color: white;
        box-shadow: 0 15px 30px rgba(255, 106, 0, 0.2);
    }

    /* Responsif Mobile */
    @media (max-width: 768px) {
        .guide-page {
            padding: 100px 0 40px;
        }
        .guide-header {
            text-align: center;
        }
        .guide-header p {
            margin: 0 auto;
        }
        .timeline {
            padding-left: 1rem;
        }
        .timeline::before {
            left: 5px;
        }
        .timeline-item {
            padding-left: 1.5rem;
        }
        .timeline-icon {
            left: -1rem;
            width: 32px;
            height: 32px;
            font-size: 1rem;
        }
        .guide-card {
            padding: 1.2rem 1.2rem;
        }
    }
</style>
@endpush

@section('content')
<main class="guide-page">
    <div class="container">
        
        <div class="row">
            
            <div class="col-lg-3 mb-4 mb-lg-0">
                @include('panduan.sidebar')
            </div>

            <div class="col-lg-9">
                <header class="guide-header">
                    <span class="badge bg-light text-orange border border-warning-subtle px-3 py-2 rounded-pill mb-3 fw-bold shadow-sm">
                        <i class="bi bi-journal-text me-1"></i> Pusat Bantuan
                    </span>
                    <h1>Panduan Pendaftaran & Berlangganan di Flavory.id</h1>
                    <p class="mt-3">Ikuti langkah-langkah mudah di bawah ini untuk memulai digitalisasi bisnis kuliner Anda bersama kami.</p>
                </header>

                <div class="ratio ratio-16x9 mb-5 shadow-sm rounded-4 overflow-hidden" style="border: 1px solid rgba(0,0,0,0.05);">
                    <iframe src="https://www.youtube.com/embed/JA5A_ueQae4" title="Video Panduan" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>

                <section>
                    <ol class="timeline">
                        
                        <li class="timeline-item">
                            <div class="timeline-icon" aria-hidden="true">1</div>
                            <article class="guide-card">
                                <h2>Buka Website Flavory.id</h2>
                                <p class="text-muted mb-0">Buka aplikasi peramban (browser) di perangkat Anda (Laptop/PC/HP) lalu ketikkan dan akses alamat website <strong>flavory.id</strong>.</p>
                            </article>
                        </li>

                        <li class="timeline-item">
                            <div class="timeline-icon" aria-hidden="true">2</div>
                            <article class="guide-card">
                                <h2>Klik Tombol Daftar</h2>
                                <p class="text-muted mb-0">
                                    Cari dan klik tombol <span class="badge bg-orange text-white">Daftar</span> atau <span class="badge bg-orange text-white">Daftar Sekarang</span> yang berada di pojok kanan atas layar.<br>
                                    <small class="d-block mt-2 text-secondary"><em>*Jika Anda menggunakan HP (Mobile), ketuk ikon menu hamburger (<i class="bi bi-list fw-bold"></i>) terlebih dahulu, lalu pilih tombol Daftar.</em></small>
                                </p>
                            </article>
                        </li>

                        <li class="timeline-item">
                            <div class="timeline-icon" aria-hidden="true">3</div>
                            <article class="guide-card">
                                <h2>Lengkapi Tahapan Pendaftaran</h2>
                                <p class="text-muted mb-3">Sistem akan mengarahkan Anda ke formulir pendaftaran. Anda hanya perlu menyelesaikan 4 tahapan ringkas berikut:</p>
                                <ul class="step-list">
                                    <li><strong>Membuat akun kasir</strong> (Menentukan username dan password).</li>
                                    <li><strong>Mengisi data manager/owner</strong> (Nama lengkap, WhatsApp, dan Email aktif).</li>
                                    <li><strong>Mengisi profil outlet</strong> (Nama outlet, jenis usaha, alamat, dan foto).</li>
                                    <li><strong>Memilih paket berlangganan</strong> dan melanjutkan ke proses pembayaran.</li>
                                </ul>

                                <div class="alert-trial">
                                    <i class="bi bi-gift-fill" aria-hidden="true"></i>
                                    <div>
                                        <p>Tersedia Paket Gratis!</p>
                                        <span class="text-muted" style="font-size: 0.85rem;">Bagi pengguna baru, kami menyediakan paket uji coba (Trial) secara GRATIS selama 7 hari penuh dengan akses fitur lengkap.</span>
                                    </div>
                                </div>
                            </article>
                        </li>

                        <li class="timeline-item">
                            <div class="timeline-icon" aria-hidden="true">4</div>
                            <article class="guide-card">
                                <h2>Verifikasi Akun via Email</h2>
                                <p class="text-muted mb-0">
                                    Setelah seluruh proses pendaftaran diselesaikan, sistem kami akan mengirimkan <strong>6-digit kode verifikasi</strong> ke alamat email yang Anda daftarkan. Buka kotak masuk email Anda, lalu masukkan kode tersebut ke halaman verifikasi Flavory.id untuk mengaktifkan akun.
                                </p>
                            </article>
                        </li>

                        <li class="timeline-item">
                            <div class="timeline-icon" aria-hidden="true">5</div>
                            <article class="guide-card">
                                <h2>Login & Mulai Berbisnis!</h2>
                                <p class="text-muted mb-0">
                                    Jika verifikasi berhasil, Anda akan otomatis diarahkan ke halaman Login. Silakan masuk menggunakan username dan password yang telah Anda buat, dan Anda akan langsung masuk ke <strong>Dashboard Kasir</strong>.
                                </p>
                            </article>
                        </li>

                    </ol> 
                    
                    <div class="closing-box mt-5">
                        <i class="bi bi-stars" style="font-size: 2.5rem; color: #ffdda8;" aria-hidden="true"></i>
                        <h3 class="fw-bold mt-3 mb-2">Selesai!</h3>
                        <p class="mb-4 text-light" style="font-size: 1.1rem; opacity: 0.9;">
                            Selamat mencoba dan selamat bergabung di ekosistem Kasir Flavory.id. Semoga bisnis kuliner Anda semakin sukses dan berkembang!
                        </p>
                        <a href="{{ route('register') ?? '#' }}" class="btn btn-light text-orange fw-bold rounded-pill px-4 py-2 shadow-sm">
                            Mulai Daftar Sekarang <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </section>
                
            </div>
        </div>

    </div>
</main>
@endsection