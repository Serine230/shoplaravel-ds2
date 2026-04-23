<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()->with('items.product')->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $ids      = array_keys($cart);
        $products = Product::whereIn('id', $ids)->get()
            ->map(function ($p) use ($cart) {
                $p->cart_qty      = $cart[$p->id];
                $p->cart_subtotal = $p->price * $cart[$p->id];
                return $p;
            });

        $subtotal = $products->sum('cart_subtotal');
        $shipping = $subtotal >= 100 ? 0 : 7;
        $total    = $subtotal + $shipping;

        return view('orders.checkout', compact('products', 'subtotal', 'shipping', 'total'));
    }

    public function store(StoreOrderRequest $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Panier vide.');
        }

        $ids      = array_keys($cart);
        $products = Product::whereIn('id', $ids)->get()->keyBy('id');
        $subtotal = 0;
        $items    = [];

        foreach ($cart as $productId => $qty) {
            $product = $products[$productId] ?? null;
            if (!$product || $product->stock < $qty) {
                return back()->with('error', "Stock insuffisant pour : {$product?->title}");
            }
            $lineTotal = $product->price * $qty;
            $subtotal += $lineTotal;
            $items[] = [
                'product_id' => $productId,
                'quantity'   => $qty,
                'price'      => $product->price,
                'total'      => $lineTotal,
            ];
        }

        $shipping = $subtotal >= 100 ? 0 : 7;
        $total    = $subtotal + $shipping;

        DB::transaction(function () use ($request, $items, $subtotal, $shipping, $total, $products, $cart) {
            $order = Order::create([
                'user_id'          => auth()->id(),
                'subtotal'         => $subtotal,
                'shipping'         => $shipping,
                'total'            => $total,
                'payment_method'   => $request->payment_method,
                'shipping_address' => $request->shipping_address,
                'shipping_city'    => $request->shipping_city,
                'shipping_phone'   => $request->shipping_phone,
                'notes'            => $request->notes,
                'status'           => 'en_attente',
            ]);

            $order->items()->createMany($items);

            // Décrémenter le stock
            foreach ($cart as $productId => $qty) {
                $products[$productId]->decrement('stock', $qty);
            }

            session()->forget('cart');

            // Notification flash
            session()->flash('order_id', $order->id);
        });

        return redirect()->route('orders.show', session('order_id') ?? Order::latest()->first()->id)
            ->with('success', '🎉 Commande passée avec succès !');
    }

    public function show(Order $order)
    {
        abort_if($order->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);
        $order->load('items.product', 'user');
        return view('orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);
        abort_if(!$order->canBeCancelled(), 422, 'Cette commande ne peut plus être annulée.');

        // Restituer le stock
        foreach ($order->items as $item) {
            $item->product->increment('stock', $item->quantity);
        }

        $order->update(['status' => 'annulee']);
        return back()->with('success', 'Commande annulée.');
    }
}
