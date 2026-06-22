<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $categories = \App\Models\Category::where('active', true)->orderBy('order')->get();
        $brands = \App\Models\Brand::all();

        $query = \App\Models\Product::with(['brand', 'category'])->active();

        if ($request->category) {
            $cat = \App\Models\Category::where('slug', $request->category)->firstOrFail();
            $query->where('category_id', $cat->id);
        }
        if ($request->brand) {
            $brand = \App\Models\Brand::where('slug', $request->brand)->first();
            if ($brand) $query->where('brand_id', $brand->id);
        }
        if ($request->search) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }
        if ($request->min_price) $query->where('price', '>=', $request->min_price);
        if ($request->max_price) $query->where('price', '<=', $request->max_price);

        $sort = $request->sort ?? 'featured';
        match($sort) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'newest'     => $query->orderByDesc('created_at'),
            default      => $query->orderByDesc('featured')->orderByDesc('views'),
        };

        $products = $query->paginate(12)->withQueryString();
        $selectedCategory = $request->category ? \App\Models\Category::where('slug', $request->category)->first() : null;

        return view('products.index', compact('products', 'categories', 'brands', 'selectedCategory'));
    }

    public function show(\App\Models\Product $product)
    {
        $product->increment('views');
        $product->load(['brand', 'category']);
        $related = \App\Models\Product::with('brand')
            ->active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)->get();
        return view('products.show', compact('product', 'related'));
    }
}
