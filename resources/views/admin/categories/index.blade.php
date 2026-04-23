@extends('layouts.admin')
@section('title', 'Catégories')
@section('content')
<div class="flex justify-end mb-4">
    <a href="{{ route('admin.categories.create') }}" class="btn-primary">
        <i data-lucide="plus" class="w-4 h-4"></i> Nouvelle catégorie
    </a>
</div>
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full admin-table">
        <thead><tr><th>Nom</th><th>Parent</th><th>Produits</th><th>Statut</th><th>Ordre</th><th>Actions</th></tr></thead>
        <tbody>
            @foreach($categories as $cat)
            <tr>
                <td class="font-semibold">{{ $cat->name }}</td>
                <td class="text-gray-500 text-sm">{{ $cat->parent?->name ?? '—' }}</td>
                <td>{{ $cat->products_count }}</td>
                <td><span class="{{ $cat->is_active ? 'badge-green' : 'badge-red' }}">{{ $cat->is_active ? 'Active' : 'Inactive' }}</span></td>
                <td class="text-gray-400">{{ $cat->sort_order }}</td>
                <td>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.categories.edit', $cat) }}" class="text-indigo-600 hover:underline text-sm">Modifier</a>
                        <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Supprimer ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600 text-sm">Suppr.</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
