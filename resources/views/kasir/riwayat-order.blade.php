<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Riwayat Order Kasir</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    @include('layouts.favicon')
    @include('layouts.loading')
    <style>
        :root { 
            --primary-orange: #fd7e14; 
            --dark-orange: #e86c00; 
            --soft-orange: #fff4e6;
        }

        body { 
            font-family: 'Poppins', sans-serif; 
            background: #fff7ed; /* Background abu-abu muda agar card putih terlihat jelas */
            color: #333;
        }

        .text-orange { color: var(--primary-orange) !important; }
        .bg-orange { background-color: var(--primary-orange) !important; }
        
        /* Dashboard Stats */
        .card-stats {
            transition: transform 0.3s ease;
            border-radius: 16px;
        }
        .card-stats:hover {
            transform: translateY(-5px);
        }

        /* Filter Section */
        .filter-card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important;
        }

        /* Button Styling */
        .btn-orange-outline { 
            border: 2px solid var(--primary-orange); 
            color: var(--primary-orange); 
            font-weight: 600; 
            border-radius: 12px; 
            transition: 0.3s;
            padding: 8px 20px;
        }
        .btn-orange-outline:hover { 
            background: var(--primary-orange); 
            color: white; 
        }

        /* Order Card Styling */
        .order-card { 
            border-radius: 20px; 
            border: none; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            background: #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        }
        .order-card:hover { 
            transform: translateY(-8px); 
            box-shadow: 0 12px 30px rgba(253, 126, 20, 0.1) !important;
        }

        .badge-method { 
            background: var(--soft-orange); 
            color: var(--primary-orange); 
            border: 1px solid #ffe8cc; 
            font-size: 0.7rem; 
            padding: 4px 10px;
            border-radius: 8px;
            font-weight: 700;
        }

        .form-control:focus { 
            border-color: var(--primary-orange); 
            box-shadow: 0 0 0 0.25rem rgba(253, 126, 20, 0.1); 
        }

        /* Pagination custom color */
        .pagination .page-link { color: var(--primary-orange); }
        .pagination .page-item.active .page-link { 
            background-color: var(--primary-orange); 
            border-color: var(--primary-orange); 
        }
    </style>
</head>
<body>
    @include('kasir.layouts.app')
<div class="container py-4">@yield('content') </div>

<div class="container-fluid py-4" style="margin-top:-50px;">
    
    <div class="row g-3 mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark mb-1">
                <i class="bi bi-clock-history text-orange me-2"></i>Riwayat Pesanan
            </h2>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-orange text-white card-stats h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <small class="opacity-75 fw-medium">Total Transaksi</small>
                    <h3 class="fw-bold mb-0" id="stat-transaksi">{{ number_format($stats['total_transaksi'] ?? 0) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-dark text-white card-stats h-100">
                <div class="card-body d-flex flex-column justify-content-center position-relative">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <small class="opacity-75 fw-medium">Total Pendapatan</small>
                        <button id="btn-toggle-pendapatan"
                                onclick="togglePendapatan()"
                                class="btn btn-sm p-0 text-white opacity-75"
                                style="line-height:1; background:none; border:none;"
                                title="Tampilkan / Sembunyikan">
                            <i class="bi bi-eye-slash" id="icon-toggle-pendapatan"></i>
                        </button>
                    </div>
                    <h4 class="fw-bold mb-0" id="stat-pendapatan">Rp {{ number_format($stats['total_pendapatan'] ?? 0, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card filter-card mb-2">
        <div class="card-body p-3">
            <form id="filterForm" class="row g-3">
                <div class="col-lg-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" id="searchInput" class="form-control border-start-0 ps-0" placeholder="Cari Pelanggan, No Pesanan, WA...">
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <label class="small text-muted mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" id="startDate" class="form-control">
                </div>
                <div class="col-lg-2 col-6">
                    <label class="small text-muted mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="endDate" class="form-control">
                </div>
                <div class="col-lg-4 text-lg-end d-flex align-items-end justify-content-lg-end mt-lg-0">
                    <button type="button" onclick="resetFilter()" class="btn btn-light border me-2 py-2 px-3">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </button>
                    <button type="button" onclick="loadAll()" class="btn btn-orange-outline py-2">Lihat Semua</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 ms-auto" id="order-container">
        @include('kasir.partials.order-cards')
    </div>

    <div id="loader" class="text-center py-5 d-none">
        <div class="spinner-border text-orange" role="status"></div>
        <p class="mt-2 text-muted fw-medium">Menyinkronkan data...</p>
    </div>

    <div class="mt-5 d-flex justify-content-center" id="pagination-wrapper">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const filterForm = document.getElementById('filterForm');
    const orderContainer = document.getElementById('order-container');
    const loader = document.getElementById('loader');

    async function fetchOrders(url = "{{ route('kasir.riwayat.index') }}") {
        if (window.showLoading) window.showLoading();
        loader.classList.remove('d-none');
        orderContainer.style.opacity = '0.3';
        
        const params = new URLSearchParams(new FormData(filterForm));
        const finalUrl = `${url}${url.includes('?') ? '&' : '?'}${params.toString()}`;

        try {
            const response = await fetch(finalUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();
            
            orderContainer.innerHTML = data.html;
            document.getElementById('stat-transaksi').innerText = data.stats.total_transaksi;
            // Simpan nilai asli ke data-attribute, lalu terapkan state tersembunyi jika aktif
            const elPendapatan = document.getElementById('stat-pendapatan');
            elPendapatan.dataset.nilai = data.stats.total_pendapatan;
            if (localStorage.getItem('pendapatan_hidden') === '1') {
                elPendapatan.innerText = '••••••••';
            } else {
                elPendapatan.innerText = data.stats.total_pendapatan;
            }

            const paidOrderId = localStorage.getItem('last_paid_order_id');
        if (paidOrderId) {
            const targetCard = document.getElementById(`order-card-${paidOrderId}`);
            if (targetCard) {
                // Tambahkan efek shadow
                targetCard.classList.add('order-paid-highlight');
                
                // Hilangkan setelah 5 detik
                setTimeout(() => {
                    targetCard.classList.remove('order-paid-highlight');
                    localStorage.removeItem('last_paid_order_id');
                }, 5000);
            }
        }
            
            const paginator = document.getElementById('pagination-wrapper');
            if(params.get('limit') === 'all') paginator.classList.add('d-none');
            else paginator.classList.remove('d-none');

        } catch (error) {
            console.error("Fetch Error:", error);
        } finally {
            loader.classList.add('d-none');
            orderContainer.style.opacity = '1';
            if (window.hideLoading) window.hideLoading();
        }
    }

    let debounceTimer;
    document.getElementById('searchInput').addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => fetchOrders(), 500);
    });

    document.getElementById('startDate').addEventListener('change', () => fetchOrders());
    document.getElementById('endDate').addEventListener('change', () => fetchOrders());

    function resetFilter() {
        filterForm.reset();
        fetchOrders();
    }

    function loadAll() {
        const url = new URL("{{ route('kasir.riwayat.index') }}");
        url.searchParams.set('limit', 'all');
        fetchOrders(url.toString());
    }

    // ── Toggle sembunyikan / tampilkan Total Pendapatan ──
    function togglePendapatan() {
        const el   = document.getElementById('stat-pendapatan');
        const icon = document.getElementById('icon-toggle-pendapatan');
        const hidden = localStorage.getItem('pendapatan_hidden') === '1';

        if (hidden) {
            // Tampilkan kembali
            el.innerText = el.dataset.nilai || el.innerText;
            icon.className = 'bi bi-eye-slash';
            localStorage.setItem('pendapatan_hidden', '0');
        } else {
            // Sembunyikan
            el.dataset.nilai = el.innerText; // simpan nilai asli sebelum disembunyikan
            el.innerText = '••••••••';
            icon.className = 'bi bi-eye';
            localStorage.setItem('pendapatan_hidden', '1');
        }
    }

    // Terapkan state tersimpan saat halaman pertama kali dimuat
    (function initPendapatanState() {
        if (localStorage.getItem('pendapatan_hidden') === '1') {
            const el   = document.getElementById('stat-pendapatan');
            const icon = document.getElementById('icon-toggle-pendapatan');
            el.dataset.nilai = el.innerText;
            el.innerText     = '••••••••';
            icon.className   = 'bi bi-eye';
        }
    })();
</script>
</body>
</html>