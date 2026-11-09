@extends('owner.layouts.app')

@section('title', 'Pengaturan Akun')

@section('styles')
<style>
    /* ── Page wrapper ──────────────────────────────────────────────── */
    .akun-wrap {
        max-width: 680px;
        margin: 0 auto;
        padding: 40px 24px 80px;
    }

    /* ── Page header ───────────────────────────────────────────────── */
    .page-header {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 36px;
    }
    .page-header-back {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: white;
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--muted);
        font-size: 1.1rem;
        text-decoration: none;
        transition: all .2s ease;
        flex-shrink: 0;
    }
    .page-header-back:hover {
        background: var(--orange);
        border-color: var(--orange);
        color: white;
    }
    .page-header-title {
        font-size: 1.35rem;
        font-weight: 900;
        color: var(--text);
        letter-spacing: -0.5px;
        line-height: 1.2;
    }
    .page-header-sub {
        font-size: 0.8rem;
        color: var(--muted);
        font-weight: 500;
        margin-top: 2px;
    }

    /* ── Alert ─────────────────────────────────────────────────────── */
    .alert-flavory {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 18px;
        border-radius: 14px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 28px;
        border: none;
    }
    .alert-flavory i { font-size: 1.1rem; flex-shrink: 0; margin-top: 1px; }
    .alert-success-f { background: #ecfdf5; color: #065f46; }
    .alert-error-f   { background: #fef2f2; color: #991b1b; }
    .alert-flavory ul { margin: 6px 0 0; padding-left: 18px; }
    .alert-flavory li { margin-bottom: 2px; }

    /* ── Section divider ───────────────────────────────────────────── */
    .section-head {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 22px;
        padding-bottom: 14px;
        border-bottom: 1px solid var(--border);
    }
    .section-head-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .icon-profile  { background: #fff4ec; color: var(--orange); }
    .icon-security { background: #eff6ff; color: #3b82f6; }
    .section-head-title { font-size: 1rem; font-weight: 800; color: var(--text); }
    .section-head-sub   { font-size: 0.74rem; color: var(--muted); font-weight: 500; }

    /* ── Form field ────────────────────────────────────────────────── */
    .field-group { margin-bottom: 20px; }
    .field-label {
        display: block;
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--text);
        letter-spacing: 0.3px;
        text-transform: uppercase;
        margin-bottom: 7px;
    }
    .field-label .req { color: var(--orange); }
    .field-input {
        width: 100%;
        padding: 11px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        background: white;
        font-family: inherit;
        font-size: 0.92rem;
        font-weight: 500;
        color: var(--text);
        transition: border-color .2s ease, box-shadow .2s ease;
        outline: none;
    }
    .field-input:focus {
        border-color: var(--orange);
        box-shadow: 0 0 0 3px rgba(255,106,0,0.1);
    }
    .field-input::placeholder { color: #94a3b8; font-weight: 400; }

    .field-hint {
        font-size: 0.75rem;
        color: var(--muted);
        margin-top: 5px;
        font-weight: 500;
    }

    /* password wrapper */
    .field-password { position: relative; }
    .field-password .field-input { padding-right: 44px; }
    .field-eye {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: var(--muted);
        font-size: 1rem;
        transition: color .2s ease;
        user-select: none;
    }
    .field-eye:hover { color: var(--orange); }

    /* ── Status badges (username / password) ───────────────────────── */
    .field-status {
        display: none;
        font-size: 0.78rem;
        font-weight: 600;
        margin-top: 6px;
        padding: 6px 10px;
        border-radius: 8px;
    }
    .field-status.checking { display: block; background: #fffbeb; color: #92400e; }
    .field-status.ok       { display: block; background: #ecfdf5; color: #065f46; }
    .field-status.error    { display: block; background: #fef2f2; color: #991b1b; }

    /* ── Submit button ──────────────────────────────────────────────── */
    .btn-save {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 24px;
        background: var(--orange);
        color: white;
        border: none;
        border-radius: 12px;
        font-family: inherit;
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        cursor: pointer;
        transition: background .2s ease, transform .15s ease, box-shadow .2s ease;
    }
    .btn-save:hover {
        background: #e85d00;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(255,106,0,0.28);
    }
    .btn-save:active { transform: translateY(0); }

    /* ── Section separator ──────────────────────────────────────────── */
    .form-sep {
        border: none;
        border-top: 1px dashed #e2e8f0;
        margin: 36px 0;
    }

    /* ── Responsive ─────────────────────────────────────────────────── */
    @media (max-width: 600px) {
        .akun-wrap { padding: 24px 16px 80px; }
        .page-header-title { font-size: 1.15rem; }
    }
</style>
@endsection

@section('content')
<div class="akun-wrap">

    {{-- Header --}}
    <div class="page-header">
        <a href="{{ route('owner.dashboard') }}" class="page-header-back" aria-label="Kembali">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <div class="page-header-title">Pengaturan Akun</div>
            <div class="page-header-sub">Kelola profil dan keamanan akun Owner Anda</div>
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="alert-flavory alert-success-f" role="alert">
        <i class="bi bi-check-circle-fill"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="alert-flavory alert-error-f" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>
            <div>Terjadi kesalahan:</div>
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════
         FORM 1 — PROFIL MANAGER
    ════════════════════════════════════════ --}}
    <div class="section-head">
        <div class="section-head-icon icon-profile">
            <i class="bi bi-person-badge"></i>
        </div>
        <div>
            <div class="section-head-title">Informasi Profil</div>
            <div class="section-head-sub">Nama, email, dan nomor WhatsApp yang terdaftar</div>
        </div>
    </div>

    <form method="POST" action="{{ route('owner.akun.update') }}" id="profileForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="form_type" value="profile">

        <div class="field-group">
            <label for="nama_manager" class="field-label">
                Nama Manager <span class="req">*</span>
            </label>
            <input type="text" id="nama_manager" name="nama_manager" class="field-input"
                   value="{{ old('nama_manager', $user->partner->nama_manager ?? '') }}"
                   placeholder="Nama lengkap Anda" required>
        </div>

        <div class="field-group">
            <label for="email" class="field-label">
                Email <span class="req">*</span>
            </label>
            <input type="email" id="email" name="email" class="field-input"
                   value="{{ old('email', $user->partner->email ?? '') }}"
                   placeholder="contoh@email.com" required>
            <div class="field-hint">Email ini digunakan untuk pengiriman kode OTP saat lupa password.</div>
        </div>

        <div class="field-group">
            <label for="no_wa" class="field-label">
                Nomor WhatsApp <span class="req">*</span>
            </label>
            <input type="text" id="no_wa" name="no_wa" class="field-input"
                   value="{{ old('no_wa', $user->partner->no_wa ?? '') }}"
                   placeholder="08xxxxxxxxxx" required>
        </div>

        <div style="display:flex; justify-content:flex-end; margin-top: 4px;">
            <button type="submit" class="btn-save">
                <i class="bi bi-check-lg"></i> Simpan Profil
            </button>
        </div>
    </form>

    <hr class="form-sep">

    {{-- ═══════════════════════════════════════
         FORM 2 — KEAMANAN
    ════════════════════════════════════════ --}}
    <div class="section-head">
        <div class="section-head-icon icon-security">
            <i class="bi bi-shield-lock"></i>
        </div>
        <div>
            <div class="section-head-title">Keamanan Akun</div>
            <div class="section-head-sub">Ubah username atau password login Anda</div>
        </div>
    </div>

    <form method="POST" action="{{ route('owner.akun.update') }}" id="securityForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="form_type" value="security">

        <div class="field-group">
            <label for="username" class="field-label">
                Username <span class="req">*</span>
            </label>
            <input type="text" id="username" name="username" class="field-input"
                   value="{{ old('username', $user->username ?? '') }}"
                   placeholder="Username Anda" required autocomplete="off">
            <div id="usernameStatus" class="field-status"></div>
        </div>

        <div class="field-group">
            <label for="password" class="field-label">Password Baru</label>
            <div class="field-password">
                <input type="password" id="password" name="password" class="field-input"
                       placeholder="Kosongkan jika tidak ingin diubah" autocomplete="new-password">
                <i class="bi bi-eye field-eye" id="togglePassword"></i>
            </div>
            <div class="field-hint">Minimal 6 karakter.</div>
        </div>

        <div class="field-group">
            <label for="password_confirmation" class="field-label">Konfirmasi Password Baru</label>
            <div class="field-password">
                <input type="password" id="password_confirmation" name="password_confirmation" class="field-input"
                       placeholder="Ketik ulang password baru" autocomplete="new-password">
                <i class="bi bi-eye field-eye" id="togglePasswordConfirm"></i>
            </div>
            <div id="passwordMatchStatus" class="field-status"></div>
        </div>

        <div style="display:flex; justify-content:flex-end; margin-top: 4px;">
            <button type="submit" class="btn-save" id="btnSaveSecurity">
                <i class="bi bi-shield-check"></i> Simpan Keamanan
            </button>
        </div>
    </form>

</div>
@endsection

@section('scripts')
<script>
    // ── AJAX: cek ketersediaan username ─────────────────────────────
    const originalUsername = "{{ $user->username }}";
    let typingTimer;

    document.getElementById('username').addEventListener('input', function () {
        clearTimeout(typingTimer);
        const val = this.value.trim();
        const status = document.getElementById('usernameStatus');

        if (!val || val === originalUsername) {
            status.className = 'field-status';
            return;
        }

        status.className = 'field-status checking';
        status.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Memeriksa...';

        typingTimer = setTimeout(() => {
            fetch("{{ route('owner.akun.check-username') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ username: val })
            })
            .then(r => r.json())
            .then(data => {
                if (data.available) {
                    status.className = 'field-status ok';
                    status.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Username tersedia';
                } else {
                    status.className = 'field-status error';
                    status.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> Username sudah digunakan';
                }
            })
            .catch(() => { status.className = 'field-status'; });
        }, 500);
    });

    // ── Real-time password match ─────────────────────────────────────
    function checkMatch() {
        const pw  = document.getElementById('password').value;
        const cpw = document.getElementById('password_confirmation').value;
        const st  = document.getElementById('passwordMatchStatus');

        if (!pw || !cpw) { st.className = 'field-status'; return; }

        if (pw === cpw) {
            st.className = 'field-status ok';
            st.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Password cocok';
        } else {
            st.className = 'field-status error';
            st.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> Password tidak cocok';
        }
    }
    document.getElementById('password').addEventListener('input', checkMatch);
    document.getElementById('password_confirmation').addEventListener('input', checkMatch);

    // ── Toggle password visibility ───────────────────────────────────
    function toggleVis(inputId, iconEl) {
        const input = document.getElementById(inputId);
        const isPass = input.type === 'password';
        input.type = isPass ? 'text' : 'password';
        iconEl.classList.toggle('bi-eye',      !isPass);
        iconEl.classList.toggle('bi-eye-slash', isPass);
    }
    document.getElementById('togglePassword').addEventListener('click', function () { toggleVis('password', this); });
    document.getElementById('togglePasswordConfirm').addEventListener('click', function () { toggleVis('password_confirmation', this); });

    // ── Auto-dismiss flash alert setelah 5 detik ────────────────────
    setTimeout(() => {
        document.querySelectorAll('.alert-flavory').forEach(el => {
            el.style.transition = 'opacity .4s ease';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 400);
        });
    }, 5000);
</script>
@endsection
