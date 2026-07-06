<div class="mt-5">
    
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h5 fw-black text-dark d-flex align-items-center gap-2 mb-0" style="font-weight: 900;">
                <span class="bg-orange rounded-pill" style="width: 8px; height: 30px;"></span>
                DAFTAR <span class="text-orange">MENU ANDA</span>
            </h2>
        </div>
        
        <div class="position-relative" style="width: 100%; max-width: 350px;">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
            <input type="text" 
                   id="searchInput" 
                   placeholder="Cari nama, harga, atau kategori..." 
                   class="form-control rounded-pill ps-5 py-2 shadow-sm border-warning-subtle focus-ring focus-ring-warning">
            
            <div id="searchSpinner" class="position-absolute top-50 end-0 translate-middle-y me-3 d-none">
                <i class="bi bi-arrow-repeat spin-anim text-orange"></i>
            </div>
        </div>
    </div>

    <div class="card border-warning-subtle shadow-sm" style="border-radius: 2rem; overflow: hidden;">
        <div class="table-responsive custom-scroll">
            <table class="table table-borderless mb-0 w-100 align-middle">
                <thead class="bg-warning-subtle border-bottom border-warning-subtle">
                    <tr>
                        <th class="py-3 px-4 text-orange text-uppercase fw-bold" style="font-size: 11px; letter-spacing: 1px;">Info Menu</th>
                        <th class="py-3 px-3 text-orange text-uppercase fw-bold" style="font-size: 11px; letter-spacing: 1px;">Harga Satuan</th>
                        <th class="py-3 px-3 text-orange text-uppercase fw-bold text-center" style="font-size: 11px; letter-spacing: 1px;">Status</th>
                        <th class="py-3 px-4 text-orange text-uppercase fw-bold text-end" style="font-size: 11px; letter-spacing: 1px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="menu-table-body">
                    @include('kasir.partials.list-menu-rows', ['menus' => $menus])
                </tbody>
            </table>
        </div>

        {{-- Tombol Lihat Semua — hanya muncul jika menu > 20 --}}
        @if($menus->count() > 20)
        <div id="show-all-wrapper" class="text-center py-3 border-top border-warning-subtle">
            <button type="button" id="btnLihatSemua" onclick="toggleSemuaMenu()"
                class="btn btn-outline-orange rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center gap-2"
                style="font-size: 13px;">
                <i class="bi bi-eye-fill" id="iconLihatSemua"></i>
                <span id="labelLihatSemua">Lihat Semua Menu ({{ $menus->count() }} total)</span>
            </button>
        </div>
        @endif

<script>
// ── Toggle Lihat Semua / Sembunyikan ──
let _semuaTampil = false;

