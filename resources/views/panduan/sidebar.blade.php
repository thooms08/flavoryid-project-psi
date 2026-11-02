@php
    // Tentukan grup aktif berdasarkan route saat ini
    $isFlavoryRoute = request()->routeIs(
        'panduan.daftar', 'panduan.login', 'panduan.menu', 'panduan.form',
        'panduan.catatan', 'panduan.pesanan', 'panduan.riwayat-pesanan',
        'panduan.pesanan-batal', 'panduan.ikhtisar-bisnis',
        'panduan.laporan-pengeluaran', 'panduan.modal-usaha'
    );
    $isHrmsRoute = request()->routeIs('panduan.hrms') || request()->routeIs('panduan.hrms.*');
@endphp

<style>
    :root {
        --primary-orange: #ff6a00;
        --light-orange:   #fff4e6;
        --hrms-green:     #198754;
        --hrms-light:     #f0fdf4;
        --text-dark:      #1e293b;
        --text-muted:     #64748b;
    }

    /* ── Wrapper ── */
    .sidebar-panduan { font-family: 'Inter', 'Poppins', sans-serif; }

    /* ── Grup header (tombol buka/tutup) ── */
    .sp-group-btn {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 10px 14px;
        border: none;
        border-radius: 12px;
        background: transparent;
        cursor: pointer;
        font-weight: 700;
        font-size: .82rem;
        letter-spacing: .3px;
        transition: background .18s, color .18s;
        text-align: left;
    }
    .sp-group-btn:hover { background: #f1f5f9; }
    .sp-group-btn.flavory-btn           { color: var(--primary-orange); }
    .sp-group-btn.flavory-btn.open,
    .sp-group-btn.flavory-btn:hover     { background: var(--light-orange); }
    .sp-group-btn.hrms-btn              { color: var(--hrms-green); }
    .sp-group-btn.hrms-btn.open,
    .sp-group-btn.hrms-btn:hover        { background: var(--hrms-light); }

    /* Ikon kiri grup */
    .sp-group-icon {
        width: 28px; height: 28px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: .95rem; flex-shrink: 0;
    }
    .flavory-btn .sp-group-icon { background: rgba(255,106,0,.12); color: var(--primary-orange); }
    .hrms-btn    .sp-group-icon { background: rgba(25,135,84,.12);  color: var(--hrms-green); }

    /* Chevron */
    .sp-chevron {
        font-size: .72rem;
        transition: transform .25s ease;
        color: inherit;
        opacity: .7;
    }
    .sp-group-btn.open .sp-chevron { transform: rotate(180deg); }

    /* ── Submenu ── */
    .sp-submenu {
        overflow: hidden;
        max-height: 0;
        transition: max-height .35s ease, opacity .3s ease;
        opacity: 0;
    }
    .sp-submenu.open {
        max-height: 700px;   /* cukup besar untuk menampung semua item */
        opacity: 1;
    }
    .sp-submenu ul {
        list-style: none;
        padding: 4px 0 8px 0;
        margin: 0;
    }

    /* ── Nav link (item submenu) ── */
    .sp-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px 8px 20px;
        border-radius: 10px;
        color: var(--text-muted);
        font-size: .83rem;
        font-weight: 500;
        text-decoration: none;
        transition: background .15s, color .15s, transform .15s;
        margin-bottom: 2px;
        position: relative;
    }
    .sp-link i { font-size: 1rem; flex-shrink: 0; }
    .sp-link:hover {
        background: #f8fafc;
        color: var(--text-dark);
        transform: translateX(3px);
    }
    /* Active — Flavory */
    .sp-link.active-flavory {
        background: var(--light-orange);
        color: var(--primary-orange);
        font-weight: 700;
    }
    .sp-link.active-flavory i { color: var(--primary-orange); }
    /* Active — HRMS */
    .sp-link.active-hrms {
        background: var(--hrms-light);
        color: var(--hrms-green);
        font-weight: 700;
    }
    .sp-link.active-hrms i { color: var(--hrms-green); }

    /* Garis vertikal di samping kiri submenu */
    .sp-submenu ul {
        border-left: 2px solid rgba(0,0,0,.06);
        margin-left: 20px;
    }
    .flavory-btn ~ .sp-submenu ul { border-left-color: rgba(255,106,0,.18); }
    .hrms-btn    ~ .sp-submenu ul { border-left-color: rgba(25,135,84,.18); }

    /* ── Mobile toggle ── */
    .btn-mobile-toggle {
        background: linear-gradient(135deg, var(--primary-orange), #e65f00);
        color: white; border: none; border-radius: .5rem;
        padding: 8px 15px; font-weight: 600;
        display: inline-flex; align-items: center; gap: 8px;
        box-shadow: 0 4px 10px rgba(255,106,0,.2);
    }
    .btn-mobile-toggle:hover {
        color: white; transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(255,106,0,.3);
    }

    /* ── Kotak bantuan ── */
    .sp-help-box {
        margin-top: 16px; padding: 14px;
        border-radius: 14px;
        background: var(--light-orange);
        border: 1px solid rgba(255,106,0,.2);
    }
</style>

{{-- Mobile toggle --}}
<div class="d-lg-none mb-4">
    <button class="btn btn-mobile-toggle" type="button"
            data-bs-toggle="offcanvas" data-bs-target="#sidebarPanduan"
            aria-controls="sidebarPanduan">
        <i class="bi bi-list fs-4"></i> Menu Panduan
    </button>
</div>

<aside class="offcanvas-lg offcanvas-start sidebar-panduan"
       tabindex="-1" id="sidebarPanduan" aria-labelledby="sidebarPanduanLabel">

    <div class="offcanvas-header border-bottom d-lg-none">
        <h5 class="offcanvas-title fw-bold text-dark d-flex align-items-center gap-2"
            id="sidebarPanduanLabel">
            <i class="bi bi-journal-bookmark-fill" style="color:#ff6a00;"></i>
            Panduan Flavory
        </h5>
        <button type="button" class="btn-close"
                data-bs-dismiss="offcanvas" data-bs-target="#sidebarPanduan"
                aria-label="Close"></button>
    </div>

    <div class="offcanvas-body flex-column p-0 p-lg-3">
        <nav class="w-100">

            <div class="d-none d-lg-block mb-2 px-1">
                <span class="text-uppercase fw-bold text-muted"
                      style="font-size:.72rem;letter-spacing:1px;">Daftar Panduan</span>
            </div>

            {{-- ════════════════════════════════
                 GRUP 1 — Flavory.id (POS Kasir)
            ════════════════════════════════ --}}
            <button type="button"
                    class="sp-group-btn flavory-btn {{ $isFlavoryRoute ? 'open' : '' }}"
                    data-sp-target="submenu-flavory"
                    aria-expanded="{{ $isFlavoryRoute ? 'true' : 'false' }}">
                <span class="d-flex align-items-center gap-2">
                    <span class="sp-group-icon"><i class="bi bi-shop-window"></i></span>
                    Flavory.id
                </span>
                <i class="bi bi-chevron-down sp-chevron"></i>
            </button>

            <div id="submenu-flavory"
                 class="sp-submenu {{ $isFlavoryRoute ? 'open' : '' }}">
                <ul>
                    <li>
                        <a class="sp-link {{ request()->routeIs('panduan.daftar') ? 'active-flavory' : '' }}"
                           href="{{ route('panduan.daftar') }}">
                            <i class="bi bi-person-plus-fill"></i> Panduan Daftar
                        </a>
                    </li>
                    <li>
                        <a class="sp-link {{ request()->routeIs('panduan.login') ? 'active-flavory' : '' }}"
                           href="{{ route('panduan.login') }}">
                            <i class="bi bi-box-arrow-in-right"></i> Panduan Log In
                        </a>
                    </li>
                    <li>
                        <a class="sp-link {{ request()->routeIs('panduan.menu') ? 'active-flavory' : '' }}"
                           href="{{ route('panduan.menu') }}">
                            <i class="bi bi-journal-plus"></i> Panduan Tambah Menu
                        </a>
                    </li>
                    <li>
                        <a class="sp-link {{ request()->routeIs('panduan.form') ? 'active-flavory' : '' }}"
                           href="{{ route('panduan.form') }}">
                            <i class="bi bi-ui-checks"></i> Form Data Pesanan
                        </a>
                    </li>
                    <li>
                        <a class="sp-link {{ request()->routeIs('panduan.catatan') ? 'active-flavory' : '' }}"
                           href="{{ route('panduan.catatan') }}">
                            <i class="bi bi-receipt"></i> Kelola Catatan Struk
                        </a>
                    </li>
                    <li>
                        <a class="sp-link {{ request()->routeIs('panduan.pesanan') ? 'active-flavory' : '' }}"
                           href="{{ route('panduan.pesanan') }}">
                            <i class="bi bi-cart-plus-fill"></i> Kelola Pesanan
                        </a>
                    </li>
                    <li>
                        <a class="sp-link {{ request()->routeIs('panduan.riwayat-pesanan') ? 'active-flavory' : '' }}"
                           href="{{ route('panduan.riwayat-pesanan') }}">
                            <i class="bi bi-clock-history"></i> Riwayat & Cetak Struk
                        </a>
                    </li>
                    <li>
                        <a class="sp-link {{ request()->routeIs('panduan.pesanan-batal') ? 'active-flavory' : '' }}"
                           href="{{ route('panduan.pesanan-batal') }}">
                            <i class="bi bi-trash3-fill"></i> Pesanan Dibatalkan
                        </a>
                    </li>
                    <li>
                        <a class="sp-link {{ request()->routeIs('panduan.ikhtisar-bisnis') ? 'active-flavory' : '' }}"
                           href="{{ route('panduan.ikhtisar-bisnis') }}">
                            <i class="bi bi-bar-chart-fill"></i> Ikhtisar Bisnis
                        </a>
                    </li>
                    <li>
                        <a class="sp-link {{ request()->routeIs('panduan.laporan-pengeluaran') ? 'active-flavory' : '' }}"
                           href="{{ route('panduan.laporan-pengeluaran') }}">
                            <i class="bi bi-receipt-cutoff"></i> Laporan Pengeluaran
                        </a>
                    </li>
                    <li>
                        <a class="sp-link {{ request()->routeIs('panduan.modal-usaha') ? 'active-flavory' : '' }}"
                           href="{{ route('panduan.modal-usaha') }}">
                            <i class="bi bi-wallet2"></i> Modal Usaha
                        </a>
                    </li>
                </ul>
            </div>

            {{-- ════════════════════════════════
                 GRUP 2 — HRMS (Key.Flavory.id)
            ════════════════════════════════ --}}
            <button type="button"
                    class="sp-group-btn hrms-btn mt-1 {{ $isHrmsRoute ? 'open' : '' }}"
                    data-sp-target="submenu-hrms"
                    aria-expanded="{{ $isHrmsRoute ? 'true' : 'false' }}">
                <span class="d-flex align-items-center gap-2">
                    <span class="sp-group-icon"><i class="bi bi-people-fill"></i></span>
                    HRMS (Key.Flavory.id)
                </span>
                <i class="bi bi-chevron-down sp-chevron"></i>
            </button>

            <div id="submenu-hrms"
                 class="sp-submenu {{ $isHrmsRoute ? 'open' : '' }}">
                <ul>
                    <li>
                        <a class="sp-link {{ request()->routeIs('panduan.hrms') && !request()->routeIs('panduan.hrms.*') ? 'active-hrms' : '' }}"
                           href="{{ route('panduan.hrms') }}">
                            <i class="bi bi-grid-fill"></i> Overview HRMS
                        </a>
                    </li>
                    <li>
                        <a class="sp-link {{ request()->routeIs('panduan.hrms.akses-dasbor') ? 'active-hrms' : '' }}"
                           href="{{ route('panduan.hrms.akses-dasbor') }}">
                            <i class="bi bi-box-arrow-in-right"></i> Akses Dasbor Manager
                        </a>
                    </li>
                    <li>
                        <a class="sp-link {{ request()->routeIs('panduan.hrms.data-karyawan') ? 'active-hrms' : '' }}"
                           href="{{ route('panduan.hrms.data-karyawan') }}">
                            <i class="bi bi-person-lines-fill"></i> Data Karyawan
                        </a>
                    </li>
                    <li>
                        <a class="sp-link {{ request()->routeIs('panduan.hrms.shift') ? 'active-hrms' : '' }}"
                           href="{{ route('panduan.hrms.shift') }}">
                            <i class="bi bi-calendar-week-fill"></i> Manajemen Shift
                        </a>
                    </li>
                    <li>
                        <a class="sp-link {{ request()->routeIs('panduan.hrms.absensi') ? 'active-hrms' : '' }}"
                           href="{{ route('panduan.hrms.absensi') }}">
                            <i class="bi bi-fingerprint"></i> Rekap Absensi
                        </a>
                    </li>
                    <li>
                        <a class="sp-link {{ request()->routeIs('panduan.hrms.modal-usaha') ? 'active-hrms' : '' }}"
                           href="{{ route('panduan.hrms.modal-usaha') }}">
                            <i class="bi bi-wallet2"></i> Modal Usaha
                        </a>
                    </li>
                    <li>
                        <a class="sp-link {{ request()->routeIs('panduan.hrms.laporan-bisnis') ? 'active-hrms' : '' }}"
                           href="{{ route('panduan.hrms.laporan-bisnis') }}">
                            <i class="bi bi-bar-chart-line-fill"></i> Laporan Bisnis
                        </a>
                    </li>
                    <li>
                        <a class="sp-link {{ request()->routeIs('panduan.hrms.mineai-manager') ? 'active-hrms' : '' }}"
                           href="{{ route('panduan.hrms.mineai-manager') }}">
                            <i class="bi bi-stars"></i> MineAI Manager
                        </a>
                    </li>
                </ul>
            </div>

        </nav>

        {{-- Kotak bantuan --}}
        <div class="sp-help-box">
            <h6 class="fw-bold text-dark mb-1" style="font-size:.85rem;">Butuh Bantuan Lain?</h6>
            <p class="text-muted mb-2" style="font-size:.75rem;">Hubungi tim support jika ada kendala teknis.</p>
            <a href="https://wa.me/6285797574754" target="_blank" rel="noopener noreferrer"
               class="btn btn-sm w-100 fw-bold"
               style="background:#ff6a00;color:white;border-radius:8px;">
                <i class="bi bi-whatsapp me-1"></i> Hubungi CS
            </a>
        </div>
    </div>
</aside>

<script>
// Accordion sidebar panduan — toggle buka/tutup tiap grup
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.sp-group-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var targetId = this.getAttribute('data-sp-target');
            var submenu  = document.getElementById(targetId);
            var isOpen   = submenu.classList.contains('open');

            // Tutup semua submenu lain dulu
            document.querySelectorAll('.sp-submenu').forEach(function (sm) {
                sm.classList.remove('open');
            });
            document.querySelectorAll('.sp-group-btn').forEach(function (b) {
                b.classList.remove('open');
                b.setAttribute('aria-expanded', 'false');
            });

            // Buka yang diklik (toggle: jika sudah open, tutup saja)
            if (!isOpen) {
                submenu.classList.add('open');
                this.classList.add('open');
                this.setAttribute('aria-expanded', 'true');
            }
        });
    });
});
</script>
