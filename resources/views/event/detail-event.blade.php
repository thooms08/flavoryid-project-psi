{{-- resources/views/event/detail-event.blade.php --}}
@extends('layouts.app')

@section('title', $event->event_name . ' | Event Flavory.id')
@section('meta_description', Str::limit(strip_tags($event->description), 155))
@section('meta_og_type', 'article')
@section('meta_og_url', route('event.show', $event->slug ?? $event->uuid))
@section('meta_image', $event->image ? secure_url('assets/event/' . $event->image) : secure_url('assets/event-preview.jpeg'))

@push('styles')
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

    /* ── Floating CTA (mobile only) - tombol kecil sebelum sheet dibuka ── */
    /* ── Floating CTA & Container (mobile only) ── */
    .floating-cta {
        display: none;
    }
    
    @media (max-width: 991.98px) {
        /* Kontainer putih w-100 yang fixed di bawah */
        .mobile-bottom-container {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #ffffff;
            padding: 16px 20px;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.08);
            z-index: 998;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        /* Tombol Daftar dibikin full-width */
        .floating-cta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            background: var(--primary-orange);
            color: #fff;
            font-size: 0.95rem;
            font-weight: 700;
            padding: 14px 28px;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(255,106,0,.45);
            text-decoration: none;
            white-space: nowrap;
        }
        
        .floating-cta.hide, .mobile-bottom-container.hide {
            opacity: 0 !important;
            visibility: hidden !important;
            pointer-events: none;
        }

        /* 
         * PENTING: Ganti ".class-tombol-wa-kamu" dengan nama class 
         * yang ada di dalam file layouts.wa milikmu. 
         * Ini gunanya untuk menaikkan posisi WA biar nggak ketutupan bar putih.
         */
        .class-tombol-wa-kamu {
            bottom: 100px !important; /* Disesuaikan agar pas di atas kontainer putih */
            z-index: 999 !important;
        }

        /* Tambah ruang di bawah hero biar konten terbawah nggak ketutup bar putih */
        .detail-hero { padding-bottom: 130px !important; }
    }
    /* ── Bottom Sheet Form (mobile only) ── */
    .form-sheet-overlay {
        display: none;
    }
    @media (max-width: 991.98px) {
        /* Overlay gelap di belakang sheet */
        .form-sheet-overlay {
            display: block;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.45);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: opacity .3s ease, visibility .3s ease;
        }
        .form-sheet-overlay.open {
            opacity: 1;
            visibility: visible;
        }

        /* Container sheet yang naik dari bawah */
        .form-bottom-sheet {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1050;
            background: #fff;
            border-radius: 24px 24px 0 0;
            box-shadow: 0 -8px 40px rgba(0,0,0,.15);
            max-height: 90dvh;          /* maksimal 90% tinggi viewport */
            overflow-y: auto;
            padding: 0 20px 32px;
            transform: translateY(100%);
            transition: transform .4s cubic-bezier(.32,.72,0,1);
            -webkit-overflow-scrolling: touch;
        }
        .form-bottom-sheet.open {
            transform: translateY(0);
        }

        /* Handle tarik atas */
        .sheet-handle {
            width: 40px;
            height: 4px;
            background: #cbd5e1;
            border-radius: 4px;
            margin: 14px auto 20px;
        }

        /* Header sheet */
        .sheet-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .sheet-header-title {
            font-size: 1rem;
            font-weight: 800;
            color: #1a202c;
            margin: 0;
        }
        .sheet-close-btn {
            background: #f1f5f9;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: #475569;
            cursor: pointer;
            flex-shrink: 0;
        }
        .sheet-close-btn:active { background: #e2e8f0; }

        /* Sembunyikan form card asli di mobile agar tidak double */
        .col-lg-5 .form-card {
            display: none;
        }
    }
    .share-bar {
        display: flex;
        align-items: center;
    }
    .share-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 20px;
        border-radius: 50px;
        font-size: .85rem;
        font-weight: 600;
        background: #f1f5f9;
        color: #475569;
        border: 1.5px solid #e2e8f0;
        cursor: pointer;
        transition: all .2s ease;
        white-space: nowrap;
        text-decoration: none;
    }
    .share-btn i { font-size: 1rem; }
    .share-btn:hover {
        background: var(--primary-orange);
        color: #fff;
        border-color: var(--primary-orange);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255,106,0,.25);
    }
    .share-btn.copied {
        background: #22c55e;
        color: #fff;
        border-color: #22c55e;
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

    // Event "ditutup" jika: inactive (admin tutup manual), sedang berlangsung, atau sudah selesai
    $isSudahDitutup = $isClosed || $isOngoing || $isEnded;

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
    }
