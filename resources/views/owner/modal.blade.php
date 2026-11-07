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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        :root {
            --bs-orange: #fd7e14;
            --bs-orange-hover: #e37011;
        }
        body { font-family: 'Poppins', sans-serif; background-color: #fff7ed; }
        .btn-orange { background-color: var(--bs-orange); color: white; border: none; }
        .btn-orange:hover { background-color: var(--bs-orange-hover); color: white; }
        .btn-outline-orange { color: var(--bs-orange); border-color: var(--bs-orange); }
        .btn-outline-orange:hover, .btn-outline-orange.active { background-color: var(--bs-orange); color: white; }
        .text-orange { color: var(--bs-orange) !important; }
        .border-orange { border-color: var(--bs-orange) !important; }
        .form-control:focus, .form-select:focus {
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

        /* Summary Card */
        .summary-card {
            border-radius: 12px;
            transition: transform 0.2s;
            border-left: 5px solid var(--bs-orange) !important;
        }
        .summary-card:hover { transform: translateY(-3px); }

        /* Card total: full width di mobile, fixed 220px di desktop */
        .w-md-220 { width: 100%; }
        @media (min-width: 768px) {
            .w-md-220 { width: 220px; }
        }
    </style>
</head>
<body>

<div class="container-fluid py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('owner.dashboard') }}" class="btn btn-light border-warning-subtle text-secondary rounded-4 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
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

    {{-- ===== FILTER CARD ===== --}}
    <div class="card shadow-sm border-0 mb-4 rounded-3 border-top border-orange border-3">
        <div class="card-body">
            <form action="{{ route('owner.modal.index') }}" method="GET" class="row g-3 align-items-end" id="filterForm">
                <input type="hidden" name="status" value="{{ $status }}">

                <div class="col-md-5">
                    <label class="form-label fw-bold text-muted small mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control rounded-3" value="{{ $startDate }}">
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-bold text-muted small mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control rounded-3" value="{{ $endDate }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-orange w-100 fw-medium shadow-sm rounded-3">
                        <i class="bi bi-search me-1"></i> Terapkan
                    </button>
                </div>

                @php
                    $isDefault = !$quickFilter && !request('start_date');
                @endphp
                <div class="col-12 mt-3">
                    <div class="btn-group w-100 d-flex flex-wrap gap-2" role="group">
                        <button type="submit" name="quick_filter" value="1_hari"
                                class="btn flex-fill rounded-3 border fw-medium {{ ($quickFilter == '1_hari' || $isDefault) ? 'btn-orange text-white shadow-sm' : 'btn-outline-orange' }}">
                            1 Hari
                        </button>
                        <button type="submit" name="quick_filter" value="1_minggu"
                                class="btn flex-fill rounded-3 border fw-medium {{ $quickFilter == '1_minggu' ? 'btn-orange text-white shadow-sm' : 'btn-outline-orange' }}">
                            1 Minggu
                        </button>
                        <button type="submit" name="quick_filter" value="1_bulan"
                                class="btn flex-fill rounded-3 border fw-medium {{ $quickFilter == '1_bulan' ? 'btn-orange text-white shadow-sm' : 'btn-outline-orange' }}">
                            1 Bulan
                        </button>
                        <button type="submit" name="quick_filter" value="1_tahun"
                                class="btn flex-fill rounded-3 border fw-medium {{ $quickFilter == '1_tahun' ? 'btn-orange text-white shadow-sm' : 'btn-outline-orange' }}">
                            1 Tahun
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== CARD TOTAL MODAL + GRAFIK (1 BARIS) ===== --}}
    <div class="d-flex gap-3 align-items-stretch mb-4 flex-column flex-md-row">

        {{-- Card Total Modal --}}
        <div class="flex-shrink-0 w-md-220">
            <div class="card summary-card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center gap-2 py-4">
                    <div class="bg-success-subtle rounded-circle d-flex align-items-center justify-content-center mb-1" style="width: 44px; height: 44px;">
                        <i class="bi bi-wallet2 text-success fs-5"></i>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: .5px;">Total Modal Usaha</span>
                        <button type="button" class="btn-eye-card btn btn-link p-0 text-muted" data-key="modal_total" style="font-size:.85rem;" title="Sembunyikan/Tampilkan">
                            <i class="bi bi-eye-slash"></i>
                        </button>
                    </div>
                    <div class="fw-bold text-success lh-1 card-nominal" style="font-size: 1.35rem;"
                         data-original="Rp {{ number_format($totalModal, 0, ',', '.') }}">
                        Rp {{ number_format($totalModal, 0, ',', '.') }}
                    </div>
                    <small class="text-muted" style="font-size: 0.73rem;">
                        {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }}<br>
                        - {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}
                    </small>
                </div>
            </div>
        </div>

        {{-- Grafik Batang --}}
        @if(count($chartLabels) > 0)
        <div class="card shadow-sm border-0 rounded-3 flex-grow-1" style="min-width: 0;">
            <div class="card-header bg-white border-0 pt-3 pb-1">
                <h6 class="fw-bold text-dark m-0">
                    <i class="bi bi-bar-chart-line text-orange me-2"></i> Grafik Modal Usaha
                </h6>
            </div>
            <div class="card-body pt-1 pb-3 px-3">
                <canvas id="chartModal" style="display: block; width: 100%; height: 180px;"></canvas>
            </div>
        </div>
        @endif

    </div>

    <div class="row g-4">
        {{-- BAGIAN KIRI: Form Input --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white pt-3 pb-2 border-0">
                    <h6 class="fw-bold text-dark m-0"><i class="bi bi-plus-circle-fill text-orange me-2"></i> Input Modal Baru</h6>
                </div>
                <div class="card-body bg-light rounded-bottom">
                    <form action="{{ route('owner.modal.store') }}" method="POST">
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
                                <a href="{{ route('owner.modal.index', ['status' => 'save', 'start_date' => $startDate, 'end_date' => $endDate, 'quick_filter' => $quickFilter]) }}"
                                   class="btn btn-sm flex-grow-1 flex-sm-grow-0 {{ $status == 'save' ? 'btn-active' : 'btn-inactive' }}">
                                    <i class="bi bi-check-circle me-1"></i> Tersedia
                                </a>
                                <a href="{{ route('owner.modal.index', ['status' => 'delete', 'start_date' => $startDate, 'end_date' => $endDate, 'quick_filter' => $quickFilter]) }}"
                                   class="btn btn-sm flex-grow-1 flex-sm-grow-0 {{ $status == 'delete' ? 'btn-active' : 'btn-inactive' }}">
                                    <i class="bi bi-trash-fill me-1"></i> Terhapus
                                </a>
                            </div>

                            <div class="d-none d-sm-block border-end py-3"></div>

                            {{-- TOMBOL EXCEL --}}
                            <form action="{{ route('owner.modal.export') }}" method="GET" class="m-0 d-flex">
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
                                    <th class="py-3" width="20%">NOMINAL</th>
                                    <th class="py-3" width="35%">KETERANGAN</th>
                                    <th class="py-3" width="30%">TANGGAL</th>
                                    <th class="text-center py-3" width="10%">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0" id="tableBody">
                                @forelse($modals as $index => $m)
                                    <tr data-row-index="{{ $index }}">
                                        <td class="text-center text-muted fw-medium small">{{ $index + 1 }}</td>
                                        <td class="fw-bold text-success">
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
                                                <form action="{{ route('owner.modal.destroy', $m->id) }}" method="POST" class="m-0 form-trash">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light text-danger border shadow-sm">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('owner.modal.restore', $m->id) }}" method="POST" class="m-0 form-restore">
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

                {{-- FOOTER TOMBOL LIHAT SEMUA --}}
                <div id="tableFooterArea">
                    @if(count($modals) > 10)
                    <div class="card-footer bg-white border-top border-light text-center py-3">
                        <button type="button" id="btnShowAll" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-medium shadow-sm">
                            Lihat Semua Data ({{ count($modals) }}) <i class="bi bi-chevron-down ms-1"></i>
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ===== TOGGLE NOMINAL PER CARD (EYE) - persist via localStorage =====
(function() {
    const MASK    = '••••••';
    const PREFIX  = 'card_hidden_';

    function applyCard(btn, hidden) {
        const card    = btn.closest('.card-body');
        const nominal = card.querySelector('.card-nominal');
        const icon    = btn.querySelector('i');
        nominal.textContent = hidden ? MASK : nominal.dataset.original;
        icon.className = hidden ? 'bi bi-eye' : 'bi bi-eye-slash';
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-eye-card').forEach(function(btn) {
            const key    = PREFIX + (btn.dataset.key || '');
            const hidden = localStorage.getItem(key) === '1';
            applyCard(btn, hidden);

            btn.addEventListener('click', function() {
                const nowHidden = localStorage.getItem(key) === '1';
                const next      = !nowHidden;
                localStorage.setItem(key, next ? '1' : '0');
                applyCard(btn, next);
            });
        });
    });
})();

