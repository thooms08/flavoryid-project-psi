<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'partner_id' => 'nullable|integer|exists:partners,id',
            'nama_pelanggan' => 'nullable|string|max:255',
            'no_whatsapp' => 'nullable|string|max:32',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|integer|exists:menus,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.variant' => 'nullable|string|max:255',
        ];
    }
}
