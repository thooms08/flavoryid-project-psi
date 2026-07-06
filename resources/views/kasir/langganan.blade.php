<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Langganan Bisnis - Flavory.id</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    @include('layouts.favicon')
    @include('layouts.loading')
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --bs-orange: #fd7e14;
            --bs-orange-hover: #e37011;
            --soft-orange: #fff4e6;
        }

        body {
            background-color: #fff7ed;
            font-family: 'Poppins', sans-serif;
            color: #333;
        }

        /* Button & Utility Styles */
        .btn-orange { background-color: var(--bs-orange); color: white; border: none; font-weight: 600; }
        .btn-orange:hover { background-color: var(--bs-orange-hover); color: white; }
        .text-orange { color: var(--bs-orange) !important; }
        .hover-scale { transition: transform 0.2s; }
        .hover-scale:hover { transform: scale(1.05); }

        /* Custom Cards */
        .card-custom { border-radius: 1rem; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.03); background-color: #fff; }
        
        .package-card {
            border-radius: 1rem; border: 2px solid #f1f1f1; background: #fff;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; position: relative; overflow: hidden;
        }
        .package-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(253, 126, 20, 0.1); border-color: var(--soft-orange); }
        .package-card.selected { border-color: var(--bs-orange); background: var(--soft-orange); }
        
        /* Badges */
        .badge-status { padding: 10px 20px; border-radius: 50px; font-weight: 700; letter-spacing: 1px; font-size: 0.85rem; display: inline-block; }
        .bg-aktif { background: #d1e7dd; color: #0f5132; }
        .bg-jatuh_tempo { background: #fff3cd; color: #664d03; }
        .bg-nonaktif { background: #f8d7da; color: #842029; }
    </style>
</head>
<body>

<div class="container-fluid py-4" style="max-width: 900px;">
    <div class="d-flex align-items-center gap-3 mb-4">
        @if($status !== 'nonaktif')
        <a href="{{ route('kasir.dashboard') }}" class="btn btn-light border-warning-subtle text-secondary rounded-4 d-flex align-items-center justify-content-center hover-scale shadow-sm" style="width: 48px; height: 48px;">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        @endif
        <h1 class="h4 mb-0 fw-black text-uppercase" style="font-weight: 900; letter-spacing: -0.5px;">Kelola Langganan</h1>
    </div>

    @if(session('error'))
        <div class="alert alert-danger rounded-4 fw-bold shadow-sm mb-4 border-0 border-start border-danger border-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="row g-3 mb-4 align-items-stretch">
        <div class="col-lg-7">
            <div class="card card-custom p-4 h-100 d-flex flex-column justify-content-center">
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 text-center text-md-start">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-orange shadow-sm" style="width: 50px; height: 50px;">
                            <i class="bi bi-shield-check fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Status Lisensi</h5>
                            <p class="text-muted small mb-0">
                                Outlet: <strong>{{ $outlet->nama_outlet }}</strong> 
                                <span class="mx-1">|</span> 
                                Paket: <span class="badge bg-light text-orange border border-warning-subtle text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">{{ $paket }}</span>
                            </p>
                        </div>
                    </div>
                    <div>
                        @if($status === 'aktif')
                            <div class="badge-status bg-aktif shadow-sm"><i class="bi bi-check-circle-fill me-1"></i> AKTIF</div>
                        @elseif($status === 'jatuh_tempo')
                            <div class="badge-status bg-jatuh_tempo shadow-sm"><i class="bi bi-exclamation-circle-fill me-1"></i> JATUH TEMPO</div>
                        @else
                            <div class="badge-status bg-nonaktif shadow-sm"><i class="bi bi-x-circle-fill me-1"></i> NONAKTIF</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card card-custom p-4 h-100 d-flex flex-column justify-content-center" style="background: linear-gradient(135deg, var(--bs-orange) 0%, #e37011 100%); color: white; position: relative; overflow: hidden;">
                
                <i class="bi bi-graph-up-arrow position-absolute" style="font-size: 8rem; right: -20px; bottom: -30px; opacity: 0.15;"></i>
                
                <div class="position-relative z-1">
                    <h6 class="fw-bold mb-1" style="color: #ffe8d6; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">
                        <i class="bi bi-stars me-1"></i> Keuntungan Anda Bulan {{ $namaBulan }}
                    </h6>
                    <h3 class="fw-black mb-2" style="font-weight: 900;">
                        Rp {{ number_format($totalUntung ?? 0, 0, ',', '.') }}
                    </h3>
                    <p class="small mb-0" style="opacity: 0.85; line-height: 1.4;">
                        Ayo tingkatkan terus penjualan {{ $outlet->nama_outlet }}!
                    </p>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('kasir.langganan.subscribe') }}" method="POST">
        @csrf
        <div class="card card-custom p-4 mb-4">
            <h5 class="fw-bold mb-4 text-dark"><i class="bi bi-box-seam text-orange me-2"></i> 1. Pilih Paket Layanan</h5>
            
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="w-100 h-100">
                        <input type="radio" name="paket" value="eksklusif" class="d-none package-radio" checked>
                        <div class="package-card p-4 text-center h-100 d-flex flex-column justify-content-center selected" id="card-eksklusif">
                            <h4 class="fw-bold text-dark mb-2">EKSKLUSIF</h4>
                            <h2 class="fw-extrabold mb-0 text-orange">Rp 70.000</h2>
                            <span class="text-muted small">/ bulan</span>
                            <hr class="w-50 mx-auto my-3 text-muted opacity-25">
                            <ul class="list-unstyled small text-muted text-start mb-0 px-md-3">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Full Akses Dashboard Kasir</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i> Support Prioritas 24/7</li>
                            </ul>
                        </div>
                    </label>
                </div>
                <div class="col-md-6">
                    <label class="w-100 h-100">
                        <input type="radio" name="paket" value="super" class="d-none package-radio">
                        <div class="package-card p-4 text-center h-100 d-flex flex-column justify-content-center" id="card-super">
                            <h4 class="fw-bold text-dark mb-2 mt-2">SUPER</h4>
                            <h2 class="fw-extrabold mb-0 text-orange">Rp 880.000</h2>
                            <span class="text-muted small">/ tahun</span>
                            <hr class="w-50 mx-auto my-3 text-muted opacity-25">
                            <ul class="list-unstyled small text-muted text-start mb-0 px-md-3">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Semua Fitur Paket Eksklusif</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i> Bebas Biaya Admin Transaksi</li>
                            </ul>
                        </div>
                    </label>
                </div>
            </div>

            <hr class="text-muted opacity-10 my-4">

            <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-wallet2 text-orange me-2"></i> 2. Metode Pembayaran</h5>
            <div class="mb-2">
                <select name="metode_pembayaran" class="form-select form-select-lg shadow-sm" style="border-radius: 12px; border: 1px solid #dee2e6; font-size: 0.95rem;" required>
                    <option value="QRIS">QRIS (Otomatis & Instan)</option>
                    <option value="BCAVA">BCA Virtual Account</option>
                    <option value="BRIVA">BRI Virtual Account</option>
                    <option value="BNIVA">BNI Virtual Account</option>
                    <option value="BSIVA">BSI Virtual Account</option>
                    <option value="MANDIRIVA">Mandiri Virtual Account</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-orange w-100 py-3 mt-4 fs-6 fw-bold shadow-sm rounded-pill hover-scale">
                LANJUTKAN PEMBAYARAN <i class="bi bi-arrow-right-circle ms-2"></i>
            </button>
        </div>
    </form>

    <div class="mt-5 pt-3 pb-5 text-center">
        <p class="text-muted small mb-3">Ingin berhenti menggunakan layanan Flavory.id?</p>
        <button type="button" class="btn btn-outline-danger rounded-pill px-4 py-2 fw-bold text-uppercase shadow-sm" data-bs-toggle="modal" data-bs-target="#modalUnsubscribe">
            <i class="bi bi-dash-circle me-1"></i> Henti Berlangganan
        </button>
    </div>
</div>

<div class="modal fade" id="modalUnsubscribe" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
            <div class="modal-header border-0 pb-0 mt-2 mx-2">
                <h5 class="modal-title fw-bold text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Berhenti</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('kasir.langganan.unsubscribe') }}" method="POST">
                @csrf
                <div class="modal-body py-4 px-4">
                    <div class="p-3 bg-danger bg-opacity-10 rounded-3 mb-3">
                        <p class="text-danger small mb-0 fw-medium">Sangat disayangkan Anda harus pergi. Mohon beritahu kami alasan Anda berhenti berlangganan untuk peningkatan sistem kami ke depan.</p>
                    </div>
                    <label class="form-label fw-bold text-secondary small">Alasan Berhenti:</label>
                    <textarea name="alasan" class="form-control bg-light" rows="4" placeholder="Tuliskan alasan Anda di sini (Minimal 10 karakter)..." style="border-radius: 12px; border: 1px solid #dee2e6;" required minlength="10"></textarea>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4 d-flex gap-2">
                    <button type="button" class="btn btn-light rounded-pill fw-bold flex-fill border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill fw-bold flex-fill shadow-sm">Ya, Hentikan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Script untuk efek toggle radio button
    document.querySelectorAll('.package-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.package-card').forEach(card => card.classList.remove('selected'));
            this.nextElementSibling.classList.add('selected');
        });
    });
</script>
</body>
</html>