<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InformasiOutlet extends Model
{
    use HasFactory;

    protected $table = 'informasi_outlet';

    protected $fillable = [
        'partner_id',
        'jenis_outlet',
        'jenis_lainnya',
        'nama_outlet',
        'logo',
        'foto_outlet',
        'page_name',
        'deskripsi',
        'alamat_lengkap',
        'kota',
        'provinsi',
        'maps',
    ];

    /**
     * Relasi Balik ke Partner.
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Relasi ke Media QR & Paket.
     */
    public function mediaQr(): HasOne
    {
        return $this->hasOne(MediaQr::class, 'informasi_id');
    }
}