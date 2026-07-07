<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu | Flavory.id</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('layouts.favicon')
    @include('layouts.loading')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');
        
        :root {
            --orange-main: #f97316;
            --orange-hover: #ea580c;
        }

        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #fff7ed;
            color: #1e293b;
            padding-bottom: 5rem;
        }
        
        /* Utility Classes Custom */
        .text-orange { color: var(--orange-main) !important; }
        .bg-orange { background-color: var(--orange-main) !important; }
        .btn-orange { background-color: var(--orange-main); color: white; border: none; }
        .btn-orange:hover { background-color: var(--orange-hover); color: white; }
        .btn-outline-orange { color: var(--orange-main); border-color: var(--orange-main); }
        .btn-outline-orange:hover, .btn-check:checked + .btn-outline-orange { background-color: var(--orange-main); color: white; }
        .bg-warning-subtle { background-color: #ffedd5 !important; } 
        
        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scroll::-webkit-scrollbar-thumb { background: var(--orange-main); border-radius: 10px; }
        
        .spin-anim { animation: spin 1s linear infinite; display: inline-block; }
        @keyframes spin { 100% { transform: rotate(360deg); } }
        
        .hover-scale { transition: transform 0.2s; }
        .hover-scale:active { transform: scale(0.95); }
    </style>
</head>
<body>

    <header class="container pt-4 mb-2" style="max-width: 900px;">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4 bg-white p-4 rounded-4 border border-warning-subtle shadow-sm" style="--bs-bg-opacity: .7; backdrop-filter: blur(4px);">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('kasir.dashboard') }}" class="btn btn-light border-warning-subtle text-secondary rounded-4 d-flex align-items-center justify-content-center hover-scale" style="width: 48px; height: 48px;">
                    <i class="bi bi-arrow-left fs-4"></i>
                </a>
                <h1 class="h4 mb-0 fw-black text-uppercase" style="font-weight: 900; letter-spacing: -0.5px;">Kelola Menu</h1>
            </div>

            <div class="d-flex align-items-center gap-3 bg-orange text-white px-4 py-2 rounded-pill shadow">
                <div class="bg-white rounded-3 d-flex align-items-center justify-content-center" style="--bs-bg-opacity: .2; width: 40px; height: 40px;">
                    <i class="bi bi-database-fill-check fs-5"></i>
                </div>
                <div class="d-flex flex-column">
                    <span class="text-white-50 text-uppercase fw-bold" style="font-size: 9px; letter-spacing: 1px;">Sisa Kuota</span>
                    <span class="fw-black" style="font-size: 14px;">
                        {{ $menus->count() }} <small class="text-white-50">/</small> {{ $limit }} <small class="fw-medium text-white-50" style="font-size: 10px;">Menu</small>
                    </span>
                </div>
            </div>
        </div>
    </header>

    <main class="container py-4" style="max-width: 900px;">
        
        @if ($errors->any())
            <div class="alert alert-danger border-0 border-start border-4 border-danger rounded-3 mb-4 shadow-sm">
                <p class="fw-bold mb-1">Terjadi kesalahan validasi:</p>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('kasir.menu.store') }}" method="POST" enctype="multipart/form-data" id="addMenuForm">
            @csrf

            <div id="repeaterContainer" class="d-flex flex-column gap-3 mb-5"></div>

            <div class="card bg-white border-white shadow p-4 p-md-5" style="border-radius: 2.5rem;">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold text-uppercase mb-1 ms-1" style="font-size: 10px; letter-spacing: 1px;">Kategori Utama</label>
                        <input type="text" name="kategori" placeholder="Contoh: Coffee" class="form-control bg-light border-0 rounded-4 px-4 py-3 shadow-inner">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold text-uppercase mb-1 ms-1" style="font-size: 10px; letter-spacing: 1px;">Tipe Penjualan</label>
                        <div class="d-flex gap-2">
                            <input type="radio" class="btn-check" name="tipe_menu" id="tipe_utama" value="utama" checked>
                            <label class="btn btn-outline-orange border-2 rounded-3 flex-fill py-3 text-uppercase fw-bold" style="font-size: 11px;" for="tipe_utama">Menu Utama</label>

                            <input type="radio" class="btn-check" name="tipe_menu" id="tipe_best" value="best_seller">
                            <label class="btn btn-outline-orange border-2 rounded-3 flex-fill py-3 text-uppercase fw-bold" style="font-size: 11px;" for="tipe_best">Best Seller</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" id="btnSubmitAdd" class="btn btn-orange btn-lg rounded-4 px-5 fw-bold shadow hover-scale d-flex align-items-center gap-2">
                        SIMPAN SEMUA MENU <i class="bi bi-send-check-fill"></i>
                    </button>
                </div>
            </div>
        </form>

        @include('kasir.partials.list-menu')

    </main>

    <div class="modal fade" id="detailMenuModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 2rem;">
                <div class="modal-body p-4 p-md-5 text-start">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="h5 fw-black mb-0 text-uppercase" style="letter-spacing: -0.5px;">Detail Menu Tambahan</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="d-flex flex-column gap-3">
                        <input type="hidden" id="detailActiveIndex">
                        
                        <div>
                            <label class="form-label text-muted fw-bold text-uppercase mb-1" style="font-size: 10px;">Deskripsi</label>
                            <textarea id="detail_deskripsi" rows="2" class="form-control bg-light border-0 rounded-4 p-3"></textarea>
                        </div>

                        <div>
                            <label class="form-label text-muted fw-bold text-uppercase mb-1" style="font-size: 10px;">Varian / Level Harga</label>
                            <div id="detailVariantContainer" class="d-flex flex-column gap-2 mt-1 mb-2"></div>
                            <div class="d-flex gap-2 p-2 bg-light border border-dashed rounded-3">
                                <input type="text" id="detail_temp_name" placeholder="Contoh: Pedas" class="form-control bg-transparent border-0 px-2 py-1 shadow-none" style="font-size: 12px;">
                                <input type="number" id="detail_temp_price" placeholder="Harga" class="form-control bg-transparent border-0 px-2 py-1 shadow-none" style="width: 80px; font-size: 12px;">
                                <button type="button" onclick="addDetailVariant()" class="btn btn-orange text-white rounded-3"><i class="bi bi-plus"></i></button>
                            </div>
                        </div>

                        <div>
                            <label class="form-label text-muted fw-bold text-uppercase mb-1" style="font-size: 10px;">Stok (Opsional)</label>
                            <input type="number" id="detail_stok" class="form-control bg-light border-0 rounded-4 px-3 py-2">
                        </div>

                        <div>
                            <label class="form-label text-muted fw-bold text-uppercase mb-1" style="font-size: 10px;">Foto</label>
                            <div class="d-flex align-items-center gap-3 mt-1">
                                <img id="detail_preview_img" src="" class="d-none rounded-3 object-fit-cover shadow-sm border border-white" style="width: 60px; height: 60px;">
                                <div class="flex-fill">
                                    <input type="file" id="detail_file" class="form-control form-control-sm rounded-3" accept="image/*" onchange="previewAddImage(this)">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" onclick="saveDetailModal()" class="btn btn-dark w-100 rounded-4 py-3 mt-4 fw-black text-uppercase" style="letter-spacing: 1px; font-size: 13px;">
                        Terapkan Detail
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ==========================================
        // BAGIAN 1: REPEATER (TAMBAH MENU MULTIPLE)
        // ==========================================
        const LIMIT_MENU = {{ $limit ?? 0 }};
        const CURRENT_COUNT = {{ $menus->count() ?? 0 }};
        
        let addMenuState = [];
        let rowCounter = 0; 
        
        const container = document.getElementById('repeaterContainer');
        const detailModalEl = document.getElementById('detailMenuModal');
        const detailModal = new bootstrap.Modal(detailModalEl);

        // Render awal baris pertama
        document.addEventListener('DOMContentLoaded', () => {
            addNewRow(); 
        });

        // Menambahkan baris ke DOM tanpa me-reset baris yang sudah ada
        function addNewRow() {
            if ((addMenuState.length + CURRENT_COUNT) >= LIMIT_MENU) {
                Swal.fire('Limit Tercapai', 'Batas maksimal menu sudah penuh.', 'warning');
                return;
            }
            
            rowCounter++;
            const newRow = { id: rowCounter, deskripsi: '', stok: '', variants: [], file: null, fileInput: null };
            addMenuState.push(newRow);
            
            const html = `
            <div class="card border-warning-subtle shadow-sm transition-all" style="border-radius: 1.5rem;" id="add-row-${newRow.id}">
                <div class="card-body p-3 d-flex flex-column flex-md-row gap-3">
                    <div class="flex-fill">
                        <input type="text" name="menus[${newRow.id}][nama_menu]" placeholder="Nama Menu" required class="form-control bg-light border-0 rounded-4 px-3 py-2">
                    </div>
                    <div style="width: 100%; max-width: 150px;">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 text-muted fw-bold rounded-start-4">Rp</span>
                            <input type="number" name="menus[${newRow.id}][harga]" placeholder="0" required class="form-control bg-light border-0 rounded-end-4 py-2">
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" id="btn-detail-${newRow.id}" onclick="openDetailModal(${newRow.id})" class="btn btn-light text-secondary rounded-3 hover-scale" style="width: 40px; height: 40px;" title="Tambah Varian/Foto">
                            <i class="bi bi-grid-fill"></i>
                        </button>
                        <button type="button" id="btn-add-${newRow.id}" onclick="addNewRow()" class="btn btn-orange text-white rounded-3 shadow-sm hover-scale btn-add-row" style="width: 40px; height: 40px;">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                        <button type="button" onclick="removeRow(${newRow.id})" class="btn btn-danger-subtle text-danger rounded-3 hover-scale btn-remove-row" style="width: 40px; height: 40px;">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </div>
                    
                    <div id="hidden-inputs-${newRow.id}">
                        <input type="hidden" id="hidden-desc-${newRow.id}" name="menus[${newRow.id}][deskripsi]" value="">
                        <input type="hidden" id="hidden-stok-${newRow.id}" name="menus[${newRow.id}][stok_menu]" value="">
                        <div id="hidden-variants-${newRow.id}"></div>
                    </div>
                </div>
            </div>`;
            
            container.insertAdjacentHTML('beforeend', html);
            updateRowButtons();
        }

        function removeRow(id) {
            if(addMenuState.length <= 1) return; // Sisakan minimal 1
            
            addMenuState = addMenuState.filter(row => row.id !== id);
            const rowElement = document.getElementById(`add-row-${id}`);
            if(rowElement) rowElement.remove();
            
            updateRowButtons();
        }

        // Perbarui visibilitas tombol tambah/hapus
        function updateRowButtons() {
            const addButtons = document.querySelectorAll('.btn-add-row');
            const removeButtons = document.querySelectorAll('.btn-remove-row');
            
            addButtons.forEach(btn => btn.style.display = 'none'); // Sembunyikan semua tombol +
            
            if (addButtons.length > 0) {
                const canAddMore = (addMenuState.length + CURRENT_COUNT) < LIMIT_MENU;
                if (canAddMore) {
                    addButtons[addButtons.length - 1].style.display = 'inline-block'; // Tampilkan + di baris terakhir
                }
            }

            removeButtons.forEach(btn => {
                btn.style.display = addMenuState.length > 1 ? 'inline-block' : 'none';
            });
        }

        // --- MANAJEMEN MODAL DETAIL TAMBAH MENU ---
        // ==========================================
