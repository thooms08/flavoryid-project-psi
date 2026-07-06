<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modal extends Model
{
    use HasFactory;

    protected $table = 'modal';

    protected $fillable = [
        'partner_id',
        'nominal',
        'keterangan',
        'status'
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}