@endphp

<div class="detail-hero pb-5">
    <div class="container">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-orange text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('event.index') }}" class="text-orange text-decoration-none">Event</a></li>
                <!--<li class="breadcrumb-item active text-muted" aria-current="page">{{ Str::limit($event->event_name, 40) }}</li> -->           </ol>
        </nav>

        {{-- ============================================================ --}}
        {{-- TAMPILAN KHUSUS: Event sudah ditutup (inactive / berlangsung / selesai) --}}
        {{-- ============================================================ --}}
        @if($isSudahDitutup)
        <div class="row justify-content-center py-5">
            <div class="col-lg-7 col-md-9 text-center">

                <div style="
                    background: #fef2f2;
                    border: 2px solid #fecaca;
                    border-radius: 24px;
                    padding: 60px 40px;
                    box-shadow: 0 8px 30px rgba(239,68,68,.08);
                ">
                    <div style="
                        width: 80px; height: 80px;
                        background: #fee2e2;
                        border-radius: 50%;
                        display: flex; align-items: center; justify-content: center;
                        margin: 0 auto 24px;
                    ">
                        <i class="bi bi-lock-fill" style="font-size: 2rem; color: #dc2626;"></i>
                    </div>

                    <h2 class="fw-bold mb-3" style="font-size: 1.5rem; color: #991b1b; line-height: 1.4;">
                        EVENT {{ strtoupper($event->event_name) }} SUDAH DITUTUP
                    </h2>

                    <p class="text-muted mb-4" style="font-size: .92rem;">
                        @if($isEnded)
                            Event ini telah berakhir pada
                            <strong>{{ \Carbon\Carbon::parse($event->event_end_date)->translatedFormat('d F Y') }}</strong>.
                        @elseif($isOngoing)
                            Event ini sedang berlangsung dan pendaftaran peserta baru telah ditutup.
                        @else
                            Pendaftaran untuk event ini telah ditutup oleh penyelenggara.
                        @endif
                    </p>

                    <a href="{{ route('event.index') }}"
                       class="btn"
                       style="
                           background: #dc2626; color: #fff;
                           border-radius: 50px;
                           padding: 12px 32px;
                           font-weight: 700;
                           font-size: .9rem;
                           text-decoration: none;
                           display: inline-flex; align-items: center; gap: 8px;
                       ">
                        <i class="bi bi-arrow-left"></i> Lihat Event Lainnya
                    </a>
                </div>

            </div>
        </div>
        @else
        {{-- ============================================================ --}}
        {{-- TAMPILAN NORMAL: Event masih aktif dan belum mulai --}}
        {{-- ============================================================ --}}
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
                    @if($event->event_start_time)
                    <span class="info-badge">
                        <i class="bi bi-clock"></i>
                        {{ \Carbon\Carbon::parse($event->event_start_time)->format('H:i') }}
                        @if($event->event_end_time)
                            &ndash; {{ \Carbon\Carbon::parse($event->event_end_time)->format('H:i') }} WIB
                        @else
                            WIB
                        @endif
                    </span>
                    @endif
                    @if($event->location)
                    <span class="info-badge">
                        <i class="bi bi-geo-alt-fill"></i>
                        {{ $event->location }}
                    </span>
                    @endif
                    <span class="info-badge">
                        <i class="bi bi-people-fill"></i>
                        {{ $pesertaCount }} Peserta
                    </span>
                </div>

                {{-- Share Button --}}
                <div class="share-bar mb-4">
                    <button type="button" id="btnShare" class="share-btn" aria-label="Bagikan event ini">
                        <i class="bi bi-share-fill" id="shareIcon"></i>
                        <span id="shareLabel">Bagikan Event</span>
                    </button>
                </div>

                {{-- Deskripsi --}}
                <div class="event-description">
                    {!! nl2br(e($event->description)) !!}
                </div>

            </div>

            {{-- Kanan: Form Pendaftaran --}}
            <div class="col-lg-5">
                <div class="form-card" id="formPendaftaran">

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
        @endif
        {{-- /@if($isSudahDitutup) ... @else ... @endif --}}
    </div>
