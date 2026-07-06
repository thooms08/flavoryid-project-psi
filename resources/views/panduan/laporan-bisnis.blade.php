{{-- resources/views/panduan/laporan-bisnis.blade.php --}}
@extends('layouts.app')

@section('title', 'Panduan Laporan Bisnis Kasir | Flavory.id')

@push('styles')
<meta name="description" content="Panduan lengkap membaca dan mengelola laporan bisnis kasir di Flavory.id. Pantau laporan operasional kasir, pengeluaran, hingga laporan keuangan Flavory dengan akurat.">
<meta name="keywords" content="laporan bisnis kasir, laporan keuangan flavory, laporan operasional kasir, panduan kasir flavory, rekap penjualan kasir">
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

    .text-orange { color: var(--primary-orange) !important; }

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
                        <i class="bi bi-graph-up-arrow me-1" aria-hidden="true"></i> Analisis & Laporan
                    </span>
                    <h1>Panduan Fitur Laporan Bisnis di Flavory.id</h1>
                    <p class="text-muted mt-3 fs-5">
                        Pantau terus kesehatan finansial usahamu. Sistem ini menyediakan 3 jenis laporan utama: Laporan Operasional, Pengeluaran, dan Modal Usaha.
                    </p>
                </header>

                <div class="ratio ratio-16x9 mb-5 shadow-sm rounded-4 overflow-hidden" style="border: 1px solid rgba(0,0,0,0.05);">
                    <iframe src="https://www.youtube.com/embed/R-LO_-zaXyU" title="Video Panduan" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>

                <section class="mb-5">
                    <div class="info-alert d-flex flex-column flex-md-row align-items-md-center gap-4">
                        <div class="bg-light p-3 rounded-3 border text-center" style="min-width: 120px;">
                            <i class="bi bi-map-fill fs-1 text-orange" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h2 class="h5 fw-bold text-dark mb-2">Cara Mengakses Fitur Laporan</h2>
                            <p class="text-muted mb-2">
                                Anda dapat melihat seluruh rekapitulasi data penjualan dan keuangan secara terpusat melalui navigasi utama kasir.
                            </p>
                            <p class="mb-0 fw-bold text-orange">
                                <i class="bi bi-box-arrow-right me-1" aria-hidden="true"></i> Langkah: Klik tab "Opsi Lainnya" pada navbar &rarr; Pilih "Laporan Bisnis".
                            </p>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <h2 class="section-title">1. Laporan Operasional</h2>
                    <p class="text-muted mb-4">
                        Menampilkan gambaran kinerja bisnis secara menyeluruh. Secara <em>default</em>, laporan ini menampilkan data <strong>1 hari terakhir</strong>, namun Anda dapat mengatur rentang tanggal sesuai kebutuhan audit.
                    </p>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <article class="feature-card">
                                <div class="feature-icon" aria-hidden="true"><i class="bi bi-bar-chart-line-fill"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-1">Kinerja & Grafik</h3>
                                    <p class="text-muted small mb-0">Pantau Total Keuntungan (Profit), Kerugian, Pengeluaran, Omzet, Modal, serta grafik visual operasional.</p>
                                </div>
                            </article>
                        </div>
                        <div class="col-md-6">
                            <article class="feature-card">
                                <div class="feature-icon" aria-hidden="true"><i class="bi bi-star-fill"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-1">Performa Menu</h3>
                                    <p class="text-muted small mb-0">Lihat 10 menu paling laris & 5 menu tidak laris. <em>*Menu yang tidak muncul berarti di rata-rata penjualan.</em></p>
                                </div>
                            </article>
                        </div>
                        <div class="col-md-6">
                            <article class="feature-card">
                                <div class="feature-icon" aria-hidden="true"><i class="bi bi-receipt-cutoff"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-1">Tabel Penjualan & Detail</h3>
                                    <p class="text-muted small mb-0">Tabel lengkap dari seluruh laporan penjualan beserta rincian setiap transaksi yang terjadi.</p>
                                </div>
                            </article>
                        </div>
                        <div class="col-md-6">
                            <article class="feature-card">
                                <div class="feature-icon" aria-hidden="true"><i class="bi bi-file-earmark-excel-fill"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-1">Export ke Excel</h3>
                                    <p class="text-muted small mb-0">Unduh langsung data operasional Anda ke format Excel untuk kebutuhan pembukuan eksternal.</p>
                                </div>
                            </article>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <h2 class="section-title">2. Laporan Pengeluaran</h2>
                    <p class="text-muted mb-4">
                        Halaman ini digunakan khusus untuk mencatat dan memantau seluruh arus kas keluar bisnis Anda. <br>
                        <span class="fw-bold text-orange"><i class="bi bi-arrow-return-right me-1"></i> Cara Akses:</span> Klik tombol "Pengeluaran" di dalam halaman Laporan Bisnis.
                    </p>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <article class="feature-card">
                                <div class="feature-icon" aria-hidden="true"><i class="bi bi-ui-checks"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-2">Form Input Lengkap</h3>
                                    <ul class="text-muted small mb-0 ps-3" style="line-height: 1.6;">
                                        <li><strong>Nama Barang</strong></li>
                                        <li><strong>Harga & Qty</strong> (jumlah)</li>
                                        <li><strong>Total</strong> (otomatis, bisa diedit manual)</li>
                                        <li><strong>Keterangan & Bukti Foto</strong></li>
                                    </ul>
                                </div>
                            </article>
                        </div>
                        <div class="col-md-6">
                            <article class="feature-card">
                                <div class="feature-icon" aria-hidden="true"><i class="bi bi-table"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-1">Tabel Rekap & Export</h3>
                                    <p class="text-muted small mb-0">Semua data yang tersimpan akan masuk ke Tabel Laporan Pengeluaran lengkap yang juga dapat di-Export ke Excel.</p>
                                </div>
                            </article>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <h2 class="section-title">3. Laporan Modal Usaha</h2>
                    <p class="text-muted mb-4">
                        Digunakan untuk mencatat riwayat suntikan modal usaha Anda agar perhitungan profit menjadi akurat.<br>
                        <span class="fw-bold text-orange"><i class="bi bi-arrow-return-right me-1"></i> Cara Akses:</span> Klik tombol "Modal Usaha" yang berada di samping tombol Pengeluaran.
                    </p>

                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <article class="step-card">
                                <div class="step-number" aria-hidden="true"><i class="bi bi-cash-coin"></i></div>
                                <h3 class="h6 fw-bold text-dark mb-2">Input Nominal</h3>
                                <p class="text-muted small mb-0">Masukkan angka nominal dari modal awal atau dana tambahan.</p>
                            </article>
                        </div>
                        <div class="col-md-4">
                            <article class="step-card">
                                <div class="step-number" aria-hidden="true"><i class="bi bi-pencil-square"></i></div>
                                <h3 class="h6 fw-bold text-dark mb-2">Keterangan</h3>
                                <p class="text-muted small mb-0">Tambahkan catatan jelas mengenai sumber modal tersebut.</p>
                            </article>
                        </div>
                        <div class="col-md-4">
                            <article class="step-card">
                                <div class="step-number" aria-hidden="true"><i class="bi bi-card-list"></i></div>
                                <h3 class="h6 fw-bold text-dark mb-2">Daftar Data</h3>
                                <p class="text-muted small mb-0">Pantau seluruh riwayat data modal yang telah berhasil dimasukkan.</p>
                            </article>
                        </div>
                    </div>

                    <aside class="important-alert mt-5">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <i class="bi bi-shield-exclamation text-danger fs-2" aria-hidden="true"></i>
                            <h3 class="h5 fw-bold text-dark mb-0">Catatan Penting Data Modal</h3>
                        </div>
                        <p class="text-muted mb-0" style="font-size: 1.05rem; line-height: 1.7;">
                            Untuk menjaga integritas laporan, data modal yang telah tersimpan <strong>tidak dapat diedit</strong>. Jika terdapat kesalahan input, data tersebut <strong>hanya dapat dihapus</strong> kemudian diinput ulang. Aturan ketat ini bertujuan untuk mencegah manipulasi data finansial yang sensitif pada sistem Anda.
                        </p>
                    </aside>
                </section>

            </div>
        </div>
    </div>
</main>
@endsection