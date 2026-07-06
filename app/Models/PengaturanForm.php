<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengaturanForm extends Model
{
    protected $table = 'pengaturan_form';

    protected $fillable = [
        'partner_id',
        'nama',
        'whatsapp',
        'meja',
        'keterangan',
        'diskon',
        'bukti_pembayaran',
    ];

    // Casting tipe data ke boolean agar otomatis dikonversi saat diakses
    protected $casts = [
        'nama' => 'boolean',
        'whatsapp' => 'boolean',
        'meja' => 'boolean',
        'keterangan' => 'boolean',
        'diskon' => 'boolean',
        'bukti_pembayaran' => 'boolean',
    ];

    /**
     * Relasi ke model Partner (Pastikan model Partner sudah ada)
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}