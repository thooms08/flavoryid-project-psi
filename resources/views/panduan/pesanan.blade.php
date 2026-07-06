@extends('layouts.app')

@section('title', 'Cara Membuat Pesanan Kasir | Panduan Flavory.id')

@push('styles')
<meta name="description" content="Panduan lengkap cara membuat pesanan kasir, input pesanan, pengaturan diskon, hingga proses pembayaran dan cetak struk bluetooth di dashboard kasir Flavory.id.">
<meta name="keywords" content="cara membuat pesanan kasir, input pesanan flavory, dashboard kasir flavory, cara cetak struk bluetooth, aplikasi kasir, point of sale">
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
        position: relative;
        overflow: hidden;
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

    /* Feature Box */
    .feature-box {
        background: #ffffff;
        border-left: 5px solid var(--primary-orange);
        border-radius: 1rem;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
    }

    /* Action List */
    .action-list {
        list-style: none;
        padding: 0;
    }
    
    .action-list li {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
        padding: 15px;
        background-color: #f8fafc;
        border-radius: 0.8rem;
        border: 1px solid #e2e8f0;
    }

    .action-list li i {
        font-size: 1.5rem;
        color: var(--primary-orange);
    }

    /* Alert / Notes */
    .note-alert {
        background-color: var(--light-orange);
        border: 1px dashed var(--primary-orange);
        border-radius: 1rem;
        padding: 20px;
    }

    /* Payment Methods */
    .payment-method {
        text-align: center;
        padding: 20px;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        background-color: white;
        height: 100%;
    }
    
    .payment-method i {
        font-size: 2.5rem;
        color: var(--text-dark);
        margin-bottom: 15px;
        display: block;
    }

    /* Buttons Override */
    .btn-outline-orange {
        color: var(--primary-orange);
        border-color: var(--primary-orange);
    }
    .btn-outline-orange:hover {
        background-color: var(--primary-orange);
        color: white;
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
                    <span class="badge bg-white text-orange border border-warning-subtle px-3 py-2 rounded-pill mb-3 fw-bold shadow-sm" style="color: var(--primary-orange);">
                        <i class="bi bi-cart-check-fill me-1" aria-hidden="true"></i> Transaksi Kasir
                    </span>
                    <h1>Panduan Membuat Pesanan di Flavory.id</h1>
                    <p class="text-muted mt-3 fs-5">
                        Pelajari alur lengkap input pesanan flavory, penerapan diskon, pencetakan struk dapur, hingga konfirmasi pembayaran.
                    </p>
                </header>

                <div class="ratio ratio-16x9 mb-5 shadow-sm rounded-4 overflow-hidden" style="border: 1px solid rgba(0,0,0,0.05);">
                    <iframe src="https://www.youtube.com/embed/eKbB4l3IDGQ" title="Video Panduan" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>

                <section class="mb-5">
                    <h2 class="section-title">Langkah Membuat Pesanan Baru</h2>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <article class="step-card">
                                <div class="step-number" aria-hidden="true">1</div>
                                <h3 class="h5 fw-bold text-dark">Mulai Pesanan Baru</h3>
                                <p class="text-muted mb-0">Pada halaman utama <strong>dashboard kasir</strong>, cari dan klik tombol <span class="badge px-2 py-1" style="background-color: var(--primary-orange); color: white;"><i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah Pesanan</span> untuk membuka form pemesanan.</p>
                            </article>
                        </div>
                        
                        <div class="col-md-6">
                            <article class="step-card">
                                <div class="step-number" aria-hidden="true">2</div>
                                <h3 class="h5 fw-bold text-dark">Input Menu & Data</h3>
                                <p class="text-muted mb-0">Pilih menu yang dipesan oleh pelanggan. Selanjutnya, isi data pesanan pada form yang tampil. <em>(Form yang muncul seperti Nama, No. Meja, dll akan menyesuaikan dengan pengaturan form yang telah Anda tentukan sebelumnya)</em>.</p>
                            </article>
                        </div>

                        <div class="col-md-6">
                            <article class="step-card">
                                <div class="step-number" aria-hidden="true">3</div>
                                <h3 class="h5 fw-bold text-dark">Pengaturan Diskon</h3>
                                <p class="text-muted mb-2">Jika ada potongan harga, Anda dapat memasukkan diskon. Tersedia 2 jenis diskon:</p>
                                <ul class="text-muted small ps-3 mb-0">
                                    <li><strong>Diskon Persentase (%)</strong> - Potongan berdasarkan persentase total.</li>
                                    <li><strong>Diskon Nominal (Rp)</strong> - Potongan dengan angka pasti (misal: Rp 10.000).</li>
                                </ul>
                            </article>
                        </div>

                        <div class="col-md-6">
                            <article class="step-card">
                                <div class="step-number" aria-hidden="true">4</div>
                                <h3 class="h5 fw-bold text-dark">Proses Pesanan</h3>
                                <p class="text-muted mb-0">Setelah semua data terisi dengan benar, klik tombol <strong>"PROSES SEKARANG"</strong>. Pesanan akan otomatis masuk ke <em>antrean pesanan</em> di dashboard kasir Anda.</p>
                            </article>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <h2 class="section-title">Aksi Sebelum Pembayaran (Antrean)</h2>
                    <p class="text-muted mb-4">Saat pesanan berada di antrean (belum dibayar), kasir dapat melakukan beberapa tindakan berikut:</p>
                    
                    <div class="row g-4">
                        <div class="col-md-5">
                            <ul class="action-list">
                                <li>
                                    <i class="bi bi-check2-circle text-success" aria-hidden="true"></i>
                                    <div>
                                        <h3 class="h6 fw-bold mb-1">Status Penyajian</h3>
                                        <span class="text-muted small">Fitur ini membantu memberitahu kasir apakah menu pesanan sudah disajikan atau belum.</span>
                                    </div>
                                </li>
                                <li>
                                    <i class="bi bi-printer" aria-hidden="true"></i>
                                    <div>
                                        <h3 class="h6 fw-bold mb-1">Cetak Struk Dapur</h3>
                                        <span class="text-muted small">Kirim rincian pesanan ke dapur.</span>
                                    </div>
                                </li>
                                <li>
                                    <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                    <div>
                                        <h3 class="h6 fw-bold mb-1">Edit Pesanan</h3>
                                        <span class="text-muted small">Ubah pesanan jika pelanggan merevisi order.</span>
                                    </div>
                                </li>
                                <li>
                                    <i class="bi bi-trash text-danger" aria-hidden="true"></i>
                                    <div>
                                        <h3 class="h6 fw-bold mb-1">Hapus Pesanan</h3>
                                        <span class="text-muted small">Batalkan pesanan dari antrean.</span>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="col-md-7">
                            <aside class="feature-box h-100">
                                <h3 class="h5 fw-bold text-dark d-flex align-items-center gap-2 mb-3">
                                    <i class="bi bi-bluetooth" aria-hidden="true"></i> Panduan Cetak Struk Bluetooth
                                </h3>
                                <p class="text-muted small mb-3">Untuk mencetak struk fisik ke dapur menggunakan smartphone, ikuti langkah ini:</p>
                                <ol class="text-muted small ps-3 mb-0" style="line-height: 1.8;">
                                    <li>
                                        Unduh aplikasi <strong>"Printer Bluetooth Sederhana"</strong> melalui Google Play Store.
                                        <a href="https://play.google.com/store/apps/details?id=com.iyaltamizh.bluetoothprint" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-orange mt-2 mb-2 d-inline-flex align-items-center gap-2 rounded-pill px-3">
                                            <i class="bi bi-google-play"></i> Download di Play Store
                                        </a>
                                    </li>
                                    <li><strong>Aktifkan Bluetooth</strong> pada perangkat HP Anda.</li>
                                    <li>Hubungkan HP Anda dengan printer thermal <em>(Sangat disarankan menggunakan tipe POS/ESC 58)</em>.</li>
                                    <li>Lakukan percobaan cetak struk dari aplikasi untuk memastikan koneksi berhasil.</li>
                                </ol>
                            </aside>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <h2 class="section-title">Proses Pembayaran</h2>
                    <p class="text-muted mb-4">Untuk menyelesaikan transaksi, klik tombol <strong>"BAYAR SEKARANG"</strong> pada pesanan di antrean. Anda akan diminta memilih salah satu dari 3 metode pembayaran berikut:</p>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <article class="payment-method shadow-sm">
                                <i class="bi bi-cash-coin text-success" aria-hidden="true"></i>
                                <h3 class="h6 fw-bold text-dark mb-0">Cash (Tunai)</h3>
                            </article>
                        </div>
                        <div class="col-md-4">
                            <article class="payment-method shadow-sm">
                                <i class="bi bi-qr-code-scan text-primary" aria-hidden="true"></i>
                                <h3 class="h6 fw-bold text-dark mb-0">QRIS</h3>
                            </article>
                        </div>
                        <div class="col-md-4">
                            <article class="payment-method shadow-sm">
                                <i class="bi bi-bank text-info" aria-hidden="true"></i>
                                <h3 class="h6 fw-bold text-dark mb-0">Transfer Bank</h3>
                            </article>
                        </div>
                    </div>

                    <aside class="feature-box mt-4 mb-4" style="border-left-color: #3b82f6;">
                        <h3 class="h6 fw-bold text-dark d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-camera-fill text-primary" aria-hidden="true"></i> Kamera Bukti Pembayaran
                        </h3>
                        <p class="text-muted small mb-0">
                            Ketika pelanggan menggunakan metode pembayaran <strong>QRIS</strong> atau <strong>Transfer</strong>, akan muncul fitur kamera langsung di form pembayaran. Fitur ini mempermudah kasir untuk memotret bukti pembayaran secara instan tanpa harus bolak-balik ke aplikasi kamera bawaan perangkat. 
                            <br><br>
                            <em class="text-secondary">*Catatan: Jika fitur kamera ini belum dibutuhkan, Anda dapat menonaktifkannya kapan saja melalui menu <strong>"Pengaturan Form"</strong>.</em>
                        </p>
                    </aside>

                    <aside class="note-alert d-flex align-items-center gap-3">
                        <i class="bi bi-exclamation-triangle-fill fs-2 text-orange" style="color: var(--primary-orange);" aria-hidden="true"></i>
                        <div>
                            <h3 class="h6 fw-bold text-dark mb-1">Catatan Penting Sistem Pembayaran</h3>
                            <p class="text-muted small mb-0">Metode pembayaran yang dipilih pada tahap ini <strong>hanya berfungsi sebagai pencatatan atau konfirmasi kasir</strong> untuk keperluan pembukuan (belum terintegrasi secara otomatis dengan <em>payment gateway</em> pihak ketiga).</p>
                        </div>
                    </aside>
                </section>

                <aside class="card border-0 rounded-4" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white;">
                    <div class="card-body p-4 p-md-5 d-flex align-items-center gap-4">
                        <div class="d-none d-md-block">
                            <i class="bi bi-check-circle-fill" style="font-size: 3.5rem; color: #22c55e;" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h3 class="h5 fw-bold mb-2">Transaksi Selesai!</h3>
                            <p class="mb-0 fs-6 opacity-75">
                                Setelah pembayaran berhasil dikonfirmasi, Anda akan diarahkan ke halaman <strong>Riwayat Pesanan</strong>. Di halaman ini, Anda dapat mencetak struk akhir pelanggan, baik dalam bentuk fisik (thermal) maupun mengirimkan <strong>struk digital</strong> kepada pelanggan.
                            </p>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </div>
</main>
@endsection