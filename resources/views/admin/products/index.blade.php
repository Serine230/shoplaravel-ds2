@extends('layouts.admin')
@section('title', 'Gestion des produits')
@section('content')
<div class="flex justify-between items-center mb-6">
    <div></div>
    <a href="{{ route('admin.products.create') }}" class="btn-primary">
        <i data-lucide="plus" class="w-4 h-4"></i> Nouveau produit
    </a>
</div>
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full admin-table">
            <thead><tr><th>Produit</th><th>Vendeur</th><th>Prix</th><th>Stock</th><th>Catégories</th><th>Statut</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($products as $p)
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <img src="{{ $p->image_url }}" alt="" class="w-10 h-10 object-cover rounded-xl border border-gray-100">
                            <span class="font-medium max-w-[200px] truncate">{{ $p->title }}</span>
                        </div>
                    </td>
                    <td class="text-gray-500">{{ $p->user->name }}</td>
                    <td class="font-bold text-indigo-700">{{ number_format($p->price, 2) }} DT</td>
                    <td>
                        <span class="{{ $p->stock < 5 ? 'text-red-600 font-bold' : 'text-gray-700' }}">{{ $p->stock }}</span>
                    </td>
                    <td>
                        <div class="flex flex-wrap gap-1">
                            @foreach($p->categories->take(2) as $c)
                                <span class="badge-blue text-xs">{{ $c->name }}</span>
                            @endforeach
                        </div>
                    </td>
                    <td>
                        <span class="{{ $p->is_active ? 'badge-green' : 'badge-red' }}">
                            {{ $p->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.products.edit', $p) }}" class="text-indigo-600 hover:underline text-sm font-medium">Modifier</a>
                            <form action="{{ route('admin.products.destroy', $p) }}" method="POST" onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 text-sm">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100">{{ $products->links() }}</div>
</div>
@endsection
