@extends('layouts.app')

@section('title', 'Commande — Ma Quincaillerie Solaire')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    <h1 class="text-3xl font-bold text-gray-900 mb-8">📋 Finaliser la commande</h1>

    {{-- Steps --}}
    <div class="flex items-center gap-2 mb-10">
        <div class="flex items-center gap-2 text-primary-700 font-semibold text-sm">
            <div class="w-7 h-7 bg-primary-700 text-white rounded-full flex items-center justify-center text-xs font-bold">1</div>
            <span>Livraison</span>
        </div>
        <div class="flex-1 h-0.5 bg-gray-200"></div>
        <div class="flex items-center gap-2 text-gray-400 text-sm">
            <div class="w-7 h-7 border-2 border-gray-300 rounded-full flex items-center justify-center text-xs">2</div>
            <span>Paiement</span>
        </div>
        <div class="flex-1 h-0.5 bg-gray-200"></div>
        <div class="flex items-center gap-2 text-gray-400 text-sm">
            <div class="w-7 h-7 border-2 border-gray-300 rounded-full flex items-center justify-center text-xs">3</div>
            <span>Confirmation</span>
        </div>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="grid lg:grid-cols-3 gap-8">

            {{-- Form --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Delivery info --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                    <h2 class="font-bold text-gray-800 text-lg mb-5">📦 Informations de livraison</h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Prénom *</label>
                            <input type="text" name="first_name" value="{{ old('first_name', auth()->user()->name ?? '') }}"
                                class="input-field" required>
                            @error('first_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nom *</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}"
                                class="input-field" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email *</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}"
                                class="input-field" required>
                            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Téléphone</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}"
                                placeholder="06 00 00 00 00" class="input-field">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Adresse *</label>
                            <input type="text" name="address" value="{{ old('address') }}"
                                placeholder="12 rue de la Paix" class="input-field" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Ville *</label>
                            <input type="text" name="city" value="{{ old('city') }}"
                                class="input-field" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Code postal *</label>
                            <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                                placeholder="75001" class="input-field" required>
                        </div>
                    </div>
                </div>

                {{-- Payment method --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                    <h2 class="font-bold text-gray-800 text-lg mb-5">💳 Mode de paiement</h2>
                    <div class="space-y-3" x-data="{ method: '{{ old('payment_method', 'cod') }}' }">
                        <label class="flex items-center gap-3 p-4 rounded-xl cursor-pointer transition-colors"
                               :class="method === 'cod' ? 'border-2 border-primary-500 bg-primary-50' : 'border border-gray-200 hover:border-primary-300'">
                            <input type="radio" name="payment_method" value="cod" x-model="method" class="text-primary-700">
                            <div>
                                <div class="font-semibold text-sm">🚚 Paiement à la livraison</div>
                                <div class="text-xs text-gray-500">Payez en espèces à la réception de votre commande</div>
                            </div>
                            <div class="ml-auto text-xs font-medium text-green-600 shrink-0">Recommandé</div>
                        </label>
                        <label class="flex items-center gap-3 p-4 rounded-xl cursor-pointer transition-colors"
                               :class="method === 'card' ? 'border-2 border-primary-500 bg-primary-50' : 'border border-gray-200 hover:border-primary-300'">
                            <input type="radio" name="payment_method" value="card" x-model="method" class="text-primary-700">
                            <div>
                                <div class="font-semibold text-sm">💳 Carte bancaire</div>
                                <div class="text-xs text-gray-500">Visa, Mastercard, American Express</div>
                            </div>
                            <div class="ml-auto flex gap-1 text-xs text-gray-400 shrink-0">VISA MC</div>
                        </label>
                        <label class="flex items-center gap-3 p-4 rounded-xl cursor-pointer transition-colors"
                               :class="method === 'transfer' ? 'border-2 border-primary-500 bg-primary-50' : 'border border-gray-200 hover:border-primary-300'">
                            <input type="radio" name="payment_method" value="transfer" x-model="method" class="text-primary-700">
                            <div>
                                <div class="font-semibold text-sm">🏦 Virement bancaire</div>
                                <div class="text-xs text-gray-500">Délai : 2-3 jours ouvrables</div>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-4 rounded-xl cursor-pointer transition-colors"
                               :class="method === 'check' ? 'border-2 border-primary-500 bg-primary-50' : 'border border-gray-200 hover:border-primary-300'">
                            <input type="radio" name="payment_method" value="check" x-model="method" class="text-primary-700">
                            <div>
                                <div class="font-semibold text-sm">📝 Chèque</div>
                                <div class="text-xs text-gray-500">À l'ordre de Ma Quincaillerie Solaire</div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Notes de commande (optionnel)</label>
                    <textarea name="notes" rows="3" placeholder="Instructions spéciales pour la livraison..."
                        class="input-field resize-none">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- Order summary --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 sticky top-24">
                    <h2 class="font-bold text-gray-800 text-lg mb-5">Récapitulatif</h2>
                    <div class="space-y-3 mb-5">
                        @foreach($items as $item)
                        <div class="flex gap-3 text-sm">
                            <div class="w-10 h-10 bg-gray-50 rounded-lg flex items-center justify-center shrink-0 text-lg">
                                {{ $item->product->category->icon ?? '📦' }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-medium text-gray-800 truncate text-xs">{{ $item->product->name }}</div>
                                <div class="text-gray-400 text-xs">× {{ $item->quantity }}</div>
                            </div>
                            <div class="font-semibold text-gray-800 text-xs shrink-0">
                                {{ fcfa($item->product->price * $item->quantity) }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="border-t border-gray-100 pt-4 space-y-2 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Sous-total HT</span>
                            <span>{{ fcfa($subtotal) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>TVA (18%)</span>
                            <span>{{ fcfa($tax) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Livraison</span>
                            <span class="{{ $shipping == 0 ? 'text-green-600' : '' }}">{{ $shipping == 0 ? 'Gratuite' : fcfa($shipping) }}</span>
                        </div>
                        <div class="flex justify-between font-bold text-base pt-2 border-t border-gray-100">
                            <span>Total TTC</span>
                            <span class="text-primary-800">{{ fcfa($total) }}</span>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary w-full text-center text-base py-4 mt-5">
                        ✅ Confirmer la commande
                    </button>
                    <p class="text-xs text-gray-400 text-center mt-3">🔒 Paiement 100% sécurisé</p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
