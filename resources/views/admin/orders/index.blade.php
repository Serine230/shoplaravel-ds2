@extends('layouts.admin')
@section('title', 'Commandes')
@section('content')
<div class="mb-4">
    <form method="GET" class="flex gap-3">
        <select name="status" class="input-field max-w-xs" onchange="this.form.submit()">
            <option value="">Tous les statuts</option>
            @foreach(['en_attente'=>'En attente','validee'=>'Validée','expediee'=>'Expédiée','livree'=>'Livrée','annulee'=>'Annulée'] as $v=>$l)
                <option value="{{ $v }}" {{ request('status') === $v ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
        </select>
    </form>
</div>
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full admin-table">
        <thead><tr><th>#</th><th>Client</th><th>Total</th><th>Statut</th><th>Paiement</th><th>Date</th><th></th></tr></thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td class="font-mono font-medium">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                <td>
                    <div class="flex items-center gap-2">
                        <img src="{{ $order->user->avatar_url }}" alt="" class="w-7 h-7 rounded-full">
                        <div>
                            <p class="font-medium text-sm">{{ $order->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $order->shipping_city }}</p>
                        </div>
                    </div>
                </td>
                <td class="font-bold text-indigo-700">{{ number_format($order->total, 2) }} DT</td>
                <td><span class="badge-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
                <td class="capitalize text-gray-500 text-sm">{{ $order->payment_method }}</td>
                <td class="text-gray-400 text-sm">{{ $order->created_at->format('d/m/Y') }}</td>
                <td><a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:underline text-sm font-medium">Détails</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">{{ $orders->links() }}</div>
</div>
@endsection
