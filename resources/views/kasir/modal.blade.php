<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Modal Usaha - Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('layouts.favicon')
    <script src="{{ asset('js/loading.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --bs-orange: #fd7e14;
            --bs-orange-hover: #e37011;
        }
        body { font-family: 'Poppins', sans-serif; background-color: #fff7ed; }
        .btn-orange { background-color: var(--bs-orange); color: white; border: none; }
        .btn-orange:hover { background-color: var(--bs-orange-hover); color: white; }
        .text-orange { color: var(--bs-orange) !important; }
        .border-orange { border-color: var(--bs-orange) !important; }
        .form-control:focus {
            border-color: var(--bs-orange);
            box-shadow: 0 0 0 0.25rem rgba(253, 126, 20, 0.25);
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
</head>
<body>

<div class="container-fluid py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('kasir.laporan.index') }}" class="btn btn-light border-warning-subtle text-secondary rounded-4 d-flex align-items-center justify-content-center hover-scale" style="width: 48px; height: 48px;">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="h4 mb-0 fw-black text-uppercase" style="font-weight: 900; letter-spacing: -0.5px;">Modal Usaha</h1>
    </div>

    @if(session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session("success") }}',
                    timer: 2500,
                    showConfirmButton: false
                });
            });
        </script>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <ul class="mb-0 px-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- BAGIAN KIRI: Form Input & Filter --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4 rounded-3 border-top border-orange border-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="bi bi-funnel text-muted me-1"></i> Filter Tanggal</h6>
                    <form action="{{ route('kasir.modal.index') }}" method="GET">
                        <input type="hidden" name="status" value="{{ $status }}">
                        
                        <div class="mb-2">
                            <label class="form-label small text-muted">Mulai Tanggal</label>
                            <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Sampai Tanggal</label>
                            <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}">
                        </div>
                        <button type="submit" class="btn btn-dark btn-sm w-100 fw-medium shadow-sm">
                            <i class="bi bi-search me-1"></i> Tampilkan Data
                        </button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white pt-3 pb-2 border-0">
                    <h6 class="fw-bold text-dark m-0"><i class="bi bi-plus-circle-fill text-orange me-2"></i> Input Modal Baru</h6>
                </div>
                <div class="card-body bg-light rounded-bottom">
                    <form action="{{ route('kasir.modal.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Nominal (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0 text-muted">Rp</span>
                                <input type="number" name="nominal" class="form-control border-start-0 ps-0" placeholder="0" required min="1">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold small">Keterangan (Opsional)</label>
                            <textarea name="keterangan" class="form-control form-control-sm" rows="2" placeholder="Tulis catatan jika perlu..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-orange btn-sm w-100 fw-bold shadow-sm py-2">
                            <i class="bi bi-floppy me-1"></i> Simpan Modal
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- BAGIAN KANAN: Tabel Data --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 border-top border-dark border-3 rounded-3 h-100">
                
                {{-- HEADER TABEL: Judul, Navigasi Tab, Tombol Excel --}}
                {{-- Diberikan ID khusus agar mudah di-replace oleh AJAX --}}
                <div id="tableHeaderArea" class="card-header bg-white border-bottom-0 pt-4 pb-3">
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                        
                        {{-- Judul & Badge --}}
                        <h5 class="fw-bold text-dark m-0 d-flex align-items-center flex-wrap gap-2">
                            <span><i class="bi bi-table text-dark me-1"></i> Riwayat Modal Usaha</span>
                            @if($status == 'save')
                                <span class="badge bg-success-subtle text-success border border-success border-opacity-25 px-2 py-1">Tersedia</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-25 px-2 py-1">Terhapus</span>
                            @endif
                        </h5>
                        
                        {{-- Navigasi Tab & Excel --}}
                        <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-2 gap-sm-3">
                            
                            {{-- TAB NAVIGASI STATUS --}}
                            <div class="nav-status d-flex gap-2 flex-grow-1 flex-sm-grow-0">
                                <a href="{{ route('kasir.modal.index', ['status' => 'save', 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                                   class="btn btn-sm flex-grow-1 flex-sm-grow-0 {{ $status == 'save' ? 'btn-active' : 'btn-inactive' }}">
                                    <i class="bi bi-check-circle me-1"></i> Tersedia
                                </a>
                                <a href="{{ route('kasir.modal.index', ['status' => 'delete', 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                                   class="btn btn-sm flex-grow-1 flex-sm-grow-0 {{ $status == 'delete' ? 'btn-active' : 'btn-inactive' }}">
                                    <i class="bi bi-trash-fill me-1"></i> Terhapus
                                </a>
                            </div>
                            
                            {{-- Garis Pemisah (Hanya di layar besar) --}}
                            <div class="d-none d-sm-block border-end py-3"></div> 
                            
                            {{-- TOMBOL EXCEL --}}
                            <form action="{{ route('kasir.modal.export') }}" method="GET" class="m-0 d-flex">
                                <input type="hidden" name="start_date" value="{{ $startDate }}">
                                <input type="hidden" name="end_date" value="{{ $endDate }}">
                                <button type="submit" class="btn btn-success btn-sm fw-medium shadow-sm w-100 px-3">
                                    <i class="bi bi-file-earmark-excel me-1"></i> EXCEL
                                </button>
                            </form>
                            
                        </div>
                    </div>
                </div>
                
                {{-- BODY TABEL --}}
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted" style="font-size: 0.85rem;">
                                <tr>
                                    <th class="text-center py-3" width="5%">#</th>
                                    <th class="py-3 text-end" width="20%">NOMINAL</th>
                                    <th class="py-3" width="35%">KETERANGAN</th>
                                    <th class="py-3" width="30%">TANGGAL</th>
                                    <th class="text-center py-3" width="10%">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0" id="tableBody">
                                @forelse($modals as $index => $m)
                                    <tr>
                                        <td class="text-center text-muted fw-medium small">{{ $index + 1 }}</td>
                                        <td class="text-end fw-bold text-success">
                                            Rp {{ number_format((float)$m->nominal, 0, ',', '.') }}
                                        </td>
                                        <td class="small text-secondary">
                                            {{ $m->keterangan ?? '-' }}
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="text-dark fw-medium" style="font-size: 0.85rem;">
                                                    <i class="bi bi-calendar-event text-muted me-1"></i> 
                                                    {{ \Carbon\Carbon::parse($m->created_at)->timezone('Asia/Jakarta')->translatedFormat('d M Y') }}
                                                </span>
                                                <span class="text-muted" style="font-size: 0.75rem;">
                                                    <i class="bi bi-clock me-1"></i> 
                                                    {{ \Carbon\Carbon::parse($m->created_at)->timezone('Asia/Jakarta')->format('H:i') }} WIB
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if($status == 'save')
                                                <form action="{{ route('kasir.modal.destroy', $m->id) }}" method="POST" class="m-0 form-trash">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light text-danger border shadow-sm">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('kasir.modal.restore', $m->id) }}" method="POST" class="m-0 form-restore">
                                                    @csrf 
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success border shadow-sm fw-bold">
                                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Pulihkan
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                            Belum ada data modal pada rentang tanggal ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        // 1. AJAX Untuk Navigasi Tab Pindah Tanpa Reload
        // Kita gunakan document.body.addEventListener agar delegasi event berfungsi meski tombol link di-reload via AJAX
        document.body.addEventListener('click', function(e) {
            // Cek apakah yang diklik adalah link tab navigasi
            let targetLink = e.target.closest('.nav-status a');
            
            if (targetLink) {
                e.preventDefault();
                let url = targetLink.getAttribute('href');

                // Tampilkan efek loading pada area tabel
                document.getElementById('tableBody').innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <div class="spinner-border text-orange mb-2" role="status"></div>
                            <div class="small">Memuat data...</div>
                        </td>
                    </tr>
                `;

                // Update Visual Tombol Tab secara manual agar langsung terlihat perubahannya
                document.querySelectorAll('.nav-status a').forEach(a => {
                    a.classList.remove('btn-active');
                    a.classList.add('btn-inactive');
                });
                targetLink.classList.remove('btn-inactive');
                targetLink.classList.add('btn-active');

                // Sinkronisasikan Status ke Form Filter Input Hidden agar filter tetap pada tab yg sama
                let statusVal = 'save'; // Default
                if(url.includes('?')) {
                    const urlParams = new URLSearchParams(url.split('?')[1]);
                    statusVal = urlParams.get('status') || 'save';
                }
                
                const statusInput = document.querySelector('input[name="status"]');
                if(statusInput) statusInput.value = statusVal;

                if (window.showLoading) window.showLoading();
                // Request ke Controller Menggunakan Fetch API
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(response => {
                        if (!response.ok) throw new Error("Terjadi kesalahan server.");
                        return response.text();
                    })
                    .then(html => {
                        let doc = new DOMParser().parseFromString(html, 'text/html');
                        
                        // Perbarui seluruh Header Tabel (Judul + Tombol)
                        let newHeader = doc.getElementById('tableHeaderArea');
                        if(newHeader) document.getElementById('tableHeaderArea').innerHTML = newHeader.innerHTML;

                        // Perbarui isi Data Tabel
                        let newTbody = doc.getElementById('tableBody');
                        if(newTbody) {
                            document.getElementById('tableBody').innerHTML = newTbody.innerHTML;
                        } else {
                            console.error("Gagal menemukan tbody dari respon HTML.");
                            Swal.fire('Error', 'Format data tidak valid dari server.', 'error');
                        }

                        // Update URL di browser
                        window.history.pushState({path: url}, '', url);
                        if (window.hideLoading) window.hideLoading();
                    })
                    .catch(error => {
                        console.error(error);
                        if (window.hideLoading) window.hideLoading();
                        Swal.fire('Error', 'Gagal memuat data dari server. Periksa koneksi Anda.', 'error');
                        document.getElementById('tableBody').innerHTML = `
                            <tr><td colspan="5" class="text-center py-4 text-danger">Gagal memuat data.</td></tr>
                        `;
                    });
            }
        });

        // 2. Event Delegation untuk aksi Form Delete dan Restore
        document.addEventListener('submit', function(e) {
            
            // Logika Tab Tersedia -> Klik Hapus
            if(e.target.closest('.form-trash')) {
                e.preventDefault();
                let form = e.target.closest('.form-trash');
                Swal.fire({
                    title: 'Hapus Modal?',
                    text: "Data ini akan disembunyikan dan dipindahkan ke tab Data Terhapus.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#fd7e14',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Pindahkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitFormAjax(form, 'Data berhasil dipindahkan ke Terhapus.');
                    }
                });
            }

            // Logika Tab Terhapus -> Klik Pulihkan
            if(e.target.closest('.form-restore')) {
                e.preventDefault();
                let form = e.target.closest('.form-restore');
                Swal.fire({
                    title: 'Pulihkan Modal?',
                    text: "Data ini akan dikembalikan dan bisa dilihat pada tab utama.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Pulihkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitFormAjax(form, 'Data berhasil dipulihkan.');
                    }
                });
            }
        });

        // 3. Fungsi Reusable untuk Submit AJAX & Mengganti Komponen HTML
        function submitFormAjax(form, successMessage) {
            let formData = new FormData(form);
            if (window.showLoading) window.showLoading();
            fetch(form.action, {
                method: 'POST', 
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => {
                if(!response.ok) throw new Error("Terjadi kesalahan sistem");
                return response.text();
            })
            .then(html => {
                if (window.hideLoading) window.hideLoading();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: successMessage,
                    timer: 2000,
                    showConfirmButton: false
                });

                let doc = new DOMParser().parseFromString(html, 'text/html');
                
                // Replace Header Area
                let newHeader = doc.getElementById('tableHeaderArea');
                if(newHeader) document.getElementById('tableHeaderArea').innerHTML = newHeader.innerHTML;

                // Replace Tbody
                let newTbody = doc.getElementById('tableBody');
                if(newTbody) {
                    document.getElementById('tableBody').innerHTML = newTbody.innerHTML;
                }
            })
            .catch(error => {
                if (window.hideLoading) window.hideLoading();
                Swal.fire('Error', 'Terjadi kesalahan sistem, silahkan coba lagi.', 'error');
            });
        }
    });
</script>
</body>
</html>