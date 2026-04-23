@extends('layouts.app')
@section('title', 'Mes Commandes')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Mes Commandes</h1>

    @if($orders->isEmpty())
        <div class="text-center py-24 bg-white rounded-3xl border border-gray-100">
            <i data-lucide="shopping-bag" class="w-16 h-16 text-gray-200 mx-auto mb-4"></i>
            <h2 class="text-xl font-bold text-gray-600 mb-2">Aucune commande pour l'instant</h2>
            <a href="{{ route('products.index') }}" class="btn-primary mt-4">Commencer à acheter</a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                {{-- Order header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <div class="flex items-center gap-6">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Commande</p>
                            <p class="font-bold text-gray-900">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Date</p>
                            <p class="font-medium text-gray-700 text-sm">{{ $order->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Total</p>
                            <p class="font-bold text-indigo-700">{{ number_format($order->total, 2) }} DT</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="badge-{{ $order->status_color }} text-xs">{{ $order->status_label }}</span>
                        <a href="{{ route('orders.show', $order) }}"
                           class="text-sm text-indigo-600 font-semibold hover:underline">
                            Voir →
                        </a>
                    </div>
                </div>

                {{-- Order items preview --}}
                <div class="px-6 py-4 flex items-center gap-4 overflow-x-auto">
                    @foreach($order->items->take(4) as $item)
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <img src="{{ $item->product->image_url }}" alt=""
                                 class="w-12 h-12 object-cover rounded-xl border border-gray-100">
                            <div class="text-sm">
                                <p class="font-medium text-gray-800 line-clamp-1 max-w-[120px]">{{ $item->product->title }}</p>
                                <p class="text-gray-400 text-xs">×{{ $item->quantity }}</p>
                            </div>
                        </div>
                    @endforeach
                    @if($order->items->count() > 4)
                        <span class="text-sm text-gray-400 flex-shrink-0">+{{ $order->items->count() - 4 }} autre(s)</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
