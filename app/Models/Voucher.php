<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Voucher extends Model
{
    protected $table = 'vouchers';

    protected $fillable = [
        'partner_id',
        'kode_voucher',
        'jenis_potongan',
        'nominal_potongan',
        'is_active',
    ];

    protected $casts = [
        'is_active'        => 'boolean',
        'nominal_potongan' => 'decimal:2',
    ];

    /**
     * Relasi ke Partner
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}
