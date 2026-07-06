<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Flavory.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        :root {
            --bg-dark: #070B19;
            --accent-cyan: #00E5FF;
            --accent-blue: #3B82F6;
            --accent-success: #10B981;
            --bg-card: rgba(15, 23, 42, 0.85);
            --text-primary: #F8FAFC;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-primary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Background Effects */
        .cyber-grid-bg {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            z-index: -3; background-color: var(--bg-dark);
            background-image: linear-gradient(rgba(0, 229, 255, 0.05) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(0, 229, 255, 0.05) 1px, transparent 1px);
            background-size: 50px 50px; pointer-events: none;
        }

        .cyber-grid-overlay {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -2;
            background: radial-gradient(circle at center, transparent 10%, var(--bg-dark) 100%);
            pointer-events: none;
        }

        /* Compact Card Styling */
        .auth-card {
            background: var(--bg-card);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(0, 229, 255, 0.2);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5), 0 0 20px rgba(0, 229, 255, 0.1);
            padding: 2rem 1.5rem; /* Container dibuat lebih ramping */
            width: 100%;
            max-width: 420px;
            position: relative;
            overflow: hidden;
        }

        .auth-card::before {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 4px;
            background: linear-gradient(90deg, var(--accent-cyan), var(--accent-blue));
        }

        /* Inputs & Buttons */
        .form-control-cyber {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(0, 229, 255, 0.2) !important;
            color: #fff !important;
            padding: 0.7rem 1rem;
            transition: all 0.3s;
        }

        .form-control-cyber:focus {
            background: rgba(0, 229, 255, 0.05) !important;
            border-color: var(--accent-cyan) !important;
            box-shadow: 0 0 15px rgba(0, 229, 255, 0.2) !important;
        }

        .form-control-cyber[readonly] {
            background: rgba(0, 0, 0, 0.3) !important;
            color: var(--accent-cyan) !important;
            cursor: not-allowed;
        }

        .input-group-text.cyber-addon {
            background: rgba(255,255,255,0.05); 
            border: 1px solid rgba(0,229,255,0.2); 
            color: var(--accent-cyan);
        }

        .form-label { color: #94A3B8; font-size: 0.8rem; letter-spacing: 1px; text-transform: uppercase; font-weight: 600; margin-bottom: 0.3rem; }

        .btn-cyber {
            background: rgba(0, 229, 255, 0.1);
            color: var(--accent-cyan);
            border: 1px solid var(--accent-cyan);
            padding: 0.8rem; font-weight: bold; letter-spacing: 1px;
            transition: 0.3s; width: 100%;
        }

        .btn-cyber:hover {
            background: var(--accent-cyan);
            color: var(--bg-dark);
            box-shadow: 0 0 20px rgba(0, 229, 255, 0.4);
        }

        .btn-toggle-pass {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(0,229,255,0.2);
            color: #94A3B8;
            transition: 0.3s;
        }
        .btn-toggle-pass:hover { color: var(--accent-cyan); background: rgba(0,229,255,0.1); }
    </style>
</head>
<body>

    <div class="cyber-grid-bg"></div>
    <div class="cyber-grid-overlay"></div>

    <div class="container px-3 d-flex justify-content-center position-relative z-1">
        <div class="auth-card">
            
            <div class="text-center mb-3">
                <i class="fa-solid fa-bolt fa-2x mb-2" style="color: var(--accent-cyan); text-shadow: 0 0 15px rgba(0,229,255,0.5);"></i>
                <h4 class="fw-bold text-white letter-spacing-1 mb-1">Flavory.id</h4>
                <p class="text-secondary small mb-0">Atur Ulang Kata Sandi Baru</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger py-2 px-3 mb-3" style="background: rgba(239, 68, 68, 0.1); border: 1px solid #EF4444; color: #FCA5A5;">
                    <ul class="mb-0 small ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="resetPasswordForm" method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-3">
                    <label class="form-label">Username Anda</label>
                    <div class="input-group">
                        <span class="input-group-text cyber-addon"><i class="fa-solid fa-user"></i></span>
                        <input type="text" class="form-control form-control-cyber" name="username" value="{{ $username ?? old('username') }}" readonly required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password Baru</label>
                    <div class="input-group">
                        <span class="input-group-text cyber-addon"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" class="form-control form-control-cyber" id="password" name="password" placeholder="Minimal 8 karakter" required autofocus>
                        <button class="btn btn-toggle-pass" type="button" onclick="togglePassword('password', this)">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Konfirmasi Password (Auto-Sync)</label>
                    <div class="input-group">
                        <span class="input-group-text cyber-addon"><i class="fa-solid fa-robot"></i></span>
                        <input type="password" class="form-control form-control-cyber" id="password_confirmation" name="password_confirmation" placeholder="Menyinkronkan otomatis..." readonly required>
                        <button class="btn btn-toggle-pass" type="button" onclick="togglePassword('password_confirmation', this)">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" id="btnSubmitForm" class="btn btn-cyber text-uppercase">
                    <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Password
                </button>
            </form>
        </div>
    </div>

    <div class="modal fade" id="successModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-center" style="background: var(--bg-dark); border: 1px solid var(--accent-success); box-shadow: 0 0 30px rgba(16,185,129,0.2);">
                <div class="modal-body py-4 px-3">
                    <div class="mb-3">
                        <i class="fa-solid fa-shield-check fa-4x" style="color: var(--accent-success); text-shadow: 0 0 20px rgba(16,185,129,0.6);"></i>
                    </div>
                    <h5 class="fw-bold text-white mb-2">Akses Dipulihkan!</h5>
                    <p class="text-secondary small mb-4">Kata sandi akun Anda berhasil diperbarui dan disinkronisasi ke sistem.</p>
                    <a href="{{ route('login') }}" class="btn w-100 fw-bold text-dark" style="background: var(--accent-success); letter-spacing: 1px;">
                        MENUJU LOGIN <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // 1. Fungsi Buka/Tutup Password (Show/Hide)
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // 2. Sinkronisasi Real-Time Input Password ("Ajax Polling" Behavior versi Client-side)
        // Cara ini lebih cepat dan efisien daripada membebani server dengan request Ajax
        const passInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');

        passInput.addEventListener('input', function() {
            confirmInput.value = this.value;
        });

        // 3. Intercept Form Submit dengan AJAX Fetch untuk Memunculkan Modal
        const form = document.getElementById('resetPasswordForm');
        form.addEventListener('submit', async function(e) {
            e.preventDefault(); // Tahan pengiriman form default
            
            const btnSubmit = document.getElementById('btnSubmitForm');
            const originalHtml = btnSubmit.innerHTML;
            
            // Ubah tombol jadi loading
            btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Memproses...';
            btnSubmit.disabled = true;

            try {
                const formData = new FormData(this);
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json' }
                });

                // Jika server merespon dengan error validasi (422), biarkan form terkirim default agar Laravel memunculkan pesan errornya di view
                if (response.status === 422) {
                    form.removeEventListener('submit', arguments.callee);
                    form.submit();
                } else if (response.ok || response.redirected) {
                    // Berhasil! Tampilkan Modal
                    new bootstrap.Modal(document.getElementById('successModal')).show();
                } else {
                    form.submit(); // Fallback jika error lain
                }
            } catch (error) {
                form.submit(); // Fallback keamanan jika fetch gagal
            }
        });
    </script>
</body>
</html>