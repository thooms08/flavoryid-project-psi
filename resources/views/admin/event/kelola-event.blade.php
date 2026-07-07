@extends('admin.layouts.app')

@section('content')
<style>
    :root {
        --bg-dark: #0B1120;
        --accent-cyan: #00E5FF;
        --accent-blue: #3B82F6;
        --accent-warning: #F59E0B;
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
        padding: 1.5rem; position: relative;
    }

    .cyber-card::before {
        content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 3px;
        background: linear-gradient(90deg, var(--accent-cyan), var(--accent-blue));
    }

    .table-cyber {
        color: #F8FAFC;
        border-collapse: separate;
        border-spacing: 0 8px;
    }
    
    .table-cyber th {
        background: transparent; color: #94A3B8; font-size: 0.8rem;
        letter-spacing: 1px; border: none; text-transform: uppercase;
    }

    .table-cyber td {
        background: rgba(255, 255, 255, 0.03);
        border: none; vertical-align: middle; padding: 1rem;
    }

    .table-cyber tr:hover td { background: rgba(0, 229, 255, 0.05); }
    .table-cyber td:first-child { border-top-left-radius: 8px; border-bottom-left-radius: 8px; border-left: 2px solid transparent; }
    .table-cyber tr:hover td:first-child { border-left: 2px solid var(--accent-cyan); }
    .table-cyber td:last-child { border-top-right-radius: 8px; border-bottom-right-radius: 8px; }

    .btn-cyber-info { background: rgba(0, 229, 255, 0.1); color: var(--accent-cyan); border: 1px solid rgba(0, 229, 255, 0.4); transition: 0.3s; }
    .btn-cyber-info:hover { background: rgba(0, 229, 255, 0.3); color: #FFF; box-shadow: 0 0 10px rgba(0, 229, 255, 0.4); }

    .btn-cyber-warning { background: rgba(245, 158, 11, 0.1); color: var(--accent-warning); border: 1px solid rgba(245, 158, 11, 0.4); transition: 0.3s; }
    .btn-cyber-warning:hover { background: rgba(245, 158, 11, 0.3); color: #FFF; box-shadow: 0 0 10px rgba(245, 158, 11, 0.4); }

    .btn-cyber-danger { background: rgba(239, 68, 68, 0.1); color: #FCA5A5; border: 1px solid rgba(239, 68, 68, 0.4); transition: 0.3s; }
    .btn-cyber-danger:hover { background: rgba(239, 68, 68, 0.3); color: #FFF; box-shadow: 0 0 10px rgba(239, 68, 68, 0.4); }

    .form-control, .form-select {
        background-color: rgba(15, 23, 42, 0.8) !important;
        border: 1px solid rgba(0, 229, 255, 0.2) !important;
        color: #FFF !important;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--accent-cyan) !important;
        box-shadow: 0 0 10px rgba(0, 229, 255, 0.3) !important;
    }
</style>

<div class="cyber-grid-bg"></div>

<div class="container-fluid position-relative z-1 pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h3 class="fw-bold mb-1" style="color: var(--accent-cyan);"><i class="fa-solid fa-calendar-days me-2"></i>Kelola Event</h3>
            <p class="text-secondary mb-0">Kelola event, festival, seminar, dan aktivitas kuliner Flavory.id</p>
        </div>
        <button class="btn btn-cyber-info px-4 d-flex align-items-center gap-2 fw-bold" data-bs-toggle="modal" data-bs-target="#addEventModal">
            <i class="fa-solid fa-plus"></i> Tambah Event Baru
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 text-white bg-success-subtle mb-4" role="alert" style="border-left: 4px solid #10B981 !important; background-color: rgba(16, 185, 129, 0.1) !important;">
            <i class="fa-solid fa-circle-check text-success me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="cyber-card">
        <div class="table-responsive">
            <table class="table table-cyber mb-0">
                <thead>
                    <tr>
                        <th style="width: 100px;">Gambar</th>
                        <th>Nama Event</th>
                        <th>Waktu Pelaksanaan</th>
                        <th>URL / Slug</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td>
                                <img src="{{ asset('assets/event/' . $event->image) }}" alt="{{ $event->event_name }}" class="rounded" style="width: 80px; height: 50px; object-fit: cover; border: 1px solid rgba(0, 229, 255, 0.2);">
                            </td>
                            <td>
                                <div class="fw-bold text-white fs-6">{{ $event->event_name }}</div>
                                <div class="text-secondary text-truncate" style="max-width: 250px; font-size: 0.8rem;" title="{{ $event->description }}">
                                    {{ Str::limit($event->description, 60) }}
                                </div>
                            </td>
                            <td>
                                <div class="font-monospace text-white" style="font-size: 0.85rem;">
                                    <i class="fa-solid fa-calendar-day text-info me-1"></i> {{ \Carbon\Carbon::parse($event->event_start_date)->translatedFormat('d M Y') }}
                                    <br>
                                    <i class="fa-solid fa-arrow-right text-muted me-1" style="font-size: 0.75rem;"></i> {{ \Carbon\Carbon::parse($event->event_end_date)->translatedFormat('d M Y') }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-dark text-info border border-info font-monospace">{{ $event->url }}</span>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.event.toggle-status', $event->uuid) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm rounded px-3 py-1 font-monospace fw-bold {{ $event->status === 'active' ? 'bg-success bg-opacity-10 text-success border border-success' : 'bg-secondary bg-opacity-10 text-secondary border border-secondary' }}" style="font-size: 0.75rem;">
    {{ strtoupper($event->status) ?: 'STATUS KOSONG' }}
</button>
                                </form>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <!-- Edit Button -->
                                    <button class="btn btn-sm btn-cyber-warning" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editEventModal{{ $event->id }}"
                                            title="Edit Event">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.event.destroy', $event->uuid) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus event ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-cyber-danger" title="Hapus Event">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fa-regular fa-calendar-times fs-2 mb-3 d-block text-secondary"></i>
                                <span class="text-secondary">Belum ada data event tersedia. Silakan buat event baru.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Edit Event Modals (Placed at root level to prevent z-index/stacking context rendering bugs) -->
@foreach($events as $event)
    <div class="modal fade" id="editEventModal{{ $event->id }}" tabindex="-1" aria-labelledby="editEventModalLabel{{ $event->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content text-white" style="background: var(--bg-dark); border: 1px solid var(--accent-cyan); border-radius: 16px;">
                <div class="modal-header" style="border-bottom: 1px solid rgba(0, 229, 255, 0.1);">
                    <h5 class="modal-title fw-bold" id="editEventModalLabel{{ $event->id }}" style="color: var(--accent-cyan);"><i class="fa-solid fa-pen-to-square me-2"></i> Edit Event</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.event.update', $event->uuid) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label text-info fw-bold">Nama Event</label>
                                <input type="text" name="event_name" value="{{ $event->event_name }}" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-info fw-bold">Deskripsi Event</label>
                                <textarea name="description" rows="4" class="form-control" required>{{ $event->description }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-info fw-bold">Tanggal Mulai</label>
                                <input type="date" name="event_start_date" value="{{ $event->event_start_date }}" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-info fw-bold">Tanggal Selesai</label>
                                <input type="date" name="event_end_date" value="{{ $event->event_end_date }}" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-info fw-bold">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="active" {{ $event->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $event->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-info fw-bold">Poster / Banner Event</label>
                                <input type="file" name="image" class="form-control">
                                <div class="form-text text-secondary" style="font-size: 0.75rem;">Biarkan kosong jika tidak ingin mengganti gambar.</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-info fw-bold">Tautan Grup WA (WhatsApp Group Link)</label>
                                <input type="url" name="wa_group_link" value="{{ $event->wa_group_link }}" placeholder="Contoh: https://chat.whatsapp.com/..." class="form-control">
                            </div>
                            <div class="col-12 text-center mt-3">
                                <div class="text-secondary mb-2" style="font-size: 0.8rem;">Poster saat ini:</div>
                                <img src="{{ asset('storage/' . $event->image) }}" alt="Current Poster" class="rounded border" style="max-height: 150px; object-fit: cover; border-color: rgba(0, 229, 255, 0.2) !important;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid rgba(0, 229, 255, 0.1);">
                        <button type="button" class="btn btn-cyber-outline" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-cyber-warning px-4 fw-bold">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content text-white" style="background: var(--bg-dark); border: 1px solid var(--accent-cyan); border-radius: 16px;">
            <div class="modal-header" style="border-bottom: 1px solid rgba(0, 229, 255, 0.1);">
                <h5 class="modal-title fw-bold" id="addEventModalLabel" style="color: var(--accent-cyan);"><i class="fa-solid fa-calendar-plus me-2"></i> Tambah Event Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.event.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-info fw-bold">Nama Event</label>
                            <input type="text" name="event_name" placeholder="Contoh: Festival Kuliner Karawang 2026" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-info fw-bold">Deskripsi Event</label>
                            <textarea name="description" rows="4" placeholder="Detail deskripsi kegiatan, tempat, syarat, dan ketentuan..." class="form-control" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-info fw-bold">Tanggal Mulai</label>
                            <input type="date" name="event_start_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-info fw-bold">Tanggal Selesai</label>
                            <input type="date" name="event_end_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-info fw-bold">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                                                            <label class="form-label text-info fw-bold">Poster / Banner Event</label>
                                                            <input type="file" name="image" class="form-control" required>
                                                            <div class="form-text text-secondary" style="font-size: 0.75rem;">Maksimal file 2MB dengan tipe jpeg, png, jpg, gif.</div>
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="form-label text-info fw-bold">Tautan Grup WA (WhatsApp Group Link)</label>
                                                            <input type="url" name="wa_group_link" placeholder="Contoh: https://chat.whatsapp.com/..." class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(0, 229, 255, 0.1);">
                    <button type="button" class="btn btn-cyber-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-cyber-info px-4 fw-bold">Tambah Event</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
