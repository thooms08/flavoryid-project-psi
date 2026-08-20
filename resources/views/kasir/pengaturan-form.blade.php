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
    /* Styling khusus untuk toggle warna oranye */
    .switch-orange:checked {
        background-color: #fd7e14 !important;
        border-color: #fd7e14 !important;
    }
    .switch-orange:focus {
        box-shadow: 0 0 0 0.25rem rgba(253, 126, 20, 0.25) !important;
    }
    /* Styling efek hover pada tombol kembali */
    .btn-back:hover {
        background-color: #e9ecef;
        color: #fd7e14;
    }
</style>
<body>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="{{ route('kasir.dashboard') }}" class="btn btn-light border-warning-subtle text-secondary rounded-4 d-flex align-items-center justify-content-center hover-scale" style="width: 48px; height: 48px;">
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

                        <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-0 border-bottom-0">
                            <div>
                                <h6 class="mb-0 fw-semibold">Catatan Pesanan</h6>
                                <small class="text-muted">Area teks untuk request khusus (opsional).</small>
                            </div>
                            <div class="form-check form-switch fs-4 mb-0">
                                <input class="form-check-input switch-orange toggle-setting" type="checkbox" 
                                    data-field="keterangan" {{ $pengaturan->keterangan ? 'checked' : '' }}>
                            </div>
                        </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggles = document.querySelectorAll('.toggle-setting');
    const alertNotif = document.getElementById('alert-notif');

    toggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const field = this.getAttribute('data-field');
            const status = this.checked ? 1 : 0;
            
            // Disable toggle sementara proses AJAX berjalan
            this.disabled = true;
            if (window.showLoading) window.showLoading();

            fetch('{{ route("kasir.pengaturan.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ field: field, status: status })
            })
            .then(response => response.json())
            .then(data => {
                this.disabled = false;
                if (window.hideLoading) window.hideLoading();
                if(data.success) {
                    // Tampilkan notifikasi
                    alertNotif.innerHTML = data.message;
                    alertNotif.classList.remove('d-none');
                    alertNotif.classList.add('fade', 'show');
                    
                    // Sembunyikan notif setelah 2.5 detik
                    setTimeout(() => {
                        alertNotif.classList.add('d-none');
                    }, 2500);
                }
            })
            .catch(error => {
                this.disabled = false;
                if (window.hideLoading) window.hideLoading();
                // Kembalikan ke posisi semula jika error
                this.checked = !this.checked; 
                console.error('Error:', error);
                alert('Terjadi kesalahan sistem saat menyimpan pengaturan.');
            });
        });
    });
});
</script>
</body>
</html>