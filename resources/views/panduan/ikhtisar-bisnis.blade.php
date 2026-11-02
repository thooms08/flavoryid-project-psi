{{-- resources/views/panduan/ikhtisar-bisnis.blade.php --}}
@extends('layouts.app')

@section('title', 'Panduan Ikhtisar Bisnis Kasir | Flavory.id')

@push('styles')
<meta name="description" content="Panduan lengkap membaca dan memahami Ikhtisar Bisnis kasir di Flavory.id. Pantau ringkasan omzet, profit, dan kinerja operasional outlet Anda secara akurat.">
<meta name="keywords" content="ikhtisar bisnis kasir, laporan keuangan flavory, laporan operasional kasir, panduan kasir flavory, rekap penjualan kasir">
<meta name="author" content="Flavory.id">

<style>
    :root {
        --primary-orange: #ff6a00;
        --dark-orange: #e65f00;
        --light-orange: #fff4e6;
        --text-dark: #1e293b;
        --text-muted: #64748b;
    }

    .guide-page {
        background-color: #f8fafc;
        min-height: 100vh;
        padding: 120px 0 80px;
    }

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

    .info-alert {
        background-color: white;
        border-left: 5px solid var(--primary-orange);
        border-radius: 0.8rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
        padding: 20px;
    }

    .shortcut-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 18px 20px;
        display: flex;
        align-items: flex-start;
        gap: 14px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .shortcut-card:hover {
        border-color: var(--primary-orange);
        box-shadow: 0 10px 20px rgba(255, 106, 0, 0.07);
        transform: translateY(-3px);
    }
    .shortcut-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .text-orange { color: var(--primary-orange) !important; }

    @media (max-width: 768px) {
        .guide-page { padding: 100px 0 40px; }
        .guide-header { text-align: center; }
        .guide-header p { margin: 0 auto; }
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
                        <i class="bi bi-graph-up-arrow me-1" aria-hidden="true"></i> Analisis &amp; Laporan
                    </span>
                    <h1>Panduan Fitur Ikhtisar Bisnis di Flavory.id</h1>
                    <p class="text-muted mt-3 fs-5">
                        Pantau ringkasan kinerja harian outlet Anda secara menyeluruh. Ikhtisar Bisnis menampilkan
                        data omzet, profit, kerugian, dan grafik operasional dalam satu halaman terpadu.
                    </p>
                </header>

                <div class="ratio ratio-16x9 mb-5 shadow-sm rounded-4 overflow-hidden" style="border: 1px solid rgba(0,0,0,0.05);">
                    <iframe src="https://www.youtube.com/embed/R-LO_-zaXyU" title="Video Panduan Ikhtisar Bisnis" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>

                <section class="mb-5">
                    <div class="info-alert d-flex flex-column flex-md-row align-items-md-center gap-4">
                        <div class="bg-light p-3 rounded-3 border text-center" style="min-width: 120px;">
                            <i class="bi bi-map-fill fs-1 text-orange" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h2 class="h5 fw-bold text-dark mb-2">Cara Mengakses Ikhtisar Bisnis</h2>
                            <p class="text-muted mb-2">
                                Anda dapat membuka halaman ringkasan keuangan ini langsung dari navigasi utama kasir.
                            </p>
                            <p class="mb-0 fw-bold text-orange">
                                <i class="bi bi-box-arrow-right me-1" aria-hidden="true"></i>
                                Langkah: Klik tab "Opsi Lainnya" pada navbar, lalu pilih "Ikhtisar Bisnis".
                            </p>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <h2 class="section-title">Fitur Laporan Operasional</h2>
                    <p class="text-muted mb-4">
                        Menampilkan gambaran kinerja bisnis secara menyeluruh. Secara default, halaman ini menampilkan
                        data <strong>1 hari terakhir</strong>, namun Anda dapat mengatur rentang tanggal sesuai
                        kebutuhan audit.
                    </p>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <article class="feature-card">
                                <div class="feature-icon" aria-hidden="true"><i class="bi bi-bar-chart-line-fill"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-1">Kinerja &amp; Grafik</h3>
                                    <p class="text-muted small mb-0">
                                        Pantau Total Keuntungan (Profit), Kerugian, Pengeluaran, Omzet, Modal,
                                        serta grafik visual operasional outlet.
                                    </p>
                                </div>
                            </article>
                        </div>
                        <div class="col-md-6">
                            <article class="feature-card">
                                <div class="feature-icon" aria-hidden="true"><i class="bi bi-star-fill"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-1">Performa Menu</h3>
                                    <p class="text-muted small mb-0">
                                        Lihat 10 menu paling laris dan 5 menu tidak laris.
                                        <em>Menu yang tidak muncul berarti berada di rata-rata penjualan.</em>
                                    </p>
                                </div>
                            </article>
                        </div>
                        <div class="col-md-6">
                            <article class="feature-card">
                                <div class="feature-icon" aria-hidden="true"><i class="bi bi-receipt-cutoff"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-1">Tabel Penjualan &amp; Detail</h3>
                                    <p class="text-muted small mb-0">
                                        Tabel lengkap dari seluruh laporan penjualan beserta rincian setiap
                                        transaksi yang terjadi dalam periode yang dipilih.
                                    </p>
                                </div>
                            </article>
                        </div>
                        <div class="col-md-6">
                            <article class="feature-card">
                                <div class="feature-icon" aria-hidden="true"><i class="bi bi-file-earmark-excel-fill"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-1">Export ke Excel</h3>
                                    <p class="text-muted small mb-0">
                                        Unduh langsung data operasional ke format Excel untuk kebutuhan
                                        pembukuan eksternal atau pelaporan ke owner.
                                    </p>
                                </div>
                            </article>
                        </div>
                    </div>
                </section>

                <section class="mb-2">
                    <h2 class="section-title">Laporan Terkait Lainnya</h2>
                    <p class="text-muted mb-4">
                        Mulai versi 3.0, Laporan Pengeluaran dan Modal Usaha kini tersedia sebagai menu mandiri
                        di navbar kasir. Akses panduannya di sini:
                    </p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('panduan.laporan-pengeluaran') }}" class="shortcut-card">
                                <div class="shortcut-icon bg-warning bg-opacity-10" style="color:#d97706;">
                                    <i class="bi bi-receipt-cutoff"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark mb-1" style="font-size:.9rem;">Panduan Laporan Pengeluaran</div>
                                    <p class="text-muted small mb-0">Cara mencatat arus kas keluar dan export data pengeluaran.</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('panduan.modal-usaha') }}" class="shortcut-card">
                                <div class="shortcut-icon bg-success bg-opacity-10" style="color:#16a34a;">
                                    <i class="bi bi-wallet2"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark mb-1" style="font-size:.9rem;">Panduan Modal Usaha</div>
                                    <p class="text-muted small mb-0">Cara mencatat suntikan modal agar perhitungan profit akurat.</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
</main>
@endsection
