<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ubah Akun - Flavory.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @include('layouts.favicon')
    <script src="{{ asset('js/loading.js') }}"></script>
</head>
<style>
    :root { 
        --primary-orange: #fd7e14; 
        --dark-orange: #e86c00; 
        --soft-orange: #fff4e6;
    }

    body {
        background-color: #fff7ed;
    }

    .profile-card {
        background: white;
        border-radius: 20px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        overflow: hidden;
    }

    .profile-header {
        background: var(--soft-orange);
        border-bottom: 2px solid rgba(253, 126, 20, 0.1);
        padding: 1.5rem;
        text-align: center;
    }

    .form-control:focus {
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 4px rgba(253, 126, 20, 0.15);
    }

    .btn-orange { 
        background-color: var(--primary-orange); 
        color: white; 
        font-weight: 600; 
        border-radius: 12px; 
        transition: 0.3s; 
    }
    
    .btn-orange:hover {
        background-color: var(--dark-orange);
        color: white;
    }

    .btn-dark-outline {
        border: 2px solid #343a40;
        color: #343a40;
        font-weight: 600;
        border-radius: 12px;
        transition: 0.3s;
    }

    .btn-dark-outline:hover {
        background-color: #343a40;
        color: white;
    }
</style>
<body>
@include('kasir.layouts.app')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">

            <div class="profile-card">
                <div class="profile-header d-flex justify-content-between align-items-center px-4">
                    <a href="{{ route('kasir.profile-outlet.edit') }}" class="btn btn-sm btn-light rounded-circle"><i class="bi bi-arrow-left"></i></a>
                    <div>
                        <i class="bi bi-person-badge fs-2 mb-1 d-block" style="color: var(--primary-orange);"></i>
                        <h4 class="mb-0" style="color: var(--dark-orange); font-weight: 700;">Pengaturan Akun</h4>
                        <p class="text-muted small mb-0 mt-1">Kelola profil, username, dan password Anda</p>
                    </div>
                    <div style="width: 32px;"></div> 
                </div>

                <div class="card-body p-4 p-md-5">
                    
                    <div class="mb-4 p-3 bg-light rounded-3 border">
                        <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Akun Saat Ini</h6>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <span class="text-muted small d-block">Username Aktif:</span>
                                <strong class="fs-6">{{ $user->username }}</strong>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small d-block">Nama Manager:</span>
                                <strong class="fs-6">{{ $user->partner->nama_manager ?? '-' }}</strong>
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3" style="color: var(--dark-orange);"><i class="bi bi-person-lines-fill me-2"></i>Informasi Profil</h5>
                    <form action="{{ route('kasir.akun.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="form_type" value="profile">
                        
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label class="form-label fw-bold text-muted small">Nama Manager <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_manager" value="{{ old('nama_manager', $user->partner->nama_manager ?? '') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" value="{{ old('email', $user->partner->email ?? '') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Nomor WhatsApp <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="no_wa" value="{{ old('no_wa', $user->partner->no_wa ?? '') }}" required placeholder="Contoh: 08123456789">
                            </div>
                        </div>

                        <div class="d-grid mb-5">
                            <button type="submit" class="btn btn-orange text-uppercase py-2">
                                <i class="bi bi-save2 me-2"></i> Simpan Profil
                            </button>
                        </div>
                    </form>

                    <hr class="my-4 border-2 border-orange opacity-25">

                    <h5 class="fw-bold mb-3" style="color: var(--dark-orange);"><i class="bi bi-shield-lock me-2"></i>Keamanan Akun</h5>
                    <form action="{{ route('kasir.akun.update') }}" method="POST" id="form-akun">
                        @csrf
                        <input type="hidden" name="form_type" value="security">
                        
                        <div class="mb-4">
                            <label for="username" class="form-label fw-bold text-muted small">Username Baru <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $user->username) }}" required autocomplete="off">
                            <div id="username-msg" class="mt-1"></div>
                        </div>

                        <div class="alert alert-warning border-0" style="background-color: var(--soft-orange); color: var(--dark-orange);">
                            <i class="bi bi-exclamation-triangle me-2"></i> Kosongkan kolom password di bawah ini jika tidak ingin mengubah password.
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-bold text-muted small">Password Baru</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 6 karakter">
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-bold text-muted small">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru">
                                <div id="password-msg" class="mt-1"></div>
                            </div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="show-password">
                            <label class="form-check-label text-muted small" for="show-password">
                                Tampilkan Password
                            </label>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-dark-outline text-uppercase py-2" id="btn-submit">
                                <i class="bi bi-key-fill me-2"></i> Perbarui Keamanan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    
    // Tampilkan Alert Sukses
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#fd7e14'
        });
    @endif

    // Tampilkan Alert Error Validasi Backend
    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Gagal Menyimpan!',
            html: '<ul class="text-start">@foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>',
            confirmButtonColor: '#fd7e14'
        });
    @endif

    // Toggle Show Password
    document.getElementById('show-password').addEventListener('change', function() {
        const type = this.checked ? 'text' : 'password';
        document.getElementById('password').type = type;
        document.getElementById('password_confirmation').type = type;
    });

    // Validasi Konfirmasi Password (AJAX/JS Frontend)
    const passInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const passMsg = document.getElementById('password-msg');

    function checkPasswordMatch() {
        const pass = passInput.value;
        const confirm = confirmInput.value;

        if (confirm.length === 0) {
            passMsg.innerHTML = '';
            confirmInput.classList.remove('is-invalid', 'is-valid');
            return;
        }

        if (pass !== confirm) {
            passMsg.innerHTML = '<span class="text-danger small"><i class="bi bi-x-circle"></i> Password tidak cocok!</span>';
            confirmInput.classList.add('is-invalid');
            confirmInput.classList.remove('is-valid');
        } else {
            passMsg.innerHTML = '<span class="text-success small"><i class="bi bi-check-circle"></i> Password cocok!</span>';
            confirmInput.classList.remove('is-invalid');
            confirmInput.classList.add('is-valid');
        }
    }

    passInput.addEventListener('input', checkPasswordMatch);
    confirmInput.addEventListener('input', checkPasswordMatch);

    // Validasi Username ketersediaan (AJAX)
    const usernameInput = document.getElementById('username');
    const usernameMsg = document.getElementById('username-msg');
    let timeout = null;

    usernameInput.addEventListener('input', function() {
        clearTimeout(timeout);
        const username = this.value.trim();

        if (username.length === 0) {
            usernameMsg.innerHTML = '';
            this.classList.remove('is-invalid', 'is-valid');
            return;
        }

        // Indikator Loading
        usernameMsg.innerHTML = '<span class="text-muted small"><i class="spinner-border spinner-border-sm"></i> Memeriksa username...</span>';

        timeout = setTimeout(() => {
            fetch('{{ route("kasir.akun.cek-username") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ username: username })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.available) {
                    usernameMsg.innerHTML = '<span class="text-danger fw-bold small"><i class="bi bi-x-circle"></i> Username sudah digunakan!</span>';
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                } else {
                    usernameMsg.innerHTML = '<span class="text-success fw-bold small"><i class="bi bi-check-circle"></i> Username tersedia!</span>';
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            })
            .catch(error => {
                usernameMsg.innerHTML = '<span class="text-danger small">Gagal memeriksa username.</span>';
            });
        }, 600);
    });

    // Mencegah submit jika ada error validasi di frontend pada form keamanan
    document.getElementById('form-akun').addEventListener('submit', function(e) {
        if (usernameInput.classList.contains('is-invalid') || confirmInput.classList.contains('is-invalid')) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Tunggu Sebentar',
                text: 'Pastikan username tersedia dan konfirmasi password sudah cocok sebelum menyimpan.',
                confirmButtonColor: '#fd7e14'
            });
        }
    });
});
</script>
</body>
</html>