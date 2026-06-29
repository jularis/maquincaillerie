@extends('layouts.app')
@section('title', 'Mes commandes — Ma Quincaillerie Solaire')

@section('content')
<section class="py-10 bg-gray-50 min-h-screen">
    <div class="max-w-screen-xl mx-auto px-4">

        <div class="mb-6">
            <h1 class="text-2xl font-extrabold text-navy">Mes commandes</h1>
            <p class="text-gray-400 text-sm mt-1">Historique de tous vos achats</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            @include('account._sidebar')

            <main class="flex-1">
                @if($orders->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <p class="font-semibold text-gray-700 mb-1">Aucune commande pour le moment</p>
                    <p class="text-gray-400 text-sm mb-5">Vos futures commandes apparaîtront ici.</p>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-navy text-white font-semibold px-5 py-2.5 rounded-xl hover:bg-navy-dark transition-colors text-sm">
                        Découvrir nos produits
                    </a>
                </div>
                @else
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                    @php
                    $statusMap = [
                        'pending'    => ['label'=>'En attente',  'class'=>'bg-yellow-100 text-yellow-700'],
                        'processing' => ['label'=>'En cours',    'class'=>'bg-blue-100 text-blue-700'],
                        'shipped'    => ['label'=>'Expédiée',    'class'=>'bg-purple-100 text-purple-700'],
                        'delivered'  => ['label'=>'Livrée',      'class'=>'bg-green-100 text-green-700'],
                        'cancelled'  => ['label'=>'Annulée',     'class'=>'bg-red-100 text-red-700'],
                    ];
                    @endphp

                    {{-- Header --}}
                    <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-3 bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-wider">
                        <div class="col-span-3">Commande</div>
                        <div class="col-span-3">Date</div>
                        <div class="col-span-3">Statut</div>
                        <div class="col-span-2 text-right">Total</div>
                        <div class="col-span-1"></div>
                    </div>

                    <div class="divide-y divide-gray-50">
                        @foreach($orders as $order)
                        @php $st = $statusMap[$order->status] ?? ['label'=>$order->status,'class'=>'bg-gray-100 text-gray-600']; @endphp
                        <div class="grid grid-cols-2 md:grid-cols-12 gap-3 md:gap-4 px-6 py-5 items-center hover:bg-gray-50 transition-colors">
                            <div class="col-span-2 md:col-span-3">
                                <p class="text-sm font-bold text-navy">{{ $order->order_number }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ count($order->items_decoded) }} article{{ count($order->items_decoded) > 1 ? 's' : '' }}</p>
                            </div>
                            <div class="col-span-1 md:col-span-3 text-sm text-gray-500">
                                {{ $order->created_at->format('d/m/Y') }}<br>
                                <span class="text-xs text-gray-400">{{ $order->created_at->format('H:i') }}</span>
                            </div>
                            <div class="col-span-1 md:col-span-3">
                                <span class="inline-block text-xs font-bold px-2.5 py-1 rounded-full {{ $st['class'] }}">
                                    {{ $st['label'] }}
                                </span>
                            </div>
                            <div class="col-span-1 md:col-span-2 text-right">
                                <p class="text-sm font-extrabold text-navy">{{ number_format($order->total, 0, ',', ' ') }}</p>
                                <p class="text-xs text-gray-400">F CFA</p>
                            </div>
                            <div class="col-span-1 md:col-span-1 text-right">
                                <button x-data x-on:click="$dispatch('open-order-{{ $order->id }}')"
                                        class="text-xs font-semibold text-navy hover:text-orange transition-colors">
                                    Détails
                                </button>
                            </div>
                        </div>

                        {{-- Détails expandables --}}
                        <div x-data="{ open: false }" @open-order-{{ $order->id }}.window="open = !open">
                            <div x-show="open" x-cloak class="px-6 pb-5 bg-gray-50 border-t border-gray-100">
                                <div class="grid md:grid-cols-2 gap-6 pt-4">
                                    {{-- Articles --}}
                                    <div>
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Articles</p>
                                        <div class="space-y-2">
                                            @foreach($order->items_decoded as $item)
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-700 font-medium">{{ $item['name'] }}</span>
                                                <span class="text-gray-500">× {{ $item['quantity'] }}</span>
                                                <span class="font-semibold text-navy">{{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }} F</span>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-3 pt-3 border-t border-gray-200 flex justify-between text-sm font-extrabold text-navy">
                                            <span>Total TTC</span>
                                            <span>{{ number_format($order->total, 0, ',', ' ') }} F CFA</span>
                                        </div>
                                    </div>
                                    {{-- Livraison --}}
                                    <div>
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Livraison</p>
                                        <p class="text-sm text-gray-700 leading-relaxed">
                                            {{ $order->first_name }} {{ $order->last_name }}<br>
                                            @if($order->phone)<span class="text-gray-500">{{ $order->phone }}</span><br>@endif
                                            {{ $order->address }}<br>
                                            {{ $order->postal_code ? $order->postal_code . ' ' : '' }}{{ $order->city }}<br>
                                            <span class="text-gray-400">{{ $order->country }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
                @endif
            </main>
        </div>
    </div>
</section>
@endsection
