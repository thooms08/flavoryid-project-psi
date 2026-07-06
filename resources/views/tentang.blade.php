{{-- resources/views/tentang.blade.php --}}
@extends('layouts.app')

@section('title', 'Tentang Flavory.id - Aplikasi Kasir All-in-One & Manajemen Bisnis Kuliner')

@push('styles')
<meta name="description" content="Mengenal Flavory.id, lebih dari sekadar aplikasi POS terbaik. Kami adalah solusi All-in-One untuk manajemen bisnis restoran yang dilengkapi dengan prediksi performa dan MineAI.">
<meta name="keywords" content="tentang flavory, aplikasi POS terbaik, aplikasi kasir kuliner, manajemen bisnis restoran, aplikasi kasir all in one, software restoran digital">
<meta name="author" content="Flavory.id">

<style>
    /* Hero Section */
    .about-hero {
        padding: 140px 0 80px;
        background: linear-gradient(135deg, var(--light-orange) 0%, #ffffff 100%);
        border-bottom: 1px solid #f8fafc;
    }
    
    .section-title {
        position: relative;
        display: inline-block;
        margin-bottom: 2rem;
        font-weight: 800;
        color: var(--text-dark);
    }
    
    .section-title::after {
        content: "";
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background-color: var(--primary-orange);
        border-radius: 2px;
    }

    .section-title.text-start::after {
        left: 0;
        transform: none;
    }

    /* Cards */
    .info-card {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 20px;
        padding: 40px 30px;
        height: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(255, 106, 0, 0.08);
    }

    .icon-wrapper {
        width: 70px;
        height: 70px;
        background-color: var(--light-orange);
        color: var(--primary-orange);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 24px;
    }

    /* Vision Mission Banner */
    .vm-banner {
        background-color: #1e293b; /* Darker professional blue-grey */
        border-radius: 24px;
        overflow: hidden;
        position: relative;
    }

    .check-list-orange {
        list-style: none;
        padding-left: 0;
    }
    
    .check-list-orange li {
        margin-bottom: 16px;
        display: flex;
        align-items: flex-start;
        color: var(--text-muted);
    }
    
    .check-list-orange i {
        color: var(--primary-orange);
        font-size: 1.25rem;
        margin-right: 12px;
    }

    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, var(--primary-orange) 0%, #ff8c33 100%);
        padding: 80px 0;
        color: white;
    }

    /* Utility */
    .text-orange { color: var(--primary-orange) !important; }
    .bg-orange { background-color: var(--primary-orange) !important; }
</style>
@endpush

