@extends('layouts.app')
@section('title', 'Mon espace — Ma Quincaillerie Solaire')

@section('content')
<section class="py-10 bg-gray-50 min-h-screen">
    <div class="max-w-screen-xl mx-auto px-4">

        <div class="mb-6">
            <h1 class="text-2xl font-extrabold text-navy">Mon espace</h1>
            <p class="text-gray-400 text-sm mt-1">Bienvenue, <span class="font-semibold text-gray-600">{{ $user->name }}</span> 👋</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">

            @include('account._sidebar')

            <main class="flex-1 space-y-8">

                {{-- Cartes statistiques --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white rounded-2xl border border-gray-200 p-5">
                        <div class="w-10 h-10 bg-navy/10 rounded-xl flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <div class="text-2xl font-extrabold text-navy">{{ $ordersCount }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">Commande{{ $ordersCount > 1 ? 's' : '' }}</div>
                    </div>
                    <div class="bg-white rounded-2xl border border-gray-200 p-5">
                        <div class="w-10 h-10 bg-orange/10 rounded-xl flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div class="text-2xl font-extrabold text-navy">{{ $addressCount }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">Adresse{{ $addressCount > 1 ? 's' : '' }} sauvegardée{{ $addressCount > 1 ? 's' : '' }}</div>
                    </div>
                    <div class="bg-white rounded-2xl border border-gray-200 p-5">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="text-2xl font-extrabold text-navy">
                            {{ $user->orders()->where('status', 'delivered')->count() }}
                        </div>
                        <div class="text-xs text-gray-500 mt-0.5">Livraison{{ $user->orders()->where('status','delivered')->count() > 1 ? 's' : '' }} effectuée{{ $user->orders()->where('status','delivered')->count() > 1 ? 's' : '' }}</div>
                    </div>
                    <div class="bg-white rounded-2xl border border-gray-200 p-5">
                        <div class="w-10 h-10 bg-solar/10 rounded-xl flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-solar" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="text-2xl font-extrabold text-navy">
                            {{ number_format($user->orders()->sum('total'), 0, ',', ' ') }}
                        </div>
                        <div class="text-xs text-gray-500 mt-0.5">F CFA dépensés</div>
                    </div>
                </div>

                {{-- Rubriques accès rapide --}}
                <div class="grid sm:grid-cols-3 gap-4">
                    <a href="{{ route('account.orders') }}"
                       class="group bg-white rounded-2xl border border-gray-200 p-6 hover:border-navy hover:shadow-card-hover transition-all">
                        <div class="w-12 h-12 bg-navy/5 group-hover:bg-navy rounded-2xl flex items-center justify-center mb-4 transition-colors">
                            <svg class="w-6 h-6 text-navy group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </div>
                        <h3 class="font-bold text-navy mb-1">Mes commandes</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">Suivez l'état de vos commandes et consultez votre historique d'achats.</p>
                        <div class="flex items-center gap-1 mt-3 text-xs font-semibold text-navy group-hover:text-orange transition-colors">
                            Voir mes commandes
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </a>

                    <a href="{{ route('addresses.index') }}"
                       class="group bg-white rounded-2xl border border-gray-200 p-6 hover:border-orange hover:shadow-card-hover transition-all">
                        <div class="w-12 h-12 bg-orange/5 group-hover:bg-orange rounded-2xl flex items-center justify-center mb-4 transition-colors">
                            <svg class="w-6 h-6 text-orange group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <h3 class="font-bold text-navy mb-1">Mes adresses</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">Gérez vos adresses de livraison pour passer commande plus rapidement.</p>
                        <div class="flex items-center gap-1 mt-3 text-xs font-semibold text-orange transition-colors">
                            Gérer mes adresses
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </a>

                    <a href="{{ route('account.profile') }}"
                       class="group bg-white rounded-2xl border border-gray-200 p-6 hover:border-solar hover:shadow-card-hover transition-all">
                        <div class="w-12 h-12 bg-solar/10 group-hover:bg-solar rounded-2xl flex items-center justify-center mb-4 transition-colors">
                            <svg class="w-6 h-6 text-solar group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <h3 class="font-bold text-navy mb-1">Mon profil</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">Modifiez vos informations personnelles et votre mot de passe.</p>
                        <div class="flex items-center gap-1 mt-3 text-xs font-semibold text-solar transition-colors">
                            Modifier mon profil
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </a>
                </div>

                {{-- Dernières commandes --}}
                @if($recentOrders->isNotEmpty())
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <h2 class="font-bold text-gray-800">Dernières commandes</h2>
                        <a href="{{ route('account.orders') }}" class="text-xs font-semibold text-navy hover:underline">Tout voir →</a>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @foreach($recentOrders as $order)
                        @php
                        $statusMap = [
                            'pending'    => ['label'=>'En attente',  'class'=>'bg-yellow-100 text-yellow-700'],
                            'processing' => ['label'=>'En cours',    'class'=>'bg-blue-100 text-blue-700'],
                            'shipped'    => ['label'=>'Expédiée',    'class'=>'bg-purple-100 text-purple-700'],
                            'delivered'  => ['label'=>'Livrée',      'class'=>'bg-green-100 text-green-700'],
                            'cancelled'  => ['label'=>'Annulée',     'class'=>'bg-red-100 text-red-700'],
                        ];
                        $st = $statusMap[$order->status] ?? ['label'=>$order->status, 'class'=>'bg-gray-100 text-gray-600'];
                        @endphp
                        <div class="flex items-center justify-between px-6 py-4">
                            <div>
                                <p class="text-sm font-bold text-navy">{{ $order->order_number }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->format('d/m/Y') }} · {{ count($order->items_decoded) }} article{{ count($order->items_decoded) > 1 ? 's' : '' }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-block text-xs font-bold px-2.5 py-1 rounded-full {{ $st['class'] }} mb-1">{{ $st['label'] }}</span>
                                <p class="text-sm font-extrabold text-navy">{{ number_format($order->total, 0, ',', ' ') }} F CFA</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </main>
        </div>
    </div>
</section>
@endsection
