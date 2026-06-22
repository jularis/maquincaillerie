@extends('layouts.app')

@section('title', 'Mes adresses de livraison — Ma Quincaillerie Solaire')

@section('content')
<section class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-screen-lg mx-auto px-4">

        {{-- En-tête --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-extrabold text-navy">Mes adresses de livraison</h1>
                <p class="text-gray-500 text-sm mt-1">Connecté en tant que <span class="font-semibold text-navy">{{ auth()->user()->name }}</span></p>
            </div>
            <a href="{{ route('addresses.create') }}"
               class="inline-flex items-center gap-2 bg-navy text-white font-semibold px-4 py-2.5 rounded-xl hover:bg-navy-dark transition-colors text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Ajouter une adresse
            </a>
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
        <div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-medium">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- Aucune adresse --}}
        @if($addresses->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <p class="font-semibold text-gray-700 mb-1">Aucune adresse enregistrée</p>
            <p class="text-gray-400 text-sm mb-5">Ajoutez une adresse pour accélérer vos prochaines commandes.</p>
            <a href="{{ route('addresses.create') }}" class="inline-flex items-center gap-2 bg-navy text-white font-semibold px-5 py-2.5 rounded-xl hover:bg-navy-dark transition-colors text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Ajouter ma première adresse
            </a>
        </div>
        @else

        {{-- Grille d'adresses --}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($addresses as $addr)
            <div class="bg-white rounded-2xl border-2 {{ $addr->is_default ? 'border-navy' : 'border-gray-200' }} p-5 flex flex-col relative">

                {{-- Badge par défaut --}}
                @if($addr->is_default)
                <span class="absolute top-4 right-4 text-xs font-bold bg-navy text-white px-2 py-0.5 rounded-full">Par défaut</span>
                @endif

                {{-- Label --}}
                @if($addr->label)
                <div class="text-xs font-bold text-orange uppercase tracking-widest mb-2">{{ $addr->label }}</div>
                @endif

                {{-- Infos --}}
                <div class="flex-1 space-y-1 text-sm text-gray-700">
                    <p class="font-bold text-navy text-base">{{ $addr->full_name }}</p>
                    @if($addr->phone)
                    <p class="text-gray-500">{{ $addr->phone }}</p>
                    @endif
                    <p>{{ $addr->address }}</p>
                    <p>{{ $addr->postal_code ? $addr->postal_code . ' ' : '' }}{{ $addr->city }}</p>
                    <p class="text-gray-400 text-xs">{{ $addr->country }}</p>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('addresses.edit', $addr) }}"
                       class="flex-1 text-center py-2 text-xs font-semibold text-navy border border-navy/25 rounded-lg hover:bg-navy hover:text-white transition-colors">
                        Modifier
                    </a>
                    @if(!$addr->is_default)
                    <form action="{{ route('addresses.setDefault', $addr) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full py-2 text-xs font-semibold text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            Définir par défaut
                        </button>
                    </form>
                    @endif
                    <form action="{{ route('addresses.destroy', $addr) }}" method="POST"
                          onsubmit="return confirm('Supprimer cette adresse ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Retour accueil --}}
        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-navy transition-colors">← Retour à l'accueil</a>
        </div>
    </div>
</section>
@endsection