</div>

{{-- Floating CTA & WhatsApp (mobile only) - hanya tampil jika event masih bisa didaftar --}}
@if(!$isSudahDitutup)
    
    {{-- Memanggil komponen tombol WA bawaan --}}
    @include('layouts.wa')

    {{-- Kontainer Putih (Fixed Bottom) --}}
    <div class="mobile-bottom-container" id="mobileBottomContainer">
        <button type="button" id="floatingCta" class="floating-cta" aria-label="Buka formulir pendaftaran">
            <i class="bi bi-pencil-square"></i> Daftar Sekarang
        </button>
    </div>
@endif

{{-- ── Bottom Sheet Form (mobile only) - hanya jika event masih bisa didaftar ── --}}
@if(!$isSudahDitutup)
<div id="formSheetOverlay" class="form-sheet-overlay" aria-hidden="true"></div>

<div id="formBottomSheet" class="form-bottom-sheet" role="dialog" aria-modal="true" aria-label="Formulir Pendaftaran">
    <div class="sheet-handle" aria-hidden="true"></div>
    <div class="sheet-header">
        <p class="sheet-header-title">
            <i class="bi bi-pencil-square text-orange me-2"></i>Formulir Pendaftaran
        </p>
        <button type="button" class="sheet-close-btn" id="sheetCloseBtn" aria-label="Tutup formulir">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    {{-- Isi form identik dengan yang di desktop --}}
    @if($isEnded)
        <div class="closed-banner">
            <i class="bi bi-lock-fill d-block mb-2" style="font-size:2rem;"></i>
            <strong>Pendaftaran Ditutup</strong>
            <p class="small mb-0 mt-1">Event ini sudah berakhir pada {{ \Carbon\Carbon::parse($event->event_end_date)->translatedFormat('d M Y') }}.</p>
        </div>
    @elseif($isClosed)
        <div class="closed-banner">
            <i class="bi bi-x-circle-fill d-block mb-2" style="font-size:2rem;"></i>
            <strong>Event Telah Ditutup</strong>
            <p class="small mb-0 mt-1">Pendaftaran untuk event ini telah ditutup oleh penyelenggara.</p>
        </div>
    @else
        {{-- Jumlah peserta --}}
        <div class="peserta-count">
            <i class="bi bi-people-fill"></i>
            <div>
                <strong>{{ $pesertaCount }} orang sudah mendaftar</strong><br>
                <span>Segera daftar sebelum tempat penuh!</span>
            </div>
        </div>

        <form action="{{ route('event.daftar', $event->slug ?? $event->uuid) }}" method="POST" id="formDaftarSheet">
            @csrf

            {{-- Nama --}}
            <div class="mb-3">
                <label class="form-label-custom" for="sheet_name">
                    Nama Lengkap <span class="text-danger">*</span>
                </label>
                <input type="text" id="sheet_name" name="name"
                       value="{{ old('name') }}"
                       class="form-control form-control-custom @error('name') is-invalid @enderror"
                       placeholder="Contoh: Budi Santoso" required>
                @error('name')
                    <div class="invalid-feedback d-block" style="font-size:.78rem;">{{ $message }}</div>
                @enderror
            </div>

            {{-- Nomor WhatsApp --}}
            <div class="mb-3">
                <label class="form-label-custom" for="sheet_wa">
                    Nomor WhatsApp Aktif <span class="text-danger">*</span>
                </label>
                <input type="number" id="sheet_wa" name="wa"
                       value="{{ old('wa') }}"
                       class="form-control form-control-custom @error('wa') is-invalid @enderror"
                       placeholder="Contoh: 08123456789" inputmode="numeric" required>
                @error('wa')
                    <div class="invalid-feedback d-block" style="font-size:.78rem;">{{ $message }}</div>
                @enderror
            </div>

            {{-- Kategori Peserta --}}
            <div class="mb-4">
                <label class="form-label-custom" for="sheet_participant_category">
                    Kategori Peserta <span class="text-danger">*</span>
                </label>
                <div class="position-relative">
                    <select id="sheet_participant_category" name="participant_category"
                            class="form-select form-control-custom @error('participant_category') is-invalid @enderror"
                            style="background-image:none !important; padding-right:40px;" required>
                        <option value="" disabled {{ old('participant_category') ? '' : 'selected' }}>-- Pilih kategori --</option>
                        <option value="pengusaha_kuliner"     {{ old('participant_category')==='pengusaha_kuliner'     ?'selected':'' }}>Pengusaha Kuliner</option>
                        <option value="pengusaha_non_kuliner" {{ old('participant_category')==='pengusaha_non_kuliner' ?'selected':'' }}>Pengusaha Non-Kuliner</option>
                        <option value="pelajar"               {{ old('participant_category')==='pelajar'               ?'selected':'' }}>Pelajar / Mahasiswa</option>
                        <option value="umum"                  {{ old('participant_category')==='umum'                  ?'selected':'' }}>Umum</option>
                    </select>
                    <i id="sheet_category_arrow" class="bi bi-chevron-down position-absolute"
                       style="right:15px; top:50%; transform:translateY(-50%); pointer-events:none; transition:transform .2s; color:#475569; font-size:.85rem;"></i>
                </div>
                @error('participant_category')
                    <div class="invalid-feedback d-block" style="font-size:.78rem;">{{ $message }}</div>
                @enderror
            </div>

            {{-- Nama Usaha (kondisional) --}}
            <div id="sheet_group_nama_usaha" class="mb-3" style="display:none;">
                <label class="form-label-custom" for="sheet_nama_usaha">
                    Nama Usaha <span class="text-danger">*</span>
                </label>
                <input type="text" id="sheet_nama_usaha" name="nama_usaha"
                       value="{{ old('nama_usaha') }}"
                       class="form-control form-control-custom @error('nama_usaha') is-invalid @enderror"
                       placeholder="Contoh: Kopi Nusantara">
                @error('nama_usaha')
                    <div class="invalid-feedback d-block" style="font-size:.78rem;">{{ $message }}</div>
                @enderror
            </div>

            {{-- Nama Instansi (kondisional) --}}
            <div id="sheet_group_nama_instansi" class="mb-3" style="display:none;">
                <label class="form-label-custom" for="sheet_nama_instansi">
                    Nama Instansi / Sekolah / Universitas <span class="text-danger">*</span>
                </label>
                <input type="text" id="sheet_nama_instansi" name="nama_instansi"
                       value="{{ old('nama_instansi') }}"
                       class="form-control form-control-custom @error('nama_instansi') is-invalid @enderror"
                       placeholder="Contoh: Universitas Indonesia">
                @error('nama_instansi')
                    <div class="invalid-feedback d-block" style="font-size:.78rem;">{{ $message }}</div>
                @enderror
            </div>

            {{-- Informasi Event --}}
            <div class="mb-3">
                <label class="form-label-custom" for="sheet_info">
                    Informasi Event <span class="text-danger">*</span>
                </label>
                <input type="text" id="sheet_info" name="info"
                       value="{{ old('info') }}"
                       class="form-control form-control-custom @error('info') is-invalid @enderror"
                       placeholder="Medsos, Blog Flavory.id,.." required>
                @error('info')
                    <div class="invalid-feedback d-block" style="font-size:.78rem;">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-daftar" id="btnDaftarSheet">
                <i class="bi bi-send-fill me-2"></i> Daftar & Bergabung ke Grup WA
            </button>

            <p class="text-center text-muted mt-3 small">
                <i class="bi bi-shield-check text-success me-1"></i>
                Data Anda aman dan tidak akan disalahgunakan.
            </p>
        </form>
    @endif
