<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $outlet->nama_outlet }} - Outlet Info</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @include('layouts.favicon')
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Yeseva+One&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-orange: #ff6a00;
            --brand-orange-hover: #e65f00;
            --bg-light-grey: #f8f9fa;
        }

        body {
            background-color: #fff7ed ; 
            font-family: 'Poppins', sans-serif; 
            color: #333;
        }

        .outlet-title { font-family: 'Yeseva One', cursive; font-weight: 800; color: black; font-size: 2.8rem; text-transform: capitalize; }
        .text-orange { color: var(--brand-orange) !important; }
        .bg-orange { background-color: var(--brand-orange) !important; }
        .btn-orange { background-color: var(--brand-orange); color: #fff; font-weight: 600; border: none; transition: all 0.3s ease; box-shadow: 0 4px 6px rgba(255, 106, 0, 0.2); }
        .btn-orange:hover { background-color: var(--brand-orange-hover); color: #fff; transform: translateY(-2px); box-shadow: 0 6px 12px rgba(255, 106, 0, 0.3); }
        .img-outlet-wrapper { width: 100%; height: 100%; min-height: 300px; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,0.08); background-color: #fff; }
        .img-outlet-wrapper img { width: 100%; height: 100%; object-fit: cover; }
        .info-card { background-color: #ffffff; border-radius: 16px; padding: 2rem; box-shadow: 0 8px 24px rgba(0,0,0,0.05); height: 100%; display: flex; flex-direction: column; }
        
        .menu-card { border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border-radius: 18px; overflow: hidden; position: relative; height: 100%; background: #ffffff; transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); cursor: pointer; }
        .menu-card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(255, 106, 0, 0.12); }
        .menu-img-wrapper { position: relative; overflow: hidden; padding-top: 75%; background-color: #f8f9fa; }
        .menu-img { position: absolute; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
        .menu-card:hover .menu-img { transform: scale(1.08); }
        .badge-menu { position: absolute; top: 12px; right: 12px; z-index: 5; font-size: 0.7rem; padding: 6px 14px; border-radius: 8px; font-weight: 700; text-transform: uppercase; }
        .badge-best-seller { background: linear-gradient(135deg, #ff416c, #ff4b2b); color: white; box-shadow: 0 4px 10px rgba(255, 75, 43, 0.3); border: 1px solid rgba(255,255,255,0.2); }
        .badge-utama { background: rgba(255, 255, 255, 0.95); color: var(--brand-orange); box-shadow: 0 4px 15px rgba(0,0,0,0.08); backdrop-filter: blur(4px); }
        .menu-overlay-habis { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(255, 255, 255, 0.5); backdrop-filter: blur(3px); display: flex; align-items: center; justify-content: center; z-index: 10; }
        .label-habis { background-color: #1e293b; color: white; padding: 8px 24px; border-radius: 30px; font-weight: 700; font-size: 0.9rem; letter-spacing: 1px; box-shadow: 0 4px 15px rgba(0,0,0,0.15); }
        .btn-modern-outline { border: 2px solid #e2e8f0; color: #475569; background: white; transition: 0.3s ease; }
        .btn-modern-outline:hover { border-color: var(--brand-orange); color: var(--brand-orange); background: #fffaf7; }
        #searchInput:focus{ border: 2px solid var(--primary-orange) !important; box-shadow: 0 0 20px rgba(253, 126, 20, 0.4) !important; }

        @media (max-width: 767.98px) {
            #menuContainer:not(.show-all-menus) .menu-item:nth-child(n+5) { display: none !important; }
            .outlet-title { font-size: 2.2rem; }
            .info-card { padding: 1.5rem; }
        }
        @media (min-width: 768px) {
            #menuContainer:not(.show-all-menus) .menu-item:nth-child(n+9) { display: none !important; }
        }

        /* --- TAMBAHAN CSS CART --- */
        .floating-cart-btn {
            position: fixed; bottom: 30px; right: 30px; z-index: 999;
            width: 65px; height: 65px; border-radius: 50%; display: none;
            align-items: center; justify-content: center;
            background: var(--brand-orange); color: white;
            box-shadow: 0 10px 25px rgba(255, 106, 0, 0.4);
            transition: 0.3s transform; border: none; font-size: 1.5rem;
        }
        .floating-cart-btn:hover { transform: scale(1.1); }
        .cart-badge {
            position: absolute; top: 0px; right: 0px; background: #dc3545; color: white;
            font-size: 0.75rem; font-weight: bold; width: 24px; height: 24px;
            display: flex; align-items: center; justify-content: center; border-radius: 50%;
            border: 2px solid white;
        }
        .form-control-custom { border-radius: 10px; background: #f8f9fa; border: 1px solid #dee2e6; }
        .form-control-custom:focus { border-color: var(--brand-orange); box-shadow: 0 0 0 3px rgba(255, 106, 0, 0.15); background: #fff; }

        /* Custom Radio Button Variant */
        .variant-radio-label {
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }
        .form-check-input:checked + .variant-radio-label {
            color: var(--brand-orange);
            font-weight: 700 !important;
        }
        .form-check-input:checked + .variant-radio-label .variant-price {
            color: var(--brand-orange) !important;
        }
        .variant-box {
            border: 2px solid transparent;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }
        .variant-box:hover {
            background-color: #fff7ed !important;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="outlet-title m-0">{{ $outlet->nama_outlet }}</h1>
        <div class="mx-auto mt-3" style="width: 60px; height: 4px; background-color: var(--brand-orange); border-radius: 2px;"></div>
    </div>

    <div class="row g-4 mb-5 align-items-stretch">
        <div class="col-lg-6">
            <div class="img-outlet-wrapper">
                @if($outlet->foto_outlet)
                   <img src="{{ asset('assets/outlet/' . $outlet->foto_outlet) }}" alt="{{ $outlet->nama_outlet }}" class="img-fluid">
                @else
                    <div class="d-flex justify-content-center align-items-center bg-light w-100 h-100" style="min-height: 350px;"><i class="bi bi-images text-secondary" style="font-size: 5rem; opacity: 0.5;"></i></div>
                @endif
            </div>
        </div>
        <div class="col-lg-6">
            <div class="info-card">
                <h5 class="fw-bold mb-3 d-flex align-items-center"><i class="bi bi-info-square-fill text-orange me-2 fs-4"></i> Tentang Outlet</h5>
                <p class="text-secondary mb-4" style="line-height: 1.7;">{{ $outlet->deskripsi ?? 'Tidak ada deskripsi yang tersedia untuk outlet ini.' }}</p>
                <div class="d-flex align-items-start mb-4 bg-light p-3 rounded-4 border">
                    <div class="bg-white p-2 rounded-circle shadow-sm me-3 d-flex justify-content-center align-items-center"><i class="bi bi-geo-alt-fill text-orange fs-4"></i></div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Alamat Lokasi</h6>
                        <p class="mb-1 text-secondary" style="font-size: 0.95rem;">{{ $outlet->alamat_lengkap }}</p>
                        <p class="text-muted mb-0 fw-medium small">{{ $outlet->kota }}, {{ $outlet->provinsi }}</p>
                    </div>
                </div>
                <div class="mt-auto">
                    @if($outlet->maps)
                        <a href="{{ $outlet->maps }}" target="_blank" class="btn btn-orange w-100 py-3 rounded-pill fs-6 d-flex justify-content-center align-items-center"><i class="bi bi-map-fill me-2 fs-5"></i> BUKA GOOGLE MAPS</a>
                    @else
                        <button class="btn btn-secondary w-100 py-3 rounded-pill fs-6" disabled><i class="bi bi-map-fill me-2 fs-5"></i> MAPS BELUM TERSEDIA</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-4 shadow-sm mb-4 d-flex flex-column flex-md-row justify-content-between align-items-center border">
        <h4 class="fw-bold mb-3 mb-md-0 m-0 d-flex align-items-center text-dark"><i class="bi bi-grid-fill text-orange me-2 fs-4"></i> Daftar Menu</h4>
        <div class="input-group w-100 w-md-50" style="max-width: 450px;">
            <span class="input-group-text bg-light border-end-0 rounded-start-pill ps-3"><i class="bi bi-search text-orange"></i></span>
            <input type="text" id="searchInput" class="form-control bg-light border-start-0 rounded-end-pill py-2" placeholder="Cari menu, harga, jenis...">
        </div>
    </div>

    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 g-md-4 mb-4" id="menuContainer">
        @foreach($menus as $index => $menu)
           <div class="col menu-item" @if($menu->status_menu != 'habis') onclick='handleMenuClick(@json($menu))' @endif>
                <div class="card menu-card h-100">
                    @if($menu->status_menu == 'habis')
                        <div class="menu-overlay-habis"><span class="label-habis">HABIS</span></div>
                    @endif
                    <div class="menu-img-wrapper">
                        @if($menu->tipe_menu == 'utama')
                            <span class="badge badge-menu badge-utama">Menu Utama</span>
                        @elseif($menu->tipe_menu == 'best_seller')
                            <span class="badge badge-menu badge-best-seller"><i class="bi bi-fire me-1 text-warning"></i>Best Seller</span>
                        @endif
                        <img src="{{ $menu->gambar ? asset('assets/menus/' . $menu->gambar) : 'https://placehold.co/400x300?text=No+Image' }}" class="card-img-top menu-img" alt="{{ $menu->nama_menu }}">
                    </div>
                    <div class="card-body d-flex flex-column p-3 p-md-4">
                        <h6 class="card-title fw-bold text-truncate mb-1" style="color: #1e293b; font-size: 1.05rem;" title="{{ $menu->nama_menu }}">{{ $menu->nama_menu }}</h6>
                        
                        @if(count($menu->variants) > 0)
                            <span class="badge bg-light text-dark border d-inline-block mt-1 mb-2" style="width: fit-content; font-size: 0.7rem;"><i class="bi bi-tags-fill text-orange me-1"></i> Punya Varian</span>
                        @else
                            <div class="mb-2" style="height: 20px;"></div> @endif

                        <div class="mt-auto pt-3 d-flex align-items-end justify-content-between border-top border-light">
                            <div>
                                <span class="d-block text-muted" style="font-size: 0.75rem; font-weight: 500; margin-bottom: 2px;">Harga Mulai</span>
                                <h5 class="card-text text-orange fw-bolder mb-0" style="letter-spacing: -0.5px;">Rp {{ number_format($menu->harga, 0, ',', '.') }}</h5>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-light transition-all" style="width: 32px; height: 32px; {{ $menu->status_menu == 'habis' ? 'opacity:0.3' : 'opacity: 1; color: var(--brand-orange);' }}">
                                <i class="bi bi-cart-plus-fill fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if(count($menus) > 4)
        <div class="text-center mb-5 mt-4 {{ count($menus) <= 8 ? 'd-md-none' : '' }}" id="toggleMenuContainer">
            <button id="btnToggleMenu" class="btn btn-modern-outline px-5 py-3 rounded-pill fw-bold">LIHAT SEMUA MENU <i class="bi bi-chevron-down ms-2"></i></button>
        </div>
    @endif

    <hr class="text-secondary opacity-25 my-5">

    @if($outlet->mediaQr && ($outlet->mediaQr->instagram || $outlet->mediaQr->x || $outlet->mediaQr->tiktok || $outlet->mediaQr->youtube || $outlet->mediaQr->facebook))
        <div class="text-center pb-5 pt-2">
            <h6 class="fw-bold mb-4 text-secondary text-uppercase tracking-wider">Kunjungi Sosial Media Kami</h6>
            <div class="d-flex justify-content-center gap-3">
                @if($outlet->mediaQr->facebook) <a href="{{ $outlet->mediaQr->facebook }}" target="_blank" class="btn btn-primary rounded-circle shadow-sm" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;"><i class="bi bi-facebook fs-5"></i></a> @endif
                @if($outlet->mediaQr->instagram) <a href="{{ $outlet->mediaQr->instagram }}" target="_blank" class="btn rounded-circle shadow-sm text-white" style="background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;"><i class="bi bi-instagram fs-5"></i></a> @endif
                @if($outlet->mediaQr->tiktok) <a href="{{ $outlet->mediaQr->tiktok }}" target="_blank" class="btn btn-dark rounded-circle shadow-sm" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;"><i class="bi bi-tiktok fs-5"></i></a> @endif
                @if($outlet->mediaQr->youtube) <a href="{{ $outlet->mediaQr->youtube }}" target="_blank" class="btn btn-danger rounded-circle shadow-sm" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;"><i class="bi bi-youtube fs-5"></i></a> @endif
                @if($outlet->mediaQr->x) <a href="{{ $outlet->mediaQr->x }}" target="_blank" class="btn btn-dark rounded-circle shadow-sm" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;"><i class="bi bi-twitter-x fs-5"></i></a> @endif
            </div>
        </div>
    @endif
</div>

<div class="modal fade" id="variantModal" tabindex="-1" aria-labelledby="variantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <div>
                    <h5 class="modal-title fw-bold text-dark" id="variantMenuName">Nama Menu</h5>
                    <p class="mb-0 text-muted small">Silakan pilih varian/level yang diinginkan</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <form id="variantForm">
                    <div id="variantOptions" class="mb-4">
                        </div>
                    <button type="button" class="btn btn-orange w-100 py-3 rounded-pill fw-bold" onclick="submitVariantForm()">
                        <i class="bi bi-cart-plus me-1"></i> TAMBAH KE PESANAN
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<button class="floating-cart-btn" id="floatingCart" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
    <i class="bi bi-basket2-fill"></i>
    <span class="cart-badge" id="cartCountBadge">0</span>
</button>

<div class="offcanvas offcanvas-end shadow-lg" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel" style="width: 400px; border-left: none;">
    <div class="offcanvas-header border-bottom bg-light">
        <h5 class="offcanvas-title fw-bold text-dark d-flex align-items-center" id="cartOffcanvasLabel">
            <i class="bi bi-basket2-fill text-orange me-2 fs-4"></i> Pesanan Saya
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <div class="offcanvas-body p-0 d-flex flex-column bg-white">
        <div id="cartList" class="flex-grow-1 overflow-y-auto p-3" style="background: #fdfdfd;">
            <div class="text-center py-5 text-muted">
                <i class="bi bi-cart3 display-4 opacity-25"></i>
                <p class="mt-3">Keranjang masih kosong</p>
            </div>
        </div>

        <div class="border-top bg-white p-3 shadow-sm" style="z-index: 10;">
            <h6 class="fw-bold mb-3 text-secondary" style="font-size: 0.85rem;"><i class="bi bi-person-lines-fill me-1"></i> Informasi Pelanggan</h6>
            
            @if(isset($pengaturan) && $pengaturan->nama)
                <div class="mb-2">
                    <input type="text" id="custName" class="form-control form-control-sm form-control-custom" placeholder="Nama Anda *" required>
                </div>
            @endif
            
            <div class="row g-2 mb-2">
                @if(isset($pengaturan) && $pengaturan->meja)
                    <div class="col-6"><input type="text" id="custTable" class="form-control form-control-sm form-control-custom" placeholder="No. Meja"></div>
                @endif
                @if(isset($pengaturan) && $pengaturan->whatsapp)
                    <div class="col-6"><input type="number" id="custWa" class="form-control form-control-sm form-control-custom" placeholder="WhatsApp"></div>
                @endif
            </div>

            @if(isset($pengaturan) && $pengaturan->keterangan)
                <div class="mb-3">
                    <textarea id="custNotes" class="form-control form-control-sm form-control-custom" rows="2" placeholder="Catatan pesanan..." style="resize:none;"></textarea>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-3 pt-2 border-top">
                <span class="fw-bold text-muted small">TOTAL</span>
                <span class="fs-4 fw-bolder text-orange" id="cartTotalView">Rp 0</span>
            </div>

            <button type="button" class="btn btn-orange w-100 py-3 rounded-pill" onclick="prosesPesanan()">
                PESAN SEKARANG <i class="bi bi-arrow-right-circle ms-1"></i>
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // --- STATE KERANJANG & VARIANT ---
    let cart = [];
    let tempSelectedMenu = null; // Menyimpan sementara menu yang sedang dipilih variannya
    let variantModalInstance = null;
    
    const assetUrl = "{{ asset('') }}"; 
    const formatRupiah = (angka) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka).replace('Rp', 'Rp ').trim();

    document.addEventListener("DOMContentLoaded", function () {
        // Inisialisasi modal BS
        variantModalInstance = new bootstrap.Modal(document.getElementById('variantModal'));
    });

    // --- LOGIC KLIK MENU ---
    function handleMenuClick(menu) {
        // Cek apakah menu punya varian
        if (menu.variants && menu.variants.length > 0) {
            tempSelectedMenu = menu;
            document.getElementById('variantMenuName').innerText = menu.nama_menu;
            
            let htmlOptions = '';
            
            // Opsi Default (Original)
            htmlOptions += `
                <div class="form-check p-3 bg-light rounded-3 mb-2 variant-box" onclick="document.getElementById('var_none').click()">
                    <input class="form-check-input ms-1" type="radio" name="menuVariant" id="var_none" value="" data-harga="0" checked>
                    <label class="form-check-label w-100 ms-2 fw-medium d-flex justify-content-between variant-radio-label" for="var_none">
                        Original / Tanpa Varian 
                        <span class="text-secondary small variant-price">+ Rp 0</span>
                    </label>
                </div>
            `;

            // Opsi Varian dari Database
            menu.variants.forEach((v) => {
                htmlOptions += `
                    <div class="form-check p-3 bg-light rounded-3 mb-2 variant-box" onclick="document.getElementById('var_${v.id}').click()">
                        <input class="form-check-input ms-1" type="radio" name="menuVariant" id="var_${v.id}" value="${v.variant}" data-harga="${v.harga}">
                        <label class="form-check-label w-100 ms-2 fw-medium d-flex justify-content-between variant-radio-label" for="var_${v.id}">
                            ${v.variant} 
                            <span class="text-muted small variant-price">+ ${formatRupiah(v.harga)}</span>
                        </label>
                    </div>
                `;
            });

            document.getElementById('variantOptions').innerHTML = htmlOptions;
            variantModalInstance.show();
        } else {
            // Jika tidak ada varian, langsung masuk keranjang
            addToCart(menu, null, 0);
        }
    }

    // --- LOGIC SUBMIT MODAL VARIANT ---
    function submitVariantForm() {
        const selectedRadio = document.querySelector('input[name="menuVariant"]:checked');
        if (!selectedRadio) return; 

        const variantName = selectedRadio.value;
        const variantHarga = parseInt(selectedRadio.getAttribute('data-harga'));

        addToCart(tempSelectedMenu, variantName, variantHarga);
        variantModalInstance.hide();
    }

    // --- LOGIC ADD TO CART ---
    function addToCart(menu, variantName = null, variantHarga = 0) {
        // Buat ID unik berdasarkan ID menu dan nama varian.
        // Agar "Es Teh Manis (Original)" terpisah dengan "Es Teh Manis (Jumbo)"
        const cartId = variantName ? `${menu.id}_${variantName}` : `${menu.id}`;
        
        const index = cart.findIndex(i => i.cartId === cartId);
        const hargaSatuanAkhir = parseInt(menu.harga) + parseInt(variantHarga);
        
        if (index > -1) {
            // Validasi Stok
            if (menu.stok_menu !== null && (cart[index].qty + 1) > menu.stok_menu) {
                Swal.fire('Info Stok', `Maksimal pesanan ${menu.nama_menu} adalah ${menu.stok_menu}`, 'warning');
                return;
            }
            cart[index].qty++;
            cart[index].subtotal = cart[index].qty * hargaSatuanAkhir;
        } else {
            cart.push({
                cartId: cartId, // Custom Unique ID
                id: menu.id,    // Real Database Menu ID
                nama: menu.nama_menu,
                harga: parseInt(menu.harga),
                variant_name: variantName,
                variant_harga: parseInt(variantHarga),
                qty: 1,
                subtotal: hargaSatuanAkhir,
                stok: menu.stok_menu
            });
        }
        
        renderCart();
        
        // Tampilkan Offcanvas otomatis saat item pertama masuk
        if(cart.length === 1) {
            const offcanvasEl = document.getElementById('cartOffcanvas');
            const bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl) || new bootstrap.Offcanvas(offcanvasEl);
            bsOffcanvas.show();
        }
    }

    // --- LOGIC UPDATE QTY ---
    function updateQty(cartId, delta) {
        const index = cart.findIndex(i => i.cartId === cartId);
        if (index > -1) {
            if (delta > 0 && cart[index].stok !== null && (cart[index].qty + delta) > cart[index].stok) {
                Swal.fire('Peringatan', 'Stok tidak mencukupi', 'warning');
                return;
            }
            
            cart[index].qty += delta;
            
            if (cart[index].qty <= 0) {
                cart.splice(index, 1);
            } else {
                const hargaSatuan = cart[index].harga + cart[index].variant_harga;
                cart[index].subtotal = cart[index].qty * hargaSatuan;
            }
            renderCart();
        }
    }

    function removeItem(cartId) {
        const index = cart.findIndex(i => i.cartId === cartId);
        if (index > -1) {
            cart.splice(index, 1);
            renderCart();
        }
    }

    // --- RENDER KERANJANG ---
    function renderCart() {
        const list = document.getElementById('cartList');
        const badge = document.getElementById('cartCountBadge');
        const totalView = document.getElementById('cartTotalView');
        const floatBtn = document.getElementById('floatingCart');

        let totalHarga = 0;
        let totalItem = 0;
        list.innerHTML = '';

        if (cart.length === 0) {
            list.innerHTML = `<div class="text-center py-5 text-muted"><i class="bi bi-cart3 display-4 opacity-25"></i><p class="mt-3">Keranjang masih kosong</p></div>`;
            floatBtn.style.display = 'none';
        } else {
            floatBtn.style.display = 'flex';
            cart.forEach(item => {
                totalHarga += item.subtotal;
                totalItem += item.qty;
                
                // Element HTML untuk Varian
                let variantHtml = item.variant_name ? `<span class="badge bg-warning text-dark me-2 border" style="font-size:0.65rem;">${item.variant_name}</span>` : '';
                const hargaSatuan = item.harga + item.variant_harga;

                list.innerHTML += `
                    <div class="d-flex align-items-center mb-3 bg-white p-2 rounded-3 border">
                        <div class="flex-grow-1 overflow-hidden pe-2">
                            <h6 class="mb-1 text-truncate fw-bold" style="font-size: 0.9rem;">${item.nama}</h6>
                            <div class="mb-1 d-flex align-items-center">
                                ${variantHtml}
                            </div>
                            <span class="text-orange fw-bold small">${formatRupiah(hargaSatuan)}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center bg-light rounded-pill border px-2 py-1 me-2">
                                <button type="button" class="btn btn-sm p-0 text-secondary" onclick="updateQty('${item.cartId}', -1)">
                                    <i class="bi bi-dash-circle-fill"></i>
                                </button>
                                <span class="mx-2 fw-bold" style="font-size: 0.85rem; width: 16px; text-align:center;">${item.qty}</span>
                                <button type="button" class="btn btn-sm p-0 text-orange" onclick="updateQty('${item.cartId}', 1)">
                                    <i class="bi bi-plus-circle-fill"></i>
                                </button>
                            </div>
                            
                            <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle" onclick="removeItem('${item.cartId}')" title="Hapus Item">
                                <i class="bi bi-trash3-fill fs-5"></i>
                            </button>
                        </div>
                    </div>
                `;
            });
        }

        badge.innerText = totalItem;
        totalView.innerText = formatRupiah(totalHarga);
    }

    // --- PROSES SIMPAN ORDER KE BACKEND ---
    async function prosesPesanan() {
        if (cart.length === 0) return Swal.fire('Oops', 'Keranjang masih kosong', 'error');

        const elName = document.getElementById('custName');
        const name = elName ? elName.value.trim() : 'Pelanggan';
        
        if (elName && !name) {
            return Swal.fire('Data Kurang', 'Silakan isi Nama Anda', 'warning');
        }

        const dataPesanan = {
            nama_pelanggan: name,
            no_meja: document.getElementById('custTable') ? document.getElementById('custTable').value : '',
            no_whatsapp: document.getElementById('custWa') ? document.getElementById('custWa').value : '',
            keterangan: document.getElementById('custNotes') ? document.getElementById('custNotes').value : '',
            cart: cart // Mengirim array keranjang yang sekarang sudah berisi properti variant_name & variant_harga
        };

        Swal.fire({ title: 'Memproses...', text: 'Mohon tunggu', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        try {
            const res = await fetch("{{ route('outlet.order.store', $outlet->page_name) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(dataPesanan)
            });

            const result = await res.json();
            
            if (res.ok && result.success) {
                Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Pesanan Diterima', showConfirmButton: false, timer: 1500 })
                .then(() => {
                    window.location.href = result.redirect_url;
                });
            } else {
                throw new Error(result.message || 'Terjadi kesalahan sistem');
            }
        } catch (e) {
            Swal.fire('Gagal', e.message, 'error');
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        // --- LOGIC TOMBOL LIHAT SEMUA ---
        const btnToggleMenu = document.getElementById('btnToggleMenu');
        const menuContainer = document.getElementById('menuContainer');
        if (btnToggleMenu && menuContainer) {
            btnToggleMenu.addEventListener('click', function() {
                menuContainer.classList.toggle('show-all-menus');
                if (menuContainer.classList.contains('show-all-menus')) {
                    this.innerHTML = 'SEMBUNYIKAN MENU <i class="bi bi-chevron-up ms-1"></i>';
                } else {
                    this.innerHTML = 'LIHAT SEMUA MENU <i class="bi bi-chevron-down ms-1"></i>';
                }
            });
        }

        // --- PENCARIAN AJAX ---
        const searchInput = document.getElementById('searchInput');
        const toggleContainer = document.getElementById('toggleMenuContainer');
        const searchEndpoint = "{{ route('outlet.search', $outlet->page_name) }}";

        if(searchInput) {
            searchInput.addEventListener('input', function () {
                let keyword = this.value.trim();
                if (toggleContainer) {
                    if (keyword.length > 0) { toggleContainer.style.display = 'none'; } 
                    else { window.location.reload(); return; }
                }

                fetch(`${searchEndpoint}?q=${encodeURIComponent(keyword)}`, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }})
                .then(response => response.json())
                .then(data => {
                    menuContainer.innerHTML = ''; 
                    menuContainer.classList.add('show-all-menus');

                    if (data.length === 0) {
                        menuContainer.innerHTML = `<div class="col-12 text-center py-5"><i class="bi bi-search text-muted" style="font-size: 3rem;"></i><h5 class="text-muted mt-3 fw-bold">Menu tidak ditemukan</h5></div>`;
                        return;
                    }

                    data.forEach(menu => {
                        let imgSrc = menu.gambar ? (assetUrl + 'assets/menus/' + menu.gambar) : 'https://placehold.co/400x300?text=No+Image';
                        let overlayHabis = menu.status_menu === 'habis' ? `<div class="menu-overlay-habis"><span class="label-habis">HABIS</span></div>` : '';
                        let badgeTipe = '';
                        if (menu.tipe_menu === 'utama') badgeTipe = `<span class="badge bg-primary badge-menu shadow-sm">Menu Utama</span>`;
                        else if (menu.tipe_menu === 'best_seller') badgeTipe = `<span class="badge bg-danger badge-menu shadow-sm"><i class="bi bi-star-fill me-1 text-warning"></i>Best Seller</span>`;

                        // Tanda Varian untuk hasil pencarian
                        let variantBadge = (menu.variants && menu.variants.length > 0) 
                            ? `<span class="badge bg-light text-dark border d-inline-block mt-1 mb-2" style="width: fit-content; font-size: 0.7rem;"><i class="bi bi-tags-fill text-orange me-1"></i> Punya Varian</span>`
                            : `<div class="mb-2" style="height: 20px;"></div>`;

                        let jsonStr = JSON.stringify(menu).replace(/'/g, "\\'");
                        let cursorClick = menu.status_menu === 'habis' ? '' : `onclick='handleMenuClick(${jsonStr})'`;
                        let iconCart = menu.status_menu === 'habis' ? 'opacity:0.3' : 'opacity:1; color: var(--brand-orange)';

                        let cardHtml = `
                            <div class="col menu-item" ${cursorClick}>
                                <div class="card menu-card h-100">
                                    ${overlayHabis}
                                    <div class="menu-img-wrapper">${badgeTipe}<img src="${imgSrc}" class="card-img-top menu-img" alt="${menu.nama_menu}"></div>
                                    <div class="card-body d-flex flex-column p-3 p-md-4">
                                        <h6 class="card-title fw-bold text-dark text-truncate mb-1" style="font-size: 1.05rem;" title="${menu.nama_menu}">${menu.nama_menu}</h6>
                                        ${variantBadge}
                                        <div class="mt-auto pt-3 d-flex align-items-end justify-content-between border-top border-light">
                                            <div>
                                                <span class="d-block text-muted" style="font-size: 0.75rem; font-weight: 500; margin-bottom: 2px;">Harga Mulai</span>
                                                <h5 class="card-text text-orange fw-bolder mb-0" style="letter-spacing: -0.5px;">${formatRupiah(menu.harga)}</h5>
                                            </div>
                                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-light transition-all" style="width: 32px; height: 32px; ${iconCart}">
                                                <i class="bi bi-cart-plus-fill fs-5"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                        menuContainer.insertAdjacentHTML('beforeend', cardHtml);
                    });
                });
            });
        }
    });
</script>
@include('layouts.loading')
</body>
</html>