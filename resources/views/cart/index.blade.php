@extends('layouts.app')

@section('title', 'Mon Panier — Ma Quincaillerie Solaire')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    <h1 class="text-3xl font-bold text-gray-900 mb-8">🛒 Mon Panier</h1>

    @if($items->isEmpty())
    <div class="text-center py-20 bg-white rounded-2xl border border-gray-100">
        <div class="text-7xl mb-4">🛒</div>
        <h3 class="text-xl font-bold text-gray-700 mb-2">Votre panier est vide</h3>
        <p class="text-gray-400 mb-8">Découvrez nos produits solaires et commencez votre installation !</p>
        <a href="{{ route('products.index') }}" class="btn-primary">Découvrir nos produits</a>
    </div>
    @else
    <div class="grid lg:grid-cols-3 gap-8">
        {{-- Items --}}
        <div class="lg:col-span-2 space-y-4">
            @foreach($items as $item)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex gap-4">
                {{-- Image --}}
                <div class="w-24 h-24 bg-gray-50 rounded-lg flex items-center justify-center shrink-0 overflow-hidden">
                    @if($item->product->image)
                        <img src="{{ asset('storage/app/public/'.$item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-4xl">{{ $item->product->category->icon ?? '📦' }}</span>
                    @endif
                </div>
                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2">
                        <div>
                            @if($item->product->brand)
                            <div class="text-xs text-primary-600 font-semibold mb-0.5">{{ $item->product->brand->name }}</div>
                            @endif
                            <h3 class="font-semibold text-gray-800 text-sm">
                                <a href="{{ route('products.show', $item->product->slug) }}" class="hover:text-primary-700">{{ $item->product->name }}</a>
                            </h3>
                        </div>
                        <div class="text-right shrink-0">
                            <div class="font-bold text-primary-800">{{ fcfa($item->product->price * $item->quantity) }}</div>
                            <div class="text-xs text-gray-400">{{ fcfa($item->product->price) }} / unité</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 mt-3">
                        {{-- Qty --}}
                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                            @csrf @method('PATCH')
                            <input type="hidden" name="quantity" id="qty-{{ $item->id }}" value="{{ $item->quantity }}">
                            <button type="button"
                                onclick="var h=document.getElementById('qty-{{ $item->id }}'); if(h.value>1){h.value--; document.getElementById('qty-display-{{ $item->id }}').textContent=h.value; this.form.submit();}"
                                class="px-3 py-2 text-gray-600 hover:bg-gray-50 text-sm font-bold leading-none">−</button>
                            <span id="qty-display-{{ $item->id }}" class="w-10 text-center text-sm font-semibold text-gray-800 select-none">{{ $item->quantity }}</span>
                            <button type="button"
                                onclick="var h=document.getElementById('qty-{{ $item->id }}'); if(h.value<{{ $item->product->stock }}){h.value++; document.getElementById('qty-display-{{ $item->id }}').textContent=h.value; this.form.submit();}"
                                class="px-3 py-2 text-gray-600 hover:bg-gray-50 text-sm font-bold leading-none">+</button>
                        </form>
                        {{-- Remove --}}
                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600 text-sm transition-colors flex items-center gap-1">
                                🗑️ Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Summary --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 sticky top-24">
                <h2 class="font-bold text-gray-800 text-lg mb-5">Récapitulatif</h2>
                <div class="space-y-3 text-sm mb-5">
                    <div class="flex justify-between text-gray-600">
                        <span>Sous-total ({{ $items->sum('quantity') }} article(s))</span>
                        <span>{{ fcfa($total) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Livraison</span>
                        <span>Au frais du client</span>
                    </div>
                </div>
                <div class="border-t border-gray-100 pt-4 mb-5">
                    <div class="flex justify-between font-bold text-lg">
                        <span>Total</span>
                        <span class="text-primary-800">{{ fcfa($total) }}</span>
                    </div>
                </div>
                <a href="{{ route('checkout.index') }}" class="btn-primary w-full text-center text-base py-3.5">
                    Passer la commande →
                </a>
                <a href="{{ route('products.index') }}" class="block text-center mt-3 text-sm text-gray-500 hover:text-primary-700 transition-colors">
                    ← Continuer mes achats
                </a>
                <div class="mt-5 pt-5 border-t border-gray-100 flex items-center gap-2 text-xs text-gray-400">
                    🔒 Paiement 100% sécurisé — SSL
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
