{{-- resources/views/event/partials/card-event.blade.php --}}
@php
    $isOngoing  = now()->between($event->event_start_date, $event->event_end_date);
    $isUpcoming = now()->lt($event->event_start_date);
    $isEnded    = now()->gt($event->event_end_date);
    $isClosed   = $event->status === 'inactive'; // ditutup manual oleh admin

    // Badge: "Ditutup" prioritas tertinggi jika admin nonaktifkan,
    // lalu cek tanggal untuk Sedang Berlangsung / Segera Hadir / Sudah Selesai
    if ($isClosed && !$isEnded) {
        $badgeClass = 'bg-danger';
        $badgeText  = 'Ditutup';
    } elseif ($isOngoing) {
        $badgeClass = 'bg-success';
        $badgeText  = 'Sedang Berlangsung';
    } elseif ($isUpcoming) {
        $badgeClass = 'bg-primary';
        $badgeText  = 'Segera Hadir';
    } else {
        // $isEnded - tanggal memang sudah lewat
        $badgeClass = 'bg-secondary';
        $badgeText  = 'Sudah Selesai';
    }

    $peserta = $event->participants_count ?? $event->participants->count();

    // Tombol daftar: nonaktif jika event ditutup ATAU tanggal sudah selesai
    $canRegister = !$isClosed && !$isEnded;
@endphp

<article class="event-card h-100 d-flex flex-column">

    {{-- Gambar Event --}}
    <div class="event-card-img-wrap">
        @if($event->image)
            <img src="{{ asset('assets/event/' . $event->image) }}"
                 alt="{{ $event->event_name }}"
                 class="event-card-img"
                 loading="lazy"
                 onerror="this.src='https://placehold.co/640x360/fff4ec/ff6a00?text=Event+Flavory'">
        @else
            <div class="event-card-img-placeholder">
                <i class="bi bi-calendar-event"></i>
            </div>
        @endif
        <span class="event-status-badge badge {{ $badgeClass }}">
            <i class="bi bi-circle-fill me-1" style="font-size:.5rem;vertical-align:middle;"></i>
            {{ $badgeText }}
        </span>
    </div>

    {{-- Body --}}
    <div class="event-card-body flex-grow-1 d-flex flex-column">

        <h3 class="event-card-title">{{ $event->event_name }}</h3>

        <p class="event-card-desc">
            {{ Str::limit(strip_tags($event->description), 110) }}
        </p>

        <ul class="event-meta list-unstyled mt-auto mb-3">
            <li>
                <i class="bi bi-calendar3 text-orange"></i>
                <span>
                    {{ \Carbon\Carbon::parse($event->event_start_date)->translatedFormat('d M Y') }}
                    @if($event->event_start_date !== $event->event_end_date)
                        &ndash; {{ \Carbon\Carbon::parse($event->event_end_date)->translatedFormat('d M Y') }}
                    @endif
                </span>
            </li>
            <li>
                <i class="bi bi-people-fill text-orange"></i>
                <span>{{ $peserta }} Peserta Terdaftar</span>
            </li>
        </ul>

        @if($canRegister)
            <a href="{{ route('event.show', $event->slug ?? $event->uuid) }}"
               class="btn btn-orange-event w-100">
                <i class="bi bi-pencil-square me-1"></i> Daftar Sekarang
            </a>
        @elseif($isClosed && !$isEnded)
            <button class="btn btn-secondary-event w-100" disabled>
                <i class="bi bi-x-circle me-1"></i> Event Telah Ditutup
            </button>
        @else
            {{-- Event benar-benar sudah selesai (tanggal lewat) --}}
            <a href="{{ route('event.peserta', $event->slug ?? $event->uuid) }}"
               class="btn btn-orange-event w-100">
                <i class="bi bi-award-fill me-1"></i> Download Sertifikat
            </a>
        @endif
    </div>
</article>
