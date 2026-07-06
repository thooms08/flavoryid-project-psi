<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    protected $fillable = ['order_id', 'menu_id', 'qty', 'harga', 'variant'];

    public function order(): BelongsTo {
        return $this->belongsTo(Order::class);
    }

    public function menu(): BelongsTo {
        return $this->belongsTo(Menu::class);
    }
}