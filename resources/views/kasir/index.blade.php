<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Kasir - Flavory.id</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    @include('layouts.favicon')
    @include('layouts.loading')

    <style>
        :root { 
            --primary-orange: #fd7e14; 
            --dark-orange: #e86c00; 
            --soft-orange: #fff4e6;
        }
        
        body { font-family: 'Poppins', sans-serif; background-color: #fff7ed ; color: #333; }
        
        .navbar-flavory { 
            background: white; 
            border-bottom: 3px solid var(--primary-orange); 
            padding: 0.5rem 1.5rem;
        }

        .order-focus-orange {
    border: 2px solid var(--primary-orange) !important;
    animation: orangeGlow 5s ease-out forwards;
}

@keyframes orangeGlow {
    0% { 
        box-shadow: 0 0 25px rgba(253, 126, 20, 0.8); 
        transform: scale(1.02);
    }
    10% { transform: scale(1); }
    100% { 
        box-shadow: 0 4px 12px rgba(0,0,0,0.03); 
        border: 2px solid transparent !important;
    }
}

        .modal-custom-height .modal-body {
        height: 80vh; /* Mengatur tinggi modal 80% dari tinggi layar */
        overflow: hidden; /* Mencegah scroll ganda pada modal */
    }
    #orderIframe {
        width: 100%;
        height: 100%;
        border: none;
    }

        .outlet-profile-img {
            width: 35px; height: 35px; object-fit: cover;
            border-radius: 8px; border: 2px solid var(--soft-orange);
        }

       /* Card Enhancement */
.order-list-item {
    background: white;
    border-radius: 20px; /* Lebih bulat agar modern */
    padding: 1.25rem;
    margin-bottom: 1.25rem;
    border: 1px solid rgba(0,0,0,0.05);
    border-left: 8px solid var(--primary-orange);
    box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.order-list-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(253, 126, 20, 0.1);
    border-color: var(--soft-orange);
}

/* Meja Badge */
.table-badge {
    background: var(--soft-orange);
    color: var(--dark-orange);
    padding: 10px;
    border-radius: 12px;
    min-width: 65px;
    border: 1px solid rgba(253, 126, 20, 0.2);
}

/* Order Item List */
.item-row {
    padding: 4px 0;
    border-bottom: 1px dashed #f0f0f0;
}
.item-row:last-child { border-bottom: none; }

/* Action Buttons */
.btn-action-light {
    background: #f8f9fa;
    border: none;
    color: #6c757d;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    transition: 0.2s;
}
.btn-action-light:hover {
    background: #e9ecef;
    color: var(--dark-orange);
}
.btn-pay-now {
    border-radius: 12px;
    padding: 12px 20px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.wa-link {
    color: #25d366;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 500;
}
.wa-link:hover { color: #128c7e; }

.note-box {
    background: #fff9f0;
    border-radius: 8px;
    padding: 8px 12px;
    border-left: 3px solid #ffc107;
    margin-top: 10px;
    font-style: italic;
    font-size: 0.8rem;
    color: #666;
}
.date-badge {
    font-size: 0.75rem;
    color: #888;
    display: flex;
    align-items: center;
    gap: 4px;
}
        
        .btn-orange { 
            background-color: var(--primary-orange); color: white; 
            border: none; font-weight: 600; border-radius: 10px; transition: 0.3s; 
        }
        .btn-orange:hover { background-color: var(--dark-orange); color: white; }
        .btn-orange:disabled { background-color: #ccc; cursor: not-allowed; }

        .search-input { border-radius: 50px; padding-left: 45px; border: 2px solid transparent; }
        .search-input:focus { border-color: var(--primary-orange); box-shadow: none; }

        .modal-custom-height .modal-content { height: 90vh; }

      /* Styling Modal Pembayaran */
.modal-payment-modern .modal-content {
    border-radius: 24px;
    border: none;
}

.total-bill-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 20px;
    padding: 25px;
    text-align: center;
    border: 1px solid rgba(0,0,0,0.05);
}

.total-bill-card h1 {
    font-size: 2.5rem;
    font-weight: 800;
    color: #2d3436;
    letter-spacing: -1px;
    margin-bottom: 0;
}

/* Custom Payment Method Radio Cards */
.payment-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}

.payment-item {
    cursor: pointer;
    position: relative;
}

.payment-item input {
    display: none; /* Sembunyikan radio button asli */
}

.payment-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 15px 10px;
    background: #ffffff;
    border: 2px solid #f1f1f1;
    border-radius: 16px;
    transition: all 0.2s ease;
    height: 100%;
}

.payment-card i {
    font-size: 1.5rem;
    margin-bottom: 8px;
    color: #6c757d;
}

.payment-card span {
    font-size: 0.8rem;
    font-weight: 700;
    color: #495057;
}

/* State Saat Dipilih */
.payment-item input:checked + .payment-card {
    border-color: var(--primary-orange);
    background-color: #fff9f4;
}

.payment-item input:checked + .payment-card i,
.payment-item input:checked + .payment-card span {
    color: var(--primary-orange);
}

/* Input Amount Styling */
.input-pay-wrapper {
    position: relative;
}

.input-pay-wrapper .currency-label {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-weight: 700;
    color: #adb5bd;
}

.input-pay-custom {
    padding: 18px 20px 18px 50px !important;
    font-size: 1.25rem !important;
    font-weight: 700 !important;
    border-radius: 15px !important;
    border: 2px solid #f1f1f1 !important;
    background-color: #f8f9fa !important;
}

.input-pay-custom:focus {
    border-color: var(--primary-orange) !important;
    background-color: #fff !important;
    box-shadow: 0 0 0 4px rgba(253, 126, 20, 0.1) !important;
}

/* Change/Debt Section */
.result-box {
    background: #ffffff;
    border-radius: 15px;
    padding: 15px;
    border: 1px solid #eee;
}

/* Mengecilkan Lebar dan Spasi Modal */
#paymentModal .modal-dialog {
    max-width: 400px;
}

#paymentModal .modal-content {
    border-radius: 15px;
}

/* Memadatkan Total Tagihan */
.total-bill-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 15px !important; /* Diperkecil */
    margin-bottom: 15px !important;
}

.total-bill-card h1 {
    font-size: 1.8rem !important; /* Diperkecil dari 2.5rem */
    margin-top: 5px;
}

