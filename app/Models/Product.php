<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'slug', 'description', 'price', 'old_price',
        'stock', 'image', 'images', 'is_active', 'is_featured',
    ];

    protected $casts = [
        'images'      => 'array',
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
        'price'       => 'decimal:2',
        'old_price'   => 'decimal:2',
    ];

    // ─── Auto Slug ───────────────────────────────────────────────
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            $product->slug = $product->slug ?? static::generateUniqueSlug($product->title);
        });
    }

    public static function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'LIKE', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }

    // ─── Accessors ───────────────────────────────────────────────
    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/product-placeholder.jpg');
    }

    public function getDiscountPercentAttribute(): ?int
    {
        if ($this->old_price && $this->old_price > $this->price) {
            return (int) round((($this->old_price - $this->price) / $this->old_price) * 100);
        }
        return null;
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    // ─── Scopes ──────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopePriceBetween($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'LIKE', "%{$term}%")
              ->orWhere('description', 'LIKE', "%{$term}%");
        });
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->whereHas('categories', fn($q) => $q->where('categories.id', $categoryId));
    }

    public function scopeSortBy($query, string $sort)
    {
        return match ($sort) {
            'price_asc'   => $query->orderBy('price', 'asc'),
            'price_desc'  => $query->orderBy('price', 'desc'),
            'newest'      => $query->orderBy('created_at', 'desc'),
            'popular'     => $query->orderBy('views', 'desc'),
            'rating'      => $query->withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating'),
            default       => $query->orderBy('created_at', 'desc'),
        };
    }

    // ─── Relationships ───────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
