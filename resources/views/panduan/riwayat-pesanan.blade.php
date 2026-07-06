@extends('layouts.app')

@section('title', 'Panduan Cetak Struk & Riwayat Pesanan | Flavory.id')

@push('styles')
<meta name="description" content="Panduan menggunakan fitur riwayat pesanan flavory.id. Pelajari cara cetak struk kasir via Bluetooth, USB, hingga kirim struk digital kasir via WhatsApp dan QR Code.">
<meta name="keywords" content="cetak struk kasir, riwayat pesanan flavory, struk digital kasir, struk whatsapp, cetak struk bluetooth, struk thermal POS">
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

    /* Receipt Method Cards */
    .receipt-card {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 1.2rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.02);
        height: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        padding: 25px;
        position: relative;
        overflow: hidden;
    }

    .receipt-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(255, 106, 0, 0.08);
        border-color: rgba(255, 106, 0, 0.2);
    }

    .receipt-icon-wrapper {
        font-size: 2.5rem;
        color: var(--primary-orange);
        margin-bottom: 15px;
    }

    .pro-con-list {
        list-style: none;
        padding: 0;
        margin-top: 15px;
        font-size: 0.9rem;
    }

    .pro-con-list li {
        margin-bottom: 8px;
        display: flex;
        align-items: flex-start;
        gap: 8px;
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
                        <i class="bi bi-clock-history me-1" aria-hidden="true"></i> Riwayat & Laporan
                    </span>
                    <h1>Panduan Cetak Struk & Riwayat Pesanan</h1>
                    <p class="text-muted mt-3 fs-5">
                        Pelajari cara memaksimalkan halaman riwayat pesanan untuk memantau penjualan, serta berbagai metode pencetakan dan pengiriman struk pelanggan.
                    </p>
                </header>

                <div class="ratio ratio-16x9 mb-5 shadow-sm rounded-4 overflow-hidden" style="border: 1px solid rgba(0,0,0,0.05);">
                    <iframe src="https://www.youtube.com/embed/kuHw40eM7bA" title="Video Panduan" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>

                <section class="mb-5">
                    <div class="bg-white p-4 rounded-4 border shadow-sm">
                        <p class="text-muted mb-0">
                            Setelah proses konfirmasi pembayaran di halaman dashboard selesai dilakukan, Anda akan otomatis diarahkan ke halaman <strong>Riwayat Pesanan</strong>. Halaman ini bertindak sebagai pusat rekapitulasi harian transaksi outlet Anda.
                        </p>
                    </div>
                </section>

                <section class="mb-5">
                    <h2 class="section-title">Fitur Halaman Riwayat Pesanan</h2>
                    <p class="text-muted mb-4">Di halaman ini, Anda memiliki kendali penuh untuk memantau performa penjualan. Berikut adalah fitur-fitur yang tersedia:</p>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <article class="feature-card">
                                <div class="feature-icon" aria-hidden="true"><i class="bi bi-card-checklist"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-1">Daftar Pesanan (Cards)</h3>
                                    <p class="text-muted small mb-0">Melihat seluruh riwayat pesanan yang sudah dikonfirmasi pembayarannya dalam bentuk kartu (card) yang rapi.</p>
                                </div>
                            </article>
                        </div>
                        <div class="col-md-6">
                            <article class="feature-card">
                                <div class="feature-icon" aria-hidden="true"><i class="bi bi-graph-up-arrow"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-1">Ringkasan Pendapatan</h3>
                                    <p class="text-muted small mb-0">Mengetahui <strong>Total Penjualan</strong> (jumlah struk) dan <strong>Total Pendapatan</strong> (Rp) secara <em>real-time</em>.</p>
                                </div>
                            </article>
                        </div>
                        <div class="col-md-6">
                            <article class="feature-card">
                                <div class="feature-icon" aria-hidden="true"><i class="bi bi-calendar-range"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-1">Filter Tanggal</h3>
                                    <p class="text-muted small mb-0">Memfilter dan menyortir riwayat pesanan berdasarkan rentang tanggal tertentu (harian, mingguan, bulanan).</p>
                                </div>
                            </article>
                        </div>
                        <div class="col-md-6">
                            <article class="feature-card">
                                <div class="feature-icon" aria-hidden="true"><i class="bi bi-search"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-1">Pencarian Cepat</h3>
                                    <p class="text-muted small mb-0">Mencari pesanan spesifik melalui kolom pencarian, baik menggunakan nama pelanggan maupun nomor pesanan (Order ID).</p>
                                </div>
                            </article>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <div class="bg-white p-4 rounded-4 border shadow-sm d-flex flex-column flex-md-row align-items-center gap-4">
                        <div class="bg-light p-3 rounded-3 border text-center" style="min-width: 150px;">
                            <i class="bi bi-three-dots-vertical fs-1 text-orange" style="color: var(--primary-orange);" aria-hidden="true"></i>
                            <div class="fw-bold mt-2 text-dark small">Ikon Aksi</div>
                        </div>
                        <div>
                            <h2 class="h5 fw-bold text-dark mb-2">Cara Membuka Menu Struk</h2>
                            <p class="text-muted mb-0">Untuk mencetak atau mengirim struk, <strong>klik ikon titik tiga (⋮)</strong> yang terletak di pojok kanan atas pada setiap *card* riwayat pesanan. Setelah diklik, sebuah menu *dropdown* akan muncul berisi berbagai pilihan metode struk.</p>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <h2 class="section-title">5 Pilihan Metode Struk</h2>
                    <p class="text-muted mb-4">Pilih metode penyampaian bukti pembayaran yang paling sesuai dengan ketersediaan perangkat dan kebutuhan pelanggan Anda:</p>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <article class="receipt-card">
                                <div class="receipt-icon-wrapper" aria-hidden="true"><i class="bi bi-qr-code-scan"></i></div>
                                <h3 class="h5 fw-bold text-dark">1. Struk Digital (QR Code)</h3>
                                <p class="text-muted small">Sistem akan menampilkan QR Code besar di layar perangkat kasir Anda.</p>
                                <ul class="pro-con-list text-muted">
                                    <li><i class="bi bi-check-circle-fill text-success" aria-hidden="true"></i> <strong>Kelebihan:</strong> Praktis, ramah lingkungan, tidak memerlukan printer thermal sama sekali.</li>
                                    <li><i class="bi bi-info-circle-fill text-primary" aria-hidden="true"></i> <strong>Catatan:</strong> Pelanggan harus menggunakan kamera HP mereka untuk melakukan scan (*scan*) agar struk tampil di HP mereka.</li>
                                </ul>
                            </article>
                        </div>

                        <div class="col-md-6">
                            <article class="receipt-card">
                                <div class="receipt-icon-wrapper" aria-hidden="true"><i class="bi bi-whatsapp" style="color: #25D366;"></i></div>
                                <h3 class="h5 fw-bold text-dark">2. Struk Digital (WhatsApp)</h3>
                                <p class="text-muted small">Struk digital dalam bentuk pesan teks rapi akan dikirimkan langsung ke WhatsApp pelanggan.</p>
                                <ul class="pro-con-list text-muted">
                                    <li><i class="bi bi-check-circle-fill text-success" aria-hidden="true"></i> <strong>Kelebihan:</strong> Sangat praktis, pelanggan tidak perlu *scan* atau menunggu cetakan fisik.</li>
                                    <li><i class="bi bi-info-circle-fill text-primary" aria-hidden="true"></i> <strong>Syarat:</strong> Form "Nomor WhatsApp" pelanggan harus sudah diisi pada saat proses input pesanan.</li>
                                </ul>
                            </article>
                        </div>

                        <div class="col-md-6">
                            <article class="receipt-card">
                                <div class="receipt-icon-wrapper" aria-hidden="true"><i class="bi bi-bluetooth"></i></div>
                                <h3 class="h5 fw-bold text-dark">3. Cetak Fisik (Bluetooth)</h3>
                                <p class="text-muted small">Mencetak struk fisik melalui *smartphone* atau tablet menggunakan printer thermal (ukuran kertas 58mm).</p>
                                <ul class="pro-con-list text-muted mb-2">
                                    <li><i class="bi bi-gear-fill text-secondary" aria-hidden="true"></i> <strong>Kebutuhan:</strong> Aplikasi "Printer Bluetooth Sederhana".</li>
                                </ul>
                                <div class="bg-light p-2 rounded small text-muted">
                                    <strong>Langkah:</strong> Install aplikasi &rarr; Aktifkan Bluetooth HP &rarr; Hubungkan (Pairing) ke printer &rarr; Cetak.
                                </div>
                            </article>
                        </div>

                        <div class="col-md-6">
                            <article class="receipt-card">
                                <div class="receipt-icon-wrapper" aria-hidden="true"><i class="bi bi-usb-symbol text-secondary"></i></div>
                                <h3 class="h5 fw-bold text-dark">4. Cetak Fisik (USB Printer)</h3>
                                <p class="text-muted small">Mencetak struk menggunakan printer thermal ESC/POS (58mm) yang dihubungkan menggunakan kabel USB.</p>
                                <ul class="pro-con-list text-muted mb-2">
                                    <li><i class="bi bi-exclamation-triangle-fill text-warning" aria-hidden="true"></i> <strong>Catatan:</strong> Metode ini <em>hanya</em> berlaku untuk perangkat Laptop atau Komputer (PC), tidak untuk HP/Tablet.</li>
                                </ul>
                                <div class="bg-light p-2 rounded small text-muted">
                                    <strong>Langkah:</strong> Colok kabel USB printer ke Laptop &rarr; Klik metode ini &rarr; Cetak seperti biasa melalui dialog browser.
                                </div>
                            </article>
                        </div>

                        <div class="col-12">
                            <article class="receipt-card d-flex flex-column flex-md-row gap-4 align-items-md-center border-orange" style="border-color: rgba(255, 106, 0, 0.3); background-color: #fffcf8;">
                                <div class="receipt-icon-wrapper mb-0 ms-md-3" aria-hidden="true"><i class="bi bi-shop"></i></div>
                                <div>
                                    <h3 class="h5 fw-bold text-dark">5. Cetak Struk Dapur (Kitchen) via Bluetooth</h3>
                                    <p class="text-muted small mb-2">Digunakan untuk mengirim daftar rincian pesanan langsung ke area dapur. Sangat membantu meningkatkan efisiensi operasional dan mengurangi risiko salah pesanan.</p>
                                    <ul class="pro-con-list text-muted mb-0 d-flex flex-column flex-md-row gap-3">
                                        <li><i class="bi bi-check2-square text-orange" aria-hidden="true"></i> Butuh Printer BT (58mm)</li>
                                        <li><i class="bi bi-check2-square text-orange" aria-hidden="true"></i> Butuh App Printer Bluetooth</li>
                                        <li><i class="bi bi-info-circle-fill text-primary" aria-hidden="true"></i> <strong>Catatan:</strong> Fitur ini juga bisa diakses di dashboard kasir sebelum pelanggan membayar.</li>
                                    </ul>
                                </div>
                            </article>
                        </div>

                    </div>
                </section>

                <aside class="important-alert mt-5">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <i class="bi bi-shield-fill-exclamation text-danger fs-3" aria-hidden="true"></i>
                        <h3 class="h5 fw-bold text-dark mb-0">Keamanan Data Penjualan</h3>
                    </div>
                    <p class="text-muted mb-0" style="font-size: 1.05rem;">
                        Harap diperhatikan bahwa pada halaman Riwayat Pesanan <strong>tidak disediakan fitur Hapus Data</strong>. Kebijakan sistem ini diterapkan secara ketat demi keamanan outlet, untuk mencegah terjadinya kecurangan atau <strong>manipulasi data penjualan</strong> oleh pihak kasir atau karyawan.
                    </p>
                </aside>

            </div>
        </div>
    </div>
</main>
@endsection