</div>
@endif
{{-- /@if(!$isSudahDitutup) bottom sheet --}}

@endsection

@push('scripts')
<script>
    // ── Kondisional Field (Desktop form) ──
const categorySelect = document.getElementById('participant_category');
const groupNamaUsaha = document.getElementById('group_nama_usaha');
const groupNamaInstansi = document.getElementById('group_nama_instansi');
const inputNamaUsaha = document.getElementById('nama_usaha');
const inputNamaInstansi = document.getElementById('nama_instansi');

function toggleFields() {
    const val = categorySelect?.value;
    if (!val) return;
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
    toggleFields();
}

// ── Kondisional Field (Sheet / Mobile form) ──
const sheetCategorySelect  = document.getElementById('sheet_participant_category');
const sheetGroupNamaUsaha  = document.getElementById('sheet_group_nama_usaha');
const sheetGroupNamaInstansi = document.getElementById('sheet_group_nama_instansi');
const sheetInputNamaUsaha   = document.getElementById('sheet_nama_usaha');
const sheetInputNamaInstansi = document.getElementById('sheet_nama_instansi');

function sheetToggleFields() {
    const val = sheetCategorySelect?.value;
    if (!val) return;
    if (val === 'pengusaha_kuliner' || val === 'pengusaha_non_kuliner') {
        sheetGroupNamaUsaha.style.display = 'block';
        sheetInputNamaUsaha.required = true;
        sheetGroupNamaInstansi.style.display = 'none';
        sheetInputNamaInstansi.required = false;
    } else if (val === 'pelajar') {
        sheetGroupNamaUsaha.style.display = 'none';
        sheetInputNamaUsaha.required = false;
        sheetGroupNamaInstansi.style.display = 'block';
        sheetInputNamaInstansi.required = true;
    } else {
        sheetGroupNamaUsaha.style.display = 'none';
        sheetInputNamaUsaha.required = false;
        sheetGroupNamaInstansi.style.display = 'none';
        sheetInputNamaInstansi.required = false;
    }
}
if (sheetCategorySelect) {
    sheetCategorySelect.addEventListener('change', sheetToggleFields);
    sheetToggleFields();
}