/* Memadatkan Kartu Metode */
.payment-card {
    padding: 8px 5px !important; /* Lebih tipis */
    border-radius: 10px;
}

.payment-card i {
    font-size: 1.2rem !important; /* Icon lebih kecil */
    margin-bottom: 2px !important;
}

/* Menyesuaikan Input dan Button */
.input-pay-custom {
    padding: 12px 15px 12px 45px !important; /* Lebih ramping */
    font-size: 1.1rem !important;
}

.quick-cash-btn {
    padding: 5px !important; /* Tombol nominal lebih pendek */
    font-size: 0.8rem !important;
}

/* Tambahkan kode ini di barisan paling bawah dalam <style> index.blade.php */
@media (max-width: 768px) {
    #orderModal .modal-dialog {
        margin: 0;
        width: 100vw;
        max-width: none;
        height: 100vh;
    }
    #orderModal .modal-content {
        height: 100vh !important;
        border-radius: 0;
    }
    .modal-custom-height .modal-body {
        height: calc(100vh - 55px); /* Menyisakan ruang untuk header modal */
        overflow: hidden;
    }
}
    </style>
</head>
<body>
<div id="offline-banner" class="bg-danger text-white text-center py-2 fw-bold w-100 shadow-sm transition-all" style="display: none; position: sticky; top: 0; z-index: 9999; letter-spacing: 1px;">
    <i class="bi bi-wifi-off me-2"></i> ANDA DALAM MODE OFFLINE
</div>
@include('kasir.layouts.app')
<div class="container py-4">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 border-bottom pb-3 gap-3">
    
    <h4 class="fw-bold mb-0" style="color: var(--dark-orange);">
        <i class="bi bi-pc-display-horizontal me-2"></i> DASHBOARD KASIR
    </h4>
    
    <div class="d-flex align-items-center flex-wrap gap-3 bg-white px-4 py-2 rounded-4 shadow-sm border mb-0">
        
        <div class="form-check form-switch mb-0">
            <input class="form-check-input" type="checkbox" role="switch" id="globalBulkCheck" style="cursor: pointer;" onchange="toggleBulkMode()">
            <label class="form-check-label fw-bold text-muted small" for="globalBulkCheck" style="cursor: pointer; padding-top: 2px;">
                Pilih Multi Pesanan
            </label>
        </div>
        
        <div class="vr d-none d-sm-block bg-secondary opacity-25"></div>
        
        <div class="form-check form-switch mb-0">
            <input class="form-check-input" type="checkbox" role="switch" id="toggleSoundSwitch" onchange="toggleSound()" autocomplete="off" {{ $toggleSound->is_sound_enabled ? 'checked' : '' }} style="cursor: pointer;">
            <label class="form-check-label fw-bold text-muted small" for="toggleSoundSwitch" id="soundText" style="cursor: pointer; padding-top: 2px;">
                {{ $toggleSound->is_sound_enabled ? 'Suara Notifikasi: ON' : 'Suara Notifikasi: OFF' }}
            </label>
        </div>
        
    </div>
    
</div>
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <div class="position-relative">
                <i class="bi bi-search position-absolute" style="left: 30px; top: 50%; transform: translateY(-50%); color: var(--primary-orange)"></i>
                <input type="text" id="menuSearch" class="form-control form-control-lg search-input shadow-sm" placeholder="  Cari pelanggan atau nomor pesanan...">
            </div>
        </div>
        
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <button class="btn btn-orange px-4 py-2 w-100 w-md-auto" onclick="openOrderModal()">
                <i class="bi bi-plus-lg me-2"></i> TAMBAH PESANAN
            </button>
        </div>
    </div>

    <div id="bulkConfirmContainer" class="alert d-flex justify-content-between align-items-center mb-4 shadow-sm" style="display: none !important; background-color: var(--soft-orange); border: 2px dashed var(--primary-orange);">
        <div class="d-flex align-items-center gap-2 text-dark">
            <i class="bi bi-check-square-fill fs-4" style="color: var(--primary-orange);"></i>
            <span class="fw-bold fs-5"><span id="bulkSelectedCount">0</span> Pesanan Terpilih</span>
        </div>
        <button class="btn btn-success fw-bold px-4 py-2 shadow-sm rounded-pill" onclick="processBulkConfirmation()">
            <i class="bi bi-check-circle-fill me-2"></i> KONFIRMASI MASSAL
        </button>
    </div>

    <div id="order-container">
        <div class="text-center py-5" id="loader">
            <div class="spinner-border text-warning" role="status"></div>
            <p class="mt-2 text-muted">Memuat data pesanan...</p>
        </div>
    </div>
</div>

