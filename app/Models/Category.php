<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'icon', 'description', 'image', 'parent_id', 'order', 'active'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Required by Voyager BREAD: Str::camel('parent_id') = 'parentId'
    public function parentId()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
