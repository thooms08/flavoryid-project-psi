<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Verifikasi extends Model
{
    use HasFactory;

    protected $table = 'verifikasi';

    protected $fillable = [
        'partner_id',
        'kode_verifikasi',
        'konfirmasi',
    ];

    /**
     * Casting data otomatis (Laravel 11/12 style).
     */
    protected function casts(): array
    {
        return [
            'konfirmasi' => 'boolean',
        ];
    }

    /**
     * Relasi Balik ke Partner.
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}