// ── Animasi Panah Dropdown (Desktop) ──
const selectEl = document.getElementById('participant_category');
const arrowEl  = document.getElementById('category_arrow');
if (selectEl && arrowEl) {
    selectEl.addEventListener('focus',  () => arrowEl.style.transform = 'translateY(-50%) rotate(180deg)');
    selectEl.addEventListener('blur',   () => arrowEl.style.transform = 'translateY(-50%) rotate(0deg)');
    selectEl.addEventListener('change', () => selectEl.blur());
}

// ── Animasi Panah Dropdown (Sheet) ──
const sheetSelectEl = document.getElementById('sheet_participant_category');
const sheetArrowEl  = document.getElementById('sheet_category_arrow');
if (sheetSelectEl && sheetArrowEl) {
    sheetSelectEl.addEventListener('focus',  () => sheetArrowEl.style.transform = 'translateY(-50%) rotate(180deg)');
    sheetSelectEl.addEventListener('blur',   () => sheetArrowEl.style.transform = 'translateY(-50%) rotate(0deg)');
    sheetSelectEl.addEventListener('change', () => sheetSelectEl.blur());
}

// ── Submit spinner (Desktop) ──
document.getElementById('formDaftar')?.addEventListener('submit', function () {
    const btn = document.getElementById('btnDaftar');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Mendaftarkan...';
});

