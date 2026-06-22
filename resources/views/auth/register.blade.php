@extends('layouts.app')

@section('title', 'Créer un compte — Ma Quincaillerie Solaire')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-6">
                <div class="w-10 h-10 bg-primary-700 rounded-xl flex items-center justify-center">
                    <span class="text-white text-xl">☀</span>
                </div>
                <span class="text-primary-800 font-bold text-xl">Ma Quincaillerie Solaire</span>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Créer votre compte</h1>
            <p class="text-gray-500 mt-1 text-sm">Rejoignez nos 50 000+ clients satisfaits</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <form action="{{ route('register') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nom complet</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="input-field @error('name') border-red-300 @enderror"
                        placeholder="Jean Dupont">
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Adresse email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="input-field @error('email') border-red-300 @enderror"
                        placeholder="votre@email.fr">
                    @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Mot de passe</label>
                    <input type="password" name="password" required
                        class="input-field @error('password') border-red-300 @enderror"
                        placeholder="8 caractères minimum">
                    @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" required
                        class="input-field" placeholder="••••••••">
                </div>
                <button type="submit" class="btn-primary w-full py-3 text-base">
                    Créer mon compte
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-gray-500">
                Déjà un compte ?
                <a href="{{ route('login') }}" class="text-primary-700 font-semibold hover:underline">Se connecter</a>
            </div>
        </div>
    </div>
</div>
@endsection
