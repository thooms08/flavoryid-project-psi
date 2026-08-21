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

    /* === BACKGROUND LAYER (Konsisten dengan Dashboard) === */
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
        background: var(--accent-cyan);
        border-radius: 50%;
        box-shadow: 0 0 12px var(--accent-cyan);
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
        border: 1px solid rgba(0, 229, 255, 0.15);
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
        background: linear-gradient(90deg, var(--accent-cyan), var(--accent-blue));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.5s ease;
    }

    .cyber-card:hover::before {
        transform: scaleX(1);
    }

    /* === BUTTON STYLING === */
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

    .btn-cyber-danger {
        background: rgba(239, 68, 68, 0.1);
        color: #FCA5A5;
        border: 1px solid rgba(239, 68, 68, 0.4);
        transition: all 0.3s ease;
    }
    .btn-cyber-danger:hover {
        background: rgba(239, 68, 68, 0.3);
        color: #FFF;
        box-shadow: 0 0 15px rgba(239, 68, 68, 0.4);
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

    .btn-cyber-purple {
        background: rgba(139, 92, 246, 0.1);
        color: #C4B5FD;
        border: 1px solid rgba(139, 92, 246, 0.4);
        transition: all 0.3s ease;
    }
    .btn-cyber-purple:hover {
        background: rgba(139, 92, 246, 0.3);
        color: #FFF;
        box-shadow: 0 0 15px rgba(139, 92, 246, 0.4);
    }

    /* === BADGE PAKET === */
    .badge-paket-gratis   { background: rgba(16,185,129,0.15); color: #34D399; border: 1px solid rgba(52,211,153,0.4); }
    .badge-paket-eksklusif{ background: rgba(59,130,246,0.15); color: #93C5FD; border: 1px solid rgba(147,197,253,0.4); }
    .badge-paket-super    { background: rgba(251,191,36,0.15);  color: #FCD34D; border: 1px solid rgba(252,211,77,0.5);  }
    .badge-paket-unknown  { background: rgba(148,163,184,0.15); color: #94A3B8; border: 1px solid rgba(148,163,184,0.3); }

    /* === PAKET OPTION CARDS di MODAL === */
    .paket-option {
        cursor: pointer;
        border: 2px solid rgba(0, 229, 255, 0.1);
        border-radius: 12px;
        padding: 1rem;
        background: rgba(255,255,255,0.03);
        transition: all 0.25s ease;
        position: relative;
    }
    .paket-option:hover {
        border-color: rgba(0, 229, 255, 0.4);
        background: rgba(0, 229, 255, 0.05);
    }
    .paket-option.selected {
        border-color: var(--accent-cyan);
        background: rgba(0, 229, 255, 0.08);
        box-shadow: 0 0 12px rgba(0, 229, 255, 0.15);
    }
    .paket-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    .paket-option .paket-icon {
        font-size: 1.4rem;
        margin-bottom: 0.4rem;
    }
    .paket-check {
        position: absolute;
        top: 10px; right: 12px;
        width: 18px; height: 18px;
        border-radius: 50%;
        border: 2px solid rgba(0,229,255,0.3);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.6rem;
        transition: all 0.2s;
    }
    .paket-option.selected .paket-check {
        background: var(--accent-cyan);
        border-color: var(--accent-cyan);
        color: #0B1120;
    }
</style>

<div class="cyber-grid-bg"></div>
<div class="cyber-grid-overlay"></div>
<div id="particles-container"></div>

<div class="container-fluid cyber-anim-container">
    <div class="row mb-4 cyber-header">
        <div class="col-12">
            <h3 class="fw-bold" style="color: var(--accent-cyan); letter-spacing: 1px;">Partner Aktif</h3>
            <p class="text-secondary">Kelola status partner yang saat ini sedang aktif di sistem.</p>
        </div>
    </div>

    <div class="row" id="partner-list">
        @forelse($partners as $partner)
            @php
                $paketAktif = strtolower($partner->outlet->mediaQr->paket ?? '');
                $paketLabel = match($paketAktif) {
                    'gratis'    => 'GRATIS',
                    'eksklusif' => 'EKSKLUSIF',
                    'super'     => 'SUPER',
                    default     => strtoupper($paketAktif) ?: 'TIDAK DIKETAHUI'
                };
                $paketBadgeClass = match($paketAktif) {
                    'gratis'    => 'badge-paket-gratis',
                    'eksklusif' => 'badge-paket-eksklusif',
                    'super'     => 'badge-paket-super',
                    default     => 'badge-paket-unknown'
                };
                $paketIcon = match($paketAktif) {
                    'gratis'    => 'fa-leaf',
                    'eksklusif' => 'fa-star',
                    'super'     => 'fa-infinity',
                    default     => 'fa-question'
                };
            @endphp
            <div class="col-12 col-md-6 col-lg-4 mb-4 cyber-card-item">
                <div class="card cyber-card h-100">
                    <div class="card-body p-4 text-white d-flex flex-column">
                        
                        {{-- Baris atas: badge status + last update --}}
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #34D399; border: 1px solid #34D399; padding: 0.5em 0.8em; letter-spacing: 1px;">AKTIF</span>
                            
                            <div class="text-end" style="font-family: monospace; font-size: 0.7rem; line-height: 1.3;">
                                <span class="text-secondary" style="letter-spacing: 0.5px;">LAST UPDATE</span><br>
                                <span style="color: var(--accent-cyan);">
                                    @if($partner->outlet && $partner->outlet->mediaQr)
                                        {{ \Carbon\Carbon::parse($partner->outlet->mediaQr->updated_at ?? $partner->outlet->mediaQr->created_at)->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }} WIB
                                    @else
                                        ---
                                    @endif
                                </span>
                            </div>
                        </div>

                        {{-- Nama + UUID --}}
                        <h5 class="fw-bold mb-1 text-truncate" style="color: var(--text-primary);">{{ $partner->nama_manager }}</h5>
                        <p class="text-white small mb-2" style="font-family: monospace; opacity: 0.7;">ID: {{ $partner->uuid }}</p>

                        {{-- Badge Paket --}}
                        <div class="mb-3">
                            <span class="badge {{ $paketBadgeClass }} px-3 py-2" style="font-size: 0.7rem; letter-spacing: 1px; border-radius: 20px;">
                                <i class="fa-solid {{ $paketIcon }} me-1"></i>
                                PAKET {{ $paketLabel }}
                                @if($paketAktif === 'super')
                                    &nbsp;&#8734;
                                @elseif($paketAktif === 'eksklusif')
                                    &mdash; Bulanan
                                @elseif($paketAktif === 'gratis')
                                    &mdash; Trial 30 Hari
                                @endif
                            </span>
                        </div>
                        
                        {{-- Info Outlet --}}
                        <div class="p-3 rounded mb-4 flex-grow-1" style="background: rgba(0, 229, 255, 0.05); border-left: 3px solid var(--accent-cyan);">
                            <small class="d-block text-secondary fw-bold text-uppercase mb-1" style="font-size: 0.65rem; letter-spacing: 1px;">Nama Outlet</small>
                            <span class="fw-semibold" style="color: var(--accent-cyan); font-size: 1.1rem;">{{ $partner->outlet->nama_outlet ?? '---' }}</span>
                        </div>
                        
                        {{-- Tombol Aksi --}}
                        <div class="d-flex flex-column gap-2 mt-auto">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.partner.detail', $partner->uuid) }}" class="btn btn-cyber-info btn-sm flex-grow-1 fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">
                                    <i class="fa-solid fa-eye me-1"></i> DETAIL
                                </a>

                                {{-- Tombol Ubah Paket --}}
                                <button type="button"
                                    class="btn btn-cyber-purple btn-sm flex-grow-1 fw-bold btn-ubah-paket"
                                    style="font-size: 0.75rem; letter-spacing: 1px;"
                                    data-action="{{ route('admin.partner.set_aktif', $partner->uuid) }}"
                                    data-name="{{ $partner->nama_manager }}"
                                    data-paket-sekarang="{{ $paketAktif }}">
                                    <i class="fa-solid fa-pen-to-square me-1"></i> UBAH PAKET
                                </button>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="button"
                                    class="btn btn-cyber-warning btn-sm flex-grow-1 fw-bold btn-confirm-status"
                                    style="font-size: 0.75rem; letter-spacing: 1px;"
                                    data-action="{{ route('admin.partner.set_jatuh_tempo', $partner->uuid) }}"
                                    data-name="{{ $partner->nama_manager }}"
                                    data-paket="{{ $paketAktif }}"
                                    data-target="jatuh_tempo">
                                    JATUH TEMPO
                                </button>

                                <button type="button"
                                    class="btn btn-cyber-danger btn-sm flex-grow-1 fw-bold btn-confirm-status"
                                    style="font-size: 0.75rem; letter-spacing: 1px;"
                                    data-action="{{ route('admin.partner.set_nonaktif', $partner->uuid) }}"
                                    data-name="{{ $partner->nama_manager }}"
                                    data-paket="{{ $paketAktif }}"
                                    data-target="nonaktif">
                                    NONAKTIFKAN
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 cyber-empty-state">
                <div class="mb-3">
                    <i class="fas fa-folder-open fa-3x" style="color: rgba(0, 229, 255, 0.3);"></i>
                </div>
                <h5 style="color: var(--text-primary);">Data tidak tersedia</h5>
                <p class="text-secondary small">Belum ada partner dengan status aktif saat ini.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- ===== MODAL UBAH PAKET ===== --}}
<div class="modal fade" id="modalUbahPaket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content" style="background: #0B1120; border: 1px solid rgba(0,229,255,0.25); border-radius: 20px; box-shadow: 0 0 40px rgba(0,229,255,0.1);">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div>
                    <h5 class="fw-bold text-white mb-1">
                        <i class="fa-solid fa-pen-to-square me-2" style="color: var(--accent-cyan);"></i>
                        Ubah Paket Partner
                    </h5>
                    <p class="text-secondary small mb-0">Pilih paket baru untuk <strong id="modalPartnerName" class="text-white"></strong></p>
                </div>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formUbahPaket" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body px-4 py-3">

                    {{-- Info paket saat ini --}}
                    <div class="mb-3 px-3 py-2 rounded-3" style="background: rgba(0,229,255,0.05); border: 1px solid rgba(0,229,255,0.1);">
                        <small class="text-secondary" style="font-size: 0.7rem; letter-spacing: 1px; text-transform: uppercase;">Paket Saat Ini</small>
                        <p class="mb-0 fw-bold text-white" id="modalPaketSekarang" style="font-size: 0.9rem;"></p>
                    </div>

                    {{-- Pilihan Paket --}}
                    <p class="text-secondary small mb-3" style="font-size: 0.8rem;">Pilih paket yang akan diterapkan:</p>

                    <div class="d-flex flex-column gap-2">

                        {{-- Gratis --}}
                        <label class="paket-option" id="opt-gratis" onclick="pilihPaket('gratis', this)">
                            <input type="radio" name="paket" value="gratis">
                            <div class="paket-check"><i class="fa-solid fa-check"></i></div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="paket-icon text-success"><i class="fa-solid fa-leaf"></i></div>
                                <div>
                                    <p class="mb-0 fw-bold text-white" style="font-size: 0.9rem;">Gratis</p>
                                    <p class="mb-0 text-secondary" style="font-size: 0.75rem;">Trial 30 hari &mdash; Rp 0</p>
                                </div>
                            </div>
                        </label>

                        {{-- Eksklusif --}}
                        <label class="paket-option" id="opt-eksklusif" onclick="pilihPaket('eksklusif', this)">
                            <input type="radio" name="paket" value="eksklusif">
                            <div class="paket-check"><i class="fa-solid fa-check"></i></div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="paket-icon" style="color: #93C5FD;"><i class="fa-solid fa-star"></i></div>
                                <div>
                                    <p class="mb-0 fw-bold text-white" style="font-size: 0.9rem;">Eksklusif</p>
                                    <p class="mb-0 text-secondary" style="font-size: 0.75rem;">Akses penuh bulanan &mdash; Rp 70.000 / bulan</p>
                                </div>
                            </div>
                        </label>

                        {{-- Super --}}
                        <label class="paket-option" id="opt-super" onclick="pilihPaket('super', this)">
                            <input type="radio" name="paket" value="super">
                            <div class="paket-check"><i class="fa-solid fa-check"></i></div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="paket-icon" style="color: #FCD34D;"><i class="fa-solid fa-infinity"></i></div>
                                <div>
                                    <p class="mb-0 fw-bold text-white" style="font-size: 0.9rem;">Super <span style="color:#FCD34D; font-size:0.7rem;">&infin; SELAMANYA</span></p>
                                    <p class="mb-0 text-secondary" style="font-size: 0.75rem;">Bayar sekali, aktif selamanya &mdash; Rp 880.000</p>
                                </div>
                            </div>
                        </label>

                    </div>
                </div>

                <div class="modal-footer border-0 px-4 pb-4 pt-2 d-flex gap-2">
                    <button type="button" class="btn flex-grow-1 fw-bold py-2" 
                        style="background: rgba(148,163,184,0.1); color: #94A3B8; border: 1px solid rgba(148,163,184,0.2); border-radius: 10px;"
                        data-bs-dismiss="modal">
                        BATAL
                    </button>
                    <button type="button" id="btnKonfirmasiPaket" class="btn flex-grow-1 fw-bold py-2" disabled
                        style="background: rgba(0,229,255,0.15); color: #00E5FF; border: 1px solid rgba(0,229,255,0.4); border-radius: 10px; transition: all 0.3s;"
                        onmouseover="if(!this.disabled) this.style.cssText='background:rgba(0,229,255,0.3);color:#fff;border:1px solid #00E5FF;border-radius:10px;box-shadow:0 0 15px rgba(0,229,255,0.3);'"
                        onmouseout="if(!this.disabled) this.style.cssText='background:rgba(0,229,255,0.15);color:#00E5FF;border:1px solid rgba(0,229,255,0.4);border-radius:10px;'">
                        <i class="fa-solid fa-check me-1"></i> KONFIRMASI
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@stack('scripts')
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ===== FUNGSI PILIH PAKET DI MODAL =====
    let selectedPaket = null;

    function pilihPaket(nilai, elLabel) {
        document.querySelectorAll('.paket-option').forEach(el => el.classList.remove('selected'));
        elLabel.classList.add('selected');
        selectedPaket = nilai;
        document.getElementById('btnKonfirmasiPaket').disabled = false;
    }

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

        // 5. ANIMASI HOVER KARTU
        const cards = document.querySelectorAll('.cyber-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                anime({ targets: card, scale: 1.02, translateY: -5, boxShadow: '0 12px 30px rgba(0, 229, 255, 0.15)', duration: 300, easing: 'easeOutExpo' });
            });
            card.addEventListener('mouseleave', () => {
                anime({ targets: card, scale: 1, translateY: 0, boxShadow: '0 8px 32px rgba(0, 0, 0, 0.3)', duration: 500, easing: 'easeOutElastic(1, .8)' });
            });
        });

        // 6. TOMBOL UBAH PAKET — buka modal
        const modalEl      = document.getElementById('modalUbahPaket');
        const bsModal      = new bootstrap.Modal(modalEl);
        let pendingAction  = null;

        // Reset state setiap kali modal ditutup
        modalEl.addEventListener('hidden.bs.modal', function () {
            selectedPaket = null;
            document.querySelectorAll('.paket-option').forEach(el => el.classList.remove('selected'));
            document.getElementById('btnKonfirmasiPaket').disabled = true;
        });

        document.querySelectorAll('.btn-ubah-paket').forEach(btn => {
            btn.addEventListener('click', function () {
                const action        = this.dataset.action;
                const name          = this.dataset.name;
                const paketSekarang = this.dataset.paketSekarang || '';

                // Simpan action URL ke variabel, BUKAN ke form.action
                pendingAction = action;

                // Isi info modal
                document.getElementById('modalPartnerName').innerText = name;
                const labelMap = { gratis: 'Gratis (Trial 30 Hari)', eksklusif: 'Eksklusif (Bulanan)', super: 'Super (Selamanya) ∞' };
                document.getElementById('modalPaketSekarang').innerText = labelMap[paketSekarang] ?? paketSekarang.toUpperCase();

                // Pre-select paket saat ini
                const optEl = document.getElementById('opt-' + paketSekarang);
                if (optEl) pilihPaket(paketSekarang, optEl);

                bsModal.show();
            });
        });

        // Tombol KONFIRMASI di modal
        document.getElementById('btnKonfirmasiPaket').addEventListener('click', function () {
            if (!selectedPaket || !pendingAction) return;

            const partnerName    = document.getElementById('modalPartnerName').innerText;
            const labelMap       = { gratis: 'Gratis (Trial 30 Hari)', eksklusif: 'Eksklusif (Bulanan)', super: 'Super (Selamanya) ∞' };
            const labelBaru      = labelMap[selectedPaket] ?? selectedPaket.toUpperCase();
            const snapshotPaket  = selectedPaket;
            const snapshotAction = pendingAction;

            // Tutup modal dulu, lalu tampilkan SweetAlert
            bsModal.hide();

            // Tunggu animasi modal selesai (300ms Bootstrap default)
            setTimeout(function () {
                Swal.fire({
                    background: '#0B1120',
                    color: '#F8FAFC',
                    icon: 'question',
                    iconColor: '#00E5FF',
                    title: '<span style="color:#00E5FF;letter-spacing:1px;">KONFIRMASI UBAH PAKET</span>',
                    html: `<span style="color:#94A3B8;">Anda akan mengubah paket partner <strong style="color:#F8FAFC;">${partnerName}</strong> menjadi:<br><br><strong style="color:#00E5FF;font-size:1.1rem;">${labelBaru}</strong></span>`,
                    showCancelButton: true,
                    confirmButtonText: 'YA, UBAH PAKET',
                    cancelButtonText: 'BATAL',
                    confirmButtonColor: '#00E5FF',
                    cancelButtonColor: 'rgba(148,163,184,0.2)',
                    customClass: { confirmButton: 'fw-bold text-dark', cancelButton: 'fw-bold' }
                }).then(result => {
                    if (result.isConfirmed) {
                        // Buat form baru di memori — pola yang sama dengan tombol jatuh tempo/nonaktif
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = snapshotAction;
                        form.innerHTML =
                            '<input type="hidden" name="_token" value="{{ csrf_token() }}">' +
                            '<input type="hidden" name="_method" value="PATCH">' +
                            '<input type="hidden" name="paket" value="' + snapshotPaket + '">';
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }, 350);
        });

        // 7. SWEETALERT KONFIRMASI UBAH STATUS + PROTEKSI PAKET SUPER
        document.querySelectorAll('.btn-confirm-status').forEach(btn => {
            btn.addEventListener('click', function () {
                const action   = this.dataset.action;
                const name     = this.dataset.name;
                const paket    = (this.dataset.paket || '').toLowerCase();
                const target   = this.dataset.target;

                // --- BLOKIR jika paket super ---
                if (paket === 'super') {
                    Swal.fire({
                        background: '#0B1120',
                        color: '#F8FAFC',
                        icon: 'error',
                        iconColor: '#00E5FF',
                        title: '<span style="color:#00E5FF;letter-spacing:1px;">AKSI DITOLAK</span>',
                        html: `<span style="color:#94A3B8;">Partner <strong style="color:#F8FAFC;">${name}</strong> menggunakan <span style="color:#00E5FF;font-weight:700;">Paket Super (Selamanya)</span>.<br>Status tidak dapat diubah menjadi jatuh tempo atau nonaktif.</span>`,
                        confirmButtonText: 'MENGERTI',
                        confirmButtonColor: '#00E5FF',
                        customClass: { confirmButton: 'fw-bold', popup: 'border border-1' }
                    });
                    return;
                }

                // --- Konfirmasi normal untuk paket lain ---
                const isJatuhTempo = target === 'jatuh_tempo';
                const labelAksi    = isJatuhTempo ? 'JATUH TEMPO' : 'NONAKTIFKAN';
                const warna        = isJatuhTempo ? '#F59E0B' : '#EF4444';
                const ikonWarna    = isJatuhTempo ? '#FCD34D' : '#FCA5A5';

                Swal.fire({
                    background: '#0B1120',
                    color: '#F8FAFC',
                    icon: 'warning',
                    iconColor: ikonWarna,
                    title: `<span style="color:${ikonWarna};letter-spacing:1px;">KONFIRMASI ${labelAksi}</span>`,
                    html: `<span style="color:#94A3B8;">Apakah Anda yakin ingin mengubah status partner <strong style="color:#F8FAFC;">${name}</strong> menjadi <strong style="color:${ikonWarna};">${labelAksi}</strong>?</span>`,
                    showCancelButton: true,
                    confirmButtonText: `YA, ${labelAksi}`,
                    cancelButtonText: 'BATAL',
                    confirmButtonColor: warna,
                    cancelButtonColor: 'rgba(148,163,184,0.2)',
                    customClass: { confirmButton: 'fw-bold', cancelButton: 'fw-bold' }
                }).then(result => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = action;
                        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="PATCH">';
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });

    });
</script>
@endpush
