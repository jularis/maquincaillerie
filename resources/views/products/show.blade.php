@extends('layouts.app')

@section('title', $product->name . ' — Ma Quincaillerie Solaire')
@section('meta_description', $product->short_description)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6 flex-wrap">
        <a href="{{ route('home') }}" class="hover:text-primary-700">Accueil</a>
        <span>›</span>
        <a href="{{ route('products.index') }}" class="hover:text-primary-700">Produits</a>
        <span>›</span>
        <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:text-primary-700">{{ $product->category->name }}</a>
        <span>›</span>
        <span class="text-gray-800 font-medium truncate max-w-xs">{{ $product->name }}</span>
    </nav>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="grid lg:grid-cols-2 gap-0">

            {{-- Galerie --}}
            @php
                $gallery = [];
                if ($product->image) $gallery[] = $product->image;
                foreach (json_decode($product->images ?? '[]', true) ?? [] as $img) { if ($img !== $product->image) $gallery[] = $img; }
            @endphp
            <div x-data="{ active: '{{ $gallery[0] ?? '' }}' }" class="bg-gray-50 flex flex-col gap-3 p-6 relative">
                @if($product->old_price)
                <div class="absolute top-4 left-4 z-10">
                    <span class="bg-red-500 text-white text-sm font-semibold px-3 py-1 rounded-full">-{{ $product->discount_percent }}%</span>
                </div>
                @endif

                {{-- Image principale --}}
                <div class="flex items-center justify-center min-h-72 rounded-xl overflow-hidden bg-white">
                    @if($gallery)
                        <img :src="'{{ asset('storage/') }}/' + active" alt="{{ $product->name }}"
                             class="max-h-80 max-w-full object-contain transition-opacity duration-200">
                    @else
                        <div class="text-center py-10">
                            <div class="text-8xl mb-4">{{ $product->category->icon ?? '📦' }}</div>
                            <span class="text-gray-400 text-sm">{{ $product->category->name }}</span>
                        </div>
                    @endif
                </div>

                {{-- Miniatures --}}
                @if(count($gallery) > 1)
                <div class="flex gap-2 overflow-x-auto pb-1">
                    @foreach($gallery as $img)
                    <button type="button"
                            @click="active = '{{ $img }}'"
                            :class="active === '{{ $img }}' ? 'ring-2 ring-primary-600 ring-offset-1' : 'ring-1 ring-gray-200 opacity-70 hover:opacity-100'"
                            class="shrink-0 w-16 h-16 rounded-lg overflow-hidden bg-white transition-all">
                        <img src="{{ asset('storage/'.$img) }}" alt="{{ $product->name }}" class="w-full h-full object-contain p-1">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="p-8 lg:p-10">
                @if($product->brand)
                <a href="{{ route('products.index', ['brand' => $product->brand->slug]) }}"
                   class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary-600 mb-3 hover:text-primary-800 transition-colors">
                    {{ $product->brand->name }}
                    @if($product->brand->country)
                    <span class="text-gray-400">· {{ $product->brand->country }}</span>
                    @endif
                </a>
                @endif

                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>

                @if($product->sku)
                <div class="text-xs text-gray-400 mb-4">Réf : {{ $product->sku }}</div>
                @endif

                <p class="text-gray-600 leading-relaxed mb-6">{{ $product->short_description }}</p>

                {{-- Price --}}
                <div class="flex items-end gap-3 mb-2">
                    <span class="text-4xl font-bold text-primary-800">{{ fcfa($product->price) }}</span>
                    @if($product->old_price)
                    <div class="pb-1">
                        <span class="text-lg text-gray-400 line-through">{{ fcfa($product->old_price) }}</span>
                        <span class="ml-2 text-red-500 text-sm font-semibold">Économisez {{ fcfa($product->old_price - $product->price) }}</span>
                    </div>
                    @endif
                </div>
                <p class="text-xs text-gray-400 mb-6">Prix HT hors taxe — TVA applicable à la commande</p>

                {{-- Stock --}}
                <div class="mb-6">
                    @if($product->stock > 5)
                    <span class="inline-flex items-center gap-1.5 text-green-700 bg-green-50 px-3 py-1.5 rounded-full text-sm font-medium">
                        ✅ En stock — Livraison au frais du client
                    </span>
                    @elseif($product->stock > 0)
                    <span class="inline-flex items-center gap-1.5 text-orange-700 bg-orange-50 px-3 py-1.5 rounded-full text-sm font-medium">
                        ⚠️ Plus que {{ $product->stock }} en stock !
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 text-red-700 bg-red-50 px-3 py-1.5 rounded-full text-sm font-medium">
                        ❌ Rupture de stock
                    </span>
                    @endif
                </div>

                {{-- Add to cart --}}
                <div x-data="addToCart({{ $product->id }}, {{ $product->stock > 0 ? 'true' : 'false' }})" class="flex gap-3 mb-6">
                    <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                        <button type="button" @click="quantity > 1 && quantity--"
                            class="px-3 py-2.5 text-gray-600 hover:bg-gray-50 transition-colors font-bold">−</button>
                        <input type="number" x-model.number="quantity" min="1" max="{{ $product->stock }}"
                            class="w-14 text-center border-0 outline-none py-2.5 font-medium">
                        <button type="button" @click="quantity < {{ $product->stock }} && quantity++"
                            class="px-3 py-2.5 text-gray-600 hover:bg-gray-50 transition-colors font-bold">+</button>
                    </div>
                    <button @click="submit()"
                        :disabled="loading || {{ $product->stock <= 0 ? 'true' : 'false' }}"
                        :class="added ? 'bg-green-600 hover:bg-green-700' : ''"
                        class="flex-1 btn-primary text-base transition-all duration-300 disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <svg x-show="loading" class="animate-spin w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
                        <span x-text="loading ? 'Ajout en cours…' : (added ? '✓ Ajouté au panier !' : '🛒 Ajouter au panier')">🛒 Ajouter au panier</span>
                    </button>
                </div>

                {{-- Infos rapides --}}
                <div class="grid grid-cols-2 gap-3 mb-6">
                    @if($product->warranty)
                    <div class="flex items-center gap-2 text-sm text-gray-600 bg-gray-50 rounded-lg px-3 py-2">
                        🛡️ <span>Garantie {{ $product->warranty }}</span>
                    </div>
                    @endif
                    @if($product->power)
                    <div class="flex items-center gap-2 text-sm text-gray-600 bg-gray-50 rounded-lg px-3 py-2">
                        ⚡ <span>{{ number_format($product->power) }} W</span>
                    </div>
                    @endif
                    <div class="flex items-center gap-2 text-sm text-gray-600 bg-gray-50 rounded-lg px-3 py-2">
                        🚚 <span>Livraison au frais du client</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 bg-gray-50 rounded-lg px-3 py-2">
                        ↩️ <span>Retour 30 jours</span>
                    </div>
                </div>

                {{-- Fiche technique --}}
                @if($product->datasheet)
                <a href="{{ asset('storage/' . $product->datasheet) }}"
                   target="_blank"
                   download
                   class="flex items-center gap-3 w-full bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 hover:bg-red-100 transition-colors mb-6 font-semibold text-sm">
                    <svg class="w-6 h-6 shrink-0 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM12 17l-4-4h2.5v-3h3v3H16l-4 4z"/>
                    </svg>
                    <div>
                        <div>Télécharger la fiche technique</div>
                        <div class="text-xs text-red-400 font-normal">Format PDF</div>
                    </div>
                    <svg class="w-4 h-4 ml-auto shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </a>
                @endif

                {{-- Contact --}}
                <div class="bg-primary-50 rounded-xl p-4 border border-primary-100">
                    <p class="text-sm font-semibold text-primary-800 mb-1">💬 Besoin d'un conseil ?</p>
                    <p class="text-xs text-primary-600">Nos experts sont disponibles du lun. au sam. de 9h à 18h</p>
                    <a href="tel:{{ preg_replace('/\s+/', '', setting('site.phone_1')) }}" class="mt-2 inline-flex items-center gap-1 text-sm text-primary-700 font-semibold hover:text-primary-900">
                        📞 {{ setting('site.phone_1') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Tabs: Description + Specs --}}
        <div class="border-t border-gray-100 p-8 lg:p-10" x-data="{ tab: 'desc' }">
            <div class="flex gap-1 mb-6 border-b border-gray-200">
                <button @click="tab = 'desc'" :class="tab === 'desc' ? 'border-b-2 border-primary-700 text-primary-700 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                    class="px-5 py-3 text-sm transition-colors">Description</button>
                @if($product->specs)
                <button @click="tab = 'specs'" :class="tab === 'specs' ? 'border-b-2 border-primary-700 text-primary-700 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                    class="px-5 py-3 text-sm transition-colors">Caractéristiques</button>
                @endif
            </div>

            <div x-show="tab === 'desc'" class="prose max-w-none text-gray-700 text-sm leading-relaxed">
                {!! $product->description ?? $product->short_description !!}
            </div>

            @if($product->specs)
            <div x-show="tab === 'specs'" class="prose max-w-none text-gray-700 text-sm leading-relaxed">
                {!! $product->specs !!}
            </div>
            @endif
        </div>
    </div>

    {{-- Related products --}}
    @if($related->isNotEmpty())
    <div class="mt-14">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Produits similaires</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($related as $rel)
            @include('partials.product-card', ['product' => $rel])
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
