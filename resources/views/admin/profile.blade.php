{{-- resources/views/admin/profile.blade.php --}}
@extends('admin.layouts.app')

@section('content')
<style>
    :root {
        --bg-dark: #0B1120;
        --accent-cyan: #00E5FF;
        --accent-blue: #3B82F6;
        --bg-card: rgba(15, 23, 42, 0.7);
    }

    .cyber-grid-bg {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
        z-index: -3; background-color: var(--bg-dark);
        background-image: linear-gradient(rgba(0, 229, 255, 0.05) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(0, 229, 255, 0.05) 1px, transparent 1px);
        background-size: 50px 50px; pointer-events: none;
    }

    .cyber-card {
        background: var(--bg-card);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        padding: 2rem; position: relative;
    }

    .cyber-card::before {
        content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 3px;
        background: linear-gradient(90deg, var(--accent-cyan), var(--accent-blue));
    }

    .form-cyber {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(0, 229, 255, 0.2);
        color: #FFF !important;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s;
    }
    
    .form-cyber:focus {
        background: rgba(0, 229, 255, 0.05);
        border-color: var(--accent-cyan);
        box-shadow: 0 0 10px rgba(0, 229, 255, 0.2);
        color: #FFF !important;
    }

    .form-label-cyber {
        color: #94A3B8;
        font-size: 0.8rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .btn-cyber-success {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
        border: 1px solid rgba(16, 185, 129, 0.4);
        transition: all 0.3s ease;
        letter-spacing: 1px;
    }
    
    .btn-cyber-success:hover {
        background: rgba(16, 185, 129, 0.3);
        color: #FFF;
        box-shadow: 0 0 15px rgba(16, 185, 129, 0.4);
    }
</style>

<div class="cyber-grid-bg"></div>

<div class="container position-relative z-1 pb-5" style="max-width: 800px;">
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="fw-bold" style="color: var(--accent-cyan);"><i class="fa-solid fa-circle-user me-2"></i>Profil Admin</h3>
            <p class="text-secondary">Kelola username dan kata sandi akses akun Administrator Anda.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 text-white mb-4" style="background: rgba(16, 185, 129, 0.2); border-left: 4px solid #10B981 !important;">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="cyber-card">
        <form action="{{ route('admin.profile.update') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="username" class="form-label form-label-cyber">Username Admin</label>
                <input type="text" name="username" id="username" class="form-control form-cyber @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" autocomplete="off">
                @error('username')
                    <div class="invalid-feedback text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <hr class="my-4" style="border-color: rgba(0, 229, 255, 0.15);">
            <p class="text-secondary small mb-3"><i class="fa-solid fa-circle-info me-2"></i>Kosongkan kolom password di bawah jika Anda tidak ingin mengubah password.</p>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label for="password" class="form-label form-label-cyber">Password Baru</label>
                    <input type="password" name="password" id="password" class="form-control form-cyber @error('password') is-invalid @enderror" placeholder="••••••••">
                    @error('password')
                        <div class="invalid-feedback text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-4">
                    <label for="password_confirmation" class="form-label form-label-cyber">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-cyber" placeholder="••••••••">
                </div>
            </div>

            <div class="d-flex justify-content-end mt-2">
                <button type="submit" class="btn btn-cyber-success px-4 py-2 fw-bold rounded-pill">
                    <i class="fa-solid fa-floppy-disk me-2"></i>SIMPAN PERUBAHAN
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