function toggleSemuaMenu() {
    const extras = document.querySelectorAll('#menu-table-body .menu-row-extra');
    const icon   = document.getElementById('iconLihatSemua');
    const label  = document.getElementById('labelLihatSemua');
    const total  = {{ $menus->count() }};

    _semuaTampil = !_semuaTampil;

    extras.forEach(row => {
        row.classList.toggle('d-none', !_semuaTampil);
    });

    if (_semuaTampil) {
        icon.className    = 'bi bi-eye-slash-fill';
        label.textContent = 'Sembunyikan (tampil 20 saja)';
    } else {
        icon.className    = 'bi bi-eye-fill';
        label.textContent = 'Lihat Semua Menu (' + total + ' total)';
    }
}
</script>
    </div>

    <div class="modal fade" id="editMenuModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 2rem;">
                
                <div class="modal-header border-0 px-4 pt-4 pb-0 align-items-center">
                    <h3 class="modal-title h5 fw-black text-dark d-flex align-items-center gap-3" style="font-weight: 900;">
                        <div class="bg-orange text-white rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                            <i class="bi bi-pencil-fill"></i>
                        </div>
                        EDIT DATA <span class="text-orange ms-1">MENU</span>
                    </h3>
                    <button type="button" class="btn-close bg-light rounded-circle p-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4 custom-scroll">
                    <form id="updateMenuForm" class="row g-3" enctype="multipart/form-data">
                        <input type="hidden" id="edit_id">
                        
                        <div class="col-12">
                            <label class="form-label text-muted fw-bold text-uppercase mb-1 ms-2" style="font-size: 10px; letter-spacing: 1px;">Nama Menu</label>
                            <input type="text" id="edit_nama_menu" required class="form-control bg-light border-0 rounded-4 px-3 py-2 fw-bold">
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label text-muted fw-bold text-uppercase mb-1 ms-2" style="font-size: 10px; letter-spacing: 1px;">Deskripsi Singkat</label>
                            <textarea id="edit_deskripsi" rows="3" class="form-control bg-light border-0 rounded-4 px-3 py-2"></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-muted fw-bold text-uppercase mb-1 ms-2" style="font-size: 10px; letter-spacing: 1px;">Variant / Level Harga</label>
                            
                            <div id="editVariantContainer" class="d-flex flex-column gap-2 mb-2">
                                </div>

                            <div class="d-flex gap-2 p-2 bg-light border border-dashed rounded-3">
                                <input type="text" id="edit_new_variant_name" placeholder="Nama Variant..." class="form-control bg-transparent border-0 px-2 py-1 shadow-none" style="font-size: 14px;">
                                <input type="number" id="edit_new_variant_price" placeholder="Harga" class="form-control bg-transparent border-0 px-2 py-1 shadow-none" style="width: 100px; font-size: 14px;">
                                <button type="button" onclick="addEditVariant()" class="btn btn-orange text-white rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-muted fw-bold text-uppercase mb-1 ms-2" style="font-size: 10px; letter-spacing: 1px;">Harga Utama (Rp)</label>
                            <input type="number" id="edit_harga" required class="form-control bg-light border-0 rounded-4 px-3 py-2 fw-bold">
                        </div>

                        <div class="col-12">
                            <label class="form-label text-muted fw-bold text-uppercase mb-1 ms-2" style="font-size: 10px; letter-spacing: 1px;">Stok (Kosongkan jika tidak terbatas)</label>
                            <input type="number" id="edit_stok_menu" class="form-control bg-light border-0 rounded-4 px-3 py-2">
                        </div>

                        <div class="col-6">
                            <label class="form-label text-muted fw-bold text-uppercase mb-2 ms-2 d-block text-center" style="font-size: 10px; letter-spacing: 1px;">Tipe Menu</label>
                            <div class="d-flex bg-light p-1 rounded-4">
                                <input type="radio" class="btn-check" name="edit_tipe_menu" id="edit_tipe_utama" value="utama" autocomplete="off">
                                <label class="btn btn-sm btn-outline-orange border-0 rounded-3 flex-fill text-uppercase fw-bold" style="font-size: 10px;" for="edit_tipe_utama">Utama</label>

                                <input type="radio" class="btn-check" name="edit_tipe_menu" id="edit_tipe_best" value="best_seller" autocomplete="off">
                                <label class="btn btn-sm btn-outline-orange border-0 rounded-3 flex-fill text-uppercase fw-bold" style="font-size: 10px;" for="edit_tipe_best">Best</label>
                            </div>
                        </div>

                        <div class="col-6">
                            <label class="form-label text-muted fw-bold text-uppercase mb-2 ms-2 d-block text-center" style="font-size: 10px; letter-spacing: 1px;">Status Stok</label>
                            <div class="d-flex bg-light p-1 rounded-4">
                                <input type="radio" class="btn-check" name="edit_status_menu" id="edit_status_ready" value="tersedia" autocomplete="off">
                                <label class="btn btn-sm btn-outline-success border-0 rounded-3 flex-fill text-uppercase fw-bold" style="font-size: 10px;" for="edit_status_ready">Ready</label>

                                <input type="radio" class="btn-check" name="edit_status_menu" id="edit_status_habis" value="habis" autocomplete="off">
                                <label class="btn btn-sm btn-outline-danger border-0 rounded-3 flex-fill text-uppercase fw-bold" style="font-size: 10px;" for="edit_status_habis">Habis</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-muted fw-bold text-uppercase mb-1 ms-2" style="font-size: 10px; letter-spacing: 1px;">Foto Menu</label>
                            <div class="mt-2 d-flex flex-column align-items-center gap-3 p-4 border border-warning-subtle border-2 border-dashed rounded-4 bg-warning-subtle" style="--bs-bg-opacity: .3;">
                                <img id="edit_preview_img" src="" class="d-none rounded-3 object-fit-cover shadow-sm border border-white" style="width: 100px; height: 100px;">
                                <input type="file" id="edit_file_gambar" class="form-control form-control-sm" accept="image/*" onchange="previewEditImage(this)">
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer border-0 p-4 pt-2">
                    <button type="button" onclick="submitEditMenu()" id="btnSubmitEdit" class="btn btn-dark w-100 rounded-4 py-3 fw-black text-uppercase shadow" style="letter-spacing: 2px; font-size: 12px;">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>