@extends('layouts.app')
@section('title', 'Modifier : ' . $product->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900">Modifier le produit</h1>
        <p class="text-gray-500 mt-1">{{ $product->title }}</p>
    </div>

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf @method('PUT')
        @include('products._form')

        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
            <button type="submit" class="btn-primary !px-8 !py-3.5 text-base">
                <i data-lucide="save" class="w-5 h-5"></i> Enregistrer les modifications
            </button>
            <a href="{{ route('products.show', $product) }}" class="text-gray-500 hover:text-gray-700 font-medium">Annuler</a>

            <form action="{{ route('products.destroy', $product) }}" method="POST" class="ml-auto"
                  onsubmit="return confirm('Supprimer ce produit ?')">
                @csrf @method('DELETE')
                <button type="submit" class="flex items-center gap-2 text-red-600 hover:bg-red-50 px-4 py-2.5 rounded-xl transition-colors text-sm font-semibold">
                    <i data-lucide="trash-2" class="w-4 h-4"></i> Supprimer
                </button>
            </form>
        </div>
    </form>
</div>
@endsection
