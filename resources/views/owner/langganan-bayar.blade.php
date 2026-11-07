<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bayar Langganan - Flavory.id</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    @include('layouts.favicon')
    <script src="{{ asset('js/loading.js') }}"></script>
</head>
<body>
<div class="container py-5 d-flex justify-content-center" style="font-family: 'Poppins', sans-serif;">
    <div class="card border-0 shadow-sm rounded-4 w-100" style="max-width: 550px; background-color: #fff;">
        <div class="card-body p-4 p-md-5 text-center">
            
            <h4 class="fw-black mb-2" style="color: #fd7e14; font-weight: 800;">Menunggu Pembayaran</h4>
            <p class="text-muted small mb-4">Selesaikan pembayaran Anda sebelum waktu habis agar lisensi langsung aktif.</p>
            
            <div class="p-4 rounded-4 mb-4" style="background-color: #fff7ed; border: 1px dashed #fd7e14;">
                <p class="small text-muted fw-bold mb-1 letter-spacing-1">METODE PEMBAYARAN</p>
                <h5 class="fw-bold text-dark mb-4">{{ $payment['payment_name'] }}</h5>

                @if(isset($payment['qr_image']))
                    <div class="bg-white p-2 d-inline-block rounded-4 shadow-sm mb-3">
                        <img src="{{ $payment['qr_image'] }}" alt="QRIS" class="img-fluid" style="max-width: 220px; border-radius: 10px;">
                    </div>
                    <p class="small text-muted mb-0">Scan QR Code di atas menggunakan aplikasi e-Wallet atau M-Banking Anda.</p>
                @else
                    <p class="small text-muted fw-bold mb-1">KODE PEMBAYARAN / NO. VA</p>
                    <h2 class="fw-black mb-3" style="color: #fd7e14; letter-spacing: 2px;">
                        {{ $payment['nomor_va'] ?? $payment['pay_code'] ?? '-' }}
                    </h2>
                @endif

                <hr class="text-muted opacity-25 my-3">
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <span class="text-muted fw-medium">Total Tagihan</span>
                    <span class="fs-4 fw-bold text-dark">Rp {{ number_format($payment['total_bayar'], 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <span class="text-muted fw-medium small">Batas Waktu Bayar</span>
                    <span class="fw-bold text-danger small"><i class="bi bi-clock-history me-1"></i> {{ \Carbon\Carbon::parse($payment['expired'])->translatedFormat('d M Y, H:i') }} WIB</span>
                </div>
            </div>

            <div class="text-start bg-light border border-light-subtle rounded-4 p-4 mb-4">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle" style="color: #fd7e14;"></i> Panduan Pembayaran</h6>
                <div class="small text-muted" style="white-space: pre-line; line-height: 1.6;">{{ $payment['tutorial_pembayaran'] }}</div>
            </div>

            <a href="{{ route('owner.dashboard') }}" class="btn rounded-pill w-100 fw-bold py-3 text-white transition-all shadow-sm" style="background-color: #fd7e14;">
                Kembali ke Dashboard
            </a>
            <p class="text-muted mt-3 mb-0" style="font-size: 0.75rem;">Status pembayaran Anda akan otomatis diperbarui dan diverifikasi oleh sistem setelah transfer berhasil.</p>

        </div>
    </div>
</div> 
<script>
    const invoiceNumber = "{{ $invoice }}";

    const checkPaymentStatus = setInterval(function() {
        // Panggil endpoint khusus subscription
        fetch(`/api/check-subscription-status/${invoiceNumber}`)
            .then(response => response.json())
            .then(data => {
                console.log("Status Pembayaran:", data.status);
                
                const status = data.status ? data.status.toLowerCase() : 'pending';

                if (status === 'success' || status === 'paid' || status === 'settlement') {
                    clearInterval(checkPaymentStatus);

                    Swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Berhasil!',
                        text: 'Masa aktif outlet Anda telah diperbarui.',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.href = "{{ route('owner.langganan.sukses') }}";
                    });
                }
            })
            .catch(error => console.error('Error Polling:', error));
    }, 3000);
</script>
</body>
</html>