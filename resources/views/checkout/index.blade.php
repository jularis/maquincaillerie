@extends('layouts.app')

@section('title', 'Commande — Ma Quincaillerie Solaire')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    <h1 class="text-3xl font-bold text-gray-900 mb-8">📋 Finaliser la commande</h1>

    {{-- Steps --}}
    <div class="flex items-center gap-2 mb-10">
        <div class="flex items-center gap-2 text-navy font-semibold text-sm">
            <div class="w-7 h-7 bg-navy text-white rounded-full flex items-center justify-center text-xs font-bold">1</div>
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

    <form action="{{ route('checkout.store') }}" method="POST"
          x-data="{
              selectedAddress: null,
              fill(addr) {
                  this.selectedAddress = addr.id;
                  document.getElementById('f_first_name').value  = addr.first_name;
                  document.getElementById('f_last_name').value   = addr.last_name;
                  document.getElementById('f_phone').value       = addr.phone    ?? '';
                  document.getElementById('f_address').value     = addr.address;
                  document.getElementById('f_city').value        = addr.city;
                  document.getElementById('f_postal_code').value = addr.postal_code ?? '';
                  document.getElementById('f_country').value     = addr.country;
              }
          }">
        @csrf
        <div class="grid lg:grid-cols-3 gap-8">

            {{-- Form --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- ===== Adresses sauvegardées (si connecté et en a) ===== --}}
                @if($savedAddresses->isNotEmpty())
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-bold text-gray-800 text-lg">📍 Choisir une adresse enregistrée</h2>
                        <a href="{{ route('addresses.create') }}" target="_blank"
                           class="text-xs font-semibold text-navy hover:underline flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Ajouter une adresse
                        </a>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-3">
                        @foreach($savedAddresses as $addr)
                        @php
                        $addrJson = json_encode([
                            'id'          => $addr->id,
                            'first_name'  => $addr->first_name,
                            'last_name'   => $addr->last_name,
                            'phone'       => $addr->phone ?? '',
                            'address'     => $addr->address,
                            'city'        => $addr->city,
                            'postal_code' => $addr->postal_code ?? '',
                            'country'     => $addr->country,
                        ]);
                        @endphp
                        <button type="button"
                            @click="fill({{ $addrJson }})"
                            :class="selectedAddress === {{ $addr->id }}
                                ? 'border-navy bg-navy/5 ring-2 ring-navy/20'
                                : 'border-gray-200 hover:border-navy/40'"
                            class="text-left p-4 rounded-xl border-2 transition-all relative">

                            @if($addr->is_default)
                            <span class="absolute top-2 right-2 text-[10px] font-bold bg-navy text-white px-2 py-0.5 rounded-full">Par défaut</span>
                            @endif

                            @if($addr->label)
                            <div class="text-xs font-bold text-orange uppercase tracking-wide mb-1">{{ $addr->label }}</div>
                            @endif

                            <div class="font-semibold text-gray-800 text-sm pr-14">{{ $addr->full_name }}</div>
                            @if($addr->phone)
                            <div class="text-xs text-gray-500 mt-0.5">{{ $addr->phone }}</div>
                            @endif
                            <div class="text-xs text-gray-500 mt-1 leading-snug">
                                {{ $addr->address }}<br>
                                {{ $addr->postal_code ? $addr->postal_code . ' ' : '' }}{{ $addr->city }}
                            </div>

                            <div x-show="selectedAddress === {{ $addr->id }}"
                                 class="absolute bottom-3 right-3 w-5 h-5 bg-navy rounded-full flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </button>
                        @endforeach
                    </div>

                    <p class="text-xs text-gray-400 mt-3">
                        Cliquez sur une adresse pour pré-remplir le formulaire ci-dessous. Vous pouvez modifier les champs avant de confirmer.
                    </p>
                </div>
                @endif

                {{-- ===== Formulaire d'adresse ===== --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                    <h2 class="font-bold text-gray-800 text-lg mb-5">
                        @if($savedAddresses->isNotEmpty())
                        📦 Adresse de livraison
                        @else
                        📦 Informations de livraison
                        @endif
                    </h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Prénom *</label>
                            <input id="f_first_name" type="text" name="first_name"
                                value="{{ old('first_name', auth()->user()->name ?? '') }}"
                                class="input-field" required>
                            @error('first_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nom *</label>
                            <input id="f_last_name" type="text" name="last_name"
                                value="{{ old('last_name') }}"
                                class="input-field" required>
                            @error('last_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email *</label>
                            <input type="email" name="email"
                                value="{{ old('email', auth()->user()->email ?? '') }}"
                                class="input-field" required>
                            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Téléphone</label>
                            <input id="f_phone" type="tel" name="phone"
                                value="{{ old('phone') }}"
                                placeholder="+225 07 00 00 00 00" class="input-field">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Adresse *</label>
                            <input id="f_address" type="text" name="address"
                                value="{{ old('address') }}"
                                placeholder="Rue, quartier, numéro…" class="input-field" required>
                            @error('address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Ville *</label>
                            <input id="f_city" type="text" name="city"
                                value="{{ old('city') }}"
                                class="input-field" required>
                            @error('city') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Code postal</label>
                            <input id="f_postal_code" type="text" name="postal_code"
                                value="{{ old('postal_code') }}"
                                class="input-field">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Pays</label>
                            <select id="f_country" name="country" class="input-field bg-white">
                                @php
                                $countries = ['CI'=>"Côte d'Ivoire",'SN'=>'Sénégal','ML'=>'Mali','BF'=>'Burkina Faso','GN'=>'Guinée','GH'=>'Ghana','TG'=>'Togo','BJ'=>'Bénin','CM'=>'Cameroun','FR'=>'France'];
                                $selCountry = old('country','CI');
                                @endphp
                                @foreach($countries as $code => $cLabel)
                                <option value="{{ $code }}" {{ $selCountry === $code ? 'selected' : '' }}>{{ $cLabel }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- ===== Mode de paiement ===== --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                    <h2 class="font-bold text-gray-800 text-lg mb-5">💳 Mode de paiement</h2>
                    <div class="space-y-3" x-data="{ method: '{{ old('payment_method', 'cod') }}' }">
                        <label class="flex items-center gap-3 p-4 rounded-xl cursor-pointer transition-colors"
                               :class="method === 'cod' ? 'border-2 border-navy bg-navy/5' : 'border border-gray-200 hover:border-navy/30'">
                            <input type="radio" name="payment_method" value="cod" x-model="method" class="text-navy">
                            <div>
                                <div class="font-semibold text-sm">🚚 Paiement à la livraison</div>
                                <div class="text-xs text-gray-500">Payez en espèces à la réception de votre commande</div>
                            </div>
                            <div class="ml-auto text-xs font-medium text-green-600 shrink-0">Recommandé</div>
                        </label>
                        <label class="flex items-center gap-3 p-4 rounded-xl cursor-pointer transition-colors"
                               :class="method === 'card' ? 'border-2 border-navy bg-navy/5' : 'border border-gray-200 hover:border-navy/30'">
                            <input type="radio" name="payment_method" value="card" x-model="method" class="text-navy">
                            <div>
                                <div class="font-semibold text-sm">💳 Carte bancaire</div>
                                <div class="text-xs text-gray-500">Visa, Mastercard, American Express</div>
                            </div>
                            <div class="ml-auto flex gap-1 text-xs text-gray-400 shrink-0">VISA MC</div>
                        </label>
                        <label class="flex items-center gap-3 p-4 rounded-xl cursor-pointer transition-colors"
                               :class="method === 'transfer' ? 'border-2 border-navy bg-navy/5' : 'border border-gray-200 hover:border-navy/30'">
                            <input type="radio" name="payment_method" value="transfer" x-model="method" class="text-navy">
                            <div>
                                <div class="font-semibold text-sm">🏦 Virement bancaire</div>
                                <div class="text-xs text-gray-500">Délai : 2-3 jours ouvrables</div>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-4 rounded-xl cursor-pointer transition-colors"
                               :class="method === 'check' ? 'border-2 border-navy bg-navy/5' : 'border border-gray-200 hover:border-navy/30'">
                            <input type="radio" name="payment_method" value="check" x-model="method" class="text-navy">
                            <div>
                                <div class="font-semibold text-sm">📝 Chèque</div>
                                <div class="text-xs text-gray-500">À l'ordre de Ma Quincaillerie Solaire</div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- ===== Notes ===== --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Notes de commande <span class="text-gray-400">(optionnel)</span></label>
                    <textarea name="notes" rows="3" placeholder="Instructions spéciales pour la livraison…"
                        class="input-field resize-none">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- ===== Récapitulatif ===== --}}
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
                            <span class="text-navy">{{ fcfa($total) }}</span>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary w-full text-center text-base py-4 mt-5">
                        ✅ Confirmer la commande
                    </button>
                    <p class="text-xs text-gray-400 text-center mt-3">🔒 Paiement 100% sécurisé</p>

                    @auth
                    <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                        <a href="{{ route('addresses.index') }}" class="text-xs text-gray-400 hover:text-navy transition-colors">
                            Gérer mes adresses →
                        </a>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
