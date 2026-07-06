<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | Flavory.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.min.css" rel="stylesheet">
    @include('layouts.favicon')
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fff7ed;
            background-image: radial-gradient(at 0% 0%, rgba(251, 146, 60, 0.15) 0px, transparent 50%),
                              radial-gradient(at 100% 100%, rgba(251, 146, 60, 0.1) 0px, transparent 50%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .card-auth {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 2rem;
            border: 1px solid #fff;
            box-shadow: 0 20px 40px rgba(255, 106, 0, 0.1);
            padding: 2.5rem;
        }
        .btn-orange {
            background: linear-gradient(135deg, #ff6a00 0%, #e65f00 100%);
            color: white;
            font-weight: 700;
            border-radius: 1rem;
            padding: 0.8rem;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-orange:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 106, 0, 0.2);
            color: white;
        }
        .form-control {
            border-radius: 1rem;
            padding: 0.8rem 1.2rem 0.8rem 2.8rem;
            border: 1px solid #e2e8f0;
        }
        .form-control:focus {
            border-color: #ff6a00;
            box-shadow: 0 0 0 0.25rem rgba(255, 106, 0, 0.15);
        }
        .input-icon {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            color: #94a3b8;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                
                <div class="text-start mb-4">
                    <a href="{{ route('login') }}" class="text-decoration-none text-secondary fw-bold small">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Login
                    </a>
                </div>

                <div class="card-auth">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-orange text-white rounded-4 shadow-sm mb-3" style="width: 60px; height: 60px; background-color: #ff6a00;">
                            <i class="bi bi-key-fill fs-2"></i>
                        </div>
                        <h3 class="fw-bolder text-dark mb-1">Lupa Password?</h3>
                        <p class="text-muted small">Masukkan username Anda. Selanjutnya, Anda akan diminta memasukkan 6-digit kode verifikasi yang Anda dapatkan saat mendaftar. Kode tidak dikirim melalui email.</p>
                    </div>

                    <form action="{{ route('lupa-password.kirim') }}" method="POST">
                        @csrf
                        <div class="mb-4 position-relative">
                            <label class="form-label small fw-bold text-secondary text-uppercase tracking-wider">Username</label>
                            <div class="position-relative">
                                <i class="bi bi-person-circle input-icon"></i>
                                <input type="text" name="username" class="form-control" placeholder="Masukkan username Anda" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-orange w-100 mt-2">
                            VERIFIKASI SEKARANG <i class="bi bi-send-fill ms-1"></i>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.all.min.js"></script>
    <script>
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session("error") }}',
                confirmButtonColor: '#ff6a00',
                customClass: { popup: 'rounded-4' }
            });
        @endif
    </script>
</body>
</html>