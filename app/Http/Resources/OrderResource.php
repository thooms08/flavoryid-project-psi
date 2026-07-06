<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid ?? null,
            'partner_id' => $this->partner_id,
            'nama_pelanggan' => $this->nama_pelanggan,
            'no_whatsapp' => $this->no_whatsapp,
            'total_harga' => (float) $this->total_harga,
            'status' => $this->status,
            'items' => OrderDetailResource::collection($this->whenLoaded('orderDetails')),
            'created_at' => optional($this->created_at)->toDateTimeString(),
        ];
    }
}
