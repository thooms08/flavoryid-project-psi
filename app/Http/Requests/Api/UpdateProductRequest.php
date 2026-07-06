<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'partner_id' => 'sometimes|required|integer|exists:partners,id',
            'nama_menu' => 'sometimes|required|string|max:255',
            'harga' => 'sometimes|required|numeric|min:0',
            'stok_menu' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
            'kategori' => 'nullable|string|max:100',
            'gambar' => 'nullable|string|max:255',
            'tipe_menu' => 'nullable|string|max:100',
            'status_menu' => 'nullable|string|max:50',
        ];
    }
}
