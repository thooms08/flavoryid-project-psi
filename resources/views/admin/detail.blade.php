@extends('admin.layouts.app')

@section('content')
<style>
    /* === VARIABEL WARNA FALLBACK === */
    :root {
        --bg-dark: #0B1120;
        --accent-cyan: #00E5FF;
        --accent-blue: #3B82F6;
        --text-primary: #F8FAFC;
        --bg-card: rgba(15, 23, 42, 0.7);
    }

    /* === BACKGROUND LAYER === */
    .cyber-grid-bg {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
        z-index: -3; background-color: var(--bg-dark);
        background-image: linear-gradient(rgba(0, 229, 255, 0.05) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(0, 229, 255, 0.05) 1px, transparent 1px);
        background-size: 50px 50px; animation: gridMove 30s linear infinite; pointer-events: none;
    }
    .cyber-grid-overlay {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -2;
        background: radial-gradient(circle at center, transparent 10%, var(--bg-dark) 100%);
        pointer-events: none;
    }
    #particles-container {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -1;
        pointer-events: none; overflow: hidden;
    }
    .cyber-particle {
        position: absolute; background: var(--accent-cyan);
        border-radius: 50%; box-shadow: 0 0 12px var(--accent-cyan); opacity: 0;
    }
    @keyframes gridMove { 0% { background-position: 0 0; } 100% { background-position: 50px 50px; } }

    /* === CARD & KONTEN === */
    .cyber-anim-container { position: relative; z-index: 10; }
    
    .cyber-card {
        background: var(--bg-card);
        backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease; position: relative; overflow: hidden;
    }
    .cyber-card::before {
        content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 3px;
        background: linear-gradient(90deg, var(--accent-cyan), var(--accent-blue));
        transform: scaleX(0); transform-origin: left; transition: transform 0.5s ease;
    }
    .cyber-card:hover::before { transform: scaleX(1); }
    .cyber-card:hover { transform: translateY(-5px); box-shadow: 0 12px 30px rgba(0, 229, 255, 0.15); }

    /* === DATA ROW STYLING === */
    .data-row {
        background: rgba(255, 255, 255, 0.03);
        border-left: 3px solid rgba(0, 229, 255, 0.3);
        padding: 1rem; border-radius: 8px; margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    .data-row:hover {
        background: rgba(0, 229, 255, 0.05);
        border-left-color: var(--accent-cyan);
    }
    .data-label {
        font-size: 0.7rem; letter-spacing: 1px; color: #94A3B8;
        text-transform: uppercase; font-weight: 700; margin-bottom: 0.3rem; display: block;
    }
    .data-value {
        font-size: 1.05rem; color: var(--text-primary); font-weight: 500; word-break: break-word;
    }
    .btn-back {
        background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2);
        color: var(--text-primary); transition: all 0.3s;
    }
    .btn-back:hover {
        background: rgba(255, 255, 255, 0.2); color: #fff;
    }
    
    /* Image Preview Styling */
    .img-preview-box {
        width: 100%; max-width: 150px; height: 150px;
        object-fit: cover; border-radius: 8px;
        border: 1px solid rgba(0, 229, 255, 0.3);
        background: rgba(0,0,0,0.2);
    }
    
    .social-link {
        color: var(--accent-cyan); text-decoration: none; transition: 0.3s;
    }
    .social-link:hover { color: #fff; text-shadow: 0 0 8px var(--accent-cyan); }
</style>

<div class="cyber-grid-bg"></div>
<div class="cyber-grid-overlay"></div>
<div id="particles-container"></div>

<div class="container-fluid cyber-anim-container pb-5">
    <div class="row mb-4 align-items-center cyber-header">
        <div class="col-md-8 mb-3 mb-md-0">
            <h3 class="fw-bold" style="color: var(--accent-cyan); letter-spacing: 1px;">Detail Partner</h3>
            <p class="text-secondary mb-0">Informasi komprehensif akun, profil outlet, dan riwayat tagihan.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ url()->previous() }}" class="btn btn-back px-4 py-2 rounded-pill fw-bold" style="font-size: 0.85rem; letter-spacing: 1px;">
                <i class="fa-solid fa-arrow-left me-2"></i> KEMBALI
            </a>
        </div>
    </div>

    <div class="row" id="detail-cards">
        
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card cyber-card cyber-detail-item mb-4">
                <div class="card-header border-0 bg-transparent pt-4 pb-0 px-4">
                    <h5 class="fw-bold text-uppercase" style="color: var(--accent-blue); font-size: 0.9rem; letter-spacing: 2px;">
                        <i class="fa-solid fa-user-tie me-2"></i> Akun Pengelola
                    </h5>
                    <hr style="border-color: rgba(0,229,255,0.2);">
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="data-row">
                        <span class="data-label">Nama Manager</span>
                        <span class="data-value">{{ $partner->nama_manager ?? '---' }}</span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">Username Outlet</span>
                        <span class="data-value" style="color: var(--accent-cyan);">{{ $partner->user->username ?? '---' }}</span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">UUID Partner</span>
                        <span class="data-value" style="font-family: monospace; color: var(--accent-cyan); font-size: 0.9rem;">{{ $partner->uuid }}</span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">Email</span>
                        <span class="data-value">{{ $partner->email ?? '---' }}</span>
                    </div>
                    <div class="data-row mb-0">
                        <span class="data-label">No. WhatsApp</span>
                        <span class="data-value">{{ $partner->no_wa ?? '---' }}</span>
                    </div>
                </div>
            </div>

            <div class="card cyber-card cyber-detail-item">
                <div class="card-header border-0 bg-transparent pt-4 pb-0 px-4">
                    <h5 class="fw-bold text-uppercase" style="color: var(--accent-blue); font-size: 0.9rem; letter-spacing: 2px;">
                        <i class="fa-solid fa-hashtag me-2"></i> Sosial Media Outlet
                    </h5>
                    <hr style="border-color: rgba(0,229,255,0.2);">
                </div>
                <div class="card-body px-4 pb-4">
                    @if($partner->outlet && $partner->outlet->mediaQr)
                        @php $qr = $partner->outlet->mediaQr; @endphp
                        <div class="d-flex flex-column gap-3">
                            <div><i class="fa-brands fa-facebook fa-fw me-2" style="color: #1877F2;"></i> <a href="{{ $qr->facebook ?? '#' }}" target="_blank" class="social-link">{{ $qr->facebook ?? '---' }}</a></div>
                            <div><i class="fa-brands fa-instagram fa-fw me-2" style="color: #E4405F;"></i> <a href="{{ $qr->instagram ?? '#' }}" target="_blank" class="social-link">{{ $qr->instagram ?? '---' }}</a></div>
                            <div><i class="fa-brands fa-youtube fa-fw me-2" style="color: #FF0000;"></i> <a href="{{ $qr->youtube ?? '#' }}" target="_blank" class="social-link">{{ $qr->youtube ?? '---' }}</a></div>
                            <div><i class="fa-brands fa-tiktok fa-fw me-2" style="color: #fff;"></i> <a href="{{ $qr->tiktok ?? '#' }}" target="_blank" class="social-link">{{ $qr->tiktok ?? '---' }}</a></div>
                            <div><i class="fa-brands fa-x-twitter fa-fw me-2" style="color: #fff;"></i> <a href="{{ $qr->x ?? '#' }}" target="_blank" class="social-link">{{ $qr->x ?? '---' }}</a></div>
                        </div>
                    @else
                        <p class="text-secondary mb-0"><i class="fa-solid fa-circle-info me-2"></i>Data sosial media belum tersedia.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card cyber-card cyber-detail-item mb-4">
                <div class="card-header border-0 bg-transparent pt-4 pb-0 px-4">
                    <h5 class="fw-bold text-uppercase" style="color: var(--accent-blue); font-size: 0.9rem; letter-spacing: 2px;">
                        <i class="fa-solid fa-store me-2"></i> Profil Outlet
                    </h5>
                    <hr style="border-color: rgba(0,229,255,0.2);">
                </div>
                <div class="card-body px-4 pb-4">
                    @if($partner->outlet)
                        <div class="row">
                            <div class="col-md-12 mb-4 d-flex gap-4 flex-wrap">
                                <div>
                                    <span class="data-label">Logo Outlet</span>
                                    @if($partner->outlet->logo)
                                        <img src="{{ asset('assets/logo/' . $partner->outlet->logo) }}" alt="Logo" class="img-preview-box">
                                    @else
                                        <div class="img-preview-box d-flex align-items-center justify-content-center text-secondary">No Logo</div>
                                    @endif
                                </div>
                                <div>
                                    <span class="data-label">Foto Outlet</span>
                                    @if($partner->outlet->foto_outlet)
                                        <img src="{{ asset('assets/outlet/' . $partner->outlet->foto_outlet) }}" alt="Foto" class="img-preview-box" style="max-width: 250px;">
                                    @else
                                        <div class="img-preview-box d-flex align-items-center justify-content-center text-secondary" style="max-width: 250px;">No Photo</div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="data-row">
                                    <span class="data-label">Nama Outlet</span>
                                    <span class="data-value fw-bold text-white">{{ $partner->outlet->nama_outlet ?? '---' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="data-row">
                                    <span class="data-label">Jenis Outlet</span>
                                    <span class="data-value text-uppercase">
                                        {{ strtolower($partner->outlet->jenis_outlet) === 'lainnya' ? ($partner->outlet->jenis_lainnya ?? 'Lainnya') : ($partner->outlet->jenis_outlet ?? '---') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="data-row">
                                    <span class="data-label">Page Name (URL)</span>
                                    <span class="data-value" style="color: var(--accent-cyan);">{{ $partner->outlet->page_name ?? '---' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="data-row">
                                    <span class="data-label">Deskripsi</span>
                                    <span class="data-value" style="font-size: 0.9rem;">{{ $partner->outlet->deskripsi ?? '---' }}</span>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="data-row mb-0">
                                    <span class="data-label">Lokasi & Alamat</span>
                                    <span class="data-value d-block mb-2">{{ $partner->outlet->alamat_lengkap ?? '---' }}</span>
                                    <span class="badge bg-secondary me-2">{{ $partner->outlet->kota ?? 'Kota Tdk Disebut' }}</span>
                                    <span class="badge bg-secondary">{{ $partner->outlet->provinsi ?? 'Provinsi Tdk Disebut' }}</span>
                                    
                                    @if($partner->outlet->maps)
                                        <a href="{{ $partner->outlet->maps }}" target="_blank" class="btn btn-sm btn-outline-info mt-3 d-block w-100" style="border-color: var(--accent-cyan); color: var(--accent-cyan);">
                                            <i class="fa-solid fa-map-location-dot me-2"></i>Buka di Google Maps
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fa-solid fa-store-slash fa-2x mb-3" style="color: rgba(255,255,255,0.2);"></i>
                            <p class="text-secondary">Partner ini belum melengkapi data outlet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card cyber-card cyber-detail-item">
                <div class="card-header border-0 bg-transparent pt-4 pb-0 px-4">
                    <h5 class="fw-bold text-uppercase" style="color: var(--accent-blue); font-size: 0.9rem; letter-spacing: 2px;">
                        <i class="fa-solid fa-file-invoice-dollar me-2"></i> Langganan & Tagihan
                    </h5>
                    <hr style="border-color: rgba(0,229,255,0.2);">
                </div>
                <div class="card-body px-4 pb-4">
                    @if($partner->outlet && $partner->outlet->mediaQr)
                        @php
                            $qr = $partner->outlet->mediaQr;
                            $statusSistem = strtolower($qr->status);
                            $badgeStatus = 'bg-secondary';
                            if($statusSistem === 'aktif') $badgeStatus = 'bg-success';
                            if($statusSistem === 'jatuh_tempo') $badgeStatus = 'bg-warning text-dark';
                            if($statusSistem === 'nonaktif') $badgeStatus = 'bg-danger';

                            $payStatus = strtolower($qr->payment_status);
                            $badgePay = 'bg-secondary';
                            if(in_array($payStatus, ['paid', 'success', 'sukses'])) $badgePay = 'bg-success';
                            if(in_array($payStatus, ['pending', 'unpaid'])) $badgePay = 'bg-warning text-dark';
                            if(in_array($payStatus, ['failed', 'expired'])) $badgePay = 'bg-danger';
                        @endphp
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="data-row">
                                    <span class="data-label">Status Sistem</span>
                                    <span class="badge {{ $badgeStatus }} px-3 py-2 mt-1" style="letter-spacing: 1px;">
                                        {{ strtoupper($qr->status ?? 'BELUM TERSET') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="data-row">
                                    <span class="data-label">Paket Langganan</span>
                                    <span class="data-value text-uppercase" style="color: var(--accent-cyan); font-weight: 700;">
                                        {{ $qr->paket ?? '---' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="data-row">
                                    <span class="data-label">Invoice Number</span>
                                    <span class="data-value" style="font-family: monospace;">{{ $qr->invoice_number ?? '---' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="data-row">
                                    <span class="data-label">Payment Status</span>
                                    <span class="badge {{ $badgePay }} px-3 py-2 mt-1">
                                        {{ strtoupper($qr->payment_status ?? 'UNKNOWN') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fa-solid fa-file-circle-xmark fa-2x mb-3" style="color: rgba(255,255,255,0.2);"></i>
                            <p class="text-secondary mb-0">Data tagihan dan QR belum dikonfigurasi.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@stack('scripts')
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. GENERATE PARTIKEL BACKGROUND
        const particleCount = 20; 
        const container = document.getElementById('particles-container');
        for (let i = 0; i < particleCount; i++) {
            let p = document.createElement('div');
            p.classList.add('cyber-particle');
            let size = Math.random() * 3 + 1;
            p.style.width = size + 'px';
            p.style.height = size + 'px';
            p.style.left = Math.random() * 100 + 'vw';
            p.style.top = Math.random() * 100 + 'vh';
            container.appendChild(p);
        }

        anime({
            targets: '.cyber-particle',
            translateX: () => anime.random(-30, 30),
            translateY: () => anime.random(-30, 30),
            scale: () => anime.random(0.5, 1.5),
            opacity: () => anime.random(0.1, 0.4),
            duration: () => anime.random(4000, 8000),
            delay: anime.stagger(200),
            direction: 'alternate',
            loop: true,
            easing: 'easeInOutSine'
        });

        // 2. ANIMASI ENTRANCE HEADER
        anime({
            targets: '.cyber-header',
            translateY: [-20, 0],
            opacity: [0, 1],
            easing: 'easeOutQuint',
            duration: 1000
        });

        // 3. ANIMASI ENTRANCE CARD DETAIL (STAGGERED)
        anime({
            targets: '.cyber-detail-item',
            translateY: [40, 0],
            opacity: [0, 1],
            delay: anime.stagger(150, {start: 200}), 
            easing: 'easeOutElastic(1, .8)',
            duration: 1200
        });
    });
</script>
@endpush