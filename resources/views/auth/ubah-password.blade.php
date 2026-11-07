<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Password | Flavory.id</title>
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
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
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
                        <div class="d-inline-flex align-items-center justify-content-center text-white rounded-4 shadow-sm mb-3"
                             style="width: 60px; height: 60px; background-color: #ff6a00;">
                            <i class="bi bi-shield-lock-fill fs-2"></i>
                        </div>
                        <h3 class="fw-bolder text-dark mb-1">Buat Password Baru</h3>
                        <p class="text-muted small">Masukkan password baru Anda. Minimal 8 karakter.</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger rounded-3 small">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="username" value="{{ $username }}">

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary text-uppercase">Password Baru</label>
                            <div class="position-relative">
                                <i class="bi bi-lock-fill input-icon"></i>
                                <input type="password" id="password" name="password"
                                       class="form-control pe-5"
                                       placeholder="Minimal 8 karakter" required minlength="8">
                                <button type="button" class="toggle-password" onclick="toggleVisibility('password', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary text-uppercase">Konfirmasi Password</label>
                            <div class="position-relative">
                                <i class="bi bi-lock-fill input-icon"></i>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="form-control pe-5"
                                       placeholder="Ulangi password baru" required minlength="8">
                                <button type="button" class="toggle-password" onclick="toggleVisibility('password_confirmation', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-orange w-100">
                            SIMPAN PASSWORD <i class="bi bi-check-circle-fill ms-1"></i>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.all.min.js"></script>
    <script>
        function toggleVisibility(fieldId, btn) {
            const input = document.getElementById(fieldId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }

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
