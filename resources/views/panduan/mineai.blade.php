{{-- resources/views/panduan/mineai.blade.php --}}
@extends('layouts.app')

@section('title', 'Panduan Analisis & Strategi Mine AI | Flavory.id')
@section('meta_description', 'Pelajari cara membaca analisis cerdas dan strategi bisnis yang dihasilkan oleh Mine AI di Flavory.id. Optimalkan omzet dan kurangi risiko operasional Anda.')

@push('styles')
<style>
    :root {
        --mine-orange: #ff6a00;
        --mine-pink: #ee0979;
        --mine-purple: #c850c0;
        --light-gradient-bg: #fff5f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --success-green: #10b981;
    }

    /* Latar Belakang & Layout Utama */
    .guide-page {
        background-color: #f8fafc;
        min-height: 100vh;
        padding: 120px 0 80px;
    }

    /* Header Panduan dengan Gradasi Mine AI */
    .guide-header {
        margin-bottom: 40px;
    }
    
    .mine-text-gradient {
        background: linear-gradient(135deg, var(--mine-orange), var(--mine-pink), var(--mine-purple));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 800;
    }

    .guide-header h1 {
        font-weight: 900;
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
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, var(--mine-orange), var(--mine-pink));
        border-radius: 2px;
    }

    /* Mine AI Replica Card for Guide Explanation */
    .replica-card {
        border-radius: 24px;
        background: linear-gradient(135deg, #fff5f0 0%, #fff0f6 40%, #fdf0ff 100%);
        border: 2px solid rgba(255, 106, 0, 0.15);
        box-shadow: 0 15px 35px rgba(238, 9, 121, 0.08);
        padding: 25px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
    }

    .replica-badge {
        background: linear-gradient(135deg, var(--mine-orange), var(--mine-pink), var(--mine-purple));
        color: white;
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .replica-item-highlight {
        border: 2px dashed var(--mine-pink);
        background-color: rgba(238, 9, 121, 0.03);
        border-radius: 14px;
        padding: 15px;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .replica-item-highlight:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(238, 9, 121, 0.06);
        background-color: rgba(238, 9, 121, 0.05);
    }

    .replica-num {
        position: absolute;
        top: -10px;
        left: 15px;
        background: linear-gradient(135deg, var(--mine-orange), var(--mine-pink));
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
        box-shadow: 0 3px 8px rgba(238, 9, 121, 0.3);
    }

    /* Explanation Panels */
    .explain-box {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 20px;
        height: 100%;
        transition: all 0.3s ease;
    }

    .explain-box:hover {
        border-color: var(--mine-pink);
        box-shadow: 0 10px 20px rgba(238, 9, 121, 0.04);
        transform: translateY(-2px);
    }

    .explain-icon {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, rgba(255, 106, 0, 0.1), rgba(238, 9, 121, 0.1));
        color: var(--mine-pink);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 15px;
    }

    /* Step guide card */
    .step-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 25px;
        position: relative;
    }

    .step-badge {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, var(--mine-orange), var(--mine-pink));
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin-bottom: 15px;
    }

    /* Custom chip styled for guide */
    .guide-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 50px;
        background: white;
        border: 1px solid rgba(255, 106, 0, 0.15);
        color: #4a2020;
        font-size: 0.78rem;
        font-weight: 600;
        box-shadow: 0 2px 6px rgba(255, 106, 0, 0.05);
    }

    /* Warning Alert */
    .warn-alert {
        background-color: #fffaf0;
        border: 1px solid #fed7aa;
        border-left: 5px solid var(--mine-orange);
        border-radius: 1rem;
        padding: 20px;
    }

    /* Tips & Strategy Box */
    .strategy-box {
        background: linear-gradient(135deg, #fff5f0, #fff0f6);
        border: 1px solid rgba(238, 9, 121, 0.15);
        border-left: 6px solid var(--mine-pink);
        border-radius: 1rem;
        padding: 25px;
    }

    .strategy-list li {
        margin-bottom: 12px;
        color: var(--text-dark);
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

            <!-- Sidebar -->
            <div class="col-lg-3 mb-4 mb-lg-0">
                @include('panduan.sidebar')
            </div>

            <!-- Content Area -->
            <div class="col-lg-9">
                
                <header class="guide-header">
                    <span class="badge bg-white border border-danger-subtle px-3 py-2 rounded-pill mb-3 shadow-sm">
                        <span class="mine-text-gradient"><i class="bi bi-stars me-1" aria-hidden="true"></i> Kecerdasan Buatan (AI)</span>
                    </span>
                    <h1>Panduan Lengkap MineAI — Konsultan Bisnis AI Flavory.id</h1>
                    <p class="text-muted mt-3 fs-5">
                        MineAI bukan sekadar dashboard statis yang menampilkan angka. MineAI adalah <strong>asisten AI interaktif</strong> yang membaca data transaksi nyata outlet kamu, memberikan insight otomatis, lalu bisa <strong>diajak ngobrol langsung via chatbox</strong> — layaknya konsultan bisnis pribadi yang selalu siap kapan saja.
                    </p>
                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <span class="guide-chip"><i class="bi bi-graph-up-arrow" style="color:var(--mine-orange)"></i> Insight Otomatis dari Data Real</span>
                        <span class="guide-chip"><i class="bi bi-chat-dots-fill" style="color:var(--mine-pink)"></i> Chat AI Dinamis & Interaktif</span>
                        <span class="guide-chip"><i class="bi bi-clock-history" style="color:var(--mine-orange)"></i> Riwayat Percakapan Tersimpan</span>
                        <span class="guide-chip"><i class="bi bi-shield-check" style="color:var(--mine-pink)"></i> Konteks Bisnis Selalu Diingat AI</span>
                    </div>
                </header>

                <!-- Dua Mode MineAI -->
                <section class="mb-5">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="explain-box h-100" style="border-left:4px solid var(--mine-orange);">
                                <div class="explain-icon"><i class="bi bi-bar-chart-line-fill"></i></div>
                                <h3 class="h6 fw-bold text-dark mb-2">Mode 1: Insight Otomatis</h3>
                                <p class="text-muted small mb-0">
                                    Klik <strong>"Minta Insight MineAI"</strong> di halaman Performa Bisnis. MineAI langsung membaca data 30 hari terakhir — omzet, menu terlaris, jam puncak, tren pertumbuhan — lalu menyusun analisis lengkap dalam hitungan detik. Tidak perlu input manual apapun.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="explain-box h-100" style="border-left:4px solid var(--mine-pink);">
                                <div class="explain-icon" style="color:var(--mine-pink);"><i class="bi bi-chat-dots-fill"></i></div>
                                <h3 class="h6 fw-bold text-dark mb-2">Mode 2: Chat AI Interaktif</h3>
                                <p class="text-muted small mb-0">
                                    Setelah insight muncul, klik <strong>"Mulai Ngobrol dengan MineAI"</strong>. Kamu bisa tanya apa saja — strategi promo, cara atasi penurunan omzet, analisa menu, bahkan sekadar curhat soal bisnis. MineAI ingat konteks bisnismu dan menjawab secara personal, bukan jawaban generik.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 p-3 rounded-3" style="background:linear-gradient(135deg,rgba(255,106,0,.06),rgba(238,9,121,.04));border:1px solid rgba(255,106,0,.15);">
                        <p class="small mb-0 text-dark">
                            <i class="bi bi-lightbulb-fill me-2" style="color:var(--mine-orange)"></i>
                            <strong>Tips:</strong> Riwayat setiap sesi chat tersimpan otomatis. Kamu bisa kembali ke percakapan sebelumnya kapan saja lewat sidebar <em>Riwayat Chat</em> di halaman MineAI.
                        </p>
                    </div>
                </section>

                <!-- Replica Showcase of Mine AI Card -->
                <section class="mb-5">
                    <h2 class="section-title">Anatomi Panel Insight MineAI</h2>
                    <p class="text-muted mb-4">
                        Berikut adalah visualisasi struktur panel informasi Mine AI yang akan Anda temui di bagian bawah halaman <strong>Performa Bisnis</strong>:
                    </p>

                    <div class="replica-card">
                        <!-- Header Replica -->
                        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-white border border-warning-subtle rounded-3 p-2 d-flex align-items-center justify-content-center" style="width:36px; height:36px;">
                                    <i class="bi bi-cpu-fill" style="color: var(--mine-orange);"></i>
                                </div>
                                <span class="fw-bold text-dark">Insight &amp; Saran Strategi <span class="mine-text-gradient">MineAI</span></span>
                            </div>
                            <span class="replica-badge"><i class="bi bi-stars"></i> Minta Insight MineAI</span>
                        </div>

                        <!-- Warning Alert Replica -->
                        <div class="d-flex align-items-start gap-2 mb-3 p-2 rounded-3 border bg-white-50" style="font-size:0.75rem; border-color: rgba(255, 106, 0, 0.15);">
                            <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                            <span class="text-muted">Analisa ini di-generate berdasarkan kalkulasi matematis transaksi...</span>
                        </div>

                        <div class="row g-3">
                            <!-- 1. Headline & Confidence -->
                            <div class="col-12 mb-2">
                                <div class="replica-item-highlight">
                                    <div class="replica-num">1</div>
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mt-1">
                                        <div class="fst-italic text-secondary small">
                                            "MineAI mendeteksi momentum positif yang perlu segera diperkuat."
                                        </div>
                                        <div style="min-width: 120px;">
                                            <div class="d-flex justify-content-between mb-1" style="font-size: 0.65rem;">
                                                <span class="text-muted fw-bold">CONFIDENCE</span>
                                                <span class="fw-bold text-danger">85%</span>
                                            </div>
                                            <div class="progress" style="height: 4px;">
                                                <div class="progress-bar bg-danger" style="width: 85%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Mengapa Demikian -->
                            <div class="col-md-4">
                                <div class="replica-item-highlight h-100">
                                    <div class="replica-num">2</div>
                                    <div class="fw-bold text-dark mb-1 small" style="color: var(--mine-orange) !important;">
                                        <i class="bi bi-search me-1"></i> Mengapa Demikian?
                                    </div>
                                    <div class="text-muted" style="font-size:0.72rem; line-height: 1.4;">
                                        Selama Periode Ini, tercatat 45 transaksi dengan omzet Rp 2.450.000. Dibanding Periode Sebelumnya, omzet naik 15.4%...
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Risiko & Potensi -->
                            <div class="col-md-4">
                                <div class="replica-item-highlight h-100">
                                    <div class="replica-num">3</div>
                                    <div class="fw-bold text-dark mb-1 small" style="color: #059669 !important;">
                                        <i class="bi bi-shield-exclamation me-1"></i> Potensi &amp; Keuntungan
                                    </div>
                                    <div class="text-muted" style="font-size:0.72rem; line-height: 1.4;">
                                        Momentum positif ini berisiko terhenti jika stok menu terlaris tidak diamankan lebih awal. Tekanan diskon terkendali...
                                    </div>
                                </div>
                            </div>

                            <!-- 4. Solusi & Eksekusi -->
                            <div class="col-md-4">
                                <div class="replica-item-highlight h-100">
                                    <div class="replica-num">4</div>
                                    <div class="fw-bold text-dark mb-1 small" style="color: var(--mine-purple) !important;">
                                        <i class="bi bi-lightbulb-fill me-1"></i> Solusi &amp; Eksekusi
                                    </div>
                                    <div class="text-muted" style="font-size:0.72rem; line-height: 1.4;">
                                        Amankan stok menu terlaris 2-3 hari ke depan agar momentum tidak terhenti. Fokuskan promo upsell di jam puncak...
                                    </div>
                                </div>
                            </div>

                            <!-- 5. Signal Chips -->
                            <div class="col-12 mt-2">
                                <div class="replica-item-highlight">
                                    <div class="replica-num">5</div>
                                    <div class="d-flex flex-wrap gap-2 mt-1">
                                        <span class="guide-chip"><i class="bi bi-lightning-charge text-warning"></i> Avg/Trx: Rp 54.444</span>
                                        <span class="guide-chip"><i class="bi bi-calendar3 text-danger"></i> 3.5 order/hari</span>
                                        <span class="guide-chip"><i class="bi bi-percent text-success"></i> Diskon: 2.1%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Explanation of components -->
                <section class="mb-5">
                    <h2 class="section-title">Penjelasan Setiap Komponen Analisis</h2>
                    <p class="text-muted mb-4">
                        Mine AI membagi analisisnya menjadi 5 bagian penting untuk mempermudah Anda melakukan evaluasi operasional:
                    </p>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="explain-box">
                                <div class="explain-icon"><i class="bi bi-percent"></i></div>
                                <h3 class="h6 fw-bold text-dark mb-2">1. Headline &amp; Skor Confidence</h3>
                                <p class="text-muted small mb-0">
                                    <strong>Headline</strong> memberikan ringkasan cepat mengenai kondisi bisnis Anda (naik, turun, atau stabil). 
                                    <strong>Skor Confidence</strong> menunjukkan tingkat keandalan rekomendasi AI. Semakin banyak transaksi yang tercatat, semakin tinggi persentase akurasinya (hingga 97%).
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="explain-box">
                                <div class="explain-icon"><i class="bi bi-search"></i></div>
                                <h3 class="h6 fw-bold text-dark mb-2">2. Mengapa Demikian? (Analisis Penyebab)</h3>
                                <p class="text-muted small mb-0">
                                    Menjabarkan alasan matematis di balik kondisi keuangan saat ini. Panel ini menghubungkan data volume transaksi, rata-rata belanja konsumen (average ticket size), tingkat penjualan menu terlaris, serta kontribusi persentase penjualan menu tertentu.
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="explain-box">
                                <div class="explain-icon"><i class="bi bi-shield-exclamation"></i></div>
                                <h3 class="h6 fw-bold text-dark mb-2">3. Risiko Terburuk atau Potensi Keuntungan</h3>
                                <p class="text-muted small mb-0">
                                    Jika grafik Anda <strong>menurun</strong>, panel ini memperingatkan bahaya operasional seperti ancaman arus kas dan pembengkakan sisa stok. Jika <strong>meningkat</strong>, bagian ini mengidentifikasi cara menjaga momentum agar tidak terjadi kehabisan stok bahan baku menu terlaris.
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="explain-box">
                                <div class="explain-icon"><i class="bi bi-lightbulb-fill"></i></div>
                                <h3 class="h6 fw-bold text-dark mb-2">4. Solusi &amp; Eksekusi Nyata</h3>
                                <p class="text-muted small mb-0">
                                    Rencana aksi operasional taktis yang dirancang khusus sesuai tren penjualan Anda. Meliputi rekomendasi program promo, alokasi jadwal kerja staf outlet, pengamanan rantai pasokan menu favorit, hingga inovasi rotasi menu yang tidak efektif.
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="explain-box">
                                <div class="explain-icon"><i class="bi bi-cpu-fill"></i></div>
                                <h3 class="h6 fw-bold text-dark mb-2">5. Signal Chips (Data Pendukung Cepat)</h3>
                                <p class="text-muted small mb-0">
                                    Kumpulan indikator performa utama (KPI) yang dihitung secara berkala:
                                </p>
                                <ul class="text-muted small mt-2 ps-3">
                                    <li><strong>Avg/Trx:</strong> Rata-rata pengeluaran pelanggan dalam satu kali pemesanan (Average Ticket Size).</li>
                                    <li><strong>Order/Hari:</strong> Intensitas frekuensi transaksi per hari.</li>
                                    <li><strong>Diskon:</strong> Persentase nilai diskon yang dikeluarkan dibanding total omzet kotor.</li>
                                    <li><strong>Top3 Share:</strong> Persentase kontribusi penjualan 3 menu teratas dari total porsi yang terjual.</li>
                                    <li><strong>Jam Puncak:</strong> Rentang jam tersibuk dengan jumlah antrean transaksi tertinggi.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Step-by-Step Guideline -->
                <section class="mb-5">
                    <h2 class="section-title">Langkah Menggunakan Analisis Mine AI</h2>
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="step-card h-100">
                                <div class="step-badge">1</div>
                                <h3 class="h6 fw-bold text-dark mb-2">Filter Periode Waktu</h3>
                                <p class="text-muted small mb-0">
                                    Tentukan rentang filter waktu analisis di bagian atas halaman (1 Hari, 1 Minggu, 1 Bulan, 1 Tahun, atau Custom).
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="step-card h-100">
                                <div class="step-badge">2</div>
                                <h3 class="h6 fw-bold text-dark mb-2">Minta Insight & Mulai Chat</h3>
                                <p class="text-muted small mb-0">
                                    Scroll ke bagian <strong>MineAI</strong>, tekan <strong>"Minta Insight MineAI"</strong>. Setelah insight muncul, klik <strong>"Mulai Ngobrol"</strong> untuk konsultasi langsung dengan AI secara interaktif.
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="step-card h-100">
                                <div class="step-badge">3</div>
                                <h3 class="h6 fw-bold text-dark mb-2">Evaluasi &amp; Eksekusi</h3>
                                <p class="text-muted small mb-0">
                                    Baca saran aksi pada bagian <strong>Solusi &amp; Eksekusi</strong>, diskusikan bersama tim operasional, lalu mulai terapkan di outlet Anda.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <div class="warn-alert d-flex align-items-start gap-3">
                        <i class="bi bi-exclamation-triangle-fill fs-3" style="color: var(--mine-orange);" aria-hidden="true"></i>
                        <div>
                            <h3 class="h5 fw-bold text-dark mb-1">Batasan Analisis AI</h3>
                            <p class="text-muted small mb-0">
                                Analisis Mine AI dihitung murni secara matematis dari riwayat transaksi yang masuk ke sistem Flavory.id. AI tidak mengetahui faktor eksternal (seperti cuaca ekstrem, renovasi jalan di depan toko, event festival daerah, atau kampanye kompetitor). Gunakan analisis ini sebagai <strong>rekomendasi pendukung</strong>, bukan satu-satunya penentu keputusan mutlak.
                            </p>
                        </div>
                    </div>
                </section>

                <section class="mb-2">
                    <aside class="strategy-box">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="bg-white p-2 rounded-circle shadow-sm">
                                <i class="bi bi-lightbulb-fill fs-3" style="color: var(--mine-pink);" aria-hidden="true"></i>
                            </div>
                            <h2 class="h4 fw-bold text-dark mb-0">Tips Mengoptimalkan Rekomendasi Mine AI</h2>
                        </div>
                        <p class="text-muted mb-3" style="font-size: 1.05rem;">
                            Pastikan Anda mendapatkan kualitas analisis terbaik dari Mine AI dengan mengikuti panduan operasional berikut:
                        </p>
                        <ul class="strategy-list list-unstyled ps-2 mb-0">
                            <li class="d-flex align-items-start gap-2">
                                <i class="bi bi-check-circle-fill mt-1" style="color: var(--mine-pink);"></i>
                                <span><strong>Pencatatan Transaksi Real-time:</strong> Biasakan kasir langsung mencatat pesanan saat transaksi terjadi agar jam puncak terhitung secara presisi.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="bi bi-check-circle-fill mt-1" style="color: var(--mine-pink);"></i>
                                <span><strong>Penerapan Sistem Diskon:</strong> Masukkan nilai diskon dengan benar pada form pesanan agar Mine AI dapat menghitung rasio diskon terhadap profitabilitas secara akurat.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="bi bi-check-circle-fill mt-1" style="color: var(--mine-pink);"></i>
                                <span><strong>Konsistensi Data:</strong> Hindari menghapus transaksi yang sah demi menjaga keaslian visualisasi grafik perbandingan omzet antarpotongan waktu.</span>
                            </li>
                        </ul>
                    </aside>
                </section>

            </div>
        </div>
    </div>
</main>
@endsection
