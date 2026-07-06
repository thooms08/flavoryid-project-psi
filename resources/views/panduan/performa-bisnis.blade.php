{{-- resources/views/panduan/performa-bisnis.blade.php --}}
@extends('layouts.app')

@section('title', 'Panduan Performa Bisnis & Prediksi Omzet | Flavory.id')

@push('styles')
<meta name="description" content="Pelajari cara membaca analisis performa bisnis di Flavory.id. Temukan jam ramai, hari sepi, serta dapatkan prediksi omzet bisnis untuk strategi operasional kasir yang lebih baik.">
<meta name="keywords" content="performa bisnis kasir, analisis penjualan flavory, prediksi omzet bisnis, jam ramai resto, strategi promo kasir">
<meta name="author" content="Flavory.id">

<style>
    :root {
        --primary-orange: #ff6a00;
        --dark-orange: #e65f00;
        --light-orange: #fff4e6;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --success-green: #10b981;
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

    .feature-icon.icon-green {
        background-color: #ecfdf5;
        color: var(--success-green);
    }

    /* Info Alert */
    .info-alert {
        background-color: white;
        border-left: 5px solid var(--primary-orange);
        border-radius: 0.8rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
        padding: 20px;
    }

    /* Tips & Insight Box */
    .insight-alert {
        background-color: #fffbeb;
        border: 1px solid #fde68a;
        border-left: 5px solid #f59e0b;
        border-radius: 1rem;
        padding: 25px;
        box-shadow: 0 10px 25px rgba(245, 158, 11, 0.05);
    }

    .insight-list li {
        margin-bottom: 12px;
        color: var(--text-dark);
    }

    .text-orange { color: var(--primary-orange) !important; }
    .bg-orange { background-color: var(--primary-orange) !important; }

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
                        <i class="bi bi-rocket-takeoff-fill me-1" aria-hidden="true"></i> Analisis Lanjutan
                    </span>
                    <h1>Panduan Fitur Performa Bisnis di Flavory.id</h1>
                    <p class="text-muted mt-3 fs-5">
                        Tingkatkan skala usahamu dengan fitur unggulan Flavory. Pahami pola operasional, evaluasi waktu ramai dan sepi, hingga dapatkan prediksi omzet untuk merancang strategi yang lebih cerdas.
                    </p>
                </header>

                <div class="ratio ratio-16x9 mb-5 shadow-sm rounded-4 overflow-hidden" style="border: 1px solid rgba(0,0,0,0.05);">
                    <iframe src="https://www.youtube.com/embed/D7stVUvRcPk" title="Video Panduan" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>

                <section class="mb-5">
                    <div class="info-alert d-flex flex-column flex-md-row align-items-md-center gap-4">
                        <div class="bg-light p-3 rounded-3 border text-center" style="min-width: 120px;">
                            <i class="bi bi-cursor-fill fs-1 text-orange" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h2 class="h5 fw-bold text-dark mb-2">Cara Mengakses Performa Bisnis</h2>
                            <p class="text-muted mb-2">
                                Fitur ini terintegrasi langsung di dashboard kasir Anda sebagai alat bantu analisis manajerial.
                            </p>
                            <p class="mb-0 fw-bold text-orange">
                                <i class="bi bi-box-arrow-right me-1" aria-hidden="true"></i> Langkah: Klik tab "Opsi Lainnya" pada navbar &rarr; Pilih "Performa Bisnis".
                            </p>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <h2 class="section-title">Pemetaan Pola Waktu Operasional</h2>
                    <p class="text-muted mb-4">
                        Data mentah dari laporan penjualan Anda kami olah menjadi wawasan <em>(insight)</em> yang mudah dibaca. Fitur ini secara otomatis memetakan tren pelanggan berdasarkan waktu:
                    </p>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <article class="feature-card">
                                <div class="feature-icon" aria-hidden="true"><i class="bi bi-clock-history"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-1">Jam Operasional</h3>
                                    <p class="text-muted small mb-0">Identifikasi titik waktu (jam) paling ramai pelanggan dan jam-jam santai <em>(low-hour)</em> di outlet Anda.</p>
                                </div>
                            </article>
                        </div>
                        <div class="col-md-6">
                            <article class="feature-card">
                                <div class="feature-icon" aria-hidden="true"><i class="bi bi-calendar2-day"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-1">Hari dalam Seminggu</h3>
                                    <p class="text-muted small mb-0">Lihat perbandingan hari apa yang memberikan omzet tertinggi dan hari apa yang paling sepi pengunjung.</p>
                                </div>
                            </article>
                        </div>
                        <div class="col-md-6">
                            <article class="feature-card">
                                <div class="feature-icon" aria-hidden="true"><i class="bi bi-calendar-event"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-1">Tren Tanggal</h3>
                                    <p class="text-muted small mb-0">Mengetahui apakah bisnis Anda lebih ramai di awal bulan, pertengahan, atau saat tanggal gajian.</p>
                                </div>
                            </article>
                        </div>
                        <div class="col-md-6">
                            <article class="feature-card">
                                <div class="feature-icon" aria-hidden="true"><i class="bi bi-calendar-month"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-1">Performa Bulanan</h3>
                                    <p class="text-muted small mb-0">Analisis makro untuk melihat bulan apa saja yang merupakan <em>peak season</em> bisnis Anda.</p>
                                </div>
                            </article>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <h2 class="section-title">Komparasi Data & Prediksi Masa Depan</h2>
                    <p class="text-muted mb-4">
                        Selain menganalisis data masa lalu, Flavory juga menyajikan proyeksi bisnis untuk membantu Anda bersiap menghadapi bulan berikutnya.
                    </p>

                    <div class="row g-4">
                        <div class="col-lg-4">
                            <article class="feature-card flex-column align-items-center text-center h-100">
                                <div class="feature-icon mb-2" aria-hidden="true"><i class="bi bi-bar-chart-steps"></i></div>
                                <h3 class="h6 fw-bold text-dark mb-2">Grafik Komparasi</h3>
                                <p class="text-muted small mb-0">Visualisasi data perbandingan langsung performa bisnis antara <strong>bulan ini</strong> dengan <strong>bulan sebelumnya</strong>.</p>
                            </article>
                        </div>
                        <div class="col-lg-4">
                            <article class="feature-card flex-column align-items-center text-center h-100 border-orange" style="background-color: var(--light-orange);">
                                <div class="feature-icon bg-white text-orange shadow-sm mb-2" aria-hidden="true"><i class="bi bi-magic"></i></div>
                                <h3 class="h6 fw-bold text-dark mb-2">Prediksi Omzet</h3>
                                <p class="text-muted small mb-0">Estimasi proyeksi pendapatan untuk bulan depan berdasarkan kalkulasi algoritma performa penjualan Anda saat ini.</p>
                            </article>
                        </div>
                        <div class="col-lg-4">
                            <article class="feature-card flex-column align-items-center text-center h-100">
                                <div class="feature-icon mb-2 icon-green" aria-hidden="true"><i class="bi bi-list-columns-reverse"></i></div>
                                <h3 class="h6 fw-bold text-dark mb-2">Detail Perubahan</h3>
                                <p class="text-muted small mb-0">Rincian parameter apa saja yang mengalami kenaikan atau penurunan untuk bahan evaluasi detail.</p>
                            </article>
                        </div>
                    </div>
                </section>

                <section class="mb-2">
                    <aside class="insight-alert">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="bg-white p-2 rounded-circle shadow-sm">
                                <i class="bi bi-lightbulb-fill fs-3" style="color: #f59e0b;" aria-hidden="true"></i>
                            </div>
                            <h2 class="h4 fw-bold text-dark mb-0">Strategi Menggunakan Data Performa</h2>
                        </div>
                        <p class="text-muted mb-3" style="font-size: 1.05rem;">
                            Data analitik tidak akan berguna jika tidak diubah menjadi tindakan. Gunakan *insight* dari halaman ini untuk merancang strategi jitu:
                        </p>
                        <ul class="insight-list list-unstyled ps-2 mb-0">
                            <li class="d-flex align-items-start gap-2">
                                <i class="bi bi-check-circle-fill mt-1" style="color: #f59e0b;"></i>
                                <span><strong>Optimalisasi Jam Operasional:</strong> Sesuaikan jumlah karyawan yang berjaga. Tambah staf saat jam ramai, dan kurangi saat jam sepi untuk efisiensi biaya.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="bi bi-check-circle-fill mt-1" style="color: #f59e0b;"></i>
                                <span><strong>Strategi Promo Tepat Sasaran:</strong> Buat promo khusus <em>"Happy Hour"</em> di jam atau hari sepi untuk memancing kedatangan pelanggan.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="bi bi-check-circle-fill mt-1" style="color: #f59e0b;"></i>
                                <span><strong>Manajemen Stok yang Cerdas:</strong> Pastikan bahan baku utama sudah disiapkan lebih banyak pada H-1 sebelum hari atau tanggal ramai tiba (seperti akhir pekan atau tanggal gajian).</span>
                            </li>
                        </ul>
                    </aside>
                </section>

            </div>
        </div>
    </div>
</main>
@endsection