@extends('layouts.app')
@section('title', 'Panduan Manajemen Shift Karyawan — Key.Flavory.id HRMS')
@push('styles')
<meta name="description" content="Cara membuat kategori shift, mengatur rotasi jadwal kerja, dan menugaskan karyawan ke shift di Key.Flavory.id.">
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
                    <h1>Manajemen Shift Karyawan</h1>
                    <p class="mt-2">Buat kategori shift, atur rotasi jadwal harian, dan tugaskan karyawan ke shift yang tepat.</p>
                </header>

                <section>
                    <ol class="timeline">
                        <li class="timeline-item">
                            <div class="timeline-icon">1</div>
                            <article class="guide-card">
                                <h2>Buka Menu Manajemen Shift</h2>
                                <ul class="step-list">
                                    <li>Dari dasbor, klik menu <strong>"Manajemen Shift"</strong>.</li>
                                    <li>Anda akan melihat daftar semua kategori shift yang sudah dibuat.</li>
                                </ul>
                            </article>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-icon">2</div>
                            <article class="guide-card">
                                <h2>Membuat Kategori Shift Baru</h2>
                                <ul class="step-list">
                                    <li>Klik tombol <strong>"Shift Baru"</strong> di pojok kanan atas.</li>
                                    <li>Masukkan <strong>nama shift</strong> (contoh: Shift Pagi, Shift Sore, Shift Malam).</li>
                                    <li>Klik <strong>"Simpan Shift"</strong>.</li>
                                    <li>Kategori shift baru akan muncul di daftar sebagai card.</li>
                                </ul>
                            </article>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-icon">3</div>
                            <article class="guide-card">
                                <h2>Menugaskan Karyawan ke Shift</h2>
                                <ul class="step-list">
                                    <li>Klik card nama shift yang ingin diisi anggota.</li>
                                    <li>Di halaman detail shift, klik <strong>"Tambah Anggota"</strong>.</li>
                                    <li>Pilih karyawan dari daftar, lalu klik <strong>"Simpan"</strong>.</li>
                                    <li>Karyawan yang ditugaskan akan muncul dalam daftar anggota shift tersebut.</li>
                                </ul>
                            </article>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-icon">4</div>
                            <article class="guide-card">
                                <h2>Mengelola Anggota Shift</h2>
                                <ul class="step-list">
                                    <li>Untuk memindahkan karyawan ke shift lain, hapus dulu dari shift saat ini, lalu tambahkan ke shift tujuan.</li>
                                    <li>Satu karyawan bisa ditugaskan ke lebih dari satu shift sesuai kebutuhan operasional.</li>
                                    <li>Untuk menghapus kategori shift, pastikan tidak ada anggota aktif di dalamnya terlebih dahulu.</li>
                                </ul>
                            </article>
                        </li>
                    </ol>
                </section>

                <nav class="guide-nav">
                    <a href="{{ route('panduan.hrms.data-karyawan') }}"><i class="bi bi-arrow-left"></i> Sebelumnya: Data Karyawan</a>
                    <a href="{{ route('panduan.hrms.absensi') }}">Selanjutnya: Rekap Absensi <i class="bi bi-arrow-right"></i></a>
                </nav>
            </div>
        </div>
    </div>
</main>
@endsection
