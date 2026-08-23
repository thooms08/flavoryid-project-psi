<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pengaturan Form - Flavory.id</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @include('layouts.favicon')
    <script src="{{ asset('js/loading.js') }}"></script>
</head>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #fff7ed;
    }
    .switch-orange:checked {
        background-color: #fd7e14 !important;
        border-color: #fd7e14 !important;
    }
    .switch-orange:focus {
        box-shadow: 0 0 0 0.25rem rgba(253, 126, 20, 0.25) !important;
    }
    .btn-back:hover {
        background-color: #e9ecef;
        color: #fd7e14;
    }
    /* Voucher list item */
    .voucher-item {
        transition: background 0.15s;
    }
    .voucher-item:hover {
        background-color: #fff3e0;
    }
    .kode-badge {
        font-family: 'Courier New', monospace;
        font-weight: 700;
        letter-spacing: 2px;
        font-size: 0.95rem;
    }

    /* ── Form custom (referensi dari order.blade.php) ─────────────── */
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
        border-color: #fd7e14 !important;
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
    /* Input group nominal + jenis (persis seperti diskon di order.blade.php) */
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
    /* Input kode voucher - uppercase monospace */
    #kode_voucher {
        text-transform: uppercase;
        letter-spacing: 3px;
        font-family: 'Courier New', monospace;
        font-weight: 700;
        font-size: 1.15rem;
        text-align: center;
    }
    /* Alert kode check */
    #kode-check-alert {
        font-size: 0.82rem;
        border-radius: 8px;
        padding: 6px 12px;
        display: none;
    }
</style>
<body>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="{{ route('kasir.dashboard') }}" class="btn btn-light border-warning-subtle text-secondary rounded-4 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-arrow-left fs-4"></i>
                </a>
                <h1 class="h4 mb-0 fw-black text-uppercase" style="font-weight: 900; letter-spacing: -0.5px;"></h1>
            </div>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 text-center">
                    <h4 class="mb-0 fw-bold" style="color: #fd7e14;">Pengaturan Form Pesanan</h4>
                    <p class="text-muted small mt-1">Aktifkan atau nonaktifkan kolom form yang ingin ditampilkan ke pelanggan.</p>
                </div>
                <div class="card-body p-4">

                    <div id="alert-notif" class="alert alert-success d-none shadow-sm" role="alert">
                        Pengaturan berhasil disimpan.
                    </div>

                    <div class="list-group list-group-flush">

                        {{-- Nama Pelanggan --}}
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-0 border-bottom">
                            <div>
                                <h6 class="mb-0 fw-semibold">Nama Pelanggan</h6>
                                <small class="text-muted">Kolom input untuk nama pemesan.</small>
                            </div>
                            <div class="form-check form-switch fs-4 mb-0">
                                <input class="form-check-input switch-orange toggle-setting" type="checkbox"
                                    data-field="nama" {{ $pengaturan->nama ? 'checked' : '' }}>
                            </div>
                        </div>

                        {{-- Nomor WhatsApp --}}
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-0 border-bottom">
                            <div>
                                <h6 class="mb-0 fw-semibold">Nomor WhatsApp</h6>
                                <small class="text-muted">Kolom input untuk kontak pelanggan.</small>
                            </div>
                            <div class="form-check form-switch fs-4 mb-0">
                                <input class="form-check-input switch-orange toggle-setting" type="checkbox"
                                    data-field="whatsapp" {{ $pengaturan->whatsapp ? 'checked' : '' }}>
                            </div>
                        </div>

                        {{-- Nomor Meja --}}
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-0 border-bottom">
                            <div>
                                <h6 class="mb-0 fw-semibold">Nomor Meja</h6>
                                <small class="text-muted">Cocok untuk pemesanan dine-in.</small>
                            </div>
                            <div class="form-check form-switch fs-4 mb-0">
                                <input class="form-check-input switch-orange toggle-setting" type="checkbox"
                                    data-field="meja" {{ $pengaturan->meja ? 'checked' : '' }}>
                            </div>
                        </div>

                        {{-- Catatan Pesanan --}}
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-0 border-bottom">
                            <div>
                                <h6 class="mb-0 fw-semibold">Catatan Pesanan</h6>
                                <small class="text-muted">Area teks untuk request khusus (opsional).</small>
                            </div>
                            <div class="form-check form-switch fs-4 mb-0">
                                <input class="form-check-input switch-orange toggle-setting" type="checkbox"
                                    data-field="keterangan" {{ $pengaturan->keterangan ? 'checked' : '' }}>
                            </div>
                        </div>

                        {{-- Diskon Pesanan --}}
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-0 border-bottom">
                            <div>
                                <h6 class="mb-0 fw-semibold">Diskon Pesanan</h6>
                                <small class="text-muted">Kolom input untuk memberikan potongan harga.</small>
                            </div>
                            <div class="form-check form-switch fs-4 mb-0">
                                <input class="form-check-input switch-orange toggle-setting" type="checkbox"
                                    data-field="diskon" {{ $pengaturan->diskon ? 'checked' : '' }}>
                            </div>
                        </div>

                        {{-- Voucher --}}
                        <div class="list-group-item py-3 px-0 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 fw-semibold">Voucher</h6>
                                    <small class="text-muted">Aktifkan fitur kode voucher untuk pelanggan.</small>
                                </div>
                                <div class="form-check form-switch fs-4 mb-0">
                                    <input class="form-check-input switch-orange toggle-setting" type="checkbox"
                                        id="toggle-voucher" data-field="voucher" {{ $pengaturan->voucher ? 'checked' : '' }}>
                                </div>
                            </div>
                            {{-- Tombol Atur Kode Voucher - muncul saat toggle aktif --}}
                            <div id="btn-atur-voucher-wrapper" class="{{ $pengaturan->voucher ? '' : 'd-none' }} mt-3">
                                <button type="button" class="btn btn-sm btn-outline-warning rounded-3 fw-semibold"
                                    data-bs-toggle="modal" data-bs-target="#modalVoucher" id="btn-atur-voucher">
                                    <i class="bi bi-ticket-perforated me-1"></i> Atur Kode Voucher
                                </button>
                            </div>
                        </div>

                        {{-- Bukti Pembayaran --}}
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-0 border-bottom-0">
                            <div>
                                <h6 class="mb-0 fw-semibold">Bukti Pembayaran (Kamera)</h6>
                                <small class="text-muted">Aktifkan popup kamera otomatis saat memilih metode QRIS/Transfer.</small>
                            </div>
                            <div class="form-check form-switch fs-4 mb-0">
                                <input class="form-check-input switch-orange toggle-setting" type="checkbox"
                                    data-field="bukti_pembayaran" {{ $pengaturan->bukti_pembayaran ? 'checked' : '' }}>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ===================== MODAL VOUCHER ===================== --}}