<div class="modal fade modal-custom-height" id="orderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="orderModalTitle">Form Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="orderIframe" src=""></iframe>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">Pembayaran</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body py-2"> <div class="total-bill-card text-center mb-3">
                    <span class="text-muted small fw-bold text-uppercase opacity-75" style="font-size: 0.7rem;">Total Tagihan</span>
                    <h1 class="fw-bold mb-0" id="pay-total-display">Rp 0</h1>
                </div>

                <div class="mb-3">
                    <div class="payment-grid">
                            <label class="payment-item">
                                <input type="radio" name="pay_method_radio" value="cash" checked onchange="updateMethodValue(this.value)">
                                <div class="payment-card shadow-sm">
                                    <i class="bi bi-cash-stack"></i>
                                    <span>CASH</span>
                                </div>
                            </label>
                            <label class="payment-item">
                                <input type="radio" name="pay_method_radio" value="qris" onchange="updateMethodValue(this.value)">
                                <div class="payment-card shadow-sm">
                                    <i class="bi bi-qr-code-scan"></i>
                                    <span>QRIS</span>
                                </div>
                            </label>
                            <label class="payment-item">
                                <input type="radio" name="pay_method_radio" value="transfer" onchange="updateMethodValue(this.value)">
                                <div class="payment-card shadow-sm">
                                    <i class="bi bi-bank"></i>
                                    <span>TRANSFER</span>
                                </div>
                            </label>
                        </div>
                    <input type="hidden" id="pay-method" value="cash">
                </div>

                <div class="mb-3">
                    <div class="input-pay-wrapper mb-2">
                        <span class="currency-label" style="left: 15px;">Rp</span>
                        <input type="number" class="form-control input-pay-custom" id="pay-amount" placeholder="0">
                    </div>
                </div>
                @if($pengaturan->bukti_pembayaran)
                <div id="bukti-bayar-section" style="display: none;" class="mb-3">
                    <label class="small fw-bold text-muted mb-2"><i class="bi bi-camera-fill me-1"></i> BUKTI PEMBAYARAN <span class="text-danger">*</span></label>
                    <div class="border rounded-3 p-3 text-center position-relative" style="background: #f8f9fa;">
                        
                        <div id="camera-container">
                            <video id="kamera-live" autoplay playsinline class="w-100 rounded shadow-sm mb-2" style="max-height: 250px; object-fit: cover; background: #000;"></video>
                            <button type="button" class="btn btn-orange w-100 fw-bold shadow-sm" onclick="takeSnapshot()">
                                <i class="bi bi-camera-fill fs-5"></i> AMBIL FOTO
                            </button>
                        </div>

                        <div id="preview-container" style="display: none;">
                            <img id="preview-image" src="" alt="Preview Bukti" class="img-fluid rounded shadow-sm border" style="max-height: 180px; object-fit: contain;">
                            <div class="mt-2 fw-bold small" id="file-size-info"></div>
                            
                            <button type="button" class="btn btn-outline-danger mt-3 btn-sm w-100 fw-bold" onclick="retakePhoto()">
                                <i class="bi bi-arrow-repeat"></i> FOTO ULANG
                            </button>
                        </div>

                    </div>
                </div>
                @endif

                <div class="result-box bg-light border-0 p-2">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small fw-bold text-muted" style="font-size: 0.75rem;">KEMBALI</span>
                        <span class="fw-bold text-success" id="pay-change">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="small fw-bold text-muted" style="font-size: 0.75rem;">KURANG</span>
                        <span class="fw-bold text-danger" id="pay-debt">Rp 0</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 p-3 pt-0">
                <button type="button" class="btn btn-orange w-100 py-2 fw-bold shadow-sm" id="btn-confirm-pay" onclick="confirmPayment()">
                    KONFIRMASI BAYAR
                </button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('pwa-order.js') }}"></script>
