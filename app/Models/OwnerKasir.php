<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Pivot: menghubungkan akun owner dengan akun kasir.
 * Dibuat otomatis saat user mendaftar.
 */
class OwnerKasir extends Model
{
    protected $table = 'owner_kasir';

    protected $fillable = ['owner_id', 'kasir_id'];

    /** Akun owner (role=owner) */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /** Akun kasir (role=kasir) */
    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }
}
