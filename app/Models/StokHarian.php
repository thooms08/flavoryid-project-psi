<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokHarian extends Model
{
    protected $table = 'stok_harian';

    protected $fillable = [
        'stok_barang_id', 'tanggal', 'jumlah'
    ];

    public function stokBarang(): BelongsTo
    {
        return $this->belongsTo(StokBarang::class);
    }
}