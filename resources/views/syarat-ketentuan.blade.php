{{-- resources/views/ketentuan-persyaratan.blade.php --}}
@extends('layouts.app')

@section('title', 'Syarat & Ketentuan Layanan - Flavory.id & Key.Flavory.id (HRMS)')
@section('meta_description', 'Baca Syarat dan Ketentuan penggunaan Flavory.id dan Key.Flavory.id. Mencakup layanan kasir digital POS dan HRMS (Human Resource Management System) untuk bisnis kuliner Indonesia.')
@section('meta_og_type', 'website')
@section('meta_og_url', route('syarat.ketentuan'))
@section('meta_image', secure_url('assets/preview.jpg'))

@push('styles')

<style>
    :root {
        --primary-orange: #ff6a00;
        --dark-orange: #e65f00;
        --light-orange: #fff4e6;
        --text-dark: #1e293b;
        --text-muted: #64748b;
    }

    .terms-hero {
        padding: 140px 0 80px;
        background: linear-gradient(135deg, var(--light-orange) 0%, #ffffff 100%);
        border-bottom: 1px solid #f8fafc;
    }

    .terms-content {
        background-color: #ffffff;
        border-radius: 24px;
        padding: 50px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.05);
    }

    .terms-content h2 {
        color: var(--text-dark);
        font-weight: 700;
        font-size: 1.5rem;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--light-orange);
    }

    .terms-content p, .terms-content li {
        color: var(--text-muted);
        line-height: 1.8;
        font-size: 1.05rem;
        margin-bottom: 1rem;
    }

    .terms-content ul, .terms-content ol {
        margin-bottom: 1.5rem;
        padding-left: 1.5rem;
    }

    .terms-content li::marker {
        color: var(--primary-orange);
        font-weight: bold;
    }

    .last-updated {
        display: inline-block;
        background-color: #f1f5f9;
        color: var(--text-muted);
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 2rem;
    }
</style>

<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "WebPage",
  "name": "Syarat dan Ketentuan Layanan Aplikasi Flavory.id",
  "description": "Syarat dan ketentuan operasional serta penggunaan layanan kasir digital Flavory.id untuk pemilik bisnis F&B.",
  "publisher": {
    "@@type": "Organization",
    "name": "Flavory.id",
    "logo": {
      "@@type": "ImageObject",
      "url": "{{ asset('logo-512.png') }}"
    }
  }
}
</script>
@endpush

