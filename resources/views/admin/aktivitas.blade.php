{{-- resources/views/admin/aktivitas.blade.php --}}
@extends('admin.layouts.app')

@section('content')
<style>
    :root {
        --bg-dark: #0B1120;
        --accent-cyan: #00E5FF;
        --accent-blue: #3B82F6;
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

    .table-cyber {
        color: #F8FAFC;
        border-collapse: separate;
        border-spacing: 0 8px;
    }
    
    .table-cyber th {
        background: transparent; color: #94A3B8; font-size: 0.8rem;
        letter-spacing: 1px; border: none; text-transform: uppercase;
    }

    .table-cyber td {
        background: rgba(255, 255, 255, 0.03);
        border: none; vertical-align: middle; padding: 0.75rem 1rem;
    }

    .table-cyber tr:hover td { background: rgba(0, 229, 255, 0.05); }
    .table-cyber td:first-child { border-top-left-radius: 8px; border-bottom-left-radius: 8px; border-left: 2px solid transparent; }
    .table-cyber tr:hover td:first-child { border-left: 2px solid var(--accent-cyan); }
    .table-cyber td:last-child { border-top-right-radius: 8px; border-bottom-right-radius: 8px; }

    .btn-cyber-info { background: rgba(0, 229, 255, 0.1); color: var(--accent-cyan); border: 1px solid rgba(0, 229, 255, 0.4); transition: 0.3s; }
    .btn-cyber-info:hover { background: rgba(0, 229, 255, 0.3); color: #FFF; box-shadow: 0 0 10px rgba(0, 229, 255, 0.4); }

    .chart-container {
        width: 140px;
        height: 45px;
        display: inline-block;
        vertical-align: middle;
    }
</style>

<div class="cyber-grid-bg"></div>

<div class="container-fluid position-relative z-1 pb-5">
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="fw-bold" style="color: var(--accent-cyan);"><i class="fa-solid fa-chart-line me-2"></i>Aktivitas Partner</h3>
            <p class="text-secondary">Lihat ringkasan grafik aktivitas transaksi 7 hari terakhir dari masing-masing partner.</p>
        </div>
    </div>

    <div class="cyber-card">
        <div class="table-responsive">
            <table class="table table-cyber mb-0">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Nama Manager</th>
                        <th>Nama Outlet</th>
                        <th style="width: 160px;">Tren 7 Hari</th>
                        <th class="text-end" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($partners as $partner)
                        <tr>
                            <td class="fw-bold text-white">{{ $partner->user->username ?? '---' }}</td>
                            <td class="text-white">{{ $partner->nama_manager }}</td>
                            <td class="text-white">{{ $partner->outlet->nama_outlet ?? '---' }}</td>
                            <td>
                                <div class="chart-container">
                                    <canvas id="miniChart-{{ $partner->id }}"></canvas>
                                </div>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.partner.aktivitas', $partner->uuid) }}" class="btn btn-cyber-info btn-sm fw-bold">
                                    <i class="fa-solid fa-eye me-1"></i> LIHAT DETAIL
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fa-solid fa-users-slash fa-3x mb-3" style="color: rgba(0, 229, 255, 0.2);"></i>
                                <h5 class="text-white">Data tidak tersedia</h5>
                                <p class="text-secondary small">Belum ada partner kasir yang terdaftar.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@stack('scripts')
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    @foreach($partners as $partner)
        (function() {
            const ctx = document.getElementById('miniChart-{{ $partner->id }}').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['', '', '', '', '', '', ''],
                    datasets: [{
                        data: @json($partner->activity_chart),
                        borderColor: '#00E5FF',
                        borderWidth: 2.5,
                        pointRadius: 0,
                        pointHoverRadius: 4,
                        pointHoverBackgroundColor: '#00E5FF',
                        pointHoverBorderColor: '#FFF',
                        fill: true,
                        backgroundColor: 'rgba(0, 229, 255, 0.05)',
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false }
                    },
                    scales: {
                        x: { display: false },
                        y: { display: false, min: 0 }
                    }
                }
            });
        })();
    @endforeach
});
</script>
@endpush
