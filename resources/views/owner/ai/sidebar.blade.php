

<aside class="ai-sidebar" id="aiSidebar">

    {{-- ── Brand ── --}}
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">
            {{-- Wajah robot: markup SVG diisi oleh JS (hydrateFaces) di index.blade.php --}}
            <span class="fa-face" data-global data-fa-hydrate data-exp="smile"></span>
        </div>
        <div class="sidebar-brand-text">
            <span class="sidebar-brand-name">FlavoryAI</span>
            <span class="sidebar-brand-sub">Consultant</span>
        </div>
        {{-- Tombol tutup sidebar (mobile: tutup panel, desktop: collapse) --}}
        <button type="button" class="sidebar-close-btn" id="btnCloseSidebar" aria-label="Tutup sidebar">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    {{-- ── Tombol Chat Baru ── --}}
    <div class="sidebar-new-chat-wrap">
        <button type="button" class="btn-new-chat" id="btnNewChat">
            <i class="bi bi-plus-lg"></i>
            <span>Chat Baru</span>
        </button>
    </div>

    {{-- ── Daftar Riwayat ── --}}
    <div class="sidebar-history-label">Riwayat Obrolan</div>

    <div class="sidebar-sessions" id="sidebarSessions">
        @forelse($sessions as $sesi)
            <div class="session-item {{ $activeSession && $activeSession->uuid === $sesi->uuid ? 'active' : '' }}"
                 data-uuid="{{ $sesi->uuid }}"
                 data-title="{{ $sesi->title }}"
                 role="button"
                 tabindex="0">
                <div class="session-item-body">
                    <i class="bi bi-chat-left-text session-icon"></i>
                    <span class="session-title">{{ $sesi->title }}</span>
                </div>
                <button type="button"
                        class="session-delete-btn"
                        data-uuid="{{ $sesi->uuid }}"
                        title="Hapus sesi ini"
                        aria-label="Hapus">
                    <i class="bi bi-trash3"></i>
                </button>
            </div>
        @empty
            <div class="sessions-empty" id="sessionsEmpty">
                <i class="bi bi-chat-square-dots"></i>
                <span>Belum ada riwayat chat</span>
            </div>
        @endforelse
    </div>

    {{-- ── Footer sidebar ── --}}
    <div class="sidebar-footer">
        <a href="{{ route('owner.dashboard') }}" class="sidebar-back-btn">
            <i class="bi bi-arrow-left-circle"></i>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>

</aside>