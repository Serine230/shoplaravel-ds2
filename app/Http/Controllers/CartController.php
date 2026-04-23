<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart     = $this->getCart();
        $products = collect();
        $total    = 0;

        if (!empty($cart)) {
            $ids      = array_keys($cart);
            $products = Product::with('categories')->whereIn('id', $ids)->get()
                ->map(function ($product) use ($cart) {
                    $product->cart_qty = $cart[$product->id];
                    $product->cart_subtotal = $product->price * $cart[$product->id];
                    return $product;
                });
            $total = $products->sum('cart_subtotal');
        }

        return view('cart.index', compact('products', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        abort_if(!$product->is_active || $product->stock < 1, 422);

        $qty  = max(1, (int) $request->get('qty', 1));
        $cart = $this->getCart();

        $currentQty = $cart[$product->id] ?? 0;
        $newQty     = min($currentQty + $qty, $product->stock);

        $cart[$product->id] = $newQty;
        $this->saveCart($cart);

        if ($request->ajax()) {
            return response()->json([
                'success'  => true,
                'message'  => 'Produit ajouté au panier !',
                'cartCount' => array_sum($cart),
            ]);
        }

        return back()->with('success', '🛒 ' . $product->title . ' ajouté au panier !');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate(['qty' => 'required|integer|min:0|max:99']);

        $cart = $this->getCart();
        $qty  = (int) $request->qty;

        if ($qty <= 0) {
            unset($cart[$product->id]);
        } else {
            $cart[$product->id] = min($qty, $product->stock);
        }

        $this->saveCart($cart);

        return back()->with('success', 'Panier mis à jour.');
    }

    public function remove(Product $product)
    {
        $cart = $this->getCart();
        unset($cart[$product->id]);
        $this->saveCart($cart);

        return back()->with('success', 'Article retiré du panier.');
    }

    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Panier vidé.');
    }

    // ─── Helpers ─────────────────────────────────────────────────
    private function getCart(): array
    {
        return session()->get('cart', []);
    }

    private function saveCart(array $cart): void
    {
        session()->put('cart', $cart);
    }

    public static function getCount(): int
    {
        return array_sum(session()->get('cart', []));
    }
}
