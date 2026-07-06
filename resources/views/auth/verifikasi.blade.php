<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Kode | Flavory.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.min.css" rel="stylesheet">
    @include('layouts.favicon')

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fff7ed;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .card-auth {
            background: #ffffff;
            border-radius: 2rem;
            border: none;
            box-shadow: 0 20px 40px rgba(255, 106, 0, 0.1);
            padding: 2.5rem;
        }
        .btn-orange {
            background: #ff6a00;
            color: white;
            font-weight: 700;
            border-radius: 1rem;
            padding: 0.8rem;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-orange:hover {
            background: #e65f00;
            color: white;
            transform: translateY(-2px);
        }
        .form-control-code {
            border-radius: 1rem;
            padding: 1rem;
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            letter-spacing: 10px;
            border: 2px solid #ff6a00;
            color: #ff6a00;
            background-color: #fff4e6;
        }
        .form-control-code:focus {
            box-shadow: 0 0 0 0.25rem rgba(255, 106, 0, 0.2);
            border-color: #ff6a00;
            outline: none;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                
                <div class="card-auth text-center">
                    <div class="mb-4">
                        <i class="bi bi-envelope-paper-heart-fill text-orange" style="font-size: 3.5rem; color: #ff6a00;"></i>
                    </div>
                    <h3 class="fw-bolder text-dark mb-2">Masukkan Kode Verifikasi</h3>
                    <p class="text-muted small mb-4">
                        Masukkan 6-digit kode verifikasi yang sama seperti kode saat pendaftaran. Kode ini langsung ditampilkan di layar setelah Anda selesai mendaftar.
                    </p>

                    <form action="{{ route('password.verifikasi.proses') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <input type="text" name="code" class="form-control form-control-code w-100" placeholder="ABC123" maxlength="6" pattern="[A-Za-z0-9]{6}" required autocomplete="off" oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');">
                            <div class="form-text mt-2 text-danger small">Masukkan kombinasi huruf dan angka yang Anda terima saat pendaftaran.</div>
                        </div>

                        <button type="submit" class="btn btn-orange w-100">
                            VERIFIKASI & MASUK <i class="bi bi-box-arrow-in-right ms-1"></i>
                        </button>
                    </form>

                    <div class="mt-4 pt-3 border-top">
                        <p class="small text-muted mb-0">Belum punya kode? 
                            <a href="{{ route('lupa-password') }}" class="text-decoration-none fw-bold" style="color: #ff6a00;">Kembali ke form lupa password</a>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.all.min.js"></script>
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session("success") }}',
                confirmButtonColor: '#ff6a00',
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Verifikasi Gagal',
                text: '{{ session("error") }}',
                confirmButtonColor: '#ff6a00',
            });
        @endif
        
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Input Tidak Valid',
                text: 'Pastikan kode terdiri dari 6 angka.',
                confirmButtonColor: '#ff6a00',
            });
        @endif
    </script>
</body>
</html>