@extends('layouts.app')

@section('title', 'Commande confirmée — Ma Quincaillerie Solaire')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-16 text-center">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10">
        <div class="text-7xl mb-6">🎉</div>
        <h1 class="text-3xl font-bold text-gray-900 mb-3">Commande confirmée !</h1>
        <p class="text-gray-500 mb-6">Merci {{ $order->first_name }} ! Votre commande a bien été enregistrée.</p>

        <div class="bg-primary-50 rounded-xl p-5 mb-8 text-left">
            <div class="text-sm font-semibold text-primary-800 mb-3">📋 Détails de la commande</div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Numéro de commande</span>
                    <span class="font-bold text-primary-700">{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Email de confirmation</span>
                    <span class="font-medium">{{ $order->email }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Total TTC</span>
                    <span class="font-bold text-gray-800">{{ fcfa($order->total) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Paiement</span>
                    <span class="font-medium">
                        @php
                            echo match($order->payment_method) {
                                'cod'      => '🏪 Paiement espèce en magasin',
                                'orange_money' => '🟠 Orange Money',
                                'wave'         => '🌊 Wave',
                                'mtn_money'    => '🟡 MTN Money',
                                'transfer' => '🏦 Virement bancaire',
                                'check'    => '📝 Chèque',
                                default    => e($order->payment_method),
                            };
                        @endphp
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Livraison à</span>
                    <span class="font-medium">{{ $order->address }}, {{ $order->postal_code }} {{ $order->city }}</span>
                </div>
            </div>
        </div>

        @if($order->payment_method === 'cod')
        <div class="bg-amber-50 rounded-xl p-4 mb-8 text-sm text-amber-700 border border-amber-200">
            🏪 Votre commande a été bien enregistrée. Vous paierez <strong>{{ fcfa($order->total) }}</strong> en espèces en magasin.<br>
            Livraison sous <strong>24 heures ouvrées</strong>.
        </div>
        @elseif($order->payment_method === 'transfer')
        <div class="bg-blue-50 rounded-xl p-4 mb-8 text-sm text-blue-700 border border-blue-200">
            🏦 <strong>Virement bancaire — merci d'effectuer le virement dans les 48h pour valider votre commande.</strong><br><br>
            <span class="font-medium">Bénéficiaire :</span> CLEAN ENERGY SERVICES<br>
            <span class="font-medium">RIB :</span> <span class="font-mono tracking-wide">CI93 CI12 1013 0603 2173 1002 0104</span><br>
            <span class="text-xs text-blue-500 mt-1 block">Indiquez votre numéro de commande <strong>{{ $order->order_number }}</strong> en référence du virement.</span>
        </div>
        @elseif(in_array($order->payment_method, ['orange_money', 'wave', 'mtn_money']))
        @php
            $mobileLabel = match($order->payment_method) {
                'orange_money' => '🟠 Orange Money — ' . setting('site.orange_money_number'),
                'wave'         => '🌊 Wave — ' . setting('site.wave_number'),
                'mtn_money'    => '🟡 MTN Money — ' . setting('site.mtn_money_number'),
            };
        @endphp
        <div class="bg-yellow-50 rounded-xl p-4 mb-8 text-sm text-yellow-800 border border-yellow-200">
            📱 <strong>Paiement Mobile — {{ $mobileLabel }}</strong><br>
            <span class="text-xs text-yellow-700 mt-1 block">Effectuez le paiement et envoyez la capture d'écran à <strong>{{ setting('site.email') }}</strong> avec la référence <strong>{{ $order->order_number }}</strong>.</span>
        </div>
        @elseif($order->payment_method === 'check')
        <div class="bg-gray-50 rounded-xl p-4 mb-8 text-sm text-gray-700 border border-gray-200">
            📝 <strong>Chèque à l'ordre de : CLEAN ENERGY SERVICES</strong><br>
            <span class="text-xs text-gray-500 mt-1 block">Merci d'indiquer votre numéro de commande <strong>{{ $order->order_number }}</strong> au dos du chèque.</span>
        </div>
        @else
        <div class="bg-green-50 rounded-xl p-4 mb-8 text-sm text-green-700">
            ✅ Un email de confirmation a été envoyé à <strong>{{ $order->email }}</strong>.<br>
            Votre commande sera expédiée sous <strong>24 heures ouvrées</strong>.
        </div>
        @endif

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}" class="btn-secondary">← Retour à l'accueil</a>
            <a href="{{ route('products.index') }}" class="btn-primary">Continuer mes achats</a>
        </div>

        <div class="mt-8 text-sm text-gray-400">
            Une question ? <a href="tel:0755539417" class="text-primary-600 hover:underline">📞 07 55 53 94 17</a>
        </div>
    </div>
</div>
@endsection
