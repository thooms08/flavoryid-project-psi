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
</style>

<div class="cyber-grid-bg"></div>

<div class="container-fluid position-relative z-1 pb-5">
    <div class="mb-4">
        <h3 class="fw-bold mb-1" style="color: var(--accent-cyan);"><i class="fa-solid fa-users-gear me-2"></i>Kelola Peserta Event</h3>
        <p class="text-secondary mb-0">Pilih salah satu event di bawah ini untuk mengelola data peserta dan mengunggah sertifikat.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 text-white bg-success-subtle mb-4" role="alert" style="border-left: 4px solid #10B981 !important; background-color: rgba(16, 185, 129, 0.1) !important;">
            <i class="fa-solid fa-circle-check text-success me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        @forelse($events as $event)
            @include('admin.event.partials.card-event', ['event' => $event])
        @empty
            <div class="col-12 text-center py-5">
                <div class="card p-5 border-0" style="background: rgba(19, 32, 64, 0.7); backdrop-filter: blur(16px); border: 1px solid rgba(0, 229, 255, 0.15) !important; border-radius: 16px;">
                    <i class="fa-solid fa-calendar-xmark fs-1 mb-3 text-secondary"></i>
                    <h5 class="text-white fw-bold mb-2">Belum Ada Event</h5>
                    <p class="text-secondary mb-0">Tidak ditemukan event yang aktif atau tersedia untuk mengelola peserta. Silakan buat event terlebih dahulu di menu Kelola Event.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
