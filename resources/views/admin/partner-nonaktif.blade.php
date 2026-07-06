@extends('admin.layouts.app')

@section('content')
<style>
    /* === VARIABEL WARNA FALLBACK === */
    :root {
        --bg-dark: #0B1120;
        --accent-cyan: #00E5FF;
        --accent-blue: #3B82F6;
        --accent-danger: #EF4444; /* Merah untuk Nonaktif */
        --accent-danger-light: #FCA5A5;
        --text-primary: #F8FAFC;
        --bg-card: rgba(15, 23, 42, 0.7);
    }

    /* === BACKGROUND LAYER === */
    .cyber-grid-bg {
        position: fixed;
        top: 0; left: 0; width: 100vw; height: 100vh;
        z-index: -3; 
        background-color: var(--bg-dark);
        background-image: 
            linear-gradient(rgba(0, 229, 255, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 229, 255, 0.05) 1px, transparent 1px);
        background-size: 50px 50px;
        animation: gridMove 30s linear infinite;
        pointer-events: none;
    }

    .cyber-grid-overlay {
        position: fixed;
        top: 0; left: 0; width: 100vw; height: 100vh;
        z-index: -2;
        background: radial-gradient(circle at center, transparent 10%, var(--bg-dark) 100%);
        pointer-events: none;
    }

    #particles-container {
        position: fixed;
        top: 0; left: 0; width: 100vw; height: 100vh;
        z-index: -1;
        pointer-events: none;
        overflow: hidden;
    }

    .cyber-particle {
        position: absolute;
        background: var(--accent-danger); /* Partikel warna merah untuk halaman ini */
        border-radius: 50%;
        box-shadow: 0 0 12px var(--accent-danger);
        opacity: 0;
    }

    @keyframes gridMove {
        0% { background-position: 0 0; }
        100% { background-position: 50px 50px; }
    }

    /* === CARD & KONTEN === */
    .cyber-anim-container {
        position: relative;
        z-index: 10;
    }

    .cyber-card {
        background: var(--bg-card);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(239, 68, 68, 0.15); /* Border nuansa danger */
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .cyber-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 3px;
        /* Garis atas kartu warna gradient merah */
        background: linear-gradient(90deg, var(--accent-danger), var(--accent-danger-light));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.5s ease;
    }

    .cyber-card:hover::before {
        transform: scaleX(1);
    }

    /* === BUTTON STYLING === */
    .btn-cyber-success {
        background: rgba(16, 185, 129, 0.1);
        color: #34D399;
        border: 1px solid rgba(16, 185, 129, 0.4);
        transition: all 0.3s ease;
    }
    .btn-cyber-success:hover {
        background: rgba(16, 185, 129, 0.3);
        color: #FFF;
        box-shadow: 0 0 15px rgba(16, 185, 129, 0.4);
    }

    .btn-cyber-warning {
        background: rgba(245, 158, 11, 0.1);
        color: #FCD34D;
        border: 1px solid rgba(245, 158, 11, 0.4);
        transition: all 0.3s ease;
    }
    .btn-cyber-warning:hover {
        background: rgba(245, 158, 11, 0.3);
        color: #FFF;
        box-shadow: 0 0 15px rgba(245, 158, 11, 0.4);
    }
    .btn-cyber-info {
        background: rgba(59, 130, 246, 0.1);
        color: #93C5FD;
        border: 1px solid rgba(59, 130, 246, 0.4);
        transition: all 0.3s ease;
    }
    .btn-cyber-info:hover {
        background: rgba(59, 130, 246, 0.3);
        color: #FFF;
        box-shadow: 0 0 15px rgba(59, 130, 246, 0.4);
    }
</style>

<div class="cyber-grid-bg"></div>
<div class="cyber-grid-overlay"></div>
<div id="particles-container"></div>

