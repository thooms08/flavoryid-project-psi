{{-- ══════════════════════════════════════════════════
   SECTION: Fitur Utama (Ikhtisar, Kasir, Stok)
   resources/views/section/tentang.blade.php
══════════════════════════════════════════════════ --}}

@push('styles')
<style>
    /* Check List */
    .check-list { list-style: none; padding-left: 0; flex-grow: 1; }
    .check-list li { margin-bottom: 12px; display: flex; align-items: flex-start; }
    .check-list i { color: var(--primary-orange); margin-right: 12px; font-size: 1.2rem; margin-top: -2px; }

    /* Highlight Fitur */
    .feature-highlight-card {
        background: #fff;
        border-left: 4px solid var(--primary-orange);
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        transition: all 0.3s ease;
    }
    .feature-highlight-card:hover {
        box-shadow: 0 8px 25px rgba(255, 106, 0, 0.1);
        transform: translateX(5px);
    }
</style>
@endpush

<section id="fitur" class="py-5" style="background-color: #f8fafc;">
    <div class="container py-5">
        <div class="text-center mb-5 animate-slide-up">
            <span class="text-orange fw-bold tracking-wider text-uppercase small"><i class="bi bi-stars"></i> Fitur Andalan Flavory.id</span>
            <h2 class="fw-bold mt-2">Semua yang Anda Butuhkan dalam Satu Aplikasi</h2>
            <div class="mx-auto mt-3 mb-4" style="width: 80px; height: 4px; background-color: var(--primary-orange); border-radius: 2px;"></div>
        </div>

        {{-- Ikhtisar Bisnis & Data Pelanggan --}}
        <div class="row align-items-center mb-5 pb-5 border-bottom">
            <div class="col-lg-6 mb-4 mb-lg-0 animate-slide-up">
                <h3 class="fw-bold mb-3"><i class="bi bi-graph-up-arrow text-orange me-2"></i> Ikhtisar Bisnis & Data Pelanggan</h3>
                <p class="text-muted mb-4">Pantau kesehatan bisnis Anda secara menyeluruh — dari tren omzet harian hingga profil pelanggan setia, semua tersaji dalam satu tampilan yang mudah dibaca.</p>
                <ul class="check-list mt-3">
                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        <div><strong>Ikhtisar Bisnis Real-time</strong><br><span class="small text-muted">Lihat omzet, total order, keuntungan bersih, dan performa menu terlaris setiap hari secara langsung dari dashboard.</span></div>
                    </li>
                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        <div><strong>Database Pelanggan Terintegrasi</strong><br><span class="small text-muted">Simpan data pelanggan otomatis saat transaksi. Kirim struk digital ke WhatsApp dan bangun loyalitas pelanggan dengan mudah.</span></div>
                    </li>
                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        <div><strong>Analisa Performa & Prediksi</strong><br><span class="small text-muted">Grafik interaktif tren penjualan, analisis menu terlaris & jarang terjual, serta prediksi bisnis berbasis data nyata outlet Anda.</span></div>
                    </li>
                </ul>
            </div>
            <div class="col-lg-6 ps-lg-5 animate-fade-in">
                <img src="{{ asset('assets/analisis.png') }}"
                     onerror="this.src='https://placehold.co/620x420/fff4ec/ff6a00?text=Laporan+%26+Analisa'"
                     class="img-fluid rounded-4 shadow-lg border"
                     alt="Fitur Ikhtisar Bisnis, Analisa Performa, dan Data Pelanggan Flavory.id">
            </div>
        </div>

        {{-- Sistem Kasir & Manajemen Pesanan --}}
        <div class="row align-items-center mb-5 pb-5 border-bottom">
            <div class="col-lg-6 order-2 order-lg-1 pe-lg-5 animate-fade-in">
                <img src="{{ asset('assets/order.png') }}"
                     onerror="this.src='https://placehold.co/620x420/fff4ec/ff6a00?text=Sistem+Kasir+%26+Order'"
                     class="img-fluid rounded-4 shadow-lg border"
                     alt="Aplikasi Kasir Kuliner POS Flavory.id">
            </div>
            <div class="col-lg-6 order-1 order-lg-2 mb-4 mb-lg-0 animate-slide-up">
                <h3 class="fw-bold mb-3"><i class="bi bi-shop-window text-orange me-2"></i> Sistem Kasir & Manajemen Pesanan</h3>
                <p class="text-muted mb-4">Transaksi secepat kilat, baik untuk makan di tempat (dine-in), bungkus (take-away), atau order online secara mandiri melalui QR code.</p>
                <ul class="check-list mt-3">
                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        <div><strong>Pencatatan Pesanan Fleksibel</strong><br><span class="small text-muted">Mendukung input varian menu, level pedas/topping (harga dinamis), hingga split bill dengan sangat mudah.</span></div>
                    </li>
                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        <div><strong>Database Pelanggan & Struk Digital</strong><br><span class="small text-muted">Kirim struk langsung ke WhatsApp pelanggan beserta catatan kustom (Cth: Password WiFi/Promo).</span></div>
                    </li>
                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        <div><strong>Multi-Printer Support</strong><br><span class="small text-muted">Cetak struk fisik (via printer bluetooth maupun USB), dan struk digital via QR code maupun tautan WhatsApp, serta mendukung struk dapur.</span></div>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Kendali Finansial & Stok Dapur --}}
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0 animate-slide-up">
                <h3 class="fw-bold mb-3"><i class="bi bi-boxes text-orange me-2"></i> Kendali Finansial & Stok Dapur</h3>
                <p class="text-muted mb-4">Cegah kebocoran dana dan kehabisan bahan baku secara mendadak. Semua aktivitas stok dan keuangan outlet tercatat rapi.</p>
                <ul class="check-list">
                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        <div><strong>Manajemen Stok Akurat</strong><br><span class="small text-muted">Kontrol penuh atas ketersediaan barang. Indikator pintar akan memberi warna merah saat bahan baku menipis atau habis.</span></div>
                    </li>
                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        <div><strong>Laporan Arus Kas Otomatis</strong><br><span class="small text-muted">Sistem merekap pemasukan dan pengeluaran, serta menghitung keuntungan bersih harian langsung di dalam aplikasi.</span></div>
                    </li>
                </ul>
            </div>
            <div class="col-lg-6 ps-lg-5 animate-fade-in">
                <img src="{{ asset('assets/stok.png') }}"
                     onerror="this.src='https://placehold.co/620x420/fff4ec/ff6a00?text=Manajemen+Stok'"
                     class="img-fluid rounded-4 shadow-lg border"
                     alt="Fitur Manajemen Stok Barang Restoran Flavory.id">
            </div>
        </div>

    </div>
</section>
