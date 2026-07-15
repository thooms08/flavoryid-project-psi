{{-- resources/views/event/detail-event.blade.php --}}
@extends('layouts.app')

@section('title', $event->event_name . ' | Event Flavory.id')

@section('meta_description', Str::limit(strip_tags($event->description), 155))

@section('meta_image', $event->image ? asset('assets/event/' . $event->image) : asset('assets/event-preview.jpeg'))

@push('styles')
{{-- Meta OG & Twitter khusus untuk halaman event ini (override meta di layouts/app.blade.php) --}}
<meta property="og:type"        content="article">
<meta property="og:url"         content="{{ route('event.show', $event->slug ?? $event->uuid) }}">
<meta property="og:title"       content="{{ $event->event_name }} | Event Flavory.id">
<meta property="og:description" content="{{ Str::limit(strip_tags($event->description), 155) }}">
<meta property="og:image"       content="{{ $event->image ? asset('assets/event/' . $event->image) : asset('assets/event-preview.jpeg') }}">
<meta property="og:image:width"  content="1200">
<meta property="og:image:height" content="630">
<meta property="og:site_name"   content="Aplikasi Flavory.id">

<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:url"         content="{{ route('event.show', $event->slug ?? $event->uuid) }}">
<meta name="twitter:title"       content="{{ $event->event_name }} | Event Flavory.id">
<meta name="twitter:description" content="{{ Str::limit(strip_tags($event->description), 155) }}">
<meta name="twitter:image"       content="{{ $event->image ? asset('assets/event/' . $event->image) : asset('assets/event-preview.jpeg') }}">
<style>
    :root {
        --primary-orange: #ff6a00;
        --dark-orange:    #e65f00;
        --light-orange:   #fff4ec;
    }

    .detail-hero {
        padding: 120px 0 0;
        background: linear-gradient(135deg, #ffffff 0%, var(--light-orange) 100%);
    }
    .event-cover {
        width: 100%;
        height: auto;           /* ikuti rasio asli gambar, portrait ya portrait, landscape ya landscape */
        display: block;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,.12);
    }
    .event-cover-placeholder {
        width: 100%;
        height: 320px;
        border-radius: 20px;
        background: linear-gradient(135deg, var(--light-orange), #ffe0c0);
        display: flex; align-items: center; justify-content: center;
        font-size: 5rem;
        color: var(--primary-orange);
    }

    /* Info badges */
    .info-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f1f5f9;
        border-radius: 10px;
        padding: 8px 14px;
        font-size: .82rem;
        font-weight: 600;
        color: #475569;
    }
    .info-badge i { color: var(--primary-orange); font-size: .95rem; }

    /* Description */
    .event-description {
        font-size: .92rem;
        line-height: 1.8;
        color: #475569;
    }
    .event-description h1, .event-description h2,
    .event-description h3, .event-description h4 {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1a202c;
        margin-top: 20px;
    }

    /* Form Card */
    .form-card {
        background: #fff;
        border-radius: 24px;
        border: 1px solid rgba(0,0,0,.06);
        box-shadow: 0 8px 30px rgba(0,0,0,.06);
        padding: 36px;
        position: sticky;
        top: 90px;
    }
    .form-card-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: #1a202c;
        margin-bottom: 4px;
    }
    .form-label-custom {
        font-size: .8rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 5px;
    }
    .form-control-custom {
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 11px 14px;
        font-size: .88rem;
        transition: all .2s;
        background: #fafafa;
    }
    .form-control-custom:focus {
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 3px rgba(255,106,0,.12);
        background: #fff;
        outline: none;
    }
    .form-control-custom.is-invalid { border-color: #ef4444; }
    .btn-daftar {
        background: var(--primary-orange);
        color: #fff;
        border: none;
        border-radius: 14px;
        padding: 13px;
        font-size: .95rem;
        font-weight: 700;
        width: 100%;
        transition: all .25s;
    }
    .btn-daftar:hover {
        background: var(--dark-orange);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255,106,0,.3);
    }
    .btn-daftar:disabled {
        background: #cbd5e1;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* Peserta count pill */
    .peserta-count {
        background: var(--light-orange);
        border: 1px solid #ffd8b0;
        border-radius: 12px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }
    .peserta-count i { font-size: 1.3rem; color: var(--primary-orange); }
    .peserta-count strong { font-size: .85rem; color: #1a202c; }
    .peserta-count span { font-size: .78rem; color: #64748b; }

    /* Closed banner */
    .closed-banner {
        background: #fef2f2;
        border: 1.5px solid #fecaca;
        border-radius: 14px;
        padding: 20px;
        text-align: center;
        color: #b91c1c;
    }

    @media (max-width: 991px) {
        .form-card { position: static; margin-top: 40px; }
        .detail-hero { padding-top: 100px; }
    }
</style>
@endpush

@section('content')

@php
    $isOngoing  = now()->between($event->event_start_date, $event->event_end_date);
    $isUpcoming = now()->lt($event->event_start_date);
    $isEnded    = now()->gt($event->event_end_date);
    $isClosed   = $event->status === 'inactive'; // ditutup manual oleh admin
    $pesertaCount = $event->participants->count();

    if ($isClosed && !$isEnded) {
        $badgeClass = 'bg-danger';
        $badgeText  = 'Ditutup';
    } elseif ($isOngoing) {
        $badgeClass = 'bg-success';
        $badgeText  = 'Sedang Berlangsung';
    } elseif ($isUpcoming) {
        $badgeClass = 'bg-primary';
        $badgeText  = 'Segera Hadir';
    } else {
        $badgeClass = 'bg-secondary';
        $badgeText  = 'Sudah Selesai';
    }@endphp

<div class="detail-hero pb-5">
    <div class="container">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-orange text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('event.index') }}" class="text-orange text-decoration-none">Event</a></li>
                <!--<li class="breadcrumb-item active text-muted" aria-current="page">{{ Str::limit($event->event_name, 40) }}</li> -->           </ol>
        </nav>

        <div class="row g-5">
            {{-- Kiri: Info Event --}}
            <div class="col-lg-7">

                {{-- Cover --}}
                @if($event->image)
                    <img src="{{ asset('assets/event/' . $event->image) }}"
                         alt="{{ $event->event_name }}"
                         class="event-cover mb-4"
                         onerror="this.src='https://placehold.co/800x420/fff4ec/ff6a00?text=Event+Flavory'">
                @else
                    <div class="event-cover-placeholder mb-4">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                @endif

                {{-- Status + Judul --}}
                <span class="badge {{ $badgeClass }} mb-2">
                    <i class="bi bi-circle-fill me-1" style="font-size:.5rem;vertical-align:middle;"></i>
                    {{ $badgeText }}
                </span>
                <h1 class="fw-bold mb-3" style="font-size:1.8rem;line-height:1.35;">
                    {{ $event->event_name }}
                </h1>

                {{-- Info Badges --}}
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <span class="info-badge">
                        <i class="bi bi-calendar3"></i>
                        {{ \Carbon\Carbon::parse($event->event_start_date)->translatedFormat('d M Y') }}
                        @if($event->event_start_date !== $event->event_end_date)
                            &ndash; {{ \Carbon\Carbon::parse($event->event_end_date)->translatedFormat('d M Y') }}
                        @endif
                    </span>
                    <span class="info-badge">
                        <i class="bi bi-people-fill"></i>
                        {{ $pesertaCount }} Peserta
                    </span>
                </div>

                {{-- Deskripsi --}}
                <div class="event-description">
                    {!! nl2br(e($event->description)) !!}
                </div>

            </div>

            {{-- Kanan: Form Pendaftaran --}}
            <div class="col-lg-5">
                <div class="form-card">

                    <p class="form-card-title">
                        <i class="bi bi-pencil-square text-orange me-2"></i>
                        Formulir Pendaftaran
                    </p>
                    <p class="text-muted small mb-4">Isi data diri Anda dengan benar. Setelah mendaftar, Anda akan langsung diarahkan ke grup WhatsApp event.</p>

                    {{-- Jumlah peserta --}}
                    <div class="peserta-count">
                        <i class="bi bi-people-fill"></i>
                        <div>
                            <strong>{{ $pesertaCount }} orang sudah mendaftar</strong><br>
                            <span>Segera daftar sebelum tempat penuh!</span>
                        </div>
                    </div>

                    @if($isEnded)
                        {{-- Event sudah selesai berdasarkan tanggal --}}
                        <div class="closed-banner">
                            <i class="bi bi-lock-fill d-block mb-2" style="font-size:2rem;"></i>
                            <strong>Pendaftaran Ditutup</strong>
                            <p class="small mb-0 mt-1">Event ini sudah berakhir pada {{ \Carbon\Carbon::parse($event->event_end_date)->translatedFormat('d M Y') }}.</p>
                        </div>
                    @elseif($isClosed)
                        {{-- Event ditutup manual oleh admin --}}
                        <div class="closed-banner">
                            <i class="bi bi-x-circle-fill d-block mb-2" style="font-size:2rem;"></i>
                            <strong>Event Telah Ditutup</strong>
                            <p class="small mb-0 mt-1">Pendaftaran untuk event ini telah ditutup oleh penyelenggara.</p>
                        </div>
                    @else
                        {{-- Form Aktif --}}
                        <form action="{{ route('event.daftar', $event->slug ?? $event->uuid) }}" method="POST" id="formDaftar">
                            @csrf

                            {{-- Nama --}}
                            <div class="mb-3">
                                <label class="form-label-custom" for="name">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       id="name"
                                       name="name"
                                       value="{{ old('name') }}"
                                       class="form-control form-control-custom @error('name') is-invalid @enderror"
                                       placeholder="Contoh: Budi Santoso"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback d-block" style="font-size:.78rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Nomor WhatsApp --}}
                            <div class="mb-3">
                                <label class="form-label-custom" for="wa">
                                    Nomor WhatsApp Aktif <span class="text-danger">*</span>
                                </label>
                                <input type="number"
                                       id="wa"
                                       name="wa"
                                       value="{{ old('wa') }}"
                                       class="form-control form-control-custom @error('wa') is-invalid @enderror"
                                       placeholder="Contoh: 08123456789"
                                       inputmode="numeric"
                                       required>
                                @error('wa')
                                    <div class="invalid-feedback d-block" style="font-size:.78rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Kategori Peserta --}}
                            <div class="mb-4">
                                <label class="form-label-custom" for="participant_category">
                                    Kategori Peserta <span class="text-danger">*</span>
                                </label>
                                <div class="position-relative">
                                    <select id="participant_category"
                                            name="participant_category"
                                            class="form-select form-control-custom @error('participant_category') is-invalid @enderror"
                                            style="background-image: none !important; padding-right: 40px;"
                                            required>
                                        <option value="" disabled {{ old('participant_category') ? '' : 'selected' }}>-- Pilih kategori --</option>
                                        <option value="pengusaha_kuliner"     {{ old('participant_category') === 'pengusaha_kuliner'     ? 'selected' : '' }}>Pengusaha Kuliner</option>
                                        <option value="pengusaha_non_kuliner" {{ old('participant_category') === 'pengusaha_non_kuliner' ? 'selected' : '' }}>Pengusaha Non-Kuliner</option>
                                        <option value="pelajar"               {{ old('participant_category') === 'pelajar'               ? 'selected' : '' }}>Pelajar / Mahasiswa</option>
                                        <option value="umum"                  {{ old('participant_category') === 'umum'                  ? 'selected' : '' }}>Umum</option>
                                    </select>
                                    {{-- Icon Panah Kustom --}}
                                    <i id="category_arrow" class="bi bi-chevron-down position-absolute" 
                                       style="right: 15px; top: 50%; transform: translateY(-50%); pointer-events: none; transition: transform 0.2s; color: #475569; font-size: 0.85rem;"></i>
                                </div>
                                @error('participant_category')
                                    <div class="invalid-feedback d-block" style="font-size:.78rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Input Nama Usaha (Kondisional) --}}
                            <div id="group_nama_usaha" class="mb-3" style="display: none;">
                                <label class="form-label-custom" for="nama_usaha">
                                    Nama Usaha <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       id="nama_usaha"
                                       name="nama_usaha"
                                       value="{{ old('nama_usaha') }}"
                                       class="form-control form-control-custom @error('nama_usaha') is-invalid @enderror"
                                       placeholder="Contoh: Kopi Nusantara">
                                @error('nama_usaha')
                                    <div class="invalid-feedback d-block" style="font-size:.78rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Input Nama Instansi / Sekolah (Kondisional) --}}
                            <div id="group_nama_instansi" class="mb-3" style="display: none;">
                                <label class="form-label-custom" for="nama_instansi">
                                    Nama Instansi / Sekolah / Universitas <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       id="nama_instansi"
                                       name="nama_instansi"
                                       value="{{ old('nama_instansi') }}"
                                       class="form-control form-control-custom @error('nama_instansi') is-invalid @enderror"
                                       placeholder="Contoh: SMA Negeri 1 Jakarta / Universitas Indonesia">
                                @error('nama_instansi')
                                    <div class="invalid-feedback d-block" style="font-size:.78rem;">{{ $message }}</div>
                                @enderror
                            </div>

                             {{-- Informasi Event--}}
                            <div class="mb-3">
                                <label class="form-label-custom" for="info">
                                    Informasi Event<span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       id="info"
                                       name="info"
                                       value="{{ old('info') }}"
                                       class="form-control form-control-custom @error('info') is-invalid @enderror"
                                       placeholder="Medsos, Blog Flavory.id,.."
                                       required>
                                @error('info')
                                    <div class="invalid-feedback d-block" style="font-size:.78rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn-daftar" id="btnDaftar">
                                <i class="bi bi-send-fill me-2"></i> Daftar & Bergabung ke Grup WA
                            </button>

                            <p class="text-center text-muted mt-3 small">
                                <i class="bi bi-shield-check text-success me-1"></i>
                                Data Anda aman dan tidak akan disalahgunakan.
                            </p>
                        </form>
                    @endif

                    {{-- Kembali ke daftar event --}}
                    <a href="{{ route('event.index') }}"
                       class="d-flex align-items-center justify-content-center gap-2 mt-3 text-muted small text-decoration-none">
                        <i class="bi bi-arrow-left"></i> Lihat event lainnya
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Logic Togel Form Kondisional Kategori Peserta
const categorySelect = document.getElementById('participant_category');
const groupNamaUsaha = document.getElementById('group_nama_usaha');
const groupNamaInstansi = document.getElementById('group_nama_instansi');
const inputNamaUsaha = document.getElementById('nama_usaha');
const inputNamaInstansi = document.getElementById('nama_instansi');

