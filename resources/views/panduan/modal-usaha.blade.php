{{-- resources/views/panduan/modal-usaha.blade.php --}}
@extends('layouts.app')

@section('title', 'Panduan Modal Usaha Kasir | Flavory.id')

@push('styles')
<meta name="description" content="Panduan lengkap mencatat dan mengelola modal usaha di Flavory.id. Pastikan suntikan modal tercatat dengan benar agar perhitungan profit outlet Anda selalu akurat.">
<meta name="keywords" content="modal usaha kasir, suntikan modal flavory, laporan modal usaha, panduan kasir flavory, pencatatan modal bisnis">
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

    .important-alert {
        background-color: #fef2f2;
        border-left: 5px solid #ef4444;
        border-radius: 1rem;
        padding: 25px;
        box-shadow: 0 10px 25px rgba(239, 68, 68, 0.05);
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
                        <i class="bi bi-wallet2 me-1" aria-hidden="true"></i> Keuangan &amp; Modal
                    </span>
                    <h1>Panduan Fitur Modal Usaha di Flavory.id</h1>
                    <p class="text-muted mt-3 fs-5">
                        Catat riwayat suntikan modal usaha agar perhitungan profit berjalan akurat.
                        Setiap modal yang tercatat akan langsung mempengaruhi kalkulasi keuntungan bersih
                        di halaman Ikhtisar Bisnis.
                    </p>
                </header>

                <section class="mb-5">
                    <div class="info-alert d-flex flex-column flex-md-row align-items-md-center gap-4">
                        <div class="bg-light p-3 rounded-3 border text-center" style="min-width: 120px;">
                            <i class="bi bi-wallet2 fs-1 text-orange" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h2 class="h5 fw-bold text-dark mb-2">Cara Mengakses Modal Usaha</h2>
                            <p class="text-muted mb-2">
                                Mulai versi 3.0, Modal Usaha tersedia sebagai menu mandiri di navbar kasir,
                                terpisah dari halaman Ikhtisar Bisnis untuk akses yang lebih cepat.
                            </p>
                            <p class="mb-0 fw-bold text-orange">
                                <i class="bi bi-box-arrow-right me-1" aria-hidden="true"></i>
                                Langkah: Klik tab "Opsi Lainnya" pada navbar, lalu pilih "Modal Usaha".
                            </p>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <h2 class="section-title">Cara Mencatat Modal Usaha</h2>
                    <p class="text-muted mb-4">
                        Setiap kali ada penambahan modal -- baik modal awal maupun suntikan dana tambahan --
                        segera catat melalui langkah berikut:
                    </p>

                    <div class="row g-4">
                        <div class="col-md-4">
                            <article class="step-card">
                                <div class="step-number" aria-hidden="true"><i class="bi bi-cash-coin"></i></div>
                                <h3 class="h6 fw-bold text-dark mb-2">Input Nominal</h3>
                                <p class="text-muted small mb-0">
                                    Masukkan angka nominal dari modal awal atau dana tambahan yang
                                    disiapkan untuk operasional outlet.
                                </p>
                            </article>
                        </div>
                        <div class="col-md-4">
                            <article class="step-card">
                                <div class="step-number" aria-hidden="true"><i class="bi bi-pencil-square"></i></div>
                                <h3 class="h6 fw-bold text-dark mb-2">Tambahkan Keterangan</h3>
                                <p class="text-muted small mb-0">
                                    Tulis keterangan yang jelas mengenai sumber modal tersebut,
                                    misalnya: "Modal awal bulan Agustus" atau "Tambahan modal dari owner".
                                </p>
                            </article>
                        </div>
                        <div class="col-md-4">
                            <article class="step-card">
                                <div class="step-number" aria-hidden="true"><i class="bi bi-card-list"></i></div>
                                <h3 class="h6 fw-bold text-dark mb-2">Pantau Daftar Data</h3>
                                <p class="text-muted small mb-0">
                                    Setelah disimpan, data muncul di tabel riwayat modal. Pantau seluruh
                                    riwayat suntikan modal yang telah berhasil dicatat.
                                </p>
                            </article>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <h2 class="section-title">Fitur yang Tersedia</h2>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <article class="feature-card">
                                <div class="feature-icon" aria-hidden="true"><i class="bi bi-clock-history"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-1">Riwayat Modal Lengkap</h3>
                                    <p class="text-muted small mb-0">
                                        Semua entri modal tersimpan secara kronologis dengan tanggal, nominal,
                                        dan keterangan yang tercatat permanen di sistem.
                                    </p>
                                </div>
                            </article>
                        </div>
                        <div class="col-md-6">
                            <article class="feature-card">
                                <div class="feature-icon" aria-hidden="true"><i class="bi bi-graph-up-arrow"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-1">Integrasi Kalkulasi Profit</h3>
                                    <p class="text-muted small mb-0">
                                        Setiap modal yang dicatat langsung diperhitungkan dalam kalkulasi
                                        profit di halaman Ikhtisar Bisnis secara otomatis.
                                    </p>
                                </div>
                            </article>
                        </div>
                    </div>
                </section>

                <aside class="important-alert mt-2">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <i class="bi bi-shield-exclamation text-danger fs-2" aria-hidden="true"></i>
                        <h3 class="h5 fw-bold text-dark mb-0">Catatan Penting Data Modal</h3>
                    </div>
                    <p class="text-muted mb-0" style="font-size: 1.05rem; line-height: 1.7;">
                        Untuk menjaga integritas laporan, data modal yang telah tersimpan
                        <strong>tidak dapat diedit</strong>. Jika terdapat kesalahan input, data tersebut
                        <strong>hanya dapat dihapus</strong> kemudian diinput ulang. Aturan ini bertujuan
                        untuk mencegah manipulasi data finansial yang sensitif pada sistem Anda.
                    </p>
                </aside>

            </div>
        </div>
    </div>
</main>
@endsection
