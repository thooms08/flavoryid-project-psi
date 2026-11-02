@extends('layouts.app')
@section('title', 'Panduan Dasbor Manager — Overview Fitur & AI Consultant Flavory.id')
@push('styles')
<meta name="description" content="Panduan lengkap Dasbor Manager Flavory.id: manajemen karyawan, shift, absensi, modal usaha, laporan bisnis, dan AI Consultant untuk owner dan manager outlet kuliner.">
<meta name="keywords" content="panduan dasbor manager flavory, manajemen karyawan restoran, laporan bisnis kuliner, AI consultant flavory">
<meta name="author" content="Flavory.id">
<style>
    :root {
        --manager-green:  #198754;
        --manager-light:  #f0fdf4;
        --text-dark:      #1e293b;
        --text-muted:     #64748b;
        --primary-orange: #ff6a00;
    }
    .guide-page { background-color: #f8fafc; min-height: 100vh; padding: 120px 0 80px; }
    .guide-header h1 { font-weight: 800; color: var(--text-dark); letter-spacing: -.5px; }
    .guide-header p   { color: var(--text-muted); font-size: 1.05rem; max-width: 700px; }

    /* Grid card modul */
    .manager-overview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 16px;
        margin-top: 8px;
    }
    .manager-ov-card {
        display: flex; flex-direction: column;
        background: #fff; border-radius: 16px;
        border: 1.5px solid rgba(25,135,84,.14);
        padding: 22px 20px; text-decoration: none;
        transition: transform .22s, box-shadow .22s, border-color .22s;
        box-shadow: 0 4px 14px rgba(0,0,0,.04);
    }
    .manager-ov-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(25,135,84,.12);
        border-color: var(--manager-green);
    }
    .manager-ov-icon {
        width: 46px; height: 46px; border-radius: 12px;
        background: var(--manager-light);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; color: var(--manager-green);
        margin-bottom: 14px; flex-shrink: 0;
    }
    .manager-ov-card h3 { font-size: .95rem; font-weight: 700; color: var(--text-dark); margin-bottom: 5px; }
    .manager-ov-card p  { font-size: .78rem; color: var(--text-muted); margin: 0; line-height: 1.5; flex-grow: 1; }
    .manager-ov-arrow   { font-size: .75rem; color: var(--manager-green); margin-top: 14px; font-weight: 600; }

    /* CTA box */
    .manager-cta {
        background: linear-gradient(135deg, var(--manager-green), #20c997);
        border-radius: 20px; padding: 36px 32px;
        color: white; text-align: center;
        box-shadow: 0 12px 30px rgba(25,135,84,.25);
    }

    @media(max-width: 768px) {
        .guide-page { padding: 100px 0 40px; }
        .manager-overview-grid { grid-template-columns: 1fr 1fr; }
    }
    @media(max-width: 480px) {
        .manager-overview-grid { grid-template-columns: 1fr; }
    }
</style>
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
                    <h1>Panduan Dasbor Manager Flavory.id</h1>
                    <p class="mt-2">
                        <strong>Dasbor Manager</strong> adalah platform manajemen tim dan bisnis milik Flavory.id yang
                        membantu owner & manager mengelola karyawan, laporan bisnis, dan berkonsultasi dengan AI Consultant — semuanya terintegrasi
                        dengan data kasir secara real-time.
                    </p>
                </header>

                {{-- Grid panduan per modul --}}
                <div class="manager-overview-grid mb-5">
                    <a href="{{ route('panduan.hrms.akses-dasbor') }}" class="manager-ov-card">
                        <div class="manager-ov-icon"><i class="bi bi-box-arrow-in-right"></i></div>
                        <h3>Akses Dasbor Manager</h3>
                        <p>Cara login dan mulai menjelajahi dasbor manager Flavory.id.</p>
                        <span class="manager-ov-arrow">Baca panduan <i class="bi bi-arrow-right"></i></span>
                    </a>
                    <a href="{{ route('panduan.hrms.data-karyawan') }}" class="manager-ov-card">
                        <div class="manager-ov-icon"><i class="bi bi-person-lines-fill"></i></div>
                        <h3>Data Karyawan</h3>
                        <p>Tambah, edit, arsip karyawan aktif & nonaktif, serta kelola akun akses kasir.</p>
                        <span class="manager-ov-arrow">Baca panduan <i class="bi bi-arrow-right"></i></span>
                    </a>
                    <a href="{{ route('panduan.hrms.shift') }}" class="manager-ov-card">
                        <div class="manager-ov-icon"><i class="bi bi-calendar-week-fill"></i></div>
                        <h3>Manajemen Shift</h3>
                        <p>Buat kategori shift, atur rotasi jadwal, dan tugaskan karyawan ke shift.</p>
                        <span class="manager-ov-arrow">Baca panduan <i class="bi bi-arrow-right"></i></span>
                    </a>
                    <a href="{{ route('panduan.hrms.absensi') }}" class="manager-ov-card">
                        <div class="manager-ov-icon"><i class="bi bi-fingerprint"></i></div>
                        <h3>Rekap Absensi</h3>
                        <p>Pantau kehadiran, keterlambatan, dan setujui pengajuan izin karyawan.</p>
                        <span class="manager-ov-arrow">Baca panduan <i class="bi bi-arrow-right"></i></span>
                    </a>
                    <a href="{{ route('panduan.hrms.modal-usaha') }}" class="manager-ov-card">
                        <div class="manager-ov-icon"><i class="bi bi-wallet2"></i></div>
                        <h3>Modal Usaha</h3>
                        <p>Catat setoran modal, lihat grafik tren, dan kelola riwayat modal usaha.</p>
                        <span class="manager-ov-arrow">Baca panduan <i class="bi bi-arrow-right"></i></span>
                    </a>
                    <a href="{{ route('panduan.hrms.laporan-bisnis') }}" class="manager-ov-card">
                        <div class="manager-ov-icon"><i class="bi bi-bar-chart-line-fill"></i></div>
                        <h3>Laporan Bisnis</h3>
                        <p>Pantau omzet, untung-rugi, pengeluaran, dan menu terlaris outlet secara real-time.</p>
                        <span class="manager-ov-arrow">Baca panduan <i class="bi bi-arrow-right"></i></span>
                    </a>
                    <a href="{{ route('panduan.hrms.mineai-manager') }}" class="manager-ov-card">
                        <div class="manager-ov-icon"><i class="bi bi-stars"></i></div>
                        <h3>AI Consultant</h3>
                        <p>Dapatkan insight AI bisnis dan berkonsultasi langsung berbasis data outlet Anda.</p>
                        <span class="manager-ov-arrow">Baca panduan <i class="bi bi-arrow-right"></i></span>
                    </a>
                </div>

                {{-- CTA box --}}
                <div class="manager-cta">
                    <i class="bi bi-people-fill" style="font-size:2.2rem;opacity:.9;"></i>
                    <h3 class="fw-bold mt-3 mb-2">Siap Kelola Tim Lebih Efisien?</h3>
                    <p class="mb-4 opacity-90" style="font-size:1rem;">
                        Daftarkan bisnis Anda sekarang — manajemen karyawan terintegrasi penuh dengan kasir Flavory.id.
                    </p>
                    <a href="{{ route('register') }}"
                       class="btn btn-light fw-bold rounded-pill px-5 py-2 shadow-sm"
                       style="color:var(--manager-green);">
                        <i class="bi bi-person-plus-fill me-1"></i> Daftar Sekarang
                    </a>
                </div>

            </div>
        </div>
    </div>
</main>
@endsection
