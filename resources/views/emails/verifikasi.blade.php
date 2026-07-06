<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #fff7ed; padding: 20px; }
        .container { background-color: #ffffff; padding: 30px; border-radius: 10px; max-width: 500px; margin: auto; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .kode { font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #ea580c; text-align: center; padding: 20px; background-color: #fffaf5; border: 2px dashed #fed7aa; border-radius: 10px; margin: 20px 0; }
        .footer { text-align: center; color: #94a3b8; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="color: #0f172a;">Halo, {{ $nama }}!</h2>
        <p style="color: #475569;">Terima kasih telah mendaftar sebagai partner <b>Flavory.id</b> dan menyelesaikan pembayaran. Anda selangkah lagi bergabung dalam ekosistem Flavory.id.</p>
        <p style="color: #475569;">Berikut adalah kode verifikasi unik untuk mengaktifkan akun kasir outlet Anda:</p>
        
        <div class="kode">
            {{ $kode }}
        </div>
        
        <p style="color: #475569;">Masukkan kode ini pada halaman verifikasi di browser Anda. Kode ini bersifat rahasia, jangan berikan kepada siapa pun.</p>
        
        <div class="footer">
            &copy; 2026 Flavory.id Security System<br>
            Email otomatis, mohon tidak dibalas.
        </div>
    </div>
</body>
</html>