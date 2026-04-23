<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $featured   = Product::active()->featured()->inStock()->with('categories')->latest()->take(8)->get();
        $newest     = Product::active()->inStock()->with('categories', 'reviews')->latest()->take(12)->get();
        $categories = Category::where('is_active', true)->whereNull('parent_id')->withCount('products')->orderBy('sort_order')->take(8)->get();

        return view('home', compact('featured', 'newest', 'categories'));
    }

    public function category(Category $category)
    {
        $products = Product::active()->inStock()
            ->byCategory($category->id)
            ->with('categories', 'reviews')
            ->paginate(12);

        return view('products.index', compact('products', 'category'));
    }
}
