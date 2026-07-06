@extends('layouts.app')

@section('title', 'Panduan Mengelola Catatan Struk Kasir | Flavory.id')

@push('styles')
<meta name="description" content="Panduan mudah cara menambahkan dan kelola catatan struk flavory.id. Tambahkan kode WiFi, ucapan terima kasih, atau info promo pada catatan struk kasir Anda.">
<meta name="keywords" content="catatan struk kasir, kelola catatan struk flavory, struk pos, setting struk kasir, aplikasi kasir flavory">
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
        max-width: 700px;
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

    /* Example Cards */
    .example-card {
        background: #ffffff;
        border: 1px dashed #cbd5e1;
        border-radius: 1rem;
        padding: 20px;
        text-align: center;
        height: 100%;
        transition: all 0.3s;
    }
    
    .example-card:hover {
        border-color: var(--primary-orange);
        background-color: var(--light-orange);
    }

    .example-icon {
        font-size: 2rem;
        color: var(--primary-orange);
        margin-bottom: 10px;
    }

    /* Important Rule Box */
    .rule-box {
        background-color: #ffffff;
        border-radius: 1.2rem;
        border-left: 6px solid #ef4444; /* Red accent for importance */
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
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
                        <i class="bi bi-receipt me-1" aria-hidden="true"></i> Pengaturan Struk
                    </span>
                    <h1>Panduan Menambahkan Catatan pada Struk</h1>
                    <p class="text-muted mt-3 fs-5">
                        Setelah mengatur form pesanan, Anda dapat menyisipkan pesan khusus pada bukti pembayaran pelanggan. Fitur ini bersifat <strong>opsional</strong> dan dapat disesuaikan dengan kebutuhan outlet Anda.
                    </p>
                </header>

                <div class="ratio ratio-16x9 mb-5 shadow-sm rounded-4 overflow-hidden" style="border: 1px solid rgba(0,0,0,0.05);">
                    <iframe src="https://www.youtube.com/embed/ooj9sj050KY" title="Video Panduan" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>

                <section class="mb-5">
                    <h2 class="section-title">Langkah-Langkah Menambahkan Catatan</h2>
                    
                    <div class="row g-4">
                        <div class="col-md-6 col-lg-3">
                            <article class="step-card text-center">
                                <div class="step-number mx-auto" aria-hidden="true">1</div>
                                <h3 class="h6 fw-bold text-dark">Buka Opsi Lainnya</h3>
                                <p class="text-muted mb-0 small">Klik tab <strong>Opsi Lainnya</strong> pada navbar kasir. (Buka menu <i class="bi bi-list" aria-hidden="true"></i> jika di HP).</p>
                            </article>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <article class="step-card text-center">
                                <div class="step-number mx-auto" aria-hidden="true">2</div>
                                <h3 class="h6 fw-bold text-dark">Pilih Submenu</h3>
                                <p class="text-muted mb-0 small">Pilih menu <strong>Kelola Catatan Struk</strong> dari daftar opsi yang muncul.</p>
                            </article>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <article class="step-card text-center">
                                <div class="step-number mx-auto" aria-hidden="true">3</div>
                                <h3 class="h6 fw-bold text-dark">Halaman Pengaturan</h3>
                                <p class="text-muted mb-0 small">Anda akan diarahkan ke halaman daftar pengaturan catatan struk outlet Anda.</p>
                            </article>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <article class="step-card text-center">
                                <div class="step-number mx-auto" aria-hidden="true">4</div>
                                <h3 class="h6 fw-bold text-dark">Tambah Catatan</h3>
                                <p class="text-muted mb-0 small">Klik tombol <strong>"+ Tambah Catatan"</strong> untuk mulai menulis pesan baru Anda.</p>
                            </article>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <div class="bg-white p-4 p-md-5 rounded-4 border shadow-sm">
                        <h2 class="section-title mb-4">Apa yang Bisa Ditulis?</h2>
                        <p class="text-muted mb-4">Anda dapat memasukkan teks apa pun yang relevan untuk pelanggan Anda. Berikut adalah beberapa contoh penggunaan catatan struk yang populer:</p>
                        
                        <div class="row g-3">
                            <div class="col-md-4">
                                <article class="example-card">
                                    <i class="bi bi-wifi example-icon" aria-hidden="true"></i>
                                    <h3 class="h6 fw-bold text-dark">Kode WiFi</h3>
                                    <p class="text-muted small mb-0">"Password WiFi: flavory2026"</p>
                                </article>
                            </div>
                            <div class="col-md-4">
                                <article class="example-card">
                                    <i class="bi bi-emoji-smile example-icon" aria-hidden="true"></i>
                                    <h3 class="h6 fw-bold text-dark">Ucapan Terima Kasih</h3>
                                    <p class="text-muted small mb-0">"Terima kasih telah berkunjung, semoga harimu menyenangkan!"</p>
                                </article>
                            </div>
                            <div class="col-md-4">
                                <article class="example-card">
                                    <i class="bi bi-megaphone example-icon" aria-hidden="true"></i>
                                    <h3 class="h6 fw-bold text-dark">Informasi Tambahan</h3>
                                    <p class="text-muted small mb-0">"Dapatkan diskon 10% di kunjungan berikutnya dengan membawa struk ini."</p>
                                </article>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <h2 class="section-title">Manajemen Catatan</h2>
                    
                    <div class="row g-4 align-items-center">
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex align-items-start mb-3">
                                    <i class="bi bi-check-circle-fill text-orange fs-5 me-3" style="color: var(--primary-orange);" aria-hidden="true"></i>
                                    <div>
                                        <span class="fw-bold text-dark d-block">Bisa Menyimpan Banyak Catatan</span>
                                        <span class="text-muted small">Anda bebas membuat lebih dari satu variasi catatan sebagai draf persiapan.</span>
                                    </div>
                                </li>
                                <li class="d-flex align-items-start">
                                    <i class="bi bi-check-circle-fill text-orange fs-5 me-3" style="color: var(--primary-orange);" aria-hidden="true"></i>
                                    <div>
                                        <span class="fw-bold text-dark d-block">Mudah Diedit</span>
                                        <span class="text-muted small">Catatan yang sudah dibuat dapat diubah kembali kapan saja jika terjadi perubahan informasi (misalnya password WiFi ganti).</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <aside class="rule-box p-4">
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <i class="bi bi-exclamation-triangle-fill text-danger fs-3" aria-hidden="true"></i>
                                    <h3 class="h5 fw-bold text-dark mb-0">Hanya 1 Catatan Aktif</h3>
                                </div>
                                <p class="text-muted mb-0 small">
                                    Meskipun Anda bisa membuat banyak catatan, <strong>hanya satu catatan yang dapat diaktifkan</strong> (berstatus "Aktif") pada satu waktu untuk dicetak pada struk pesanan. Gunakan fitur tombol sakelar (toggle) untuk memilih mana yang ingin digunakan.
                                </p>
                            </aside>
                        </div>
                    </div>
                </section>

                <aside class="card border-0 rounded-4" style="background: linear-gradient(135deg, var(--primary-orange) 0%, #e65f00 100%); color: white;">
                    <div class="card-body p-4 p-md-5 text-center">
                        <i class="bi bi-star-fill text-warning fs-1 mb-3" aria-hidden="true"></i>
                        <h3 class="fw-bold mb-3">Tingkatkan Kesan Profesional</h3>
                        <p class="mb-0 fs-6 opacity-75 mx-auto" style="max-width: 600px;">
                            Gunakan fitur catatan struk ini secara maksimal untuk memberikan informasi penting atau sentuhan personal kepada pelanggan Anda. Struk yang informatif akan meningkatkan kenyamanan dan loyalitas pelanggan outlet Anda.
                        </p>
                    </div>
                </aside>

            </div>
        </div>
    </div>
</main>
@endsection