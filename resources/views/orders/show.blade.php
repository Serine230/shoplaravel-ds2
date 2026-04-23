@extends('layouts.app')
@section('title', 'Commande #' . str_pad($order->id, 6, '0', STR_PAD_LEFT))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">
                Commande #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
            </h1>
            <p class="text-gray-500 mt-1">Passée le {{ $order->created_at->format('d/m/Y à H:i') }}</p>
        </div>
        <span class="badge-{{ $order->status_color }} text-sm !px-4 !py-2">{{ $order->status_label }}</span>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-2xl p-5 mb-6 flex items-center gap-4">
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
            <i data-lucide="check-circle" class="w-7 h-7 text-green-500"></i>
        </div>
        <div>
            <p class="font-bold text-green-800">🎉 {{ session('success') }}</p>
            <p class="text-sm text-green-700 mt-0.5">Vous recevrez une confirmation par email.</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Products --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="font-bold text-gray-900">Articles commandés</h2>
                </div>
                @foreach($order->items as $item)
                    <div class="flex items-center gap-4 px-6 py-4 border-b border-gray-50 last:border-0">
                        <img src="{{ $item->product->image_url }}" alt=""
                             class="w-16 h-16 object-cover rounded-xl border border-gray-100">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">{{ $item->product->title }}</p>
                            <p class="text-sm text-gray-400">{{ number_format($item->price, 2) }} DT × {{ $item->quantity }}</p>
                        </div>
                        <p class="font-bold text-gray-900">{{ number_format($item->total, 2) }} DT</p>
                    </div>
                @endforeach

                {{-- Totals --}}
                <div class="px-6 py-4 bg-gray-50/50 space-y-2">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Sous-total</span><span>{{ number_format($order->subtotal, 2) }} DT</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Livraison</span>
                        <span>{{ $order->shipping == 0 ? 'Gratuite' : number_format($order->shipping, 2) . ' DT' }}</span>
                    </div>
                    <div class="flex justify-between font-extrabold text-gray-900 text-lg pt-2 border-t border-gray-200">
                        <span>Total</span><span class="text-indigo-700">{{ number_format($order->total, 2) }} DT</span>
                    </div>
                </div>
            </div>

            {{-- Cancel button --}}
            @if($order->canBeCancelled())
                <form action="{{ route('orders.cancel', $order) }}" method="POST"
                      onsubmit="return confirm('Confirmer l\'annulation de cette commande ?')">
                    @csrf @method('PUT')
                    <button type="submit" class="flex items-center gap-2 text-red-600 hover:bg-red-50 px-4 py-2.5 rounded-xl border border-red-200 transition-colors text-sm font-semibold">
                        <i data-lucide="x-circle" class="w-4 h-4"></i> Annuler la commande
                    </button>
                </form>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="font-bold text-gray-900 mb-4">Livraison</h3>
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex items-start gap-2">
                        <i data-lucide="map-pin" class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0"></i>
                        <p>{{ $order->shipping_address }}, {{ $order->shipping_city }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="phone" class="w-4 h-4 text-gray-400 flex-shrink-0"></i>
                        <p>{{ $order->shipping_phone }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="font-bold text-gray-900 mb-4">Paiement</h3>
                <p class="text-sm text-gray-600 flex items-center gap-2">
                    <i data-lucide="credit-card" class="w-4 h-4 text-gray-400"></i>
                    {{ ucfirst($order->payment_method) }}
                </p>
            </div>

            @if($order->notes)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="font-bold text-gray-900 mb-2">Notes</h3>
                <p class="text-sm text-gray-600">{{ $order->notes }}</p>
            </div>
            @endif

            {{-- Status timeline --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="font-bold text-gray-900 mb-4">Suivi</h3>
                @php
                    $statuses = ['en_attente','validee','expediee','livree'];
                    $current = array_search($order->status, $statuses);
                @endphp
                <div class="space-y-3">
                    @foreach([
                        ['en_attente','En attente','clock'],
                        ['validee','Validée','check'],
                        ['expediee','Expédiée','truck'],
                        ['livree','Livrée','package-check'],
                    ] as [$val, $label, $icon])
                        @php $idx = array_search($val, $statuses); @endphp
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0
                                {{ $order->status === $val ? 'bg-indigo-600 text-white' : ($idx < $current ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-400') }}">
                                <i data-lucide="{{ $icon }}" class="w-3.5 h-3.5"></i>
                            </div>
                            <span class="text-sm {{ $order->status === $val ? 'font-bold text-indigo-700' : ($idx < $current ? 'text-green-700' : 'text-gray-400') }}">
                                {{ $label }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
