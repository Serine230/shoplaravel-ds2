@extends('layouts.app')
@section('title', 'Mes Produits')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Mes Produits</h1>
            <p class="text-gray-500 mt-1">{{ $products->total() }} produit(s) publié(s)</p>
        </div>
        <a href="{{ route('products.create') }}" class="btn-primary">
            <i data-lucide="plus" class="w-4 h-4"></i> Ajouter un produit
        </a>
    </div>

    @if($products->isEmpty())
        <div class="text-center py-24 bg-white rounded-3xl border border-gray-100">
            <i data-lucide="package" class="w-16 h-16 text-gray-200 mx-auto mb-4"></i>
            <h2 class="text-xl font-bold text-gray-600 mb-2">Aucun produit publié</h2>
            <p class="text-gray-400 mb-6">Commencez à vendre dès maintenant !</p>
            <a href="{{ route('products.create') }}" class="btn-primary">Publier mon premier produit</a>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Produit</th>
                        <th class="px-6 py-3">Catégories</th>
                        <th class="px-6 py-3">Prix</th>
                        <th class="px-6 py-3">Stock</th>
                        <th class="px-6 py-3">Statut</th>
                        <th class="px-6 py-3">Vues</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($products as $product)
                        <tr class="hover:bg-indigo-50/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $product->image_url }}" alt=""
                                         class="w-12 h-12 object-cover rounded-xl border border-gray-100 flex-shrink-0">
                                    <div>
                                        <p class="font-semibold text-gray-900 max-w-[200px] truncate text-sm">{{ $product->title }}</p>
                                        <p class="text-xs text-gray-400">{{ $product->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($product->categories->take(2) as $cat)
                                        <span class="text-xs bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-full">{{ $cat->name }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-indigo-700">{{ number_format($product->price, 2) }} DT</span>
                                @if($product->old_price)
                                    <span class="block text-xs text-gray-400 line-through">{{ number_format($product->old_price, 2) }} DT</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-sm {{ $product->stock < 5 ? 'text-red-600' : 'text-gray-700' }}">
                                    {{ $product->stock }}
                                    @if($product->stock < 5 && $product->stock > 0)
                                        <span class="text-xs font-normal text-red-400 block">⚠ Faible</span>
                                    @elseif($product->stock === 0)
                                        <span class="text-xs font-normal text-red-500 block">Rupture</span>
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="{{ $product->is_active ? 'badge-green' : 'badge-red' }} text-xs">
                                    {{ $product->is_active ? '✓ Actif' : '✗ Inactif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <span class="flex items-center gap-1">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    {{ number_format($product->views) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('products.show', $product) }}"
                                       class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                       title="Voir">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}"
                                       class="p-1.5 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                                       title="Modifier">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST"
                                          onsubmit="return confirm('Supprimer ce produit définitivement ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Supprimer">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-100">{{ $products->links() }}</div>
        </div>
    @endif
</div>
@endsection
