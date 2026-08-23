@extends('layouts.app')
@section('title', 'Panduan Mengelola Data Karyawan — Key.Flavory.id HRMS')
@push('styles')
<meta name="description" content="Cara menambah, mengedit, dan mengelola data karyawan aktif maupun nonaktif di Key.Flavory.id.">
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
                    <h1>Mengelola Data Karyawan</h1>
                    <p class="mt-2">Kelola profil, tambah anggota tim baru, dan atur akses akun kasir untuk setiap karyawan.</p>
                </header>

                <section>
                    <ol class="timeline">
                        <li class="timeline-item">
                            <div class="timeline-icon">1</div>
                            <article class="guide-card">
                                <h2>Buka Menu Data Karyawan</h2>
                                <ul class="step-list">
                                    <li>Dari dasbor utama, klik menu <strong>"Data Karyawan"</strong>.</li>
                                    <li>Anda akan melihat tiga sub-menu: <strong>Karyawan Aktif</strong>, <strong>Karyawan Nonaktif</strong>, dan <strong>Akses Akun</strong>.</li>
                                </ul>
                            </article>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-icon">2</div>
                            <article class="guide-card">
                                <h2>Menambah Karyawan Baru</h2>
                                <ul class="step-list">
                                    <li>Klik <strong>"Karyawan Aktif"</strong>, lalu klik tombol <strong>"Tambah Karyawan"</strong>.</li>
                                    <li>Isi data: nama lengkap, foto profil, nomor telepon, posisi/jabatan, dan tanggal bergabung.</li>
                                    <li>Klik <strong>"Simpan"</strong> untuk menyimpan data karyawan baru.</li>
                                </ul>
                            </article>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-icon">3</div>
                            <article class="guide-card">
                                <h2>Menonaktifkan Karyawan</h2>
                                <ul class="step-list">
                                    <li>Cari nama karyawan di daftar <strong>Karyawan Aktif</strong>.</li>
                                    <li>Klik ikon aksi, lalu pilih <strong>"Nonaktifkan"</strong>.</li>
                                    <li>Karyawan akan berpindah ke tab <strong>Karyawan Nonaktif</strong> sebagai arsip.</li>
                                    <li>Data tidak dihapus — bisa diaktifkan kembali kapan saja.</li>
                                </ul>
                            </article>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-icon">4</div>
                            <article class="guide-card">
                                <h2>Membuat Akun Akses Kasir</h2>
                                <ul class="step-list">
                                    <li>Klik sub-menu <strong>"Akses Akun"</strong>.</li>
                                    <li>Klik <strong>"Buat Akun Baru"</strong>, pilih karyawan, tentukan username dan password.</li>
                                    <li>Akun ini digunakan karyawan untuk login ke aplikasi kasir <strong>Flavory.id</strong>.</li>
                                </ul>
                                <div class="alert-hrms">
                                    <i class="bi bi-shield-lock-fill"></i>
                                    <div>
                                        <p>Satu karyawan = satu akun kasir.</p>
                                        <span>Setiap akun kasir terhubung ke outlet Anda secara otomatis melalui Manager Key.</span>
                                    </div>
                                </div>
                            </article>
                        </li>
                    </ol>
                </section>

                <nav class="guide-nav">
                    <a href="{{ route('panduan.hrms.akses-dasbor') }}"><i class="bi bi-arrow-left"></i> Sebelumnya: Akses Dasbor</a>
                    <a href="{{ route('panduan.hrms.shift') }}">Selanjutnya: Manajemen Shift <i class="bi bi-arrow-right"></i></a>
                </nav>
            </div>
        </div>
    </div>
</main>
@endsection
