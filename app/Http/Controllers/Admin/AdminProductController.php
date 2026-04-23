<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('user', 'categories')
            ->when($request->q, fn($q, $s) => $q->where('title', 'like', "%$s%"))
            ->latest()
            ->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Admin can create products on behalf of any user
        return redirect()->route('admin.products.index')
            ->with('success', 'Produit créé.');
    }

    public function show(Product $produit)
    {
        $produit->load('user', 'categories', 'reviews.user', 'orderItems');
        return view('admin.products.show', ['product' => $produit]);
    }

    public function edit(Product $produit)
    {
        $categories = Category::where('is_active', true)->get();
        $produit->load('categories');
        return view('admin.products.edit', [
            'product'    => $produit,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Product $produit)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'old_price'   => 'nullable|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'is_active'   => 'boolean',
            'is_featured' => 'boolean',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data['is_active']   = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('image')) {
            if ($produit->image) {
                Storage::disk('public')->delete($produit->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $produit->update($data);

        if ($request->has('categories')) {
            $produit->categories()->sync($request->categories);
        }

        return back()->with('success', 'Produit mis à jour.');
    }

    public function destroy(Product $produit)
    {
        if ($produit->image) {
            Storage::disk('public')->delete($produit->image);
        }
        if ($produit->images) {
            foreach ($produit->images as $img) {
                Storage::disk('public')->delete($img);
            }
        }
        $produit->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit supprimé.');
    }
}
