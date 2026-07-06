<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'menu_id' => $this->menu_id,
            'menu' => $this->whenLoaded('menu'),
            'qty' => (int) $this->qty,
            'harga' => (float) $this->harga,
            'variant' => $this->variant,
        ];
    }
}
