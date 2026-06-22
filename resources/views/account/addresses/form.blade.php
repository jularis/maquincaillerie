@extends('layouts.app')

@section('title', ($address->exists ? 'Modifier' : 'Ajouter') . ' une adresse — Ma Quincaillerie Solaire')

@section('content')
<section class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-xl mx-auto px-4">

        {{-- En-tête --}}
        <div class="mb-8">
            <a href="{{ route('addresses.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-navy transition-colors mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Mes adresses
            </a>
            <h1 class="text-2xl font-extrabold text-navy">
                {{ $address->exists ? 'Modifier l\'adresse' : 'Ajouter une adresse' }}
            </h1>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8">
            <form action="{{ $address->exists ? route('addresses.update', $address) : route('addresses.store') }}"
                  method="POST" class="space-y-5">
                @csrf
                @if($address->exists) @method('PUT') @endif

                {{-- Label / surnom --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Étiquette <span class="text-gray-400 font-normal">(optionnel)</span></label>
                    <input type="text" name="label" value="{{ old('label', $address->label) }}"
                           placeholder="Ex : Maison, Bureau, Entrepôt…"
                           class="w-full px-4 py-2.5 border-2 {{ $errors->has('label') ? 'border-red-400' : 'border-gray-200' }} rounded-xl text-sm focus:border-navy focus:outline-none transition-colors">
                    @error('label') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Nom / Prénom --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Prénom <span class="text-red-400">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name', $address->first_name) }}"
                               class="w-full px-4 py-2.5 border-2 {{ $errors->has('first_name') ? 'border-red-400' : 'border-gray-200' }} rounded-xl text-sm focus:border-navy focus:outline-none transition-colors">
                        @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nom <span class="text-red-400">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name', $address->last_name) }}"
                               class="w-full px-4 py-2.5 border-2 {{ $errors->has('last_name') ? 'border-red-400' : 'border-gray-200' }} rounded-xl text-sm focus:border-navy focus:outline-none transition-colors">
                        @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Téléphone --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Téléphone <span class="text-gray-400 font-normal">(optionnel)</span></label>
                    <input type="text" name="phone" value="{{ old('phone', $address->phone) }}"
                           placeholder="+225 07 00 00 00 00"
                           class="w-full px-4 py-2.5 border-2 {{ $errors->has('phone') ? 'border-red-400' : 'border-gray-200' }} rounded-xl text-sm focus:border-navy focus:outline-none transition-colors">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Adresse --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Adresse <span class="text-red-400">*</span></label>
                    <input type="text" name="address" value="{{ old('address', $address->address) }}"
                           placeholder="Rue, quartier, numéro…"
                           class="w-full px-4 py-2.5 border-2 {{ $errors->has('address') ? 'border-red-400' : 'border-gray-200' }} rounded-xl text-sm focus:border-navy focus:outline-none transition-colors">
                    @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Ville / Code postal --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ville <span class="text-red-400">*</span></label>
                        <input type="text" name="city" value="{{ old('city', $address->city) }}"
                               class="w-full px-4 py-2.5 border-2 {{ $errors->has('city') ? 'border-red-400' : 'border-gray-200' }} rounded-xl text-sm focus:border-navy focus:outline-none transition-colors">
                        @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Code postal <span class="text-gray-400 font-normal">(optionnel)</span></label>
                        <input type="text" name="postal_code" value="{{ old('postal_code', $address->postal_code) }}"
                               class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:border-navy focus:outline-none transition-colors">
                    </div>
                </div>

                {{-- Pays --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pays <span class="text-red-400">*</span></label>
                    <select name="country"
                            class="w-full px-4 py-2.5 border-2 {{ $errors->has('country') ? 'border-red-400' : 'border-gray-200' }} rounded-xl text-sm focus:border-navy focus:outline-none transition-colors bg-white">
                        @php
                        $countries = [
                            'CI' => 'Côte d\'Ivoire',
                            'SN' => 'Sénégal',
                            'ML' => 'Mali',
                            'BF' => 'Burkina Faso',
                            'GN' => 'Guinée',
                            'GH' => 'Ghana',
                            'TG' => 'Togo',
                            'BJ' => 'Bénin',
                            'NE' => 'Niger',
                            'CM' => 'Cameroun',
                            'FR' => 'France',
                        ];
                        $selected = old('country', $address->country ?: 'CI');
                        @endphp
                        @foreach($countries as $code => $name)
                        <option value="{{ $code }}" {{ $selected === $code ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('country') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Adresse par défaut --}}
                <div class="flex items-center gap-3 bg-gray-50 rounded-xl px-4 py-3">
                    <input type="checkbox" name="is_default" id="is_default" value="1"
                           {{ old('is_default', $address->is_default) ? 'checked' : '' }}
                           class="w-4 h-4 text-navy border-gray-300 rounded focus:ring-navy">
                    <label for="is_default" class="text-sm font-medium text-gray-700 cursor-pointer">
                        Définir comme adresse de livraison par défaut
                    </label>
                </div>

                {{-- Boutons --}}
                <div class="flex items-center gap-3 pt-2">
                    <a href="{{ route('addresses.index') }}"
                       class="flex-1 text-center py-3 border-2 border-gray-200 text-gray-600 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-sm">
                        Annuler
                    </a>
                    <button type="submit"
                            class="flex-1 py-3 bg-navy text-white font-semibold rounded-xl hover:bg-navy-dark transition-colors text-sm">
                        {{ $address->exists ? 'Enregistrer les modifications' : 'Ajouter l\'adresse' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
