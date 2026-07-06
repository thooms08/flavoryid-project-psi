<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Struk #{{ $order->nomor_pesanan }}</title>
    <style>
        @page { 
            margin: 0; 
        }
        body { 
            /* Menggunakan Arial Bold agar tinta cetak thermal lebih tebal dan tajam */
            font-family: 'Arial', sans-serif; 
            font-weight: bold; 
            width: 58mm; 
            margin: 0; 
            padding: 0; 
            font-size: 13px; 
            color: #000;
            line-height: 1.2;
        }
        .container {
            padding: 2mm;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        /* Karena body sudah bold, class ini untuk jaga-jaga saja */
        .fw-bold { font-weight: bold; } 
        .text-uppercase { text-transform: uppercase; }
        
        /* Garis putus-putus lebih tegas */
        .border-bottom { 
            border-bottom: 2px dashed #000; /* Ditebalkan menjadi 2px */
            margin: 8px 0; 
        }
        
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; padding: 2px 0; }
        
        /* Google Fonts tidak diperlukan di struk USB karena printer tidak bisa render web font.
           Poppins di-stack dengan Arial Bold sebagai fallback agar cetak tetap tebal & rapi. */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@700;800&display=swap');

        .header-title {
            font-family: 'Poppins', 'Arial', sans-serif;
            font-size: 16px;
            font-weight: 800;
            margin: 0;
            line-height: 1.1;
            letter-spacing: 0.3px;
        }
        .outlet-info {
            font-size: 11px;
            margin-top: 2px;
            font-weight: normal; /* Dibuat normal agar beda dengan judul */
        }
        .qr-section {
            margin-top: 10px;
            padding: 5px;
        }
        .qr-image {
            width: 120px; 
            height: 120px;
            margin-bottom: 5px;
        }
        .footer { 
            margin-top: 5px; 
            font-size: 11px; 
            line-height: 1.4;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 5px;
        }
    
        .outlet-logo {
            max-width: 40mm; 
            max-height: 25mm;
            display: block;
            margin: 0 auto 5px auto;
            /* Kontras dinaikkan agar hasil cetak hitam putih lebih pekat */
            filter: grayscale(100%) contrast(1.5); 
        }
    </style>
</head>
<body onload="window.print(); setTimeout(window.close, 500);">

    <div class="container">
        <div class="text-center">
        @if($outlet && $outlet->logo)
            <div class="logo-container mb-3">
                <img src="{{ asset('storage/logo/' . basename($outlet->logo)) }}" 
                     alt="Logo" 
                     class="outlet-logo">
            </div>
        @endif
        
        <h2 class="header-title fw-bold text-uppercase">{{ $outlet->nama_outlet ?? 'OUTLET' }}</h2>
        <div class="outlet-info">{{ $outlet->alamat ?? '' }}</div>
        </div>

        <div class="border-bottom"></div>

        <table>
            <tr>
                <td class="fw-bold">#{{ $order->nomor_pesanan }}</td>
                <td class="text-right fw-bold">MEJA: {{ $order->no_meja ?? '-' }}</td>
            </tr>
            <tr>
                <td colspan="2" style="font-weight: normal; font-size: 12px;">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/y H:i') }}</td>
            </tr>
            <tr>
                <td colspan="2">Pelanggan: {{ $order->nama_pelanggan }}</td>
            </tr>
        </table>

        <div class="border-bottom"></div>

        <table>
            @php $subTotalRaw = 0; @endphp
            @foreach($order->orderDetails as $detail)
            @php 
                $itemTotal = $detail->qty * $detail->harga;
                $subTotalRaw += $itemTotal;
            @endphp
            <tr>
                <td colspan="2" class="fw-bold">
                    {{ $detail->menu->nama_menu }}
                    
                    @if($detail->variant)
                        <div style="font-weight: normal; font-size: 11px; margin-top: 1px;">
                            (+ {{ $detail->variant }})
                        </div>
                    @endif
                </td>
            </tr>
            <tr>
                <td style="padding-bottom: 5px; font-weight: normal;">{{ $detail->qty }} x {{ number_format($detail->harga, 0, ',', '.') }}</td>
                <td class="text-right" style="padding-bottom: 5px;">{{ number_format($itemTotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </table>

        <div class="border-bottom"></div>

       <table style="font-size: 14px;">
            @if(isset($order->nominal_diskon) && $order->nominal_diskon > 0)
                <tr style="font-weight: normal; font-size: 12px;">
                    <td>SUBTOTAL</td>
                    <td class="text-right">{{ number_format($subTotalRaw, 0, ',', '.') }}</td>
                </tr>
                
                @php
                    $potongan = 0;
                    $labelDiskon = "DISKON";
                    
                    if ($order->jenis_diskon === '%') {
                        $potongan = ($subTotalRaw * $order->nominal_diskon) / 100;
                        $labelDiskon = "DISKON (" . floatval($order->nominal_diskon) . "%)";
                    } else {
                        $potongan = $order->nominal_diskon;
                    }
                @endphp
                
                <tr style="font-weight: normal; font-size: 12px;">
                    <td style="padding-bottom: 4px;">{{ $labelDiskon }}</td>
                    <td class="text-right" style="padding-bottom: 4px;">-{{ number_format($potongan, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr class="fw-bold">
                <td>TOTAL</td>
                <td class="text-right">Rp{{ number_format($order->total_harga, 0, ',', '.') }}</td>
            </tr>
            <tr style="font-weight: normal; font-size: 12px;">
                <td>BAYAR</td>
                <td class="text-right">{{ number_format($order->uang_bayar, 0, ',', '.') }}</td>
            </tr>
            <tr style="font-weight: normal; font-size: 12px;">
                <td>KEMBALI</td>
                <td class="text-right">{{ number_format($order->kembalian, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="border-bottom"></div>
        
        <div class="text-center footer">
            @php
                $rawCatatan = $order->keterangan ?? '';
                $cleanCatatan = trim(str_replace('(Order via PWA Offline)', '', $rawCatatan));
            @endphp
            
            @if(!empty($cleanCatatan))
                <div style="text-align: left; margin-bottom: 8px;">
                    <strong>Catatan:</strong><br>
                    <span style="font-weight: normal;">{{ $cleanCatatan }}</span>
                </div>
                <div class="border-bottom" style="margin: 4px 0;"></div>
            @endif
            <p style="font-weight: normal;">{{ $teksCatatan ?? 'Terima Kasih Atas Kunjungan Anda!' }}<br>Powered by <strong>Flavory.id</strong></p>
            <div style="margin-top: 10px; color: #fff;">.</div>
        </div>
    </div>
</body>
</html>