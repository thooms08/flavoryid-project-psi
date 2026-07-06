<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; color: #333; }
        .email-wrapper { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header { background-color: #fd7e14; padding: 30px 20px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 600; letter-spacing: 1px; }
        .content { padding: 40px 30px; }
        .greeting { font-size: 18px; font-weight: 600; margin-bottom: 20px; color: #2d3748; }
        .message { font-size: 15px; line-height: 1.6; color: #4a5568; margin-bottom: 30px; }
        .invoice-box { background-color: #fffaf5; border: 1px solid #feebc8; border-radius: 8px; padding: 25px; margin-bottom: 30px; }
        .invoice-row { display: flex; justify-content: space-between; margin-bottom: 15px; border-bottom: 1px dashed #e2e8f0; padding-bottom: 10px; }
        .invoice-row:last-child { margin-bottom: 0; border-bottom: none; padding-bottom: 0; }
        .invoice-label { color: #718096; font-size: 14px; }
        .invoice-value { font-weight: 600; color: #2d3748; font-size: 15px; text-align: right; }
        .total-row { background-color: #fd7e14; color: white; padding: 15px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; margin-top: 15px; }
        .total-label { font-size: 16px; font-weight: 600; }
        .total-value { font-size: 22px; font-weight: 700; }
        .btn-wrapper { text-align: center; margin-top: 30px; }
        .btn-pay { background-color: #fd7e14; color: #ffffff; text-decoration: none; padding: 14px 35px; border-radius: 50px; font-weight: 600; font-size: 16px; display: inline-block; transition: background-color 0.3s; }
        .btn-pay:hover { background-color: #e86c00; }
        .footer { background-color: #f8fafc; padding: 20px; text-align: center; font-size: 13px; color: #a0aec0; border-top: 1px solid #edf2f7; }
        .status-badge { display: inline-block; padding: 6px 15px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; }
        .status-warning { background-color: #fefcbf; color: #b7791f; }
        .status-danger { background-color: #fed7d7; color: #c53030; }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="header">
            <h1>Flavory.id</h1>
        </div>
        
        <div class="content">
            @if($data['status'] === 'jatuh_tempo')
                <div style="text-align: center;">
                    <span class="status-badge status-warning">Jatuh Tempo</span>
                </div>
            @else
                <div style="text-align: center;">
                    <span class="status-badge status-danger">Layanan Nonaktif</span>
                </div>
            @endif

            <div class="greeting">Halo, {{ $data['nama_user'] }}!</div>
            
            <div class="message">
                @if($data['status'] === 'jatuh_tempo')
                    Masa aktif langganan untuk outlet <strong>{{ $data['nama_outlet'] }}</strong> telah memasuki masa tenggang. Segera lakukan pembayaran agar operasional kasir Anda tidak terganggu.
                @else
                    Masa tenggang untuk outlet <strong>{{ $data['nama_outlet'] }}</strong> telah habis. Saat ini layanan kasir Anda <strong>dinonaktifkan sementara</strong>. Silakan lakukan pembayaran untuk mengaktifkan kembali layanan.
                @endif
            </div>

            <div class="invoice-box">
                <div class="invoice-row">
                    <span class="invoice-label">Nama Outlet</span>
                    <span class="invoice-value">{{ $data['nama_outlet'] }}</span>
                </div>
                <div class="invoice-row">
                    <span class="invoice-label">Paket Langganan</span>
                    <span class="invoice-value" style="text-transform: capitalize;">{{ $data['paket'] }}</span>
                </div>
                <div class="total-row">
                    <span class="total-label">Total Tagihan</span>
                    <span class="total-value">Rp {{ number_format($data['tagihan'], 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="btn-wrapper">
                <a href="{{ url('/login') }}" class="btn-pay">Bayar Sekarang</a>
            </div>
            
            <p style="text-align: center; font-size: 13px; color: #718096; margin-top: 30px;">
                Abaikan email ini jika Anda sudah melakukan pembayaran.
            </p>
        </div>
        
        <div class="footer">
            &copy; {{ date('Y') }} Flavory.id. Hak Cipta Dilindungi.
        </div>
    </div>
</body>
</html>