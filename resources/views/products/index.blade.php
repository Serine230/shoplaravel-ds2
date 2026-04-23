@extends('layouts.app')
@section('title', 'Catalogue')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Catalogue</h1>
            <p class="text-gray-500 mt-1">{{ $products->total() }} produit(s) trouvé(s)</p>
        </div>
        @auth
            <a href="{{ route('products.create') }}" class="btn-primary">
                <i data-lucide="plus" class="w-4 h-4"></i> Ajouter un produit
            </a>
        @endauth
    </div>

    <div class="flex gap-8">
        {{-- ═══ SIDEBAR FILTERS ═══ --}}
        <aside class="w-64 flex-shrink-0 hidden lg:block">
            <form method="GET" action="{{ route('products.index') }}" id="filter-form">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-6">

                    {{-- Search --}}
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Recherche</label>
                        <div class="relative">
                            <input type="text" name="q" value="{{ request('q') }}"
                                   placeholder="Mot-clé..."
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pl-9 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <i data-lucide="search" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
                        </div>
                    </div>

                    {{-- Categories --}}
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Catégorie</label>
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="categorie" value="" {{ !request('categorie') ? 'checked' : '' }}
                                       class="text-indigo-600" onchange="document.getElementById('filter-form').submit()">
                                <span class="text-sm text-gray-600">Toutes</span>
                            </label>
                            @foreach($categories as $cat)
                                <label class="flex items-center justify-between cursor-pointer hover:bg-gray-50 rounded-lg px-2 py-1">
                                    <div class="flex items-center gap-2">
                                        <input type="radio" name="categorie" value="{{ $cat->id }}"
                                               {{ request('categorie') == $cat->id ? 'checked' : '' }}
                                               class="text-indigo-600" onchange="document.getElementById('filter-form').submit()">
                                        <span class="text-sm text-gray-600">{{ $cat->name }}</span>
                                    </div>
                                    <span class="text-xs text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded-full">{{ $cat->products_count }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Price Range --}}
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Fourchette de prix (DT)</label>
                        <div class="flex gap-2 items-center">
                            <input type="number" name="prix_min" value="{{ request('prix_min') }}"
                                   placeholder="Min" min="0"
                                   class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <span class="text-gray-400">—</span>
                            <input type="number" name="prix_max" value="{{ request('prix_max') }}"
                                   placeholder="Max" min="0"
                                   class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <button type="submit" class="w-full mt-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold py-2 rounded-xl text-sm transition-colors">
                            Appliquer
                        </button>
                    </div>

                    {{-- Reset --}}
                    @if(request()->hasAny(['q','categorie','prix_min','prix_max','tri']))
                        <a href="{{ route('products.index') }}"
                           class="block text-center text-sm text-red-600 hover:underline">
                            ✕ Effacer les filtres
                        </a>
                    @endif
                </div>
            </form>
        </aside>

        {{-- ═══ PRODUCTS GRID ═══ --}}
        <div class="flex-1">
            {{-- Sort bar --}}
            <div class="flex items-center justify-between mb-6 bg-white rounded-2xl border border-gray-100 px-5 py-3">
                <p class="text-sm text-gray-500">
                    @if(request('q'))<strong class="text-gray-900">« {{ request('q') }} »</strong> — @endif
                    {{ $products->total() }} résultat(s)
                </p>
                <form method="GET" action="{{ route('products.index') }}">
                    @foreach(request()->except('tri') as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <select name="tri" onchange="this.form.submit()"
                            class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="newest"     {{ request('tri','newest') === 'newest'     ? 'selected' : '' }}>Plus récents</option>
                        <option value="price_asc"  {{ request('tri') === 'price_asc'  ? 'selected' : '' }}>Prix croissant</option>
                        <option value="price_desc" {{ request('tri') === 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                        <option value="popular"    {{ request('tri') === 'popular'    ? 'selected' : '' }}>Popularité</option>
                        <option value="rating"     {{ request('tri') === 'rating'     ? 'selected' : '' }}>Meilleures notes</option>
                    </select>
                </form>
            </div>

            @if($products->isEmpty())
                <div class="text-center py-20">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="search-x" class="w-10 h-10 text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">Aucun produit trouvé</h3>
                    <p class="text-gray-500 mb-6">Essayez d'autres mots-clés ou réinitialisez les filtres.</p>
                    <a href="{{ route('products.index') }}" class="btn-primary">Voir tous les produits</a>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-5">
                    @foreach($products as $product)
                        @include('components.product-card', ['product' => $product])
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- toggleWishlist() is defined globally in layouts/app.blade.php --}}
@endsection
