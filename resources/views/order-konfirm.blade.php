<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan - {{ $outlet->nama_outlet }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @include('layouts.favicon')
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .success-card { background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: none; overflow: hidden; }
        .icon-circle { width: 80px; height: 80px; background: #e8f5e9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; }
        .btn-orange { background-color: #ff6a00; color: #fff; border-radius: 50px; padding: 12px 30px; font-weight: 600; text-decoration: none; display: inline-block; transition: 0.3s; }
        .btn-orange:hover { background-color: #e65f00; color: #fff; transform: translateY(-2px); }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 py-5">

    <div class="container" style="max-width: 500px;">
        <div class="success-card text-center p-5">
            <div class="icon-circle mb-4">
                <i class="bi bi-check-lg text-success" style="font-size: 3rem;"></i>
            </div>
            
            <h3 class="fw-bold mb-1">Pesanan Diterima!</h3>
            <p class="text-muted mb-4">Terima kasih, pesanan Anda telah masuk ke sistem kami.</p>

            <div class="bg-light rounded-4 p-4 mb-4 border">
                <p class="text-muted small fw-bold mb-1">NOMOR PESANAN</p>
                <h2 class="fw-bolder text-dark mb-3">#{{ $order->nomor_pesanan }}</h2>
                
                <hr class="opacity-25 my-3">
                
                <p class="text-muted small fw-bold mb-1">TOTAL PEMBAYARAN</p>
                <h3 class="fw-bolder mb-0" style="color: #ff6a00;">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</h3>
            </div>

            <div class="alert alert-warning border-0 rounded-4 text-start d-flex align-items-center mb-5" style="background-color: #fff4e6; color: #cc5500;">
                <i class="bi bi-info-circle-fill fs-3 me-3"></i>
                <span class="small fw-medium" style="line-height: 1.5;">Silakan menuju kasir untuk melakukan pembayaran dan konfirmasi pesanan agar pesanan dapat diproses.</span>
            </div>

            <a href="{{ route('outlet.public', $outlet->page_name) }}" class="btn-orange w-100">
                <i class="bi bi-arrow-left me-2"></i> KEMBALI KE MENU
            </a>
        </div>
    </div>

</body>
</html>