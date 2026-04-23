@extends('layouts.app')
@section('title', $product->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-8">
        <a href="{{ route('home') }}" class="hover:text-indigo-600">Accueil</a>
        <span>/</span>
        <a href="{{ route('products.index') }}" class="hover:text-indigo-600">Catalogue</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">{{ Str::limit($product->title, 30) }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

        {{-- ═══ LEFT: IMAGES ═══ --}}
        <div x-data="{ active: '{{ $product->image_url }}' }">
            <div class="aspect-square rounded-3xl overflow-hidden bg-gray-50 border border-gray-100">
                <img :src="active" alt="{{ $product->title }}"
                     class="w-full h-full object-cover transition-all duration-300">
            </div>
            @if($product->images && count($product->images))
                <div class="flex gap-3 mt-4 overflow-x-auto pb-2">
                    <button @click="active = '{{ $product->image_url }}'"
                            class="w-20 h-20 rounded-xl overflow-hidden border-2 border-transparent hover:border-indigo-500 transition-colors flex-shrink-0">
                        <img src="{{ $product->image_url }}" alt="" class="w-full h-full object-cover">
                    </button>
                    @foreach($product->images as $img)
                        <button @click="active = '{{ asset('storage/'.$img) }}'"
                                class="w-20 h-20 rounded-xl overflow-hidden border-2 border-transparent hover:border-indigo-500 transition-colors flex-shrink-0">
                            <img src="{{ asset('storage/'.$img) }}" alt="" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ═══ RIGHT: DETAILS ═══ --}}
        <div>
            {{-- Categories --}}
            <div class="flex flex-wrap gap-2 mb-3">
                @foreach($product->categories as $cat)
                    <a href="{{ route('category.show', $cat) }}"
                       class="text-xs bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full font-medium hover:bg-indigo-100 transition-colors">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            <h1 class="text-3xl font-extrabold text-gray-900 mb-4 leading-tight">{{ $product->title }}</h1>

            {{-- Rating --}}
            <div class="flex items-center gap-3 mb-6">
                <div class="flex items-center gap-1">
                    @for($i = 1; $i <= 5; $i++)
                        <i data-lucide="star" class="w-5 h-5 {{ $i <= $product->average_rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-200 fill-gray-200' }}"></i>
                    @endfor
                </div>
                <span class="font-bold text-gray-800">{{ $product->average_rating }}/5</span>
                <span class="text-gray-400 text-sm">({{ $product->reviews->count() }} avis)</span>
                <span class="text-gray-300">•</span>
                <span class="text-gray-500 text-sm">{{ $product->views }} vues</span>
            </div>

            {{-- Price --}}
            <div class="bg-indigo-50 rounded-2xl p-5 mb-6">
                <div class="flex items-end gap-3">
                    <span class="text-4xl font-extrabold text-indigo-700">{{ number_format($product->price, 2) }} DT</span>
                    @if($product->old_price)
                        <span class="text-xl text-gray-400 line-through pb-1">{{ number_format($product->old_price, 2) }} DT</span>
                        <span class="bg-red-500 text-white text-sm font-bold px-2.5 py-1 rounded-xl pb-1">
                            -{{ $product->discount_percent }}%
                        </span>
                    @endif
                </div>
                <p class="text-sm text-gray-500 mt-1">
                    @if($product->stock > 10)
                        <span class="text-green-600 font-medium">✅ En stock ({{ $product->stock }} disponibles)</span>
                    @elseif($product->stock > 0)
                        <span class="text-orange-600 font-medium">⚠️ Plus que {{ $product->stock }} en stock !</span>
                    @else
                        <span class="text-red-600 font-medium">❌ Rupture de stock</span>
                    @endif
                </p>
            </div>

            {{-- Add to cart --}}
            @if($product->stock > 0)
                @auth
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="flex gap-3 mb-6">
                        @csrf
                        <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden">
                            <button type="button" onclick="const i=this.nextElementSibling; if(i.value>1) i.value--"
                                    class="px-4 py-3 bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold text-lg transition-colors">−</button>
                            <input type="number" name="qty" value="1" min="1" max="{{ $product->stock }}"
                                   class="w-16 text-center py-3 border-0 focus:outline-none font-semibold text-gray-900">
                            <button type="button" onclick="const i=this.previousElementSibling; if(i.value<{{ $product->stock }}) i.value++"
                                    class="px-4 py-3 bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold text-lg transition-colors">+</button>
                        </div>
                        <button type="submit" class="flex-1 btn-primary !py-3 !text-base justify-center">
                            <i data-lucide="shopping-cart" class="w-5 h-5"></i> Ajouter au panier
                        </button>
                    </form>

                    {{-- Wishlist --}}
                    <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="mb-6">
                        @csrf
                        <button type="submit" class="w-full btn-outline !py-3 justify-center">
                            <i data-lucide="heart" class="w-5 h-5 {{ auth()->user()->hasInWishlist($product) ? 'fill-red-500 text-red-500' : '' }}"></i>
                            {{ auth()->user()->hasInWishlist($product) ? 'Dans la wishlist ❤️' : 'Ajouter à la wishlist' }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-primary w-full !py-3.5 justify-center mb-6">
                        <i data-lucide="log-in" class="w-5 h-5"></i> Connectez-vous pour acheter
                    </a>
                @endauth
            @else
                <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6 flex items-center gap-3">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
                    <p class="text-red-700 font-medium">Ce produit est actuellement en rupture de stock.</p>
                </div>
            @endif

            {{-- Description --}}
            <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed mb-6">
                <h3 class="font-bold text-gray-900 text-base mb-2">Description</h3>
                <p>{{ $product->description }}</p>
            </div>

            {{-- Seller --}}
            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl">
                <img src="{{ $product->user->avatar_url }}" alt="{{ $product->user->name }}"
                     class="w-12 h-12 rounded-full object-cover">
                <div>
                    <p class="font-semibold text-gray-900 text-sm">{{ $product->user->name }}</p>
                    <p class="text-xs text-gray-500">Vendeur depuis {{ $product->user->created_at->diffForHumans() }}</p>
                </div>
                @auth
                    @if(auth()->id() !== $product->user_id)
                        <a href="{{ route('messages.show', $product->user) }}"
                           class="ml-auto text-sm text-indigo-600 font-semibold hover:underline flex items-center gap-1">
                            <i data-lucide="message-circle" class="w-4 h-4"></i> Contacter
                        </a>
                    @else
                        <a href="{{ route('products.edit', $product) }}" class="ml-auto btn-outline text-sm !px-3 !py-1.5">
                            <i data-lucide="edit" class="w-4 h-4"></i> Modifier
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    {{-- ═══ REVIEWS ═══ --}}
    <div class="mt-16 grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Reviews list --}}
        <div class="lg:col-span-2">
            <h2 class="text-2xl font-extrabold text-gray-900 mb-6">
                Avis clients
                <span class="text-gray-400 font-normal text-lg">({{ $product->reviews->count() }})</span>
            </h2>

            @forelse($product->reviews->where('is_approved', true) as $review)
                <div class="bg-white rounded-2xl border border-gray-100 p-5 mb-4 shadow-sm">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $review->user->avatar_url }}" alt="" class="w-9 h-9 rounded-full object-cover">
                            <div>
                                <p class="font-semibold text-sm text-gray-900">{{ $review->user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <i data-lucide="star" class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-200 fill-gray-200' }}"></i>
                            @endfor
                        </div>
                    </div>
                    @if($review->title)
                        <p class="font-semibold text-gray-800 mb-1">{{ $review->title }}</p>
                    @endif
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $review->comment }}</p>

                    @auth
                        @if(auth()->id() === $review->user_id || auth()->user()->isAdmin())
                            <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="mt-3">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:text-red-600 transition-colors">Supprimer</button>
                            </form>
                        @endif
                    @endauth
                </div>
            @empty
                <div class="text-center py-10 bg-gray-50 rounded-2xl">
                    <i data-lucide="message-square" class="w-10 h-10 text-gray-300 mx-auto mb-3"></i>
                    <p class="text-gray-500">Pas encore d'avis. Soyez le premier !</p>
                </div>
            @endforelse
        </div>

        {{-- Leave a review --}}
        <div>
            @auth
                @if(!$userReview)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-24">
                        <h3 class="font-bold text-gray-900 text-lg mb-5">Laisser un avis</h3>
                        <form action="{{ route('reviews.store', $product) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="text-sm font-semibold text-gray-700 block mb-2">Note</label>
                                <div x-data="{ rating: 0 }" class="flex gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <button type="button"
                                                @click="rating = {{ $i }}"
                                                @mouseenter="rating = {{ $i }}"
                                                class="text-2xl focus:outline-none transition-transform hover:scale-110">
                                            <i data-lucide="star" class="w-7 h-7"
                                               :class="rating >= {{ $i }} ? 'text-yellow-400 fill-yellow-400' : 'text-gray-200 fill-gray-200'"></i>
                                        </button>
                                        <input type="radio" name="rating" value="{{ $i }}" class="hidden"
                                               x-bind:checked="rating === {{ $i }}">
                                    @endfor
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-700 block mb-2">Titre (optionnel)</label>
                                <input type="text" name="title" class="input-field" placeholder="Résumé de votre avis">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-700 block mb-2">Commentaire</label>
                                <textarea name="comment" rows="4" required class="input-field resize-none"
                                          placeholder="Décrivez votre expérience..."></textarea>
                            </div>
                            <button type="submit" class="btn-primary w-full justify-center">
                                <i data-lucide="send" class="w-4 h-4"></i> Publier l'avis
                            </button>
                        </form>
                    </div>
                @else
                    <div class="bg-green-50 rounded-2xl border border-green-200 p-6">
                        <i data-lucide="check-circle" class="w-8 h-8 text-green-500 mb-2"></i>
                        <h3 class="font-bold text-green-800 mb-1">Vous avez déjà donné votre avis !</h3>
                        <p class="text-green-700 text-sm">Merci pour votre retour sur ce produit.</p>
                    </div>
                @endif
            @else
                <div class="bg-indigo-50 rounded-2xl border border-indigo-200 p-6 text-center">
                    <i data-lucide="star" class="w-8 h-8 text-indigo-500 mx-auto mb-3"></i>
                    <h3 class="font-bold text-indigo-800 mb-2">Vous avez acheté ce produit ?</h3>
                    <a href="{{ route('login') }}" class="btn-primary mt-2 w-full justify-center">
                        Connectez-vous pour laisser un avis
                    </a>
                </div>
            @endauth
        </div>
    </div>

    {{-- ═══ RELATED PRODUCTS ═══ --}}
    @if($related->count())
        <div class="mt-16">
            <h2 class="text-2xl font-extrabold text-gray-900 mb-6">Produits similaires</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                @foreach($related as $rProduct)
                    @include('components.product-card', ['product' => $rProduct])
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
