@extends('layouts.app')
@section('title', 'Panduan Laporan Bisnis & Pengeluaran — Key.Flavory.id HRMS')
@push('styles')
<meta name="description" content="Cara memantau laporan omzet, keuntungan, kerugian, dan pengeluaran outlet dari dasbor manager Key.Flavory.id.">
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
                    <h1>Laporan Bisnis & Pengeluaran</h1>
                    <p class="mt-2">Pantau omzet, keuntungan, kerugian, dan pengeluaran outlet secara real-time langsung dari dasbor manager.</p>
                </header>

                <section>
                    <ol class="timeline">
                        <li class="timeline-item">
                            <div class="timeline-icon">1</div>
                            <article class="guide-card">
                                <h2>Membuka Laporan Bisnis</h2>
                                <ul class="step-list">
                                    <li>Dari dasbor, klik menu <strong>"Laporan Bisnis"</strong>.</li>
                                    <li>Halaman menampilkan ringkasan: Total Modal, Total Omzet, Total Pengeluaran, Untung, dan Rugi.</li>
                                    <li>Di bawahnya terdapat grafik tren keuangan harian dan daftar menu terlaris.</li>
                                </ul>
                            </article>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-icon">2</div>
                            <article class="guide-card">
                                <h2>Memfilter Periode Laporan</h2>
                                <ul class="step-list">
                                    <li>Gunakan filter tanggal manual atau quick filter: <strong>1 Hari, 1 Minggu, 1 Bulan, 1 Tahun</strong>.</li>
                                    <li>Klik <strong>"Terapkan"</strong> untuk memuat ulang laporan sesuai periode.</li>
                                    <li>Semua card ringkasan dan grafik diperbarui otomatis.</li>
                                </ul>
                            </article>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-icon">3</div>
                            <article class="guide-card">
                                <h2>Membaca Grafik & Menu Terlaris</h2>
                                <ul class="step-list">
                                    <li>Grafik menampilkan tren <strong>Omzet, Untung, Pengeluaran, Rugi, dan Modal</strong> harian.</li>
                                    <li>Bagian <strong>"Menu Terlaris"</strong> menunjukkan 10 menu dengan penjualan tertinggi pada periode tersebut.</li>
                                    <li>Bagian <strong>"Menu Jarang Terjual"</strong> menampilkan menu yang perlu dievaluasi.</li>
                                </ul>
                            </article>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-icon">4</div>
                            <article class="guide-card">
                                <h2>Memantau Laporan Pengeluaran</h2>
                                <ul class="step-list">
                                    <li>Dari dasbor, klik menu <strong>"Laporan Pengeluaran"</strong>.</li>
                                    <li>Anda bisa melihat seluruh catatan pengeluaran operasional yang diinput oleh kasir.</li>
                                    <li>Gunakan filter tanggal untuk melihat pengeluaran pada periode tertentu.</li>
                                    <li>Data pengeluaran sudah terintegrasi otomatis dengan laporan bisnis.</li>
                                </ul>
                                <div class="alert-hrms">
                                    <i class="bi bi-bar-chart-line-fill"></i>
                                    <div>
                                        <p>Data laporan bersumber langsung dari kasir Flavory.id.</p>
                                        <span>Setiap transaksi yang dicatat kasir akan langsung terefleksi di laporan bisnis dasbor manager tanpa perlu input manual.</span>
                                    </div>
                                </div>
                            </article>
                        </li>
                    </ol>
                </section>

                <nav class="guide-nav">
                    <a href="{{ route('panduan.hrms.modal-usaha') }}"><i class="bi bi-arrow-left"></i> Sebelumnya: Modal Usaha</a>
                    <a href="{{ route('panduan.hrms.mineai-manager') }}">Selanjutnya: MineAI Manager <i class="bi bi-arrow-right"></i></a>
                </nav>
            </div>
        </div>
    </div>
</main>
@endsection
