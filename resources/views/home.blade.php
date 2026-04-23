@extends('layouts.app')
@section('title', 'Accueil')

@section('content')

{{-- ═══════ HERO ═══════ --}}
<section class="relative bg-gradient-to-br from-indigo-900 via-indigo-800 to-purple-900 text-white overflow-hidden">
    {{-- Background shapes --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 relative">
        <div class="max-w-2xl">
            <div class="inline-flex items-center gap-2 bg-white/10 text-indigo-200 px-4 py-2 rounded-full text-sm font-medium mb-6 border border-white/10 backdrop-blur-sm">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                Livraison gratuite dès 100 DT
            </div>
            <h1 class="text-5xl md:text-6xl font-extrabold leading-tight mb-6">
                Découvrez<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-300 to-purple-300">
                    des milliers<br>de produits
                </span>
            </h1>
            <p class="text-xl text-indigo-200 mb-10 leading-relaxed">
                La marketplace tunisienne de confiance. Achetez, vendez, et échangez en toute sécurité.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('products.index') }}"
                   class="bg-white text-indigo-700 font-bold px-8 py-4 rounded-2xl hover:bg-indigo-50 transition-all duration-200 flex items-center justify-center gap-2 shadow-lg">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i> Explorer le catalogue
                </a>
                @guest
                    <a href="{{ route('register') }}"
                       class="border-2 border-white/30 text-white font-bold px-8 py-4 rounded-2xl hover:bg-white/10 transition-all duration-200 flex items-center justify-center gap-2 backdrop-blur-sm">
                        Commencer à vendre →
                    </a>
                @endguest
            </div>

            {{-- Stats --}}
            <div class="flex gap-10 mt-14">
                <div>
                    <p class="text-3xl font-extrabold text-white">50+</p>
                    <p class="text-indigo-300 text-sm">Produits</p>
                </div>
                <div class="w-px bg-white/20"></div>
                <div>
                    <p class="text-3xl font-extrabold text-white">100%</p>
                    <p class="text-indigo-300 text-sm">Sécurisé</p>
                </div>
                <div class="w-px bg-white/20"></div>
                <div>
                    <p class="text-3xl font-extrabold text-white">24h</p>
                    <p class="text-indigo-300 text-sm">Livraison</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════ CATEGORIES ═══════ --}}
@if($categories->count())
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900">Catégories populaires</h2>
            <p class="text-gray-500 text-sm mt-1">Parcourez par catégorie</p>
        </div>
        <a href="{{ route('products.index') }}" class="text-indigo-600 font-semibold text-sm hover:underline flex items-center gap-1">
            Tout voir <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-4">
        @foreach($categories as $cat)
            <a href="{{ route('category.show', $cat) }}"
               class="flex flex-col items-center gap-3 p-4 bg-white rounded-2xl border border-gray-100 hover:border-indigo-300 hover:shadow-md hover:-translate-y-1 transition-all duration-200 group">
                <div class="w-12 h-12 bg-indigo-50 group-hover:bg-indigo-100 rounded-2xl flex items-center justify-center transition-colors">
                    <i data-lucide="tag" class="w-6 h-6 text-indigo-600"></i>
                </div>
                <span class="text-xs font-semibold text-gray-700 text-center leading-tight">{{ $cat->name }}</span>
                <span class="text-xs text-gray-400">{{ $cat->products_count }} produits</span>
            </a>
        @endforeach
    </div>
</section>
@endif

{{-- ═══════ FEATURED PRODUCTS ═══════ --}}
@if($featured->count())
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900">Produits en vedette</h2>
            <p class="text-gray-500 text-sm mt-1">Sélection exclusive</p>
        </div>
        <a href="{{ route('products.index') }}" class="text-indigo-600 font-semibold text-sm hover:underline flex items-center gap-1">
            Tout voir <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($featured as $product)
            @include('components.product-card', ['product' => $product])
        @endforeach
    </div>
</section>
@endif

{{-- ═══════ NEW ARRIVALS ═══════ --}}
@if($newest->count())
<section class="bg-gray-100/50">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900">Nouveautés</h2>
            <p class="text-gray-500 text-sm mt-1">Les derniers ajouts</p>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($newest->take(8) as $product)
            @include('components.product-card', ['product' => $product])
        @endforeach
    </div>

    <div class="text-center mt-10">
        <a href="{{ route('products.index') }}" class="btn-primary text-base !px-8 !py-3.5">
            Voir tous les produits <i data-lucide="arrow-right" class="w-5 h-5"></i>
        </a>
    </div>
</div>
</section>
@endif

{{-- ═══════ WHY US ═══════ --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach([
            ['icon' => 'shield-check', 'title' => 'Paiements sécurisés', 'desc' => 'Toutes vos transactions sont protégées avec les meilleures technologies de chiffrement.'],
            ['icon' => 'truck', 'title' => 'Livraison rapide', 'desc' => 'Livraison en 24–48h partout en Tunisie. Gratuite dès 100 DT d\'achat.'],
            ['icon' => 'refresh-cw', 'title' => 'Retours faciles', 'desc' => '30 jours pour changer d\'avis. Retours gratuits et sans questions.'],
        ] as $feat)
            <div class="flex gap-5 p-6 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <i data-lucide="{{ $feat['icon'] }}" class="w-6 h-6 text-indigo-600"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 mb-1">{{ $feat['title'] }}</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">{{ $feat['desc'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>

@endsection
