@extends('layouts.app')

@section('title', 'Peserta & Sertifikat – ' . $event->event_name . ' | Flavory.id')
@section('meta_description', 'Cari dan unduh sertifikat keikutsertaan ' . $event->event_name . '. Cari berdasarkan nama atau nomor WhatsApp.')
@section('meta_og_url', url()->current())
@section('meta_image', secure_url('assets/event/' . $event->image))

@push('styles')
<style>
    :root {
        --primary-orange: #ff6a00;
        --dark-orange:    #e65f00;
        --light-orange:   #fff4ec;
    }

    /* ── Hero ── */
    .peserta-hero {
        padding: 120px 0 60px;
        background: linear-gradient(135deg, #ffffff 0%, var(--light-orange) 100%);
    }
    .peserta-hero-title {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1.35;
        letter-spacing: -.4px;
    }
    @media (max-width: 768px) { .peserta-hero-title { font-size: 1.5rem; } }

    /* ── Search Box ── */
    .search-wrap {
        position: relative;
        max-width: 540px;
        margin: 0 auto;
    }
    .search-wrap .search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--primary-orange);
        font-size: 1rem;
        pointer-events: none;
    }
    .search-wrap input {
        padding-left: 42px;
        border: 2px solid #e2e8f0;
        border-radius: 50px;
        font-size: .9rem;
        height: 48px;
        transition: border-color .25s, box-shadow .25s;
    }
    .search-wrap input:focus {
        outline: none;
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 4px rgba(255,106,0,.1);
    }
    .search-wrap .clear-btn {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        font-size: .85rem;
        display: none;
        padding: 4px;
    }
    .search-wrap .clear-btn:hover { color: var(--primary-orange); }

    /* ── Table ── */
    .peserta-table thead th {
        background: var(--light-orange);
        color: #64748b;
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .5px;
        text-transform: uppercase;
        border: none;
        padding: 14px 16px;
    }
    .peserta-table tbody td {
        vertical-align: middle;
        padding: 14px 16px;
        border-color: #f1f5f9;
        font-size: .88rem;
    }
    .peserta-table tbody tr:hover { background: #fff8f3; }

    .no-col     { width: 60px; text-align: center; }
    .cert-col   { width: 180px; text-align: center; }

    /* ── Tombol Download ── */
    .btn-download-cert {
        background: var(--primary-orange);
        color: #fff;
        border: none;
        border-radius: 50px;
        padding: 6px 16px;
        font-size: .78rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: background .2s, transform .2s;
    }
    .btn-download-cert:hover {
        background: var(--dark-orange);
        color: #fff;
        transform: translateY(-1px);
    }
    .badge-no-cert {
        background: #f1f5f9;
        color: #94a3b8;
        border-radius: 50px;
        padding: 5px 12px;
        font-size: .75rem;
        font-weight: 600;
    }

    /* ── Status bar ── */
    .search-status {
        font-size: .8rem;
        color: #94a3b8;
        min-height: 20px;
    }

    /* ── Empty / Skeleton ── */
    .empty-row td {
        text-align: center;
        padding: 60px 0;
        color: #94a3b8;
    }
    .skeleton-row td {
        padding: 14px 16px;
    }
    .skeleton-line {
        height: 14px;
        border-radius: 6px;
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 400% 100%;
        animation: shimmer 1.2s infinite;
    }
    @keyframes shimmer { 0% { background-position: 100% } 100% { background-position: -100% } }

    /* ── Back btn ── */
    .btn-back-orange {
        background: var(--light-orange);
        color: var(--primary-orange);
        border: 2px solid rgba(255,106,0,.2);
        border-radius: 50px;
        padding: 8px 20px;
        font-size: .85rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all .2s;
    }
    .btn-back-orange:hover {
        background: var(--primary-orange);
        color: #fff;
        border-color: var(--primary-orange);
    }
</style>
@endpush

@section('content')

{{-- ── Hero ── --}}
<header class="peserta-hero">
    <div class="container">
        <div class="mb-4">
            <a href="{{ route('event.index') }}" class="btn-back-orange">
                <i class="bi bi-arrow-left"></i> Kembali ke Event
            </a>
        </div>

        <div class="row align-items-center g-4">
            {{-- Info Event --}}
            <div class="col-lg-7">
                <span class="badge px-3 py-2 rounded-pill fw-bold border border-warning-subtle mb-3"
                      style="background: var(--light-orange); color: var(--primary-orange); font-size:.75rem;">
                    <i class="bi bi-award-fill me-1"></i> Sertifikat Peserta
                </span>
                <h1 class="peserta-hero-title mb-2">{{ $event->event_name }}</h1>
                <div class="d-flex flex-wrap gap-3 mt-3">
                    <span class="text-muted small">
                        <i class="bi bi-calendar3 me-1 text-orange" style="color:var(--primary-orange)"></i>
                        {{ \Carbon\Carbon::parse($event->event_start_date)->translatedFormat('d M Y') }}
                        @if($event->event_start_date !== $event->event_end_date)
                            &ndash; {{ \Carbon\Carbon::parse($event->event_end_date)->translatedFormat('d M Y') }}
                        @endif
                    </span>
                    <span class="text-muted small">
                        <i class="bi bi-people-fill me-1" style="color:var(--primary-orange)"></i>
                        {{ $participants->count() }} Peserta Terdaftar
                    </span>
                    @if($event->location)
                        <span class="text-muted small">
                            <i class="bi bi-geo-alt-fill me-1" style="color:var(--primary-orange)"></i>
                            {{ $event->location }}
                        </span>
                    @endif
                </div>
            </div>
            {{-- Poster --}}
            @if($event->image)
                <div class="col-lg-5 text-center text-lg-end">
                    <img src="{{ asset('assets/event/' . $event->image) }}"
                         alt="{{ $event->event_name }}"
                         class="rounded-4 shadow-sm"
                         style="max-height: 180px; max-width: 100%; object-fit: cover;"
                         onerror="this.parentElement.remove()">
                </div>
            @endif
        </div>

        {{-- Search Box --}}
        <div class="mt-5">
            <div class="search-wrap">
                <i class="bi bi-search search-icon"></i>
                <input type="text"
                       id="searchInput"
                       class="form-control w-100"
                       placeholder="Cari nama atau nomor WhatsApp peserta..."
                       autocomplete="off">
                <button class="clear-btn" id="clearBtn" title="Hapus pencarian">
                    <i class="bi bi-x-circle-fill"></i>
                </button>
            </div>
            <div class="text-center mt-2 search-status" id="searchStatus">
                Menampilkan <strong id="countShown">{{ $participants->count() }}</strong> peserta
            </div>
        </div>
    </div>
</header>

{{-- ── Tabel Peserta ── --}}
<section class="py-5" style="background: #f8fafc;">
    <div class="container py-2">
        <div class="card border-0 rounded-4 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table peserta-table mb-0" id="pesertaTable">
                    <thead>
                        <tr>
                            <th class="no-col">#</th>
                            <th>Nama Peserta</th>
                            <th class="cert-col">Sertifikat</th>
                        </tr>
                    </thead>
                    <tbody id="pesertaBody">
                        @forelse($participants as $i => $p)
                            <tr>
                                <td class="no-col text-secondary font-monospace">{{ $i + 1 }}</td>
                                <td class="fw-semibold text-dark">{{ $p->name }}</td>
                                <td class="cert-col">
                                    @if($p->certificat)
                                        <a href="{{ asset('assets/sertifikat/' . $p->certificat) }}"
                                           download
                                           class="btn-download-cert">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                    @else
                                        <span class="badge-no-cert">Belum tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="3">
                                    <i class="bi bi-people fs-2 d-block mb-2 opacity-25"></i>
                                    Belum ada peserta terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Catatan --}}
        <p class="text-center text-muted mt-4" style="font-size:.78rem;">
            <i class="bi bi-info-circle me-1"></i>
            Jika nama Anda terdaftar namun sertifikat belum tersedia, harap tunggu atau hubungi panitia.
        </p>
    </div>
