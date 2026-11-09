<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Akun | Flavory.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('layouts.favicon')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-effect {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-orange-50 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-orange-500 rounded-2xl shadow-xl shadow-orange-200 mb-4">
                <i class="bi bi-shield-check text-white text-3xl"></i>
            </div>
           <h1 class="text-3xl font-extrabold text-slate-900">Verifikasi <span class="text-orange-600">Partner</span></h1>
            <div class="mt-5 bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3 text-left shadow-sm">
                <i class="bi bi-envelope-check-fill text-blue-500 text-xl flex-shrink-0 mt-0.5"></i>
                <div>
                    <p class="text-sm font-bold text-blue-800 mb-1">Kode OTP Telah Dikirim ke Email Anda</p>
                    <p class="text-xs text-blue-700 leading-relaxed">
                        Silakan buka email yang Anda daftarkan, lalu masukkan kode verifikasi 6 karakter yang dikirim dari <strong>support@flavory.id</strong>. Kode bersifat rahasia, jangan berikan kepada siapa pun.
                    </p>
                </div>
            </div>

        <div class="glass-effect rounded-[2.5rem] p-8 md:p-10 shadow-2xl border border-white">
            <div class="space-y-6">
                <div>
                    <label for="kode" class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-3 text-center">Masukkan Kode Verifikasi</label>
                    <div class="relative">
                        <input type="text" id="kode" maxlength="6" placeholder="ABCDEF" 
                               class="w-full text-center text-3xl font-black tracking-[0.5em] py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-orange-500 focus:ring-4 focus:ring-orange-100 outline-none transition-all uppercase placeholder:text-slate-200">
                    </div>
                </div>

                <button onclick="submitVerif()" id="btnVerif"
                        class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-orange-200 transition-all transform active:scale-95 flex items-center justify-center gap-2 tracking-widest text-sm">
                    KONFIRMASI <i class="bi bi-check-circle-fill"></i>
                </button>
                
                <div class="text-center">
                    <a href="/register" class="text-xs font-bold text-slate-400 hover:text-orange-600 transition-colors uppercase">
                        <i class="bi bi-arrow-left"></i> Kembali ke Pendaftaran
                    </a>
                </div>
            </div>
        </div>

        <p class="text-center text-slate-400 text-[10px] mt-8 uppercase tracking-widest font-bold">
            &copy; 2026 Flavory.id Security System
        </p>
    </div>

    <script>
        async function submitVerif() {
            const kodeInput = document.getElementById('kode');
            const btn = document.getElementById('btnVerif');
            const kode = kodeInput.value.trim();

            if (kode.length < 1) {
                Swal.fire('Opps!', 'Masukkan kode verifikasi terlebih dahulu.', 'warning');
                return;
            }

            // Efek Loading
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> MEMPROSES...';

            try {
                const res = await fetch(window.location.href, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                    },
                    body: JSON.stringify({ kode })
                });

                if (res.ok) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Selamat, Anda resmi menjadi Partner Flavory.id',
                        icon: 'success',
                        confirmButtonColor: '#ea580c',
                        confirmButtonText: 'Log In Sekarang'
                    }).then(() => {
                        window.location.href = "{{ route('login') }}";
                    });
                } else {
                    Swal.fire('Gagal', 'Kode verifikasi tidak valid atau sudah kedaluwarsa.', 'error');
                    btn.disabled = false;
                    btn.innerHTML = 'KONFIRMASI <i class="bi bi-check-circle-fill"></i>';
                }
            } catch (error) {
                Swal.fire('Error', 'Terjadi kesalahan sistem. Coba lagi nanti.', 'error');
                btn.disabled = false;
                btn.innerHTML = 'KONFIRMASI <i class="bi bi-check-circle-fill"></i>';
            }
        }

        // Auto uppercase & limit typing
        document.getElementById('kode').addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
        });
    </script>
    <script src="{{ asset('js/loading.js') }}"></script>
</body>
</html>