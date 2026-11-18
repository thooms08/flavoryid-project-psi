{{-- ══════════════════════════════════════════════════
   SECTION: Harga & Paket + Bonus Banner
   resources/views/section/paket.blade.php
══════════════════════════════════════════════════ --}}

@push('styles')
<style>
    .pricing-card {
        border-radius: 24px; border: 2px solid #f1f5f9; padding: 40px 30px;
        background: white; transition: all 0.3s; position: relative;
        height: 100%; display: flex; flex-direction: column;
    }
    .pricing-card.popular {
        border-color: var(--primary-orange);
        box-shadow: 0 20px 40px rgba(255, 106, 0, 0.15);
        transform: scale(1.05); z-index: 10;
        background: linear-gradient(to bottom, #ffffff, #fffdfa);
    }
    .badge-popular {
        position: absolute; top: -15px; left: 50%; transform: translateX(-50%);
        background: var(--primary-orange); color: white;
        padding: 6px 24px; border-radius: 20px; font-weight: 700; font-size: 0.85rem;
        box-shadow: 0 4px 10px rgba(255, 106, 0, 0.3); letter-spacing: 0.5px;
    }
    .price-strike { text-decoration: line-through; color: #94a3b8; font-size: 1.1rem; font-weight: 500; margin-right: 8px; }
    .promo-tag { background: #fee2e2; color: #ef4444; font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 6px; }

    .bonus-banner {
        background: linear-gradient(45deg, var(--primary-orange), #ff8c33);
        border-radius: 24px; color: white; position: relative; overflow: hidden;
    }

    @media (max-width: 991px) {
        .pricing-card.popular { transform: scale(1); margin-top: 2rem; margin-bottom: 2rem; }
    }
</style>
@endpush

<section id="paket" class="py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5 mx-auto animate-slide-up" style="max-width:760px;">
            <span class="text-orange fw-bold text-uppercase small"><i class="bi bi-tag-fill me-1"></i> Harga & Paket</span>
            <h2 class="fw-bold mt-2">Investasi Terbaik untuk Masa Depan Bisnis Anda</h2>
            <p class="text-muted">Semua paket mendapatkan <strong class="text-dark">SELURUH FITUR LENGKAP</strong> — POS Kasir, FlavoryAI, Prediksi Bisnis, dan masih banyak lagi. Yang berbeda hanya durasi & harga.</p>
        </div>

        <div class="row justify-content-center align-items-stretch g-4">

            {{-- Paket Trial --}}
            <div class="col-md-6 col-lg-4 animate-slide-up" style="transition-delay:.1s">
                <article class="pricing-card text-center">
                    <h3 class="h4 fw-bold text-dark mb-1">Paket Gratis</h3>
                    <p class="text-muted small mb-4">Buktikan sendiri kemudahan aplikasi kami</p>
                    <div class="mb-4">
                        <span class="fw-bold text-dark display-6">Rp 0</span>
                        <span class="text-muted d-block" style="font-size:.85rem;">/ 30 Hari</span>
                    </div>
                    <div class="text-start border-top pt-4 mb-4">
                        <p class="fw-bold text-dark small mb-2" style="letter-spacing:.3px;text-transform:uppercase;"><i class="bi bi-shop me-1 text-orange"></i> Fitur POS Kasir</p>
                        <ul class="check-list small">
                            <li><i class="bi bi-check2"></i> Kasir POS (dine-in, take away, QR order)</li>
                            <li><i class="bi bi-check2"></i> Diskon nominal/persen + Kode Voucher</li>
                            <li><i class="bi bi-check2"></i> Fitur E-Commerce (customer pesen dari rumah)</li>
                            <li><i class="bi bi-check2"></i> Cetak struk 5 jalur: bluetooth, USB, QR & WhatsApp</li>
                            <li><i class="bi bi-check2"></i> Pesanan offline &amp; sinkronisasi otomatis</li>
                            <li><i class="bi bi-check2"></i> Manajemen stok real-time + indikator tipis</li>
                            <li><i class="bi bi-check2"></i> Laporan pengeluaran operasional</li>
                            <li><i class="bi bi-check2"></i> Ikhtisar bisnis harian (omzet, order, untung)</li>
                            <li><i class="bi bi-check2"></i> Analisis menu paling laris &amp; tidak laris</li>
                            <li><i class="bi bi-check2"></i> Modal Usaha &amp; riwayat setoran</li>
                            <li><i class="bi bi-check2"></i> Pembaruan sistem otomatis (free update)</li>
                        </ul>
                    </div>
                    <div class="mt-auto">
                        <a href="{{ route('register') }}" class="btn btn-outline-dark w-100 fw-bold py-2 rounded-pill">Mulai Gratis Sekarang</a>
                    </div>
                </article>
            </div>

            {{-- Paket Bulanan --}}
            <div class="col-md-6 col-lg-4 animate-slide-up" style="transition-delay:.2s">
                <article class="pricing-card popular text-center shadow-lg">
                    <span class="badge-popular">PALING DIMINATI</span>
                    <h3 class="h4 fw-bold text-dark mb-1">Paket Eksklusif</h3>
                    <p class="text-muted small mb-3">Solusi cerdas dengan harga sangat terjangkau</p>
                    <div class="mb-2">
                        <span class="promo-tag mb-2 d-inline-block shadow-sm"><i class="bi bi-lightning-charge-fill me-1"></i> PROMO TERBATAS: DISKON 30%</span>
                    </div>
                    <div class="mb-4">
                        <span class="price-strike">Rp 100.000</span>
                        <span class="fw-bolder text-orange display-5 d-block" style="letter-spacing:-1px;">Rp 70.000</span>
                        <span class="text-muted fw-medium">/ Bulan</span>
                    </div>
                    <div class="text-start border-top pt-4 mb-4">
                        <p class="fw-bold text-dark small mb-2" style="letter-spacing:.3px;text-transform:uppercase;">Semua Fitur POS Kasir +</p>
                        <ul class="check-list small">
                            <li><i class="bi bi-check-circle-fill text-orange"></i> Kasir POS (dine-in, take away, QR order)</li>
                            <li><i class="bi bi-check-circle-fill text-orange"></i> Diskon nominal/persen + Kode Voucher</li>
                            <li><i class="bi bi-check-circle-fill text-orange"></i> Fitur E-Commerce (customer pesen dari rumah)</li>
                            <li><i class="bi bi-check-circle-fill text-orange"></i> Cetak struk 5 jalur: bluetooth, USB, QR & WhatsApp</li>
                            <li><i class="bi bi-check-circle-fill text-orange"></i> Pesanan offline &amp; sinkronisasi otomatis</li>
                            <li><i class="bi bi-check-circle-fill text-orange"></i> Manajemen stok real-time + indikator tipis</li>
                            <li><i class="bi bi-check-circle-fill text-orange"></i> Laporan pengeluaran operasional</li>
                            <li><i class="bi bi-check-circle-fill text-orange"></i> Ikhtisar bisnis harian (omzet, order, untung)</li>
                            <li><i class="bi bi-check-circle-fill text-orange"></i> Analisis menu paling laris &amp; tidak laris</li>
                            <li><i class="bi bi-check-circle-fill text-orange"></i> Modal Usaha &amp; riwayat setoran</li>
                            <li><i class="bi bi-check-circle-fill text-orange"></i> Pembaruan sistem otomatis (free update)</li>
                        </ul>
                    </div>
                    <div class="mt-auto">
                        <a href="{{ route('register') }}" class="btn btn-orange w-100 py-3 fs-5 shadow-sm fw-bold rounded-pill">Ambil Promo Bulanan</a>
                    </div>
                </article>
            </div>

            {{-- Paket Selamanya --}}
            <div class="col-md-6 col-lg-4 animate-slide-up" style="transition-delay:.3s">
                <article class="pricing-card text-center">
                    <h3 class="h4 fw-bold text-dark mb-1">Paket Super</h3>
                    <p class="text-muted small mb-4">Bayar sekali, aktif selamanya</p>
                    <div class="mb-4">
                        <span class="fw-bold text-dark display-6 d-block">Rp 689.000</span>
                        <span class="text-muted">/ Selamanya <span class="badge bg-success-subtle text-success fw-bold ms-1" style="font-size:.65rem;">Bayar Sekali</span></span>
                    </div>
                    <div class="text-start border-top pt-4 mb-4">
                        <p class="fw-bold text-dark small mb-2" style="letter-spacing:.3px;text-transform:uppercase;"><i class="bi bi-shop me-1 text-orange"></i> Semua Fitur Lengkap</p>
                        <ul class="check-list small">
                            <li><i class="bi bi-check2 text-success"></i> Kasir POS (dine-in, take away, QR order)</li>
                            <li><i class="bi bi-check2 text-success"></i> Diskon nominal/persen + Kode Voucher</li>
                            <li><i class="bi bi-check2 text-success"></i> Fitur E-Commerce (customer pesen dari rumah)</li>
                            <li><i class="bi bi-check2 text-success"></i> Cetak struk 5 jalur: bluetooth, USB, QR & WhatsApp</li>
                            <li><i class="bi bi-check2 text-success"></i> Pesanan offline &amp; sinkronisasi otomatis</li>
                            <li><i class="bi bi-check2 text-success"></i> Manajemen stok real-time + indikator tipis</li>
                            <li><i class="bi bi-check2 text-success"></i> Laporan pengeluaran operasional</li>
                            <li><i class="bi bi-check2 text-success"></i> Ikhtisar bisnis harian (omzet, order, untung)</li>
                            <li><i class="bi bi-check2 text-success"></i> Analisis menu paling laris &amp; tidak laris</li>
                            <li><i class="bi bi-check2 text-success"></i> Modal Usaha &amp; riwayat setoran</li>
                            <li><i class="bi bi-check2 text-success"></i> Pembaruan sistem otomatis (free update)</li>
                        </ul>
                    </div>
                    <div class="mt-auto">
                        <a href="{{ route('register') }}" class="btn btn-outline-success w-100 fw-bold py-2 rounded-pill">Pilih Paket Selamanya</a>
                    </div>
                </article>
            </div>

        </div>
    </div>
</section>

{{-- Bonus Banner --}}
<section class="py-5" style="background-color: #f8fafc;">
    <div class="container">
        <div class="bonus-banner p-4 p-md-5 text-center shadow-lg animate-fade-in">
            <i class="bi bi-gift-fill display-4 mb-3 d-block opacity-75"></i>
            <h2 class="fw-bold text-white mb-3">BONUS SPESIAL: PANDUAN PENGGUNAAN GRATIS!</h2>
            <p class="fs-5 mb-4 opacity-100">Kami tidak akan membiarkan Anda kebingungan. Tim kami siap memastikan usaha kuliner Anda beroperasi maksimal dengan Flavory.id.</p>

            <div class="row justify-content-center g-4 mb-4">
                <div class="col-md-5">
                    <article class="bg-white text-dark p-3 rounded-4 shadow-sm h-100 d-flex align-items-center justify-content-center">
                        <h3 class="h5 fw-bold m-0"><i class="bi bi-laptop text-orange me-2"></i> PENDAMPINGAN ONLINE<br><span class="small text-muted fw-normal fs-6">Praktis untuk seluruh area Indonesia</span></h3>
                    </article>
                </div>
                <div class="col-md-5">
                    <article class="bg-white text-dark p-3 rounded-4 shadow-sm h-100 d-flex align-items-center justify-content-center">
                        <h3 class="h5 fw-bold m-0"><i class="bi bi-people-fill text-orange me-2"></i> TRAINING TATAP MUKA<br><span class="small text-muted fw-normal fs-6">Layanan eksklusif untuk area Karawang</span></h3>
                    </article>
                </div>
            </div>

            <a href="{{ route('register') }}"
               class="btn btn-light btn-lg fw-bold text-orange rounded-pill px-5 mt-3 shadow-lg"
               style="transform: scale(1.05);"
               aria-label="Daftar Aplikasi Manajemen Restoran Flavory.id">
                Daftar Sekarang & Klaim Bonus
            </a>
        </div>
    </div>
</section>