// ── Submit spinner (Sheet) ──
document.getElementById('formDaftarSheet')?.addEventListener('submit', function () {
    const btn = document.getElementById('btnDaftarSheet');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Mendaftarkan...';
});

// ── Bottom Sheet Logic ──
const floatingCta     = document.getElementById('floatingCta');
const sheetOverlay    = document.getElementById('formSheetOverlay');
const bottomSheet     = document.getElementById('formBottomSheet');
const sheetCloseBtn   = document.getElementById('sheetCloseBtn');

function openSheet() {
    bottomSheet?.classList.add('open');
    sheetOverlay?.classList.add('open');
    document.body.style.overflow = 'hidden';  // cegah scroll background
    // Fokus ke input pertama agar keyboard muncul
    setTimeout(() => document.getElementById('sheet_name')?.focus(), 350);
}

function closeSheet() {
    bottomSheet?.classList.remove('open');
    sheetOverlay?.classList.remove('open');
    document.body.style.overflow = '';
}

if (floatingCta) {
    floatingCta.addEventListener('click', function (e) {
        e.preventDefault();
        // Di desktop (>991px), tidak ada sheet - scroll ke form saja
        if (window.innerWidth > 991) {
            document.getElementById('formPendaftaran')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } else {
            openSheet();
        }
    });
}

sheetCloseBtn?.addEventListener('click', closeSheet);
sheetOverlay?.addEventListener('click', closeSheet);

// Tutup sheet dengan swipe ke bawah
let sheetTouchStartY = 0;
bottomSheet?.addEventListener('touchstart', e => { sheetTouchStartY = e.touches[0].clientY; }, { passive: true });
bottomSheet?.addEventListener('touchend',   e => {
    const deltaY = e.changedTouches[0].clientY - sheetTouchStartY;
    if (deltaY > 80) closeSheet();
}, { passive: true });

// Tutup sheet dengan tombol Escape
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSheet(); });

// ── Floating CTA: hilang saat form desktop sudah terlihat (hanya berlaku di desktop) ──
const formTarget = document.getElementById('formPendaftaran');
if (floatingCta && formTarget) {
    function checkFormVisibility() {
        if (window.innerWidth > 991) {
            const rect = formTarget.getBoundingClientRect();
            const isVisible = rect.top < window.innerHeight * 0.85;
            floatingCta.classList.toggle('hide', isVisible);
        } else {
            floatingCta.classList.remove('hide'); // selalu tampil di mobile
        }
    }
    window.addEventListener('scroll', checkFormVisibility, { passive: true });
    window.addEventListener('resize', checkFormVisibility, { passive: true });
    checkFormVisibility();
}

// ── Web Share API ──
const btnShare   = document.getElementById('btnShare');
const shareIcon  = document.getElementById('shareIcon');
const shareLabel = document.getElementById('shareLabel');

if (btnShare) {
    const shareData = {
        title: '{{ addslashes($event->event_name) }} | Event Flavory.id',
        text:  'Yuk ikuti event ini! {{ addslashes(Str::limit(strip_tags($event->description), 100)) }}',
        url:   '{{ route('event.show', $event->slug ?? $event->uuid) }}',
    };

    btnShare.addEventListener('click', async function () {
        if (navigator.share) {
            try { await navigator.share(shareData); } catch (err) {}
        } else {
            try {
                await navigator.clipboard.writeText(shareData.url);
            } catch {
                const el = document.createElement('textarea');
                el.value = shareData.url;
                el.style.position = 'fixed';
                el.style.opacity  = '0';
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
            }
            btnShare.classList.add('copied');
            shareIcon.className    = 'bi bi-check-lg';
            shareLabel.textContent = 'Link Tersalin!';
            setTimeout(() => {
                btnShare.classList.remove('copied');
                shareIcon.className    = 'bi bi-share-fill';
                shareLabel.textContent = 'Bagikan Event';
            }, 2500);
        }
    });
}
</script>
@endpush
