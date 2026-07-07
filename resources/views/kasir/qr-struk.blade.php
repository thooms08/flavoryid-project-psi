<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Digital #{{ $order->nomor_pesanan }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    {{-- Poppins untuk body struk --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    {{-- Yeseva One untuk nama outlet — sama persis dengan page-outlet --}}
    <link href="https://fonts.googleapis.com/css2?family=Yeseva+One&display=swap" rel="stylesheet">
    @include('layouts.favicon')
    @include('layouts.loading')

    {{-- html-to-image: konversi DOM → PNG native tanpa screenshot JS --}}
    <script src="https://cdn.jsdelivr.net/npm/html-to-image@1.11.11/dist/html-to-image.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fff7ed;
            color: #1a1a1a; /* Diperjelas dari #333 */
        }

        /* ── Nama outlet: Yeseva One — sama dengan page-outlet ── */
        /* Yeseva One hanya punya weight 400 — jangan paksa 700/800 atau browser akan faux-bold */
        .outlet-name {
            font-family: 'Yeseva One', serif;
            font-weight: 400;
            font-size: 1.25rem;
            color: #0f172a;
            text-transform: capitalize;
            line-height: 1.2;
            margin-bottom: 2px;
            letter-spacing: 0.3px;
        }

        #capture-area {
            padding: 15px;
            background-color: #fff7ed;
            display: inline-block;
            width: 100%;
        }

        .receipt-paper {
            background: white;
            padding: 25px;
            width: 100%;
            max-width: 380px;
            margin: auto;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            font-family: 'Poppins', sans-serif;
            border-top: 5px solid #fd7e14;
            position: relative;
        }

        /* Efek gerigi kertas */
        .receipt-paper::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100%;
            height: 10px;
            background: linear-gradient(-45deg, transparent 5px, white 5px),
                        linear-gradient(45deg, transparent 5px, white 5px);
            background-size: 10px 10px;
        }

        .qr-container { text-align: center; padding: 20px; }

        .btn-orange {
            background-color: #fd7e14;
            color: white;
            border-radius: 10px;
            transition: 0.3s;
            border: none;
        }
        .btn-orange:hover { background-color: #e86d0c; color: white; }

        .border-dashed { border-top: 1px dashed #ccc; margin: 10px 0; }

        .kode-box {
            background-color: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 10px;
            margin-top: 15px;
            display: inline-block;
            min-width: 150px;
        }
        .kode-text {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 5px;
            color: #333;
            margin-bottom: 0;
        }

        /* Tabel item struk */
        .receipt-paper table { font-size: 0.84rem; color: #1a1a1a; } /* Diperjelas dari #333 */
        .receipt-paper .item-name { font-weight: 700; color: #0f172a; } /* Bold + hitam penuh */
        .receipt-paper .item-variant { font-size: 0.74rem; color: #4b5563; font-style: italic; } /* Abu lebih gelap */
        .receipt-paper .total-row td { font-weight: 700; font-size: 0.92rem; color: #0f172a; }

        /* Info order (tanggal, pelanggan, dll) */
        .receipt-paper table td { color: #1a1a1a; }

        /* Subtotal & bayar: sedikit redup tapi masih terbaca */
        .receipt-paper .text-muted-row td { color: #4b5563; }

        /* Footer struk */
        .receipt-footer {
            font-family: 'Poppins', sans-serif;
            font-size: 0.78rem;
            color: #374151; /* Diperjelas dari #6c757d */
            font-style: italic;
        }
        .receipt-footer .brand {
            color: #ea6c00; /* Oranye lebih gelap agar kontras */
            font-weight: 700;
            font-style: normal;
        }
    </style>
</head>
<body>

@php
    $isCustomerView = request()->query('mode') == 'view';
    $customerUrl    = route('kasir.qr-struk', ['uuid' => $order->uuid, 'mode' => 'view']);
@endphp

<div class="container py-4 py-md-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">

                    @if(!$isCustomerView)
                    <div class="d-flex align-items-center mb-3">
                        <a href="{{ route('kasir.riwayat.index') }}" class="btn btn-light rounded-circle me-3">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <h5 class="mb-0 fw-bold">QR Struk Digital</h5>
                    </div>
                    @endif

                    {{-- ── Panel QR Code ── --}}
                    <div id="qr-section" class="qr-container"
                         style="{{ $isCustomerView ? 'display:none' : 'display:block' }}">
                        <p class="text-muted small mb-2">Scan QR Code untuk melihat struk digital</p>
                        <div class="bg-white p-3 d-inline-block border shadow-sm mb-2">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode($customerUrl) }}" alt="QR Code">
                        </div>
                        <!--<div class="d-block mb-2">
                            <div class="kode-box">
                                <small class="text-muted d-block" style="font-size: 10px; letter-spacing: 1px;">Kode Struk</small>
                                <p class="kode-text">{{ strtolower(trim($order->kode_struk)) }}</p>
                            </div>-->
                        </div>
                        <div class="d-grid gap-2">
                            <button onclick="toggleView()" class="btn btn-orange fw-bold py-2">
                                <i class="bi bi-eye me-2"></i> Preview Struk
                            </button>
                        </div>
                    </div>

                    {{-- ── Panel Preview Struk ── --}}
                    <div id="struk-preview"
                         style="{{ $isCustomerView ? 'display:block' : 'display:none' }}">

                        <div id="capture-area">
                            <div class="receipt-paper" id="receipt-content">

                                {{-- Header outlet --}}
                                <div class="text-center mb-3">
                                    @if($outlet && $outlet->logo)
                                        <img src="{{ asset('assets/logo/' . $outlet->logo) }}"
                                             style="height: 50px; margin-bottom: 6px;" class="d-block mx-auto">
                                    @endif
                                    <div class="outlet-name">{{ $outlet->nama_outlet ?? 'Nama Outlet' }}</div>
                                    <p class="mb-0 mt-1" style="font-size: 10px; color: #4b5563;">
                                        {{ $outlet->alamat ?? '' }}
                                    </p>
                                    <div class="border-bottom border-1 my-2"></div>
                                </div>

                                {{-- Info order --}}
                                <table class="w-100 mb-2">
                                    <tr>
                                        <td>No: #{{ $order->nomor_pesanan }}</td>
                                        <td class="text-end">Meja: {{ $order->no_meja ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            {{ \Carbon\Carbon::parse($order->created_at)->timezone('Asia/Jakarta')->translatedFormat('d/m/Y H:i') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Pelanggan: {{ $order->nama_pelanggan }}</td>
                                    </tr>
                                </table>

                                <div class="border-dashed"></div>

                                {{-- Item pesanan --}}
                                <table class="w-100 mb-3">
                                    <tbody>
                                        @php $subTotalRaw = 0; @endphp
                                        @foreach($order->orderDetails as $detail)
                                        @php
                                            $itemTotal   = $detail->qty * $detail->harga;
                                            $subTotalRaw += $itemTotal;
                                        @endphp
                                        <tr>
                                            <td style="vertical-align:top; width:12%;">{{ $detail->qty }}x</td>
                                            <td style="padding-left:5px; width:58%; word-break:break-word; white-space:normal; padding-right:8px;">
                                                <span class="item-name d-block lh-sm mb-1">{{ $detail->menu->nama_menu }}</span>
                                                @if($detail->variant)
                                                    <span class="item-variant d-block">(+ {{ $detail->variant }})</span>
                                                @endif
                                            </td>
                                            <td class="text-end" style="vertical-align:top; width:30%;">
                                                {{ number_format($itemTotal, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="border-dashed"></div>

                                {{-- Total --}}
                                <table class="w-100">
                                    @if(isset($order->nominal_diskon) && $order->nominal_diskon > 0)
                                        <tr style="font-weight:400; color:#4b5563;">
                                            <td>Subtotal</td>
                                            <td class="text-end">Rp {{ number_format($subTotalRaw, 0, ',', '.') }}</td>
                                        </tr>
                                        @php
                                            $potongan    = 0;
                                            $labelDiskon = 'Diskon';
                                            if ($order->jenis_diskon === '%') {
                                                $potongan    = ($subTotalRaw * $order->nominal_diskon) / 100;
                                                $labelDiskon = 'Diskon (' . floatval($order->nominal_diskon) . '%)';
                                            } else {
                                                $potongan = $order->nominal_diskon;
                                            }
                                        @endphp
                                        <tr class="text-danger" style="font-weight:400;">
                                            <td>{{ $labelDiskon }}</td>
                                            <td class="text-end">-Rp {{ number_format($potongan, 0, ',', '.') }}</td>
                                        </tr>
                                    @endif
                                    <tr class="total-row">
                                        <td>TOTAL</td>
                                        <td class="text-end">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr style="font-weight:500; color:#374151;">
                                        <td>Bayar</td>
                                        <td class="text-end">Rp {{ number_format($order->uang_bayar, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr style="font-weight:500; color:#374151;">
                                        <td>Kembali</td>
                                        <td class="text-end">Rp {{ number_format($order->kembalian, 0, ',', '.') }}</td>
                                    </tr>
                                </table>

                                @php
                                    $keteranganBersih = trim(str_replace('(Order via PWA Offline)', '', $order->keterangan ?? ''));
                                @endphp
                                @if(!empty($keteranganBersih))
                                    <div class="border-dashed"></div>
                                    <p class="mb-0" style="font-size:11px; color:#374151;">
                                        <strong>Catatan:</strong> {{ $keteranganBersih }}
                                    </p>
                                @endif

                                <div class="text-center mt-4 pt-3 border-top receipt-footer">
                                    <p class="mb-1">{{ $teksCatatan ?? 'Terima Kasih Atas Kunjungan Anda!' }}</p>
                                    <small>Powered by <span class="brand">Flavory.id</span></small>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol aksi --}}
                        <div class="px-3 mt-4">
                            <div class="d-grid gap-2">
                                <button onclick="downloadReceipt(this)" class="btn btn-success fw-bold py-2 shadow-sm">
                                    <i class="bi bi-download me-2"></i> DOWNLOAD PNG
                                </button>
                                @if(!$isCustomerView)
                                <button onclick="toggleView()" class="btn btn-link text-muted text-decoration-none mt-2">
                                    <i class="bi bi-qr-code-scan"></i> Kembali ke QR
                                </button>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleView() {
        const qr    = document.getElementById('qr-section');
        const struk = document.getElementById('struk-preview');
        const showing = struk.style.display !== 'none';
        struk.style.display = showing ? 'none' : 'block';
        qr.style.display    = showing ? 'block' : 'none';
    }

    /**
     * Download struk sebagai PNG menggunakan html-to-image.
     */
    function downloadReceipt(btn) {
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Mengunduh...';
        btn.disabled  = true;

        const node = document.getElementById('capture-area');

        htmlToImage.toPng(node, {
            quality          : 1,
            pixelRatio       : 2,
            backgroundColor  : '#fff7ed',
            fontEmbedCSS     : true
        })
        .then(function (dataUrl) {
            const link      = document.createElement('a');
            link.download   = 'Struk-{{ $order->nomor_pesanan }}.png';
            link.href       = dataUrl;
            link.click();
        })
        .catch(function (err) {
            console.error(err);
            alert('Gagal mendownload struk. Silakan coba screenshot manual.');
        })
        .finally(function () {
            btn.innerHTML = '<i class="bi bi-download me-2"></i> DOWNLOAD PNG';
            btn.disabled  = false;
        });
    }
</script>

</body>
</html>
