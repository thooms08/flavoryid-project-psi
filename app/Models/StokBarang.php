<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokBarang extends Model
{
    protected $table = 'stok_barang';

    protected $fillable = [
        'partner_id', 'nama_barang', 'satuan',
        'min_hijau', 'max_hijau', 
        'min_kuning', 'max_kuning', 
        'min_merah', 'max_merah'
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function stokHarian(): HasMany
    {
        return $this->hasMany(StokHarian::class);
    }

    // Accessor untuk mendapatkan stok terbaru
    public function getStokTerakhirAttribute()
    {
        $stokTerbaru = $this->stokHarian()->orderBy('tanggal', 'desc')->first();
        return $stokTerbaru ? $stokTerbaru->jumlah : 0;
    }
}