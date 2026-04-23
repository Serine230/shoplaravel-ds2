@extends('layouts.admin')
@section('title', $product->title)
@section('subtitle', 'Détail du produit')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left: product info --}}
    <div class="lg:col-span-2 space-y-5">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex gap-6 p-6">
                <img src="{{ $product->image_url }}" alt="{{ $product->title }}"
                     class="w-36 h-36 object-cover rounded-2xl border border-gray-100 flex-shrink-0">
                <div class="flex-1">
                    <div class="flex items-start justify-between gap-3">
                        <h2 class="text-xl font-extrabold text-gray-900 leading-tight">{{ $product->title }}</h2>
                        <div class="flex gap-2 flex-shrink-0">
                            <span class="{{ $product->is_active ? 'badge-green' : 'badge-red' }}">
                                {{ $product->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                            @if($product->is_featured)
                                <span class="badge-purple">⭐ Vedette</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2 mt-2">
                        @foreach($product->categories as $cat)
                            <span class="badge-blue text-xs">{{ $cat->name }}</span>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-3 gap-4 mt-4">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Prix</p>
                            <p class="font-extrabold text-indigo-700 text-lg">{{ number_format($product->price, 2) }} DT</p>
                            @if($product->old_price)
                                <p class="text-xs text-gray-400 line-through">{{ number_format($product->old_price, 2) }} DT</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Stock</p>
                            <p class="font-bold text-lg {{ $product->stock < 5 ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $product->stock }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Vues</p>
                            <p class="font-bold text-lg text-gray-900">{{ number_format($product->views) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 pb-6">
                <h3 class="font-semibold text-gray-700 text-sm mb-2">Description</h3>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $product->description }}</p>
            </div>

            <div class="border-t border-gray-100 px-6 py-4 bg-gray-50 flex gap-3">
                <a href="{{ route('admin.products.edit', $product) }}" class="btn-primary text-sm">
                    <i data-lucide="edit" class="w-4 h-4"></i> Modifier
                </a>
                <a href="{{ route('products.show', $product) }}" target="_blank"
                   class="flex items-center gap-2 text-sm text-gray-500 hover:text-indigo-600 px-3 py-2 rounded-xl hover:bg-indigo-50 transition-colors font-medium">
                    <i data-lucide="external-link" class="w-4 h-4"></i> Voir sur la boutique
                </a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                      onsubmit="return confirm('Supprimer ce produit ?')" class="ml-auto">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger text-sm">
                        <i data-lucide="trash-2" class="w-4 h-4"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>

        {{-- Reviews --}}
        @if($product->reviews->count())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-900">Avis ({{ $product->reviews->count() }})</h3>
                <div class="flex items-center gap-1">
                    @for($i = 1; $i <= 5; $i++)
                        <i data-lucide="star" class="w-4 h-4 {{ $i <= $product->average_rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-200 fill-gray-200' }}"></i>
                    @endfor
                    <span class="text-sm font-bold text-gray-700 ml-1">{{ $product->average_rating }}/5</span>
                </div>
            </div>
            <div class="divide-y divide-gray-50 max-h-80 overflow-y-auto">
                @foreach($product->reviews as $review)
                    <div class="px-6 py-4 flex items-start gap-3">
                        <img src="{{ $review->user->avatar_url }}" alt="" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-semibold text-sm text-gray-900">{{ $review->user->name }}</span>
                                <div class="flex gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i data-lucide="star" class="w-3 h-3 {{ $i <= $review->rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-200 fill-gray-200' }}"></i>
                                    @endfor
                                </div>
                                <span class="text-xs text-gray-400 ml-auto">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-600">{{ $review->comment }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Right: stats sidebar --}}
    <div class="space-y-5">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-bold text-gray-900 mb-4">Vendeur</h3>
            <div class="flex items-center gap-3">
                <img src="{{ $product->user->avatar_url }}" alt="" class="w-10 h-10 rounded-full object-cover">
                <div>
                    <p class="font-semibold text-sm text-gray-900">{{ $product->user->name }}</p>
                    <p class="text-xs text-gray-400">{{ $product->user->email }}</p>
                </div>
            </div>
            <a href="{{ route('admin.users.show', $product->user) }}"
               class="mt-3 text-xs text-indigo-600 hover:underline block">
                Voir le profil →
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-bold text-gray-900 mb-4">Statistiques</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Commandes</span>
                    <span class="font-bold text-gray-900">{{ $product->orderItems->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Unités vendues</span>
                    <span class="font-bold text-gray-900">{{ $product->orderItems->sum('quantity') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Revenus générés</span>
                    <span class="font-bold text-indigo-700">{{ number_format($product->orderItems->sum('total'), 2) }} DT</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Note moyenne</span>
                    <span class="font-bold text-yellow-600">{{ $product->average_rating }}/5</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Publié le</span>
                    <span class="font-medium text-gray-700">{{ $product->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        {{-- Gallery --}}
        @if($product->images && count($product->images))
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-bold text-gray-900 mb-3">Galerie</h3>
            <div class="grid grid-cols-3 gap-2">
                @foreach($product->images as $img)
                    <img src="{{ asset('storage/' . $img) }}" alt=""
                         class="w-full aspect-square object-cover rounded-xl border border-gray-100">
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