@section('content')
    <main>
        <header class="terms-hero text-center">
            <div class="container">
                <span class="badge bg-white text-orange px-3 py-2 rounded-pill mb-3 fw-bold border border-warning-subtle shadow-sm">
                    <i class="bi bi-file-earmark-text-fill me-1"></i> Kebijakan Layanan
                </span>
                <h1 class="display-4 fw-bolder mb-3 text-dark">Syarat & Ketentuan</h1>
                <p class="lead text-muted mx-auto" style="max-width: 700px;">
                    Ketentuan penggunaan ini mengatur hak dan kewajiban Anda saat mengakses dan menggunakan platform Aplikasi Flavory.id.
                </p>
            </div>
        </header>

        <section class="py-5" style="background-color: #f8fafc;">
            <div class="container py-4">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <article class="terms-content">
                            <div class="text-center">
                                <span class="last-updated"><i class="bi bi-clock-history me-2"></i>Pembaruan Terakhir: 7 April 2026</span>
                            </div>

                            <p>Selamat datang di <strong>Flavory.id</strong> dan <strong>Key.Flavory.id</strong>. Syarat dan Ketentuan ini ("Ketentuan") merupakan perjanjian hukum antara Anda ("Pengguna" atau "Pelanggan") dan Flavory.id ("Kami") mengenai penggunaan platform kami yang terdiri dari:</p>
                            <ul>
                                <li><strong>Flavory.id</strong> - aplikasi kasir (Point of Sale / POS) untuk operasional outlet F&B.</li>
                                <li><strong>Key.Flavory.id</strong> - platform HRMS (Human Resource Management System) untuk owner dan manager, mencakup manajemen karyawan, shift, absensi, modal usaha, dan laporan bisnis.</li>
                            </ul>
                            <p>Dengan mendaftar atau menggunakan salah satu platform kami, Anda menyatakan telah membaca, memahami, dan menyetujui Ketentuan ini.</p>

                            <h2>1. Definisi Layanan</h2>
                            <p>Flavory.id adalah ekosistem digital untuk bisnis kuliner (Food & Beverage) yang terdiri dari dua platform:</p>
                            <ul>
                                <li><strong>Flavory.id (POS Kasir)</strong> - Aplikasi kasir berbasis web untuk mencatat transaksi, manajemen stok, mencetak struk, laporan performa, prediksi bisnis, dan MineAI konsultan bisnis.</li>
                                <li><strong>Key.Flavory.id (HRMS)</strong> - Platform manajemen tim dan bisnis untuk owner/manager: data karyawan, manajemen shift, rekap absensi, modal usaha, laporan bisnis, dan MineAI Manager.</li>
                            </ul>

                            <h2>2. Pendaftaran dan Keamanan Akun</h2>
                            <ol>
                                <li>Anda wajib memberikan informasi pendaftaran yang akurat, lengkap, dan terkini.</li>
                                <li>Anda bertanggung jawab penuh atas keamanan kata sandi (password) dan semua aktivitas yang terjadi di bawah akun Anda.</li>
                                <li>Satu akun berlangganan berlaku untuk satu outlet/cabang, kecuali disepakati lain dalam paket layanan yang Anda pilih.</li>
                                <li>Kami berhak menangguhkan atau membatalkan akun jika ditemukan penyalahgunaan, aktivitas mencurigakan, atau pelanggaran terhadap Ketentuan ini.</li>
                            </ol>

                            <h2>3. Penggunaan yang Diizinkan</h2>
                            <p>Anda setuju untuk menggunakan Aplikasi Flavory.id hanya untuk tujuan bisnis yang sah. Anda dilarang keras untuk:</p>
                            <ul>
                                <li>Menggunakan layanan untuk aktivitas ilegal, penipuan, atau pencucian uang.</li>
                                <li>Melakukan rekayasa balik (*reverse engineering*), menyalin, atau mendistribusikan kode sumber Aplikasi Flavory.id tanpa izin tertulis dari pihak kami.</li>
                                <li>Mencoba mendapatkan akses tidak sah ke sistem internal, *database*, atau akun pengguna lain.</li>
                            </ul>

                            <h2>4. Berlangganan dan Pembayaran</h2>
                            <ol>
                                <li>Flavory.id menawarkan masa uji coba (Trial) gratis selama 7 hari. Setelah masa trial berakhir, Anda diwajibkan untuk berlangganan agar tetap dapat mengakses fitur premium.</li>
                                <li>Biaya berlangganan ditagihkan sesuai dengan siklus yang Anda pilih (Bulanan atau Tahunan). Pembayaran tidak dapat dikembalikan (*non-refundable*) untuk siklus yang sedang berjalan.</li>
                                <li>Jika Anda gagal melakukan pembayaran perpanjangan langganan, kami berhak mengubah status akun Anda menjadi "Non-Aktif", yang akan membatasi akses Anda ke fitur transaksi.</li>
                            </ol>

                            <h2>5. Data Pengguna dan Privasi</h2>
                            <p>Kami sangat menghargai privasi data bisnis Anda (termasuk data transaksi, laporan keuangan, dan menu). Data yang Anda masukkan ke dalam Flavory.id adalah milik Anda sepenuhnya. Kami hanya akan memproses data tersebut untuk keperluan fungsionalitas sistem, analisis internal, dan peningkatan layanan. Untuk detail lebih lanjut, silakan merujuk pada halaman <a href="{{ route('kebijakan.privasi') }}" class="text-orange text-decoration-none fw-bold">Kebijakan Privasi</a> kami.</p>

                            <h2>6. Ketersediaan Layanan (Uptime) & Mode Offline</h2>
                            <p>Meskipun kami berupaya memastikan ketersediaan server 99.9%, kami tidak menjamin bahwa layanan akan selalu bebas dari gangguan atau *maintenance*. Aplikasi kami dilengkapi dengan fitur <strong>Mode Offline</strong> yang memungkinkan Anda mencatat transaksi tanpa internet. Namun, Anda bertanggung jawab untuk melakukan sinkronisasi data ketika perangkat kembali terhubung ke internet agar data masuk ke *cloud*.</p>

                            <h2>7. Batasan Tanggung Jawab</h2>
                            <p>Sejauh diizinkan oleh hukum yang berlaku di Indonesia, Flavory.id tidak bertanggung jawab atas kerugian tidak langsung, insidental, kerugian laba/omzet, atau hilangnya data yang timbul akibat kegagalan perangkat keras Anda, koneksi internet, maupun kesalahan operasional kasir Anda.</p>

                            <h2>8. Perubahan Ketentuan</h2>
                            <p>Kami berhak untuk mengubah, memodifikasi, atau memperbarui Syarat dan Ketentuan ini kapan saja. Jika ada perubahan signifikan, kami akan memberitahukan Anda melalui email atau pengumuman di dalam Dashboard Kasir. Penggunaan Anda yang berkelanjutan setelah perubahan tersebut dianggap sebagai persetujuan terhadap ketentuan yang baru.</p>

                            <h2>9. Kontak dan Layanan Bantuan</h2>
                            <p>Jika Anda memiliki pertanyaan, keluhan, atau membutuhkan bantuan teknis terkait Syarat dan Ketentuan ini, silakan hubungi tim kami melalui:</p>
                            <ul>
                                <li><strong>Email:</strong> adminku@flavory.id</li>
                                <li><strong>WhatsApp:</strong> +62 8579 7574 754</li>
                            </ul>

                        </article>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection