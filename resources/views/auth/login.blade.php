@extends('layouts.app')

@section('title', 'Connexion — Ma Quincaillerie Solaire')

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
            <h1 class="text-2xl font-bold text-gray-900">Connexion à votre compte</h1>
            <p class="text-gray-500 mt-1 text-sm">Accédez à vos commandes et préférences</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Adresse email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="input-field @error('email') border-red-300 @enderror"
                        placeholder="votre@email.fr">
                    @error('email')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-sm font-medium text-gray-700">Mot de passe</label>
                        <a href="#" class="text-xs text-primary-600 hover:underline">Mot de passe oublié ?</a>
                    </div>
                    <input type="password" name="password" required
                        class="input-field" placeholder="••••••••">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember" class="rounded text-primary-700">
                    <label for="remember" class="text-sm text-gray-600">Se souvenir de moi</label>
                </div>
                <button type="submit" class="btn-primary w-full py-3 text-base">
                    Se connecter
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-gray-500">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="text-primary-700 font-semibold hover:underline">Créer un compte</a>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            En vous connectant, vous acceptez nos <a href="#" class="underline">CGV</a> et <a href="#" class="underline">politique de confidentialité</a>
        </p>
    </div>
</div>
@endsection
