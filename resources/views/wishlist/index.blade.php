@extends('layouts.app')
@section('title', 'Ma Wishlist')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-8 flex items-center gap-3">
        <i data-lucide="heart" class="w-8 h-8 text-red-500"></i>
        Ma Wishlist
        @if($products->total() > 0)
            <span class="text-lg font-normal text-gray-400">({{ $products->total() }} article(s))</span>
        @endif
    </h1>

    @if($products->isEmpty())
        <div class="text-center py-24 bg-white rounded-3xl border border-gray-100">
            <i data-lucide="heart" class="w-16 h-16 text-gray-200 mx-auto mb-4"></i>
            <h2 class="text-xl font-bold text-gray-600 mb-2">Votre wishlist est vide</h2>
            <p class="text-gray-400 mb-6">Ajoutez des produits en cliquant sur ❤️</p>
            <a href="{{ route('products.index') }}" class="btn-primary">Explorer le catalogue</a>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>
        <div class="mt-8">{{ $products->links() }}</div>
    @endif
</div>
@endsection
