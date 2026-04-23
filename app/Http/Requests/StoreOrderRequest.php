<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'shipping_address' => 'required|string|min:10',
            'shipping_city'    => 'required|string|min:2',
            'shipping_phone'   => 'required|string|regex:/^[0-9+\s\-]{8,15}$/',
            'payment_method'   => 'required|in:cash,card,virement',
            'notes'            => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'shipping_address.required' => 'L\'adresse de livraison est obligatoire.',
            'shipping_city.required'    => 'La ville est obligatoire.',
            'shipping_phone.required'   => 'Le numéro de téléphone est obligatoire.',
            'payment_method.in'         => 'Méthode de paiement invalide.',
        ];
    }
}
