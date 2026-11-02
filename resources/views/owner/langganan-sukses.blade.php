<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil | Flavory.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;800&display=swap" rel="stylesheet">
    @include('layouts.favicon')
    <script src="{{ asset('js/loading.js') }}"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        /* Animasi kustom untuk ikon sukses */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .animate-float { animation: float 3s ease-in-out infinite; }
    </style>
</head>
<body class="bg-orange-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-[2.5rem] shadow-2xl p-8 text-center border border-white">
        
        <div class="mb-6 flex justify-center">
            <div class="w-28 h-28 bg-green-50 rounded-full flex items-center justify-center animate-float border-[6px] border-green-100">
                <i class="bi bi-check-circle-fill text-green-500 drop-shadow-sm" style="font-size: 4.5rem;"></i>
            </div>
        </div>

        <div class="mb-8">
            <h2 class="text-3xl font-black text-slate-800 mb-3">Hore, Berhasil!</h2>
            <p class="text-slate-500 text-sm leading-relaxed px-2">
                Pembayaran langganan telah kami terima. Sistem membutuhkan sedikit waktu untuk menyinkronkan pembaruan lisensi outlet Anda.
            </p>
            
            <div class="mt-5 p-4 bg-orange-50 rounded-2xl border border-orange-100 text-left flex gap-3 items-start">
                <i class="bi bi-info-circle-fill text-orange-500 mt-0.5"></i>
                <p class="text-xs text-orange-800 font-medium leading-relaxed">
                    Sesi Anda telah diakhiri untuk alasan keamanan. Silakan masuk kembali ke dashboard untuk menikmati fitur paket terbaru.
                </p>
            </div>
        </div>

        <a href="{{ route('login') }}" class="group block w-full bg-[#fd7e14] hover:bg-orange-600 text-white font-bold py-4 rounded-full shadow-lg shadow-orange-500/30 transition-all duration-300 transform hover:-translate-y-1 text-sm tracking-wide">
            LOG IN ULANG SEKARANG
            <i class="bi bi-arrow-right ms-2 inline-block transition-transform group-hover:translate-x-1"></i>
        </a>
        
    </div>
</body>
</html>