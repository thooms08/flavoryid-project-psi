<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kelola Note Struk - Flavory.id</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('layouts.favicon')
    <script src="{{ asset('js/loading.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
</head>
<style>
    :root {
        --primary-orange: #fd7e14;
        --soft-orange: #fff4e6;
        --dark-orange: #e86c00;
        --bg-body: #fff7ed;
    }

    body {
        background-color: var(--bg-body);
        font-family: 'Poppins', sans-serif;
    }

    .note-card {
        background: white;
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,0.05);
        border-left: 6px solid #ccc; /* Default grey */
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        transition: all 0.3s ease;
    }

    .note-card.active-note {
        border-left-color: var(--primary-orange);
        box-shadow: 0 8px 25px rgba(253, 126, 20, 0.15);
    }

    .note-card:hover {
        transform: translateY(-3px);
    }

    .badge-aktif {
        background-color: #d1e7dd;
        color: #0f5132;
    }

    .badge-nonaktif {
        background-color: #e2e3e5;
        color: #41464b;
    }

    .btn-orange {
        background-color: var(--primary-orange);
        color: white;
        font-weight: 600;
    }
    .btn-orange:hover {
        background-color: var(--dark-orange);
        color: white;
    }

    /* Karakter counter style */
    .char-counter {
        font-size: 0.75rem;
        font-weight: 600;
        transition: color 0.2s;
    }
    .char-counter.limit { color: #dc3545; }
</style>
<body>

<div class="container-fluid px-3 px-md-5 py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 border-bottom pb-3">
        <div class="d-flex align-items-center gap-3">
        <a href="{{ route('kasir.dashboard') }}" class="btn btn-light border-warning-subtle text-secondary rounded-4 d-flex align-items-center justify-content-center hover-scale" style="width: 48px; height: 48px;">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
            <h1 class="h4 mb-0 fw-black text-uppercase" style="font-weight: 900; color: #444;">Catatan Struk</h1>
        </div>
        <button class="btn btn-orange rounded-pill px-4 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addNoteModal">
            <i class="bi bi-plus-lg me-2"></i> Tambah Catatan
        </button>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-3">
        @forelse ($notes as $note)
        <div class="col-12 col-md-6 col-lg-4">
            <div class="note-card p-3 {{ $note->status === 'aktif' ? 'active-note' : '' }}">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge rounded-pill {{ $note->status === 'aktif' ? 'badge-aktif' : 'badge-nonaktif' }} px-3 py-2 small fw-bold">
                        <i class="bi {{ $note->status === 'aktif' ? 'bi-check-circle-fill' : 'bi-dash-circle-fill' }} me-1"></i> 
                        {{ strtoupper($note->status) }}
                    </span>
                    
                    <form action="{{ route('kasir.notes.toggle', $note->id) }}" method="POST">
                        @csrf
                        <div class="form-check form-switch mb-0" style="transform: scale(1.2); cursor: pointer;">
                            <input class="form-check-input mt-0 shadow-sm toggle-status-btn" type="checkbox" role="switch" onchange="this.form.submit()" {{ $note->status === 'aktif' ? 'checked' : '' }} style="cursor: pointer;">
                        </div>
                    </form>
                </div>

                <div class="mt-3 mb-4">
                    <p class="mb-0 fw-medium text-dark" style="font-size: 0.95rem; line-height: 1.5; word-wrap: break-word;">
                        "{{ $note->note }}"
                    </p>
                </div>

                <div class="d-flex justify-content-between align-items-center border-top pt-3">
                    <span class="text-muted" style="font-size: 0.75rem;">
                        <i class="bi bi-clock me-1"></i> {{ $note->created_at->diffForHumans() }}
                    </span>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-light border text-primary" data-bs-toggle="modal" data-bs-target="#editNoteModal{{ $note->id }}">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-sm btn-light border text-danger" onclick="confirmDelete({{ $note->id }})">
                            <i class="bi bi-trash"></i>
                        </button>
                        
                        <form id="delete-form-{{ $note->id }}" action="{{ route('kasir.notes.destroy', $note->id) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editNoteModal{{ $note->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold">Edit Catatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('kasir.notes.update', $note->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="form-label small fw-bold text-muted">Isi Catatan Struk (Maks. 100 Karakter)</label>
                                <textarea class="form-control bg-light border-0 edit-note-input" name="note" rows="3" maxlength="100" required placeholder="Contoh: WiFi: FLAVORY2026">{{ $note->note }}</textarea>
                                <div class="text-end mt-1 char-counter text-muted"><span>{{ strlen($note->note) }}</span>/100</div>
                            </div>
                        </div>
                        <div class="modal-footer border-top-0 pt-0">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-orange px-4">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="d-inline-flex justify-content-center align-items-center rounded-circle bg-white shadow-sm mb-3" style="width: 80px; height: 80px;">
                <i class="bi bi-receipt fs-1 text-muted"></i>
            </div>
            <h5 class="fw-bold text-dark">Belum ada catatan struk</h5>
            <p class="text-muted small">Tambahkan catatan khusus seperti password WiFi atau ucapan terima kasih.</p>
        </div>
        @endforelse
    </div>
</div>

<div class="modal fade" id="addNoteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Catatan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('kasir.notes.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label small fw-bold text-muted">Isi Catatan Struk (Maks. 100 Karakter)</label>
                        <textarea class="form-control bg-light border-0" id="add-note-input" name="note" rows="3" maxlength="100" required placeholder="Contoh: Terima kasih sudah mampir! WiFi: free2026"></textarea>
                        <div class="text-end mt-1 char-counter text-muted" id="add-char-counter"><span>0</span>/100</div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-orange px-4">Simpan Catatan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Alert Sukses
    @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 3000
        });
    @endif

    // Penghitung Karakter untuk Tambah Note
    const addInput = document.getElementById('add-note-input');
    const addCounter = document.getElementById('add-char-counter').querySelector('span');
    
    addInput.addEventListener('input', function() {
        const len = this.value.length;
        addCounter.textContent = len;
        if(len >= 100) {
            this.nextElementSibling.classList.add('limit');
        } else {
            this.nextElementSibling.classList.remove('limit');
        }
    });

    // Penghitung Karakter untuk Edit Note
    const editInputs = document.querySelectorAll('.edit-note-input');
    editInputs.forEach(input => {
        input.addEventListener('input', function() {
            const len = this.value.length;
            this.nextElementSibling.querySelector('span').textContent = len;
            if(len >= 100) {
                this.nextElementSibling.classList.add('limit');
            } else {
                this.nextElementSibling.classList.remove('limit');
            }
        });
    });
});

// Konfirmasi Hapus Data
function confirmDelete(id) {
    Swal.fire({
        title: 'Hapus Catatan?',
        text: "Catatan yang dihapus tidak bisa dikembalikan.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
</body>
</html>