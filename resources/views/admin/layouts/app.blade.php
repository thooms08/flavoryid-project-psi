<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Flavory.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @include('admin.layouts.favicon_admin')
    
    <style>
        :root {
            --bg-dark: #070B19;
            --bg-sidebar: #0C152D;
            --bg-card: #132040;
            --text-primary: #E2E8F0;
            --accent-cyan: #00E5FF;
            --accent-blue: #3B82F6;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-primary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* Layout Structure */
        #wrapper {
            display: flex;
            width: 100vw;
            height: 100vh;
        }

        /* Sidebar Styling */
        #sidebar {
            width: 260px;
            background-color: var(--bg-sidebar);
            height: 100vh;
            border-right: 1px solid rgba(0, 229, 255, 0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            position: fixed;
            z-index: 1000;
        }

        #sidebar.toggled {
            margin-left: -260px;
        }

        #sidebar .sidebar-heading {
            padding: 1.5rem;
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--accent-cyan);
            border-bottom: 1px solid rgba(0, 229, 255, 0.1);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Tombol Close Sidebar (Mobile) */
        .btn-close-sidebar {
            background: transparent;
            color: rgba(255, 255, 255, 0.5);
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            transition: 0.3s ease;
            padding: 0;
            line-height: 1;
        }

        .btn-close-sidebar:hover {
            color: #EF4444; /* Merah neon saat disentuh */
            text-shadow: 0 0 10px rgba(239, 68, 68, 0.8);
        }

        #sidebar .list-group-item {
            background: transparent;
            color: var(--text-primary);
            border: none;
            padding: 1rem 1.5rem;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        #sidebar .list-group-item:hover, #sidebar .list-group-item.active {
            background-color: rgba(0, 229, 255, 0.1);
            color: var(--accent-cyan);
            border-left: 4px solid var(--accent-cyan);
        }

        /* Main Content */
        #page-content-wrapper {
            flex-grow: 1;
            margin-left: 260px;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            width: calc(100% - 260px);
        }

        #page-content-wrapper.toggled {
            margin-left: 0;
            width: 100%;
        }

        /* Navbar */
        .navbar-cyber {
            background-color: var(--bg-sidebar);
            border-bottom: 1px solid rgba(0, 229, 255, 0.1);
            padding: 1rem 1.5rem;
        }

        .btn-toggle {
            background: transparent;
            color: var(--accent-cyan);
            border: 1px solid var(--accent-cyan);
            border-radius: 5px;
            padding: 5px 12px;
            transition: 0.3s;
        }

        .btn-toggle:hover {
            background: var(--accent-cyan);
            color: var(--bg-dark);
        }

        .btn-cyber-outline {
            background: transparent;
            color: #94A3B8;
            border: 1px solid #475569;
            transition: 0.3s;
        }
        .btn-cyber-outline:hover {
            background: rgba(148, 163, 184, 0.1);
            color: #FFF;
            border-color: #94A3B8;
        }

        /* Responsive */
        @media (max-width: 768px) {
            #sidebar {
                margin-left: -260px;
            }
            #sidebar.toggled {
                margin-left: 0;
            }
            #page-content-wrapper {
                margin-left: 0;
                width: 100%;
            }
        }

        /* Scrollbar kustom untuk Sidebar */
        #sidebar .list-group {
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 229, 255, 0.2) transparent;
        }
        #sidebar .list-group::-webkit-scrollbar {
            width: 4px;
        }
        #sidebar .list-group::-webkit-scrollbar-track {
            background: transparent;
        }
        #sidebar .list-group::-webkit-scrollbar-thumb {
            background-color: rgba(0, 229, 255, 0.2);
            border-radius: 20px;
        }
    </style>
</head>
<body>

    <div id="wrapper">
        <div id="sidebar">
            <div class="sidebar-heading d-flex justify-content-between align-items-center">
                <div><i class="fa-solid fa-bolt"></i> FLAVORY.ID</div>
                
                <button id="sidebar-close" class="btn-close-sidebar d-md-none">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <div class="list-group list-group-flush mt-3 flex-grow-1">
                <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i> Dashboard
                </a>
                <a href="{{ route('admin.partner.aktif') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.partner.aktif') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i> Partner Aktif
                </a>
                <a href="{{ route('admin.partner.jatuh_tempo') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.partner.jatuh_tempo') ? 'active' : '' }}">
                    <i class="fa-solid fa-clock-rotate-left"></i> Partner Jatuh Tempo
                </a>
                <a href="{{ route('admin.partner.nonaktif') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.partner.nonaktif') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-xmark"></i> Partner Nonaktif
                </a>
                <a href="{{ route('admin.partner.tanpa_status') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.partner.tanpa_status') ? 'active' : '' }}">
                    <i class="fa-solid fa-question-circle"></i> Tanpa Status
                </a>
                <a href="{{ route('admin.akun_partner.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.akun_partner.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-lock"></i> Akun Partner
                </a>
                <a href="{{ route('admin.aktivitas.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.aktivitas.index') || request()->routeIs('admin.partner.aktivitas') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i> Aktivitas Partner
                </a>
            </div>
            
            <div class="p-3 border-top" style="border-color: rgba(0, 229, 255, 0.1) !important;">
                <button type="button" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </div>
        </div>

        <div id="page-content-wrapper">
            <nav class="navbar navbar-cyber d-flex justify-content-between align-items-center">
                <button class="btn-toggle" id="menu-toggle">
                    <i class="fa-solid fa-bars fs-5"></i>
                </button>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('admin.profile') }}" class="d-flex align-items-center justify-content-center text-info fs-4" style="transition: 0.3s;" title="Profil Admin">
                        <i class="fa-solid fa-circle-user" style="color: var(--accent-cyan);"></i>
                    </a>
                    <span class="fw-semibold text-white d-none d-sm-inline">Admin Panel</span>
                </div>
            </nav>

            <div class="container-fluid p-4">
                @yield('content')
            </div>
        </div>
<!-- Modal Logout Confirmation -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="background: var(--bg-dark); border: 1px solid #ef4444; box-shadow: 0 0 20px rgba(239,68,68,0.2);">
            <div class="modal-body text-center py-4">
                <i class="fa-solid fa-triangle-exclamation fa-3x mb-3 text-danger" style="text-shadow: 0 0 10px rgba(239, 68, 68, 0.5);"></i>
                <h5 class="fw-bold text-white mb-2" id="logoutModalLabel">Konfirmasi Logout</h5>
                <p class="text-secondary small mb-4">Apakah Anda yakin ingin keluar dari Admin Panel?</p>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-cyber-outline w-100 fw-bold" data-bs-dismiss="modal">BATAL</button>
                    <form action="{{ route('logout') }}" method="POST" class="w-100">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100 fw-bold">YA, KELUAR</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Logika untuk membuka/menutup sidebar dari Navbar
        document.getElementById("menu-toggle").addEventListener("click", function(e) {
            e.preventDefault();
            document.getElementById("sidebar").classList.toggle("toggled");
            document.getElementById("page-content-wrapper").classList.toggle("toggled");
        });

        // Logika baru untuk tombol "X" di dalam Sidebar
        const sidebarCloseBtn = document.getElementById("sidebar-close");
        if (sidebarCloseBtn) {
            sidebarCloseBtn.addEventListener("click", function(e) {
                e.preventDefault();
                // Hapus class 'toggled' untuk menyembunyikan sidebar di mode mobile
                document.getElementById("sidebar").classList.remove("toggled");
                document.getElementById("page-content-wrapper").classList.remove("toggled");
            });
        }
    </script>

    @stack('scripts')
</body>
</html>