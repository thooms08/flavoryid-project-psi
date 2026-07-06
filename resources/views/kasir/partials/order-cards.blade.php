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
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <span class="info-label d-block">No. Pesanan</span>
                        <span class="text-orange fw-bold">#{{ $order->nomor_pesanan }}</span>
                    </div>

                    <div class="dropdown">
            <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-three-dots-vertical fs-5"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                <li>
                    <a class="dropdown-item small" href="{{ route('kasir.qr-struk', $order->uuid) }}">
                        <i class="bi bi-qr-code me-2 text-secondary"></i> QR Struk Digital
                    </a>
                </li>
                @if(!empty($order->no_whatsapp))
                <li>
                    @php
                        $no_wa = $order->no_whatsapp;
                        if(str_starts_with($no_wa, '0')) $no_wa = '62' . substr($no_wa, 1);
                        $no_wa = preg_replace('/[^0-9]/', '', $no_wa);
                        
                        $url_struk = route('kasir.qr-struk', $order->uuid);
                        $teks_wa = "Halo *{$order->nama_pelanggan}*, Berikut adalah struk digital pesanan Anda #{$order->nomor_pesanan}: {$url_struk}";
                    @endphp
                    <a class="dropdown-item small" 
                    href="https://api.whatsapp.com/send?phone={{ $no_wa }}&text={{ urlencode($teks_wa) }}" 
                    target="_blank">
                        <i class="bi bi-whatsapp me-2 text-secondary"></i> Kirim via WhatsApp
                    </a>
                </li>
                @endif
                <li>
                    <a class="dropdown-item small" href="{{ route('kasir.print-struk', $order->uuid) }}" target="_blank">
                        <i class="bi bi-printer me-2 text-secondary"></i> Cetak Struk Via USB
                    </a>
                </li>
                <li>
                    <a class="dropdown-item small" href="{{ route('kasir.print-bt', $order->uuid) }}">
                        <i class="bi bi-bluetooth me-2 text-secondary"></i> Cetak Struk Via BT
                    </a>
                </li>
                <li>
                    <a class="dropdown-item small" href="{{ route('kasir.print-kitchen-bt', $order->id) }}">
                        <i class="bi bi-receipt me-2 text-secondary"></i> Cetak Struk Kitchen
                    </a>
                </li>
            </ul>
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

                <div class="mb-3">
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

               @php
                    $keteranganBersih = trim(str_replace('(Order via PWA Offline)', '', $order->keterangan ?? ''));
                @endphp
                
                @if(!empty($keteranganBersih))
                <div class="p-2 bg-warning bg-opacity-10 border-start border-warning border-3 rounded-1 small mb-3">
                    <i class="bi bi-info-circle-fill text-warning me-1"></i>
                    <strong>Catatan:</strong> {{ $keteranganBersih }}
                </div>
                @endif
                <div class="border-dashed pt-2">
                    <div class="d-flex justify-content-between mb-2">
                      <span class="small text-muted">Metode Bayar</span>
                    <span class="badge badge-method px-2 py-1 rounded-pill"><i class="bi bi-wallet2 me-1"></i>{{ strtoupper($order->metode_bayar) }}</span>
                </div>
                @if(isset($order->nominal_diskon) && $order->nominal_diskon > 0)
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small text-muted">Diskon</span>
                        <span class="small text-danger fw-bold bg-danger bg-opacity-10 px-2 rounded">
                            <i class="bi bi-tag-fill me-1"></i>
                            @if($order->jenis_diskon === '%')
                                {{ floatval($order->nominal_diskon) }}%
                            @else
                                Rp {{ number_format($order->nominal_diskon, 0, ',', '.') }}
                            @endif
                        </span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small text-muted">Total</span>
                        <span class="fw-bold text-dark">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small text-muted">Dibayar</span>
                        <span class="small fw-medium">Rp {{ number_format($order->uang_bayar, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="small text-muted">Kembalian</span>
                        <span class="small fw-medium">Rp {{ number_format($order->kembalian, 0, ',', '.') }}</span>
                    </div>
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
            }, 6000); // Sedikit lebih lama agar kasir sempat melihat
        }
    }
});
</script>