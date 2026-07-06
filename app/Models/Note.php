<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    protected $fillable = [
        'partner_id',
        'note',
        'status'
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}