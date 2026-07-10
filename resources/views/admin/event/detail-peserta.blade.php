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

    .btn-cyber-outline { background: transparent; color: #94A3B8; border: 1px solid #475569; transition: 0.3s; }
    .btn-cyber-outline:hover { background: rgba(148, 163, 184, 0.1); color: #FFF; border-color: #94A3B8; }

    .form-control {
        background-color: rgba(15, 23, 42, 0.8) !important;
        border: 1px solid rgba(0, 229, 255, 0.2) !important;
        color: #FFF !important;
    }
    .form-control:focus {
        border-color: var(--accent-cyan) !important;
        box-shadow: 0 0 10px rgba(0, 229, 255, 0.3) !important;
    }
</style>

<div class="cyber-grid-bg"></div>

<div class="container-fluid position-relative z-1 pb-5">
    <div class="mb-4 d-flex align-items-center gap-3">
        <a href="{{ route('admin.event.kelola-peserta') }}" class="btn btn-cyber-info rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" title="Kembali ke Kelola Peserta">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h3 class="fw-bold mb-1" style="color: var(--accent-cyan);">
                <i class="fa-solid fa-users me-2"></i>Daftar Peserta: {{ $event->event_name }}
            </h3>
            <p class="text-secondary mb-0">Total: {{ $participants->count() }} Peserta terdaftar</p>
        </div>
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
                        <th style="width: 60px;">#</th>
                        <th>Nama Peserta</th>
                        <th>No. WhatsApp</th>
                        <th>Kategori</th>
                        <th>Nama Usaha</th>
                        <th>Instansi</th>
                        <th>Informasi</th>
                        <th class="text-center">Sertifikat</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($participants as $index => $participant)
                        <tr>
                            <td class="font-monospace text-secondary">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold text-white">{{ $participant->name }}</div>
                                <div class="text-secondary small">ID: {{ $participant->uuid }}</div>
                            </td>
                            <td>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $participant->wa) }}" target="_blank" class="text-decoration-none text-success hover-underline d-inline-flex align-items-center gap-2 fw-bold">
                                    <i class="fa-brands fa-whatsapp fs-5"></i> 
                                    <span class="text-white">{{ $participant->wa }}</span>
                                </a>
                            </td>
                            <td>
                                @php
                                    $categoryBadge = [
                                        'umum' => 'bg-secondary',
                                        'pelajar' => 'bg-primary',
                                        'pengusaha_kuliner' => 'bg-warning text-dark',
                                        'pengusaha_non_kuliner' => 'bg-info text-dark'
                                    ][$participant->participant_category] ?? 'bg-light text-dark';

                                    $categoryLabel = [
                                        'umum' => 'Umum',
                                        'pelajar' => 'Pelajar/Mahasiswa',
                                        'pengusaha_kuliner' => 'Pengusaha Kuliner',
                                        'pengusaha_non_kuliner' => 'Pengusaha Non-Kuliner'
                                    ][$participant->participant_category] ?? $participant->participant_category;
                                @endphp
                                <span class="badge {{ $categoryBadge }}">{{ $categoryLabel }}</span>
                            </td>
                            {{-- Nama Usaha --}}
                            <td>
                                <span class="{{ $participant->nama_usaha ? 'text-white fw-semibold' : 'text-secondary' }}">
                                    {{ $participant->nama_usaha ?? '-' }}
                                </span>
                            </td>

                            {{-- Instansi --}}
                            <td>
                                <span class="{{ $participant->nama_instansi ? 'text-white fw-semibold' : 'text-secondary' }}">
                                    {{ $participant->nama_instansi ?? '-' }}
                                </span>
                            </td>

                            {{-- Informasi --}}
                            <td>
                                <span class="{{ $participant->info ? 'text-white ' : 'text-secondary' }}">
                                    {{ $participant->info ?? '-' }}
                                </span>
                            </td>
                            <td class="text-center font-monospace">
                                @if($participant->certificat)
                                    <div class="d-flex align-items-center justify-content-center gap-3">
                                        <a href="{{ asset('storage/' . $participant->certificat) }}" target="_blank" class="text-info d-inline-flex align-items-center gap-1 text-decoration-none fw-bold" title="Lihat Sertifikat">
                                            <i class="fa-regular fa-file-image fs-4"></i>
                                            <span style="font-size: 0.8rem;">Lihat</span>
                                        </a>
                                        <button class="btn btn-sm btn-cyber-warning border-0 p-1" data-bs-toggle="modal" data-bs-target="#uploadCertificateModal{{ $participant->id }}" title="Ganti Sertifikat">
                                            <i class="fa-solid fa-arrows-rotate"></i>
                                        </button>
                                    </div>
                                @else
                                    <button class="btn btn-sm btn-cyber-info rounded-circle p-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#uploadCertificateModal{{ $participant->id }}"
                                            title="Upload Sertifikat"
                                            style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                @endif
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-cyber-outline py-1 px-3" data-bs-toggle="modal" data-bs-target="#uploadCertificateModal{{ $participant->id }}">
                                    {{ $participant->certificat ? 'Ganti Sertifikat' : 'Upload' }}
                                </button>
                            </td>
                        </tr>

                        <!-- Certificate Upload Modal -->
                        <div class="modal fade" id="uploadCertificateModal{{ $participant->id }}" tabindex="-1" aria-labelledby="uploadCertificateModalLabel{{ $participant->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content text-white" style="background: var(--bg-dark); border: 1px solid var(--accent-cyan); border-radius: 16px;">
                                    <div class="modal-header" style="border-bottom: 1px solid rgba(0, 229, 255, 0.1);">
                                        <h5 class="modal-title fw-bold" id="uploadCertificateModalLabel{{ $participant->id }}" style="color: var(--accent-cyan);">
                                            <i class="fa-solid fa-file-signature me-2"></i> Upload Sertifikat
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.event.upload-sertifikat', $participant->uuid) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body p-4">
                                            <div class="mb-3 text-center">
                                                <h6 class="fw-bold mb-1 text-white">{{ $participant->name }}</h6>
                                                <span class="text-secondary small font-monospace">{{ $participant->wa }}</span>
                                            </div>

                                            @if($participant->certificat)
                                                <div class="mb-4 text-center">
                                                    <div class="text-secondary mb-2 small">Sertifikat saat ini:</div>
                                                    <img src="{{ asset('storage/' . $participant->certificat) }}" alt="Sertifikat" class="rounded border shadow-sm" style="max-width: 100%; max-height: 150px; object-fit: cover; border-color: rgba(0, 229, 255, 0.2) !important;">
                                                </div>
                                            @endif

                                            <div class="mb-3">
                                                <label class="form-label text-info fw-bold">Pilih File Sertifikat</label>
                                                <input type="file" name="certificat" class="form-control" required>
                                                <div class="form-text text-secondary small mt-2">Dukung format gambar (JPEG, PNG, JPG) atau PDF. Maksimal 4MB.</div>
                                            </div>
                                        </div>
                                        <div class="modal-footer" style="border-top: 1px solid rgba(0, 229, 255, 0.1);">
                                            <button type="button" class="btn btn-cyber-outline" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-cyber-info px-4 fw-bold">Upload Sertifikat</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fa-solid fa-users-slash fs-2 mb-3 d-block text-secondary"></i>
                                <span class="text-secondary">Belum ada peserta yang mendaftar pada event ini.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .hover-underline:hover {
        text-decoration: underline !important;
    }
</style>
@endsection
