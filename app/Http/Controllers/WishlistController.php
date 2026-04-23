<?php

namespace App\Http\Controllers;

use App\Models\Product;

class WishlistController extends Controller
{
    public function index()
    {
        $products = auth()->user()->wishlistProducts()->with('categories', 'reviews')->paginate(12);
        return view('wishlist.index', compact('products'));
    }

    public function toggle(Product $product)
    {
        $user = auth()->user();

        if ($user->hasInWishlist($product)) {
            $user->wishlistProducts()->detach($product->id);
            $inWishlist = false;
            $message    = 'Retiré de la wishlist.';
        } else {
            $user->wishlistProducts()->attach($product->id);
            $inWishlist = true;
            $message    = '❤️ Ajouté à la wishlist !';
        }

        if (request()->ajax()) {
            return response()->json(['success' => true, 'inWishlist' => $inWishlist]);
        }

        return back()->with('success', $message);
    }
}