function toggleFields() {
    const val = categorySelect.value;
    
    if (val === 'pengusaha_kuliner' || val === 'pengusaha_non_kuliner') {
        groupNamaUsaha.style.display = 'block';
        inputNamaUsaha.required = true;
        
        groupNamaInstansi.style.display = 'none';
        inputNamaInstansi.required = false;
    } else if (val === 'pelajar') {
        groupNamaUsaha.style.display = 'none';
        inputNamaUsaha.required = false;
        
        groupNamaInstansi.style.display = 'block';
        inputNamaInstansi.required = true;
    } else {
        groupNamaUsaha.style.display = 'none';
        inputNamaUsaha.required = false;
        
        groupNamaInstansi.style.display = 'none';
        inputNamaInstansi.required = false;
    }
}

if (categorySelect) {
    categorySelect.addEventListener('change', toggleFields);
    toggleFields(); // Jalankan sekali saat page load untuk cek 'old value'
}

// Logic Animasi Panah Dropdown Kategori
const selectEl = document.getElementById('participant_category');
const arrowEl = document.getElementById('category_arrow');

if (selectEl && arrowEl) {
    selectEl.addEventListener('focus', () => {
        arrowEl.style.transform = 'translateY(-50%) rotate(180deg)';
    });
    
    selectEl.addEventListener('blur', () => {
        arrowEl.style.transform = 'translateY(-50%) rotate(0deg)';
    });
    
    selectEl.addEventListener('change', () => {
        selectEl.blur(); // Otomatis tutup focus dan kembalikan panah ke bawah setelah memilih
    });
}

document.getElementById('formDaftar')?.addEventListener('submit', function () {
    const btn = document.getElementById('btnDaftar');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Mendaftarkan...';
});
</script>
@endpush
