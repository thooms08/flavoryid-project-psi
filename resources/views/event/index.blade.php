{{-- resources/views/event/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Event & Workshop Flavory.id | Tingkatkan Skill Bisnis Kuliner Anda')

@push('styles')
<meta name="description" content="Ikuti event dan workshop eksklusif Flavory.id. Pelajari strategi bisnis kuliner, manajemen restoran, dan teknologi POS bersama para ahlinya.">
<style>
    :root {
        --primary-orange: #ff6a00;
        --dark-orange:    #e65f00;
        --light-orange:   #fff4ec;
    }

    /* ── Hero ── */
    .event-hero {
        padding: 130px 0 70px;
        background: linear-gradient(135deg, #ffffff 0%, var(--light-orange) 100%);
    }
    .event-hero-title {
        font-size: 2.6rem;
        font-weight: 800;
        line-height: 1.3;
        letter-spacing: -.5px;
    }
    @media (max-width: 768px) { .event-hero-title { font-size: 1.9rem; } }

    /* ── Filter Tabs ── */
    .filter-tabs {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: center;
        margin-bottom: 40px;
    }
    .filter-tab {
        padding: 8px 22px;
        border-radius: 50px;
        border: 2px solid #e2e8f0;
        background: #fff;
        font-size: .85rem;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: all .25s;
    }
    .filter-tab.active,
    .filter-tab:hover {
        border-color: var(--primary-orange);
        background: var(--primary-orange);
        color: #fff;
    }

    /* ── Event Card ── */
    .event-card {
        border: 1px solid rgba(0,0,0,.06);
        border-radius: 20px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 4px 16px rgba(0,0,0,.04);
        transition: all .3s ease;
    }
    .event-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 40px rgba(255,106,0,.12);
        border-color: rgba(255,106,0,.25);
    }
    .event-card-img-wrap {
        position: relative;
        aspect-ratio: 16/9;
        overflow: hidden;
        background: #f8f9fa;
    }
    .event-card-img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform .4s ease;
    }
    .event-card:hover .event-card-img { transform: scale(1.05); }
    .event-card-img-placeholder {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, #fff4ec, #ffe8d0);
        font-size: 3rem;
        color: var(--primary-orange);
    }
    .event-status-badge {
        position: absolute;
        top: 12px; left: 12px;
        font-size: .72rem;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 20px;
        letter-spacing: .3px;
    }
    .event-card-body { padding: 20px; }
    .event-card-title {
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.4;
        margin-bottom: 8px;
        color: #1a202c;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .event-card-desc {
        font-size: .82rem;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 12px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .event-meta { display: flex; flex-direction: column; gap: 6px; }
    .event-meta li {
        display: flex; align-items: center; gap: 8px;
        font-size: .8rem; color: #475569;
    }
    .btn-orange-event {
        background: var(--primary-orange);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 10px;
        font-weight: 600;
        font-size: .85rem;
        transition: all .25s;
        text-decoration: none;
        display: block;
        text-align: center;
    }
    .btn-orange-event:hover {
        background: var(--dark-orange);
        color: #fff;
        transform: translateY(-2px);
    }
    .btn-secondary-event {
        background: #e2e8f0;
        color: #94a3b8;
        border: none;
        border-radius: 12px;
        padding: 10px;
        font-weight: 600;
        font-size: .85rem;
        cursor: not-allowed;
    }

    /* ── Empty State ── */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        color: #94a3b8;
    }
    .empty-state i { font-size: 4rem; margin-bottom: 16px; display: block; opacity: .4; }
</style>
@endpush

@section('content')
{{-- Hero --}}
<header class="event-hero">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-7">
                <span class="badge bg-light-orange text-orange px-3 py-2 rounded-pill fw-bold border border-warning-subtle mb-3">
                    <i class="bi bi-calendar-event-fill me-1"></i> Event & Workshop
                </span>
                <h1 class="event-hero-title mb-3">
                    Perluas Ilmu,<br>
                    <span style="color: var(--primary-orange);">Kembangkan Bisnis Kulinermu</span>
                </h1>
                <p class="text-muted fs-6">
                    Ikuti event dan workshop eksklusif Flavory.id. Belajar langsung dari praktisi,
                    bertemu komunitas pemilik usaha kuliner, dan dapatkan insight nyata untuk bisnismu.
                </p>
            </div>
        </div>
    </div>
</header>

{{-- Daftar Event --}}
<section class="py-5" style="background:#f8fafc;">
    <div class="container py-3">

        {{-- Filter Tabs --}}
        <div class="filter-tabs">
            <button class="filter-tab active" data-filter="all">Semua Event</button>
            <button class="filter-tab" data-filter="upcoming">Segera Hadir</button>
            <button class="filter-tab" data-filter="ongoing">Sedang Berlangsung</button>
            <button class="filter-tab" data-filter="ended">Sudah Selesai</button>
        </div>

        @if($events->isEmpty())
            <div class="empty-state">
                <i class="bi bi-calendar-x"></i>
                <h4 class="fw-bold text-dark mb-2">Belum Ada Event</h4>
                <p class="text-muted">Event akan segera hadir. Pantau terus halaman ini!</p>
            </div>
        @else
            <div class="row g-4" id="eventGrid">
                @foreach($events as $event)
                @php
                    $isOngoing  = now()->between($event->event_start_date, $event->event_end_date);
                    $isUpcoming = now()->lt($event->event_start_date);
                    $filterAttr = $isOngoing ? 'ongoing' : ($isUpcoming ? 'upcoming' : 'ended');
                @endphp
                <div class="col-md-6 col-lg-4 event-item" data-status="{{ $filterAttr }}">
                    @include('event.partials.card-event', ['event' => $event])
                </div>
                @endforeach
            </div>

            <div id="emptyFilter" class="empty-state d-none">
                <i class="bi bi-search"></i>
                <h4 class="fw-bold text-dark mb-2">Tidak ada event di kategori ini</h4>
                <p class="text-muted">Coba lihat kategori lain.</p>
            </div>
        @endif

    </div>
</section>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.filter-tab').forEach(tab => {
    tab.addEventListener('click', function () {
        document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');

        const filter = this.dataset.filter;
        const items  = document.querySelectorAll('.event-item');
        let visible  = 0;

        items.forEach(item => {
            const show = filter === 'all' || item.dataset.status === filter;
            item.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        const emptyMsg = document.getElementById('emptyFilter');
        if (emptyMsg) emptyMsg.classList.toggle('d-none', visible > 0);
    });
});
</script>
@endpush