document.addEventListener("DOMContentLoaded", function() {

    // ===== GRAFIK MODAL =====
    @if(count($chartLabels) > 0)
    (function() {
        const ctx = document.getElementById('chartModal');
        if (!ctx) return;

        const labels = @json($chartLabels);
        const data   = @json($chartData);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Modal (Rp)',
                    data: data,
                    backgroundColor: 'rgba(253, 126, 20, 0.75)',
                    borderColor: '#fd7e14',
                    borderWidth: 1.5,
                    borderRadius: 5,
                    borderSkipped: false,
                    maxBarThickness: 48,   // batang tidak lebih lebar dari 48px meski data sedikit
                    barPercentage: 0.55,   // batang 55% dari slot kategori
                    categoryPercentage: 0.7,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => 'Rp ' + Number(ctx.raw).toLocaleString('id-ID')
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: v => 'Rp ' + Number(v).toLocaleString('id-ID'),
                            font: { size: 10 },
                            maxTicksLimit: 5,
                        },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        ticks: { font: { size: 11 } },
                        grid: { display: false }
                    }
                }
            }
        });
    })();
    @endif

    // ===== LIMIT TABEL 10 BARIS + TOMBOL LIHAT SEMUA =====
    function initTableLimit() {
        const MAX_ROWS   = 10;
        let isShowingAll = false;

        const tbody    = document.getElementById('tableBody');
        const footerEl = document.getElementById('tableFooterArea');
        if (!tbody) return;

        const rows = tbody.querySelectorAll('tr[data-row-index]');

        // Sembunyikan baris > 10
        rows.forEach(row => {
            const idx = parseInt(row.getAttribute('data-row-index'), 10);
            row.style.display = (idx < MAX_ROWS) ? '' : 'none';
        });

        // Pasang listener tombol - clone dulu agar tidak duplikat listener
        const btnOld = document.getElementById('btnShowAll');
        if (!btnOld) return; // data <= 10, tidak perlu tombol

        const btn = btnOld.cloneNode(true);
        btnOld.parentNode.replaceChild(btn, btnOld);

        btn.addEventListener('click', function () {
            isShowingAll = !isShowingAll;

            rows.forEach(row => {
                row.style.display = ''; // tampilkan semua
            });

            if (isShowingAll) {
                this.innerHTML = 'Sembunyikan <i class="bi bi-chevron-up ms-1"></i>';
            } else {
                // Sembunyikan lagi baris > 10
                rows.forEach(row => {
                    const idx = parseInt(row.getAttribute('data-row-index'), 10);
                    row.style.display = (idx < MAX_ROWS) ? '' : 'none';
                });
                this.innerHTML = 'Lihat Semua Data (' + rows.length + ') <i class="bi bi-chevron-down ms-1"></i>';
            }
        });
    }

    initTableLimit();

    // ===== AJAX TAB NAVIGASI =====
    document.body.addEventListener('click', function(e) {
        let targetLink = e.target.closest('.nav-status a');

        if (targetLink) {
            e.preventDefault();
            let url = targetLink.getAttribute('href');

            document.getElementById('tableBody').innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <div class="spinner-border text-warning mb-2" role="status"></div>
                        <div class="small">Memuat data...</div>
                    </td>
                </tr>
            `;

            document.querySelectorAll('.nav-status a').forEach(a => {
                a.classList.remove('btn-active');
                a.classList.add('btn-inactive');
            });
            targetLink.classList.remove('btn-inactive');
            targetLink.classList.add('btn-active');

            let statusVal = 'save';
            if (url.includes('?')) {
                const urlParams = new URLSearchParams(url.split('?')[1]);
                statusVal = urlParams.get('status') || 'save';
            }
            const statusInput = document.querySelector('input[name="status"]');
            if (statusInput) statusInput.value = statusVal;

            if (window.showLoading) window.showLoading();
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => {
                    if (!response.ok) throw new Error("Terjadi kesalahan server.");
                    return response.text();
                })
                .then(html => {
                    let doc = new DOMParser().parseFromString(html, 'text/html');

                    let newHeader = doc.getElementById('tableHeaderArea');
                    if (newHeader) document.getElementById('tableHeaderArea').innerHTML = newHeader.innerHTML;

                    let newTbody = doc.getElementById('tableBody');
                    if (newTbody) document.getElementById('tableBody').innerHTML = newTbody.innerHTML;

                    // Sinkronkan footer tombol dari response
                    let newFooter = doc.getElementById('tableFooterArea');
                    if (newFooter && document.getElementById('tableFooterArea')) {
                        document.getElementById('tableFooterArea').innerHTML = newFooter.innerHTML;
                    }

                    initTableLimit();
                    window.history.pushState({path: url}, '', url);
                    if (window.hideLoading) window.hideLoading();
                })
                .catch(error => {
                    if (window.hideLoading) window.hideLoading();
                    Swal.fire('Error', 'Gagal memuat data dari server.', 'error');
                    document.getElementById('tableBody').innerHTML = `
                        <tr><td colspan="5" class="text-center py-4 text-danger">Gagal memuat data.</td></tr>
                    `;
                });
        }
    });

    // ===== KONFIRMASI HAPUS & PULIHKAN =====
    document.addEventListener('submit', function(e) {

        if (e.target.closest('.form-trash')) {
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

        if (e.target.closest('.form-restore')) {
            e.preventDefault();
            let form = e.target.closest('.form-restore');
            Swal.fire({
                title: 'Pulihkan Modal?',
                text: "Data ini akan dikembalikan ke tab Tersedia.",
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

    // ===== HELPER AJAX SUBMIT =====
    function submitFormAjax(form, successMessage) {
        let formData = new FormData(form);
        if (window.showLoading) window.showLoading();
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => {
            if (!response.ok) throw new Error("Terjadi kesalahan sistem");
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

            let newHeader = doc.getElementById('tableHeaderArea');
            if (newHeader) document.getElementById('tableHeaderArea').innerHTML = newHeader.innerHTML;

            let newTbody = doc.getElementById('tableBody');
            if (newTbody) document.getElementById('tableBody').innerHTML = newTbody.innerHTML;

            // Sinkronkan footer tombol dari response
            let newFooter = doc.getElementById('tableFooterArea');
            if (newFooter && document.getElementById('tableFooterArea')) {
                document.getElementById('tableFooterArea').innerHTML = newFooter.innerHTML;
            }

            initTableLimit();
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
