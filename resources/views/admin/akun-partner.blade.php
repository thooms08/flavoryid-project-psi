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

    .btn-cyber-outline { background: transparent; color: #94A3B8; border: 1px solid #475569; transition: 0.3s; }
    .btn-cyber-outline:hover { background: rgba(148, 163, 184, 0.1); color: #FFF; border-color: #94A3B8; }
</style>

<div class="cyber-grid-bg"></div>

<div class="container-fluid position-relative z-1 pb-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8 col-12">
            <h3 class="fw-bold" style="color: var(--accent-cyan);"><i class="fa-solid fa-user-shield me-2"></i>Manajemen Akun Partner</h3>
            <p class="text-secondary mb-md-0">Kelola akses dan buat link reset kata sandi untuk partner (Kasir).</p>
        </div>
        @if(count($partners) > 10)
        <div class="col-md-4 col-12 text-md-end text-start mt-2 mt-md-0">
            <button id="toggleRowsBtn" class="btn btn-cyber-info btn-sm fw-bold">
                <i class="fa-solid fa-eye me-1"></i> Lihat semua data
            </button>
        </div>
        @endif
    </div>

    <div class="cyber-card">
        <div class="table-responsive">
            <table class="table table-cyber mb-0">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Nama Manager</th>
                        <th>Status Password</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($partners as $index => $partner)
                        @php $hasPassword = !empty($partner->user->password); @endphp
                        <tr class="partner-row {{ $index >= 10 ? 'd-none' : '' }}">
                            <td class="fw-bold text-white">{{ $partner->user->username ?? '---' }}</td>
                            <td class="text-white">{{ $partner->nama_manager }}</td>
                            <td>
                                @if($hasPassword)
                                    <span class="badge bg-success px-3 py-2" style="letter-spacing: 1px;">ADA PASSWORD</span>
                                @else
                                    <span class="badge bg-danger px-3 py-2" style="letter-spacing: 1px;">BELUM ADA PASSWORD</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-cyber-info btn-sm fw-bold btn-share" 
                                    data-url="{{ route('admin.akun_partner.generate_link', $partner->uuid) }}">
                                    <i class="fa-solid fa-key me-1"></i> Buat Link Reset
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="fa-solid fa-users-slash fa-3x mb-3" style="color: rgba(0, 229, 255, 0.2);"></i>
                                <h5 class="text-white">Data tidak tersedia</h5>
                                <p class="text-secondary small">Belum ada akun partner kasir yang terdaftar.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="shareModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: var(--bg-dark); border: 1px solid var(--accent-cyan); box-shadow: 0 0 20px rgba(0,229,255,0.2);">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" style="color: var(--accent-cyan);">Link Reset Dibuat</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-secondary small mb-2">Salin link berikut dan berikan kepada partner. Link ini bersifat unik dan hanya berlaku sementara.</p>
                <div class="input-group">
                    <input type="text" id="resetLinkInput" class="form-control text-white" style="background: rgba(255,255,255,0.05); border-color: var(--accent-cyan);" readonly>
                    <button class="btn btn-outline-info" type="button" id="btnCopy">
                        <i class="fa-solid fa-copy"></i> Copy
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="alertModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content text-center" style="background: var(--bg-dark); border: 1px solid var(--accent-warning); box-shadow: 0 0 20px rgba(245,158,11,0.2);">
            <div class="modal-body py-4">
                <i class="fa-solid fa-triangle-exclamation fa-3x mb-3" style="color: var(--accent-warning);"></i>
                <h5 class="fw-bold text-white mb-2" id="alertTitle">Peringatan</h5>
                <p class="text-secondary small mb-4" id="alertMessage">Pesan error di sini.</p>
                <button type="button" class="btn btn-cyber-outline w-100 fw-bold" data-bs-dismiss="modal">MENGERTI</button>
            </div>
        </div>
    </div>
</div>
@endsection

@stack('scripts')
@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Toggle Data Rows
    const toggleBtn = document.getElementById('toggleRowsBtn');
    if (toggleBtn) {
        const rows = document.querySelectorAll('.partner-row');
        let showAll = false;

        toggleBtn.addEventListener('click', function() {
            showAll = !showAll;
            rows.forEach((row, index) => {
                if (index >= 10) {
                    if (showAll) {
                        row.classList.remove('d-none');
                    } else {
                        row.classList.add('d-none');
                    }
                }
            });

            if (showAll) {
                toggleBtn.innerHTML = '<i class="fa-solid fa-eye-slash me-1"></i> Tutup Sebagian Data';
            } else {
                toggleBtn.innerHTML = '<i class="fa-solid fa-eye me-1"></i> Lihat semua data';
            }
        });
    }
    
    // Fungsi Custom Alert Cyber
    function showCyberAlert(message, title = "Informasi") {
        document.getElementById('alertTitle').innerText = title;
        document.getElementById('alertMessage').innerText = message;
        new bootstrap.Modal(document.getElementById('alertModal')).show();
    }

    // Fungsi Request Link Reset via AJAX
    document.querySelectorAll('.btn-share').forEach(btn => {
        btn.addEventListener('click', async function() {
            const originalHtml = this.innerHTML;
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Memproses...';
            this.disabled = true;

            try {
                let response = await fetch(this.dataset.url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                let data = await response.json();

                if (data.success) {
                    document.getElementById('resetLinkInput').value = data.link;
                    new bootstrap.Modal(document.getElementById('shareModal')).show();
                } else {
                    showCyberAlert(data.message, "Gagal Generate Link");
                }
            } catch(e) {
                showCyberAlert('Terjadi kesalahan saat menghubungi server. Periksa koneksi internet Anda.', "Koneksi Terputus");
            }

            this.innerHTML = originalHtml;
            this.disabled = false;
        });
    });

    // Copy to Clipboard
    document.getElementById('btnCopy').addEventListener('click', function() {
        let input = document.getElementById('resetLinkInput');
        input.select();
        input.setSelectionRange(0, 99999); // Untuk perangkat mobile
        navigator.clipboard.writeText(input.value);
        
        let originalText = this.innerHTML;
        this.innerHTML = '<i class="fa-solid fa-check"></i> Disalin!';
        setTimeout(() => { this.innerHTML = originalText; }, 2000);
    });
});
</script>
@endpush