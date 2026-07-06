@extends('layouts.app')

@section('title', 'Pengaturan Form Kasir & Data Pesanan | Panduan Flavory.id')

@push('styles')
<meta name="description" content="Panduan mudah pengaturan form kasir di Flavory.id. Atur dan pilih form data pesanan Flavory seperti nama pelanggan, nomor meja, hingga diskon sesuai kebutuhan outlet Anda.">
<meta name="keywords" content="pengaturan form kasir, form data pesanan flavory, seting kasir POS, panduan kasir flavory, kelola pesanan">
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

    /* Form Item Cards */
    .form-item-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: all 0.2s ease;
    }
    
    .form-item-card:hover {
        border-color: var(--primary-orange);
        background-color: var(--light-orange);
    }

    .form-icon {
        font-size: 1.5rem;
        color: var(--primary-orange);
    }

    /* Toggle Illustration */
    .toggle-illustration {
        background-color: #f1f5f9;
        border-radius: 1rem;
        padding: 20px;
        text-align: center;
        border: 1px dashed #cbd5e1;
    }

    .toggle-btn {
        font-size: 2.5rem;
        color: var(--primary-orange);
        display: inline-block;
        animation: pulseToggle 2s infinite;
    }

    @keyframes pulseToggle {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
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
                        <i class="bi bi-gear-fill me-1" aria-hidden="true"></i> Pusat Bantuan Flavory.id
                    </span>
                    <h1>Panduan Mengatur Form Data Pesanan</h1>
                    <p class="text-muted mt-3 fs-5" style="max-width: 700px;">
                        Setelah menu berhasil ditambahkan, langkah selanjutnya adalah mengatur form data pesanan sesuai kebutuhan outlet Anda.
                    </p>
                </header>

                <div class="ratio ratio-16x9 mb-5 shadow-sm rounded-4 overflow-hidden" style="border: 1px solid rgba(0,0,0,0.05);">
                    <iframe src="https://www.youtube.com/embed/rpwyj7XloZ4" title="Video Panduan" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>

                <section class="mb-5">
                    <h2 class="section-title">Cara Akses Pengaturan Form Kasir</h2>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <article class="step-card">
                                <div class="step-number" aria-hidden="true">1</div>
                                <h3 class="h5 fw-bold text-dark">Buka Opsi Lainnya</h3>
                                <p class="text-muted mb-0 small">Klik tab <strong>Opsi Lainnya</strong> pada navbar kasir Anda. <br><em>(Bagi pengguna HP, buka menu hamburger <i class="bi bi-list" aria-hidden="true"></i> terlebih dahulu).</em></p>
                            </article>
                        </div>
                        <div class="col-md-4">
                            <article class="step-card">
                                <div class="step-number" aria-hidden="true">2</div>
                                <h3 class="h5 fw-bold text-dark">Pilih Submenu</h3>
                                <p class="text-muted mb-0 small">Temukan dan pilih submenu <strong>Pengaturan Form</strong> pada daftar menu yang tersedia.</p>
                            </article>
                        </div>
                        <div class="col-md-4">
                            <article class="step-card">
                                <div class="step-number" aria-hidden="true">3</div>
                                <h3 class="h5 fw-bold text-dark">Mulai Mengatur</h3>
                                <p class="text-muted mb-0 small">Anda akan langsung diarahkan ke halaman pengaturan form data pesanan flavory.</p>
                            </article>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <h2 class="section-title">Pilihan Form Data Pesanan Flavory</h2>
                    <p class="text-muted mb-4 fs-6">Di halaman ini, Anda dapat menentukan secara spesifik data apa saja yang ingin ditampilkan saat kasir membuat pesanan baru. Berikut adalah opsi form yang tersedia:</p>
                    
                    <div class="row g-3">
                        <div class="col-md-6 col-lg-4">
                            <article class="form-item-card shadow-sm">
                                <i class="bi bi-person-vcard form-icon" aria-hidden="true"></i>
                                <h3 class="h6 fw-bold text-dark mb-0">Nama Pelanggan</h3>
                            </article>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <article class="form-item-card shadow-sm">
                                <i class="bi bi-signpost-split form-icon" aria-hidden="true"></i>
                                <h3 class="h6 fw-bold text-dark mb-0">Nomor Meja</h3>
                            </article>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <article class="form-item-card shadow-sm">
                                <i class="bi bi-whatsapp form-icon" aria-hidden="true"></i>
                                <h3 class="h6 fw-bold text-dark mb-0">No. WhatsApp</h3>
                            </article>
                        </div>
                        <div class="col-md-6">
                            <article class="form-item-card shadow-sm">
                                <i class="bi bi-chat-left-text form-icon" aria-hidden="true"></i>
                                <h3 class="h6 fw-bold text-dark mb-0">Keterangan / Catatan Pelanggan</h3>
                            </article>
                        </div>
                        <div class="col-md-6">
                            <article class="form-item-card shadow-sm">
                                <i class="bi bi-tags form-icon" aria-hidden="true"></i>
                                <h3 class="h6 fw-bold text-dark mb-0">Diskon Transaksi</h3>
                            </article>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                        <div class="row g-0">
                            <div class="col-md-7 p-4 p-lg-5 d-flex flex-column justify-content-center">
                                <h2 class="h4 fw-bold text-dark mb-3">Cara Mengaktifkan Form</h2>
                                <p class="text-muted mb-0">
                                    Pengaturannya sangat mudah. Untuk mengaktifkan atau menonaktifkan form yang ada di atas, Anda cukup klik tombol <strong>toggle (sakelar)</strong> pada masing-masing opsi. Jika berwarna oranye, berarti form tersebut aktif dan akan muncul di halaman kasir.
                                </p>
                            </div>
                            <div class="col-md-5 bg-light p-4 d-flex align-items-center justify-content-center">
                                <div class="toggle-illustration w-100">
                                    <span class="fw-bold text-secondary d-block mb-2">Contoh Toggle Aktif:</span>
                                    <i class="bi bi-toggle-on toggle-btn" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <aside class="card border-0 rounded-4" style="background: linear-gradient(135deg, #fff4e6 0%, #ffe8cc 100%); border-left: 6px solid var(--primary-orange) !important;">
                    <div class="card-body p-4 p-md-5 d-flex align-items-center gap-4">
                        <div class="d-none d-md-block">
                            <i class="bi bi-lightbulb-fill" style="font-size: 3.5rem; color: var(--primary-orange);" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h3 class="h5 fw-bold mb-2" style="color: var(--dark-orange);">Catatan Penting:</h3>
                            <p class="mb-0 text-dark opacity-75 fs-6">
                                <strong>Aktifkan hanya form yang benar-benar dibutuhkan.</strong> Menampilkan form yang terlalu banyak (namun jarang digunakan) dapat memperlambat kasir. Form yang efektif akan membuat proses input pesanan menjadi jauh lebih cepat dan efisien.
                            </p>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </div>
</main>
@endsection