<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Keuangan - Flavory.id</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('layouts.favicon')
    <script src="{{ asset('js/loading.js') }}"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        :root {
            --bs-orange: #fd7e14;
            --bs-orange-hover: #e37011;
        }

        body{
            background-color: #fff7ed;
            font-family: 'Poppins', sans-serif;
        }
        .btn-orange {
            background-color: var(--bs-orange);
            color: white;
            border: none;
        }
        .btn-orange:hover {
            background-color: var(--bs-orange-hover);
            color: white;
        }
        .btn-outline-orange {
            color: var(--bs-orange);
            border-color: var(--bs-orange);
        }
        .btn-outline-orange:hover, .btn-outline-orange.active {
            background-color: var(--bs-orange);
            color: white;
        }
        .text-orange {
            color: var(--bs-orange) !important;
        }
        /* Hide kolom ketika mode sederhana aktif */
        .mode-sederhana .col-lengkap {
            display: none !important;
        }
        
        /* STYLING BUKTI BAYAR (FITUR BARU) */
        .bukti-img-card { 
            object-fit: cover; 
            height: 160px; 
            transition: transform 0.3s ease; 
            cursor: pointer; 
            background-color: #f8f9fa;
        }
        .bukti-img-card:hover { 
            transform: scale(1.08); 
        }

        /* SUMMARY CARD STYLING (FITUR BARU) */
        .summary-card { border-radius: 12px; transition: transform 0.2s; border-left: 5px solid; }
        .summary-card:hover { transform: translateY(-3px); }
        .border-modal { border-left-color: #6c757d; }
        .border-omzet { border-left-color: #6f42c1; }
        .border-untung { border-left-color: #28a745; }
        .border-rugi { border-left-color: #dc3545; }
        .border-pengeluaran { border-left-color: #ffc107; }
    </style>
</head>
<body>

<div class="container-fluid py-4">
    
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('kasir.dashboard') }}" class="btn btn-light border-warning-subtle text-secondary rounded-4 d-flex align-items-center justify-content-center hover-scale" style="width: 48px; height: 48px;">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="h4 mb-0 fw-black text-uppercase" style="font-weight: 900; letter-spacing: -0.5px;">Laporan Bisnis</h1>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('kasir.laporan.index') }}" method="GET" class="row g-3 align-items-end" id="filterForm">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Dari Tanggal</label>
                    <input type="date" name="start_date" id="start_date" class="form-control rounded-3" value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="end_date" class="form-control rounded-3" value="{{ $endDate }}">
                </div>
                
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-orange w-100 fw-bold rounded-3 shadow-sm">
                        <i class="bi bi-search me-1"></i> Terapkan
                    </button>
                    <a href="{{ route('kasir.laporan.index') }}" class="btn btn-light fw-bold rounded-3 border shadow-sm px-3 d-flex align-items-center" title="Reset ke Default">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>

                @php
                    $qf = request('quick_filter');
                    // Karena di Laporan Bisnis defaultnya adalah "Hari Ini", kita set isDefault jika tidak ada filter
                    $isDefault = !$qf && !request('start_date'); 
                @endphp

                <div class="col-12 mt-3">
                    <div class="btn-group w-100 d-flex flex-wrap gap-2" role="group">
                        <button type="submit" name="quick_filter" value="1_hari" 
                                class="btn flex-fill rounded-3 border fw-medium {{ ($qf == '1_hari' || $isDefault) ? 'btn-orange text-white shadow-sm' : 'btn-outline-orange' }}">
                            1 Hari
                        </button>
                        
                        <button type="submit" name="quick_filter" value="1_minggu" 
                                class="btn flex-fill rounded-3 border fw-medium {{ $qf == '1_minggu' ? 'btn-orange text-white shadow-sm' : 'btn-outline-orange' }}">
                            1 Minggu
                        </button>
                        
                        <button type="submit" name="quick_filter" value="1_bulan" 
                                class="btn flex-fill rounded-3 border fw-medium {{ $qf == '1_bulan' ? 'btn-orange text-white shadow-sm' : 'btn-outline-orange' }}">
                            1 Bulan
                        </button>
                        
                        <button type="submit" name="quick_filter" value="1_tahun" 
                                class="btn flex-fill rounded-3 border fw-medium {{ $qf == '1_tahun' ? 'btn-orange text-white shadow-sm' : 'btn-outline-orange' }}">
                            1 Tahun
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card summary-card border-modal shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted small fw-bold mb-1"><i class="bi bi-wallet2 text-secondary"></i> TOTAL MODAL</div>
                    <h5 class="fw-bold mb-0 text-dark">Rp {{ number_format($totalModal ?? 0, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card summary-card border-omzet shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted small fw-bold mb-1"><i class="bi bi-graph-up text-purple" style="color:#6f42c1;"></i> TOTAL OMZET</div>
                    <h5 class="fw-bold mb-0 text-dark">Rp {{ number_format($totalOmzet ?? 0, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card summary-card border-untung shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted small fw-bold mb-1"><i class="bi bi-emoji-smile text-success"></i> TOTAL UNTUNG</div>
                    <h5 class="fw-bold mb-0 text-success">Rp {{ number_format($totalUntung ?? 0, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-6 col-lg-2">
            <div class="card summary-card border-rugi shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted small fw-bold mb-1"><i class="bi bi-emoji-frown text-danger"></i> TOTAL RUGI</div>
                    <h5 class="fw-bold mb-0 text-danger">Rp {{ number_format($totalRugi ?? 0, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card summary-card border-pengeluaran shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted small fw-bold mb-1"><i class="bi bi-cart-dash text-warning"></i> TOTAL PENGELUARAN</div>
                    <h5 class="fw-bold mb-0 text-dark">Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <button type="button" class="btn btn-outline-orange w-100 fw-bold py-2 shadow-sm rounded-3" data-bs-toggle="modal" data-bs-target="#modalChartKeuangan">
            <i class="bi bi-bar-chart-line-fill me-2"></i> LIHAT CHART DATA KEUANGAN
        </button>
    </div>

    <div class="row mb-4">
        <div class="col-lg-8 mb-3 mb-lg-0">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold text-orange">Top 10 Menu Paling Laris</h5>
                </div>
                <div class="card-body">
                    <div id="topMenuChart"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold text-danger">5 Menu Paling Tidak Laris</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($leastMenus as $menu)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <h6 class="mb-0">{{ $menu->nama_menu }}</h6>
                                    <small class="text-muted">
                                        Terakhir: {{ $menu->last_sold_at ? \Carbon\Carbon::parse($menu->last_sold_at)->translatedFormat('d M Y') : 'Belum pernah terjual' }}
                                    </small>
                                </div>
                                <i class="bi bi-arrow-down-right-circle text-danger fs-5"></i>
                            </li>
                        @empty
                            <li class="list-group-item text-muted text-center">Data tidak ditemukan.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-center align-items-center mb-4 gap-3">
       <a href="{{ route('kasir.modal.index') }}" class="btn btn-success px-4 py-2 shadow-sm fw-bold rounded-pill">
            <i class="bi bi-plus-circle-fill me-2"></i> MODAL USAHA
        </a>
        <a href="{{ route('kasir.pengeluaran.index') }}" class="btn btn-danger px-4 py-2 shadow-sm fw-bold rounded-pill">
            <i class="bi bi-dash-circle-fill me-2"></i> PENGELUARAN
        </a>
    </div>

    <div class="card shadow-sm border-0 border-top border-orange border-3 rounded-3">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h5 class="fw-bold text-dark m-0">
                <i class="bi bi-receipt text-orange me-2"></i>Detail Laporan Penjualan
            </h5>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-dark text-white btn-sm fw-medium shadow-sm" data-bs-toggle="modal" data-bs-target="#modalFolderBukti">
                    <i class="bi bi-folder-fill me-1"></i> FOLDER BUKTI
                </button>
                <button type="button" id="btnToggleMode" class="btn btn-light border-secondary text-secondary btn-sm fw-medium shadow-sm">
                    <i class="bi bi-view-list me-1"></i> SEDERHANAKAN
                </button>
                <form action="{{ route('kasir.laporan.export') }}" method="GET" target="_blank" class="m-0">
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <input type="hidden" name="mode" id="exportMode" value="lengkap">
                    <button type="submit" class="btn btn-success btn-sm fw-medium shadow-sm">
                        <i class="bi bi-file-earmark-excel me-1"></i> EXCEL
                    </button>
                </form>
            </div>
        </div>
    
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="salesTable">
                    <thead class="table-light text-muted" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                        <tr>
                            <th class="col-lengkap text-center py-3" width="5%">#</th>
                            <th class="col-lengkap py-3" width="15%">PELANGGAN</th>
                            <th class="py-3" width="20%">PESANAN</th> 
                            <th class="col-lengkap text-end py-3" width="10%">DISKON</th> 
                            <th class="col-lengkap text-end py-3" width="12%">TOTAL</th>
                            <th class="col-lengkap text-end py-3" width="10%">BAYAR</th> 
                            <th class="col-lengkap text-end py-3" width="10%">KEMBALI</th>
                            <th class="col-lengkap text-center py-3" width="8%">BUKTI</th>
                            <th class="col-lengkap text-center py-3" width="9%">METODE</th>
                            <th class="py-3" width="9%">TANGGAL</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($orders as $index => $order)
                            <tr class="row-lengkap" data-order-index="{{ $index }}">
                                <td class="col-lengkap text-center text-muted fw-medium">{{ $index + 1 }}</td>
                                <td class="col-lengkap">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-secondary me-2 shadow-sm" style="width: 32px; height: 32px;">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <span class="fw-semibold text-dark">{{ $order->nama_pelanggan ?? 'Walk-in' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1 my-1">
                                        @foreach($order->orderDetails as $detail)
                                            <div class="d-flex justify-content-between align-items-start border-bottom pb-1 mb-1 border-light">
                                                <div>
                                                    <span class="fw-medium text-dark">{{ $detail->menu->nama_menu }}</span>
                                                    @if($detail->variant) 
                                                        <br><small class="text-muted"><i class="bi bi-arrow-return-right"></i> {{ $detail->variant }}</small> 
                                                    @endif
                                                </div>
                                                <span class="badge bg-light text-dark border px-2 py-1 shadow-sm">x{{ $detail->qty }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="col-lengkap text-end fw-medium text-danger">
                                    @if(isset($order->nominal_diskon) && $order->nominal_diskon > 0)
                                        @if($order->jenis_diskon == '%')
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                                {{ (float) $order->nominal_diskon }}%
                                            </span>
                                        @else
                                            Rp {{ number_format($order->nominal_diskon, 0, ',', '.') }}
                                        @endif
                                    @else
                                        <span class="text-muted" style="font-size: 0.8rem;">0</span>
                                    @endif
                                </td>
                                <td class="col-lengkap text-end fw-bold text-orange fs-6">
                                    Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                </td>
                                <td class="col-lengkap text-end fw-medium text-secondary">
                                    Rp {{ number_format($order->uang_bayar, 0, ',', '.') }}
                                </td>
                                <td class="col-lengkap text-end fw-medium text-success">
                                    Rp {{ number_format($order->kembalian, 0, ',', '.') }}
                                </td>
                                <td class="col-lengkap text-center">
                                    @if($order->bukti_bayar)
                                        <button type="button" class="btn btn-sm btn-outline-dark rounded-circle shadow-sm" onclick="showBukti('{{ route('private.bukti_bayar', $order->bukti_bayar) }}')" title="Lihat Bukti">
                                            <i class="bi bi-image"></i>
                                        </button>
                                    @else
                                        <span class="text-muted fw-bold">-</span>
                                    @endif
                                </td>
                                <td class="col-lengkap text-center">
                                    @if(strtolower($order->metode_bayar) == 'cash')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill"><i class="bi bi-cash me-1"></i> Cash</span>
                                    @else
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill"><i class="bi bi-credit-card me-1"></i> {{ strtoupper($order->metode_bayar) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-medium" style="font-size: 0.9rem;">
                                            <i class="bi bi-calendar-event text-muted me-1"></i> {{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d M Y') }}
                                        </span>
                                        <span class="text-muted" style="font-size: 0.8rem;">
                                            <i class="bi bi-clock me-1"></i> {{ \Carbon\Carbon::parse($order->created_at)->format('H:i') }} WIB
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            
                            @foreach($order->orderDetails as $detail)
                            <tr class="row-sederhana bg-light" style="display: none;" data-order-index="{{ $index }}">
                                <td class="fw-medium text-dark ps-4">
                                    {{ $detail->menu->nama_menu }}
                                    @if($detail->variant) <small class="text-muted ms-1">({{ $detail->variant }})</small> @endif
                                    <span class="badge bg-white text-dark border ms-2">x{{ $detail->qty }}</span>
                                </td>
                                <td>
                                    <span class="text-muted" style="font-size: 0.9rem;">
                                        {{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d M Y, H:i') }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-5"> <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-inbox fs-1 text-light mb-2"></i>
                                        <span>Tidak ada data penjualan pada periode ini.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if(count($orders) > 10)
        <div class="card-footer bg-white border-top border-light text-center py-3">
            <button type="button" id="btnShowAll" class="btn btn-outline-orange rounded-pill px-4 py-2 fw-medium shadow-sm transition-all">
                LIHAT SEMUA ({{ count($orders) }} Data) <i class="bi bi-chevron-down ms-1"></i>
            </button>
        </div>
        @endif
    </div>
</div>

<div class="modal fade" id="modalChartKeuangan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-bottom-0">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-bar-chart-steps text-orange me-2"></i> Grafik Laporan Keuangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex flex-wrap gap-2 mb-4 justify-content-center" id="chartFilters">
                    <button class="btn btn-sm btn-outline-secondary btn-chart-filter" data-filter="1_hari">1 Hari</button>
                    <button class="btn btn-sm btn-outline-secondary btn-chart-filter" data-filter="1_minggu">1 Minggu</button>
                    <button class="btn btn-sm btn-orange text-white btn-chart-filter active" data-filter="1_bulan">1 Bulan</button>
                    <button class="btn btn-sm btn-outline-secondary btn-chart-filter" data-filter="1_tahun">1 Tahun</button>
                    <div class="input-group input-group-sm w-auto">
                        <input type="date" id="customStart" class="form-control">
                        <span class="input-group-text bg-light">to</span>
                        <input type="date" id="customEnd" class="form-control">
                        <button class="btn btn-secondary btn-chart-filter" data-filter="custom"><i class="bi bi-search"></i></button>
                    </div>
                </div>

                <div style="position: relative; height: 50vh; width: 100%;">
                    <canvas id="financeLineChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFolderBukti" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border border-dark border-3 shadow">
            <div class="modal-header bg-light border-bottom-0">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="bi bi-folder-fill text-warning me-2"></i> Folder Bukti Pembayaran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="row g-4">
                    @forelse($ordersWithBukti as $order)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                                <img src="{{ route('private.bukti_bayar', $order->bukti_bayar) }}" class="card-img-top bukti-img-card" loading="lazy" onclick="showBukti('{{ route('private.bukti_bayar', $order->bukti_bayar) }}')" alt="Bukti Pembayaran">
                                <div class="card-body p-3">
                                    <h6 class="fw-bold mb-1 text-orange">#{{ $order->nomor_pesanan }}</h6>
                                    <div class="small fw-medium text-dark mb-1">
                                        <i class="bi bi-person-fill text-secondary"></i> {{ $order->nama_pelanggan ?? 'Walk-in' }}
                                    </div>
                                    <div class="small text-muted" style="font-size: 0.75rem;">
                                        <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d M Y, H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-folder-x fs-1 text-muted mb-3 d-block" style="font-size: 3rem;"></i>
                            <h6 class="text-muted fw-normal">Tidak ada bukti pembayaran pada periode tanggal yang dipilih.</h6>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPreviewBukti" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 bg-transparent shadow-none">
            <div class="modal-header border-0 pb-0 justify-content-end">
                <button type="button" class="btn-close btn-close-white shadow" data-bs-dismiss="modal" aria-label="Close" style="background-color: white; opacity: 1; border-radius: 50%; padding: 0.5rem;"></button>
            </div>
            <div class="modal-body text-center p-0 mt-2">
                <img id="previewImageSrc" src="" class="img-fluid rounded-3 shadow-lg border border-3 border-white" alt="Preview Bukti Pembayaran" style="max-height: 80vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function showBukti(imageUrl) {
        document.getElementById('previewImageSrc').src = imageUrl;
        var previewModal = new bootstrap.Modal(document.getElementById('modalPreviewBukti'));
        previewModal.show();
    }
    document.addEventListener("DOMContentLoaded", function() {

        // ==========================================
        // SCRIPT LAMA: SETUP CHART MENU PALING LAKU
        // ==========================================
        const topMenusData = @json($topMenus);
        
        const categories = topMenusData.map(item => item.nama_menu);
        const seriesData = topMenusData.map(item => parseInt(item.total_qty));

        const options = {
            series: [{
                name: 'Terjual',
                data: seriesData
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                    distributed: true,
                }
            },
            colors: ['#fd7e14', '#ff9f43', '#ffaf5a', '#ffbe71', '#ffce88'], // Gradasi oranye
            dataLabels: {
                enabled: true
            },
            xaxis: {
                categories: categories,
                title: { text: 'Jumlah Terjual' }
            },
            legend: { show: false }
        };

        const chart = new ApexCharts(document.querySelector("#topMenuChart"), options);
        chart.render();

        // ==========================================
        // SCRIPT LAMA: FITUR TABEL (MODE & LIMIT)
        // ==========================================
        const btnToggleMode = document.getElementById('btnToggleMode');
        const btnShowAll = document.getElementById('btnShowAll');
        const exportModeInput = document.getElementById('exportMode');
        
        const colsLengkap = document.querySelectorAll('.col-lengkap');
        const rowsLengkap = document.querySelectorAll('.row-lengkap');
        const rowsSederhana = document.querySelectorAll('.row-sederhana');

        let isSimpleMode = false;
        let isShowingAll = false;
        const maxRows = 10; 

        function updateTableVisibility() {
            if (isSimpleMode) {
                colsLengkap.forEach(el => el.style.display = 'none');
            } else {
                colsLengkap.forEach(el => el.style.display = '');
            }

            rowsLengkap.forEach((row) => {
                const orderIndex = parseInt(row.getAttribute('data-order-index'));
                const isWithinLimit = isShowingAll || orderIndex < maxRows;
                if (isSimpleMode) {
                    row.style.display = 'none'; 
                } else {
                    row.style.display = isWithinLimit ? '' : 'none'; 
                }
            });

            rowsSederhana.forEach((row) => {
                const orderIndex = parseInt(row.getAttribute('data-order-index'));
                const isWithinLimit = isShowingAll || orderIndex < maxRows;
                if (isSimpleMode) {
                    row.style.display = isWithinLimit ? 'table-row' : 'none'; 
                } else {
                    row.style.display = 'none'; 
                }
            });
        }

        updateTableVisibility();

        if (btnToggleMode) {
            btnToggleMode.addEventListener('click', function() {
                isSimpleMode = !isSimpleMode;
                if (isSimpleMode) {
                    this.innerHTML = '<i class="bi bi-grid-3x3-gap me-1"></i> PERLENGKAP';
                    this.classList.replace('btn-light', 'btn-orange');
                    this.classList.replace('text-secondary', 'text-white');
                    exportModeInput.value = 'sederhana';
                } else {
                    this.innerHTML = '<i class="bi bi-view-list me-1"></i> SEDERHANAKAN';
                    this.classList.replace('btn-orange', 'btn-light');
                    this.classList.replace('text-white', 'text-secondary');
                    exportModeInput.value = 'lengkap';
                }
                updateTableVisibility(); 
            });
        }

        if (btnShowAll) {
            btnShowAll.addEventListener('click', function() {
                isShowingAll = !isShowingAll;
                if (isShowingAll) {
                    this.innerHTML = 'SEMBUNYIKAN <i class="bi bi-chevron-up ms-1"></i>';
                } else {
                    this.innerHTML = 'LIHAT SEMUA (' + rowsLengkap.length + ' Data) <i class="bi bi-chevron-down ms-1"></i>';
                }
                updateTableVisibility(); 
            });
        }

        // ==========================================
        // FITUR BARU: SCRIPT LOGIKA CHART KEUANGAN
        // ==========================================
        let financeChart = null;
        const ctx = document.getElementById('financeLineChart').getContext('2d');

        function loadChartData(filterMode, customStart = '', customEnd = '') {
            let url = `/kasir/laporan-bisnis/chart?filter=${filterMode}&start_date=${customStart}&end_date=${customEnd}`;
            
            if (window.showLoading) window.showLoading();
            fetch(url, { headers: { 'Accept': 'application/json' } })
                .then(response => response.json())
                .then(data => {
                    if(financeChart) financeChart.destroy(); 
                    
                    financeChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: data.datasets
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: { mode: 'index', intersect: false },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: { usePointStyle: true, boxWidth: 8, font: { family: 'Poppins' } }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label) { label += ': '; }
                                            if (context.parsed.y !== null) {
                                                label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                            }
                                            return label;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: { beginAtZero: true, ticks: { callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); } } }
                            }
                        }
                    });
                    if (window.hideLoading) window.hideLoading();
                })
                .catch(err => {
                    if (window.hideLoading) window.hideLoading();
                    console.error('Error fetching chart data:', err);
                });
        }

        // Load Default 1 Bulan saat modal dibuka pertama kali
        const modalChartEl = document.getElementById('modalChartKeuangan');
        modalChartEl.addEventListener('shown.bs.modal', function () {
            if(!financeChart) loadChartData('1_bulan');
        });

        // Listener Filter Tombol Chart
        const filterBtns = document.querySelectorAll('.btn-chart-filter');
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterBtns.forEach(b => {
                    b.classList.remove('btn-orange', 'text-white', 'active');
                    b.classList.add('btn-outline-secondary');
                });
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-orange', 'text-white', 'active');

                let filterVal = this.getAttribute('data-filter');
                if (filterVal === 'custom') {
                    let s = document.getElementById('customStart').value;
                    let e = document.getElementById('customEnd').value;
                    if(s && e) loadChartData(filterVal, s, e);
                    else alert('Silakan isi rentang tanggal custom terlebih dahulu.');
                } else {
                    loadChartData(filterVal);
                }
            });
        });
    });
</script>
</body>
</html>