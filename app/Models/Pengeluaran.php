<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengeluaran extends Model
{
    protected $fillable = [
        'partner_id', 'kategori', 'keterangan', 'item', 'harga', 'qty', 'total', 'status'
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function buktiPengeluaran(): HasMany
    {
        return $this->hasMany(BuktiPengeluaran::class, 'pengeluaran_id');
    }
}