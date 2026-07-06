@extends('layouts.app')

@section('title', 'Mode Kasir Offline & Sinkronisasi Pesanan | Panduan Flavory.id')

@push('styles')
<meta name="description" content="Panduan menggunakan mode kasir offline di Flavory.id. Tetap bisa tambah pesanan saat internet terputus, cara sinkronisasi otomatis, dan kelola pesanan offline.">
<meta name="keywords" content="kasir offline, pesanan offline flavory, mode offline kasir, sinkronisasi kasir, point of sale offline, aplikasi kasir tanpa internet">
<meta name="author" content="Flavory.id">

<style>
    :root {
        --primary-orange: #ff6a00;
        --dark-orange: #e65f00;
        --light-orange: #fff4e6;
        --text-dark: #1e293b;
        --text-muted: #64748b;
    }

    /* Latar Belakang & Layout Utama */
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
        max-width: 750px;
    }

    /* Section Titles */
    .section-title {
        font-weight: 700;
        color: var(--text-dark);
        position: relative;
        padding-bottom: 10px;
        margin-bottom: 30px;
    }

    .section-title::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 4px;
        background-color: var(--primary-orange);
        border-radius: 2px;
    }

    /* Step Cards */
    .step-card {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.03);
        border-radius: 1.2rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.02);
        height: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        padding: 25px;
        position: relative;
    }

    .step-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(255, 106, 0, 0.08);
        border-color: rgba(255, 106, 0, 0.2);
    }

    .step-number {
        width: 40px;
        height: 40px;
        background-color: var(--light-orange);
        color: var(--primary-orange);
        font-size: 1.2rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        margin-bottom: 15px;
    }

    /* Feature/Limitation Box */
    .limit-box {
        background-color: #ffffff;
        border-radius: 1rem;
        padding: 20px;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: all 0.2s ease;
    }

    .limit-box:hover {
        border-color: #ef4444;
        background-color: #fef2f2;
    }

    .limit-icon {
        font-size: 1.8rem;
        color: #ef4444; /* Red color for limitations */
    }

    /* Troubleshooting Alert */
    .troubleshoot-alert {
        background-color: #fff4e6;
        border-left: 5px solid var(--primary-orange);
        border-radius: 0.8rem;
        padding: 20px;
    }

    /* Mockup Alert Offline */
    .mockup-alert {
        background-color: #ef4444;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.02); }
        100% { transform: scale(1); }
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
                    <span class="badge bg-white text-orange border border-warning-subtle px-3 py-2 rounded-pill mb-3 fw-bold shadow-sm">
                        <i class="bi bi-wifi-off me-1" aria-hidden="true"></i> Keadaan Darurat / Offline
                    </span>
                    <h1>Panduan Menambah Pesanan Saat Offline</h1>
                    <p class="text-muted mt-3 fs-5">
                        Jangan panik saat internet terputus! Aplikasi Flavory.id dirancang untuk tetap dapat digunakan secara <em>offline</em> agar operasional outlet Anda tidak terhenti.
                    </p>
                </header>

                <section class="mb-5">
                    <div class="bg-white p-4 p-md-5 rounded-4 border shadow-sm text-center">
                        <h2 class="h4 fw-bold text-dark mb-4">Pengalaman Mode Offline</h2>
                        
                        <div class="mb-4">
                            <div class="mockup-alert" aria-hidden="true">
                                <i class="bi bi-wifi-off fs-5"></i> Anda Sedang Dalam Mode Offline
                            </div>
                        </div>

                        <p class="text-muted mb-0" style="font-size: 1.1rem; line-height: 1.8;">
                            Ketika perangkat Anda kehilangan koneksi internet, sistem akan memunculkan notifikasi merah di bagian atas halaman seperti di atas. Meskipun demikian, <strong>Anda tetap dapat menambahkan pesanan seperti biasa</strong>. Data pesanan akan disimpan dengan aman di dalam memori perangkat Anda.
                        </p>
                    </div>
                </section>

                <section class="mb-5">
                    <h2 class="section-title">Keterbatasan Fitur Saat Offline</h2>
                    <p class="text-muted mb-4">Demi menjaga konsistensi data antara perangkat dan server kami, beberapa fitur akan dinonaktifkan sementara saat internet terputus:</p>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <article class="limit-box shadow-sm">
                                <i class="bi bi-pencil-square limit-icon" aria-hidden="true"></i>
                                <h3 class="h6 fw-bold text-dark mb-0">Tidak dapat mengedit pesanan</h3>
                            </article>
                        </div>
                        <div class="col-md-4">
                            <article class="limit-box shadow-sm">
                                <i class="bi bi-trash-fill limit-icon" aria-hidden="true"></i>
                                <h3 class="h6 fw-bold text-dark mb-0">Tidak dapat menghapus pesanan</h3>
                            </article>
                        </div>
                        <div class="col-md-4">
                            <article class="limit-box shadow-sm">
                                <i class="bi bi-wallet2 limit-icon" aria-hidden="true"></i>
                                <h3 class="h6 fw-bold text-dark mb-0">Tidak dapat proses pembayaran</h3>
                            </article>
                        </div>
                    </div>

                    <aside class="alert alert-danger border-0 d-flex align-items-center gap-3">
                        <i class="bi bi-shield-lock-fill fs-3" aria-hidden="true"></i>
                        <div>
                            <strong>Penting:</strong> Tombol-tombol terkait fitur di atas akan disembunyikan. Jika Anda mencoba melakukan trik untuk memproses pembayaran, <strong>sistem akan secara otomatis menolaknya</strong> karena perangkat sedang offline.
                        </div>
                    </aside>
                </section>

                <section class="mb-5">
                    <h2 class="section-title">Kapan Sistem Kembali Normal?</h2>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <article class="step-card bg-white">
                                <div class="step-number" aria-hidden="true"><i class="bi bi-wifi text-orange"></i></div>
                                <h3 class="h5 fw-bold text-dark">1. Internet Tersambung</h3>
                                <p class="text-muted mb-0 small">Segera setelah perangkat Anda terhubung kembali dengan koneksi internet yang stabil, semua tombol dan fitur yang sebelumnya hilang akan langsung aktif kembali.</p>
                            </article>
                        </div>
                        <div class="col-md-6">
                            <article class="step-card bg-white">
                                <div class="step-number" aria-hidden="true"><i class="bi bi-cloud-arrow-up text-orange"></i></div>
                                <h3 class="h5 fw-bold text-dark">2. Sinkronisasi Otomatis</h3>
                                <p class="text-muted mb-0 small">Sistem akan melakukan sinkronisasi otomatis untuk mengirim data pesanan *offline* Anda ke server. Proses ini biasanya hanya memakan waktu <strong>5 hingga 10 detik</strong>.</p>
                            </article>
                        </div>
                    </div>

                    <aside class="troubleshoot-alert">
                        <h3 class="h6 fw-bold text-dark d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-wrench-adjustable text-orange" aria-hidden="true"></i> Jika Sinkronisasi Gagal / Macet:
                        </h3>
                        <p class="text-muted small mb-2">Jika notifikasi offline tidak kunjung hilang padahal internet sudah menyala, lakukan langkah berikut:</p>
                        <ol class="text-muted small mb-0 fw-medium">
                            <li>Matikan koneksi internet (WiFi / Data Seluler) pada perangkat Anda.</li>
                            <li>Tunggu beberapa detik, lalu <strong>nyalakan kembali koneksi internet</strong> untuk memancing (trigger) sistem melakukan sinkronisasi ulang.</li>
                        </ol>
                    </aside>
                </section>

                <section class="mb-5">
                    <h2 class="section-title">Memproses Banyak Pesanan (Setelah Online)</h2>
                    <p class="text-muted mb-4">Jika Anda menumpuk banyak pesanan saat offline, Anda tidak perlu mengonfirmasi pembayarannya satu per satu. Gunakan fitur <strong>Multi-Pesanan</strong>:</p>

                    <div class="row g-4">
                        <div class="col-md-4">
                            <article class="step-card text-center">
                                <div class="mx-auto mb-3" aria-hidden="true">
                                    <i class="bi bi-ui-checks-grid fs-1" style="color: var(--primary-orange);"></i>
                                </div>
                                <h3 class="h6 fw-bold text-dark">Pilih Multi Pesanan</h3>
                                <p class="text-muted small mb-0">Klik tombol <strong>"Pilih Multi Pesanan"</strong> yang berada di bagian atas dashboard kasir Anda.</p>
                            </article>
                        </div>
                        <div class="col-md-4">
                            <article class="step-card text-center">
                                <div class="mx-auto mb-3" aria-hidden="true">
                                    <i class="bi bi-check2-square fs-1" style="color: var(--primary-orange);"></i>
                                </div>
                                <h3 class="h6 fw-bold text-dark">Centang Pesanan</h3>
                                <p class="text-muted small mb-0">Centang kotak (checkbox) pada pesanan-pesanan antrean yang ingin Anda proses pembayarannya secara massal.</p>
                            </article>
                        </div>
                        <div class="col-md-4">
                            <article class="step-card text-center">
                                <div class="mx-auto mb-3" aria-hidden="true">
                                    <i class="bi bi-check2-all fs-1 text-success"></i>
                                </div>
                                <h3 class="h6 fw-bold text-dark">Konfirmasi</h3>
                                <p class="text-muted small mb-0">Klik tombol <strong>"Konfirmasi"</strong>. Semua pesanan yang dicentang akan otomatis dikonfirmasi dengan metode pembayaran <strong>Cash (Tunai)</strong>.</p>
                            </article>
                        </div>
                    </div>
                </section>

                <aside class="card border-0 rounded-4" style="background: linear-gradient(135deg, var(--primary-orange) 0%, #e65f00 100%); color: white;">
                    <div class="card-body p-4 p-md-5 d-flex align-items-center gap-4">
                        <div class="d-none d-md-block">
                            <i class="bi bi-info-circle-fill" style="font-size: 3.5rem; color: #ffdda8;" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h3 class="h5 fw-bold mb-2">Pesan Dari Kami</h3>
                            <p class="mb-0 fs-6 opacity-75">
                                Mode offline sangat membantu menjaga operasional tetap berjalan. Namun, <strong>kami sangat menyarankan Anda untuk segera kembali online (mencari koneksi internet)</strong> agar semua data tersimpan secara permanen di *database* pusat dan terproses dengan sempurna.
                            </p>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </div>
</main>
@endsection