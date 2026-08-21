<style>
    :root {
        --primary-orange: #fd7e14;
    }

    /* Utility */
    .text-orange { color: var(--primary-orange); }
    
    /* Efek Glow Oranye untuk pesanan baru */
    .order-paid-highlight {
        box-shadow: 0 0 25px 8px rgba(253, 126, 20, 0.5) !important;
        border: 2px solid var(--primary-orange) !important;
        transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        transform: scale(1.02);
        z-index: 10;
        position: relative;
    }

    /* Card Styling */
    .card.order-card {
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,0.08);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card.order-card:hover {
        box-shadow: 0 8px 15px rgba(0,0,0,0.05) !important;
    }

    .badge-method {
        background: #fff4e6;
        color: var(--primary-orange);
        font-size: 0.7rem;
        font-weight: 700;
        border: 1px solid rgba(253, 126, 20, 0.2);
    }

    .info-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #adb5bd;
        font-weight: 600;
    }

    .item-table td {
        padding: 2px 0;
        font-size: 0.8rem;
    }

    .border-dashed {
        border-top: 1px dashed #dee2e6;
    }
</style>

<div class="row g-4">
    @forelse($orders as $order)
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card h-100 order-card shadow-sm" id="order-card-{{ $order->id }}">
            <div class="card-body p-3 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <span class="info-label d-block">No. Pesanan</span>
                        <span class="text-orange fw-bold">#{{ $order->nomor_pesanan }}</span>
                    </div>
                </div>

                <div class="mb-3">
                    <span class="info-label d-block">Waktu Transaksi</span>
                    <div class="text-dark fw-medium" style="font-size: 0.75rem;">
                        <i class="bi bi-calendar3 me-1 text-muted"></i> 
                        {{ \Carbon\Carbon::parse($order->created_at)->timezone('Asia/Jakarta')->translatedFormat('l, d F Y') }}
                        <span class="text-muted mx-1">|</span>
                        <i class="bi bi-clock me-1 text-muted"></i>
                        {{ \Carbon\Carbon::parse($order->created_at)->timezone('Asia/Jakarta')->format('H:i') }}
                    </div>
                </div>

                <div class="p-2 bg-light rounded-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-truncate" style="font-size: 0.9rem;">
                            {{ $order->nama_pelanggan }}
                        </h6>
                        <span class="badge bg-white text-dark border fw-bold">
                            M:{{ $order->no_meja ?? '-' }}
                        </span>
                    </div>
                </div>
                
                <div class="mb-3 flex-grow-1">
                    <span class="info-label d-block mb-1">Rincian Pesanan</span>
                    <div style="max-height: 100px; overflow-y: auto;">
                        <table class="table table-sm table-borderless item-table mb-0">
                           <tbody class="text-muted">
                                @foreach($order->orderDetails as $detail)
                                <tr>
                                    <td style="width: 10%; vertical-align: top;">{{ $detail->qty }}x</td>
                                    
                                    <td style="width: 50%;" class="text-truncate">
                                        <span class="d-block">{{ $detail->menu->nama_menu }}</span>
                                        
                                        @if($detail->variant)
                                            <span class="d-block text-orange fst-italic" style="font-size: 0.7rem; line-height: 1.2;">
                                                (+ {{ $detail->variant }})
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td style="width: 40%; vertical-align: top;" class="text-end text-dark">
                                        Rp{{ number_format($detail->qty * $detail->harga, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="border-dashed pt-2">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small text-muted">Total</span>
                        <span class="fw-bold text-dark">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                    </div>
                    @if(!empty($order->kode_voucher) && $order->nilai_voucher > 0)
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small text-muted">Voucher
                            <span class="fw-bold text-success" style="font-family:'Courier New',monospace;letter-spacing:1px;font-size:0.75rem;">{{ $order->kode_voucher }}</span>
                        </span>
                        <span class="small text-success fw-bold bg-success bg-opacity-10 px-2 rounded">
                            <i class="bi bi-ticket-perforated-fill me-1"></i>
                            - Rp {{ number_format($order->nilai_voucher, 0, ',', '.') }}
                        </span>
                    </div>
                    @endif
                </div>

                <div class="mt-3 pt-3 border-top">
                    <button type="button" class="btn btn-outline-success w-100 fw-bold" onclick="restoreOrder({{ $order->id }})">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> PULIHKAN
                    </button>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <img src="https://illustrations.popsy.co/amber/no-results.svg" style="width: 180px;" alt="Empty">
        <p class="mt-3 text-muted fw-medium">Belum ada riwayat pesanan hari ini.</p>
    </div>
    @endforelse
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const lastPaidId = localStorage.getItem('last_paid_order_id');
    
    if (lastPaidId) {
        const targetCard = document.getElementById(`order-card-${lastPaidId}`);
        if (targetCard) {
            targetCard.classList.add('order-paid-highlight');
            targetCard.scrollIntoView({ behavior: 'smooth', block: 'center' });

            setTimeout(() => {
                targetCard.classList.remove('order-paid-highlight');
                localStorage.removeItem('last_paid_order_id');
            }, 6000); 
        }
    }
});

// Fungsi untuk request pemulihan order
function restoreOrder(orderId) {
    Swal.fire({
        title: 'Pulihkan Pesanan?',
        text: "Pesanan ini akan dikembalikan ke antrean aktif dan memotong kembali stok menu.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Pulihkan!'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({ title: 'Memproses...', didOpen: () => Swal.showLoading(), allowOutsideClick: false });
            
            fetch(`/kasir/order-batal/${orderId}/restore`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Gagal', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
            });
        }
    });
}
</script>