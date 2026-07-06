{{-- resources/views/admin/detail-aktivitas.blade.php --}}
@extends('admin.layouts.app')

@section('content')
<style>
    :root {
        --bg-dark: #0B1120;
        --accent-cyan: #00E5FF;
        --accent-blue: #3B82F6;
        --accent-warning: #F59E0B;
        --accent-danger: #EF4444;
        --accent-success: #10B981;
        --bg-card: rgba(15, 23, 42, 0.7);
    }

    .cyber-grid-bg {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
        z-index: -3; background-color: var(--bg-dark);
        background-image: linear-gradient(rgba(0, 229, 255, 0.05) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(0, 229, 255, 0.05) 1px, transparent 1px);
        background-size: 50px 50px; pointer-events: none;
    }

    .cyber-card {
        background: var(--bg-card);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        padding: 1.5rem; position: relative;
    }

    .cyber-card::before {
        content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 3px;
        background: linear-gradient(90deg, var(--accent-cyan), var(--accent-blue));
    }

    .form-cyber {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(0, 229, 255, 0.2);
        color: #FFF !important;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        transition: all 0.3s;
    }
    
    .form-cyber:focus {
        background: rgba(0, 229, 255, 0.05);
        border-color: var(--accent-cyan);
        box-shadow: 0 0 10px rgba(0, 229, 255, 0.2);
    }

    .btn-cyber-info { background: rgba(0, 229, 255, 0.1); color: var(--accent-cyan); border: 1px solid rgba(0, 229, 255, 0.4); transition: 0.3s; }
    .btn-cyber-info:hover { background: rgba(0, 229, 255, 0.3); color: #FFF; box-shadow: 0 0 10px rgba(0, 229, 255, 0.4); }

    .btn-cyber-outline { background: transparent; color: #94A3B8; border: 1px solid #475569; transition: 0.3s; }
    .btn-cyber-outline:hover { background: rgba(148, 163, 184, 0.1); color: #FFF; border-color: #94A3B8; }

    /* Stat Card Cyber */
    .stat-card-cyber {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        padding: 1.25rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .stat-card-cyber:hover {
        transform: translateY(-3px);
        background: rgba(0, 229, 255, 0.03);
        border-color: rgba(0, 229, 255, 0.2);
        box-shadow: 0 8px 20px rgba(0, 229, 255, 0.05);
    }
    .stat-card-cyber::after {
        content: ''; position: absolute; bottom: 0; left: 0; width: 3px; height: 100%;
        background: var(--accent-blue);
    }
    .stat-card-cyber.sc-cyan::after { background: var(--accent-cyan); }
    .stat-card-cyber.sc-success::after { background: var(--accent-success); }
    .stat-card-cyber.sc-danger::after { background: var(--accent-danger); }
    .stat-card-cyber.sc-warning::after { background: var(--accent-warning); }

    .icon-wrapper-cyber {
        width: 48px; height: 48px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; background: rgba(255,255,255,0.03);
        color: var(--accent-cyan);
    }
    .sc-cyan .icon-wrapper-cyber { color: var(--accent-cyan); }
    .sc-success .icon-wrapper-cyber { color: var(--accent-success); }
    .sc-danger .icon-wrapper-cyber { color: var(--accent-danger); }
    .sc-warning .icon-wrapper-cyber { color: var(--accent-warning); }
</style>

<div class="cyber-grid-bg"></div>

<div class="container-fluid position-relative z-1 pb-5">
    
    <!-- Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-8 mb-3 mb-md-0">
            <h3 class="fw-bold" style="color: var(--accent-cyan);"><i class="fa-solid fa-chart-bar me-2"></i>Detail Aktivitas Partner</h3>
            <p class="text-secondary mb-0">Partner: <strong class="text-white">{{ $partner->nama_manager }}</strong> | Username: <strong class="text-white">{{ $partner->user->username ?? '---' }}</strong></p>
        </div>
        <div class="col-md-4 text-md-end text-start">
            <a href="{{ route('admin.aktivitas.index') }}" class="btn btn-cyber-outline px-4 py-2 rounded-pill fw-bold" style="font-size: 0.85rem; letter-spacing: 1px;">
                <i class="fa-solid fa-arrow-left me-2"></i> KEMBALI
            </a>
        </div>
    </div>

    <!-- Filter Rentang Waktu -->
    <div class="cyber-card mb-4">
        <form action="{{ route('admin.partner.aktivitas', $partner->uuid) }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label text-secondary small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control form-cyber" value="{{ $startDateStr }}">
            </div>
            <div class="col-md-4">
                <label class="form-label text-secondary small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control form-cyber" value="{{ $endDateStr }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-cyber-info w-100 fw-bold py-2">
                    <i class="fa-solid fa-filter me-2"></i> Terapkan Filter
                </button>
                <a href="{{ route('admin.partner.aktivitas', $partner->uuid) }}" class="btn btn-cyber-outline py-2 px-3" title="Reset">
                    <i class="fa-solid fa-rotate"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Stats Grid -->
    <div class="row g-3 mb-4">
        <!-- Card Transaksi -->
        <div class="col-6 col-md-4 col-xl">
            <div class="stat-card-cyber sc-cyan">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-secondary small fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Jumlah Transaksi</span>
                    <div class="icon-wrapper-cyber"><i class="fa-solid fa-receipt"></i></div>
                </div>
                <h3 class="fw-bold text-white mb-1">{{ number_format($transactionCount) }}</h3>
                <span class="text-secondary small">Trx Berhasil</span>
            </div>
        </div>

        <!-- Card Penjualan -->
        <div class="col-6 col-md-4 col-xl">
            <div class="stat-card-cyber sc-success">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-secondary small fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Total Penjualan</span>
                    <div class="icon-wrapper-cyber"><i class="fa-solid fa-wallet"></i></div>
                </div>
                <h3 class="fw-bold text-white mb-1" style="font-size: 1.2rem;">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
                <span class="text-secondary small">Omzet Penjualan</span>
            </div>
        </div>

        <!-- Card Pengeluaran -->
        <div class="col-6 col-md-4 col-xl">
            <div class="stat-card-cyber sc-danger">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-secondary small fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Pengeluaran</span>
                    <div class="icon-wrapper-cyber"><i class="fa-solid fa-arrow-trend-down"></i></div>
                </div>
                <h3 class="fw-bold text-white mb-1" style="font-size: 1.2rem;">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h3>
                <span class="text-secondary small">Biaya Operasional</span>
            </div>
        </div>

        <!-- Card Modal Usaha -->
        <div class="col-6 col-md-6 col-xl">
            <div class="stat-card-cyber sc-warning">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-secondary small fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Modal Usaha</span>
                    <div class="icon-wrapper-cyber"><i class="fa-solid fa-sack-dollar"></i></div>
                </div>
                <h3 class="fw-bold text-white mb-1" style="font-size: 1.2rem;">Rp {{ number_format($totalCapital, 0, ',', '.') }}</h3>
                <span class="text-secondary small">Capital diinput</span>
            </div>
        </div>

        <!-- Card Menu -->
        <div class="col-6 col-md-6 col-xl">
            <div class="stat-card-cyber">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-secondary small fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Data Menu</span>
                    <div class="icon-wrapper-cyber"><i class="fa-solid fa-utensils"></i></div>
                </div>
                <h3 class="fw-bold text-white mb-1">{{ $menuCount }} Menu</h3>
                <span class="text-secondary small">{{ $menuActive }} Tersedia</span>
            </div>
        </div>
    </div>

    <!-- Chart Penjualan Real-time/Filter -->
    <div class="cyber-card">
        <h5 class="fw-bold mb-4" style="color: var(--accent-cyan);"><i class="fa-solid fa-chart-line me-2"></i>Grafik Aktivitas Penjualan</h5>
        <div style="position: relative; height: 350px;">
            <canvas id="detailActivityChart"></canvas>
        </div>
    </div>

</div>
@endsection

@stack('scripts')
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('detailActivityChart').getContext('2d');
    
    // Gradient fill background
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(0, 229, 255, 0.3)');
    gradient.addColorStop(1, 'rgba(0, 229, 255, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Aktivitas Transaksi (Trx)',
                data: @json($chartData),
                borderColor: '#00E5FF',
                borderWidth: 3,
                backgroundColor: gradient,
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#00E5FF',
                pointBorderColor: '#0B1120',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                shadowColor: 'rgba(0, 229, 255, 0.5)',
                shadowBlur: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#94A3B8',
                        font: { family: 'Segoe UI', size: 12 }
                    }
                },
                tooltip: {
                    padding: 12,
                    backgroundColor: 'rgba(11, 17, 32, 0.9)',
                    titleColor: '#00E5FF',
                    bodyColor: '#FFF',
                    borderColor: 'rgba(0, 229, 255, 0.3)',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            return ' ' + context.parsed.y + ' Transaksi';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)'
                    },
                    ticks: {
                        color: '#94A3B8',
                        font: { family: 'Segoe UI' }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)'
                    },
                    ticks: {
                        color: '#94A3B8',
                        font: { family: 'Segoe UI' },
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endpush
