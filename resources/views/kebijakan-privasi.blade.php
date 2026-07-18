@extends('layouts.app')

@section('title', 'Kebijakan Privasi - Flavory.id')
@section('meta_description', 'Kebijakan Privasi Flavory.id. Kami berkomitmen untuk melindungi data pribadi dan privasi Anda saat menggunakan layanan platform kasir digital kami.')
@section('meta_og_type', 'website')
@section('meta_og_url', route('kebijakan.privasi'))
@section('meta_image', secure_url('assets/preview.jpg'))

@push('styles')
    .privacy-header {
        padding: 120px 0 60px;
        background: linear-gradient(135deg, var(--light-orange) 0%, #ffffff 100%);
        border-bottom: 1px solid #f1f5f9;
    }
    
    .privacy-card {
        background: #ffffff;
        border: none;
        border-radius: 24px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
        padding: 40px;
        margin-top: -40px;
        position: relative;
        z-index: 2;
    }

    .privacy-section {
        margin-bottom: 40px;
    }

    .privacy-section h3 {
        color: var(--text-dark);
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
    }

    /* Aksen oranye di sebelah kiri judul section */
    .privacy-section h3::before {
        content: "";
        display: inline-block;
        width: 6px;
        height: 24px;
        background-color: var(--primary-orange);
        border-radius: 4px;
        margin-right: 12px;
    }

    .privacy-section p, .privacy-section li {
        color: var(--text-muted);
        line-height: 1.8;
        font-size: 1rem;
    }

    .privacy-section ul {
        padding-left: 1.2rem;
    }

    .privacy-section li {
        margin-bottom: 8px;
    }

    .contact-box {
        background-color: var(--light-orange);
        border-radius: 16px;
        padding: 24px;
        border: 1px solid rgba(255, 106, 0, 0.1);
    }

    @media (max-width: 768px) {
        .privacy-card {
            padding: 24px;
            border-radius: 16px;
            margin-top: -20px;
        }
        .privacy-section h3 {
            font-size: 1.25rem;
        }
    }
</style>
@endpush

@section('content')
    <header class="privacy-header text-center">
        <div class="container">
            <h1 class="fw-bolder mb-3 text-dark">Kebijakan Privasi</h1>
            <p class="text-muted fs-5 mb-0">Terakhir diperbarui: 2 April 2026</p>
        </div>
    </header>


    <section class="pb-5 mb-5" style="background-color: #f8fafc;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="privacy-card">
                        
                        <div class="privacy-section">
                            <h3>1. Pendahuluan</h3>
                            <p>Selamat datang di <strong>Flavory.id</strong>. Kami sangat menghargai privasi Anda dan berkomitmen penuh untuk melindungi informasi pribadi serta data bisnis Anda. Kebijakan Privasi ini menjelaskan secara transparan bagaimana kami mengumpulkan, menggunakan, menyimpan, dan melindungi informasi Anda saat menggunakan platform aplikasi kasir (Point of Sale) kami.</p>
                        </div>

                        <div class="privacy-section">
                            <h3>2. Informasi yang Kami Kumpulkan</h3>
                            <p>Untuk memberikan layanan yang optimal, kami perlu mengumpulkan beberapa jenis informasi dari Anda, antara lain:</p>
                            <ul>
                                <li><strong>Data Akun Pribadi:</strong> Nama pengguna (username), alamat email, nomor telepon genggam, dan kata sandi yang dienkripsi.</li>
                                <li><strong>Data Operasional Bisnis:</strong> Nama outlet, alamat, informasi kontak outlet, dan data produk/menu yang Anda masukkan ke dalam sistem.</li>
                                <li><strong>Data Transaksi:</strong> Rincian penjualan, data pelanggan (jika Anda mengaktifkan fitur form pelanggan), catatan pesanan, dan laporan keuangan bisnis Anda.</li>
                                <li><strong>Data Penggunaan Sistem:</strong> Alamat IP, jenis peramban (browser), waktu akses, dan aktivitas Anda di dalam platform untuk keperluan keamanan.</li>
                            </ul>
                        </div>

                        <div class="privacy-section">
                            <h3>3. Bagaimana Kami Menggunakan Data Anda</h3>
                            <p>Data yang kami kumpulkan hanya digunakan untuk keperluan yang sah dan relevan dengan layanan kami, yaitu:</p>
                            <ul>
                                <li><strong>Operasional Layanan:</strong> Memproses transaksi kasir, sinkronisasi pesanan offline ke online, dan menyajikan laporan performa bisnis Anda.</li>
                                <li><strong>Peningkatan Kualitas:</strong> Menganalisa statistik penggunaan untuk memperbaiki *bugs*, mengembangkan fitur baru, dan meningkatkan kenyamanan (*User Experience*).</li>
                                <li><strong>Komunikasi:</strong> Mengirimkan informasi penting, seperti pembaruan sistem, pemberitahuan tagihan langganan (invoice), peringatan jatuh tempo, dan layanan bantuan teknis (Customer Support).</li>
                            </ul>
                        </div>

                        <div class="privacy-section">
                            <h3>4. Perlindungan dan Keamanan Data</h3>
                            <p>Keamanan data bisnis Anda adalah prioritas kami. Flavory.id menggunakan standar keamanan teknologi terkini (seperti enkripsi kata sandi dan protokol aman) untuk mencegah akses yang tidak sah, penyalahgunaan, modifikasi, atau kebocoran informasi. Kami memastikan bahwa hanya Anda dan karyawan yang Anda beri hak akses yang dapat melihat laporan bisnis Anda.</p>
                        </div>

                        <div class="privacy-section">
                            <h3>5. Pembagian Data kepada Pihak Lain</h3>
                            <p>Kami <strong>tidak akan pernah menjual, menyewakan, atau membagikan</strong> informasi pribadi maupun data pelanggan Anda kepada pihak ketiga untuk kepentingan pemasaran pihak luar. Kami hanya akan membagikan data jika:</p>
                            <ul>
                                <li>Diwajibkan oleh proses hukum, peraturan, atau permintaan pemerintah yang berwenang.</li>
                                <li>Diperlukan oleh penyedia infrastruktur *server/hosting* yang terikat kontrak kerahasiaan dengan kami untuk menjalankan operasional sistem.</li>
                            </ul>
                        </div>

                        <div class="privacy-section">
                            <h3>6. Hak Anda Sebagai Pengguna</h3>
                            <p>Anda memiliki kendali penuh atas akun Anda. Anda berhak untuk:</p>
                            <ul>
                                <li><strong>Melihat & Memperbarui:</strong> Mengubah nama, kontak, atau informasi profil outlet kapan saja melalui pengaturan akun di *dashboard* kasir.</li>
                                <li><strong>Mengelola Akses:</strong> Menentukan informasi apa saja yang dapat diakses oleh karyawan Anda.</li>
                                <li><strong>Menghapus Akun:</strong> Anda berhak mengajukan penghapusan akun serta seluruh data transaksi bisnis dengan menghubungi tim layanan pelanggan kami.</li>
                            </ul>
                        </div>

                        <div class="privacy-section">
                            <h3>7. Penggunaan Cookies</h3>
                            <p>Flavory.id menggunakan <em>cookies</em> (file teks kecil yang disimpan di perangkat Anda) murni untuk tujuan kelancaran penggunaan sistem, seperti menyimpan sesi (*session*) agar Anda tidak perlu *login* berulang kali saat menutup dan membuka kembali halaman kasir (*browser*). Kami tidak menggunakan *cookies* untuk melacak aktivitas Anda di luar platform kami.</p>
                        </div>

                        <div class="privacy-section">
                            <h3>8. Perubahan pada Kebijakan Privasi</h3>
                            <p>Kami dapat memperbarui Kebijakan Privasi ini dari waktu ke waktu seiring dengan perkembangan layanan. Jika terdapat perubahan material (perubahan besar yang mempengaruhi hak Anda), kami akan memberikan pemberitahuan secara jelas melalui *dashboard* aplikasi atau melalui email sebelum perubahan tersebut berlaku efektif.</p>
                        </div>

                        <div class="privacy-section mb-0">
                            <h3>9. Hubungi Kami</h3>
                            <p>Jika Anda memiliki pertanyaan, keluhan, atau kekhawatiran terkait pengelolaan privasi dan data Anda, jangan ragu untuk menghubungi kami melalui:</p>
                            
                            <div class="contact-box mt-3">
                                <ul class="list-unstyled mb-0 text-dark fw-medium">
                                    <li class="mb-2"><i class="bi bi-envelope-fill text-orange me-3 fs-5 align-middle"></i> support@flavory.id</li>
                                    <li class="mb-2"><i class="bi bi-whatsapp text-orange me-3 fs-5 align-middle"></i> +62 800 0000 0000</li>
                                    <li><i class="bi bi-geo-alt-fill text-orange me-3 fs-5 align-middle"></i> Karawang, Jawa Barat, Indonesia</li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection