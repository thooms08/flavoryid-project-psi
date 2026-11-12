<style>
    :root {
        --primary-orange: #fd7e14;
        --soft-orange: #fff5eb;
        --text-dark: #343a40;
        --text-muted: #6c757d;
    }

    .navbar-flavory { 
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-bottom: 2px solid var(--soft-orange);
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }

    /* Brand / Profile Section */
    .profile-outlet-nav {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 5px 12px;
        border-radius: 12px;
        background: var(--soft-orange);
        text-decoration: none;
        transition: transform 0.2s;
    }
    .profile-outlet-nav:hover { transform: translateY(-2px); }

    .logo-outlet-nav {
        width: 38px; height: 38px;
        object-fit: cover; border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .profile-info { display: flex; flex-direction: column; min-width: 0; }
    .profile-text-nav {
        font-size: 0.7rem; font-weight: 800; color: var(--primary-orange);
        letter-spacing: 0.5px; text-transform: uppercase; line-height: 1;
    }
    .profile-subtext { font-size: 0.6rem; color: var(--text-muted); }

    /* Nav Links */
    .nav-link {
        color: var(--text-dark) !important;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 10px 18px !important;
        border-radius: 12px;
        margin: 0 3px;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 8px;
        position: relative;
    }

    .nav-link i { font-size: 1.1rem; }

    .nav-link:hover {
        color: var(--primary-orange) !important;
        background-color: var(--soft-orange);
    }

    .nav-link.active {
        color: white !important;
        background-color: var(--primary-orange);
        box-shadow: 0 4px 12px rgba(253, 126, 20, 0.3);
    }

    /* Dropdown Styling */
    .dropdown-menu {
        border-radius: 15px;
        padding: 0.5rem;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .dropdown-item {
        border-radius: 8px;
        padding: 8px 15px;
        font-weight: 500;
        color: var(--text-dark);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .dropdown-item:hover {
        background-color: var(--soft-orange);
        color: var(--primary-orange);
    }

    .dropdown-item.active-dropdown {
        background-color: var(--soft-orange);
        color: var(--primary-orange) !important;
        font-weight: 700;
    }

    /* Hamburger Menu Animation */
    .navbar-toggler { 
        border: none; 
        padding: 0; 
        outline: none !important; 
        box-shadow: none !important; 
    }

    .toggler-icon { 
        width: 26px;
        height: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
    }

    .toggler-icon span[class^="line-"] {
        display: block;
        height: 3px;
        width: 100%;
        border-radius: 3px;
        background-color: var(--primary-orange);
        transition: all 0.3s ease-in-out;
    }

    .navbar-toggler[aria-expanded="true"] .toggler-icon .line-1 {
        transform: translateY(8.5px) rotate(45deg);
    }
    .navbar-toggler[aria-expanded="true"] .toggler-icon .line-2 {
        opacity: 0;
    }
    .navbar-toggler[aria-expanded="true"] .toggler-icon .line-3 {
        transform: translateY(-8.5px) rotate(-45deg);
    }

    /* Tombol Absen & Shift (BARU) */
    .btn-absen {
        background: var(--primary-orange);
        border: 2px solid var(--primary-orange);
        color: white;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.75rem;
        padding: 6px 15px;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-absen:hover {
        background: var(--soft-orange);
        color: var(--primary-orange);
    }

    /* Logout Button */
    .btn-logout {
        background: transparent;
        border: 2px solid #ff4d4d;
        color: #ff4d4d;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.75rem;
        padding: 6px 15px;
        transition: all 0.3s;
    }
    .btn-logout:hover {
        background: #ff4d4d;
        color: white;
    }

    /* Custom Status Indicator */
    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 5px rgba(0,0,0,0.2);
    }
    .status-dot-warning { background-color: #ffc107; border: 2px solid white; }
    .status-dot-danger { background-color: #dc3545; border: 2px solid white; }
    
    .nav-indicator { position: absolute; top: 8px; right: 12px; }
    .toggler-indicator { position: absolute; top: -5px; right: -5px; width: 12px; height: 12px; border: 2px solid white; }
   
    .arrow-icon {
        font-size: 0.8rem;
        transition: transform 0.3s ease;
    }
    .nav-item.dropdown .nav-link.show .arrow-icon {
        transform: rotate(-180deg);
    }
    .dropdown-toggle::after {
        display: none !important;
    }

    /* Fokus Mobile */
    @media (max-width: 991.98px) {
        .navbar-collapse {
            background: white;
            margin-top: 15px;
            padding: 15px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .profile-container-nav { 
            order: -1;
            flex: 1 1 0;
            min-width: 0;
        }

        .profile-outlet-nav {
            max-width: 100%;
            min-width: 0;
            overflow: hidden;
        }

        .profile-text-nav {
            max-width: 130px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
        }

        /* Penyesuaian Tombol Absen & Logout di HP */
        .btn-absen, .btn-logout {
            width: 100%;
            margin-top: 0.5rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    }

    @media (max-width: 400px) {
        .navbar-flavory {
            padding: 0.6rem 1rem;
        }
        .profile-text-nav {
            max-width: 90px;
        }
        .logo-outlet-nav {
            width: 32px;
            height: 32px;
        }
    }

    /* MineAI Menu Highlight */
    @keyframes mineGlow {
        0%   { background-position: 0% 50%; box-shadow: 0 0 0 rgba(255,106,0,0); }
        50%  { background-position: 100% 50%; box-shadow: 0 0 12px rgba(238,9,121,.25); }
        100% { background-position: 0% 50%; box-shadow: 0 0 0 rgba(255,106,0,0); }
    }

    .dropdown-item-mineai {
        background: linear-gradient(135deg, rgba(255,106,0,.08), rgba(238,9,121,.06), rgba(255,154,0,.08)) !important;
        background-size: 300% 300% !important;
        animation: mineGlow 3s ease infinite !important;
        border: 1px solid rgba(255,106,0,.18) !important;
        border-radius: 10px !important;
    }
    .dropdown-item-mineai:hover {
        background: linear-gradient(135deg, rgba(255,106,0,.18), rgba(238,9,121,.14)) !important;
        border-color: rgba(238,9,121,.3) !important;
    }

    .badge-mineai-baru {
        display: inline-flex;
        align-items: center;
        padding: 2px 7px;
        border-radius: 20px;
        font-size: .58rem;
        font-weight: 800;
        letter-spacing: .5px;
        color: white;
        background: linear-gradient(135deg, #ff6a00, #ee0979);
        background-size: 200% 200%;
        animation: mineGlow 2s ease infinite;
        box-shadow: 0 2px 8px rgba(238,9,121,.35);
        vertical-align: middle;
        white-space: nowrap;
        flex-shrink: 0;
    }
</style>

@php
    $statusLisensi = 'aktif'; 
    $dotClass = '';

    if (auth()->check() && auth()->user()->role === 'kasir') {
        $user = auth()->user();
        
        $partner = \App\Models\Partner::where('user_id', $user->id)->first();
        if ($partner) {
            $outletInfo = \App\Models\InformasiOutlet::where('partner_id', $partner->id)->first();
            if ($outletInfo) {
                $mediaQr = \App\Models\MediaQr::where('informasi_id', $outletInfo->id)->first();
                if ($mediaQr) {
                    $statusLisensi = $mediaQr->status;
                }
            }
        }
    }

    if ($statusLisensi === 'jatuh_tempo') {
        $dotClass = 'status-dot-warning'; 
    } elseif ($statusLisensi === 'nonaktif') {
        $dotClass = 'status-dot-danger'; 
    }
@endphp

<nav class="navbar navbar-expand-lg navbar-flavory sticky-top shadow-sm">
    <div class="container-fluid">
        <div class="profile-container-nav">
            <a href="{{ route('kasir.profile-outlet.edit') }}" class="profile-outlet-nav">
                @if($outletInfo && $outletInfo->logo)
                    <img src="{{ asset('assets/logo/' . $outletInfo->logo) }}" class="logo-outlet-nav">
                @else
                    <div class="logo-outlet-nav d-flex align-items-center justify-content-center bg-white">
                        <i class="bi bi-shop text-orange" style="font-size: 1.2rem; color: var(--primary-orange);"></i>
                    </div>
                @endif
                <div class="profile-info">
                    <span class="profile-text-nav">{{ $outletInfo->nama_outlet ?? 'Nama Outlet Belum Diatur' }}</span>
                    <span class="profile-subtext">Profil Outlet</span>
                </div>
            </a>
        </div>

        {{-- Bantuan & CS - mobile --}}
        <a href="{{ route('panduan.menu') }}"
           class="d-lg-none text-muted me-1"
           style="font-size: 1.35rem; line-height:1;"
           title="Bantuan & Panduan">
            <i class="bi bi-question-circle"></i>
        </a>
        <a href="https://wa.me/6285797574754?text=Halo%20Admin%20Flavory.id%2C%20saya%20butuh%20bantuan."
           target="_blank" rel="noopener noreferrer"
           class="d-lg-none text-muted me-2"
           style="font-size: 1.35rem; line-height:1;"
           title="Hubungi Customer Service">
            <i class="bi bi-headset"></i>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-expanded="false" aria-controls="navbarNav">
            <div class="toggler-icon position-relative">
                <span class="line-1"></span>
                <span class="line-2"></span>
                <span class="line-3"></span>
                @if($dotClass !== '')
                    <span class="status-dot {{ $dotClass }} toggler-indicator"></span>
                @endif
            </div>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('kasir.dashboard') ? 'active' : '' }}" href="{{ route('kasir.dashboard') }}">
                        <i class="bi bi-pc-display-horizontal"></i> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('kasir.riwayat.*') ? 'active' : '' }}" href="{{ route('kasir.riwayat.index') }}">
                        <i class="bi bi-clock-history"></i> Riwayat Pesanan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('kasir.order-batal.*') ? 'active' : '' }}" href="{{ route('kasir.order-batal.index') }}">
                        <i class="bi bi-x-circle"></i> Pesanan Batal
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-grid me-2"></i> 
                        <span>Opsi Lainnya</span>   
                        <i class="bi bi-chevron-down ms-2 arrow-icon"></i>
                        @if($dotClass !== '')
                            <span class="status-dot {{ $dotClass }} nav-indicator"></span>
                        @endif
                    </a>
                    <ul class="dropdown-menu border-0 shadow-lg mt-lg-3">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('kasir.menu.index') ? 'active-dropdown' : '' }}" href="{{ route('kasir.menu.index') }}">
                                <span><i class="bi bi-menu-button-wide me-2"></i>Kelola Menu</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('kasir.stok.index') ? 'active-dropdown' : '' }}" href="{{ route('kasir.stok.index') }}">
                                <span><i class="bi bi-box-seam me-2"></i>Manajemen Stok</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        @if(!isset($partner) || ($partner->show_ikhtisar_bisnis ?? true))
                        <li>
                            <a class="dropdown-item" href="{{ route('kasir.ikhtisar.index') }}">
                                <span><i class="bi bi-bar-chart-line me-2"></i>Ikhtisar Bisnis</span>
                            </a>
                        </li>
                        @endif
                        <li>
                            <a class="dropdown-item" href="{{ route('kasir.pengeluaran.index') }}">
                                <span><i class="bi bi-graph-down-arrow me-2"></i>Pengeluaran</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('kasir.pengaturan.index') ? 'active-dropdown' : '' }}" href="{{ route('kasir.pengaturan.index') }}">
                                <span><i class="bi bi-card-checklist me-2"></i>Pengaturan Form</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('kasir.notes.index') ? 'active-dropdown' : '' }}" href="{{ route('kasir.notes.index') }}">
                                <span><i class="bi bi-receipt-cutoff me-2"></i>Kelola Catatan Struk</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('kasir.langganan.index') ? 'active-dropdown' : '' }}" href="{{route('kasir.langganan.index')}}">
                                <span><i class="bi bi-patch-check me-2"></i>Langganan</span>
                                @if($dotClass !== '')
                                    <span class="status-dot {{ $dotClass }}"></span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <div class="d-lg-flex align-items-center gap-3">
                {{-- Bantuan & CS - desktop/tablet --}}
                <a href="{{ route('panduan.menu') }}"
                   class="d-none d-lg-inline-flex align-items-center text-muted"
                   style="font-size: 1.35rem; line-height:1;"
                   title="Bantuan & Panduan">
                    <i class="bi bi-question-circle"></i>
                </a>
                <a href="https://wa.me/6285797574754?text=Halo%20Admin%20Flavory.id%2C%20saya%20butuh%20bantuan."
                   target="_blank" rel="noopener noreferrer"
                   class="d-none d-lg-inline-flex align-items-center text-muted"
                   style="font-size: 1.35rem; line-height:1;"
                   title="Hubungi Customer Service via WhatsApp">
                    <i class="bi bi-headset"></i>
                </a>

                <form action="{{ route('logout') }}" method="POST" class="m-0" id="form-logout">
                    @csrf
                    <button type="button" class="btn-logout" onclick="konfirmasiLogout()">
                        <i class="bi bi-box-arrow-right me-1"></i> KELUAR
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function konfirmasiLogout() {
        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah Anda yakin akan keluar?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff4d4d', 
            cancelButtonColor: '#6c757d', 
            confirmButtonText: 'Iya',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-logout').submit();
            }
        });
    }
</script>