<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('user')
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $commande)
    {
        $commande->load('user', 'items.product');
        return view('admin.orders.show', ['order' => $commande]);
    }

    public function updateStatus(Request $request, Order $commande)
    {
        $request->validate([
            'status' => 'required|in:en_attente,validee,expediee,livree,annulee',
        ]);

        $commande->update(['status' => $request->status]);

        return back()->with('success', 'Statut de la commande mis à jour.');
    }
}