</section>

@endsection

@push('scripts')
<script>
(function () {
    const searchInput = document.getElementById('searchInput');
    const clearBtn    = document.getElementById('clearBtn');
    const tbody       = document.getElementById('pesertaBody');
    const statusEl    = document.getElementById('searchStatus');
    const countEl     = document.getElementById('countShown');

    // URL endpoint AJAX
    const searchUrl = "{{ route('event.peserta.search', $event->slug ?? $event->uuid) }}";

    let debounceTimer = null;

    // ── Skeleton loader ──────────────────────────────────────────
    function showSkeleton() {
        let html = '';
        for (let i = 0; i < 5; i++) {
            html += `<tr class="skeleton-row">
                <td><div class="skeleton-line" style="width:30px"></div></td>
                <td><div class="skeleton-line" style="width:180px"></div></td>
                <td><div class="skeleton-line" style="width:100px;margin:0 auto"></div></td>
            </tr>`;
        }
        tbody.innerHTML = html;
    }

    // ── Render baris hasil AJAX ──────────────────────────────────
    function renderRows(participants) {
        if (participants.length === 0) {
            tbody.innerHTML = `
                <tr class="empty-row">
                    <td colspan="3">
                        <i class="bi bi-search fs-2 d-block mb-2 opacity-25"></i>
                        Tidak ada peserta yang cocok dengan pencarian Anda.
                    </td>
                </tr>`;
            countEl.textContent = '0';
            return;
        }

        let html = '';
        participants.forEach(p => {
            const certCell = p.certificat_url
                ? `<a href="${p.certificat_url}" download class="btn-download-cert">
                       <i class="bi bi-download"></i> Download
                   </a>`
                : `<span class="badge-no-cert">Belum tersedia</span>`;

            html += `<tr>
                <td class="no-col text-secondary font-monospace">${p.no}</td>
                <td class="fw-semibold text-dark">${escapeHtml(p.name)}</td>
                <td class="cert-col">${certCell}</td>
            </tr>`;
        });

        tbody.innerHTML = html;
        countEl.textContent = participants.length;
    }

    // ── Escape HTML sederhana ─────────────────────────────────────
    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    // ── Fetch AJAX ───────────────────────────────────────────────
    function doSearch(q) {
        showSkeleton();
        statusEl.innerHTML = '<span class="text-secondary">Mencari...</span>';

        fetch(`${searchUrl}?q=${encodeURIComponent(q)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            renderRows(data.participants);
            const total = data.participants.length;
            statusEl.innerHTML = q
                ? `Menampilkan <strong>${total}</strong> hasil untuk "<em>${escapeHtml(q)}</em>"`
                : `Menampilkan <strong>${total}</strong> peserta`;
        })
        .catch(() => {
            tbody.innerHTML = `<tr class="empty-row"><td colspan="3">Terjadi kesalahan. Silakan coba lagi.</td></tr>`;
            statusEl.innerHTML = '';
        });
    }

    // ── Event Listener: input ────────────────────────────────────
    searchInput.addEventListener('input', function () {
        const q = this.value.trim();
        clearBtn.style.display = q ? 'block' : 'none';

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => doSearch(q), 320);
    });

    // ── Clear button ─────────────────────────────────────────────
    clearBtn.addEventListener('click', function () {
        searchInput.value = '';
        clearBtn.style.display = 'none';
        doSearch('');
        searchInput.focus();
    });

})();
</script>
@endpush
