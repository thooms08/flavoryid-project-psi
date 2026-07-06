@extends('layouts.app')

@section('title', 'Cara Menambah Menu Kasir | Panduan Flavory.id')

@push('styles')
<meta name="description" content="Panduan lengkap cara menambah menu kasir dan input menu di Flavory.id. Pelajari langkah-langkah kelola menu, varian, stok, hingga foto untuk kelancaran operasional.">
<meta name="keywords" content="cara menambah menu kasir, input menu flavory, kelola menu POS, panduan kasir flavory, aplikasi kasir">
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

    /* Step Card Styling */
    .step-card {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.03);
        border-radius: 1.2rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.02);
        height: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }

    .step-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(255, 106, 0, 0.08);
        border-color: rgba(255, 106, 0, 0.2);
    }

    .step-header {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px 25px;
        border-bottom: 1px solid #f8fafc;
    }

    .step-number {
        width: 45px;
        height: 45px;
        background-color: var(--light-orange);
        color: var(--primary-orange);
        font-size: 1.2rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        flex-shrink: 0;
    }

    .step-card h3 {
        font-size: 1.15rem;
        font-weight: 700;
        margin: 0;
        color: var(--text-dark);
    }

    .step-body {
        padding: 20px 25px;
        color: var(--text-muted);
        line-height: 1.7;
    }

    /* List styling dalam card */
    .feature-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .feature-list li {
        position: relative;
        padding-left: 28px;
        margin-bottom: 12px;
    }

    .feature-list li::before {
        content: '\F26E'; /* Bootstrap icon check-circle-fill */
        font-family: "bootstrap-icons";
        position: absolute;
        left: 0;
        top: 0;
        color: var(--primary-orange);
        font-size: 1.1rem;
    }

    /* Alert Box */
    .alert-custom {
        background-color: white;
        border-left: 5px solid var(--primary-orange);
        border-radius: 0.8rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
    }

    /* Badges */
    .badge-orange {
        background-color: var(--primary-orange);
        color: white;
        padding: 5px 10px;
        border-radius: 6px;
        font-weight: 600;
    }

    /* Responsif Mobile */
    @media (max-width: 768px) {
        .guide-page {
            padding: 100px 0 40px;
        }
        .guide-header {
            text-align: center;
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
                        <i class="bi bi-journal-bookmark-fill me-1" aria-hidden="true"></i> Pusat Bantuan Flavory.id
                    </span>
                    <h1>Panduan Menambah Menu di Flavory.id</h1>
                    <p class="text-muted mt-3 fs-5" style="max-width: 700px;">
                        Langkah demi langkah cara input menu Flavory agar proses kasir dan pemesanan berjalan lancar.
                    </p>
                </header>

                <div class="ratio ratio-16x9 mb-5 shadow-sm rounded-4 overflow-hidden" style="border: 1px solid rgba(0,0,0,0.05);">
                    <iframe src="https://www.youtube.com/embed/gtHaseiJKAA" title="Video Panduan" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>

                <section class="mb-5">
                    <div class="alert alert-custom p-4 d-flex align-items-center gap-3">
                        <i class="bi bi-info-circle-fill fs-1 text-orange" style="color: var(--primary-orange);" aria-hidden="true"></i>
                        <div>
                            <h2 class="h5 fw-bold mb-1 text-dark">Informasi Penting</h2>
                            <p class="mb-0 text-muted">Sebelum membuat pesanan atau membuka kasir, pastikan Anda sudah menambahkan menu terlebih dahulu ke dalam sistem. Data yang lengkap akan mempercepat pelayanan outlet Anda.</p>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <h2 class="section-title">Langkah-Langkah Mengelola Menu</h2>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <article class="step-card">
                                <header class="step-header">
                                    <div class="step-number" aria-hidden="true">1</div>
                                    <h3>Akses Halaman Kelola Menu</h3>
                                </header>
                                <div class="step-body">
                                    <ul class="feature-list">
                                        <li>Klik tab <strong>Opsi Lainnya</strong> pada navbar kasir.</li>
                                        <li><small class="text-secondary"><em>(Bagi pengguna HP, buka menu hamburger <i class="bi bi-list" aria-hidden="true"></i> di pojok kanan atas).</em></small></li>
                                        <li>Pilih submenu <strong>Kelola Menu</strong>.</li>
                                        <li>Anda akan langsung diarahkan ke halaman pengelolaan menu. <br><strong>Catatan:</strong> Maksimal jumlah menu yang dapat ditambahkan adalah <strong>500 menu</strong>.</li>
                                    </ul>
                                </div>
                            </article>
                        </div>

                        <div class="col-md-6">
                            <article class="step-card">
                                <header class="step-header">
                                    <div class="step-number" aria-hidden="true">2</div>
                                    <h3>Cara Menambah Menu Dasar</h3>
                                </header>
                                <div class="step-body">
                                    <p class="mb-2">Pada form yang tersedia, Anda wajib mengisi informasi dasar produk:</p>
                                    <ul class="feature-list">
                                        <li><strong>Nama Menu:</strong> Ketikkan nama makanan/minuman.</li>
                                        <li><strong>Harga:</strong> Masukkan harga jual standar produk tersebut.</li>
                                    </ul>
                                    <p class="mt-3 mb-0 small text-secondary"><i class="bi bi-lightbulb-fill text-warning" aria-hidden="true"></i> <em>Tips: Gunakan nama yang singkat dan jelas agar mudah dicari kasir.</em></p>
                                </div>
                            </article>
                        </div>

                        <div class="col-md-6">
                            <article class="step-card">
                                <header class="step-header">
                                    <div class="step-number" aria-hidden="true">3</div>
                                    <h3>Melengkapi Detail Menu</h3>
                                </header>
                                <div class="step-body">
                                    <p class="mb-3">Klik <strong>ikon grid (<i class="bi bi-grid-3x3-gap-fill text-secondary" aria-hidden="true"></i>)</strong> yang berada di samping kolom harga untuk menambahkan informasi tambahan:</p>
                                    <ul class="feature-list">
                                        <li><strong>Deskripsi:</strong> Penjelasan singkat tentang menu.</li>
                                        <li><strong>Foto:</strong> Unggah gambar produk agar lebih menarik.</li>
                                        <li><strong>Varian / Level:</strong> Tambahkan ukuran (Reguler, Large) atau level pedas. <em>Bisa lebih dari satu dan masing-masing dapat memiliki harga yang berbeda.</em></li>
                                        <li><strong>Stok Menu:</strong> Atur jumlah stok tersedia.</li>
                                    </ul>
                                </div>
                            </article>
                        </div>

                        <div class="col-md-6">
                            <article class="step-card">
                                <header class="step-header">
                                    <div class="step-number" aria-hidden="true">4</div>
                                    <h3>Pengaturan Kategori & Tipe</h3>
                                </header>
                                <div class="step-body">
                                    <p class="mb-3">Untuk merapikan daftar menu, gunakan pengaturan tambahan berikut:</p>
                                    <ul class="feature-list">
                                        <li><strong>Kategori Utama:</strong> Kelompokkan menu (misal: Makanan, Minuman, Snack). <em>*Jika dikosongkan, otomatis akan masuk ke kategori "F&B".</em></li>
                                        <li><strong>Tipe Menu:</strong> Beri penanda khusus seperti <span class="badge bg-danger">Best Seller</span>, Menu Utama, atau Rekomendasi untuk menonjolkan produk andalan Anda.</li>
                                    </ul>
                                </div>
                            </article>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <h2 class="section-title">Fitur Tambahan & Manajemen Data</h2>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <article class="card border-0 shadow-sm rounded-4 h-100" style="background-color: var(--light-orange);">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <i class="bi bi-node-plus-fill fs-2" style="color: var(--primary-orange);" aria-hidden="true"></i>
                                        <h3 class="h5 fw-bold mb-0 text-dark">Tambah Banyak Menu Sekaligus</h3>
                                    </div>
                                    <p class="text-muted mb-0">
                                        Ingin input menu lebih cepat? Anda bisa menambahkan hingga <strong>20 menu sekaligus</strong> dengan menekan tombol <span class="badge badge-orange"><i class="bi bi-plus-lg" aria-hidden="true"></i></span> pada form. Kolom isian menu baru akan otomatis muncul di bagian bawah form yang sedang Anda isi.
                                    </p>
                                </div>
                            </article>
                        </div>

                        <div class="col-md-6">
                            <article class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                                <div class="card-body p-4 border border-light rounded-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <i class="bi bi-pencil-square fs-2 text-secondary" aria-hidden="true"></i>
                                        <h3 class="h5 fw-bold mb-0 text-dark">Ubah & Hapus Menu</h3>
                                    </div>
                                    <ul class="feature-list mb-0">
                                        <li class="mb-3"><strong>Ubah (Edit):</strong> Cari menu yang ingin diubah pada daftar bawah, lalu klik ikon <strong>pensil (<i class="bi bi-pencil-fill text-warning" aria-hidden="true"></i>)</strong> untuk mengubah harga, nama, atau stok.</li>
                                        <li><strong>Hapus (Delete):</strong> Jika menu sudah tidak dijual, klik ikon <strong>tong sampah (<i class="bi bi-trash-fill text-danger" aria-hidden="true"></i>)</strong> yang berada di samping tombol edit.</li>
                                    </ul>
                                </div>
                            </article>
                        </div>
                    </div>
                </section>

                <aside class="card border-0 rounded-4" style="background: linear-gradient(to right, #1e293b, #334155); color: white;">
                    <div class="card-body p-4 p-md-5 text-center">
                        <i class="bi bi-check-circle-fill text-success fs-1 mb-3 d-inline-block" aria-hidden="true"></i>
                        <h3 class="fw-bold mb-3">Penting!</h3>
                        <p class="mb-0 fs-6 opacity-75 mx-auto" style="max-width: 600px;">
                            Pastikan seluruh data menu diisi dengan benar, terutama bagian Harga dan Varian. Data yang akurat akan membuat proses pemesanan (Order) di kasir berjalan lancar tanpa kendala perhitungan.
                        </p>
                    </div>
                </aside>

            </div>
        </div>
    </div>
</main>
@endsection