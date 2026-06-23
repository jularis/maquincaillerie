<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'brand_id', 'name', 'slug', 'sku', 'short_description', 'description',
        'price', 'old_price', 'stock', 'image', 'images', 'specs', 'power',
        'warranty', 'datasheet', 'featured', 'active', 'views',
    ];

    protected $casts = [
        'images' => 'array',
        'specs'  => 'array',
        'featured' => 'boolean',
        'active'   => 'boolean',
        'price'    => 'float',
        'old_price'=> 'float',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Required by Voyager BREAD: Str::camel('category_id') = 'categoryId'
    public function categoryId()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // Required by Voyager BREAD: Str::camel('brand_id') = 'brandId'
    public function brandId()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getDiscountPercentAttribute()
    {
        if ($this->old_price && $this->old_price > $this->price) {
            return round((($this->old_price - $this->price) / $this->old_price) * 100);
        }
        return 0;
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }
}
