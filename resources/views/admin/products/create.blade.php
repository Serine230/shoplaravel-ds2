@extends('layouts.admin')
@section('title', 'Nouveau produit')
@section('subtitle', 'Ajouter un produit au catalogue')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-5">
                <p class="font-semibold text-red-700 text-sm mb-1">Erreurs :</p>
                <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.products.store') }}" method="POST"
              enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Titre <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="input-field" placeholder="Nom du produit">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4" required
                          class="input-field resize-none"
                          placeholder="Description détaillée...">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Prix (DT) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" value="{{ old('price') }}"
                           step="0.01" min="0" required class="input-field" placeholder="0.00">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ancien prix (DT)</label>
                    <input type="number" name="old_price" value="{{ old('old_price') }}"
                           step="0.01" min="0" class="input-field" placeholder="0.00">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Stock <span class="text-red-500">*</span></label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}"
                           min="0" required class="input-field">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Catégories <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto border border-gray-200 rounded-xl p-3">
                    @foreach($categories as $cat)
                        <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 px-2 py-1 rounded-lg">
                            <input type="checkbox" name="categories[]" value="{{ $cat->id }}"
                                   {{ in_array($cat->id, old('categories', [])) ? 'checked' : '' }}
                                   class="text-indigo-600 rounded">
                            <span class="text-sm text-gray-700">{{ $cat->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Image principale</label>
                <input type="file" name="image" accept="image/*" class="input-field">
            </div>

            <div class="flex gap-5">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="text-indigo-600 rounded">
                    <span class="text-sm font-medium text-gray-700">Actif</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" class="text-indigo-600 rounded">
                    <span class="text-sm font-medium text-gray-700">En vedette</span>
                </label>
            </div>

            <div class="flex gap-3 pt-2 border-t border-gray-100">
                <button type="submit" class="btn-primary">
                    <i data-lucide="upload-cloud" class="w-4 h-4"></i> Créer le produit
                </button>
                <a href="{{ route('admin.products.index') }}"
                   class="text-gray-500 hover:text-gray-700 px-4 py-2.5 font-medium text-sm">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
