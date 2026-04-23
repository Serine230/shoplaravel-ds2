<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
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

    public function messages(): array
    {
        return [
            'title.required'       => 'Le titre est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'description.min'      => 'La description doit contenir au moins 20 caractères.',
            'price.required'       => 'Le prix est obligatoire.',
            'price.numeric'        => 'Le prix doit être un nombre.',
            'price.min'            => 'Le prix doit être positif.',
            'stock.required'       => 'Le stock est obligatoire.',
            'categories.required'  => 'Choisissez au moins une catégorie.',
            'image.image'          => 'Le fichier doit être une image.',
            'image.max'            => 'L\'image ne doit pas dépasser 2 Mo.',
        ];
    }
}
