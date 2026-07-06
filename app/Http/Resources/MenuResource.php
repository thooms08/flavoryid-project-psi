<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nama_menu' => $this->nama_menu,
            'deskripsi' => $this->deskripsi,
            'kategori' => $this->kategori,
            'harga' => (float) $this->harga,
            'stok_menu' => $this->stok_menu,
            'gambar' => $this->gambar,
            'variants' => $this->whenLoaded('variants'),
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
        ];
    }
}
