<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        $product = $this->route('product');
        return auth()->check() && (auth()->user()->isAdmin() || $product->user_id === auth()->id());
    }

    public function rules(): array
    {
        return [
            'title'         => 'required|string|min:3|max:255',
            'description'   => 'required|string|min:20',
            'price'         => 'required|numeric|min:0',
            'old_price'     => 'nullable|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'categories'    => 'required|array|min:1',
            'categories.*'  => 'exists:categories,id',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'images.*'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'     => 'boolean',
        ];
    }
}
