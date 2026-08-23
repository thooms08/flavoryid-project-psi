@extends('layouts.app')
@section('title', 'Panduan Akses Dasbor Manager Key.Flavory.id')
@push('styles')
<meta name="description" content="Cara login dan mengakses dasbor manager Key.Flavory.id untuk mengelola karyawan dan bisnis outlet Anda.">
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
                    <h1>Mengakses Dasbor Manager</h1>
                    <p class="mt-2">Panduan login ke Key.Flavory.id dan mulai mengelola outlet dari dasbor manager.</p>
                </header>

                <section>
                    <ol class="timeline">
                        <li class="timeline-item">
                            <div class="timeline-icon">1</div>
                            <article class="guide-card">
                                <h2>Buka Key.Flavory.id</h2>
                                <ul class="step-list">
                                    <li>Buka website <strong>flavory.id</strong>, klik tombol <strong>"Masuk"</strong> di navbar.</li>
                                    <li>Pada modal pilihan, pilih <strong>"Masuk Beranda Manager"</strong>.</li>
                                    <li>Atau langsung kunjungi <strong>key.flavory.id</strong> di browser Anda.</li>
                                </ul>
                            </article>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-icon">2</div>
                            <article class="guide-card">
                                <h2>Login dengan Akun Manager</h2>
                                <ul class="step-list">
                                    <li>Masukkan <strong>email</strong> dan <strong>password</strong> akun manager Anda.</li>
                                    <li>Klik tombol <strong>"Masuk"</strong> untuk melanjutkan.</li>
                                    <li>Jika berhasil, Anda akan diarahkan ke dasbor manager.</li>
                                </ul>
                                <div class="alert-hrms">
                                    <i class="bi bi-info-circle-fill"></i>
                                    <div>
                                        <p>Akun manager dibuat terpisah dari akun kasir.</p>
                                        <span>Jika belum punya akun manager, daftar melalui halaman registrasi Flavory.id atau hubungi admin outlet Anda.</span>
                                    </div>
                                </div>
                            </article>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-icon">3</div>
                            <article class="guide-card">
                                <h2>Menjelajahi Dasbor</h2>
                                <ul class="step-list">
                                    <li>Setelah login, Anda akan melihat <strong>dasbor utama</strong> berisi menu-menu modul HRMS.</li>
                                    <li>Di bagian atas terdapat <strong>Manager Key</strong> — kode unik untuk menghubungkan akun karyawan ke outlet Anda.</li>
                                    <li>Navigasi utama: Data Karyawan, Manajemen Shift, Rekap Absensi, Modal Usaha, Laporan Bisnis, Performa Bisnis, dan MineAI.</li>
                                </ul>
                            </article>
                        </li>
                    </ol>
                </section>

                <nav class="guide-nav">
                    <a href="{{ route('panduan.hrms') }}"><i class="bi bi-arrow-left"></i> Kembali ke Overview HRMS</a>
                    <a href="{{ route('panduan.hrms.data-karyawan') }}">Selanjutnya: Data Karyawan <i class="bi bi-arrow-right"></i></a>
                </nav>
            </div>
        </div>
    </div>
</main>
@endsection
