<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with('categories', 'reviews');

        // Recherche par mot-clé
        if ($search = $request->get('q')) {
            $query->search($search);
        }

        // Filtrage par catégorie
        if ($categoryId = $request->get('categorie')) {
            $query->byCategory($categoryId);
        }

        // Filtrage par prix
        if ($request->filled('prix_min') || $request->filled('prix_max')) {
            $min = $request->get('prix_min', 0);
            $max = $request->get('prix_max', 999999);
            $query->priceBetween($min, $max);
        }

        // Tri
        $sort = $request->get('tri', 'newest');
        $query->sortBy($sort);

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->withCount('products')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        abort_if(!$product->is_active, 404);

        // Incrémenter vues
        $product->increment('views');

        $product->load('categories', 'user', 'reviews.user');

        $related = Product::active()->inStock()
            ->whereHas('categories', fn($q) =>
                $q->whereIn('categories.id', $product->categories->pluck('id'))
            )
            ->where('id', '!=', $product->id)
            ->take(4)->get();

        $userReview = auth()->check()
            ? $product->reviews->firstWhere('user_id', auth()->id())
            : null;

        return view('products.show', compact('product', 'related', 'userReview'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        // Upload image principale
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Upload images galerie
        if ($request->hasFile('images')) {
            $data['images'] = collect($request->file('images'))
                ->map(fn($img) => $img->store('products/gallery', 'public'))
                ->toArray();
        }

        $data['user_id'] = auth()->id();
        $product = Product::create($data);
        $product->categories()->sync($request->categories);

        return redirect()->route('products.show', $product)
            ->with('success', '🎉 Produit créé avec succès !');
    }

    public function edit(Product $product)
    {
        $this->authorizeProduct($product);
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $product->load('categories');
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($product->image) Storage::disk('public')->delete($product->image);
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        if ($request->hasFile('images')) {
            $newImages = collect($request->file('images'))
                ->map(fn($img) => $img->store('products/gallery', 'public'))
                ->toArray();
            $data['images'] = array_merge($product->images ?? [], $newImages);
        }

        $data['is_active'] = $request->boolean('is_active');
        $product->update($data);
        $product->categories()->sync($request->categories);

        return redirect()->route('products.show', $product)
            ->with('success', '✅ Produit mis à jour !');
    }

    public function destroy(Product $product)
    {
        $this->authorizeProduct($product);

        if ($product->image) Storage::disk('public')->delete($product->image);
        if ($product->images) {
            foreach ($product->images as $img) Storage::disk('public')->delete($img);
        }

        $product->delete();
        return redirect()->route('products.mine')->with('success', 'Produit supprimé.');
    }

    public function myProducts()
    {
        $products = auth()->user()->products()->with('categories')->latest()->paginate(12);
        return view('products.mine', compact('products'));
    }

    private function authorizeProduct(Product $product): void
    {
        if (!auth()->user()->isAdmin() && $product->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