<div class="container-fluid cyber-anim-container">
    <div class="row mb-4 cyber-header">
        <div class="col-12">
            <h3 class="fw-bold" style="color: var(--accent-danger-light); letter-spacing: 1px;">Partner Nonaktif</h3>
            <p class="text-secondary">Daftar akun partner yang layanannya sedang ditangguhkan.</p>
        </div>
    </div>

    <div class="row" id="partner-list">
        @forelse($partners as $partner)
            <div class="col-12 col-md-6 col-lg-4 mb-4 cyber-card-item">
                <div class="card cyber-card h-100">
                    <div class="card-body p-4 text-white d-flex flex-column">
                        
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <span class="badge" style="background: rgba(239, 68, 68, 0.2); color: var(--accent-danger-light); border: 1px solid var(--accent-danger-light); padding: 0.5em 0.8em; letter-spacing: 1px;">
                                NONAKTIF
                            </span>
                            
                            <div class="text-end" style="font-family: monospace; font-size: 0.7rem; line-height: 1.3;">
                                <span class="text-secondary" style="letter-spacing: 0.5px;">LAST UPDATE</span><br>
                                <span style="color: var(--accent-danger-light);">
                                    @if($partner->outlet && $partner->outlet->mediaQr)
                                        {{ \Carbon\Carbon::parse($partner->outlet->mediaQr->updated_at ?? $partner->outlet->mediaQr->created_at)->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }} WIB
                                    @else
                                        ---
                                    @endif
                                </span>
                            </div>
                        </div>

                        <h5 class="fw-bold mb-1 text-truncate" style="color: var(--text-primary);">{{ $partner->nama_manager }}</h5>
                        <p class="text-white small mb-3" style="font-family: monospace; opacity: 0.7;">ID: {{ $partner->uuid }}</p>
                        
                        <div class="p-3 rounded mb-4 flex-grow-1" style="background: rgba(239, 68, 68, 0.05); border-left: 3px solid var(--accent-danger);">
                            <small class="d-block text-secondary fw-bold text-uppercase mb-1" style="font-size: 0.65rem; letter-spacing: 1px;">Nama Outlet</small>
                            <span class="fw-semibold" style="color: var(--accent-danger-light); font-size: 1.1rem;">{{ $partner->outlet->nama_outlet ?? '---' }}</span>
                        </div>
                        
                        <div class="d-flex flex-column gap-2 mt-auto">
                            <a href="{{ route('admin.partner.detail', $partner->uuid) }}" class="btn btn-cyber-info btn-sm w-100 fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">
                                <i class="fa-solid fa-eye me-1"></i> LIHAT DETAIL
                            </a>

                            <div class="d-flex gap-2">
                                <div class="flex-grow-1">
                                    <button type="button" class="btn btn-cyber-success btn-sm w-100 fw-bold btn-trigger-aktif" 
                                        data-action="{{ route('admin.partner.set_aktif', $partner->uuid) }}"
                                        data-name="{{ $partner->nama_manager }}">
                                        AKTIFKAN
                                    </button>
                                </div>

                                <form action="{{ route('admin.partner.set_jatuh_tempo', $partner->uuid) }}" method="POST" class="flex-grow-1">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-cyber-warning btn-sm w-100 fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">JATUH TEMPO</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 cyber-empty-state">
                <div class="mb-3">
                    <i class="fas fa-ban fa-3x" style="color: rgba(239, 68, 68, 0.3);"></i>
                </div>
                <h5 style="color: var(--text-primary);">Data tidak tersedia</h5>
                <p class="text-secondary small">Tidak ada partner dengan status nonaktif saat ini.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Aktifkan Paket -->