// BAGIAN 3: MODAL EDIT DATA (EXISTING MENU)
// ==========================================
let editStateVariants = [];
let editSelectedFile = null;

function openEditModal(btnElement) {
    try {
        // 1. Panggil modal dengan cara paling aman di Bootstrap 5
        const editModalEl = document.getElementById('editMenuModal');
        const editModal = bootstrap.Modal.getOrCreateInstance(editModalEl);
        
        // 2. Decode base64 dengan aman untuk karakter UTF-8 / spesial
        const base64Data = btnElement.getAttribute('data-menu');
        // Trik decode yang aman untuk data dari PHP json_encode
        const jsonString = decodeURIComponent(escape(window.atob(base64Data)));
        const menu = JSON.parse(jsonString);
        
        // 3. Masukkan data ke dalam form
        document.getElementById('edit_id').value = menu.id;
        document.getElementById('edit_nama_menu').value = menu.nama_menu;
        document.getElementById('edit_deskripsi').value = menu.deskripsi || '';
        document.getElementById('edit_harga').value = menu.harga;
        document.getElementById('edit_stok_menu').value = menu.stok_menu || '';
        
        if(menu.tipe_menu === 'utama') document.getElementById('edit_tipe_utama').checked = true;
        else document.getElementById('edit_tipe_best').checked = true;

        if(menu.status_menu === 'tersedia') document.getElementById('edit_status_ready').checked = true;
        else document.getElementById('edit_status_habis').checked = true;

        // 4. Handle Preview Image
        const imgPreview = document.getElementById('edit_preview_img');
        if(menu.gambar) {
            imgPreview.src = menu.gambar.startsWith('http') ? menu.gambar : `/assets/menus/${menu.gambar}`;
            imgPreview.classList.remove('d-none');
        } else {
            imgPreview.src = '';
            imgPreview.classList.add('d-none');
        }
        document.getElementById('edit_file_gambar').value = ''; // Reset input file
        editSelectedFile = null;

        // 5. Handle Variants
        editStateVariants = [];
        if (menu.variants && menu.variants.length > 0) {
            editStateVariants = JSON.parse(JSON.stringify(menu.variants));
        }
        renderEditVariants();

        // 6. Tampilkan Modal
        editModal.show();
        
    } catch (e) { 
        console.error("Error saat membuka modal edit:", e); 
        Swal.fire('Terjadi Kesalahan', 'Gagal memuat data menu. Cek console browser.', 'error');
    }
}

