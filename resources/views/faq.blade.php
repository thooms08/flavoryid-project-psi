{{-- resources/views/faq.blade.php --}}
@extends('layouts.app')

@section('title', 'FAQ & Pusat Bantuan - Aplikasi Flavory.id')

@push('styles')
<meta name="description" content="Punya pertanyaan seputar Flavory.id? Temukan jawaban lengkap mengenai fitur kasir, harga paket, cara penggunaan, hingga dukungan teknis di sini.">
<meta name="keywords" content="FAQ Flavory, bantuan kasir, pusat bantuan aplikasi kasir, pertanyaan Flavory.id, panduan kasir online, masalah kasir digital">
<meta name="author" content="Aplikasi Flavory.id">

<style>
    :root {
        --primary-orange: #ff6a00;
        --dark-orange: #e65f00;
        --light-orange: #fff4e6;
        --text-dark: #1e293b;
        --text-muted: #64748b;
    }

    /* Hero Section */
    .faq-hero {
        padding: 140px 0 80px;
        background: linear-gradient(135deg, var(--light-orange) 0%, #ffffff 100%);
        border-bottom: 1px solid #f8fafc;
    }
    
    /* FAQ Accordion Styling */
    .faq-accordion .accordion-item {
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 16px !important;
        margin-bottom: 16px;
        background-color: #ffffff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .faq-accordion .accordion-item:hover {
        border-color: rgba(255, 106, 0, 0.2);
        box-shadow: 0 8px 25px rgba(255, 106, 0, 0.05);
    }

    .faq-accordion .accordion-button {
        font-weight: 700;
        color: var(--text-dark);
        padding: 20px 24px;
        background-color: transparent;
        font-size: 1.05rem;
        border-radius: 16px !important;
    }

    .faq-accordion .accordion-button:not(.collapsed) {
        color: var(--primary-orange);
        background-color: var(--light-orange);
        box-shadow: inset 0 -1px 0 rgba(0,0,0,0.05);
    }

    .faq-accordion .accordion-button:focus {
        border-color: rgba(255, 106, 0, 0.5);
        box-shadow: 0 0 0 0.25rem rgba(255, 106, 0, 0.15);
    }

    .faq-accordion .accordion-button::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%231e293b'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }

    .faq-accordion .accordion-button:not(.collapsed)::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23ff6a00'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }

    .faq-accordion .accordion-body {
        padding: 20px 24px;
        color: var(--text-muted);
        line-height: 1.7;
    }

    /* Category Navigation */
    .faq-nav {
        background-color: white;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        position: sticky;
        top: 100px;
    }
    
    .faq-nav .nav-link {
        color: var(--text-muted);
        font-weight: 600;
        padding: 12px 20px;
        border-radius: 12px;
        margin-bottom: 5px;
        transition: all 0.2s ease;
    }

    .faq-nav .nav-link:hover {
        background-color: #f8fafc;
        color: var(--primary-orange);
    }

    .faq-nav .nav-link.active {
        background-color: var(--light-orange);
        color: var(--primary-orange);
    }

    /* Contact Banner */
    .contact-banner {
        background: linear-gradient(135deg, var(--primary-orange) 0%, #ff8c33 100%);
        border-radius: 24px;
        padding: 40px;
        color: white;
        text-align: center;
        box-shadow: 0 15px 30px rgba(255, 106, 0, 0.2);
    }
</style>

<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "FAQPage",
  "mainEntity": [
    {
      "@@type": "Question",
      "name": "Apa itu Flavory.id?",
      "acceptedAnswer": {
        "@@type": "Answer",
        "text": "Flavory.id adalah perangkat lunak atau aplikasi kasir pintar (Point of Sale) yang dirancang khusus untuk mempermudah operasional, pelayanan, dan pembuatan laporan keuangan untuk bisnis kuliner (F&B).""
      }
    },
    {
      "@type": "Question",
      "name": "Apakah aplikasi ini bisa digunakan saat internet mati (Offline)?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Tentu saja bisa!Flavory.id mendukung mode offline. Anda tetap bisa mencatat pesanan pelanggan secara normal. Saat koneksi internet kembali terhubung, sistem akan otomatis melakukan sinkronisasi data ke cloud."
      }
    },
    {
      "@type": "Question",
      "name": "Perangkat apa saja yang mendukung Flavory.id?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Aplikasi kami berbasis web modern, sehingga sangat fleksibel dan dapat diakses melalui berbagai perangkat seperti Laptop, PC, Tablet, iPad, maupun Smartphone (Android & iOS) yang memiliki browser web."
      }
    }
  ]
}
</script>
@endpush

