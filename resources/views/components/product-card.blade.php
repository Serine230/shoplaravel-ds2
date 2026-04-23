<div class="product-card bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl hover:border-indigo-200 group">
    <div class="relative overflow-hidden aspect-square bg-gray-50">
        <a href="{{ route('products.show', $product) }}">
            <img src="{{ $product->image_url }}" alt="{{ $product->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                 loading="lazy">
        </a>

        {{-- Badges --}}
        <div class="absolute top-3 left-3 flex flex-col gap-1">
            @if($product->is_featured)
                <span class="bg-indigo-600 text-white text-xs font-bold px-2 py-1 rounded-lg">⭐ Vedette</span>
            @endif
            @if($product->discount_percent)
                <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-lg">-{{ $product->discount_percent }}%</span>
            @endif
            @if($product->stock < 5 && $product->stock > 0)
                <span class="bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded-lg">Plus que {{ $product->stock }}</span>
            @endif
        </div>

        {{-- Wishlist --}}
        @auth
        <button onclick="toggleWishlist({{ $product->id }}, this)"
                data-in-wishlist="{{ auth()->user()->hasInWishlist($product) ? 'true' : 'false' }}"
                class="absolute top-3 right-3 p-2 bg-white rounded-xl shadow-sm hover:bg-red-50 transition-colors">
            <i data-lucide="heart" class="w-4 h-4 {{ auth()->user()->hasInWishlist($product) ? 'text-red-500 fill-red-500' : 'text-gray-400' }}"></i>
        </button>
        @endauth

        {{-- Quick add --}}
        @if($product->stock > 0)
        <div class="absolute bottom-0 left-0 right-0 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
            <form action="{{ route('cart.add', $product) }}" method="POST">
                @csrf
                <button type="submit"
                        class="w-full bg-indigo-600 text-white font-semibold py-3 text-sm hover:bg-indigo-700 transition-colors flex items-center justify-center gap-2">
                    <i data-lucide="shopping-cart" class="w-4 h-4"></i> Ajouter au panier
                </button>
            </form>
        </div>
        @endif
    </div>

    <div class="p-4">
        {{-- Categories --}}
        @if($product->categories->count())
            <div class="flex flex-wrap gap-1 mb-2">
                @foreach($product->categories->take(2) as $cat)
                    <span class="text-xs text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">{{ $cat->name }}</span>
                @endforeach
            </div>
        @endif

        <a href="{{ route('products.show', $product) }}" class="block">
            <h3 class="font-semibold text-gray-900 text-sm leading-tight hover:text-indigo-600 transition-colors line-clamp-2 mb-2">
                {{ $product->title }}
            </h3>
        </a>

        {{-- Rating --}}
        <div class="flex items-center gap-1 mb-3">
            @php $rating = $product->average_rating; @endphp
            @for($i = 1; $i <= 5; $i++)
                <i data-lucide="star" class="w-3 h-3 {{ $i <= $rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-200 fill-gray-200' }}"></i>
            @endfor
            <span class="text-xs text-gray-400 ml-1">({{ $product->reviews->count() }})</span>
        </div>

        {{-- Price --}}
        <div class="flex items-center justify-between">
            <div>
                <span class="text-lg font-extrabold text-gray-900">{{ number_format($product->price, 2) }} DT</span>
                @if($product->old_price)
                    <span class="text-xs text-gray-400 line-through ml-1">{{ number_format($product->old_price, 2) }} DT</span>
                @endif
            </div>
            @if($product->stock === 0)
                <span class="text-xs text-red-500 font-medium">Rupture</span>
            @endif
        </div>
    </div>
</div>
