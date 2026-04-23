@extends('layouts.admin')
@section('title', 'Nouvelle catégorie')
@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nom</label>
                <input type="text" name="name" required class="input-field" placeholder="Ex: Électronique">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="2" class="input-field resize-none"></textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Catégorie parente</label>
                <select name="parent_id" class="input-field">
                    <option value="">— Aucune (catégorie principale) —</option>
                    @foreach($parents as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Image</label>
                <input type="file" name="image" accept="image/*" class="input-field">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Ordre d'affichage</label>
                <input type="number" name="sort_order" value="0" class="input-field w-24">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">Créer</button>
                <a href="{{ route('admin.categories.index') }}" class="text-gray-500 hover:text-gray-700 px-4 py-2.5 font-medium text-sm">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
