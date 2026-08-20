<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Management Stok - Flavory.id</title>
     <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @include('layouts.favicon')
    <script src="{{ asset('js/loading.js') }}"></script>
</head>
<style>
   body{font-family: 'Poppins', sans-serif; background-color: #fff7ed;}
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; margin: 0; 
    }
</style>
<body>
<div class="container py-4" style="font-family: 'Poppins', sans-serif;">
    <div class="d-flex align-items-center gap-3 mb-4">
                <a href="{{ route('kasir.dashboard') }}" class="btn btn-light border-warning-subtle text-secondary rounded-4 d-flex align-items-center justify-content-center hover-scale" style="width: 48px; height: 48px;">
                    <i class="bi bi-arrow-left fs-4"></i>
                </a>
                <h1 class="h4 mb-0 fw-black text-uppercase" style="font-weight: 900; letter-spacing: -0.5px;">Manajemen Stok Barang</h1>
            </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0 rounded-4 mb-3">
        <div class="card-body p-4">
            <form action="{{ route('kasir.stok.store') }}" method="POST" class="row g-3 align-items-end">
                @csrf
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-muted">Nama Barang</label>
                    <input type="text" name="nama_barang" class="form-control rounded-3" placeholder="Contoh: Kopi Bubuk Arabica" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-muted">Satuan</label>
                    <input type="text" name="satuan" class="form-control rounded-3" placeholder="Contoh: KG, PCS, LT" required>
                </div>
                <div class="col-md-5 d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary fw-bold rounded-3 py-2 w-50" data-bs-toggle="modal" data-bs-target="#modalAturWarnaBaru">
                        <i class="bi bi-palette-fill me-1"></i> ATUR WARNA
                    </button>
                    <button type="submit" class="btn text-white fw-bold rounded-3 py-2 w-50" style="background-color: #fd7e14;">
                        <i class="bi bi-plus-lg me-1"></i> TAMBAH
                    </button>
                </div>

                <div class="modal fade" id="modalAturWarnaBaru" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-4 border-0 shadow">
                            <div class="modal-header border-bottom-0">
                                <h5 class="modal-title fw-bold">Atur Range Warna Stok</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body px-4">
                                <p class="text-muted small mb-4">Tentukan range angka desimal untuk pewarnaan otomatis pada barang yang akan ditambahkan.</p>
                                
                                <div class="row g-2 mb-3">
                                    <div class="col-12"><label class="badge bg-success w-100 text-start">Hijau (Stok Aman)</label></div>
                                    <div class="col-6"><input type="number" step="0.01" name="min_hijau" class="form-control" placeholder="Min" value="10.0"></div>
                                    <div class="col-6"><input type="number" step="0.01" name="max_hijau" class="form-control" placeholder="Max" value="999.0"></div>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-12"><label class="badge bg-warning text-dark w-100 text-start">Kuning (Menipis)</label></div>
                                    <div class="col-6"><input type="number" step="0.01" name="min_kuning" class="form-control" placeholder="Min" value="3.0"></div>
                                    <div class="col-6"><input type="number" step="0.01" name="max_kuning" class="form-control" placeholder="Max" value="9.99"></div>
                                </div>

                                <div class="row g-2 mb-2">
                                    <div class="col-12"><label class="badge bg-danger w-100 text-start">Merah (Hampir Habis)</label></div>
                                    <div class="col-6"><input type="number" step="0.01" name="min_merah" class="form-control" placeholder="Min" value="0.0"></div>
                                    <div class="col-6"><input type="number" step="0.01" name="max_merah" class="form-control" placeholder="Max" value="2.99"></div>
                                </div>
                            </div>
                            <div class="modal-footer border-top-0">
                                <button type="button" class="btn text-white w-100 fw-bold" style="background-color: #fd7e14;" data-bs-dismiss="modal">SELESAI & TUTUP</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4 mb-4 bg-white">
        <div class="card-body p-3">
            <div class="row g-3 align-items-center">
                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" id="inputPencarian" class="form-control border-start-0 ps-0" placeholder="Cari nama barang...">
                    </div>
                </div>
                
                <div class="col-md-3 d-none d-md-block"></div>

                <div class="col-6 col-md-2">
                    <select id="filterBulan" class="form-select text-muted fw-medium">
                        @php
                            $namaBulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        @endphp
                        @foreach($namaBulanList as $index => $nama)
                            <option value="{{ $index + 1 }}" {{ $bulanIni == ($index + 1) ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6 col-md-2">
                    <select id="filterTahun" class="form-select text-muted fw-medium">
                        @for($t = date('Y') - 2; $t <= date('Y') + 1; $t++)
                            <option value="{{ $t }}" {{ $tahunIni == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3" id="listStokContainer">
        @include('kasir.partials.list-stok')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputPencarian = document.getElementById('inputPencarian');
        const filterBulan = document.getElementById('filterBulan');
        const filterTahun = document.getElementById('filterTahun');
        const listContainer = document.getElementById('listStokContainer');
        
        let timeout = null;

        // Fungsi utama untuk memanggil data via AJAX
        function fetchData() {
            const search = inputPencarian.value;
            const bulan = filterBulan.value;
            const tahun = filterTahun.value;

            const url = `{{ route('kasir.stok.index') }}?search=${encodeURIComponent(search)}&bulan=${bulan}&tahun=${tahun}`;

            if (window.showLoading) window.showLoading();
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                listContainer.innerHTML = html;
                if (window.hideLoading) window.hideLoading();
            })
            .catch(error => {
                if (window.hideLoading) window.hideLoading();
                console.error('Terjadi kesalahan AJAX:', error);
            });
        }

        // Event listener dengan Debounce (menunda eksekusi agar tidak setiap huruf memicu request)
        inputPencarian.addEventListener('keyup', function() {
            clearTimeout(timeout);
            timeout = setTimeout(fetchData, 300); // Tunggu 300ms setelah user berhenti mengetik
        });

        // Event listener ketika pilihan bulan atau tahun diganti
        filterBulan.addEventListener('change', fetchData);
        filterTahun.addEventListener('change', fetchData);
    });
</script>
</body>
</html>