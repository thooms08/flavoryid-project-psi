<div class="col-md-6 col-lg-4 mb-4">
    <div class="card h-100 border-0 text-white position-relative" style="background: rgba(19, 32, 64, 0.7); backdrop-filter: blur(16px); border: 1px solid rgba(0, 229, 255, 0.15) !important; border-radius: 16px; transition: all 0.3s ease; overflow: hidden;">
        <!-- Banner Image -->
        <div style="position: relative; height: 160px; overflow: hidden; border-bottom: 1px solid rgba(0, 229, 255, 0.1);">
            <img src="{{ asset('assets/event/' . $event->image) }}" alt="{{ $event->event_name }}" class="w-100 h-100" style="object-fit: cover;">
            <div style="position: absolute; top: 12px; right: 12px;">
                <span class="badge {{ $event->status === 'active' ? 'bg-success text-white' : 'bg-secondary text-white' }} font-monospace text-uppercase" style="box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);">
                    {{ $event->status }}
                </span>
            </div>
        </div>

        <!-- Card Body -->
        <div class="card-body d-flex flex-column p-4">
            <h5 class="fw-bold mb-2 text-info">{{ $event->event_name }}</h5>
            
            <p class="text-secondary mb-3 small" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; height: 54px;">
                {{ $event->description }}
            </p>

            <div class="mt-auto mb-3 pt-3 font-monospace small border-top" style="border-color: rgba(0, 229, 255, 0.1) !important;">
                <div class="d-flex align-items-center justify-content-between text-muted mb-1">
                    <span><i class="fa-solid fa-calendar-day text-info me-1"></i> Mulai:</span>
                    <span class="text-white">{{ \Carbon\Carbon::parse($event->event_start_date)->translatedFormat('d M Y') }}</span>
                </div>
                <div class="d-flex align-items-center justify-content-between text-muted">
                    <span><i class="fa-solid fa-flag-checkered text-warning me-1"></i> Selesai:</span>
                    <span class="text-white">{{ \Carbon\Carbon::parse($event->event_end_date)->translatedFormat('d M Y') }}</span>
                </div>
            </div>

            <!-- Card Actions -->
            <div class="d-flex align-items-center justify-content-between gap-2 mt-2">
                <!-- Action Redirect to Detail -->
                <a href="{{ route('admin.event.data-peserta', $event->uuid) }}" class="btn btn-outline-info flex-grow-1 py-2 font-monospace d-flex align-items-center justify-content-center gap-2" style="border-radius: 8px; font-size: 0.85rem;">
                    <i class="fa-solid fa-users-viewfinder"></i> Kelola Peserta
                </a>

                <!-- Toggle Status Button -->
                <form action="{{ route('admin.event.toggle-status', $event->uuid) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn {{ $event->status === 'active' ? 'btn-outline-danger' : 'btn-outline-success' }} py-2 px-3" 
                            title="{{ $event->status === 'active' ? 'Nonaktifkan Event' : 'Aktifkan Event' }}" 
                            style="border-radius: 8px; font-size: 0.85rem;">
                        <i class="fa-solid {{ $event->status === 'active' ? 'fa-ban' : 'fa-check' }}"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
