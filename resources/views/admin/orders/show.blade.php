@extends('layouts.admin')
@section('title', 'Commande #' . str_pad($order->id, 6, '0', STR_PAD_LEFT))
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-900">Articles</h2>
                <span class="badge-{{ $order->status_color }}">{{ $order->status_label }}</span>
            </div>
            @foreach($order->items as $item)
            <div class="flex items-center gap-4 px-6 py-4 border-b border-gray-50 last:border-0">
                <img src="{{ $item->product->image_url }}" alt="" class="w-14 h-14 object-cover rounded-xl border border-gray-100">
                <div class="flex-1">
                    <p class="font-semibold text-gray-900">{{ $item->product->title }}</p>
                    <p class="text-sm text-gray-400">{{ number_format($item->price, 2) }} DT × {{ $item->quantity }}</p>
                </div>
                <p class="font-bold">{{ number_format($item->total, 2) }} DT</p>
            </div>
            @endforeach
            <div class="px-6 py-4 bg-gray-50 flex justify-between font-bold text-lg">
                <span>Total</span>
                <span class="text-indigo-700">{{ number_format($order->total, 2) }} DT</span>
            </div>
        </div>
    </div>
    <div class="space-y-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-bold text-gray-900 mb-4">Changer le statut</h3>
            <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="space-y-3">
                @csrf @method('PUT')
                <select name="status" class="input-field">
                    @foreach(['en_attente'=>'En attente','validee'=>'Validée','expediee'=>'Expédiée','livree'=>'Livrée','annulee'=>'Annulée'] as $v=>$l)
                        <option value="{{ $v }}" {{ $order->status === $v ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-primary w-full justify-center">Mettre à jour</button>
            </form>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-bold text-gray-900 mb-3">Client</h3>
            <div class="flex items-center gap-3 mb-3">
                <img src="{{ $order->user->avatar_url }}" alt="" class="w-10 h-10 rounded-full">
                <div>
                    <p class="font-semibold text-sm">{{ $order->user->name }}</p>
                    <p class="text-xs text-gray-400">{{ $order->user->email }}</p>
                </div>
            </div>
            <div class="text-sm text-gray-600 space-y-1">
                <p><strong>Adresse:</strong> {{ $order->shipping_address }}</p>
                <p><strong>Ville:</strong> {{ $order->shipping_city }}</p>
                <p><strong>Tél:</strong> {{ $order->shipping_phone }}</p>
                <p><strong>Paiement:</strong> {{ $order->payment_method }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
