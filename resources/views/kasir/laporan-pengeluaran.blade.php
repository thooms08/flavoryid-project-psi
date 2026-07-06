<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Pengeluaran - Flavory.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('layouts.favicon')
    @include('layouts.loading')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    :root {
        --bs-orange: #fd7e14;
        --bs-orange-hover: #e37011;
    }
    body{font-family: 'Poppins', sans-serif; background-color: #fff7ed;}
    .btn-orange { background-color: var(--bs-orange); color: white; border: none; }
    .btn-orange:hover { background-color: var(--bs-orange-hover); color: white; }
    .btn-outline-orange { color: var(--bs-orange); border-color: var(--bs-orange); }
    .btn-outline-orange:hover { background-color: var(--bs-orange); color: white; }
    .text-orange { color: var(--bs-orange) !important; }
    .border-orange { border-color: var(--bs-orange) !important; }
    
    .gallery-img { object-fit: cover; height: 150px; width: 100%; border-radius: 8px; cursor: pointer; transition: 0.3s; }
    .gallery-img:hover { filter: brightness(0.8); }
    #kategoriSelect:focus, #keteranganInput:focus, #qty:focus, #harga:focus, #total:focus, #item:focus, #keterangan:focus {
        border-color: #fd7e14 !important;
        box-shadow: 0 0 0 0.25rem rgba(253, 126, 20, 0.2), 0 8px 15px rgba(253, 126, 20, 0.1) !important;
        background-color: #fff !important;
        outline: 0;
    }
    /* Nav Tab Styling */
    .nav-status .btn {
        border-radius: 20px;
        padding: 5px 15px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.2s;
        border: 1px solid transparent;
    }
    .nav-status .btn-active {
        background-color: var(--bs-orange);
        color: white;
        box-shadow: 0 4px 10px rgba(253, 126, 20, 0.2);
    }
    .nav-status .btn-inactive {
        background-color: #f8f9fa;
        color: #6c757d;
        border-color: #dee2e6;
    }
    .nav-status .btn-inactive:hover {
        border-color: var(--bs-orange);
        color: var(--bs-orange);
    }
</style>
<body>