<div class="modal fade" id="modalVoucher" tabindex="-1" aria-labelledby="modalVoucherLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow">

            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalVoucherLabel">
                    <i class="bi bi-ticket-perforated text-warning me-2"></i>Atur Kode Voucher
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4">

                {{-- Form tambah voucher --}}
                <div class="card border-0 bg-light rounded-3 p-3 mb-4">
                    <p class="text-muted small mb-3">
                        Tambah voucher baru. Kode 6 karakter, otomatis kapital.
                        <span id="voucher-counter" class="fw-semibold text-dark"></span>
                    </p>

                    <div class="mb-3">
                        <label class="form-label-custom">Kode Voucher <span class="text-danger">*</span></label>
                        <input type="text" id="kode_voucher" class="form-control form-control-custom text-center"
                            maxlength="6" placeholder="ABCDE1" autocomplete="off">
                        {{-- Alert real-time AJAX --}}
                        <div id="kode-check-alert" class="alert mt-2 mb-0"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Nominal &amp; Jenis Potongan <span class="text-danger">*</span></label>
                        <div class="input-group input-group-custom">
                            <input type="number" id="nominal_potongan" class="form-control form-control-custom"
                                placeholder="Contoh: 10000" min="0" step="any">
                            <select id="jenis_potongan" class="form-select form-control-custom" style="max-width: 80px;">
                                <option value="Rp">Rp</option>
                                <option value="%">%</option>
                            </select>
                        </div>
                    </div>

                    <div id="form-error-alert" class="alert alert-danger small d-none rounded-3 py-2"></div>

                    <button type="button" id="btn-simpan-voucher" class="btn btn-warning text-white fw-semibold w-100 rounded-3">
                        <i class="bi bi-plus-circle me-1"></i> Simpan Voucher
                    </button>
                </div>

                {{-- Daftar voucher yang sudah ada --}}
                <div>
                    <p class="fw-semibold small text-muted mb-2">Voucher Tersimpan</p>
                    <div id="voucher-list-loading" class="text-center py-3 d-none">
                        <div class="spinner-border spinner-border-sm text-warning" role="status"></div>
                        <span class="ms-2 small text-muted">Memuat...</span>
                    </div>
                    <div id="voucher-list-empty" class="text-center py-3 d-none">
                        <i class="bi bi-ticket-perforated text-muted fs-3"></i>
                        <p class="text-muted small mt-1 mb-0">Belum ada voucher. Tambahkan di atas.</p>
                    </div>
                    <ul class="list-group list-group-flush" id="voucher-list"></ul>
                </div>

            </div>

        </div>
    </div>