@section('content')
    <main>
        <header class="about-hero text-center">
            <div class="container">
                <span class="badge bg-white text-orange px-3 py-2 rounded-pill mb-3 fw-bold border border-warning-subtle shadow-sm">
                    <i class="bi bi-rocket-takeoff-fill me-1"></i> Partner Tumbuh Bisnis Kuliner
                </span>
                <h1 class="display-4 fw-bolder mb-3 text-dark">Mengenal Lebih Dekat <span class="text-orange">Flavory.id</span></h1>
                <p class="lead text-muted mx-auto" style="max-width: 800px;">
                    Kami percaya bahwa setiap pemilik usaha kuliner layak mendapatkan teknologi terbaik untuk mempermudah operasional dan melipatgandakan keuntungan.
                </p>
            </div>
        </header>

        <section class="py-5 bg-white">
            <div class="container py-4">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6">
                        <img src="{{ asset('gambar/about-vision.png') }}" onerror="this.src='https://placehold.co/600x500/fff4e6/ff6a00?text=All-in-One+Solution'" alt="Manajemen Bisnis Restoran Flavory.id" class="img-fluid rounded-4 shadow-lg">
                    </div>
                    <div class="col-lg-6">
                        <h2 class="section-title text-start">Bukan Sekadar Aplikasi POS Biasa</h2>
                        <p class="fs-5 text-dark fw-medium mb-3">
                            Flavory.id hadir sebagai <strong>Aplikasi All-in-One</strong> yang dirancang khusus untuk menjawab tantangan nyata di industri makanan dan minuman (F&B).
                        </p>
                        <p class="text-muted mb-4" style="line-height: 1.8;">
                            Banyak aplikasi kasir hanya berhenti pada mencatat transaksi. Flavory.id melangkah lebih jauh. Kami menggabungkan kemudahan transaksi dengan kekuatan analisa data untuk membantu Anda memahami kesehatan bisnis secara utuh tanpa perlu menjadi ahli teknologi.
                        </p>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill text-orange me-2"></i>
                                    <span class="small fw-bold">Mudah untuk Pemula</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill text-orange me-2"></i>
                                    <span class="small fw-bold">Laporan Real-Time</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5" style="background-color: #f8fafc;">
            <div class="container py-4">
                <div class="text-center mb-5">
                    <h2 class="section-title">Apa yang Membuat Kami Berbeda?</h2>
                    <p class="text-muted mx-auto" style="max-width: 600px;">Tiga pilar utama Flavory.id yang membantu Anda mengelola bisnis dengan lebih cerdas dan tenang.</p>
                </div>
                
                <div class="row g-4 justify-content-center">
                    <div class="col-lg-4 col-md-6">
                        <article class="info-card">
                            <div class="icon-wrapper"><i class="bi bi-bar-chart-steps"></i></div>
                            <h3 class="h4 fw-bold mb-3">Laporan Performa Bisnis</h3>
                            <p class="text-muted mb-0">Tinggalkan tebak-tebakan. Lihat dengan jelas kapan outlet Anda ramai atau sepi. Ketahui hari, tanggal, dan jam operasional terbaik agar Anda bisa mengatur stok dan tim dengan lebih efektif.</p>
                        </article>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <article class="info-card border-orange shadow-sm">
                            <div class="icon-wrapper bg-orange text-white"><i class="bi bi-graph-up-arrow"></i></div>
                            <h3 class="h4 fw-bold mb-3">Prediksi Performa ke Depan</h3>
                            <p class="text-muted mb-0">Ingin tahu omzet bulan depan? Flavory.id memberikan gambaran prediksi performa bisnis Anda hingga 1 bulan ke depan berdasarkan pola data masa lalu, membantu Anda bersiap lebih awal.</p>
                        </article>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <article class="info-card">
                            <div class="icon-wrapper"><i class="bi bi-robot"></i></div>
                            <h3 class="h4 fw-bold mb-3">MineAI (Asisten Bisnis)</h3>
                            <p class="text-muted mb-0">Asisten cerdas pribadi Anda. MineAI memberikan saran pengembangan, analisa risiko, serta menghitung potensi keuntungan dan dampak dari setiap keputusan bisnis yang akan Anda ambil.</p>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 bg-white">
            <div class="container py-4">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6">
                        <h2 class="section-title text-start">Solusi Manajemen Bisnis Restoran Modern</h2>
                        <p class="text-muted mb-4">
                            Jika Anda mencari <strong>aplikasi kasir kuliner</strong> yang tidak sekadar cetak struk, Flavory.id adalah jawabannya. Kami membangun sistem ini agar pengusaha UMKM bisa memiliki standar pengelolaan kelas restoran besar.
                        </p>
                        <ul class="check-list-orange">
                            <li><i class="bi bi-patch-check-fill"></i> <div><strong>Aplikasi Kasir All-in-One:</strong> Tidak perlu banyak aplikasi tambahan. Transaksi, stok, dan analisa jadi satu.</div></li>
                            <li><i class="bi bi-patch-check-fill"></i> <div><strong>Fokus pada Pertumbuhan:</strong> Fitur AI kami membantu Anda fokus pada strategi, bukan hanya administrasi.</div></li>
                            <li><i class="bi bi-patch-check-fill"></i> <div><strong>Dukungan Penuh:</strong> Kami memberikan pelatihan gratis karena kesuksesan Anda adalah misi kami.</div></li>
                        </ul>
                    </div>
                    <div class="col-lg-6">
                        <div class="vm-banner p-4 p-md-5 shadow-lg">
                            <h3 class="fw-bold text-orange mb-4 d-flex align-items-center">
                                <i class="bi bi-bullseye me-2"></i> Misi Kami Untuk Anda
                            </h3>
                            <ul class="list-unstyled text-light opacity-75 fs-6" style="line-height: 1.8;">
                                <li class="mb-3 d-flex"><i class="bi bi-check2 text-orange me-3 fs-4"></i> <span>Mendigitalisasi bisnis kuliner dengan cara yang paling sederhana dan mudah dipahami.</span></li>
                                <li class="mb-3 d-flex"><i class="bi bi-check2 text-orange me-3 fs-4"></i> <span>Menyediakan data akurat agar Anda bisa mengambil keputusan bisnis dengan percaya diri.</span></li>
                                <li class="mb-0 d-flex"><i class="bi bi-check2 text-orange me-3 fs-4"></i> <span>Membantu Anda menghemat waktu kerja manual agar bisa fokus pada kualitas rasa.</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="cta-section text-center">
            <div class="container">
                <h2 class="display-5 fw-bold mb-4">Siap Melangkah Lebih Jauh dengan Flavory.id?</h2>
                <p class="fs-5 mb-5 opacity-75 mx-auto" style="max-width: 700px;">
                    Jadikan Flavory.id sebagai fondasi digital bisnis kuliner Anda. Nikmati kemudahan aplikasi kasir all-in-one sekarang juga.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('register') ?? '#' }}" class="btn btn-light btn-lg fw-bold text-orange rounded-pill px-5 py-3 shadow">
                        Mulai Coba Gratis <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    <a href="https://wa.me/+6285797574754" class="btn btn-outline-light btn-lg fw-bold rounded-pill px-5 py-3">
                        <i class="bi bi-whatsapp me-2"></i> Tanya Dulu Yuk
                    </a>
                </div>
            </div>
        </section>
    </main>
@endsection