<!DOCTYPE html>
<html>
<head>
    <title>Kode Verifikasi Flavory.id</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; padding: 20px;">
    <div style="max-width: 500px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 12px; border-top: 5px solid #ff6a00;">
        <h2 style="color: #1e293b; margin-top: 0;">Halo, {{ $user->name ?? $user->username }}!</h2>
        <p style="color: #64748b; line-height: 1.6;">Kami menerima permintaan masuk tanpa password (Lupa Password) untuk akun Anda di <strong>Flavory.id</strong>.</p>
        <p style="color: #64748b;">Gunakan 6-digit kode verifikasi berikut untuk masuk ke dashboard Anda:</p>
        
        <div style="background: #fff4e6; padding: 15px; text-align: center; border-radius: 8px; margin: 25px 0;">
            <strong style="font-size: 32px; letter-spacing: 5px; color: #ff6a00;">{{ $code }}</strong>
        </div>
        
        <p style="color: #ef4444; font-size: 13px;"><em>*Kode ini hanya berlaku selama 10 menit. Jangan berikan kode ini kepada siapapun!</em></p>
        <p style="color: #64748b; font-size: 14px; margin-top: 30px;">Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini.</p>
        
        <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 20px 0;">
        <p style="color: #94a3b8; font-size: 12px; text-align: center;">&copy; {{ date('Y') }} Flavory.id - Digitalisasi Bisnis Kuliner</p>
    </div>
</body>
</html>