<script src="{{ asset('pwa-kasir.js') }}"></script>
<script>
    // ==========================================
    // FUNGSI RENDER ULANG DATA OFFLINE (TanPA RELOAD)
    // ==========================================
    async function refreshOfflineUI() {
        // 0. Reset daftar currentOrders ke data asli dari Server
        currentOrders = @json($orders);

        // 1. Ambil antrean dari IndexedDB dan gabungkan ke list UI
        const offlineOrders = await getOfflineOrders();
        offlineOrders.forEach(o => {
            let fakeOrder = {
                id: 'offline-' + o.id,
                nomor_pesanan: o.offline_nomor,
                nama_pelanggan: o.nama_pelanggan,
                no_whatsapp: o.no_whatsapp,
                no_meja: o.no_meja,
                keterangan: o.keterangan,
                created_at: new Date(o.timestamp).toISOString(),
                total_harga: o.cart.reduce((sum, item) => sum + (item.harga * item.qty), 0),
                order_details: o.cart.map(c => ({
                    menu_id: c.id, 
                    menu: { 
                        nama_menu: c.nama.split(' (')[0],
                        stok_menu: c.stok_menu 
                    },
                    variant: c.variant_name,
                    qty: c.qty,
                    harga: c.harga
                })),
                is_offline: true
            };
            currentOrders.unshift(fakeOrder);
        });

        // 2. Timpa list dengan hasil edit offline (Optimistic UI)
        const pendingEdits = JSON.parse(localStorage.getItem('offline_pending_edits') || '{}');
        
        currentOrders = currentOrders.map(order => {
            if (pendingEdits[order.id]) {
                const newData = pendingEdits[order.id];
                order.nama_pelanggan = newData.nama_pelanggan;
                order.no_meja = newData.no_meja;
                order.no_whatsapp = newData.no_whatsapp;
                order.keterangan = newData.keterangan; 
                
                order.total_harga = newData.cart.reduce((sum, item) => sum + (item.harga * item.qty), 0);
                
                order.order_details = newData.cart.map(c => ({
                    menu_id: c.id,
                    menu: { 
                        nama_menu: c.nama.split(' (')[0], 
                        stok_menu: c.stok_menu 
                    },
                    variant: c.variant_name,
                    qty: c.qty,
                    harga: c.harga
                }));
            }
            return order;
        });

        renderOrders();
    }

    document.addEventListener('DOMContentLoaded', async function() {
        if (currentOrders.length > 0) {
            const maxNum = Math.max(...currentOrders.map(o => parseInt(o.nomor_pesanan) || 0));
            await saveLastOrderNumber(maxNum);
        }

        // Panggil fungsi perakitan data saat halaman pertama dimuat
        await refreshOfflineUI();
    });
    // ==========================================
    // MANAJEMEN JARINGAN & SINKRONISASI
    // ==========================================
    function updateBannerUI() {
        const offlineBanner = document.getElementById('offline-banner');
        if (navigator.onLine) {
            offlineBanner.style.display = 'none';
        } else {
            offlineBanner.style.display = 'block';
        }
        renderOrders(); // Render ulang agar tombol edit muncul/hilang
    }

    setTimeout(updateBannerUI, 1000);

    window.addEventListener('offline', updateBannerUI);

    window.addEventListener('online', async () => {
        updateBannerUI();
        if (window.showLoading) window.showLoading();

        // 1. Sinkronkan pesanan baru dari pwa-order.js (Tunggu sampai selesai)
        if (typeof syncOfflineOrders === 'function') {
            await syncOfflineOrders(); 
        }
        
        // 2. Setelah itu, baru sinkronkan aksi kasir dari pwa-kasir.js
        if (typeof syncKasirActions === 'function') {
            await syncKasirActions();
        }

        // 3. Hapus cache edit optimistic SETELAH kedua sync selesai
        localStorage.removeItem('offline_pending_edits');
        if (window.hideLoading) window.hideLoading();
    });

    window.addEventListener('message', async function(event) {
        if (event.data.type === 'ORDER_SUCCESS') {
            // Jika pesanan Online, aman untuk reload
            window.location.reload(); 
        } else if (event.data.type === 'OFFLINE_ORDER_SAVED') {
            // JANGAN RELOAD HALAMAN! Tutup modal dan refresh Data UI saja
            if (orderModalObj) orderModalObj.hide();
            await refreshOfflineUI();
            
            Swal.fire({
                toast: true, position: 'top-end', icon: 'success',
                title: 'Tersimpan Offline', 
                text: 'Pesanan masuk ke antrean lokal.',
                showConfirmButton: false, timer: 3000
            });
        } else if (event.data.type === 'GOTO_MENU') {
            // Tombol "Atur Detail" dari modal tambah menu cepat
            if (orderModalObj) orderModalObj.hide();
            window.location.href = "{{ route('kasir.menu.index') }}";
        } else if (event.data.type === 'MENU_ADDED') {
            // Menu baru ditambahkan dari order iframe, tidak perlu aksi di parent
            // iframe sudah reload sendiri
        }
    });

    // ==========================================
    // FITUR SUARA
    // ==========================================
    let isSoundEnabled = @json((bool)$toggleSound->is_sound_enabled);

    function updateSoundUI() {
        const switchInput = document.getElementById('toggleSoundSwitch');
        const textLabel = document.getElementById('soundText');

        if (!switchInput || !textLabel) return; 

        switchInput.checked = isSoundEnabled;

        if (isSoundEnabled) {
            textLabel.innerText = 'Suara Notifikasi: ON';
            textLabel.classList.remove('text-muted');
            textLabel.classList.add('text-success');
        } else {
            textLabel.innerText = 'Suara Notifikasi: OFF';
            textLabel.classList.remove('text-success');
            textLabel.classList.add('text-muted');
        }
    }

    window.toggleSound = function() {
        isSoundEnabled = !isSoundEnabled;
        updateSoundUI();
        
        fetch("{{ route('kasir.update-sound') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ is_sound_enabled: isSoundEnabled })
        })
        .then(response => response.json())
        .then(data => console.log("Status suara disimpan ke DB:", data))
        .catch(error => console.error("Gagal menyimpan suara:", error));
        
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel(); 
            let teksFeedback = isSoundEnabled ? "Suara notifikasi pesanan online diaktifkan, mantap!" : "Suara notifikasi pesanan online dinonaktifkan, oke";
            const feedbackPesan = new SpeechSynthesisUtterance(teksFeedback);
            feedbackPesan.lang = 'id-ID'; 
            window.speechSynthesis.speak(feedbackPesan);
        }
    };

    // ==========================================
    // FITUR REAL-TIME (PUSHER)
    // ==========================================
    Pusher.logToConsole = true; 

    var pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
        cluster: '{{ env("PUSHER_APP_CLUSTER", "ap1") }}'
    });

    var channel = pusher.subscribe('kasir.{{ Auth::user()->partner->id }}');
    
    channel.bind('pesanan.baru', function(data) {
        console.log("Data masuk dari Pusher:", data);
        const namaPelanggan = data.order.nama_pelanggan || 'Pelanggan';

        if (isSoundEnabled && 'speechSynthesis' in window) {
            const pesan = new SpeechSynthesisUtterance();
            pesan.text = `yuhuu!!, Orderan Baru Nih dari ${namaPelanggan}`; 
            pesan.lang = 'id-ID'; 
            pesan.rate = 1.0;     
            pesan.pitch = 1.0;    
            window.speechSynthesis.speak(pesan);
        }

        currentOrders.unshift(data.order);
        localStorage.setItem('new_order_id', data.order.id);
        renderOrders();

        Swal.fire({
            toast: true, position: 'top-end', icon: 'success',
            title: `Orderan Baru: ${namaPelanggan}`, showConfirmButton: false, timer: 5000
        });
    });

    // ==========================================
    // DATA & RENDER LIST PESANAN
    // ==========================================
    let currentOrders = @json($orders); 
    let selectedOrderForPay = null;
    let orderModalObj, paymentModalObj;

    document.addEventListener('DOMContentLoaded', function() {
        orderModalObj = new bootstrap.Modal(document.getElementById('orderModal'));
        paymentModalObj = new bootstrap.Modal(document.getElementById('paymentModal'));
        renderOrders();
    });

    // ==========================================
    // LOGIKA MULTI-KONFIRMASI (BULK UPDATE)
    // ==========================================
    function toggleBulkMode() {
        const isChecked = document.getElementById('globalBulkCheck').checked;
        const checkboxes = document.querySelectorAll('.bulk-checkbox-container');
        const bulkContainer = document.getElementById('bulkConfirmContainer');
        
        // Tampilkan atau sembunyikan checkbox di tiap card
        checkboxes.forEach(cb => {
            cb.style.display = isChecked ? 'block' : 'none';
        });

        // Reset state jika dimatikan
        if (!isChecked) {
            document.querySelectorAll('.bulk-order-check').forEach(input => input.checked = false);
            bulkContainer.style.setProperty('display', 'none', 'important');
            updateBulkCount();
        } else {
            updateBulkCount();
        }
    }

    function updateBulkCount() {
        const isChecked = document.getElementById('globalBulkCheck').checked;
        const checkedBoxes = document.querySelectorAll('.bulk-order-check:checked');
        const count = checkedBoxes.length;
        const bulkContainer = document.getElementById('bulkConfirmContainer');
        
        document.getElementById('bulkSelectedCount').innerText = count;

        // Munculkan tombol konfirmasi besar di atas jika ada minimal 1 yang dipilih
        if (isChecked && count > 0) {
            bulkContainer.style.setProperty('display', 'flex', 'important');
        } else {
            bulkContainer.style.setProperty('display', 'none', 'important');
        }
    }

    async function processBulkConfirmation() {
        const checkedBoxes = document.querySelectorAll('.bulk-order-check:checked');
        if (checkedBoxes.length === 0) return;

        // Ambil semua ID pesanan yang dicentang
        const orderIds = Array.from(checkedBoxes).map(cb => cb.value);

        Swal.fire({
            title: 'Konfirmasi Massal?',
            text: `Anda akan menyelesaikan ${orderIds.length} pesanan menggunakan metode CASH.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            confirmButtonText: 'Ya, Konfirmasi Semua',
            cancelButtonText: 'Batal'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                    // Panggil route bulk yang baru kita buat di Controller
                    const response = await fetch("{{ route('kasir.konfirmasi.bulk') }}", {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json', 
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ order_ids: orderIds })
                    });

                    const res = await response.json();
                    if (!response.ok || !res.success) throw new Error(res.message || 'Server Error');

                    Swal.fire('Berhasil!', res.message, 'success').then(() => {
                        window.location.reload();
                    });
                } catch (e) {
                    Swal.fire('Gagal', e.message, 'error');
                }
            }
        });
    }

    function renderOrders() {
        const container = document.getElementById('order-container');
        const loader = document.getElementById('loader');
        const highlightedId = localStorage.getItem('new_order_id');
        
        if (loader) loader.style.display = 'none';
        container.innerHTML = '';

        if (!currentOrders || currentOrders.length === 0) {
            container.innerHTML = `
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                    Belum ada antrean pesanan aktif hari ini.
                </div>`;
            return;
        }
        
        window.toggleServing = function(orderId, status) {
    const cbBelum = document.getElementById(`belum-${orderId}`);
    const cbSudah = document.getElementById(`sudah-${orderId}`);
    const badgeContainer = document.getElementById(`badge-serving-${orderId}`);
    
    // Logika mutually exclusive (hanya boleh centang salah satu)
    if (status === 'belum') {
        cbBelum.checked = true; // Paksa tetap checked jika user iseng meng-klik lagi
        cbSudah.checked = false;
    } else {
        cbSudah.checked = true;
        cbBelum.checked = false;
    }

    const isSudah = cbSudah.checked;
    
    // Update label visual (badge)
    if (isSudah) {
        badgeContainer.innerHTML = `<span class="badge bg-success px-3 py-2 fs-6 shadow-sm w-100 text-center"><i class="bi bi-check-circle-fill me-1"></i> Sudah Disajikan</span>`;
    } else {
        badgeContainer.innerHTML = `<span class="badge bg-danger px-3 py-2 fs-6 shadow-sm w-100 text-center"><i class="bi bi-x-circle-fill me-1"></i> Belum Disajikan</span>`;
    }

    // Simpan state di browser secara instan
    localStorage.setItem(`serving_status_${orderId}`, isSudah ? 'sudah' : 'belum');

    // Opsional: Kirim data ke server (berjalan senyap di background)
    if (navigator.onLine && !String(orderId).startsWith('offline-')) {
        fetch(`/kasir/order/${orderId}/status-penyajian`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status_penyajian: isSudah ? 'sudah' : 'belum' })
        }).catch(e => console.log('Pembaruan di server tertunda (aman via local state).'));
    }
};

        currentOrders.forEach(order => {
            const highlightClass = (order.id == highlightedId) ? 'order-focus-orange' : '';
            
            const d = new Date(order.created_at);
            const hari = d.toLocaleDateString('id-ID', { weekday: 'long' });
            const tanggal = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            const jam = d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

            const isOffline = !navigator.onLine || order.is_offline;
            const btnEditHtml = isOffline 
                ? '' 
                : `<button class="btn btn-sm btn-light border" onclick="editOrder('${order.id}')" title="Edit">
                        <i class="bi bi-pencil-square"></i>
                   </button>`;

            const btnHapusHtml = isOffline 
                ? '' 
                : `<button class="btn btn-sm btn-danger border ms-1" onclick="deleteOrder('${order.id}')" title="Hapus">
                        <i class="bi bi-trash"></i>
                   </button>`;

            // Cek status checkbox global untuk menampilkan checkbox per-card
            const globalCheck = document.getElementById('globalBulkCheck');
            const isBulkMode = globalCheck && globalCheck.checked;
            const bulkDisplay = isBulkMode ? 'block' : 'none';
            
            // Buat elemen checkbox (hanya muncul jika sedang online/bukan data offline)
            const checkboxHtml = !isOffline ? `
                <div class="bulk-checkbox-container" style="display: ${bulkDisplay}; position: absolute; top: 15px; right: 15px; z-index: 10;">
                    <input class="form-check-input border-secondary shadow-sm bulk-order-check" type="checkbox" value="${order.id}" style="transform: scale(1.6); cursor: pointer;" onchange="updateBulkCount()">
                </div>
            ` : '';

            // 1. CEK: Apakah pesanan ini berasal dari Offline? (Lewat flagging OR dari teks keterangan DB)
            const hasOfflineText = order.keterangan && typeof order.keterangan === 'string' && order.keterangan.toLowerCase().includes('order via pwa offline');
            const isOriginallyOffline = order.is_offline || hasOfflineText;

            // 2. RENDER: Badge merah dimunculkan menggunakan logika di atas (jadi tidak akan pernah hilang)
            const offlineBadgeHtml = isOriginallyOffline ? `
                <div style="position: absolute; top: 0; left: 0; background-color: #dc3545; color: white; padding: 4px 12px; font-size: 0.7rem; font-weight: bold; border-bottom-right-radius: 12px; z-index: 10; letter-spacing: 0.5px;">
                    PESANAN OFFLINE
                </div>
            ` : '';
            
            const savedStatus = localStorage.getItem(`serving_status_${order.id}`) || 'belum';
            const isSudah = savedStatus === 'sudah';
            const isBelum = savedStatus === 'belum';

            const servingBadgeHtml = isSudah 
                ? `<span class="badge bg-success px-3 py-2 fs-6 shadow-sm w-100 text-center"><i class="bi bi-check-circle-fill me-1"></i> Sudah Disajikan</span>`
                : `<span class="badge bg-danger px-3 py-2 fs-6 shadow-sm w-100 text-center"><i class="bi bi-x-circle-fill me-1"></i> Belum Disajikan</span>`;

            const servingHtml = `
                <div class="mt-3 p-3 rounded-3" style="background-color: #f8f9fa; border: 1px solid #e9ecef;">
                    <span class="d-block small text-muted text-uppercase fw-bold ls-wide mb-2">Status Penyajian</span>
                    <div class="d-flex gap-3 align-items-center mb-3">
                        <div class="form-check mb-0">
                            <input class="form-check-input border-secondary" type="checkbox" id="belum-${order.id}" value="belum" onchange="toggleServing('${order.id}', 'belum')" ${isBelum ? 'checked' : ''} style="cursor:pointer; transform: scale(1.2);">
                            <label class="form-check-label fw-bold text-danger ms-1" for="belum-${order.id}" style="cursor:pointer;">Belum Disajikan</label>
                        </div>
                        <div class="form-check mb-0">
                            <input class="form-check-input border-secondary" type="checkbox" id="sudah-${order.id}" value="sudah" onchange="toggleServing('${order.id}', 'sudah')" ${isSudah ? 'checked' : ''} style="cursor:pointer; transform: scale(1.2);">
                            <label class="form-check-label fw-bold text-success ms-1" for="sudah-${order.id}" style="cursor:pointer;">Sudah Disajikan</label>
                        </div>
                    </div>
                    <div id="badge-serving-${order.id}">
                        ${servingBadgeHtml}
                    </div>
                </div>
            `;

            // 3. CLEAN: Hapus teks penanda (Order via PWA Offline) agar tampilan UI catatan tetap bersih!
            const cleanKeterangan = order.keterangan ? order.keterangan.replace(/\s*\({0,1}Order via PWA Offline\){0,1}/gi, '').trim() : '';

            const listHtml = `
            <div id="order-card-${order.id}" class="order-list-item ${highlightClass} position-relative">
                ${offlineBadgeHtml}
                ${checkboxHtml}
                <div class="row">
                    <div class="col-lg-3 col-md-4 mb-3 mb-md-0 border-end-md">
                        <div class="d-flex align-items-center mb-3">
                            <div class="table-badge text-center me-3 p-2 bg-light rounded-3" style="min-width: 60px;">
                                <span class="d-block small fw-bold opacity-75">MEJA</span>
                                <span class="fw-bold fs-4 text-dark">${order.no_meja || '-'}</span>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">#${order.nomor_pesanan}</h6>
                                <div class="date-badge small text-muted">
                                    <i class="bi bi-clock"></i> ${jam}
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-2">
                            <span class="d-block small text-muted text-uppercase fw-bold ls-wide">Pelanggan</span>
                            <div class="fw-bold text-dark">${order.nama_pelanggan}</div>
                            ${order.no_whatsapp ? `
                                <a href="https://wa.me/${order.no_whatsapp}" target="_blank" class="wa-link small text-success text-decoration-none">
                                    <i class="bi bi-whatsapp"></i> ${order.no_whatsapp}
                                </a>
                            ` : '<small class="text-muted">No WA tidak ada</small>'}
                        </div>

                        <div class="small text-muted mt-2 pt-2 border-top">
                            <i class="bi bi-calendar3"></i> ${hari}, ${tanggal}
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-8 mb-3 mb-md-0 border-end-lg">
                        <span class="d-block small text-muted text-uppercase fw-bold ls-wide mb-2">Rincian Menu</span>
                        <div class="pe-lg-3 mb-2" style="max-height: 150px; overflow-y: auto;">
                            ${order.order_details.map(d => `
                                <div class="d-flex justify-content-between align-items-center mb-1 pb-1 border-bottom border-light">
                                    <div>
                                        <span class="small fw-bold text-dark d-block">${d.menu ? d.menu.nama_menu : 'Menu Terhapus'}</span>
                                        ${d.variant ? `<span class="small text-orange fst-italic" style="font-size: 0.75rem;">(+ ${d.variant})</span>` : ''}
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-light text-dark me-2">x${d.qty}</span>
                                        <span class="small fw-bold text-dark">Rp ${(d.harga * d.qty).toLocaleString('id-ID')}</span>
                                    </div>
                                </div>
                            `).join('')}
                        </div>

                        ${cleanKeterangan ? `
                            <div class="p-2 bg-warning bg-opacity-10 border-start border-warning border-3 rounded-1 small mb-2">
                                <i class="bi bi-info-circle-fill text-warning me-1"></i>
                                <strong>Catatan:</strong> ${cleanKeterangan}
                            </div>
                        ` : ''}
                         ${servingHtml}
                    </div>

                    <div class="col-lg-5 mt-3 mt-lg-0">
                        <div class="d-flex flex-column h-100 justify-content-between">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="small text-muted d-block mb-1">Total Tagihan</span>
                                    
                                    ${order.nominal_diskon && parseFloat(order.nominal_diskon) > 0 ? `
                                        <div class="mb-1">
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle px-2 py-1" style="font-size: 0.7rem;">
                                                <i class="bi bi-tag-fill me-1"></i>Diskon ${order.jenis_diskon === '%' ? parseFloat(order.nominal_diskon) + '%' : 'Rp ' + parseInt(order.nominal_diskon).toLocaleString('id-ID')}
                                            </span>
                                        </div>
                                    ` : ''}
                                    <span class="fw-bold fs-3 text-orange lh-1 d-block" style="color: #fd7e14">
                                        Rp ${Math.ceil(parseFloat(order.total_harga)).toLocaleString('id-ID')}
                                    </span>
                                </div>
                                <div class="d-flex gap-2 mt-1">
                                    ${btnEditHtml}
                                    ${btnHapusHtml}
                                </div>
                            </div>

                            <div class="d-flex flex-column gap-2">
                                <a href="/kasir/print-kitchen-bt/${order.id}" 
                                   class="btn btn-outline-secondary w-100 shadow-sm d-flex align-items-center justify-content-center gap-2 py-2">
                                    <i class="bi bi-bluetooth"></i>
                                    <span class="fw-bold" style="font-size: 0.8rem;">STRUK KITCHEN</span>
                                </a>

                                <button class="btn btn-orange btn-pay-now w-100 shadow-sm d-flex align-items-center justify-content-center gap-2 py-3" style="background-color: #fd7e14; color: white;"
                                                onclick="openPaymentModal('${order.id}')">
                                    <i class="bi bi-wallet2 fs-4"></i>
                                    <span class="fw-bold" style="font-size: 1.1rem;">BAYAR SEKARANG</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

            container.insertAdjacentHTML('beforeend', listHtml);
        });
        
        if (highlightedId) {
            setTimeout(() => {
                localStorage.removeItem('new_order_id');
                const el = document.getElementById(`order-card-${highlightedId}`);
                if (el) el.classList.remove('order-focus-orange');
            }, 5000);
        }
    }

    // ==========================================
    // AKSI PESANAN (CARI, TAMBAH, EDIT, HAPUS)
    // ==========================================
    async function handleAction(url, method, data = {}) {
        Swal.fire({ title: 'Sedang Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        try {
            const response = await fetch(url, {
                method: method,
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: method !== 'GET' ? JSON.stringify(data) : null
            });

            const res = await response.json();
            if (!response.ok || !res.success) throw new Error(res.message || 'Server Error');

            Swal.fire('Berhasil!', res.message, 'success').then(() =>{
                if (url.includes('/kasir/bayar/')) {
                    localStorage.setItem('last_paid_order_id', selectedOrderForPay.id);
                    window.location.href = "{{ route('kasir.riwayat.index') }}";
                } else {
                    window.location.reload();
                }
            });
        } catch (e) {
            Swal.fire('Gagal', e.message, 'error');
        }
    }

    document.getElementById('menuSearch').addEventListener('keyup', function(e) {
        const val = e.target.value.toLowerCase();
        document.querySelectorAll('.order-list-item').forEach(card => {
            card.style.display = card.innerText.toLowerCase().includes(val) ? 'block' : 'none';
        });
    });

    function openOrderModal() {
        localStorage.removeItem('edit_order_data');
        document.getElementById('orderIframe').src = "{{ route('kasir.order.create') }}";
        orderModalObj.show();
    }

    function editOrder(id) {
        const orderData = currentOrders.find(o => o.id == id);
        if (orderData) {
            localStorage.setItem('edit_order_data', JSON.stringify(orderData));
        }
        document.getElementById('orderIframe').src = "{{ route('kasir.order.create') }}";
        orderModalObj.show();
    }

    function deleteOrder(id) {
        Swal.fire({
            title: 'Hapus Pesanan?',
            text: "Data pesanan ini akan dibatalkan permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Batalkan'
        }).then(async (result) => {
            if (result.isConfirmed) {
                if (navigator.onLine && !String(id).startsWith('offline-')) {
                    handleAction(`/kasir/order/${id}`, 'DELETE');
                } else {
                    if (!String(id).startsWith('offline-')) {
                        await saveOfflineAction('delete', id);
                    }
                    const card = document.getElementById(`order-card-${id}`);
                    if (card) card.style.display = 'none';
                    paymentModalObj.hide();
                    Swal.fire('Terhapus', 'Pesanan dibatalkan dari antrean lokal.', 'success');
                }
            }
        });
    }

    // ==========================================
    // LOGIKA PEMBAYARAN
    // ==========================================
    function openPaymentModal(orderId) {
        // Cari data pesanan berdasarkan ID
        const order = currentOrders.find(o => o.id == orderId);
        if (!order) return;

        if (String(order.id).startsWith('offline-')) {
            Swal.fire(
                'Belum Tersinkron', 
                'Pesanan ini dibuat saat offline dan belum masuk ke server. Harap tunggu internet stabil agar pesanan otomatis tersinkron sebelum dibayar.', 
                'info'
            );
            return; 
        }

        selectedOrderForPay = order;
        document.getElementById('pay-total-display').innerText = `Rp ${Math.ceil(parseFloat(order.total_harga)).toLocaleString('id-ID')}`;
        document.getElementById('pay-amount').value = '';
        
        // --- PERBAIKAN DI SINI ---
        // Sembunyikan preview dan hapus memori gambar sebelumnya (HANYA JIKA ELEMENNYA ADA)
        const previewContainer = document.getElementById('preview-container');
        if (previewContainer) {
            previewContainer.style.display = 'none';
        }
        compressedImageBlob = null;
        
        // Kembalikan kotak kamera ke state awal (HANYA JIKA ELEMENNYA ADA)
        const cameraContainer = document.getElementById('camera-container');
        if (cameraContainer) {
            cameraContainer.style.display = 'block';
        }
        // -------------------------
        
        // Pilih 'cash' sebagai metode default dan trigger UI resetnya
        const cashRadio = document.querySelector('input[name="pay_method_radio"][value="cash"]');
        if(cashRadio) cashRadio.checked = true;
        updateMethodValue('cash');

        calculatePayment();
        paymentModalObj.show();
    }

    function calculatePayment() {
        if (!selectedOrderForPay) return;
        const total = Math.ceil(parseFloat(selectedOrderForPay.total_harga));
        const pay = parseFloat(document.getElementById('pay-amount').value) || 0;
        const diff = pay - total;
        
        document.getElementById('pay-change').innerText = `Rp ${diff >= 0 ? diff.toLocaleString('id-ID') : '0'}`;
        document.getElementById('pay-debt').innerText = `Rp ${diff < 0 ? Math.abs(diff).toLocaleString('id-ID') : '0'}`;
        
        document.getElementById('btn-confirm-pay').disabled = pay < total;
    }

    document.getElementById('pay-amount').addEventListener('input', calculatePayment);

    async function confirmPayment() {
        const pay = parseFloat(document.getElementById('pay-amount').value) || 0;
        const method = document.getElementById('pay-method').value;

        // Validasi Wajib Upload Foto
        const buktiSection = document.getElementById('bukti-bayar-section');

        // Validasi Wajib Foto (Hanya dilakukan JIKA fitur kameranya memang sedang aktif/di-toggle ON)
        if (buktiSection && (method === 'qris' || method === 'transfer') && !compressedImageBlob) {
            Swal.fire({
                icon: 'warning',
                title: 'Bukti Pembayaran Diperlukan',
                text: 'Harap ambil foto bukti pembayaran terlebih dahulu sebelum konfirmasi!'
            });
            return;
        }

        if (navigator.onLine && !String(selectedOrderForPay.id).startsWith('offline-')) {
            // Karena kita mengirim file, kita tidak bisa menggunakan `handleAction` (karena JSON Stringify)
            // Kita menggunakan FormData secara khusus disini.
            const formData = new FormData();
            formData.append('uang_bayar', pay);
            formData.append('metode_bayar', method);
            
            if (compressedImageBlob) {
                // Namakan filenya secara dinamis saat di append
                formData.append('bukti_bayar', compressedImageBlob, `bukti_bayar_${Date.now()}.jpg`);
            }

            Swal.fire({ title: 'Sedang Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            try {
                const response = await fetch(`/kasir/bayar/${selectedOrderForPay.id}`, {
                    method: 'POST',
                    headers: { 
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                        // Jangan tambahkan Content-Type, karena Browser akan mengaturnya ke multipart/form-data otomatis
                    },
                    body: formData
                });

                const res = await response.json();
                if (!response.ok || !res.success) throw new Error(res.message || 'Server Error');

                Swal.fire('Berhasil!', res.message, 'success').then(() => {
                    localStorage.setItem('last_paid_order_id', selectedOrderForPay.id);
                    window.location.href = "{{ route('kasir.riwayat.index') }}";
                });
            } catch (e) {
                Swal.fire('Gagal', e.message, 'error');
            }

        } else {
            // Sinkronisasi offline (Tidak men-support payload gambar untuk kemudahan sinkronisasi IndexedDB saat ini)
            const payloadData = { uang_bayar: pay, metode_bayar: method };
            if (!String(selectedOrderForPay.id).startsWith('offline-')) {
                await saveOfflineAction('pay', selectedOrderForPay.id, payloadData);
            }
            
            const card = document.getElementById(`order-card-${selectedOrderForPay.id}`);
            if (card) card.style.display = 'none';
            paymentModalObj.hide();
            
            Swal.fire('Pembayaran Sukses', 'Tersimpan lokal. Akan tersinkronisasi saat online (Hanya mencatat data, file terkompres tidak disinkron ke server).', 'success');
        }
    }

    function updateMethodValue(val) {
        document.getElementById('pay-method').value = val;
        const inputAmount = document.getElementById('pay-amount');
        const total = selectedOrderForPay ? Math.ceil(parseFloat(selectedOrderForPay.total_harga)) : 0;
        
        // Elemen ini mungkin null jika toggle dimatikan
        const buktiSection = document.getElementById('bukti-bayar-section');

        if (val === 'qris' || val === 'transfer') {
            inputAmount.value = total;
            inputAmount.readOnly = true;
            inputAmount.style.backgroundColor = "#e9ecef"; 
            
            // JIKA fitur kamera aktif (elemen ada), maka tampilkan & nyalakan kamera
            if (buktiSection) {
                buktiSection.style.display = 'block';
                if (!compressedImageBlob) {
                    startCamera();
                }
            }
        } else {
            inputAmount.value = '';
            inputAmount.readOnly = false;
            inputAmount.style.backgroundColor = "#ffffff";
            inputAmount.focus();
            
            // Sembunyikan section & matikan kamera jika elemen ada
            if (buktiSection) {
                buktiSection.style.display = 'none';
                stopCamera();
                compressedImageBlob = null;
            }
        }
        calculatePayment();
    }
    let compressedImageBlob = null; 
    let cameraStream = null; // Menyimpan stream kamera agar bisa dimatikan

    // 1. Menyalakan Kamera
    async function startCamera() {
        const cameraContainer = document.getElementById('camera-container');
        const previewContainer = document.getElementById('preview-container');
        const video = document.getElementById('kamera-live');

        cameraContainer.style.display = 'block';
        previewContainer.style.display = 'none';
        compressedImageBlob = null;

        try {
            // Minta akses kamera belakang (environment)
            cameraStream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'environment' },
                audio: false
            });
            video.srcObject = cameraStream;
        } catch (err) {
            console.error("Gagal mengakses kamera:", err);
            Swal.fire('Izin Kamera', 'Gagal mengakses kamera. Pastikan browser memiliki izin untuk mengakses kamera perangkat Anda.', 'error');
        }
    }

    // 2. Mematikan Kamera
    function stopCamera() {
        if (cameraStream) {
            cameraStream.getTracks().forEach(track => track.stop());
            cameraStream = null;
        }
    }

    // 3. Menjepret Foto & Kompresi via Canvas
    function takeSnapshot() {
        const video = document.getElementById('kamera-live');
        
        // Pastikan video sudah ready
        if (!video.videoWidth) return;

        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');

        // Batasi resolusi maksimal 1024x1024
        const MAX_WIDTH = 1024;
        const MAX_HEIGHT = 1024;
        let width = video.videoWidth;
        let height = video.videoHeight;

        if (width > height) {
            if (width > MAX_WIDTH) { height *= MAX_WIDTH / width; width = MAX_WIDTH; }
        } else {
            if (height > MAX_HEIGHT) { width *= MAX_HEIGHT / height; height = MAX_HEIGHT; }
        }

        canvas.width = width;
        canvas.height = height;
        
        // Gambar frame dari video ke canvas
        ctx.drawImage(video, 0, 0, width, height);

        // Matikan kamera setelah dijepret agar hemat baterai/memori
        stopCamera();
        document.getElementById('camera-container').style.display = 'none';

        Swal.fire({ title: 'Memproses Gambar...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        // Mulai proses kompresi otomatis (Target: < 100KB)
        let quality = 0.8;
        function compress() {
            canvas.toBlob((blob) => {
                if (blob.size > 100 * 1024 && quality > 0.1) {
                    quality -= 0.1;
                    compress(); // Rekursif jika masih kebesaran
                } else {
                    compressedImageBlob = blob;
                    showPreview(blob);
                }
            }, 'image/jpeg', quality);
        }
        compress();
    }

    // 4. Tampilkan Hasil
    function showPreview(blob) {
        const preview = document.getElementById('preview-image');
        const container = document.getElementById('preview-container');
        const info = document.getElementById('file-size-info');
        const sizeKB = (blob.size / 1024).toFixed(2);

        preview.src = URL.createObjectURL(blob);
        container.style.display = 'block';

        if (blob.size < 80 * 1024) {
            info.innerHTML = `Terkompresi: ${sizeKB} KB <i class="bi bi-check-circle-fill text-success"></i>`;
            info.className = "mt-2 fw-bold small text-success";
        } else {
            info.innerHTML = `Terkompresi: ${sizeKB} KB <i class="bi bi-check-circle-fill text-primary"></i>`;
            info.className = "mt-2 fw-bold small text-primary";
        }
        Swal.close();
    }

    // 5. Ulangi Foto
    function retakePhoto() {
        startCamera();
    }
    
    document.getElementById('paymentModal').addEventListener('hidden.bs.modal', function () {
        stopCamera(); // Pastikan kamera mati saat modal diclose (Batal)
    });
</script>
</body>
</html>