<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Order - Flavory</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="{{ asset('js/loading.js') }}"></script>
    
    <style>
        :root { 
            --primary-orange: #fd7e14; 
            --dark-orange: #e86c00; 
            --soft-orange: #fff4e6;
            --gray-bg: #f8f9fa;
        }

        body { 
            font-family: 'Poppins', sans-serif; 
            background: #fdfdfd; 
            color: #333;
            margin: 0;
            padding: 0;
            height: 100vh;
        }

        .card-selected {
            border: 2px solid var(--primary-orange) !important;
            box-shadow: 0 0 20px rgba(253, 126, 20, 0.4) !important;
            transform: translateY(-5px);
        }

        #searchMenu:focus{
            border: 2px solid var(--primary-orange) !important;
            box-shadow: 0 0 20px rgba(253, 126, 20, 0.4) !important;
        }

        .menu-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-outline-danger:hover {
            background-color: #fff5f5;
            color: #dc3545;
            transform: scale(1.1);
            transition: 0.2s;
        }

        .cart-item {
            transition: all 0.3s ease;
        }

        .form-control-custom {
            background-color: #ffffff !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 12px !important;
            padding: 10px 15px;
            font-size: 0.9rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05) !important; 
            transition: all 0.2s ease-in-out !important;
        }

        .form-control-custom:focus {
            border-color: var(--primary-orange) !important;
            box-shadow: 0 0 0 0.25rem rgba(253, 126, 20, 0.2), 0 8px 15px rgba(253, 126, 20, 0.1) !important;
            background-color: #fff !important;
            outline: 0;
        }

        .form-label-custom {
            font-size: 0.8rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
            display: block;
        }

        /* Input Group Custom untuk Diskon */
        .input-group-custom .form-control-custom {
            border-radius: 12px;
        }
        .input-group-custom input {
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }
        .input-group-custom select {
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            background-color: #f8f9fa !important;
            font-weight: bold;
        }

        @media (min-width: 992px) {
            .main-container {
                display: flex;
                height: 100vh;
                overflow: hidden;
            }
            .menu-wrapper-section {
                flex: 1;
                overflow-y: auto;
                padding: 30px;
            }
            .sidebar-order {
                width: 480px; 
                background: white;
                border-left: 1px solid #edf2f7;
                display: flex;
                flex-direction: column;
                height: 100vh;
                position: sticky;
                top: 0;
                box-shadow: -10px 0 30px rgba(0,0,0,0.02);
            }
            .mobile-bottom-bar, .mobile-handle { display: none !important; }
        }

        .order-footer {
            position: sticky;
            bottom: 0;
            background: white;
            z-index: 100;
            padding: 15px;
            border-top: 1px solid #eee;
            max-height: 60vh; 
            display: flex;
            flex-direction: column;
        }

        .form-order-container {
            overflow-y: auto;
            overflow-x: hidden;
            padding-right: 8px;
            margin-bottom: 10px;
        }

        .form-order-container::-webkit-scrollbar { width: 6px; }
        .form-order-container::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }

        @media (max-height: 700px) {
            .form-order-container { max-height: 180px; }
        }

        .form-label-custom {
            font-size: 0.75rem; 
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 4px;
        }

        .form-control-custom {
            padding: 8px 12px; 
            font-size: 0.85rem;
        }

        #cart-list {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
            min-height: 150px; 
        }

        .btn-orange {
            padding: 12px;
            font-size: 1rem;
            background: var(--primary-orange);
            color: white;
            border: none;
            border-radius: 15px;
            font-weight: 600;
            width: 100%;
            transition: 0.3s;
        }
        .btn-orange:hover { background: var(--dark-orange); transform: translateY(-2px); }

        @media (max-width: 991.98px) {
            .main-container { padding: 15px; }
            .menu-wrapper-section { padding-bottom: 180px; }
            .sidebar-order {
                position: fixed;
                bottom: -100%;
                left: 0;
                right: 0;
                background: white;
                z-index: 1050;
                border-radius: 25px 25px 0 0;
                box-shadow: 0 -10px 40px rgba(0,0,0,0.15);
                transition: bottom 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                max-height: 90vh;
                display: flex;
                flex-direction: column;
            }
            .sidebar-order.active { bottom: 0; }
            .sidebar-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.5);
                backdrop-filter: blur(4px);
                display: none;
                z-index: 1040;
            }
            .sidebar-overlay.active { display: block; }
            .mobile-handle {
                width: 50px;
                height: 5px;
                background: #dee2e6;
                border-radius: 10px;
                margin: 12px auto;
                cursor: pointer;
            }
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        @media (min-width: 768px) { .menu-grid { grid-template-columns: repeat(4, 1fr); } }
        @media (min-width: 1200px) { .menu-grid { grid-template-columns: repeat(5, 1fr); } }

       
        .menu-wrapper {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .menu-card {
            border: none;
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.04);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;           /* Biar konten di dalam card bisa diatur */
            flex-direction: column;  /* Baris ke bawah */
            flex: 1;                 /* Paksa card mengisi sisa ruang wrapper penuh */
            cursor: pointer;
        }
        .menu-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(253, 126, 20, 0.1); }

        .img-container {
            aspect-ratio: 1/1;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        .img-container img { width: 100%; height: 100%; object-fit: cover; }
        .no-image {
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #fdfbfb 0%, #ebedee 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .no-image i { font-size: 2.5rem; color: #dee2e6; }

        .card-habis { cursor: not-allowed !important; filter: grayscale(0.8); }
        .overlay-habis {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.7);
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(2px);
        }
        .badge-habis {
            background: #dc3545;
            color: white;
            padding: 8px 16px;
            border-radius: 12px;
            font-weight: 800;
            font-size: 0.8rem;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }
        .stok-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(0,0,0,0.6);
            color: white;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            z-index: 5;
        }

        .cart-item {
            display: flex;
            align-items: center;
            background: var(--gray-bg);
            padding: 12px;
            border-radius: 15px;
            margin-bottom: 10px;
        }

        .mobile-bottom-bar {
            position: fixed;
            bottom: 20px;
            left: 15px;
            right: 15px;
            background: white;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            z-index: 1000;
            cursor: pointer;
            border: 1px solid var(--soft-orange);
        }

        .search-container {
            margin-bottom: 25px;
            position: relative;
        }
        .search-container input {
            border-radius: 50px;
            padding-left: 45px;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .search-container i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
        }

        /* === CARD TAMBAH MENU CEPAT === */
        .card-tambah-menu {
            border: 2.5px dashed #ffc890 !important;
            border-radius: 20px;
            background: #fffaf5;
            box-shadow: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            height: 100%;          /* ikuti tinggi menu-wrapper seperti menu-card */
            min-height: 140px;     /* fallback jika row hanya berisi card ini */
            transition: all 0.25s ease;
            padding: 16px 8px;
            gap: 8px;
        }
        .card-tambah-menu:hover {
            border-color: var(--primary-orange) !important;
            background: #fff4e6;
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(253, 126, 20, 0.12) !important;
        }
        .card-tambah-menu:hover .btn-tambah-plus {
            background: var(--primary-orange);
            color: #fff;
        }
        .btn-tambah-plus {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 2px solid var(--primary-orange);
            background: #fff;
            color: var(--primary-orange);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            font-weight: 700;
            transition: all 0.25s ease;
            flex-shrink: 0;
        }
        .card-tambah-menu span {
            font-size: 0.72rem;
            font-weight: 600;
            color: #fd7e14;
            text-align: center;
            line-height: 1.3;
        }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleCart()"></div>

