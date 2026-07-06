<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToggleSound extends Model
{
    use HasFactory;

    protected $fillable = ['partner_id', 'is_sound_enabled'];

    // Relasi balik ke Partner (Opsional tapi baik untuk kerapian)
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
