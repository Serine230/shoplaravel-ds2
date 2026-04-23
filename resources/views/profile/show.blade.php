@extends('layouts.app')
@section('title', 'Mon Profil')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Mon Profil</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ═══ LEFT: Avatar + Stats ═══ --}}
        <div class="space-y-5">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 text-center">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                     class="w-24 h-24 rounded-full object-cover mx-auto border-4 border-indigo-100 shadow-md mb-4">
                <h2 class="text-xl font-extrabold text-gray-900">{{ $user->name }}</h2>
                <p class="text-gray-400 text-sm">{{ $user->email }}</p>
                @if($user->isAdmin())
                    <span class="badge-purple mt-2 inline-block">Administrateur</span>
                @endif
                <p class="text-xs text-gray-400 mt-3">
                    Membre depuis {{ $user->created_at->diffForHumans() }}
                </p>
                <a href="{{ route('profile.edit') }}" class="btn-primary w-full justify-center mt-5 !py-2.5">
                    <i data-lucide="edit" class="w-4 h-4"></i> Modifier le profil
                </a>
            </div>

            {{-- Stats --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="font-bold text-gray-900 mb-4">Mes statistiques</h3>
                <div class="space-y-3">
                    @foreach([
                        ['Produits publiés', $user->products->count(), 'package', 'indigo'],
                        ['Commandes passées', $user->orders->count(), 'shopping-bag', 'purple'],
                        ['Avis rédigés', $user->reviews->count(), 'star', 'yellow'],
                    ] as [$label, $count, $icon, $color])
                        <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <div class="w-7 h-7 bg-{{ $color }}-100 rounded-lg flex items-center justify-center">
                                    <i data-lucide="{{ $icon }}" class="w-3.5 h-3.5 text-{{ $color }}-600"></i>
                                </div>
                                {{ $label }}
                            </div>
                            <span class="font-bold text-gray-900 text-sm">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Links --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-2">
                <a href="{{ route('products.mine') }}" class="flex items-center gap-3 text-sm text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 px-3 py-2 rounded-xl transition-colors">
                    <i data-lucide="package" class="w-4 h-4"></i> Mes produits
                </a>
                <a href="{{ route('orders.index') }}" class="flex items-center gap-3 text-sm text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 px-3 py-2 rounded-xl transition-colors">
                    <i data-lucide="shopping-bag" class="w-4 h-4"></i> Mes commandes
                </a>
                <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 text-sm text-gray-700 hover:text-red-500 hover:bg-red-50 px-3 py-2 rounded-xl transition-colors">
                    <i data-lucide="heart" class="w-4 h-4"></i> Ma wishlist
                </a>
                <a href="{{ route('messages.index') }}" class="flex items-center gap-3 text-sm text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 px-3 py-2 rounded-xl transition-colors">
                    <i data-lucide="message-circle" class="w-4 h-4"></i> Mes messages
                </a>
            </div>
        </div>

        {{-- ═══ RIGHT: Recent Orders ═══ --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Address info --}}
            @if($user->phone || $user->address)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="font-bold text-gray-900 mb-4">Informations de contact</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    @if($user->phone)
                        <div class="flex items-center gap-2 text-gray-600">
                            <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
                            {{ $user->phone }}
                        </div>
                    @endif
                    @if($user->address)
                        <div class="flex items-start gap-2 text-gray-600">
                            <i data-lucide="map-pin" class="w-4 h-4 text-gray-400 mt-0.5"></i>
                            {{ $user->address }}
                        </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Recent orders --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-bold text-gray-900">Commandes récentes</h3>
                    <a href="{{ route('orders.index') }}" class="text-sm text-indigo-600 hover:underline">Tout voir →</a>
                </div>

                @if($orders->isEmpty())
                    <div class="text-center py-10 text-gray-400">
                        <i data-lucide="shopping-bag" class="w-10 h-10 mx-auto mb-2 text-gray-200"></i>
                        <p class="text-sm">Aucune commande pour l'instant.</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($orders as $order)
                            <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50/50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                                        <i data-lucide="shopping-bag" class="w-5 h-5 text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">
                                            #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                                        </p>
                                        <p class="text-xs text-gray-400">{{ $order->created_at->format('d/m/Y') }} · {{ $order->items->count() }} article(s)</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="badge-{{ $order->status_color }} text-xs">{{ $order->status_label }}</span>
                                    <span class="font-bold text-indigo-700 text-sm">{{ number_format($order->total, 2) }} DT</span>
                                    <a href="{{ route('orders.show', $order) }}" class="text-xs text-gray-400 hover:text-indigo-600">
                                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
