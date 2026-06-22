@extends('layouts.app')
@section('title', 'Mon profil — Ma Quincaillerie Solaire')

@section('content')
<section class="py-10 bg-gray-50 min-h-screen">
    <div class="max-w-screen-xl mx-auto px-4">

        <div class="mb-6">
            <h1 class="text-2xl font-extrabold text-navy">Mon profil</h1>
            <p class="text-gray-400 text-sm mt-1">Gérez vos informations personnelles</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            @include('account._sidebar')

            <main class="flex-1 space-y-6">

                {{-- Flash messages --}}
                @if(session('success'))
                <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-medium">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
                @endif
                @if(session('success_password'))
                <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-medium">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success_password') }}
                </div>
                @endif

                {{-- Infos personnelles --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-6">
                    <h2 class="font-bold text-gray-800 text-lg mb-5 flex items-center gap-2">
                        <svg class="w-5 h-5 text-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Informations personnelles
                    </h2>
                    <form action="{{ route('account.profile.update') }}" method="POST" class="space-y-4">
                        @csrf @method('PUT')
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nom complet <span class="text-red-400">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                       class="w-full px-4 py-2.5 border-2 {{ $errors->has('name') ? 'border-red-400' : 'border-gray-200' }} rounded-xl text-sm focus:border-navy focus:outline-none transition-colors" required>
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Adresse email <span class="text-red-400">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                       class="w-full px-4 py-2.5 border-2 {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }} rounded-xl text-sm focus:border-navy focus:outline-none transition-colors" required>
                                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="inline-flex items-center gap-2 bg-navy text-white font-semibold px-5 py-2.5 rounded-xl hover:bg-navy-dark transition-colors text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Mot de passe --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-6">
                    <h2 class="font-bold text-gray-800 text-lg mb-5 flex items-center gap-2">
                        <svg class="w-5 h-5 text-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Changer le mot de passe
                    </h2>
                    <form action="{{ route('account.password.update') }}" method="POST" class="space-y-4">
                        @csrf @method('PUT')
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Mot de passe actuel <span class="text-red-400">*</span></label>
                            <input type="password" name="current_password"
                                   class="w-full px-4 py-2.5 border-2 {{ $errors->has('current_password') ? 'border-red-400' : 'border-gray-200' }} rounded-xl text-sm focus:border-navy focus:outline-none transition-colors" required>
                            @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nouveau mot de passe <span class="text-red-400">*</span></label>
                                <input type="password" name="password"
                                       class="w-full px-4 py-2.5 border-2 {{ $errors->has('password') ? 'border-red-400' : 'border-gray-200' }} rounded-xl text-sm focus:border-navy focus:outline-none transition-colors" required>
                                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirmer le mot de passe <span class="text-red-400">*</span></label>
                                <input type="password" name="password_confirmation"
                                       class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:border-navy focus:outline-none transition-colors" required>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400">Le mot de passe doit contenir au moins 8 caractères.</p>
                        <div class="pt-1">
                            <button type="submit" class="inline-flex items-center gap-2 bg-navy text-white font-semibold px-5 py-2.5 rounded-xl hover:bg-navy-dark transition-colors text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                Modifier le mot de passe
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Infos compte --}}
                <div class="bg-gray-100 rounded-2xl p-5 text-sm text-gray-500">
                    <p>Compte créé le <strong class="text-gray-700">{{ $user->created_at->format('d/m/Y') }}</strong></p>
                </div>

            </main>
        </div>
    </div>
</section>
@endsection