// Render Varian di Modal Edit
function renderEditVariants() {
    const cont = document.getElementById('editVariantContainer');
    if (editStateVariants.length === 0) {
        cont.innerHTML = ''; return;
    }
    cont.innerHTML = editStateVariants.map((v, i) => `
        <div class="d-flex gap-2 align-items-center bg-warning-subtle p-2 rounded-3 border border-warning-subtle" style="--bs-bg-opacity: .5;">
            <input type="text" class="form-control form-control-sm border-0" value="${v.variant}" onchange="updateEditVarName(${i}, this.value)">
            <input type="number" class="form-control form-control-sm border-0" style="width: 100px;" value="${v.harga}" onchange="updateEditVarPrice(${i}, this.value)">
            <button type="button" onclick="removeEditVariant(${i})" class="btn btn-sm text-danger px-2"><i class="bi bi-trash-fill"></i></button>
        </div>
    `).join('');
}

function addEditVariant() {
    const n = document.getElementById('edit_new_variant_name');
    const p = document.getElementById('edit_new_variant_price');
    if(n.value) {
        editStateVariants.push({ variant: n.value, harga: p.value || 0 });
        n.value = ''; p.value = '';
        renderEditVariants();
    }
}

function updateEditVarName(i, val) { editStateVariants[i].variant = val; }
function updateEditVarPrice(i, val) { editStateVariants[i].harga = val; }
function removeEditVariant(i) { editStateVariants.splice(i, 1); renderEditVariants(); }