@section('content')
    <main>
        <header class="faq-hero text-center">
            <div class="container">
                <span class="badge bg-white text-orange px-3 py-2 rounded-pill mb-3 fw-bold border border-warning-subtle shadow-sm">
                    <i class="bi bi-question-circle-fill me-1"></i> Pusat Bantuan
                </span>
                <h1 class="display-4 fw-bolder mb-3 text-dark">Pertanyaan yang Sering Diajukan</h1>
                <p class="lead text-muted mx-auto" style="max-width: 700px;">
                    Temukan solusi dan jawaban cepat mengenai fitur, operasional, hingga panduan berlangganan Aplikasi Flavory.id.
                </p>
            </div>
        </header>

        <section class="py-5" style="background-color: #f8fafc;">
            <div class="container py-4">
                <div class="row g-5">
                    
                    <div class="col-lg-3 d-none d-lg-block">
                        <nav class="faq-nav" id="faq-navbar">
                            <h4 class="fw-bold mb-3 ms-2 fs-5 text-dark">Kategori</h4>
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#umum"><i class="bi bi-info-square me-2"></i> Info Umum</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#fitur"><i class="bi bi-laptop me-2"></i> Fitur & Operasional</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#harga"><i class="bi bi-tag me-2"></i> Harga & Pembayaran</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#keamanan"><i class="bi bi-shield-check me-2"></i> Keamanan Data</a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <div class="col-lg-9">
                        
                        <div class="mb-5" id="umum">
                            <h3 class="fw-bold text-dark mb-4 d-flex align-items-center">
                                <span class="bg-orange text-white rounded-circle d-inline-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;"><i class="bi bi-info-lg"></i></span>
                                Informasi Umum
                            </h3>
                            
                            <div class="accordion faq-accordion" id="accordionUmum">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUmum1">
                                            Apa itu Flavory.id?
                                        </button>
                                    </h2>
                                    <div id="collapseUmum1" class="accordion-collapse collapse show" data-bs-parent="#accordionUmum">
                                        <div class="accordion-body">
                                            <strong>Flavory.id</strong> adalah perangkat lunak atau aplikasi kasir cerdas (Point of Sale) yang dirancang khusus untuk mendigitalisasi operasional bisnis kuliner (F&B). Mulai dari pencatatan pesanan, manajemen stok, hingga pembuatan laporan operasional bulanan dapat dilakukan secara otomatis di satu tempat.
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUmum2">
                                            Siapa saja yang cocok menggunakan aplikasi ini?
                                        </button>
                                    </h2>
                                    <div id="collapseUmum2" class="accordion-collapse collapse" data-bs-parent="#accordionUmum">
                                        <div class="accordion-body">
                                            Aplikasi ini sangat fleksibel sehingga cocok digunakan oleh berbagai skala bisnis kuliner, mulai dari kedai kopi (coffee shop), warung makan UMKM, restoran keluarga, food truck, hingga franchise yang sedang berkembang.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5" id="fitur">
                            <h3 class="fw-bold text-dark mb-4 d-flex align-items-center">
                                <span class="bg-orange text-white rounded-circle d-inline-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;"><i class="bi bi-laptop"></i></span>
                                Fitur & Operasional
                            </h3>
                            
                            <div class="accordion faq-accordion" id="accordionFitur">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFitur1">
                                            Apakah aplikasi ini bisa digunakan saat internet mati (Offline)?
                                        </button>
                                    </h2>
                                    <div id="collapseFitur1" class="accordion-collapse collapse" data-bs-parent="#accordionFitur">
                                        <div class="accordion-body">
                                            Tentu saja bisa! Flavory.id mendukung <strong>mode offline</strong>. Anda tetap bisa mencatat pesanan dan melayani pelanggan secara normal saat koneksi terputus. Saat perangkat terhubung kembali ke internet, sistem akan secara otomatis melakukan sinkronisasi data ke server (cloud).
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFitur2">
                                            Perangkat (device) apa saja yang mendukung Aplikasi Flavory.id?
                                        </button>
                                    </h2>
                                    <div id="collapseFitur2" class="accordion-collapse collapse" data-bs-parent="#accordionFitur">
                                        <div class="accordion-body">
                                            Aplikasi kami berbasis web modern, sehingga tidak perlu mengunduh aplikasi berat. Anda bisa mengaksesnya melalui browser (Google Chrome, Safari, dll) di berbagai perangkat seperti <strong>Laptop, PC Windows/Mac, Tablet/iPad, maupun Smartphone Android dan iOS</strong>.
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFitur3">
                                            Apakah bisa dihubungkan dengan printer struk thermal?
                                        </button>
                                    </h2>
                                    <div id="collapseFitur3" class="accordion-collapse collapse" data-bs-parent="#accordionFitur">
                                        <div class="accordion-body">
                                            Bisa. Sistem kami mendukung pencetakan struk melalui printer thermal berbasis USB (untuk PC/Laptop) maupun printer Bluetooth (untuk akses melalui Tablet/Smartphone). Selain struk fisik, kami juga mendukung pengiriman e-struk (struk digital) melalui WhatsApp pelanggan.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5" id="harga">
                            <h3 class="fw-bold text-dark mb-4 d-flex align-items-center">
                                <span class="bg-orange text-white rounded-circle d-inline-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;"><i class="bi bi-tag"></i></span>
                                Harga & Pembayaran
                            </h3>
                            
                            <div class="accordion faq-accordion" id="accordionHarga">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHarga1">
                                            Apakah ada masa uji coba (Trial) gratis?
                                        </button>
                                    </h2>
                                    <div id="collapseHarga1" class="accordion-collapse collapse" data-bs-parent="#accordionHarga">
                                        <div class="accordion-body">
                                            Ya, kami memberikan akses <strong>Uji Coba Gratis selama 7 Hari</strong>. Anda dapat mencoba seluruh fitur premium secara penuh tanpa batasan untuk memastikan aplikasi ini benar-benar cocok untuk bisnis Anda.
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHarga3">
                                            Berapa biaya berlangganan Flavory.id?
                                        </button>
                                    </h2>
                                    <div id="collapseHarga3" class="accordion-collapse collapse" data-bs-parent="#accordionHarga">
                                        <div class="accordion-body">
                                            Flavory.id menawarkan paket yang sangat terjangkau. Saat ini tersedia <strong>Paket Eksklusif (Bulanan) seharga Rp 67.000</strong> dan <strong>Paket Super (Tahunan) seharga Rp 880.000</strong>. Harga ini sudah mencakup seluruh fitur All-in-One, termasuk MineAI dan Prediksi Bisnis, tanpa biaya tersembunyi.
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHarga2">
                                            Apakah ada biaya tambahan untuk update sistem?
                                        </button>
                                    </h2>
                                    <div id="collapseHarga2" class="accordion-collapse collapse" data-bs-parent="#accordionHarga">
                                        <div class="accordion-body">
                                            Tidak ada. Semua pengguna aktif yang berlangganan (baik paket bulanan maupun tahunan) akan mendapatkan <strong>Free Update</strong> secara otomatis ketika ada fitur baru atau peningkatan performa tanpa dikenakan biaya tambahan.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="contact-banner mt-5">
                            <h3 class="fw-bold mb-3">Tidak Menemukan Jawaban Anda?</h3>
                            <p class="fs-6 mb-4 opacity-75">Tim support kami siap membantu Anda menyelesaikan kendala teknis maupun memberikan panduan lengkap mengenai Aplikasi Flavory.id.</p>
                            <a href="https://wa.me/6285797574754" target="_blank" class="btn btn-light btn-lg fw-bold text-orange rounded-pill px-5 shadow-sm">
                                <i class="bi bi-whatsapp me-2"></i> Hubungi Customer Service
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const navLinks = document.querySelectorAll('.faq-nav .nav-link');
            
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    // Remove active class from all
                    navLinks.forEach(nav => nav.classList.remove('active'));
                    // Add to clicked
                    this.classList.add('active');
                });
            });
        });
    </script>
@endsection