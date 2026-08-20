<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <title>Daftar Order Batal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    @include('layouts.favicon')
    <script src="{{ asset('js/loading.js') }}"></script>
    <style>
        :root { 
            --primary-red: #dc3545; 
            --soft-red: #fff5f5;
        }
        body { font-family: 'Poppins', sans-serif; background: #fff5f5; color: #333; }
        .text-red { color: var(--primary-red) !important; }
        .bg-red { background-color: var(--primary-red) !important; }
        
        .card-stats { transition: transform 0.3s ease; border-radius: 16px; }
        .filter-card { border-radius: 15px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important; }

        .btn-red-outline { 
            border: 2px solid var(--primary-red); color: var(--primary-red); 
            font-weight: 600; border-radius: 12px; transition: 0.3s; padding: 8px 20px;
        }
        .btn-red-outline:hover { background: var(--primary-red); color: white; }

        .order-card { 
            border-radius: 20px; border: none; transition: all 0.3s ease; 
            background: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        }
        .order-card:hover { transform: translateY(-8px); box-shadow: 0 12px 30px rgba(220, 53, 69, 0.1) !important; }
        
        /* Override oranye dari partials agar sesuai tema batal */
        .text-orange { color: var(--primary-red) !important; }
        .spinner-border.text-orange { color: var(--primary-red) !important; }
    </style>
</head>
<body>
    @include('kasir.layouts.app')
    <div class="container py-4">@yield('content') </div>

    <div class="container-fluid py-4" style="margin-top:-50px;">
        <div class="row g-3 mb-4 align-items-center">
            <div class="col-md-6">
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-x-circle-fill text-red me-2"></i>Pesanan Batal
                </h2>
                <p class="text-muted small">Daftar transaksi dengan status gagal/batal</p>
            </div> 
            <div class="col-md-3 ms-auto">
                <div class="card border-0 shadow-sm bg-red text-white card-stats h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <small class="opacity-75 fw-medium">Total Pesanan Batal</small>
                        <h3 class="fw-bold mb-0" id="stat-transaksi">{{ number_format($stats['total_transaksi'] ?? 0) }}</h3>
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
                            <input type="text" name="search" id="searchInput" class="form-control border-start-0 ps-0" placeholder="Cari No Pesanan atau Pelanggan...">
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
                        <button type="button" onclick="loadAll()" class="btn btn-red-outline py-2">Lihat Semua</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-3" id="order-container">
            @include('kasir.partials.fail-order-cards')
        </div>

        <div id="loader" class="text-center py-5 d-none">
            <div class="spinner-border text-danger" role="status"></div>
            <p class="mt-2 text-muted fw-medium">Memuat data batal...</p>
        </div>

        <div class="mt-5 d-flex justify-content-center" id="pagination-wrapper">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const filterForm = document.getElementById('filterForm');
        const orderContainer = document.getElementById('order-container');
        const loader = document.getElementById('loader');

        async function fetchOrders(url = "{{ route('kasir.order-batal.index') }}") {
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
                // Jika element stat-pendapatan ada di html Anda, ini akan bekerja. Jika tidak, abaikan errornya.
                if(document.getElementById('stat-pendapatan')){
                    document.getElementById('stat-pendapatan').innerText = data.stats.total_pendapatan;
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
            const url = new URL("{{ route('kasir.order-batal.index') }}");
            url.searchParams.set('limit', 'all');
            fetchOrders(url.toString());
        }

        // TAMBAHAN: Fungsi Restore yang sebelumnya terlewat
        function restoreOrder(orderId) {
            Swal.fire({
                title: 'Pulihkan Pesanan?',
                text: "Pesanan ini akan dikembalikan ke antrean aktif dan memotong kembali stok menu.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Pulihkan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Memproses...', didOpen: () => Swal.showLoading(), allowOutsideClick: false });
                    
                    fetch(`/kasir/order-batal/${orderId}/restore`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: data.message,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                // 1. Simpan ID pesanan ke localStorage agar halaman utama tahu card mana yang harus diberi efek shadow oranye
                                localStorage.setItem('new_order_id', orderId);
                                
                                // 2. Arahkan otomatis (direct) kembali ke halaman utama kasir
                                window.location.href = "{{ route('kasir.index') }}"; 
                                // Catatan: pastikan route('kasir.index') sesuai dengan nama route halaman utama kasir Anda
                            });
                        } else {
                            Swal.fire('Gagal', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        Swal.fire('Error', 'Terjadi kesalahan sistem saat menghubungi server', 'error');
                    });
                }
            });
        }
    </script>
</body>
</html>