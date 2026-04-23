@extends('layouts.admin')
@section('title', $user->name)
@section('subtitle', 'Profil utilisateur')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left: User card --}}
    <div class="space-y-5">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 text-center">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                 class="w-24 h-24 rounded-full object-cover mx-auto border-4 border-indigo-100 shadow mb-4">
            <h2 class="text-xl font-extrabold text-gray-900">{{ $user->name }}</h2>
            <p class="text-gray-400 text-sm">{{ $user->email }}</p>
            <span class="{{ $user->isAdmin() ? 'badge-purple' : 'badge-blue' }} mt-2 inline-block">
                {{ $user->isAdmin() ? 'Administrateur' : 'Utilisateur' }}
            </span>

            @if($user->phone)
                <p class="text-sm text-gray-500 mt-3 flex items-center justify-center gap-1">
                    <i data-lucide="phone" class="w-4 h-4"></i> {{ $user->phone }}
                </p>
            @endif
            @if($user->address)
                <p class="text-sm text-gray-500 mt-1 flex items-center justify-center gap-1">
                    <i data-lucide="map-pin" class="w-4 h-4"></i> {{ $user->address }}
                </p>
            @endif
            <p class="text-xs text-gray-400 mt-4">
                Inscrit {{ $user->created_at->diffForHumans() }}
            </p>
        </div>

        {{-- Change role --}}
        @if($user->id !== auth()->id())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-bold text-gray-900 mb-3">Changer le rôle</h3>
            <form action="{{ route('admin.users.role', $user) }}" method="POST" class="space-y-3">
                @csrf @method('PUT')
                <select name="role" class="input-field">
                    <option value="user"  {{ $user->role === 'user'  ? 'selected' : '' }}>Utilisateur</option>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrateur</option>
                </select>
                <button type="submit" class="btn-primary w-full justify-center">
                    <i data-lucide="shield" class="w-4 h-4"></i> Mettre à jour
                </button>
            </form>
        </div>

        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
              onsubmit="return confirm('Supprimer définitivement cet utilisateur et toutes ses données ?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-danger w-full justify-center">
                <i data-lucide="user-x" class="w-4 h-4"></i> Supprimer l'utilisateur
            </button>
        </form>
        @endif
    </div>

    {{-- Right: activity --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Stats --}}
        <div class="grid grid-cols-3 gap-4">
            @foreach([
                ['Produits', $user->products->count(), 'package', 'indigo'],
                ['Commandes', $user->orders->count(), 'shopping-bag', 'purple'],
                ['Total dépensé', number_format($user->orders->whereIn('status', ['validee','expediee','livree'])->sum('total'), 2) . ' DT', 'banknote', 'green'],
            ] as [$label, $val, $icon, $color])
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="w-10 h-10 bg-{{ $color }}-100 rounded-xl flex items-center justify-center mb-3">
                        <i data-lucide="{{ $icon }}" class="w-5 h-5 text-{{ $color }}-600"></i>
                    </div>
                    <p class="text-xl font-extrabold text-gray-900">{{ $val }}</p>
                    <p class="text-sm text-gray-500">{{ $label }}</p>
                </div>
            @endforeach
        </div>

        {{-- Products --}}
        @if($user->products->count())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-900">Produits publiés ({{ $user->products->count() }})</h3>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($user->products->take(5) as $product)
                    <div class="flex items-center gap-4 px-6 py-3">
                        <img src="{{ $product->image_url }}" alt=""
                             class="w-10 h-10 object-cover rounded-xl border border-gray-100">
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-sm text-gray-900 truncate">{{ $product->title }}</p>
                            <p class="text-xs text-gray-400">Stock: {{ $product->stock }}</p>
                        </div>
                        <span class="font-bold text-indigo-700 text-sm">{{ number_format($product->price, 2) }} DT</span>
                        <a href="{{ route('admin.products.show', $product) }}"
                           class="text-indigo-600 hover:underline text-xs">Voir</a>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Orders --}}
        @if($user->orders->count())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-900">Commandes ({{ $user->orders->count() }})</h3>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($user->orders->sortByDesc('created_at')->take(5) as $order)
                    <div class="flex items-center justify-between px-6 py-3">
                        <div>
                            <p class="font-medium text-sm text-gray-900">
                                #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                            </p>
                            <p class="text-xs text-gray-400">{{ $order->created_at->format('d/m/Y') }}</p>
                        </div>
                        <span class="badge-{{ $order->status_color }} text-xs">{{ $order->status_label }}</span>
                        <span class="font-bold text-indigo-700 text-sm">{{ number_format($order->total, 2) }} DT</span>
                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="text-indigo-600 hover:underline text-xs">Voir</a>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
