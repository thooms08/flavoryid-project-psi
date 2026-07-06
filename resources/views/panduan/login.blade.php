@extends('layouts.app')

@section('title', 'Panduan Log In | Flavory.id')

@push('styles')
<meta name="description" content="Panduan mudah cara log in dan masuk ke dashboard kasir Flavory.id. Akses sistem kasir bisnis kuliner Anda dengan aman, cepat, dan praktis.">
<meta name="keywords" content="cara login flavory, masuk kasir flavory, panduan login kasir, aplikasi kasir kuliner, point of sale login">
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
        list-style: none; /* SEO Fix: Menghilangkan angka bawaan browser */
    }
    
    /* Garis vertikal timeline */
    .timeline::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 15px; 
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
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.25rem;
    }

    .guide-card h2 i {
        color: var(--primary-orange);
        font-size: 1.3rem;
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
                        <i class="bi bi-journal-text me-1" aria-hidden="true"></i> Pusat Bantuan
                    </span>
                    <h1>Panduan Log In di Flavory.id</h1>
                    <p class="mt-3">Akses dashboard kasir Anda dengan mudah dan aman melalui langkah-langkah berikut.</p>
                </header>

                <section>
                    <ol class="timeline">
                        
                        <li class="timeline-item">
                            <div class="timeline-icon" aria-hidden="true">1</div>
                            <article class="guide-card">
                                <h2><i class="bi bi-box-arrow-in-right" aria-hidden="true"></i> Akses Halaman Log In</h2>
                                <p class="text-muted mb-0">
                                    Klik tombol <strong>Log In</strong> yang berada di pojok kanan atas layar Anda. <br>
                                    <small class="d-block mt-2 text-secondary">
                                        <em>*Jika menggunakan HP, buka menu hamburger (<i class="bi bi-list fw-bold" aria-hidden="true"></i>) terlebih dahulu, lalu pilih tombol <strong>Log In</strong>. Setelah itu, Anda akan langsung diarahkan ke halaman login.</em>
                                    </small>
                                </p>
                            </article>
                        </li>

                        <li class="timeline-item">
                            <div class="timeline-icon" aria-hidden="true">2</div>
                            <article class="guide-card">
                                <h2><i class="bi bi-person-vcard" aria-hidden="true"></i> Masukkan Kredensial</h2>
                                <p class="text-muted mb-0">
                                    Pada form yang tersedia, ketikkan <strong>Username</strong> dan <strong>Password</strong> yang telah Anda buat saat proses pendaftaran akun sebelumnya.
                                </p>
                            </article>
                        </li>

                        <li class="timeline-item">
                            <div class="timeline-icon" aria-hidden="true">3</div>
                            <article class="guide-card">
                                <h2><i class="bi bi-check2-square" aria-hidden="true"></i> Opsi "Ingat Saya"</h2>
                                <p class="text-muted mb-0">
                                    Jika Anda menggunakan perangkat pribadi (bukan perangkat publik), Anda dapat mencentang opsi <strong>"Ingat Saya" (Tetap masuk)</strong>. Fitur ini memungkinkan Anda untuk tetap berada dalam keadaan *login* tanpa perlu memasukkan password berulang kali selama beberapa hari ke depan.
                                </p>
                            </article>
                        </li>

                        <li class="timeline-item">
                            <div class="timeline-icon" aria-hidden="true">4</div>
                            <article class="guide-card">
                                <h2><i class="bi bi-speedometer2" aria-hidden="true"></i> Masuk ke Dashboard</h2>
                                <p class="text-muted mb-0">
                                    Periksa kembali data yang Anda masukkan. Jika sudah benar, klik tombol <strong>"Masuk Sekarang"</strong>. Sistem akan memverifikasi data Anda dan Anda akan langsung diarahkan ke halaman <strong>Dashboard Kasir</strong>.
                                </p>
                            </article>
                        </li>

                    </ol> 
                    
                    <aside class="closing-box mt-5">
                        <i class="bi bi-emoji-smile-fill" style="font-size: 2.5rem; color: #ffdda8;" aria-hidden="true"></i>
                        <h3 class="fw-bold mt-3 mb-2">Selamat Menggunakan Flavory.id!</h3>
                        <p class="mb-4 text-light" style="font-size: 1.1rem; opacity: 0.9;">
                            Kelola pesanan, pantau laporan bisnis, dan tingkatkan efisiensi operasional outlet Anda.
                        </p>
                        <a href="{{ route('login') ?? '#' }}" class="btn btn-light text-orange fw-bold rounded-pill px-5 py-2 shadow-sm">
                            Log In Sekarang <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
                        </a>
                    </aside>
                </section>
                
            </div>
        </div>

    </div>
</main>
@endsection