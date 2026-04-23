@extends('layouts.admin')
@section('title', 'Modifier : ' . $category->name)
@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nom</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="input-field">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="2" class="input-field resize-none">{{ old('description', $category->description) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Catégorie parente</label>
                <select name="parent_id" class="input-field">
                    <option value="">— Aucune —</option>
                    @foreach($parents as $p)
                        <option value="{{ $p->id }}" {{ $category->parent_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Image</label>
                @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" alt="" class="h-16 rounded-xl mb-2">
                @endif
                <input type="file" name="image" accept="image/*" class="input-field">
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }} class="text-indigo-600 rounded" id="cat_active">
                <label for="cat_active" class="text-sm font-medium text-gray-700">Catégorie active</label>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="{{ route('admin.categories.index') }}" class="text-gray-500 hover:text-gray-700 px-4 py-2.5 font-medium text-sm">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
