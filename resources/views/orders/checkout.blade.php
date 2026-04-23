@extends('layouts.app')
@section('title', 'Finaliser la commande')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Finaliser la commande</h1>
    <p class="text-gray-500 mb-8">Renseignez vos informations de livraison pour continuer.</p>

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <div class="flex flex-col lg:flex-row gap-8">

            {{-- ═══ LEFT: FORM ═══ --}}
            <div class="flex-1 space-y-6">

                {{-- Delivery info --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
                    <h2 class="font-bold text-gray-900 text-lg flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-5 h-5 text-indigo-500"></i>
                        Adresse de livraison
                    </h2>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Adresse complète <span class="text-red-500">*</span></label>
                        <textarea name="shipping_address" rows="2" required
                                  class="input-field resize-none @error('shipping_address') border-red-400 @enderror"
                                  placeholder="N° rue, quartier, immeuble...">{{ old('shipping_address', auth()->user()->address) }}</textarea>
                        @error('shipping_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ville <span class="text-red-500">*</span></label>
                            <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" required
                                   class="input-field @error('shipping_city') border-red-400 @enderror"
                                   placeholder="Tunis, Sfax...">
                            @error('shipping_city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Téléphone <span class="text-red-500">*</span></label>
                            <input type="tel" name="shipping_phone" value="{{ old('shipping_phone', auth()->user()->phone) }}" required
                                   class="input-field @error('shipping_phone') border-red-400 @enderror"
                                   placeholder="+216 XX XXX XXX">
                            @error('shipping_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Payment --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="font-bold text-gray-900 text-lg flex items-center gap-2 mb-5">
                        <i data-lucide="credit-card" class="w-5 h-5 text-indigo-500"></i>
                        Mode de paiement
                    </h2>

                    <div class="space-y-3">
                        @foreach([
                            ['cash',    'money-bill-wave', 'Paiement à la livraison',  'Payez en espèces à la réception de votre colis.'],
                            ['card',    'credit-card',     'Carte bancaire (simulée)',  'Paiement sécurisé par carte (mode démo).'],
                            ['virement','building-columns', 'Virement bancaire',        'Recevez nos coordonnées bancaires par email.'],
                        ] as [$val, $icon, $label, $desc])
                            <label class="flex items-start gap-4 p-4 border-2 rounded-xl cursor-pointer
                                         has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 border-gray-200 hover:border-indigo-300 transition-all">
                                <input type="radio" name="payment_method" value="{{ $val }}"
                                       {{ old('payment_method', 'cash') === $val ? 'checked' : '' }}
                                       class="mt-0.5 text-indigo-600">
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">{{ $label }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $desc }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('payment_method') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                {{-- Notes --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Notes pour le vendeur (optionnel)</label>
                    <textarea name="notes" rows="2" class="input-field resize-none"
                              placeholder="Instructions particulières, horaires de disponibilité...">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- ═══ RIGHT: SUMMARY ═══ --}}
            <div class="w-full lg:w-80 flex-shrink-0">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-24">
                    <h2 class="font-bold text-gray-900 text-xl mb-5">Votre commande</h2>

                    <div class="space-y-3 mb-5">
                        @foreach($products as $p)
                            <div class="flex items-center gap-3">
                                <img src="{{ $p->image_url }}" alt="" class="w-12 h-12 rounded-xl object-cover border border-gray-100">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-800 truncate">{{ $p->title }}</p>
                                    <p class="text-xs text-gray-400">× {{ $p->cart_qty }}</p>
                                </div>
                                <span class="text-sm font-bold text-gray-900">{{ number_format($p->cart_subtotal, 2) }} DT</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-100 pt-4 space-y-2 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Sous-total</span><span class="font-medium">{{ number_format($subtotal, 2) }} DT</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Livraison</span>
                            @if($shipping == 0)
                                <span class="text-green-600 font-semibold">Gratuite</span>
                            @else
                                <span>{{ number_format($shipping, 2) }} DT</span>
                            @endif
                        </div>
                        <div class="border-t border-gray-100 pt-2 flex justify-between text-lg font-extrabold text-gray-900">
                            <span>Total</span>
                            <span class="text-indigo-700">{{ number_format($total, 2) }} DT</span>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full justify-center !py-4 !text-base mt-6">
                        <i data-lucide="check-circle" class="w-5 h-5"></i> Confirmer la commande
                    </button>

                    <div class="flex items-center gap-2 mt-4 text-xs text-gray-400">
                        <i data-lucide="lock" class="w-3 h-3"></i>
                        Transaction sécurisée et cryptée
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
