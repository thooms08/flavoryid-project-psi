<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pengaturan Akun - Flavory.id</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('layouts.favicon')

    <style>
        :root {
            --primary-orange: #ff6a00;
            --dark-orange: #e86c00;
            --soft-orange: #fff4e6;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fb;
            color: #333;
        }

        .account-card {
            background: white;
            border-radius: 20px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .account-header {
            background: linear-gradient(135deg, var(--primary-orange), var(--dark-orange));
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .account-header i {
            font-size: 3rem;
            margin-bottom: 10px;
            opacity: 0.9;
        }

        .account-header h4 {
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .account-header p {
            margin: 5px 0 0;
            opacity: 0.9;
            font-size: 0.9rem;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark-orange);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--soft-orange);
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .form-label .required {
            color: #dc3545;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            padding: 12px 15px;
            border: 1.5px solid #e9ecef;
            background-color: #f8f9fa;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #ffffff;
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 4px rgba(255, 106, 0, 0.1);
        }

        .form-control:disabled {
            background-color: #e9ecef;
            cursor: not-allowed;
        }

        /* Username validation styling */
        .username-status {
            display: none;
            font-size: 0.85rem;
            margin-top: 5px;
            padding: 8px 12px;
            border-radius: 8px;
            font-weight: 600;
        }

        .username-status.checking {
            display: block;
            background-color: #fff3cd;
            color: #856404;
        }

        .username-status.available {
            display: block;
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .username-status.taken {
            display: block;
            background-color: #f8d7da;
            color: #842029;
        }

        /* Password match indicator */
        .password-match-status {
            display: none;
            font-size: 0.85rem;
            margin-top: 5px;
            padding: 8px 12px;
            border-radius: 8px;
            font-weight: 600;
        }

        .password-match-status.match {
            display: block;
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .password-match-status.nomatch {
            display: block;
            background-color: #f8d7da;
            color: #842029;
        }

        /* Toggle password visibility */
        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            font-size: 1.1rem;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: var(--primary-orange);
        }

        .btn-orange {
            background-color: var(--primary-orange);
            color: white;
            border: none;
            font-weight: 700;
            border-radius: 12px;
            padding: 12px 24px;
            transition: all 0.3s;
            letter-spacing: 0.5px;
            font-size: 0.95rem;
        }

        .btn-orange:hover {
            background-color: var(--dark-orange);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 106, 0, 0.3);
        }

        .btn-back {
            background-color: #6c757d;
            color: white;
            border: none;
            font-weight: 600;
            border-radius: 12px;
            padding: 12px 24px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-back:hover {
            background-color: #5a6268;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.3);
        }

        @media (max-width: 768px) {
            .account-header {
                padding: 1.5rem;
            }

            .account-header i {
                font-size: 2.5rem;
            }

            .account-header h4 {
                font-size: 1.3rem;
            }
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4"
                        role="alert" style="background-color: #d1e7dd; color: #0f5132;">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4"
                        role="alert" style="background-color: #f8d7da; color: #842029;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="account-card">
                    <div class="account-header">
                        <i class="bi bi-person-circle"></i>
                        <h4>Pengaturan Akun Owner</h4>
                        <p>Kelola informasi profil dan keamanan akun Anda</p>
                    </div>

                    <div class="p-4">
                        <!-- ============================================
                             FORM 1: PROFIL MANAGER
                        ============================================= -->
                        <form method="POST" action="{{ route('owner.akun.update') }}" id="profileForm">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="form_type" value="profile">

                            <h5 class="section-title">
                                <i class="bi bi-person-badge me-2"></i>Informasi Profil
                            </h5>

                            <div class="mb-3">
                                <label for="nama_manager" class="form-label">
                                    Nama Manager <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" id="nama_manager" name="nama_manager"
                                    value="{{ old('nama_manager', $user->partner->nama_manager ?? '') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    Email <span class="required">*</span>
                                </label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email', $user->partner->email ?? '') }}" required>
                            </div>

                            <div class="mb-4">
                                <label for="no_wa" class="form-label">
                                    Nomor WhatsApp <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" id="no_wa" name="no_wa"
                                    value="{{ old('no_wa', $user->partner->no_wa ?? '') }}" required
                                    placeholder="08xxxxxxxxxx">
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-orange">
                                    <i class="bi bi-save me-2"></i>Simpan Perubahan Profil
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <!-- ============================================
                             FORM 2: KEAMANAN (USERNAME & PASSWORD)
                        ============================================= -->
                        <form method="POST" action="{{ route('owner.akun.update') }}" id="securityForm">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="form_type" value="security">

                            <h5 class="section-title">
                                <i class="bi bi-shield-lock me-2"></i>Keamanan Akun
                            </h5>

                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    Username <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" id="username" name="username"
                                    value="{{ old('username', $user->username ?? '') }}" required autocomplete="off">
                                <div id="usernameStatus" class="username-status"></div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    Password Baru
                                </label>
                                <div class="password-wrapper">
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Kosongkan jika tidak ingin mengubah" autocomplete="new-password">
                                    <i class="bi bi-eye toggle-password" id="togglePassword"></i>
                                </div>
                                <small class="text-muted">Minimal 6 karakter</small>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">
                                    Konfirmasi Password Baru
                                </label>
                                <div class="password-wrapper">
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" placeholder="Ketik ulang password baru"
                                        autocomplete="new-password">
                                    <i class="bi bi-eye toggle-password" id="togglePasswordConfirm"></i>
                                </div>
                                <div id="passwordMatchStatus" class="password-match-status"></div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('owner.dashboard') }}" class="btn-back">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-orange" id="btnSaveSecurity">
                                    <i class="bi bi-shield-check me-2"></i>Simpan Perubahan Keamanan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ============================================
        // AJAX Username Availability Check
        // ============================================
        let typingTimer;
        const originalUsername = "{{ $user->username }}";

        document.getElementById('username').addEventListener('input', function() {
            clearTimeout(typingTimer);
            const username = this.value.trim();
            const statusDiv = document.getElementById('usernameStatus');

            if (username === '' || username === originalUsername) {
                statusDiv.className = 'username-status';
                statusDiv.textContent = '';
                return;
            }

            statusDiv.className = 'username-status checking';
            statusDiv.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Memeriksa ketersediaan...';

            typingTimer = setTimeout(function() {
                fetch("{{ route('owner.akun.check-username') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ username: username })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.available) {
                        statusDiv.className = 'username-status available';
                        statusDiv.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>Username tersedia!';
                    } else {
                        statusDiv.className = 'username-status taken';
                        statusDiv.innerHTML = '<i class="bi bi-x-circle-fill me-2"></i>Username sudah digunakan!';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    statusDiv.className = 'username-status';
                    statusDiv.textContent = '';
                });
            }, 500);
        });

        // ============================================
        // Real-time Password Match Check
        // ============================================
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const statusDiv = document.getElementById('passwordMatchStatus');

            if (password === '' || confirmPassword === '') {
                statusDiv.className = 'password-match-status';
                statusDiv.textContent = '';
                return;
            }

            if (password === confirmPassword) {
                statusDiv.className = 'password-match-status match';
                statusDiv.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>Password cocok!';
            } else {
                statusDiv.className = 'password-match-status nomatch';
                statusDiv.innerHTML = '<i class="bi bi-x-circle-fill me-2"></i>Password tidak cocok!';
            }
        }

        document.getElementById('password').addEventListener('input', checkPasswordMatch);
        document.getElementById('password_confirmation').addEventListener('input', checkPasswordMatch);

        // ============================================
        // Toggle Password Visibility
        // ============================================
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const icon = this;

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });

        document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
            const passwordField = document.getElementById('password_confirmation');
            const icon = this;

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });

        // ============================================
        // Auto-hide alerts after 5 seconds
        // ============================================
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>

</body>

</html>
