@extends('layouts.app')
@section('title', 'Panduan Rekap Absensi Karyawan — Dasbor Manager Flavory.id')
@push('styles')
<meta name="description" content="Cara memantau rekap kehadiran, izin, dan keterlambatan karyawan outlet melalui Dasbor Manager Flavory.id.">
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
                    <h1>Rekap Absensi Karyawan</h1>
                    <p class="mt-2">Pantau kehadiran, keterlambatan, dan pengajuan izin seluruh tim secara real-time.</p>
                </header>

                <section>
                    <ol class="timeline">
                        <li class="timeline-item">
                            <div class="timeline-icon">1</div>
                            <article class="guide-card">
                                <h2>Buka Menu Rekap Absensi</h2>
                                <ul class="step-list">
                                    <li>Dari dasbor, klik menu <strong>"Rekap Absensi"</strong>.</li>
                                    <li>Halaman menampilkan daftar absensi seluruh karyawan hari ini secara default.</li>
                                </ul>
                            </article>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-icon">2</div>
                            <article class="guide-card">
                                <h2>Memfilter Data Absensi</h2>
                                <ul class="step-list">
                                    <li>Gunakan filter <strong>tanggal</strong> atau <strong>bulan</strong> untuk melihat rekap periode tertentu.</li>
                                    <li>Filter berdasarkan <strong>nama karyawan</strong> atau <strong>status absensi</strong> (Hadir, Izin, Tidak Hadir).</li>
                                    <li>Klik <strong>"Terapkan"</strong> untuk memuat data sesuai filter.</li>
                                </ul>
                            </article>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-icon">3</div>
                            <article class="guide-card">
                                <h2>Proses Absensi Karyawan</h2>
                                <ul class="step-list">
                                    <li>Karyawan melakukan check-in absensi melalui akun kasir mereka di Flavory.id.</li>
                                    <li>Data check-in otomatis masuk ke rekap absensi dasbor manager.</li>
                                    <li>Manager dapat melihat jam masuk dan status absensi secara real-time.</li>
                                </ul>
                            </article>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-icon">4</div>
                            <article class="guide-card">
                                <h2>Mengelola Pengajuan Izin</h2>
                                <ul class="step-list">
                                    <li>Karyawan yang mengajukan izin akan muncul di tab <strong>"Pengajuan Izin"</strong>.</li>
                                    <li>Manager dapat <strong>menyetujui</strong> atau <strong>menolak</strong> pengajuan izin dengan klik tombol aksi.</li>
                                    <li>Status izin yang disetujui akan otomatis terekam di rekap absensi karyawan tersebut.</li>
                                </ul>
                                <div class="alert-info-box">
                                    <i class="bi bi-info-circle-fill"></i>
                                    <div>
                                        <p>Rekap absensi bisa diunduh untuk keperluan penggajian.</p>
                                        <span>Klik tombol "Unduh" atau "Cetak" untuk mendapatkan rekap dalam format yang dapat disimpan.</span>
                                    </div>
                                </div>
                            </article>
                        </li>
                    </ol>
                </section>

                <nav class="guide-nav">
                    <a href="{{ route('panduan.hrms.shift') }}"><i class="bi bi-arrow-left"></i> Sebelumnya: Manajemen Shift</a>
                    <a href="{{ route('panduan.hrms.modal-usaha') }}">Selanjutnya: Modal Usaha <i class="bi bi-arrow-right"></i></a>
                </nav>
            </div>
        </div>
    </div>
</main>
@endsection
