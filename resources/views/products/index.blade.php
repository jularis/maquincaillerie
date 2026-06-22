@extends('layouts.app')

@section('title', ($selectedCategory ? $selectedCategory->name . ' — ' : '') . 'Produits — Ma Quincaillerie Solaire')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-primary-700">Accueil</a>
        <span>›</span>
        <a href="{{ route('products.index') }}" class="hover:text-primary-700">Produits</a>
        @if($selectedCategory)
        <span>›</span>
        <span class="text-gray-800 font-medium">{{ $selectedCategory->name }}</span>
        @endif
    </nav>

    <div class="flex flex-col lg:flex-row gap-8">

        {{-- Sidebar filters --}}
        <aside class="w-full lg:w-64 shrink-0">
            <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm sticky top-24">
                <h2 class="font-bold text-gray-800 mb-5 flex items-center gap-2">
                    🔽 Filtres
                    @if(request()->hasAny(['category','brand','min_price','max_price']))
                    <a href="{{ route('products.index') }}" class="ml-auto text-xs text-red-500 hover:text-red-700 font-normal">Réinitialiser</a>
                    @endif
                </h2>

                <form action="{{ route('products.index') }}" method="GET" id="filter-form">
                    @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    {{-- Category filter --}}
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Catégorie</h3>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="category" value="" form="filter-form"
                                    {{ !request('category') ? 'checked' : '' }}
                                    class="text-primary-700 focus:ring-primary-500"
                                    onchange="this.form.submit()">
                                <span class="text-sm text-gray-600 group-hover:text-primary-700">Toutes</span>
                            </label>
                            @foreach($categories as $cat)
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="category" value="{{ $cat->slug }}" form="filter-form"
                                    {{ request('category') === $cat->slug ? 'checked' : '' }}
                                    class="text-primary-700 focus:ring-primary-500"
                                    onchange="this.form.submit()">
                                <span class="text-sm text-gray-600 group-hover:text-primary-700">{{ $cat->icon }} {{ $cat->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Brand filter --}}
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Marque</h3>
                        <div class="space-y-2">
                            @foreach($brands as $brand)
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" name="brand" value="{{ $brand->slug }}" form="filter-form"
                                    {{ request('brand') === $brand->slug ? 'checked' : '' }}
                                    class="text-primary-700 rounded focus:ring-primary-500"
                                    onchange="this.form.submit()">
                                <span class="text-sm text-gray-600 group-hover:text-primary-700">{{ $brand->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Price range --}}
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Prix (F CFA)</h3>
                        <div class="flex gap-2 items-center">
                            <input type="number" name="min_price" placeholder="Min" form="filter-form"
                                value="{{ request('min_price') }}"
                                class="input-field text-sm py-2 px-3 w-full">
                            <span class="text-gray-400">–</span>
                            <input type="number" name="max_price" placeholder="Max" form="filter-form"
                                value="{{ request('max_price') }}"
                                class="input-field text-sm py-2 px-3 w-full">
                        </div>
                        <button type="submit" form="filter-form" class="mt-3 w-full btn-primary text-sm py-2">Appliquer</button>
                    </div>
                </form>
            </div>
        </aside>

        {{-- Product grid --}}
        <div class="flex-1 min-w-0">
            {{-- Header toolbar --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ $selectedCategory ? $selectedCategory->icon . ' ' . $selectedCategory->name : '🛍️ Tous les produits' }}
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">{{ $products->total() }} produit(s) trouvé(s)</p>
                </div>
                <form action="{{ route('products.index') }}" method="GET" class="flex items-center gap-2">
                    @foreach(request()->except('sort') as $key => $val)
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endforeach
                    <label class="text-sm text-gray-600">Trier par :</label>
                    <select name="sort" onchange="this.form.submit()"
                        class="input-field py-2 text-sm w-auto min-w-36">
                        <option value="featured" {{ request('sort','featured') === 'featured' ? 'selected' : '' }}>Popularité</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Nouveautés</option>
                    </select>
                </form>
            </div>

            @if($products->isEmpty())
            <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
                <div class="text-6xl mb-4">🔍</div>
                <h3 class="text-xl font-bold text-gray-700 mb-2">Aucun produit trouvé</h3>
                <p class="text-gray-400 mb-6">Essayez de modifier vos filtres ou votre recherche.</p>
                <a href="{{ route('products.index') }}" class="btn-primary">Voir tous les produits</a>
            </div>
            @else
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($products as $product)
                @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
            <div class="mt-10">
                {{ $products->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
