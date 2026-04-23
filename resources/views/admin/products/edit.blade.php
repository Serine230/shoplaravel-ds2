@extends('layouts.admin')
@section('title', 'Modifier : ' . $product->title)
@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-4 mb-6">
            <img src="{{ $product->image_url }}" alt="" class="w-16 h-16 object-cover rounded-xl border border-gray-100">
            <div>
                <h2 class="font-bold text-gray-900">{{ $product->title }}</h2>
                <p class="text-sm text-gray-400">Vendu par {{ $product->user->name }}</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-3 text-green-700 text-sm mb-5 flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Titre</label>
                    <input type="text" name="title" value="{{ old('title', $product->title) }}" required class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Prix (DT)</label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" step="0.01" required class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Stock</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required class="input-field">
                </div>
            </div>

            <div class="flex gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="text-indigo-600 rounded">
                    <span class="text-sm font-medium text-gray-700">Actif</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }} class="text-indigo-600 rounded">
                    <span class="text-sm font-medium text-gray-700">En vedette</span>
                </label>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nouvelle image</label>
                <input type="file" name="image" accept="image/*" class="input-field">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="{{ route('admin.products.index') }}" class="text-gray-500 hover:text-gray-700 px-4 py-2.5 font-medium text-sm">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
