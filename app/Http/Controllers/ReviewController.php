<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'title'   => 'nullable|string|max:100',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        Review::updateOrCreate(
            ['user_id' => auth()->id(), 'product_id' => $product->id],
            [
                'rating'  => $request->rating,
                'title'   => $request->title,
                'comment' => $request->comment,
            ]
        );

        return back()->with('success', '⭐ Avis publié !');
    }

    public function destroy(Review $review)
    {
        abort_if($review->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);
        $review->delete();
        return back()->with('success', 'Avis supprimé.');
    }
}
