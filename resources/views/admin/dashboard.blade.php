@extends('layouts.admin')
@section('title', 'Tableau de bord')
@section('subtitle', 'Vue d\'ensemble de votre boutique')

@section('content')

{{-- ═══ STAT CARDS ═══ --}}
<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
    @foreach([
        ['label' => 'Utilisateurs',   'value' => number_format($stats['total_users']),    'icon' => 'users',          'color' => 'indigo'],
        ['label' => 'Produits',       'value' => number_format($stats['total_products']), 'icon' => 'package',        'color' => 'blue'],
        ['label' => 'Commandes',      'value' => number_format($stats['total_orders']),   'icon' => 'shopping-bag',   'color' => 'purple'],
        ['label' => 'Revenus (DT)',   'value' => number_format($stats['total_revenue'],2), 'icon' => 'banknote',      'color' => 'green'],
        ['label' => 'En attente',     'value' => number_format($stats['pending_orders']), 'icon' => 'clock',          'color' => 'yellow'],
        ['label' => 'Stock faible',   'value' => number_format($stats['low_stock']),      'icon' => 'alert-triangle', 'color' => 'red'],
    ] as $stat)
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-{{ $stat['color'] }}-100 rounded-xl flex items-center justify-center">
                <i data-lucide="{{ $stat['icon'] }}" class="w-5 h-5 text-{{ $stat['color'] }}-600"></i>
            </div>
        </div>
        <p class="text-2xl font-extrabold text-gray-900">{{ $stat['value'] }}</p>
        <p class="text-sm text-gray-500 mt-1">{{ $stat['label'] }}</p>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

    {{-- Revenue chart --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="font-bold text-gray-900 text-lg mb-6">Revenus mensuels (DT)</h2>
        <canvas id="revenueChart" height="100"></canvas>
    </div>

    {{-- Top products --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="font-bold text-gray-900 text-lg mb-4">Top produits</h2>
        <div class="space-y-3">
            @foreach($topProducts as $i => $product)
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold flex items-center justify-center flex-shrink-0">
                        {{ $i + 1 }}
                    </span>
                    <img src="{{ $product->image_url }}" alt="" class="w-9 h-9 object-cover rounded-xl border border-gray-100">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $product->title }}</p>
                        <p class="text-xs text-gray-400">{{ $product->order_items_count }} ventes</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Recent Orders --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-bold text-gray-900 text-lg">Commandes récentes</h2>
        <a href="{{ route('admin.orders.index') }}" class="text-sm text-indigo-600 font-semibold hover:underline">Voir toutes →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full admin-table">
            <thead>
                <tr>
                    <th>#</th><th>Client</th><th>Articles</th><th>Total</th><th>Statut</th><th>Date</th><th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                    <tr>
                        <td class="font-mono font-medium">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <img src="{{ $order->user->avatar_url }}" alt="" class="w-7 h-7 rounded-full object-cover">
                                <span class="font-medium">{{ $order->user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $order->items->count() }} article(s)</td>
                        <td class="font-bold text-indigo-700">{{ number_format($order->total, 2) }} DT</td>
                        <td><span class="badge-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
                        <td class="text-gray-400">{{ $order->created_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:underline text-sm">Voir</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
const months = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];
const revenueData = @json($monthlyRevenue);

const labels = Array.from({length: 12}, (_, i) => months[i]);
const data   = Array(12).fill(0);
revenueData.forEach(r => data[r.month - 1] = parseFloat(r.revenue));

new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            label: 'Revenus (DT)',
            data,
            borderColor: '#4F46E5',
            backgroundColor: 'rgba(79,70,229,0.08)',
            borderWidth: 2.5,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#4F46E5',
            pointRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { color: '#9ca3af' } },
            x: { grid: { display: false }, ticks: { color: '#9ca3af' } }
        }
    }
});
</script>
@endpush
@endsection