function previewEditImage(input) {
    if (input.files && input.files[0]) {
        // --- TAMBAHKAN BLOK VALIDASI INI ---
        const file = input.files[0];
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire(
                'Gambar Terlalu Besar!', 
                'Ukuran gambar melebihi batas 2MB. Silakan pilih gambar dengan ukuran yang lebih kecil.', 
                'warning'
            );
            input.value = ''; return;
        }

        editSelectedFile = file; 
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('edit_preview_img').src = e.target.result;
            document.getElementById('edit_preview_img').classList.remove('d-none');
        }
        reader.readAsDataURL(editSelectedFile);
    }
}

async function submitEditMenu() {
    const btn = document.getElementById('btnSubmitEdit');
    btn.innerHTML = '<i class="bi bi-arrow-repeat spin-anim"></i> MEMPROSES...';
    btn.disabled = true;
    if (window.showLoading) window.showLoading();

    const id = document.getElementById('edit_id').value;
    const formData = new FormData();
    
    // Spoofing method PUT untuk Laravel
    formData.append('_method', 'PUT'); 
    
    formData.append('nama_menu', document.getElementById('edit_nama_menu').value);
    formData.append('deskripsi', document.getElementById('edit_deskripsi').value);
    formData.append('harga', document.getElementById('edit_harga').value);
    formData.append('stok_menu', document.getElementById('edit_stok_menu').value);
    formData.append('tipe_menu', document.querySelector('input[name="edit_tipe_menu"]:checked').value);
    formData.append('status_menu', document.querySelector('input[name="edit_status_menu"]:checked').value);

    editStateVariants.forEach((v, i) => {
        formData.append(`variants[${i}][variant]`, v.variant);
        formData.append(`variants[${i}][harga]`, v.harga || 0);
    });

    if (editSelectedFile) formData.append('gambar', editSelectedFile);

    try {
        const res = await fetch(`/kasir/menu/${id}`, { // Sesuaikan route Laravel Anda
            method: 'POST', // Fetch API pakai POST, Laravel akan baca _method PUT dari formData
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (res.ok) {
            const editModalEl = document.getElementById('editMenuModal');
            bootstrap.Modal.getInstance(editModalEl).hide();
            
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Perubahan Disimpan', showConfirmButton: false, timer: 1500 });
            
            // Trigger refresh tabel dengan memalsukan input di search bar
            const searchInput = document.getElementById('searchInput');
            if(searchInput) searchInput.dispatchEvent(new Event('input')); 
            else window.location.reload(); // Fallback jika search bar tidak ditemukan
            
        } else {
            const result = await res.json();
            Swal.fire('Error', result.message || 'Gagal menyimpan perubahan', 'error');
        }
    } catch (err) {
        console.error(err);
        Swal.fire('Error', 'Terjadi kesalahan koneksi', 'error');
    } finally {
        btn.innerHTML = 'SIMPAN PERUBAHAN';
        btn.disabled = false;
        if (window.hideLoading) window.hideLoading();
    }
}
        function renderDetailVariantsList(variants) {
            const cont = document.getElementById('detailVariantContainer');
            if (variants.length === 0) {
                cont.innerHTML = ''; return;
            }
            cont.innerHTML = variants.map((v, i) => `
                <div class="d-flex gap-2 align-items-center bg-warning-subtle p-2 rounded-3 border border-warning-subtle" style="--bs-bg-opacity: .4;">
                    <input type="text" class="form-control form-control-sm bg-white border-0" value="${v.variant}" readonly>
                    <input type="number" class="form-control form-control-sm bg-white border-0" style="width: 80px;" value="${v.harga}" readonly>
                    <button type="button" onclick="removeDetailVariant(${i})" class="btn btn-sm text-danger px-1"><i class="bi bi-x-circle-fill"></i></button>
                </div>
            `).join('');
        }

        function addDetailVariant() {
            const nameEl = document.getElementById('detail_temp_name');
            const priceEl = document.getElementById('detail_temp_price');
            const rowId = parseInt(document.getElementById('detailActiveIndex').value);
            const row = addMenuState.find(r => r.id === rowId);
            
            if (nameEl.value) {
                row.variants.push({ variant: nameEl.value, harga: priceEl.value || 0 });
                nameEl.value = ''; priceEl.value = '';
                renderDetailVariantsList(row.variants);
            }
        }

        function removeDetailVariant(vIndex) {
            const rowId = parseInt(document.getElementById('detailActiveIndex').value);
            const row = addMenuState.find(r => r.id === rowId);
            row.variants.splice(vIndex, 1);
            renderDetailVariantsList(row.variants);
        }

        function previewAddImage(input) {
            const rowId = parseInt(document.getElementById('detailActiveIndex').value);
            const row = addMenuState.find(r => r.id === rowId);
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire(
                        'Gambar Terlalu Besar!', 
                        'Ukuran gambar melebihi batas 2MB. Silakan pilih gambar dengan ukuran yang lebih kecil.', 
                        'warning'
                    );
                    input.value = ''; return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('detail_preview_img').src = e.target.result;
                    document.getElementById('detail_preview_img').classList.remove('d-none');
                    row.fileUrl = e.target.result;
                }
                reader.readAsDataURL(file);
                
                // --- PERBAIKANNYA DI SINI ---
                // 1. Clone elemen input-nya (isi filenya pasti hilang)
                row.fileInput = input.cloneNode(true); 
                
                // 2. Buat DataTransfer baru untuk "menyelundupkan" file
                const dt = new DataTransfer();
                dt.items.add(file);
                
                // 3. Masukkan kembali file ke dalam input hasil clone
                row.fileInput.files = dt.files; 
                // -----------------------------
                
                row.file = file;
            }
        }

        function saveDetailModal() {
            const rowId = parseInt(document.getElementById('detailActiveIndex').value);
            const row = addMenuState.find(r => r.id === rowId);
            
            // Simpan ke state
            row.deskripsi = document.getElementById('detail_deskripsi').value;
            row.stok = document.getElementById('detail_stok').value;
            
            // Update hidden inputs di DOM utama
            document.getElementById(`hidden-desc-${rowId}`).value = row.deskripsi;
            document.getElementById(`hidden-stok-${rowId}`).value = row.stok;
            
            // Render hidden input untuk variants
            const variantsContainer = document.getElementById(`hidden-variants-${rowId}`);
            variantsContainer.innerHTML = row.variants.map((v, vIndex) => `
                <input type="hidden" name="menus[${rowId}][variants][${vIndex}][variant]" value="${v.variant}">
                <input type="hidden" name="menus[${rowId}][variants][${vIndex}][harga]" value="${v.harga}">
            `).join('');

            // Pindahkan file input hasil clone ke DOM jika ada
            if(row.fileInput) {
                const hiddenDiv = document.getElementById(`hidden-inputs-${rowId}`);
                row.fileInput.name = `menus[${rowId}][gambar]`;
                row.fileInput.style.display = 'none';
                
                // Hapus file input lama jika ada
                const oldInput = hiddenDiv.querySelector('input[type="file"]');
                if(oldInput) oldInput.remove();
                
                hiddenDiv.appendChild(row.fileInput);
            }

            // Ubah warna tombol grid jika ada detail tersimpan
            const btnDetail = document.getElementById(`btn-detail-${rowId}`);
            if(row.variants.length > 0 || row.deskripsi !== '' || row.stok !== '' || row.file !== null) {
                btnDetail.classList.remove('btn-light', 'text-secondary');
                btnDetail.classList.add('btn-warning-subtle', 'text-warning');
            } else {
                btnDetail.classList.add('btn-light', 'text-secondary');
                btnDetail.classList.remove('btn-warning-subtle', 'text-warning');
            }

            detailModal.hide();
        }

        // ==========================================
        // BAGIAN 2 & 3: AJAX SEARCH & MODAL EDIT 
        // (Logika biarkan sama karena elemen aslinya ada di list-menu)
        // ==========================================
        let searchTimeout = null;
        const searchInput = document.getElementById('searchInput');
        if(searchInput) {
            searchInput.addEventListener('input', function(e) {
                const val = e.target.value.trim();
                document.getElementById('searchSpinner').classList.remove('d-none');

                // Sembunyikan/tampilkan tombol "Lihat Semua" sesuai kondisi search
                const showAllWrapper = document.getElementById('show-all-wrapper');
                if (showAllWrapper) {
                    showAllWrapper.style.display = val.length > 0 ? 'none' : '';
                }

                // Jika search dikosongkan, kembalikan ke tampilan default (20 baris)
                if (val.length === 0 && typeof _semuaTampil !== 'undefined') {
                    _semuaTampil = false;
                }
                
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(async () => {
                    try {
                        const res = await fetch(`/kasir/menu/search?q=${encodeURIComponent(val)}`, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const data = await res.json();
                        document.getElementById('menu-table-body').innerHTML = data.html;

                        // Setelah hasil AJAX: hapus kelas d-none dari semua baris
                        // karena search menampilkan semua hasil sekaligus
                        if (val.length > 0) {
                            document.querySelectorAll('#menu-table-body .menu-row-extra')
                                .forEach(row => row.classList.remove('d-none'));
                        }
                    } catch(err) { console.error(err); }
                    document.getElementById('searchSpinner').classList.add('d-none');
                }, 500);
            });
        }

        async function deleteMenu(id) {
            Swal.fire({
                title: 'Hapus Menu?',
                text: "Data akan hilang permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Ya, Hapus!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    if (window.showLoading) window.showLoading();
                    try {
                        const res = await fetch(`/kasir/menu/${id}`, {
                            method: 'DELETE',
                            headers: { 
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        if (res.ok) {
                            if (window.hideLoading) window.hideLoading();
                            document.getElementById(`menu-row-${id}`).remove();
                            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Terhapus', showConfirmButton: false, timer: 1500 });
                        }
                    } catch(err) { 
                        if (window.hideLoading) window.hideLoading();
                        Swal.fire('Error', 'Gagal menghubungi server', 'error'); 
                    }
                }
            });
        }

        // Setup Alert Global Form Loading
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", confirmButtonColor: '#ea580c', timer: 3000 });
            @endif
            @if(session('error'))
                Swal.fire({ icon: 'error', title: 'Oops...', text: "{{ session('error') }}", confirmButtonColor: '#ea580c' });
            @endif
            
            document.getElementById('addMenuForm').addEventListener('submit', function() {
                const btn = document.getElementById('btnSubmitAdd');
                btn.innerHTML = '<i class="bi bi-arrow-repeat spin-anim"></i> Menyimpan...';
                btn.classList.add('disabled');
            });
        });

        function openDetailModal(rowId) {
    const row = addMenuState.find(r => r.id === rowId);
    if (!row) return;

    document.getElementById('detailActiveIndex').value = rowId;
    document.getElementById('detail_deskripsi').value = row.deskripsi || '';
    document.getElementById('detail_stok').value = row.stok || '';
    document.getElementById('detail_temp_name').value = '';
    document.getElementById('detail_temp_price').value = '';

    renderDetailVariantsList(row.variants || []);

    if (row.fileUrl) {
        document.getElementById('detail_preview_img').src = row.fileUrl;
        document.getElementById('detail_preview_img').classList.remove('d-none');
    } else {
        document.getElementById('detail_preview_img').src = '';
        document.getElementById('detail_preview_img').classList.add('d-none');
    }

    document.getElementById('detail_file').value = '';
    detailModal.show();
}
    
    </script>
</body>
</html>