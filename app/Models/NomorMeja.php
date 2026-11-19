<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NomorMeja extends Model
{
    protected $table = 'nomor_meja';

    protected $fillable = [
        'partner_id',
        'nama_meja',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}
