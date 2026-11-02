{{-- resources/views/panduan/laporan-pengeluaran.blade.php --}}
@extends('layouts.app')

@section('title', 'Panduan Laporan Pengeluaran Kasir | Flavory.id')

@push('styles')
<meta name="description" content="Panduan lengkap mencatat dan memantau laporan pengeluaran kasir di Flavory.id. Kelola arus kas keluar outlet Anda dengan akurat dan export data ke Excel.">
<meta name="keywords" content="laporan pengeluaran kasir, arus kas keluar flavory, pencatatan pengeluaran usaha, panduan kasir flavory, export pengeluaran excel">
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

    .info-alert {
        background-color: white;
        border-left: 5px solid var(--primary-orange);
        border-radius: 0.8rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
        padding: 20px;
    }

    .tips-alert {
        background-color: #fffbeb;
        border: 1px solid #fde68a;
        border-left: 5px solid #f59e0b;
        border-radius: 1rem;
        padding: 25px;
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
                        <i class="bi bi-receipt-cutoff me-1" aria-hidden="true"></i> Keuangan &amp; Pengeluaran
                    </span>
                    <h1>Panduan Fitur Laporan Pengeluaran di Flavory.id</h1>
                    <p class="text-muted mt-3 fs-5">
                        Catat dan pantau seluruh arus kas keluar bisnis Anda. Dengan pencatatan pengeluaran
                        yang rapi, laporan profit outlet menjadi lebih akurat dan mudah diaudit.
                    </p>
                </header>

                <section class="mb-5">
                    <div class="info-alert d-flex flex-column flex-md-row align-items-md-center gap-4">
                        <div class="bg-light p-3 rounded-3 border text-center" style="min-width: 120px;">
                            <i class="bi bi-receipt-cutoff fs-1 text-orange" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h2 class="h5 fw-bold text-dark mb-2">Cara Mengakses Laporan Pengeluaran</h2>
                            <p class="text-muted mb-2">
                                Mulai versi 3.0, Laporan Pengeluaran tersedia sebagai menu mandiri di navbar kasir,
                                sehingga lebih mudah diakses tanpa harus masuk ke halaman Ikhtisar Bisnis terlebih dahulu.
                            </p>
                            <p class="mb-0 fw-bold text-orange">
                                <i class="bi bi-box-arrow-right me-1" aria-hidden="true"></i>
                                Langkah: Klik tab "Opsi Lainnya" pada navbar, lalu pilih "Laporan Pengeluaran".
                            </p>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <h2 class="section-title">Fitur yang Tersedia</h2>
                    <p class="text-muted mb-4">
                        Halaman Laporan Pengeluaran menyediakan semua yang Anda butuhkan untuk mencatat
                        dan memantau arus kas keluar bisnis secara lengkap.
                    </p>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <article class="feature-card">
                                <div class="feature-icon" aria-hidden="true"><i class="bi bi-ui-checks"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-2">Form Input Pengeluaran</h3>
                                    <ul class="text-muted small mb-0 ps-3" style="line-height: 1.7;">
                                        <li><strong>Nama Barang</strong> -- keterangan item pengeluaran</li>
                                        <li><strong>Harga &amp; Qty</strong> -- harga satuan dan jumlah</li>
                                        <li><strong>Total</strong> -- dihitung otomatis, dapat diedit manual</li>
                                        <li><strong>Keterangan</strong> -- catatan tambahan pengeluaran</li>
                                        <li><strong>Bukti Foto</strong> -- lampiran struk atau nota</li>
                                    </ul>
                                </div>
                            </article>
                        </div>
                        <div class="col-md-6">
                            <article class="feature-card">
                                <div class="feature-icon" aria-hidden="true"><i class="bi bi-table"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-1">Tabel Rekap Pengeluaran</h3>
                                    <p class="text-muted small mb-0">
                                        Semua data pengeluaran yang tersimpan ditampilkan dalam tabel rekap
                                        lengkap, dapat difilter berdasarkan rentang tanggal tertentu.
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
                                        Unduh seluruh data pengeluaran ke format Excel untuk keperluan
                                        pembukuan eksternal, pelaporan ke owner, atau audit keuangan.
                                    </p>
                                </div>
                            </article>
                        </div>
                        <div class="col-md-6">
                            <article class="feature-card">
                                <div class="feature-icon" aria-hidden="true"><i class="bi bi-image-fill"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-1">Bukti Foto Pengeluaran</h3>
                                    <p class="text-muted small mb-0">
                                        Setiap entri pengeluaran dapat dilampiri foto struk atau nota sebagai
                                        bukti fisik yang tersimpan di sistem secara permanen.
                                    </p>
                                </div>
                            </article>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <h2 class="section-title">Cara Mencatat Pengeluaran</h2>
                    <p class="text-muted mb-4">
                        Ikuti langkah berikut setiap kali ada pengeluaran operasional yang perlu dicatat:
                    </p>

                    <div class="row g-4">
                        <div class="col-md-4">
                            <article class="step-card">
                                <div class="step-number" aria-hidden="true">1</div>
                                <h3 class="h6 fw-bold text-dark mb-2">Buka Form Input</h3>
                                <p class="text-muted small mb-0">
                                    Akses halaman Laporan Pengeluaran dari navbar, lalu klik tombol
                                    <strong>"Tambah Pengeluaran"</strong> untuk membuka form input.
                                </p>
                            </article>
                        </div>
                        <div class="col-md-4">
                            <article class="step-card">
                                <div class="step-number" aria-hidden="true">2</div>
                                <h3 class="h6 fw-bold text-dark mb-2">Isi Data Lengkap</h3>
                                <p class="text-muted small mb-0">
                                    Masukkan nama barang, harga, jumlah (qty), keterangan, dan lampirkan
                                    foto bukti pengeluaran jika tersedia.
                                </p>
                            </article>
                        </div>
                        <div class="col-md-4">
                            <article class="step-card">
                                <div class="step-number" aria-hidden="true">3</div>
                                <h3 class="h6 fw-bold text-dark mb-2">Simpan &amp; Verifikasi</h3>
                                <p class="text-muted small mb-0">
                                    Klik <strong>"Simpan"</strong>. Data akan masuk ke tabel rekap dan
                                    otomatis mempengaruhi kalkulasi profit di halaman Ikhtisar Bisnis.
                                </p>
                            </article>
                        </div>
                    </div>
                </section>

                <aside class="tips-alert">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-white p-2 rounded-circle shadow-sm">
                            <i class="bi bi-lightbulb-fill fs-3" style="color:#f59e0b;" aria-hidden="true"></i>
                        </div>
                        <h2 class="h4 fw-bold text-dark mb-0">Tips Pencatatan yang Baik</h2>
                    </div>
                    <ul class="list-unstyled ps-2 mb-0">
                        <li class="d-flex align-items-start gap-2 mb-2">
                            <i class="bi bi-check-circle-fill mt-1" style="color:#f59e0b;"></i>
                            <span class="text-dark"><strong>Catat sesegera mungkin</strong> -- jangan tunda pencatatan pengeluaran agar tidak ada yang terlewat saat rekap harian.</span>
                        </li>
                        <li class="d-flex align-items-start gap-2 mb-2">
                            <i class="bi bi-check-circle-fill mt-1" style="color:#f59e0b;"></i>
                            <span class="text-dark"><strong>Selalu lampirkan foto bukti</strong> -- struk atau nota foto mempermudah verifikasi saat audit oleh owner atau akuntan.</span>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <i class="bi bi-check-circle-fill mt-1" style="color:#f59e0b;"></i>
                            <span class="text-dark"><strong>Gunakan kolom keterangan</strong> -- tulis keterangan yang jelas (contoh: "Beli bahan baku sayur", "Bayar listrik") agar mudah dipahami saat dilihat kembali.</span>
                        </li>
                    </ul>
                </aside>

            </div>
        </div>
    </div>
</main>
@endsection
