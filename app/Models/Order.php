<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{

    protected $fillable = [
        'partner_id', 'offline_id', 'kode_struk','nomor_pesanan', 'nama_pelanggan', 'no_whatsapp', 
        'no_meja', 'keterangan', 'total_harga', 'uang_bayar', 
        'metode_bayar', 'kembalian', 'status','bukti_bayar', 'nominal_diskon', 'jenis_diskon'
    ];

    public function partner(): BelongsTo {
        return $this->belongsTo(Partner::class);
    }

    public function orderDetails(): HasMany {
        return $this->hasMany(OrderDetail::class);
    }

    protected static function booted() {
        static::creating(function ($order) {
            $order->uuid = (string) Str::uuid();
        });
    }
}