@extends('layouts.app')

@section('title', 'Panduan Memulihkan Pesanan Batal | Flavory.id')

@push('styles')
<meta name="description" content="Cara memulihkan pesanan kasir yang terhapus atau dibatalkan di Flavory.id. Ketahui fitur riwayat pesanan batal flavory untuk menjaga keamanan data penjualan.">
<meta name="keywords" content="pesanan batal kasir, memulihkan pesanan kasir, riwayat pesanan batal flavory, fitur kasir flavory, aplikasi kasir aman">
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

    /* Feature Cards */
    .feature-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 20px;
        height: 100%;
        transition: all 0.3s ease;
        display: flex;
        align-items: flex-start;
        gap: 15px;
    }

    .feature-card:hover {
        border-color: var(--primary-orange);
        box-shadow: 0 10px 20px rgba(255, 106, 0, 0.05);
        transform: translateY(-3px);
    }

    .feature-icon {
        width: 45px;
        height: 45px;
        background-color: var(--light-orange);
        color: var(--primary-orange);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
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
        text-align: center;
    }

    .step-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(255, 106, 0, 0.08);
        border-color: rgba(255, 106, 0, 0.2);
    }

    .step-number {
        width: 45px;
        height: 45px;
        background-color: var(--light-orange);
        color: var(--primary-orange);
        font-size: 1.3rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        margin-bottom: 15px;
    }

    /* Info Alert */
    .info-alert {
        background-color: white;
        border-left: 5px solid var(--primary-orange);
        border-radius: 0.8rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
        padding: 20px;
    }

    /* Important Alert Box */
    .important-alert {
        background-color: #fef2f2;
        border-left: 5px solid #ef4444;
        border-radius: 1rem;
        padding: 25px;
        box-shadow: 0 10px 25px rgba(239, 68, 68, 0.05);
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
                        <i class="bi bi-arrow-counterclockwise me-1" aria-hidden="true"></i> Recovery Pesanan
                    </span>
                    <h1>Panduan Memulihkan Pesanan yang Dibatalkan</h1>
                    <p class="text-muted mt-3 fs-5">
                        Pelajari cara mengembalikan pesanan yang terhapus ke antrean kasir serta memahami fitur keamanan halaman Pesanan Batal di Flavory.id.
                    </p>
                </header>

                <div class="ratio ratio-16x9 mb-5 shadow-sm rounded-4 overflow-hidden" style="border: 1px solid rgba(0,0,0,0.05);">
                    <iframe src="https://www.youtube.com/embed/TWzO91JkJsY" title="Video Panduan" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>

                <section class="mb-5">
                    <div class="info-alert d-flex flex-column flex-md-row align-items-md-center gap-4">
                        <div class="bg-light p-3 rounded-3 border text-center" style="min-width: 120px;">
                            <i class="bi bi-trash3-fill fs-1 text-danger" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h2 class="h5 fw-bold text-dark mb-2">Ke Mana Perginya Pesanan yang Dihapus?</h2>
                            <p class="text-muted mb-2">
                                Jika Anda menghapus pesanan dari antrean di dashboard kasir (melalui ikon tong sampah), pesanan tersebut <strong>tidak langsung hilang atau terhapus permanen</strong>, melainkan akan dipindahkan ke halaman khusus bernama <strong>Pesanan Batal</strong>.
                            </p>
                            <p class="mb-0 fw-bold" style="color: var(--primary-orange);">
                                <i class="bi bi-box-arrow-right me-1" aria-hidden="true"></i> Cara Mengakses: Klik tab "Pesanan Batal" pada menu navigasi kasir Anda.
                            </p>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <h2 class="section-title">Fitur Halaman Pesanan Batal</h2>
                    <p class="text-muted mb-4">Setelah masuk ke halaman Pesanan Batal, Anda akan menemukan beberapa fitur berikut untuk memudahkan pemantauan:</p>
                    
                    <div class="row g-4">
                        <div class="col-md-4">
                            <article class="feature-card">
                                <div class="feature-icon" aria-hidden="true"><i class="bi bi-calculator"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-1">Total Batal</h3>
                                    <p class="text-muted small mb-0">Melihat total keseluruhan pesanan yang pernah dibatalkan.</p>
                                </div>
                            </article>
                        </div>
                        <div class="col-md-4">
                            <article class="feature-card">
                                <div class="feature-icon" aria-hidden="true"><i class="bi bi-card-list"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-1">Daftar Pesanan</h3>
                                    <p class="text-muted small mb-0">Menampilkan pesanan batal dalam bentuk <em>card</em> (mirip dengan riwayat pesanan biasa).</p>
                                </div>
                            </article>
                        </div>
                        <div class="col-md-4">
                            <article class="feature-card">
                                <div class="feature-icon" aria-hidden="true"><i class="bi bi-calendar-check"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-1">Filter Tanggal</h3>
                                    <p class="text-muted small mb-0">Memfilter data pesanan batal berdasarkan rentang tanggal tertentu.</p>
                                </div>
                            </article>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <h2 class="section-title">Cara Memulihkan Pesanan</h2>
                    <p class="text-muted mb-4">Salah hapus pesanan? Jangan khawatir, ikuti langkah mudah berikut untuk mengembalikannya ke antrean dashboard kasir:</p>

                    <div class="row g-4">
                        <div class="col-md-6 col-lg-3">
                            <article class="step-card">
                                <div class="step-number" aria-hidden="true">1</div>
                                <h3 class="h6 fw-bold text-dark mb-2">Pilih Pesanan</h3>
                                <p class="text-muted small mb-0">Cari pesanan yang ingin dikembalikan, lalu klik tombol <strong>"Pulihkan"</strong> pada <em>card</em> pesanan tersebut.</p>
                            </article>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <article class="step-card">
                                <div class="step-number" aria-hidden="true">2</div>
                                <h3 class="h6 fw-bold text-dark mb-2">Konfirmasi</h3>
                                <p class="text-muted small mb-0">Akan muncul sebuah <em>pop-up</em> konfirmasi di layar. Klik tombol <strong>"Ya, Pulihkan"</strong> untuk menyetujui.</p>
                            </article>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <article class="step-card">
                                <div class="step-number" aria-hidden="true">3</div>
                                <h3 class="h6 fw-bold text-dark mb-2">Proses Sistem</h3>
                                <p class="text-muted small mb-0">Tunggu beberapa saat. Sistem sedang memproses pengembalian data ke antrean utama.</p>
                            </article>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <article class="step-card">
                                <div class="step-number" aria-hidden="true">4</div>
                                <h3 class="h6 fw-bold text-dark mb-2">Selesai!</h3>
                                <p class="text-muted small mb-0">Pesanan otomatis kembali ke dashboard sebagai pesanan baru. Anda dapat melanjutkan proses <strong>pembayaran</strong>.</p>
                            </article>
                        </div>
                    </div>
                </section>

                <aside class="important-alert mt-5">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <i class="bi bi-shield-lock-fill text-danger fs-2" aria-hidden="true"></i>
                        <h3 class="h5 fw-bold text-dark mb-0">Catatan Keamanan Sistem</h3>
                    </div>
                    <p class="text-muted mb-0" style="font-size: 1.05rem; line-height: 1.7;">
                        Sebagai bentuk komitmen keamanan, pada halaman Pesanan Batal ini <strong>tidak tersedia fitur "Hapus"</strong>. Seluruh pesanan yang dibatalkan akan tercatat secara permanen di dalam sistem kami. Kebijakan ini bertujuan untuk memastikan transparansi dan <strong>mencegah manipulasi data</strong> oleh pihak yang tidak bertanggung jawab.
                    </p>
                </aside>

            </div>
        </div>
    </div>
</main>
@endsection