<div class="modal fade" id="activationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: var(--bg-dark); border: 1px solid var(--accent-cyan); box-shadow: 0 0 20px rgba(0,229,255,0.2);">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold text-white"><i class="fa-solid fa-circle-check text-success me-2"></i>Aktifkan Partner &amp; Pilih Paket</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="activationForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p class="text-secondary small mb-3">Pilih paket langganan yang akan diaktifkan untuk partner <strong id="partnerNamePlaceholder" class="text-white"></strong>:</p>
                    
                    <div class="mb-3">
                        <div class="form-check p-3 rounded mb-2" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(0,229,255,0.1); cursor: pointer;" onclick="document.getElementById('paketGratis').click();">
                            <input class="form-check-input ms-0 me-2 pkg-checkbox" type="checkbox" name="paket" id="paketGratis" value="gratis" checked>
                            <label class="form-check-label text-white fw-bold" for="paketGratis" onclick="event.stopPropagation();">Gratis (Free Trial)</label>
                            <small class="d-block text-secondary mt-1">Akses gratis tanpa biaya untuk fitur-fitur dasar.</small>
                        </div>
                        <div class="form-check p-3 rounded mb-2" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(0,229,255,0.1); cursor: pointer;" onclick="document.getElementById('paketEksklusif').click();">
                            <input class="form-check-input ms-0 me-2 pkg-checkbox" type="checkbox" name="paket" id="paketEksklusif" value="eksklusif">
                            <label class="form-check-label text-white fw-bold" for="paketEksklusif" onclick="event.stopPropagation();">Eksklusif (Bulanan)</label>
                            <small class="d-block text-secondary mt-1">Akses penuh bulanan untuk fitur-fitur medium.</small>
                        </div>
                        <div class="form-check p-3 rounded mb-0" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(0,229,255,0.1); cursor: pointer;" onclick="document.getElementById('paketSuper').click();">
                            <input class="form-check-input ms-0 me-2 pkg-checkbox" type="checkbox" name="paket" id="paketSuper" value="super">
                            <label class="form-check-label text-white fw-bold" for="paketSuper" onclick="event.stopPropagation();">Super (Tahunan)</label>
                            <small class="d-block text-secondary mt-1">Akses penuh tahunan untuk fitur-fitur terlengkap.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 d-flex gap-2">
                    <button type="button" class="btn btn-cyber-danger flex-grow-1" data-bs-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-cyber-success flex-grow-1">AKTIFKAN SEKARANG</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@stack('scripts')
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // 1. GENERATE PARTIKEL BACKGROUND (Nuansa Merah/Danger)
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
            targets: '.cyber-header h3, .cyber-header p',
            translateX: [-30, 0],
            opacity: [0, 1],
            delay: anime.stagger(150),
            easing: 'easeOutQuint',
            duration: 1000
        });

        // 3. ANIMASI ENTRANCE KARTU PARTNER (STAGGERED)
        anime({
            targets: '.cyber-card-item',
            translateY: [50, 0],
            opacity: [0, 1],
            delay: anime.stagger(100, {start: 300}), 
            easing: 'easeOutElastic(1, .8)',
            duration: 1200
        });

        // 4. ANIMASI EMPTY STATE
        if(document.querySelector('.cyber-empty-state')) {
            anime({
                targets: '.cyber-empty-state',
                scale: [0.9, 1],
                opacity: [0, 1],
                easing: 'easeOutExpo',
                duration: 1000
            });
        }

        // 5. ANIMASI HOVER KARTU (Nuansa Merah/Danger pada bayangan)
        const cards = document.querySelectorAll('.cyber-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                anime({
                    targets: card,
                    scale: 1.02,
                    translateY: -5,
                    boxShadow: '0 12px 30px rgba(239, 68, 68, 0.15)', // Shadow menyesuaikan tema merah
                    duration: 300,
                    easing: 'easeOutExpo'
                });
            });

            card.addEventListener('mouseleave', () => {
                anime({
                    targets: card,
                    scale: 1,
                    translateY: 0,
                    boxShadow: '0 8px 32px rgba(0, 0, 0, 0.3)',
                    duration: 500,
                    easing: 'easeOutElastic(1, .8)'
                });
            });
        });

        // 6. LOGIKA MODAL AKTIFKAN & CHECKBOX EKSKLUSIF
        const triggerBtns = document.querySelectorAll('.btn-trigger-aktif');
        const modalElement = document.getElementById('activationModal');
        if (modalElement && triggerBtns.length > 0) {
            const modal = new bootstrap.Modal(modalElement);
            const form = document.getElementById('activationForm');
            const partnerNamePlaceholder = document.getElementById('partnerNamePlaceholder');

            triggerBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    form.action = this.dataset.action;
                    if (partnerNamePlaceholder) {
                        partnerNamePlaceholder.innerText = this.dataset.name;
                    }
                    modal.show();
                });
            });

            // Logika Checkbox Saling Uncheck (berperan seperti Radio)
            const checkboxes = document.querySelectorAll('.pkg-checkbox');
            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    if (this.checked) {
                        checkboxes.forEach(other => {
                            if (other !== this) other.checked = false;
                        });
                    } else {
                        // Minimal harus ada satu yang tercentang
                        let anyChecked = false;
                        checkboxes.forEach(other => {
                            if (other.checked) anyChecked = true;
                        });
                        if (!anyChecked) {
                            this.checked = true;
                        }
                    }
                });
            });
        }

    });
</script>
@endpush