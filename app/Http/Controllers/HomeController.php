<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Category::where('active', true)->orderBy('order')->get();
        $featuredProducts = \App\Models\Product::with(['brand', 'category'])
            ->active()->featured()->take(12)->get();
        $brands = \App\Models\Brand::where('featured', true)->get();

        return view('home', compact('categories', 'featuredProducts', 'brands'));
    }
}