<div class="container-fluid py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('kasir.laporan.index') }}" class="btn btn-light border-warning-subtle text-secondary rounded-4 d-flex align-items-center justify-content-center hover-scale" style="width: 48px; height: 48px;">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="h4 mb-0 fw-black text-uppercase" style="font-weight: 900; letter-spacing: -0.5px;">Laporan Pengeluaran</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4 rounded-3 border-top border-orange border-3">
        <div class="card-body">
            <form action="{{ route('kasir.pengeluaran.index') }}" method="GET" class="row g-3 align-items-end">
                <input type="hidden" name="status" value="{{ $status }}">
                <div class="col-md-5">
                    <label class="form-label fw-bold text-muted">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-bold text-muted">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-orange w-100 fw-medium shadow-sm"><i class="bi bi-filter"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4 rounded-3">
        <div class="card-header bg-white pt-4 pb-2 border-0">
            <h5 class="fw-bold text-dark m-0"><i class="bi bi-cart-dash text-danger me-2"></i> Input Pengeluaran Baru</h5>
        </div>
        
        <form action="{{ route('kasir.pengeluaran.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="status" value="save">
            
            <div class="card-body bg-light">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Kategori Pengeluaran</label>
                        <select name="kategori" id="kategoriSelect" class="form-select shadow-sm" required>
                            <option value="operasional usaha" selected>Operasional Usaha</option>
                            <option value="operasional karyawan">Operasional Karyawan</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="col-md-6" id="keteranganContainer" style="display: none;">
                        <label class="form-label fw-bold">Keterangan Tambahan</label>
                        <input type="text" name="keterangan" id="keteranganInput" class="form-control shadow-sm" placeholder="Sebutkan keterangan...">
                    </div>
                </div>

                <label class="form-label fw-bold">Rincian Item</label>
                <div id="itemsContainer" class="d-flex flex-column gap-3 mb-3">
                    <div class="row align-items-stretch bg-white p-3 rounded shadow-sm item-row m-0">
                        <div class="col-md-11">
                            <div class="row align-items-center">
                                <div class="col-md-4 mb-2 mb-md-0">
                                    <input type="text" id="item" name="items[]" class="form-control" placeholder="Nama Barang (Mis: Es Batu)" required>
                                </div>
                                <div class="col-md-3 mb-2 mb-md-0">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">Rp</span>
                                        <input type="number" id="harga" name="hargas[]" class="form-control harga-input" placeholder="Harga" required>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-2 mb-md-0">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">Qty</span>
                                        <input type="number" id="qty" name="qtys[]" class="form-control qty-input" placeholder="Qty" step="0.01" value="1" required>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2 mb-md-0">
                                    <input type="number" id="total" name="totals[]" class="form-control total-input border-orange fw-bold text-orange text-end" placeholder="Total" required>
                                </div>
                                <div class="col-12 mt-2">
                                    <input type="text" id="keterangan" name="keterangans[]" class="form-control form-control-sm bg-light text-muted border-0" placeholder="Keterangan item ini (Opsional)">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 d-flex justify-content-center align-items-center mt-3 mt-md-0 border-start">
                            <button type="button" class="btn btn-outline-danger btn-remove-row" disabled>
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <button type="button" class="btn btn-light border-orange text-orange btn-sm fw-bold shadow-sm rounded-pill px-3" id="btnAddRow">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Baris
                    </button>
                </div>
            </div>

            <div class="card-footer bg-white border-0 pt-3 pb-4 rounded-bottom">
                <div class="mb-4 bg-light p-3 rounded shadow-sm border border-light text-center">
                    <label class="form-label d-block fw-bold text-dark mb-2 small">
                        <i class="bi bi-camera me-1 text-orange" style="color: var(--primary-orange);"></i> Upload Bukti (Opsional)
                    </label>
                    
                    <div class="upload-area @error('bukti') error @enderror" style="cursor: pointer; padding: 15px;" onclick="document.getElementById('buktiFile').click()">
                        <div class="modern-preview-wrapper" id="bukti-preview-wrapper" style="display: none;">
                            <img src="#" alt="Preview Bukti" id="bukti-preview" class="img-fluid rounded mb-1" style="max-height: 120px; object-fit: contain;">
                            <div class="change-image-btn mt-1" style="font-size: 0.85rem;"><i class="bi bi-arrow-repeat me-1"></i> Ganti Bukti</div>
                        </div>

                        <div class="upload-placeholder py-2" id="bukti-placeholder">
                            <i class="bi bi-cloud-arrow-up fs-2 text-secondary"></i>
                            <p class="mt-1 mb-0" style="font-size: 0.9rem;">Klik di sini untuk unggah</p>
                            <small class="text-muted" style="font-size: 0.75rem;">Maks 2 MB (JPG/PNG)</small>
                        </div>

                        <input type="file" id="buktiFile" name="bukti" class="d-none" accept=".jpg,.jpeg,.png" onchange="previewModernImage(this, 'bukti-preview', 'bukti-preview-wrapper', 'bukti-placeholder')">
                    </div>
                    
                    @error('bukti') 
                        <div class="text-danger mt-1" style="font-size: 0.8rem;">{{ $message }}</div> 
                    @enderror
                </div>

                <div class="d-flex justify-content-center gap-2">
                    <button type="reset" class="btn btn-light border shadow-sm px-4 fw-medium">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-orange shadow-sm px-5 fw-bold">
                        <i class="bi bi-floppy me-1"></i> Simpan Pengeluaran
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="card shadow-sm border-0 border-top border-dark border-3 rounded-3">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <h5 class="fw-bold text-dark m-0" id="tableTitle"><i class="bi bi-table text-dark me-2"></i> Rekap Data ({{ $status == 'save' ? 'Tersedia' : 'Terhapus' }})</h5>
                
                {{-- TAB NAVIGASI STATUS --}}
                <div class="nav-status d-flex gap-2">
                    <a href="{{ route('kasir.pengeluaran.index', ['status' => 'save', 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                       class="btn {{ $status == 'save' ? 'btn-active' : 'btn-inactive' }}">
                        <i class="bi bi-check-circle me-1"></i> Data Tersedia
                    </a>
                    <a href="{{ route('kasir.pengeluaran.index', ['status' => 'delete', 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                       class="btn {{ $status == 'delete' ? 'btn-active' : 'btn-inactive' }}">
                        <i class="bi bi-trash-fill me-1"></i> Data Terhapus
                    </a>
                </div>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-dark btn-sm fw-medium shadow-sm" data-bs-toggle="modal" data-bs-target="#modalGallery">
                        <i class="bi bi-folder-fill me-1"></i> FOLDER BUKTI
                    </button>
                    <form action="{{ route('kasir.pengeluaran.export') }}" method="GET" class="m-0">
                        <input type="hidden" name="start_date" value="{{ $startDate }}">
                        <input type="hidden" name="end_date" value="{{ $endDate }}">
                        <button type="submit" class="btn btn-success btn-sm fw-medium shadow-sm">
                            <i class="bi bi-file-earmark-excel me-1"></i> EXCEL
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                   <thead class="table-light text-muted" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                        <tr>
                            <th class="text-center py-3" width="5%">#</th>
                            <th class="py-3" width="15%">KATEGORI</th>
                            <th class="py-3" width="20%">NAMA ITEM</th>
                            <th class="text-end py-3" width="12%">HARGA</th>
                            <th class="text-center py-3" width="8%">QTY</th>
                            <th class="text-end py-3" width="12%">TOTAL</th>
                            <th class="py-3" width="18%">TANGGAL</th>
                            <th class="text-center py-3" width="10%">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0" id="tableBody">
                        @forelse($pengeluarans as $index => $p)
                            <tr class="table-row" data-index="{{ $index }}">
                                <td class="text-center text-muted fw-medium">{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge {{ $p->kategori == 'operasional usaha' ? 'bg-warning-subtle text-warning' : ($p->kategori == 'operasional karyawan' ? 'bg-info-subtle text-info' : 'bg-warning-subtle text-warning') }} border">
                                        {{ ucwords($p->kategori) }}
                                    </span>
                                    @if($p->keterangan) <div class="small text-muted mt-1"><i class="bi bi-quote"></i> {{ $p->keterangan }}</div> @endif
                                </td>
                                <td class="fw-medium text-dark">{{ $p->item }}</td>
                                <td class="text-end text-secondary">Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                                <td class="text-center"><span class="badge bg-light text-dark border">{{ floatval($p->qty) }}</span></td>
                                <td class="text-end fw-bold text-danger">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-medium" style="font-size: 0.9rem;">
                                            <i class="bi bi-calendar-event text-muted me-1"></i> {{ \Carbon\Carbon::parse($p->created_at)->timezone('Asia/Jakarta')->translatedFormat('d M Y') }}
                                        </span>
                                        <span class="text-muted" style="font-size: 0.8rem;">
                                            <i class="bi bi-clock me-1"></i> {{ \Carbon\Carbon::parse($p->created_at)->timezone('Asia/Jakarta')->format('H:i') }} WIB
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        @if($status == 'save')
                                            <button class="btn btn-sm btn-light text-warning border shadow-sm" onclick="openEditModal({{ $p->id }}, '{{ addslashes($p->item) }}', {{ $p->harga }}, {{ floatval($p->qty) }}, {{ $p->total }}, '{{ addslashes($p->keterangan) }}')"><i class="bi bi-pencil"></i></button>
                                            <form action="{{ route('kasir.pengeluaran.destroy', $p->id) }}" method="POST" class="m-0 form-trash">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light text-danger border shadow-sm"><i class="bi bi-trash"></i></button>
                                            </form>
                                        @else
                                            {{-- Tambahkan class 'form-restore' pada form pulihkan --}}
                                            <form action="{{ route('kasir.pengeluaran.restore', $p->id) }}" method="POST" class="m-0 form-restore">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success border shadow-sm fw-bold">
                                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Pulihkan
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-5 text-muted">Belum ada pengeluaran pada periode ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div id="footerTableArea">
            @if(count($pengeluarans) > 10)
            <div class="card-footer bg-white text-center py-3 border-top border-light">
                <button type="button" id="btnShowAllTable" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-medium shadow-sm transition-all">
                    LIHAT SEMUA ({{ count($pengeluarans) }} Data) <i class="bi bi-chevron-down ms-1"></i>
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-dark border-3 border-top">
            <form id="formEdit" method="POST">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Edit Item Pengeluaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Item</label>
                        <input type="text" name="item" id="editItem" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">Harga</label>
                            <input type="number" name="harga" id="editHarga" class="form-control" required oninput="calcEdit()">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">Qty</label>
                            <input type="number" step="0.01" name="qty" id="editQty" class="form-control" required oninput="calcEdit()">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-danger">Total Keseluruhan</label>
                        <input type="number" step="0.01" name="total" id="editTotal" class="form-control fw-bold border-danger" required>
                    </div>
                </div>
                <div class="mb-3 px-3">
                    <label class="form-label fw-bold">Keterangan / Catatan</label>
                    <textarea name="keterangan" id="editKeterangan" class="form-control" rows="2" placeholder="Catatan opsional..."></textarea>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-dark px-4 rounded-pill fw-medium"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalGallery" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-dark border-3 border-top">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-images text-dark me-2"></i> Folder Bukti Pengeluaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-light">
                <div class="row g-3">
                    @forelse($galleryBukti as $bukti)
                        <div class="col-md-4 col-sm-6 text-center">
                            <div class="card shadow-sm border-0 h-100 p-2">
                                <a href="{{ route('private.pengeluaran', basename($bukti->path_bukti)) }}" target="_blank">
                                    <img src="{{ route('private.pengeluaran', basename($bukti->path_bukti)) }}" class="gallery-img mb-2" alt="Bukti">
                                </a>
                                <small class="text-muted fw-bold d-block">
                                    <i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($bukti->created_at)->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }}
                                </small>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted py-4">Tidak ada gambar bukti pada periode ini.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    // 1. Logika Kategori (Show/Hide Keterangan)
    const katSelect = document.getElementById('kategoriSelect');
    const ketContainer = document.getElementById('keteranganContainer');
    const ketInput = document.getElementById('keteranganInput');

    katSelect.addEventListener('change', function() {
        if(this.value === 'lainnya') {
            ketContainer.style.display = 'block';
            ketInput.required = true;
        } else {
            ketContainer.style.display = 'none';
            ketInput.required = false;
            ketInput.value = '';
        }
    });

    // 2. Logika Multi Row
    const container = document.getElementById('itemsContainer');
    const btnAdd = document.getElementById('btnAddRow');
    
    // Fungsi auto hitung jika harga atau qty diketik
    container.addEventListener('input', function(e) {
        if(e.target.classList.contains('harga-input') || e.target.classList.contains('qty-input')) {
            let row = e.target.closest('.item-row');
            let harga = parseFloat(row.querySelector('.harga-input').value) || 0;
            let qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            let total = harga * qty;
            
            row.querySelector('.total-input').value = total;
        }
    });

    // Fungsi Tambah Baris
    btnAdd.addEventListener('click', function() {
        let firstRow = container.querySelector('.item-row');
        let newRow = firstRow.cloneNode(true);
        
        // Reset values
        let inputs = newRow.querySelectorAll('input[type="text"], input[type="number"]');
        inputs.forEach(inpt => {
            if(inpt.classList.contains('qty-input')) {
                inpt.value = '1'; // Default Qty kembali ke 1
            } else {
                inpt.value = '';
            }
        });
        
        // Aktifkan tombol hapus untuk baris baru
        let btnRemove = newRow.querySelector('.btn-remove-row');
        btnRemove.disabled = false;
        
        container.appendChild(newRow);
    });

    // Fungsi Hapus Baris dengan SweetAlert
    container.addEventListener('click', function(e) {
        if(e.target.closest('.btn-remove-row')) {
            let row = e.target.closest('.item-row');
            
            if(container.querySelectorAll('.item-row').length > 1) {
                Swal.fire({
                    title: 'Hapus baris ini?',
                    text: "Inputan pada baris ini akan dihapus.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        row.remove();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Tidak dapat menghapus',
                    text: 'Minimal harus ada 1 rincian item pengeluaran!',
                    confirmButtonColor: '#fd7e14'
                });
            }
        }
    });

    // 3. Logika Limit Tabel & Lihat Semua (Dibungkus dalam function agar bisa dipanggil ulang setelah AJAX)
    function initTableLimit() {
        let isShowingAll = false;
        const maxRows = 10;
        const tableRows = document.querySelectorAll('.table-row');
        const btnShowAll = document.getElementById('btnShowAllTable');

        function updateTable() {
            tableRows.forEach((row, index) => {
                row.style.display = (isShowingAll || index < maxRows) ? '' : 'none';
            });
        }

        if (tableRows.length > 0) updateTable();

        if (btnShowAll) {
            // Re-clone to avoid duplicate event listeners after ajax re-render
            let newBtn = btnShowAll.cloneNode(true);
            btnShowAll.parentNode.replaceChild(newBtn, btnShowAll);
            
            newBtn.addEventListener('click', function() {
                isShowingAll = !isShowingAll;
                this.innerHTML = isShowingAll 
                    ? 'SEMBUNYIKAN <i class="bi bi-chevron-up ms-1"></i>' 
                    : `LIHAT SEMUA (${tableRows.length} Data) <i class="bi bi-chevron-down ms-1"></i>`;
                updateTable();
            });
        }
    }
    initTableLimit();

    // 4. AJAX Untuk Navigasi Tab Tanpa Reload
    document.querySelectorAll('.nav-status a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            let url = this.getAttribute('href');
            if (window.showLoading) window.showLoading();

            // Tambahkan Efek Loading pada Table
            document.getElementById('tableBody').innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <div class="spinner-border text-orange mb-2" role="status"></div>
                        <div>Memuat data...</div>
                    </td>
                </tr>
            `;

            // Update Class Tombol Tab Secara Visual
            document.querySelectorAll('.nav-status a').forEach(a => {
                a.classList.remove('btn-active');
                a.classList.add('btn-inactive');
            });
            this.classList.remove('btn-inactive');
            this.classList.add('btn-active');

            // Fetch Request Baru
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => response.text())
                .then(html => {
                    let doc = new DOMParser().parseFromString(html, 'text/html');

                    // Ganti bagian Body tabel
                    let newTbody = doc.getElementById('tableBody');
                    if(newTbody) document.getElementById('tableBody').innerHTML = newTbody.innerHTML;

                    // Ganti Judul
                    let newTitle = doc.getElementById('tableTitle');
                    if(newTitle) document.getElementById('tableTitle').innerHTML = newTitle.innerHTML;

                    // Ganti Area Footer / Tombol Lihat Semua
                    let newFooterArea = doc.getElementById('footerTableArea');
                    let oldFooterArea = document.getElementById('footerTableArea');
                    if(newFooterArea && oldFooterArea) {
                        oldFooterArea.innerHTML = newFooterArea.innerHTML;
                    }

                    // Reset logika filter tabel
                    initTableLimit();

                    // Update History URL tanpa reload (Agar saat direfresh halamannya tetap ada di tab yang sesuai)
                    window.history.pushState({path: url}, '', url);
                    if (window.hideLoading) window.hideLoading();
                })
                .catch(error => {
                    if (window.hideLoading) window.hideLoading();
                    Swal.fire('Error', 'Gagal memuat data.', 'error');
                });
        });
    });

    // 5. Konfirmasi Action (Hapus & Pulihkan) menggunakan Event Delegation 
    // Event delegation digunakan agar form yang baru diload via AJAX tetap terdeteksi
    document.addEventListener('submit', function(e) {
        
        // Hapus (Pindah Tong Sampah)
        if(e.target.closest('.form-trash')) {
            e.preventDefault();
            let form = e.target.closest('.form-trash');
            Swal.fire({
                title: 'Pindahkan ke Tab Terhapus?',
                text: "Data ini akan disembunyikan dari rekap utama.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#fd7e14',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Pindahkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (window.showLoading) window.showLoading();
                    form.submit();
                }
            });
        }

        // Pulihkan (Restore Data)
        if(e.target.closest('.form-restore')) {
            e.preventDefault();
            let form = e.target.closest('.form-restore');
            Swal.fire({
                title: 'Pulihkan Data?',
                text: "Data pengeluaran ini akan dikembalikan ke daftar utama (Tersedia).",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Pulihkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (window.showLoading) window.showLoading();
                    form.submit();
                }
            });
        }
    });
});

// ================= GLOBAL FUNCTIONS =================

function openEditModal(id, item, harga, qty, total, keterangan) {
    document.getElementById('formEdit').action = '/kasir/pengeluaran/' + id;
    document.getElementById('editItem').value = item;
    document.getElementById('editHarga').value = harga;
    document.getElementById('editQty').value = qty;
    document.getElementById('editTotal').value = total;
    document.getElementById('editKeterangan').value = keterangan || ''; 
    
    new bootstrap.Modal(document.getElementById('modalEdit')).show();
}

function calcEdit() {
    let harga = parseFloat(document.getElementById('editHarga').value) || 0;
    let qty = parseFloat(document.getElementById('editQty').value) || 0;
    document.getElementById('editTotal').value = harga * qty;
}

// Fungsi untuk Preview Image versi UI Modern
function previewModernImage(input, imgId, wrapperId, placeholderId) {
    const previewImg = document.getElementById(imgId);
    const wrapper = document.getElementById(wrapperId);
    const placeholder = document.getElementById(placeholderId);

    if (input.files && input.files[0]) {
        // Cek Ukuran File Maksimal 2MB
        if (input.files[0].size > 2 * 1024 * 1024) {
            Swal.fire('Gambar Terlalu Besar!', 'Ukuran bukti melebihi batas 2MB. Silakan pilih gambar yang lebih kecil.', 'warning');
            input.value = ''; // Reset input
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            wrapper.style.display = 'block'; 
            if(placeholder) {
                placeholder.style.display = 'none';
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</body>
</html>