</div>
{{-- ===================== END MODAL VOUCHER ===================== --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const CSRF = '{{ csrf_token() }}';
    const routeUpdate      = '{{ route("kasir.pengaturan.update") }}';
    const routeVoucherList = '{{ route("kasir.voucher.index") }}';
    const routeVoucherStore= '{{ route("kasir.voucher.store") }}';
    const routeVoucherCheck= '{{ route("kasir.voucher.check-kode") }}';
    const routeVoucherDel  = '{{ url("kasir/voucher") }}'; // + /{id} DELETE

    const alertNotif        = document.getElementById('alert-notif');
    const toggleVoucher     = document.getElementById('toggle-voucher');
    const btnAturWrapper    = document.getElementById('btn-atur-voucher-wrapper');
    const kodeInput         = document.getElementById('kode_voucher');
    const nominalInput      = document.getElementById('nominal_potongan');
    const jenisSelect       = document.getElementById('jenis_potongan');
    const btnSimpan         = document.getElementById('btn-simpan-voucher');
    const formErrorAlert    = document.getElementById('form-error-alert');
    const kodeCheckAlert    = document.getElementById('kode-check-alert');
    const voucherList       = document.getElementById('voucher-list');
    const voucherCounter    = document.getElementById('voucher-counter');
    const listLoading       = document.getElementById('voucher-list-loading');
    const listEmpty         = document.getElementById('voucher-list-empty');

    let checkKodeTimeout = null;
    let kodeValid        = false; // flag hasil check AJAX

    // ─── Toggle settings (semua toggle termasuk voucher) ───────────────────
    document.querySelectorAll('.toggle-setting').forEach(toggle => {
        toggle.addEventListener('change', function () {
            const field  = this.getAttribute('data-field');
            const status = this.checked ? 1 : 0;
            this.disabled = true;
            if (window.showLoading) window.showLoading();

            // Tampilkan/sembunyikan tombol Atur Kode Voucher
            if (field === 'voucher') {
                btnAturWrapper.classList.toggle('d-none', !this.checked);
            }

            fetch(routeUpdate, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({ field, status })
            })
            .then(r => r.json())
            .then(data => {
                this.disabled = false;
                if (window.hideLoading) window.hideLoading();
                if (data.success) {
                    alertNotif.innerHTML = data.message;
                    alertNotif.classList.remove('d-none', 'alert-danger');
                    alertNotif.classList.add('alert-success', 'fade', 'show');
                    setTimeout(() => alertNotif.classList.add('d-none'), 2500);
                }
            })
            .catch(() => {
                this.disabled = false;
                if (window.hideLoading) window.hideLoading();
                this.checked = !this.checked;
                if (field === 'voucher') btnAturWrapper.classList.toggle('d-none', !this.checked);
                alert('Terjadi kesalahan sistem saat menyimpan pengaturan.');
            });
        });
    });

    // ─── Muat daftar voucher saat modal dibuka ──────────────────────────────
    document.getElementById('modalVoucher').addEventListener('show.bs.modal', loadVouchers);

    function loadVouchers() {
        listLoading.classList.remove('d-none');
        listEmpty.classList.add('d-none');
        voucherList.innerHTML = '';

        fetch(routeVoucherList, {
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            listLoading.classList.add('d-none');
            if (!data.success || data.data.length === 0) {
                listEmpty.classList.remove('d-none');
                voucherCounter.textContent = '(0/30 voucher)';
                return;
            }
            const total = data.total;
            voucherCounter.textContent = '(' + total + '/30 voucher)';

            data.data.forEach(v => {
                voucherList.insertAdjacentHTML('beforeend', renderVoucherItem(v));
            });

            // Bind tombol hapus
            bindDeleteButtons();

            // Disable form jika sudah 30
            toggleFormDisable(total >= 30);
        })
        .catch(() => {
            listLoading.classList.add('d-none');
            listEmpty.classList.remove('d-none');
        });
    }

    function renderVoucherItem(v) {
        const potongan = v.jenis_potongan === '%'
            ? v.nominal_potongan + '%'
            : 'Rp ' + Number(v.nominal_potongan).toLocaleString('id-ID');
        return `
        <li class="list-group-item voucher-item d-flex justify-content-between align-items-center px-2 py-2 rounded-3 mb-1">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-warning text-dark kode-badge px-2 py-1">${v.kode_voucher}</span>
                <span class="small text-muted">Potongan <strong>${potongan}</strong></span>
            </div>
            <button class="btn btn-sm btn-outline-danger rounded-3 btn-hapus-voucher" data-id="${v.id}" data-kode="${v.kode_voucher}">
                <i class="bi bi-trash3"></i>
            </button>
        </li>`;
    }

    function bindDeleteButtons() {
        document.querySelectorAll('.btn-hapus-voucher').forEach(btn => {
            btn.addEventListener('click', function () {
                const id   = this.dataset.id;
                const kode = this.dataset.kode;
                if (!confirm('Hapus voucher ' + kode + '?')) return;

                this.disabled = true;
                fetch(routeVoucherDel + '/' + id, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) loadVouchers();
                    else alert(data.message || 'Gagal menghapus voucher.');
                })
                .catch(() => alert('Terjadi kesalahan jaringan.'));
            });
        });
    }

    function toggleFormDisable(disabled) {
        kodeInput.disabled    = disabled;
        nominalInput.disabled = disabled;
        jenisSelect.disabled  = disabled;
        btnSimpan.disabled    = disabled;
        if (disabled) {
            showFormError('Batas maksimal 30 voucher telah tercapai.');
        } else {
            hideFormError();
        }
    }

    // ─── Real-time AJAX check kode voucher ──────────────────────────────────
    kodeInput.addEventListener('input', function () {
        // Force uppercase
        const pos = this.selectionStart;
        this.value = this.value.toUpperCase();
        this.setSelectionRange(pos, pos);

        kodeValid = false;
        hideKodeAlert();

        const kode = this.value.trim();
        if (kode.length !== 6) return;

        // Debounce 400ms
        clearTimeout(checkKodeTimeout);
        checkKodeTimeout = setTimeout(() => {
            checkKodeAjax(kode);
        }, 400);
    });

    function checkKodeAjax(kode) {
        fetch(routeVoucherCheck, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ kode_voucher: kode })
        })
        .then(r => r.json())
        .then(data => {
            kodeValid = data.available;
            showKodeAlert(data.available ? 'success' : 'danger', data.message);
        })
        .catch(() => {
            kodeValid = false;
        });
    }

    function showKodeAlert(type, html) {
        kodeCheckAlert.className = 'alert alert-' + type + ' mt-2 mb-0';
        kodeCheckAlert.innerHTML = html;
        kodeCheckAlert.style.display = 'block';
    }
    function hideKodeAlert() {
        kodeCheckAlert.style.display = 'none';
        kodeCheckAlert.innerHTML     = '';
    }
    function showFormError(msg) {
        formErrorAlert.innerHTML = msg;
        formErrorAlert.classList.remove('d-none');
    }
    function hideFormError() {
        formErrorAlert.classList.add('d-none');
        formErrorAlert.innerHTML = '';
    }

    // ─── Simpan voucher ─────────────────────────────────────────────────────
    btnSimpan.addEventListener('click', function () {
        hideFormError();

        const kode    = kodeInput.value.trim().toUpperCase();
        const nominal = nominalInput.value.trim();
        const jenis   = jenisSelect.value;

        // Validasi front-end
        if (kode.length !== 6) {
            showFormError('Kode voucher harus tepat 6 karakter.');
            kodeInput.focus();
            return;
        }
        if (!kodeValid) {
            showFormError('Periksa kembali kode voucher - pastikan tersedia (berwarna hijau).');
            kodeInput.focus();
            return;
        }
        if (nominal === '' || isNaN(nominal) || Number(nominal) < 0) {
            showFormError('Nominal potongan tidak valid.');
            nominalInput.focus();
            return;
        }

        btnSimpan.disabled = true;
        btnSimpan.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

        fetch(routeVoucherStore, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ kode_voucher: kode, nominal_potongan: nominal, jenis_potongan: jenis })
        })
        .then(r => r.json())
        .then(data => {
            btnSimpan.disabled = false;
            btnSimpan.innerHTML = '<i class="bi bi-plus-circle me-1"></i> Simpan Voucher';

            if (data.success) {
                // Reset form
                kodeInput.value   = '';
                nominalInput.value= '';
                jenisSelect.value = 'Rp';
                kodeValid         = false;
                hideKodeAlert();
                hideFormError();
                // Muat ulang daftar
                loadVouchers();
            } else {
                showFormError(data.message || 'Gagal menyimpan voucher.');
            }
        })
        .catch(() => {
            btnSimpan.disabled = false;
            btnSimpan.innerHTML = '<i class="bi bi-plus-circle me-1"></i> Simpan Voucher';
            showFormError('Terjadi kesalahan jaringan. Coba lagi.');
        });
    });

    // Reset form saat modal ditutup
    document.getElementById('modalVoucher').addEventListener('hidden.bs.modal', function () {
        kodeInput.value    = '';
        nominalInput.value = '';
        jenisSelect.value  = 'Rp';
        kodeValid          = false;
        hideKodeAlert();
        hideFormError();
        voucherList.innerHTML = '';
    });

});
</script>
</body>
</html>
