@extends('layouts.app')
@section('title', 'Panduan Modal Usaha — Dasbor Manager Flavory.id')
@push('styles')
<meta name="description" content="Cara mencatat, memantau, dan mengelola setoran modal usaha outlet melalui Dasbor Manager Flavory.id.">
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
                          style="background:var(--manager-light);color:var(--manager-green);border:1px solid rgba(25,135,84,.2);">
                        <i class="bi bi-people-fill me-1"></i> Dasbor Manager
                    </span>
                    <h1>Modal Usaha</h1>
                    <p class="mt-2">Catat setiap setoran modal dan pantau riwayat lengkapnya dengan grafik yang mudah dipahami.</p>
                </header>

                <section>
                    <ol class="timeline">
                        <li class="timeline-item">
                            <div class="timeline-icon">1</div>
                            <article class="guide-card">
                                <h2>Buka Menu Modal Usaha</h2>
                                <ul class="step-list">
                                    <li>Dari dasbor, klik menu <strong>"Modal Usaha"</strong>.</li>
                                    <li>Halaman menampilkan total modal pada periode yang dipilih beserta grafik batang.</li>
                                </ul>
                            </article>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-icon">2</div>
                            <article class="guide-card">
                                <h2>Mencatat Modal Baru</h2>
                                <ul class="step-list">
                                    <li>Di kolom kiri, isi form <strong>"Input Modal Baru"</strong>.</li>
                                    <li>Masukkan <strong>nominal</strong> (dalam Rupiah) dan <strong>keterangan</strong> (opsional, misal: "Modal awal Januari").</li>
                                    <li>Klik <strong>"Simpan Modal"</strong> untuk menyimpan.</li>
                                    <li>Data langsung muncul di tabel riwayat dan grafik diperbarui otomatis.</li>
                                </ul>
                            </article>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-icon">3</div>
                            <article class="guide-card">
                                <h2>Memfilter Periode Modal</h2>
                                <ul class="step-list">
                                    <li>Gunakan filter <strong>tanggal</strong> atau quick filter (1 Hari, 1 Minggu, 1 Bulan, 1 Tahun) di bagian atas.</li>
                                    <li>Klik <strong>"Terapkan"</strong> untuk memuat data sesuai periode.</li>
                                    <li>Total modal pada periode tersebut ditampilkan di card ringkasan.</li>
                                </ul>
                            </article>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-icon">4</div>
                            <article class="guide-card">
                                <h2>Mengelola Data Modal</h2>
                                <ul class="step-list">
                                    <li>Untuk menghapus data modal, klik ikon <strong>tempat sampah</strong> di baris yang ingin dihapus.</li>
                                    <li>Data yang dihapus <strong>tidak hilang permanen</strong> — berpindah ke tab <strong>"Data Terhapus"</strong>.</li>
                                    <li>Untuk memulihkan, buka tab "Data Terhapus" dan klik tombol <strong>"Pulihkan"</strong>.</li>
                                </ul>
                                <div class="alert-info-box">
                                    <i class="bi bi-wallet2"></i>
                                    <div>
                                        <p>Modal Usaha beda dengan Laporan Bisnis.</p>
                                        <span>Modal Usaha mencatat dana yang Anda setorkan ke bisnis, sedangkan Laporan Bisnis mencatat omzet dan keuntungan dari transaksi kasir.</span>
                                    </div>
                                </div>
                            </article>
                        </li>
                    </ol>
                </section>

                <nav class="guide-nav">
                    <a href="{{ route('panduan.hrms.absensi') }}"><i class="bi bi-arrow-left"></i> Sebelumnya: Rekap Absensi</a>
                    <a href="{{ route('panduan.hrms.laporan-bisnis') }}">Selanjutnya: Laporan Bisnis <i class="bi bi-arrow-right"></i></a>
                </nav>
            </div>
        </div>
    </div>
</main>
@endsection
