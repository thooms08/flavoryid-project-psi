<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariantLevel extends Model
{
    use HasFactory;

    protected $table = 'variant_level';

    protected $fillable = [
        'menu_id',
        'variant',
        'harga',
    ];

    /**
     * Relasi ke model Menu
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

}