@extends('layouts.app')
@section('title', 'Panduan MineAI Manager — Key.Flavory.id HRMS')
@push('styles')
<meta name="description" content="Cara menggunakan MineAI di dasbor manager Key.Flavory.id untuk mendapatkan insight bisnis dan berkonsultasi dengan asisten AI berbasis data outlet.">
@include('panduan.hrms._style')
@endpush
@section('content')
<main class="guide-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 mb-4 mb-lg-0">@include('panduan.sidebar')</div>
            <div class="col-lg-9">
                <header class="guide-header mb-5">
                    <span class="badge px-3 py-2 rounded-pill mb-3 fw-bold shadow-sm"
                          style="background:var(--hrms-light);color:var(--hrms-green);border:1px solid rgba(25,135,84,.2);">
                        <i class="bi bi-people-fill me-1"></i> HRMS — Key.Flavory.id
                    </span>
                    <h1>MineAI Manager</h1>
                    <p class="mt-2">Konsultan bisnis AI yang membaca data nyata outlet Anda dan memberikan saran strategis langsung dari dasbor manager.</p>
                </header>

                <section>
                    <ol class="timeline">
                        <li class="timeline-item">
                            <div class="timeline-icon">1</div>
                            <article class="guide-card">
                                <h2>Membuka MineAI Manager</h2>
                                <ul class="step-list">
                                    <li>Dari dasbor, klik card atau menu <strong>"MineAI"</strong>.</li>
                                    <li>Anda akan masuk ke halaman chat MineAI khusus dasbor manager.</li>
                                    <li>Riwayat sesi chat sebelumnya ditampilkan di sidebar kiri.</li>
                                </ul>
                            </article>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-icon">2</div>
                            <article class="guide-card">
                                <h2>Meminta Insight Awal</h2>
                                <ul class="step-list">
                                    <li>Klik tombol <strong>"Minta Insight MineAI"</strong> di halaman utama chat.</li>
                                    <li>MineAI akan menganalisis data 30 hari terakhir outlet Anda secara otomatis.</li>
                                    <li>Insight yang ditampilkan mencakup: omzet, pertumbuhan, menu terlaris, jam puncak, hari tersibuk, dan stok tipis.</li>
                                </ul>
                                <div class="alert-hrms">
                                    <i class="bi bi-stars"></i>
                                    <div>
                                        <p>Insight bersumber dari data transaksi kasir yang nyata.</p>
                                        <span>Semakin lengkap data kasir Flavory.id Anda, semakin akurat insight yang diberikan MineAI.</span>
                                    </div>
                                </div>
                            </article>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-icon">3</div>
                            <article class="guide-card">
                                <h2>Mulai Berkonsultasi</h2>
                                <ul class="step-list">
                                    <li>Setelah insight muncul, klik <strong>"Mulai Ngobrol dengan MineAI"</strong>.</li>
                                    <li>Ketik pertanyaan Anda di kolom chat, lalu tekan Enter atau klik tombol kirim.</li>
                                    <li>MineAI akan menjawab berdasarkan data bisnis dan konteks outlet Anda secara spesifik.</li>
                                </ul>
                            </article>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-icon">4</div>
                            <article class="guide-card">
                                <h2>Contoh Pertanyaan ke MineAI</h2>
                                <ul class="step-list">
                                    <li><em>"Menu apa yang paling menguntungkan untuk difokuskan bulan ini?"</em></li>
                                    <li><em>"Bagaimana cara meningkatkan omzet di hari Senin yang biasanya sepi?"</em></li>
                                    <li><em>"Kapan waktu terbaik untuk menjalankan promo diskon?"</em></li>
                                    <li><em>"Stok apa yang perlu saya restock segera?"</em></li>
                                    <li><em>"Apakah tren bisnis saya sedang naik atau turun?"</em></li>
                                </ul>
                            </article>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-icon">5</div>
                            <article class="guide-card">
                                <h2>Mengelola Riwayat Sesi Chat</h2>
                                <ul class="step-list">
                                    <li>Setiap percakapan tersimpan otomatis sebagai sesi terpisah di sidebar kiri.</li>
                                    <li>Klik nama sesi untuk melanjutkan percakapan sebelumnya.</li>
                                    <li>Klik ikon <strong>tempat sampah</strong> pada sesi untuk menghapusnya jika tidak diperlukan.</li>
                                    <li>Klik <strong>"Chat Baru"</strong> untuk memulai sesi konsultasi baru dari awal.</li>
                                </ul>
                                <div class="alert-hrms">
                                    <i class="bi bi-lightning-charge-fill"></i>
                                    <div>
                                        <p>Kuota MineAI Manager: 500 pesan per hari.</p>
                                        <span>Kuota direset otomatis setiap hari pukul 00:00 WIB. Indikator kuota tersedia di bagian atas area chat.</span>
                                    </div>
                                </div>
                            </article>
                        </li>
                    </ol>

                    <div class="closing-box mt-5">
                        <i class="bi bi-stars" style="font-size:2.5rem;opacity:.9;" aria-hidden="true"></i>
                        <h3 class="fw-bold mt-3 mb-2">Siap Kelola Tim & Bisnis Lebih Cerdas?</h3>
                        <p class="mb-4 text-light" style="font-size:1.05rem;opacity:.9;">
                            Akses Key.Flavory.id sekarang dan rasakan kemudahan manajemen karyawan terintegrasi penuh dengan data kasir outlet Anda.
                        </p>
                        <a href="{{ rtrim(config('app.key_flavory_url', env('KEYFLAVORYID_URL','http://127.0.0.1:8001')), '/') }}/login"
                           class="btn btn-light fw-bold rounded-pill px-4 py-2 shadow-sm"
                           style="color:var(--hrms-green);">
                            <i class="bi bi-person-badge-fill me-1"></i> Buka Dasbor Manager
                        </a>
                    </div>
                </section>

                <nav class="guide-nav">
                    <a href="{{ route('panduan.hrms.laporan-bisnis') }}"><i class="bi bi-arrow-left"></i> Sebelumnya: Laporan Bisnis</a>
                    <a href="{{ route('panduan.hrms') }}">Kembali ke Overview HRMS <i class="bi bi-arrow-right"></i></a>
                </nav>
            </div>
        </div>
    </div>
</main>
@endsection
