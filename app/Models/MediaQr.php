<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MediaQr extends Model
{
    use HasFactory;

    protected $table = 'media_qr';

    protected $fillable = [
        'informasi_id',
        'facebook',
        'instagram',
        'youtube',
        'tiktok',
        'x',
        'paket',
        'status',
        'invoice_number', 
        'payment_url',    
        'payment_status', 
    ];

    /**
     * Relasi Balik ke Informasi Outlet.
     */
    public function outlet(): BelongsTo
    {
        return $this->belongsTo(InformasiOutlet::class, 'informasi_id');
    }

    /**
     * Helper untuk cek apakah paket berbayar.
     */
    public function isPremium(): bool
    {
        return in_array($this->paket, ['eksklusif', 'super_eksklusif']);
    }
}