<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model {
    use HasFactory;

    protected $fillable = [
        'partner_id', 'nama_menu', 'deskripsi', 'kategori', 
        'gambar', 'harga', 'tipe_menu', 'status_menu', 'stok_menu'
    ];

    public function partner(): BelongsTo {
        return $this->belongsTo(Partner::class);
    }

    protected $with = ['variants'];

    public function variants(): HasMany
    {
        return $this->hasMany(VariantLevel::class, 'menu_id');
    }
}