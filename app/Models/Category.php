<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'image', 'parent_id', 'is_active', 'sort_order'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($cat) {
            $cat->slug = $cat->slug ?? Str::slug($cat->name);
        });
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/category-placeholder.jpg');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
