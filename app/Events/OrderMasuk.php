<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
// UBAH BARIS INI: Gunakan ShouldBroadcastNow
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; 
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// UBAH BARIS INI: Implementasikan ShouldBroadcastNow
class OrderMasuk implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function broadcastOn()
    {
        return new Channel('kasir.' . $this->order->partner_id);
    }

    public function broadcastAs()
    {
        return 'pesanan.baru';
    }
}