<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selesaikan Pembayaran | Flavory.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @include('layouts.favicon')
</head>
<body class="bg-orange-50 min-h-screen flex items-center justify-center p-4 font-sans">
    <div class="max-w-md w-full bg-white rounded-[2.5rem] shadow-2xl p-8 text-center border border-white">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-800">Selesaikan Pembayaran</h2>
            <p class="text-slate-500 text-sm mt-1">{{ $payment['payment_name'] }}</p>
        </div>

        <div class="bg-slate-50 p-6 rounded-3xl border-2 border-dashed border-orange-200 mb-6 w-full">
            @if(isset($payment['qr_image']))
                <img src="{{ $payment['qr_image'] }}" alt="QRIS" class="w-64 h-64 mx-auto rounded-xl shadow-sm mb-2">
                <p class="text-xs text-slate-400">Scan QR Code di atas</p>
            @elseif(isset($payment['nomor_va']))
                <p class="text-xs text-slate-400 mb-2 uppercase tracking-widest font-bold">Nomor Virtual Account</p>
                <h3 class="text-3xl font-black text-orange-600 tracking-wider mb-2">{{ $payment['nomor_va'] }}</h3>
                <button onclick="navigator.clipboard.writeText('{{ $payment['nomor_va'] }}')" class="text-xs bg-orange-100 text-orange-700 px-3 py-1 rounded-full hover:bg-orange-200 transition">
                    <i class="bi bi-copy me-1"></i> Salin Nomor
                </button>
            @elseif(isset($payment['checkout_url']))
                <p class="text-xs text-slate-400 mb-2">Selesaikan pembayaran melalui tautan berikut:</p>
                <a href="{{ $payment['checkout_url'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center w-full rounded-2xl bg-orange-500 text-white py-3 font-bold hover:bg-orange-600 transition">
                    Buka Halaman Pembayaran
                </a>
            @else
                <p class="text-sm text-slate-500">Silakan lanjutkan pembayaran melalui aplikasi pembayaran Anda.</p>
            @endif
        </div>

        <div class="text-left mb-6 bg-orange-50/50 p-4 rounded-2xl">
            <h4 class="text-xs font-bold text-orange-800 mb-2 uppercase"><i class="bi bi-info-circle me-1"></i> Instruksi Bayar:</h4>
            <div class="text-[10px] leading-relaxed text-slate-600 max-h-32 overflow-y-auto">
                {!! nl2br(e($payment['tutorial_pembayaran'])) !!}
            </div>
        </div>

        <div class="space-y-3 mb-6">
            <div class="flex items-center justify-center gap-2 text-orange-600 font-bold">
                <div class="w-2 h-2 bg-orange-600 rounded-full animate-ping"></div>
                <span class="text-sm tracking-widest uppercase">Menunggu Pembayaran...</span>
            </div>
            <div class="bg-slate-100 py-2 px-4 rounded-xl flex justify-between items-center">
                <span class="text-xs text-slate-500">Total Tagihan:</span>
                <span class="font-bold text-slate-800">Rp {{ number_format($payment['total_bayar'], 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="py-4 border-t border-slate-100">
            <p class="text-xs text-slate-400 mb-1">Nomor Invoice:</p>
            <p class="font-mono text-sm font-bold text-slate-700">{{ $invoice }}</p>
        </div>
    </div>

    <script>
        // "Detektif" buat ngecek status pembayaran tiap 3 detik
        setInterval(function() {
            fetch('/api/check-payment-status/{{ $invoice }}')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Jika lunas, langsung arahkan ke halaman verifikasi
                        window.location.href = "{{ route('verifikasi.show', ['id' => $partner_id]) }}";
                    }
                });
        }, 3000); 
    </script>
    @include('layouts.loading')
</body>
</html>