<div class="main-container">
    <div class="menu-wrapper-section">
        <div class="search-container">
            <i class="bi bi-search"></i>
            <input type="text" id="searchMenu" class="form-control form-control-lg" placeholder="Cari menu favorit...">
        </div>

        <div class="menu-grid" id="menuContainer">
            {{-- Card Tambah Menu Cepat --}}
            <div class="menu-wrapper" data-name="">
                <div class="card-tambah-menu" onclick="openModalTambahMenu()" title="Tambah menu baru">
                    <div class="btn-tambah-plus">
                        <i class="bi bi-plus"></i>
                    </div>
                    <span>Tambah<br>Menu Baru</span>
                </div>
            </div>

            @forelse($menus as $menu)
               @php 
                    $isHabis = ($menu->stok_menu !== null && $menu->stok_menu <= 0) || $menu->status_menu == 'habis'; 
                    $hasVariants = $menu->variants && $menu->variants->count() > 0;
                @endphp
                <div class="menu-wrapper" data-name="{{ strtolower($menu->nama_menu) }}">
                   <div class="card menu-card {{ $isHabis ? 'card-habis' : '' }}" id="menu-card-{{ $menu->id }}" 
                         onclick="{{ $isHabis ? '' : ($hasVariants ? 'openVariantModal('.json_encode($menu).')' : 'addToCart('.json_encode($menu).')') }}">
                        
                        @if($isHabis)
                            <div class="overlay-habis">
                                <span class="badge-habis">MENU HABIS</span>
                            </div>
                        @endif

                        <div class="img-container">
                            <span class="stok-badge"><i class="bi bi-box me-1"></i>{{ $menu->stok_menu ?? '∞' }}</span>
                            @if($menu->gambar)
                                <img src="{{ asset('assets/menus/' . $menu->gambar) }}" onerror="this.onerror=null; this.src=''; this.parentElement.innerHTML='<div class=\'no-image\'><i class=\'bi bi-image\'></i></div>'">\
                            @else
                                <div class="no-image"><i class="bi bi-image"></i></div>
                            @endif
                        </div>

                        <div class="p-3 d-flex flex-column" style="flex: 1;">
                            <h6 class="fw-bold mb-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; white-space: normal;">
                                {{ $menu->nama_menu }}
                            </h6>
                            
                            @if($hasVariants)
                                <span class="text-orange mb-1" style="font-size: 0.75rem;">({{ $menu->variants->count() }} Varian)</span>
                            @endif
                            
                            <span class="fw-bold text-orange mt-auto fs-6" style="color: var(--primary-orange)">
                                Rp {{ number_format($menu->harga, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Menu belum tersedia.</p>
                </div>
            @endforelse
        </div>
    </div>

   <div class="sidebar-order" id="sidebarOrder">
    <div class="mobile-handle" onclick="toggleCart()"></div>
    
    <div class="px-4 pt-3 d-flex justify-content-between align-items-center border-bottom pb-3" style="flex-shrink: 0;">
        <h5 class="fw-bold mb-0">Detail Pesanan</h5>
        <button class="btn btn-sm d-lg-none" onclick="toggleCart()"><i class="bi bi-x-lg"></i></button>
    </div>

    <form id="orderForm" class="d-flex flex-column" style="flex: 1; overflow: hidden;">
        
        <div style="flex: 1; overflow-y: auto; padding: 15px;">
            
            <div id="cart-list" style="min-height: 100px;">
                <div class="text-center py-5 text-muted" id="empty-msg">
                    <i class="bi bi-cart3 display-4 opacity-25"></i>
                    <p class="small mt-2">Keranjang kosong</p>
                </div>
            </div>

            <div class="form-order-container" style="margin-top: 10px; border-top: 2px dashed #eee; padding-top: 15px;">
                <div class="row g-2"> 
                    @if($pengaturan->nama)
                    <div class="col-12 mb-3">
                        <label class="form-label-custom">Nama Pelanggan *</label>
                        <input type="text" id="cust_name" class="form-control form-control-custom" placeholder="Masukkan nama..." required>
                    </div>
                    @endif

                    @if($pengaturan->diskon)
                    <div class="col-12 mb-3">
                        <label class="form-label-custom">Diskon</label>
                        <div class="input-group input-group-custom">
                            <input type="number" id="cust_discount" class="form-control form-control-custom" placeholder="0" min="0">
                            <select id="cust_discount_type" class="form-select form-control-custom" style="max-width: 80px;">
                                <option value="Rp">Rp</option>
                                <option value="%">%</option>
                            </select>
                        </div>
                    </div>
                    @endif

                    @if($pengaturan->voucher)
                    <div class="col-12 mb-3">
                        <label class="form-label-custom">Kode Voucher</label>
                        <input type="text" id="cust_voucher" class="form-control form-control-custom"
                            placeholder="Ketik 6 digit kode" maxlength="6"
                            autocomplete="off" style="text-transform:uppercase;letter-spacing:3px;font-family:'Courier New',monospace;font-weight:700;font-size:1.05rem;">
                        <div id="voucher-alert" class="mt-2" style="font-size:0.82rem;display:none;"></div>
                    </div>
                    @endif

                    @if($pengaturan->meja)
                    <div class="col-6 mb-3">
                        <label class="form-label-custom">No. Meja</label>
                        <input type="text" id="cust_table" class="form-control form-control-custom" placeholder="01">
                    </div>
                    @endif

                    @if($pengaturan->whatsapp)
                    <div class="col-6 mb-3">
                        <label class="form-label-custom">WhatsApp</label>
                        <input type="number" id="cust_wa" class="form-control form-control-custom" placeholder="08xxx">
                    </div>
                    @endif

                    @if($pengaturan->keterangan)
                    <div class="col-12 mb-3">
                        <label class="form-label-custom">Catatan Pesanan</label>
                        <textarea id="cust_notes" class="form-control form-control-custom" rows="2" placeholder="Contoh: Pedas, tanpa bawang..." style="resize: none;"></textarea>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="order-footer" style="flex-shrink: 0; padding: 15px; background: white; border-top: 1px solid #eee; box-shadow: 0 -10px 20px rgba(0,0,0,0.03); z-index: 100;">
            <div class="mb-3">
                @if($pengaturan->diskon)
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-muted small">Total Harga</span>
                    <span class="fw-bold text-dark small" id="subtotal-view">Rp 0</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-1 pb-1 border-bottom">
                    <span class="text-muted small">Diskon <span id="discount-label-view" class="text-danger fw-bold"></span></span>
                    <span class="fw-bold text-danger small" id="discount-view">- Rp 0</span>
                </div>
                @endif
                @if($pengaturan->voucher)
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom" id="voucher-summary-row" style="display:none!important;">
                    <span class="text-muted small">Voucher <span id="voucher-kode-view" class="fw-bold text-success" style="font-family:'Courier New',monospace;letter-spacing:1px;"></span></span>
                    <span class="fw-bold text-success small" id="voucher-view">- Rp 0</span>
                </div>
                @endif
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-muted small">TOTAL BAYAR</span>
                    <span class="fs-5 fw-bold text-orange" id="total-view" style="color: var(--primary-orange)">Rp 0</span>
                </div>
            </div>

            <button type="button" class="btn-orange shadow w-100 py-3" onclick="saveOrder()" id="btnSubmitOrder">
                <i class="bi bi-check2-circle me-2"></i>
                PROSES SEKARANG
            </button>
        </div>
    </form>
</div>
</div>
<div class="mobile-bottom-bar" onclick="toggleCart()">
    <div>
        <span class="badge bg-orange rounded-pill me-2" id="mobile-count" style="background: var(--primary-orange)">0</span>
        <span class="small fw-bold me-1">Item Pesanan</span>
        <i class="bi bi-chevron-up" style="color: var(--primary-orange)"></i>
    </div>
    <div class="fw-bold text-orange" id="mobile-total" style="color: var(--primary-orange)">Rp 0</div>
</div>

<div class="modal fade" id="variantModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold" id="variantModalTitle">Pilih Varian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4" id="variantModalBody"></div>
            <div class="modal-footer border-top p-3">
                <button type="button" class="btn btn-orange w-100 py-3" onclick="submitVariantSelection()">
                    <i class="bi bi-cart-plus me-2"></i>Tambah ke Pesanan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Menu Cepat --}}
<div class="modal fade" id="modalTambahMenu" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-bottom px-4 pt-4 pb-3">
                <div>
                    <h5 class="modal-title fw-bold mb-0">
                        <i class="bi bi-plus-circle-fill me-2" style="color: var(--primary-orange)"></i>
                        Tambah Menu Baru
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Tambah cepat. Untuk detail lengkap, atur di halaman menu.</p>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <form id="formTambahMenuCepat" onsubmit="return false;">
                    <div class="mb-3">
                        <label class="form-label-custom">Nama Menu <span class="text-danger">*</span></label>
                        <input type="text" id="inputNamaMenu" class="form-control form-control-custom"
                               placeholder="Contoh: Es Teh Manis" maxlength="255" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" id="inputHargaMenu" class="form-control form-control-custom"
                               placeholder="Contoh: 10000" min="0" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top px-4 pb-4 pt-3 gap-2 flex-column flex-sm-row">
                <button type="button" class="btn w-100 py-2 fw-semibold order-2 order-sm-1"
                        style="border: 1.5px solid var(--primary-orange); color: var(--primary-orange); border-radius: 12px; background: #fff;"
                        onclick="window.parent.postMessage({ type: 'GOTO_MENU' }, '*')">
                    <i class="bi bi-sliders me-1"></i> Atur Detail
                </button>
                <button type="button" class="btn btn-orange w-100 py-2 fw-bold order-1 order-sm-2"
                        style="border-radius: 12px;"
                        id="btnSimpanMenuCepat" onclick="simpanMenuCepat()">
                    <i class="bi bi-check2-circle me-1"></i> Simpan Menu
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Global variables untuk pwa-order.js
    window.SYNC_ORDER_URL = "{{ url('/kasir/order/sync') }}";
</script>
<script src="{{ asset('pwa-order.js') }}"></script>
<script src="{{ asset('pwa-kasir.js') }}"></script>
<script>
    let cart = [];
    let editData = null;
    window.editOrderId = null;

    // ─── State voucher aktif ─────────────────────────────────────────────────
    window.activeVoucher = null; // { kode, jenis_potongan, nominal_potongan }

    // Event listener dinamis untuk kolom diskon
    document.getElementById('cust_discount')?.addEventListener('input', renderCart);
    document.getElementById('cust_discount_type')?.addEventListener('change', renderCart);

    // ─── Real-time AJAX cek voucher (debounce 500ms setelah 6 karakter) ──────
    let voucherDebounceTimer = null;

    document.getElementById('cust_voucher')?.addEventListener('input', function () {
        const pos = this.selectionStart;
        this.value = this.value.toUpperCase();
        this.setSelectionRange(pos, pos);

        const kode = this.value.trim();

        // Reset state jika kode berubah
        if (window.activeVoucher && window.activeVoucher.kode !== kode) {
            window.activeVoucher = null;
            renderCart();
        }
        hideVoucherAlert();
        clearTimeout(voucherDebounceTimer);

        if (kode.length === 0) return;

        if (kode.length < 6) {
            showVoucherAlert('secondary', 'Ketik ' + (6 - kode.length) + ' karakter lagi...');
            return;
        }

        // Tepat 6 karakter - fetch setelah 500ms diam
        voucherDebounceTimer = setTimeout(() => {
            applyVoucherKasir(kode);
        }, 500);
    });

    function applyVoucherKasir(kode) {
        showVoucherAlert('secondary', '<span class="spinner-border spinner-border-sm me-1"></span> Memeriksa kode voucher...');

        fetch('{{ route("kasir.voucher.apply") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify({ kode_voucher: kode })
        })
        .then(r => r.json())
        .then(data => {
            if (data.valid) {
                window.activeVoucher = {
                    kode: data.kode_voucher,
                    jenis_potongan: data.jenis_potongan,
                    nominal_potongan: data.nominal_potongan,
                };
                showVoucherAlert('success', '<i class="bi bi-check-circle-fill me-1"></i>' + data.message);
            } else {
                window.activeVoucher = null;
                showVoucherAlert('danger', '<i class="bi bi-x-circle-fill me-1"></i>' + data.message);
            }
            renderCart();
        })
        .catch(() => {
            window.activeVoucher = null;
            showVoucherAlert('danger', 'Tidak dapat memeriksa voucher. Periksa koneksi internet Anda.');
            renderCart();
        });
    }

    function removeVoucher() {
        window.activeVoucher = null;
        const kodeEl = document.getElementById('cust_voucher');
        if (kodeEl) { kodeEl.value = ''; kodeEl.readOnly = false; }
        hideVoucherAlert();
        renderCart();
    }

    function showVoucherAlert(type, html) {
        const el = document.getElementById('voucher-alert');
        if (!el) return;
        el.className = 'alert alert-' + type + ' py-1 px-2 mt-2';
        el.style.fontSize = '0.82rem';
        el.style.borderRadius = '8px';
        el.innerHTML = html;
        el.style.display = 'block';
    }
    function hideVoucherAlert() {
        const el = document.getElementById('voucher-alert');
        if (el) el.style.display = 'none';
    }

    const localEditStr = localStorage.getItem('edit_order_data');
    if (localEditStr) {
        editData = JSON.parse(localEditStr);
        window.editOrderId = editData.id;
        
        // Isi form discount agar langsung dibaca oleh fungsi renderCart()
        if (document.getElementById('cust_discount')) document.getElementById('cust_discount').value = editData.nominal_diskon || '';
        if (document.getElementById('cust_discount_type')) document.getElementById('cust_discount_type').value = editData.jenis_diskon || 'Rp';

        // Restore voucher jika ada
        if (editData.kode_voucher && document.getElementById('cust_voucher')) {
            document.getElementById('cust_voucher').value = editData.kode_voucher;
            // Validasi ulang voucher via AJAX untuk pastikan masih aktif
            fetch('{{ route("kasir.voucher.apply") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ kode_voucher: editData.kode_voucher })
            })
            .then(r => r.json())
            .then(data => {
                if (data.valid) {
                    window.activeVoucher = {
                        kode: data.kode_voucher,
                        jenis_potongan: data.jenis_potongan,
                        nominal_potongan: data.nominal_potongan,
                    };
                    if (typeof showVoucherAlert === 'function')
                        showVoucherAlert('success', '<i class="bi bi-check-circle-fill me-1"></i>Voucher <strong>' + data.kode_voucher + '</strong> aktif.');
                    renderCart();
                }
            })
            .catch(() => {});
        }

        setTimeout(() => {
            if (document.getElementById('cust_name')) document.getElementById('cust_name').value = editData.nama_pelanggan || '';
            if (document.getElementById('cust_table')) document.getElementById('cust_table').value = editData.no_meja || '';
            if (document.getElementById('cust_wa')) document.getElementById('cust_wa').value = editData.no_whatsapp || '';
            if (document.getElementById('cust_notes')) document.getElementById('cust_notes').value = editData.keterangan || '';
            
            const btnSubmit = document.getElementById('btnSubmitOrder');
            if (btnSubmit) {
                btnSubmit.innerHTML = '<i class="bi bi-check2-circle me-2"></i> UPDATE PESANAN';
            }
        }, 100);
    }

    function toggleCart() {
        document.getElementById('sidebarOrder').classList.toggle('active');
        document.getElementById('sidebarOverlay').classList.toggle('active');
    }

    if(editData && editData.order_details) {
        editData.order_details.forEach(item => {
            const menuData = item.menu || {};
            const stokTersedia = (menuData.stok_menu != null) ? (parseInt(menuData.stok_menu) + parseInt(item.qty)) : null;
            
            const variantId = item.variant ? item.variant : '0';
            const cId = `${item.menu_id}-${variantId}`;

            let namaFix = menuData.nama_menu || 'Menu Terhapus';
            if (item.variant) namaFix += ` (${item.variant})`;

            cart.push({ 
                cartItemId: cId,
                id: item.menu_id, 
                nama: namaFix, 
                harga: parseInt(item.harga), 
                qty: parseInt(item.qty),
                stok_menu: stokTersedia, 
                variant_name: item.variant ? item.variant : null,
                variant_harga: 0 
            });
        });
        renderCart();
    }

    let selectedMenuForVariant = null;
    const variantModal = new bootstrap.Modal(document.getElementById('variantModal'));

    function openVariantModal(menu) {
        selectedMenuForVariant = menu;
        document.getElementById('variantModalTitle').innerText = menu.nama_menu;
        
        const body = document.getElementById('variantModalBody');
        body.innerHTML = ''; 
        
        // --- 1. TAMBAHKAN KODE OPSI "TANPA VARIANT" DI SINI ---
        const tanpaVariantHtml = `
            <div class="form-check d-flex justify-content-between align-items-center mb-3 p-3 border rounded-3 hover-scale bg-light">
                <div>
                    <input class="form-check-input variant-checkbox" type="checkbox" value="0" id="var_none" 
                           data-name="Tanpa Variant" data-price="0" style="transform: scale(1.2); margin-top: 6px;"
                           onchange="if(this.checked) document.querySelectorAll('.variant-checkbox').forEach(cb => { if(cb.id !== 'var_none') cb.checked = false })" checked>
                    <label class="form-check-label ms-2 fw-bold text-secondary" for="var_none">
                        Tanpa Variant
                    </label>
                </div>
                <span class="text-secondary fw-bold">+Rp 0</span>
            </div>
        `;
        body.innerHTML += tanpaVariantHtml;
        // --- AKHIR KODE OPSI TANPA VARIANT ---

        menu.variants.forEach((v, index) => {
            // --- 2. TAMBAHKAN EVENT ONCHANGE PADA CHECKBOX VARIANT BAWAAN ---
            const variantHtml = `
                <div class="form-check d-flex justify-content-between align-items-center mb-3 p-3 border rounded-3 hover-scale">
                    <div>
                        <input class="form-check-input variant-checkbox" type="checkbox" value="${v.id}" id="var_${v.id}" 
                               data-name="${v.variant}" data-price="${v.harga}" style="transform: scale(1.2); margin-top: 6px;"
                               onchange="if(this.checked) document.getElementById('var_none').checked = false">
                        <label class="form-check-label ms-2 fw-medium" for="var_${v.id}">
                            ${v.variant}
                        </label>
                    </div>
                    <span class="text-orange fw-bold">+Rp ${parseInt(v.harga).toLocaleString('id-ID')}</span>
                </div>
            `;
            body.innerHTML += variantHtml;
        });
        variantModal.show();
    }

    function submitVariantSelection() {
        const checkboxes = document.querySelectorAll('.variant-checkbox:checked');
        if (checkboxes.length === 0) {
            Swal.fire('Info', 'Silakan pilih setidaknya 1 varian.', 'info');
            return;
        }

        let totalVariantPrice = 0;
        let variantNames = [];
        let variantIds = [];

        checkboxes.forEach(cb => {
            variantIds.push(cb.value);
            variantNames.push(cb.getAttribute('data-name'));
            totalVariantPrice += parseInt(cb.getAttribute('data-price'));
        });

        addToCart(selectedMenuForVariant, {
            ids: variantIds.join('-'),
            name: variantNames.join(', '), 
            price: totalVariantPrice
        });
        variantModal.hide();
    }

    function addToCart(menu, variantData = null) {
        const cartItemId = variantData ? `${menu.id}-${variantData.ids}` : `${menu.id}-0`;
        const itemSama = cart.find(i => i.cartItemId === cartItemId);

        if (itemSama) {
            if (menu.stok_menu !== null && (itemSama.qty + 1) > menu.stok_menu) {
                Swal.fire('Stok Terbatas', `Stok maksimal untuk ${menu.nama_menu} adalah ${menu.stok_menu}`, 'warning');
                return;
            }
            itemSama.qty++;
        } else {
            const finalName = variantData ? `${menu.nama_menu} (${variantData.name})` : menu.nama_menu;
            const finalHarga = parseInt(menu.harga) + (variantData ? variantData.price : 0);

            cart.push({ 
                cartItemId: cartItemId,
                id: menu.id, 
                nama: finalName, 
                harga: finalHarga, 
                qty: 1,
                stok_menu: menu.stok_menu,
                variant_name: variantData ? variantData.name : null,
                variant_harga: variantData ? variantData.price : 0
            });
        }
        renderCart();
    }

    function updateQty(cartItemId, delta) {
        const itemIndex = cart.findIndex(i => i.cartItemId === cartItemId);
        if (itemIndex > -1) {
            if (delta > 0 && cart[itemIndex].stok_menu !== null) {
                if ((cart[itemIndex].qty + delta) > cart[itemIndex].stok_menu) {
                    Swal.fire('Stok Maksimal', `Sisa stok maksimal adalah ${cart[itemIndex].stok_menu}`, 'warning');
                    return; 
                }
            }
            cart[itemIndex].qty += delta;
            if (cart[itemIndex].qty <= 0) {
                cart.splice(itemIndex, 1);
            }
            renderCart();
        }
    }

    function removeItem(cartItemId) {
        const itemIndex = cart.findIndex(i => i.cartItemId === cartItemId);
        if (itemIndex > -1) {
            cart.splice(itemIndex, 1);
            renderCart();
        }
    }

    function renderCart() {
        const list = document.getElementById('cart-list');
        const subtotalView = document.getElementById('subtotal-view');
        const discountView = document.getElementById('discount-view');
        const discountLabel = document.getElementById('discount-label-view');
        const totalView = document.getElementById('total-view');
        
        const mCount = document.getElementById('mobile-count');
        const mTotal = document.getElementById('mobile-total');
        
        list.innerHTML = '';
        let subtotal = 0;
        let count = 0;

        if (cart.length === 0) {
            list.innerHTML = `<div class="text-center py-5 text-muted"><i class="bi bi-cart3 display-4 opacity-25"></i><p class="small mt-2">Keranjang kosong</p></div>`;
            subtotalView.innerText = 'Rp 0';
            discountView.innerText = '- Rp 0';
            discountLabel.innerText = '';
            totalView.innerText = 'Rp 0';
            mCount.innerText = '0';
            mTotal.innerText = 'Rp 0';
            return;
        }

        cart.forEach(item => {
            subtotal += (item.harga * item.qty);
            count += item.qty;
            list.innerHTML += `
                <div class="cart-item">
                    <div style="flex:1" class="pe-2 overflow-hidden">
                        <div class="small fw-bold text-truncate">${item.nama}</div>
                        <div class="small text-muted">Rp ${item.harga.toLocaleString('id-ID')}</div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center bg-white rounded-pill px-2 py-1 border shadow-sm me-2">
                            <button type="button" class="btn btn-sm p-0 text-orange" onclick="updateQty('${item.cartItemId}', -1)">
                                <i class="bi bi-dash-circle-fill"></i>
                            </button>
                            <span class="mx-2 small fw-bold">${item.qty}</span>
                            <button type="button" class="btn btn-sm p-0 text-orange" onclick="updateQty('${item.cartItemId}', 1)">
                                <i class="bi bi-plus-circle-fill"></i>
                            </button>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle" onclick="removeItem('${item.cartItemId}')" title="Hapus Item">
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                    </div>
                </div>
            `;
        });
        
        // -- Logika Pengurangan Diskon UI --
        let inputDiskon = parseFloat(document.getElementById('cust_discount')?.value) || 0;
        let tipeDiskon = document.getElementById('cust_discount_type')?.value || 'Rp';
        let nilaiDiskon = 0;
        let labelDiskonStr = '';

        if (inputDiskon > 0) {
            if (tipeDiskon === '%') {
                if (inputDiskon > 100) inputDiskon = 100;
                nilaiDiskon = (subtotal * inputDiskon) / 100;
                labelDiskonStr = `(${inputDiskon}%)`;
            } else {
                if (inputDiskon > subtotal) inputDiskon = subtotal;
                nilaiDiskon = inputDiskon;
            }
        }

        // -- Logika Voucher UI --
        let nilaiVoucherUI = 0;
        const voucherSummaryRow = document.getElementById('voucher-summary-row');
        const voucherKodeView   = document.getElementById('voucher-kode-view');
        const voucherView       = document.getElementById('voucher-view');

        const afterDiskon = subtotal - nilaiDiskon;

        if (window.activeVoucher && afterDiskon > 0) {
            const v = window.activeVoucher;
            if (v.jenis_potongan === '%') {
                nilaiVoucherUI = (afterDiskon * Math.min(v.nominal_potongan, 100)) / 100;
            } else {
                nilaiVoucherUI = Math.min(v.nominal_potongan, afterDiskon);
            }
            if (voucherSummaryRow) voucherSummaryRow.style.display = 'flex';
            if (voucherKodeView)   voucherKodeView.textContent = v.kode;
            if (voucherView)       voucherView.innerText = '- Rp ' + nilaiVoucherUI.toLocaleString('id-ID');
        } else {
            if (voucherSummaryRow) voucherSummaryRow.style.display = 'none';
            if (voucherKodeView)   voucherKodeView.textContent = '';
            if (voucherView)       voucherView.innerText = '- Rp 0';
        }

        let total = afterDiskon - nilaiVoucherUI;

        subtotalView.innerText = `Rp ${subtotal.toLocaleString('id-ID')}`;
        discountView.innerText = `- Rp ${nilaiDiskon.toLocaleString('id-ID')}`;
        discountLabel.innerText = labelDiskonStr;

        const formattedTotal = `Rp ${total.toLocaleString('id-ID')}`;
        totalView.innerText = formattedTotal;
        mCount.innerText = count;
        mTotal.innerText = formattedTotal;

        document.querySelectorAll('.menu-card').forEach(card => card.classList.remove('card-selected'));
        cart.forEach(item => {
            const cardElement = document.getElementById(`menu-card-${item.id}`);
            if (cardElement) cardElement.classList.add('card-selected');
        });
    }

    async function saveOrder() {
        const elName = document.getElementById('cust_name');
        const elTable = document.getElementById('cust_table');
        const elWa = document.getElementById('cust_wa');
        const elNotes = document.getElementById('cust_notes');
        
        const elDiscount = document.getElementById('cust_discount');
        const elDiscountType = document.getElementById('cust_discount_type');

        const name = elName ? elName.value : 'Pelanggan'; 
        const table = elTable ? elTable.value : '';
        const wa = elWa ? elWa.value : '';
        const notes = elNotes ? elNotes.value : '';
        
        const discountVal = elDiscount ? (parseFloat(elDiscount.value) || 0) : 0;
        const discountType = elDiscountType ? elDiscountType.value : 'Rp';

        if ((elName && !name) || cart.length === 0) {
            return Swal.fire('Ops!', 'Lengkapi data pelanggan dan menu', 'warning');
        }

        const data = {
            nama_pelanggan: name,
            no_meja: table,
            no_whatsapp: wa,
            keterangan: notes,
            cart: cart,
            nominal_diskon: discountVal,
            jenis_diskon: discountType,
            kode_voucher: window.activeVoucher ? window.activeVoucher.kode : null,
        };

        const editId = window.editOrderId;
        Swal.fire({ title: 'Memproses...', didOpen: () => Swal.showLoading(), allowOutsideClick: false });

        if (navigator.onLine) {
            let url = editId ? `/kasir/order/update/${editId}` : '/kasir/order/store';
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await res.json();
                
                if (res.ok && result.success) {
                    if(result.data && result.data.nomor_pesanan) await saveLastOrderNumber(result.data.nomor_pesanan);
                    Swal.fire({ title: 'Berhasil!', text: result.message, icon: 'success', timer: 1500, showConfirmButton: false }).then(() => {
                        if (result.data && result.data.id) localStorage.setItem('new_order_id', result.data.id);
                        window.parent.postMessage({ type: 'ORDER_SUCCESS' }, '*');
                    });
                } else {
                    throw new Error(result.message || 'Gagal menyimpan.');
                }
            } catch (e) {
                Swal.fire('Gagal', e.message, 'error');
            }
        } else {
            if (editId) {
                try {
                    await saveOfflineAction('update', editId, data);
                    let pendingEdits = JSON.parse(localStorage.getItem('offline_pending_edits') || '{}');
                    pendingEdits[editId] = data;
                    localStorage.setItem('offline_pending_edits', JSON.stringify(pendingEdits));
                    localStorage.removeItem('edit_order_data'); 

                    Swal.fire({
                        title: 'Update Tersimpan Offline!',
                        text: 'Perubahan pesanan akan disinkronkan saat internet kembali stabil.',
                        icon: 'success', timer: 2000, showConfirmButton: false
                    }).then(() => {
                        // Kirim OFFLINE_ORDER_SAVED agar parent TIDAK reload halaman,
                        // melainkan hanya refresh UI (refreshOfflineUI) dengan data optimistic
                        window.parent.postMessage({ type: 'OFFLINE_ORDER_SAVED' }, '*');
                    });
                } catch (error) {
                    Swal.fire('Gagal', 'Tidak dapat menyimpan update secara offline', 'error');
                }
                return;
            }
            try {
                let lastNum = await getLastOrderNumber();
                let nextNum = lastNum + 1;
                
                data.offline_nomor = 'OFF-' + nextNum;
                data.timestamp = new Date().getTime();

                await saveOfflineOrder(data);
                await saveLastOrderNumber(nextNum); 

                Swal.fire({
                    title: 'Tersimpan Offline!',
                    text: 'Pesanan disimpan lokal dan akan masuk server otomatis saat online.',
                    icon: 'success', timer: 2000, showConfirmButton: false
                }).then(() => {
                    window.parent.postMessage({ type: 'OFFLINE_ORDER_SAVED' }, '*');
                });
            } catch (error) {
                Swal.fire('Gagal', 'Tidak dapat menyimpan data secara offline', 'error');
            }
        }
    }

    document.getElementById('searchMenu').addEventListener('keyup', (e) => {
        const val = e.target.value.toLowerCase();
        document.querySelectorAll('.menu-wrapper').forEach(item => {
            // Skip card tambah menu (data-name kosong), selalu tampil
            if (item.dataset.name === '') return;
            item.style.display = item.dataset.name.includes(val) ? '' : 'none';
        });
    });

    // ===== MODAL TAMBAH MENU CEPAT =====
    const _modalTambahMenu = new bootstrap.Modal(document.getElementById('modalTambahMenu'));

    function openModalTambahMenu() {
        document.getElementById('inputNamaMenu').value = '';
        document.getElementById('inputHargaMenu').value = '';
        _modalTambahMenu.show();
        setTimeout(() => document.getElementById('inputNamaMenu').focus(), 400);
    }

    async function simpanMenuCepat() {
        const nama  = document.getElementById('inputNamaMenu').value.trim();
        const harga = document.getElementById('inputHargaMenu').value.trim();

        if (!nama || nama.length < 2) {
            document.getElementById('inputNamaMenu').focus();
            return Swal.fire('Perhatian', 'Nama menu minimal 2 karakter.', 'warning');
        }
        if (harga === '' || isNaN(harga) || Number(harga) < 0) {
            document.getElementById('inputHargaMenu').focus();
            return Swal.fire('Perhatian', 'Masukkan harga yang valid.', 'warning');
        }

        const btn = document.getElementById('btnSimpanMenuCepat');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

        try {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('tipe_menu', 'utama');
            formData.append('menus[0][nama_menu]', nama);
            formData.append('menus[0][harga]', harga);

            const res = await fetch('/kasir/gallery-menu/store', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });

            // Controller redirect back, cek status
            if (res.ok || res.redirected || res.status === 302) {
                _modalTambahMenu.hide();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: `Menu "${nama}" berhasil ditambahkan.`,
                    timer: 1800,
                    showConfirmButton: false
                }).then(() => {
                    // Beritahu parent frame agar reload menu jika perlu
                    window.parent.postMessage({ type: 'MENU_ADDED' }, '*');
                    // Reload iframe order agar menu baru muncul
                    window.location.reload();
                });
            } else {
                const text = await res.text();
                // Cek apakah ada pesan error dari laravel flash
                const parser = new DOMParser();
                const doc = parser.parseFromString(text, 'text/html');
                const errEl = doc.querySelector('[data-error], .alert-danger');
                const msg = errEl ? errEl.textContent.trim() : 'Terjadi kesalahan. Coba lagi.';
                throw new Error(msg);
            }
        } catch (e) {
            Swal.fire('Gagal', e.message, 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check2-circle me-1"></i> Simpan Menu';
        }
    }
</script>
</body>
</html>