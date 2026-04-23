@extends('layouts.app')
@section('title', 'Mon Panier')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="text-3xl font-extrabold text-gray-900 mb-8 flex items-center gap-3">
        <i data-lucide="shopping-cart" class="w-8 h-8 text-indigo-600"></i>
        Mon Panier
        @if($products->count())
            <span class="text-lg font-normal text-gray-400">({{ $products->count() }} article(s))</span>
        @endif
    </h1>

    @if($products->isEmpty())
        <div class="text-center py-24 bg-white rounded-3xl border border-gray-100 shadow-sm">
            <div class="w-24 h-24 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="shopping-cart" class="w-12 h-12 text-indigo-300"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-700 mb-3">Votre panier est vide</h2>
            <p class="text-gray-400 mb-8">Explorez notre catalogue et ajoutez des produits !</p>
            <a href="{{ route('products.index') }}" class="btn-primary !text-base !px-8 !py-3.5">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i> Explorer le catalogue
            </a>
        </div>
    @else
        <div class="flex flex-col lg:flex-row gap-8">

            {{-- ═══ CART ITEMS ═══ --}}
            <div class="flex-1 space-y-4">
                @foreach($products as $product)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex gap-5">
                        {{-- Image --}}
                        <a href="{{ route('products.show', $product) }}" class="flex-shrink-0">
                            <img src="{{ $product->image_url }}" alt="{{ $product->title }}"
                                 class="w-24 h-24 object-cover rounded-xl border border-gray-100">
                        </a>

                        {{-- Details --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <a href="{{ route('products.show', $product) }}"
                                       class="font-bold text-gray-900 hover:text-indigo-600 transition-colors leading-tight line-clamp-2">
                                        {{ $product->title }}
                                    </a>
                                    <p class="text-sm text-gray-400 mt-1">{{ number_format($product->price, 2) }} DT / unité</p>
                                </div>
                                <p class="font-extrabold text-indigo-700 text-lg flex-shrink-0">
                                    {{ number_format($product->cart_subtotal, 2) }} DT
                                </p>
                            </div>

                            <div class="flex items-center justify-between mt-4">
                                {{-- Quantity control --}}
                                <form action="{{ route('cart.update', $product) }}" method="POST" class="flex items-center gap-2">
                                    @csrf @method('PUT')
                                    <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden">
                                        <button type="submit" name="qty" value="{{ max(0, $product->cart_qty - 1) }}"
                                                class="px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold transition-colors text-lg">−</button>
                                        <span class="px-4 py-2 font-bold text-gray-900 min-w-[40px] text-center">{{ $product->cart_qty }}</span>
                                        <button type="submit" name="qty" value="{{ min($product->stock, $product->cart_qty + 1) }}"
                                                class="px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold transition-colors text-lg">+</button>
                                    </div>
                                    <span class="text-xs text-gray-400">Max: {{ $product->stock }}</span>
                                </form>

                                {{-- Remove --}}
                                <form action="{{ route('cart.remove', $product) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="flex items-center gap-1.5 text-sm text-red-400 hover:text-red-600 hover:bg-red-50 px-3 py-2 rounded-xl transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i> Retirer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Clear cart --}}
                <form action="{{ route('cart.clear') }}" method="POST"
                      onsubmit="return confirm('Vider tout le panier ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-sm text-gray-400 hover:text-red-500 transition-colors flex items-center gap-1.5">
                        <i data-lucide="trash" class="w-4 h-4"></i> Vider le panier
                    </button>
                </form>
            </div>

            {{-- ═══ ORDER SUMMARY ═══ --}}
            <div class="w-full lg:w-80 flex-shrink-0">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-24">
                    <h2 class="font-bold text-gray-900 text-xl mb-6">Récapitulatif</h2>

                    <div class="space-y-3 text-sm mb-6">
                        @foreach($products as $p)
                            <div class="flex justify-between text-gray-600">
                                <span class="truncate max-w-[160px]">{{ Str::limit($p->title, 20) }} x{{ $p->cart_qty }}</span>
                                <span class="font-medium text-gray-800">{{ number_format($p->cart_subtotal, 2) }} DT</span>
                            </div>
                        @endforeach

                        <div class="border-t border-gray-100 pt-3 flex justify-between text-gray-600">
                            <span>Sous-total</span>
                            <span class="font-medium">{{ number_format($total, 2) }} DT</span>
                        </div>

                        <div class="flex justify-between text-gray-600">
                            <span>Livraison</span>
                            @if($total >= 100)
                                <span class="text-green-600 font-semibold">Gratuite 🎉</span>
                            @else
                                <span class="font-medium">7.00 DT</span>
                            @endif
                        </div>

                        @if($total < 100)
                            <div class="bg-amber-50 rounded-xl p-3 text-amber-700 text-xs">
                                <i data-lucide="info" class="w-3 h-3 inline"></i>
                                Ajoutez {{ number_format(100 - $total, 2) }} DT de plus pour la livraison gratuite !
                            </div>
                        @endif

                        <div class="border-t border-gray-100 pt-3 flex justify-between text-lg font-extrabold text-gray-900">
                            <span>Total</span>
                            <span class="text-indigo-700">{{ number_format($total + ($total >= 100 ? 0 : 7), 2) }} DT</span>
                        </div>
                    </div>

                    <a href="{{ route('orders.checkout') }}" class="btn-primary w-full justify-center !py-4 !text-base">
                        <i data-lucide="credit-card" class="w-5 h-5"></i> Passer la commande
                    </a>

                    <a href="{{ route('products.index') }}" class="block text-center text-sm text-indigo-600 font-medium mt-4 hover:underline">
                        ← Continuer mes achats
                    </a>

                    <div class="flex items-center gap-2 mt-6 pt-4 border-t border-gray-100">
                        <i data-lucide="shield-check" class="w-4 h-4 text-green-500 flex-shrink-0"></i>
                        <p class="text-xs text-gray-400">Paiement 100% sécurisé. Vos données sont protégées.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
