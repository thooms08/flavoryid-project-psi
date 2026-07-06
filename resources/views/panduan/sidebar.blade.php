<style>
    :root {
        --primary-orange: #ff6a00;
        --light-orange: #fff4e6;
        --text-dark: #1e293b;
        --text-muted: #64748b;
    }

    /* Kustomisasi Sidebar Menu */
    .sidebar-panduan .nav-link {
        color: var(--text-muted);
        border-radius: 0.8rem;
        padding: 10px 15px;
        transition: all 0.2s ease-in-out;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 500;
        margin-bottom: 5px;
    }

    .sidebar-panduan .nav-link i {
        font-size: 1.2rem;
        transition: transform 0.2s;
    }

    /* Efek Hover */
    .sidebar-panduan .nav-link:hover {
        background-color: #f8fafc;
        color: var(--text-dark);
        transform: translateX(4px);
    }

    /* Kondisi Menu Aktif */
    .sidebar-panduan .nav-link.active {
        background-color: var(--light-orange);
        color: var(--primary-orange);
        font-weight: 700;
    }

    .sidebar-panduan .nav-link.active i {
        color: var(--primary-orange);
    }

    /* Tombol Hamburger Mobile */
    .btn-mobile-toggle {
        background: linear-gradient(135deg, var(--primary-orange) 0%, #e65f00 100%);
        color: white;
        border: none;
        border-radius: 0.5rem;
        padding: 8px 15px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 10px rgba(255, 106, 0, 0.2);
    }

    .btn-mobile-toggle:hover {
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(255, 106, 0, 0.3);
    }
</style>

<div class="d-lg-none mb-4">
    <button class="btn btn-mobile-toggle" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarPanduan" aria-controls="sidebarPanduan">
        <i class="bi bi-list fs-4"></i> Menu Panduan
    </button>
</div>

<aside class="offcanvas-lg offcanvas-start sidebar-panduan" tabindex="-1" id="sidebarPanduan" aria-labelledby="sidebarPanduanLabel">
    
    <div class="offcanvas-header border-bottom d-lg-none">
        <h5 class="offcanvas-title fw-bold text-dark d-flex align-items-center gap-2" id="sidebarPanduanLabel">
            <i class="bi bi-journal-bookmark-fill" style="color: #ff6a00;"></i> Panduan Flavory
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarPanduan" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body flex-column p-0 p-lg-3">
        <nav class="w-100">
            <div class="d-none d-lg-block mb-3 px-3">
                <span class="text-uppercase fw-bold text-muted" style="font-size: 0.8rem; letter-spacing: 1px;">Daftar Isi</span>
            </div>

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('panduan.daftar') ? 'active' : '' }}" href="{{ route('panduan.daftar') }}">
                        <i class="bi bi-person-plus-fill"></i> Panduan Daftar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('panduan.login') ? 'active' : '' }}" href="{{ route('panduan.login') }}">
                        <i class="bi bi-box-arrow-in-right"></i> Panduan Log In
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('panduan.menu') ? 'active' : '' }}" href="{{ route('panduan.menu') }}">
                        <i class="bi bi-journal-plus"></i> Panduan Tambah Menu
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('panduan.form') ? 'active' : '' }}" href="{{ route('panduan.form') }}">
                        <i class="bi bi-ui-checks"></i> Form Data Pesanan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('panduan.catatan') ? 'active' : '' }}" href="{{ route('panduan.catatan') }}">
                        <i class="bi bi-receipt"></i> Kelola Catatan Struk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('panduan.pesanan') ? 'active' : '' }}" href="{{ route('panduan.pesanan') }}">
                        <i class="bi bi-cart-plus-fill"></i> Kelola Pesanan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('panduan.riwayat-pesanan') ? 'active' : '' }}" href="{{ route('panduan.riwayat-pesanan') }}">
                        <i class="bi bi-clock-history"></i> Riwayat & Cetak Struk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('panduan.pesanan-batal') ? 'active' : '' }}" href="{{ route('panduan.pesanan-batal') }}">
                        <i class="bi bi-trash3-fill"></i> Pesanan Dibatalkan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('panduan.laporan-bisnis') ? 'active' : '' }}" href="{{ route('panduan.laporan-bisnis') }}">
                        <i class="bi bi-bar-chart-fill"></i> Laporan Bisnis
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('panduan.performa-bisnis') ? 'active' : '' }}" href="{{ route('panduan.performa-bisnis') }}">
                        <i class="bi bi-graph-up"></i> Performa Bisnis
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('panduan.mineai') ? 'active' : '' }}" href="{{ route('panduan.mineai') }}">
                        <i class="bi bi-stars"></i> Panduan Mine AI
                    </a>
                </li>
            </ul>
        </nav>
        
        <div class="mt-4 p-3 rounded-4" style="background-color: #fff4e6; border: 1px solid rgba(255, 106, 0, 0.2);">
            <h6 class="fw-bold text-dark" style="font-size: 0.9rem;">Butuh Bantuan Lain?</h6>
            <p class="text-muted mb-2" style="font-size: 0.8rem;">Hubungi tim support kami jika Anda mengalami kendala teknis.</p>
            <a href="#" class="btn btn-sm w-100 fw-bold" style="background-color: #ff6a00; color: white; border-radius: 8px;">
                <i class="bi bi-whatsapp me-1"></i> Hubungi CS
            </a>
        </div>
    </div>
</aside>