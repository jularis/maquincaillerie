@extends('layouts.app')

@section('title', 'Ma Quincaillerie Solaire — Experts photovoltaïque et stockage solaire depuis 2011')
@section('meta_description', 'Réalisez votre propre installation solaire avec ou sans stockage. 50 000+ clients satisfaits. Panneaux solaires, onduleurs, batteries, kits clés en main.')

@section('content')

{{-- ====================================================
     SECTION 1 : HERO BANNER
     ================================================== --}}
<section class="bg-gradient-to-br from-navy via-navy-light to-[#2a4890] text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-0 right-0 w-1/2 h-full" style="background: radial-gradient(ellipse at 80% 20%, #f59e0b 0%, transparent 60%)"></div>
    </div>

    <div class="max-w-screen-xl mx-auto px-4 pt-12 pb-8 relative">

        {{-- Title --}}
{{-- 2 hero cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-4xl mx-auto">

            {{-- Card 1: Expert --}}
            <div class="bg-white/10 backdrop-blur rounded-2xl p-8 text-white shadow-xl flex flex-col justify-center">
                <p class="text-3xl font-extrabold leading-tight mb-3">
                    Expert Photovoltaïque<br>et stockage depuis 2019
                </p>
                <p class="text-4xl font-extrabold text-solar">
                    15 000+ clients satisfaits
                </p>
            </div>

            {{-- Card 2: Configurateur avancé --}}
            <div class="bg-orange rounded-2xl p-6 text-white shadow-xl flex flex-col">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <div class="font-bold">Configurateur</div>
                        <div class="text-xs text-orange-100">Kit sur mesure avancé</div>
                    </div>
                </div>
                <p class="text-sm text-orange-100 leading-relaxed mb-5 flex-1">
                    Devenez un expert du solaire ! Configurez votre kit sur mesure (marque, puissance, etc.) et recevez-le instantanément.
                </p>
                <a href="{{ route('configurateur') }}"
                   class="w-full text-center py-3 bg-white text-orange font-semibold rounded-xl hover:bg-orange-50 transition-colors text-sm">
                    🔆 Sélectionnez un kit solaire
                </a>
            </div>
        </div>

        {{-- Bottom links --}}
        <div class="flex flex-wrap items-center justify-center gap-6 mt-8 text-sm text-blue-200">
            <a href="{{ route('configurateur') }}" class="flex items-center gap-1.5 hover:text-white transition-colors font-medium">
                📦 Configurateur de kit solaire
            </a>
            <span class="text-blue-400">|</span>
            <a href="{{ route('products.index', ['category' => 'batteries']) }}" class="flex items-center gap-1.5 hover:text-white transition-colors font-medium">
                🔋 Batteries de stockage
            </a>
            <span class="text-blue-400">|</span>
            <a href="#contact" class="flex items-center gap-1.5 hover:text-white transition-colors font-medium">
                💬 Échanger gratuitement avec nos experts
            </a>
        </div>
    </div>
</section>


{{-- ====================================================
     SECTION 3 : CATÉGORIES DE PRODUITS
     ================================================== --}}
<section class="py-14 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="text-center mb-10">
            <h2 class="section-title">Nos catégories de produits solaires</h2>
            <p class="section-subtitle">Découvrez notre gamme complète de matériel photovoltaïque professionnel</p>
        </div>

        @php
        $catData = [
            'kits-solaires' => [
                'label' => 'KITS SOLAIRES',
                'icon_svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9zM9 22V12h6v10"/>',
                'product_svg' => '
                <svg viewBox="0 0 150 110" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-40 group-hover:scale-105 transition-transform duration-300 drop-shadow-xl">
                  <rect x="2" y="6" width="56" height="36" rx="3" stroke="white" stroke-width="2" fill="rgba(255,255,255,0.04)"/>
                  <line x1="2" y1="18" x2="58" y2="18" stroke="rgba(255,255,255,0.5)" stroke-width="1"/>
                  <line x1="2" y1="30" x2="58" y2="30" stroke="rgba(255,255,255,0.5)" stroke-width="1"/>
                  <line x1="20" y1="6" x2="20" y2="42" stroke="rgba(255,255,255,0.5)" stroke-width="1"/>
                  <line x1="39" y1="6" x2="39" y2="42" stroke="rgba(255,255,255,0.5)" stroke-width="1"/>
                  <rect x="62" y="6" width="56" height="36" rx="3" stroke="white" stroke-width="2" fill="rgba(255,255,255,0.04)"/>
                  <line x1="62" y1="18" x2="118" y2="18" stroke="rgba(255,255,255,0.5)" stroke-width="1"/>
                  <line x1="62" y1="30" x2="118" y2="30" stroke="rgba(255,255,255,0.5)" stroke-width="1"/>
                  <line x1="80" y1="6" x2="80" y2="42" stroke="rgba(255,255,255,0.5)" stroke-width="1"/>
                  <line x1="99" y1="6" x2="99" y2="42" stroke="rgba(255,255,255,0.5)" stroke-width="1"/>
                  <rect x="122" y="6" width="26" height="36" rx="3" stroke="white" stroke-width="2" fill="rgba(255,255,255,0.04)"/>
                  <line x1="122" y1="18" x2="148" y2="18" stroke="rgba(255,255,255,0.5)" stroke-width="1"/>
                  <line x1="122" y1="30" x2="148" y2="30" stroke="rgba(255,255,255,0.5)" stroke-width="1"/>
                  <rect x="44" y="60" width="62" height="44" rx="6" stroke="white" stroke-width="2" fill="rgba(255,255,255,0.05)"/>
                  <rect x="52" y="68" width="46" height="20" rx="3" stroke="#fbbf24" stroke-width="1.5" fill="rgba(251,191,36,0.1)"/>
                  <polyline points="56,78 62,70 68,78 74,70 80,78 86,70 92,78" stroke="#fbbf24" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                  <line x1="75" y1="42" x2="75" y2="60" stroke="rgba(255,255,255,0.5)" stroke-width="1.5" stroke-dasharray="3 2"/>
                </svg>',
            ],
            'panneaux-solaires' => [
                'label' => 'PANNEAUX SOLAIRES',
                'icon_svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7h18M3 12h18M3 17h18M7 3v18M12 3v18M17 3v18"/>',
                'product_svg' => '
                <svg viewBox="0 0 140 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-40 group-hover:scale-105 transition-transform duration-300 drop-shadow-xl">
                  <rect x="4" y="8" width="132" height="84" rx="4" stroke="white" stroke-width="2.5" fill="rgba(255,255,255,0.04)"/>
                  <line x1="4" y1="36" x2="136" y2="36" stroke="rgba(255,255,255,0.6)" stroke-width="1.5"/>
                  <line x1="4" y1="64" x2="136" y2="64" stroke="rgba(255,255,255,0.6)" stroke-width="1.5"/>
                  <line x1="48" y1="8" x2="48" y2="92" stroke="rgba(255,255,255,0.6)" stroke-width="1.5"/>
                  <line x1="92" y1="8" x2="92" y2="92" stroke="rgba(255,255,255,0.6)" stroke-width="1.5"/>
                  <rect x="6" y="10" width="40" height="24" fill="rgba(251,191,36,0.18)" rx="1"/>
                  <rect x="50" y="38" width="40" height="24" fill="rgba(251,191,36,0.18)" rx="1"/>
                  <rect x="94" y="66" width="40" height="24" fill="rgba(251,191,36,0.18)" rx="1"/>
                  <rect x="58" y="92" width="24" height="6" rx="2" fill="rgba(255,255,255,0.7)"/>
                  <line x1="70" y1="98" x2="70" y2="104" stroke="rgba(255,255,255,0.7)" stroke-width="2"/>
                </svg>',
            ],
            'batteries' => [
                'label' => 'BATTERIES SOLAIRES',
                'icon_svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 2h-2v2H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2h-4V2zm-1 8v4m-2-2h4"/>',
                'product_svg' => '
                <svg viewBox="0 0 80 130" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-16 h-28 group-hover:scale-105 transition-transform duration-300 drop-shadow-xl">
                  <rect x="20" y="2" width="40" height="8" rx="3" fill="rgba(255,255,255,0.7)"/>
                  <rect x="4" y="10" width="72" height="116" rx="8" stroke="white" stroke-width="2.5" fill="rgba(255,255,255,0.04)"/>
                  <rect x="14" y="22" width="52" height="10" rx="3" fill="rgba(251,191,36,0.5)" stroke="#fbbf24" stroke-width="1"/>
                  <rect x="14" y="38" width="52" height="10" rx="3" fill="rgba(251,191,36,0.35)" stroke="#fbbf24" stroke-width="1"/>
                  <rect x="14" y="54" width="52" height="10" rx="3" fill="rgba(251,191,36,0.2)" stroke="rgba(251,191,36,0.5)" stroke-width="1"/>
                  <line x1="34" y1="82" x2="34" y2="106" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
                  <line x1="24" y1="94" x2="44" y2="94" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
                  <line x1="54" y1="94" x2="66" y2="94" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
                </svg>',
            ],
            'onduleurs' => [
                'label' => 'ONDULEURS SOLAIRES',
                'icon_svg' => '<rect x="3" y="6" width="18" height="12" rx="2" stroke-width="1.5"/><path stroke-linecap="round" stroke-width="1.5" d="M8 12h1l1.5-3 2 6 1.5-3H16"/>',
                'product_svg' => '
                <svg viewBox="0 0 90 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-20 h-28 group-hover:scale-105 transition-transform duration-300 drop-shadow-xl">
                  <rect x="5" y="4" width="80" height="100" rx="8" stroke="white" stroke-width="2.5" fill="rgba(255,255,255,0.04)"/>
                  <rect x="14" y="14" width="62" height="32" rx="4" stroke="#fbbf24" stroke-width="1.5" fill="rgba(251,191,36,0.08)"/>
                  <polyline points="19,30 27,20 35,30 43,20 51,30 59,20 67,30 75,20" stroke="#fbbf24" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                  <line x1="14" y1="58" x2="76" y2="58" stroke="rgba(255,255,255,0.35)" stroke-width="1.2"/>
                  <line x1="14" y1="66" x2="76" y2="66" stroke="rgba(255,255,255,0.35)" stroke-width="1.2"/>
                  <line x1="14" y1="74" x2="76" y2="74" stroke="rgba(255,255,255,0.35)" stroke-width="1.2"/>
                  <line x1="14" y1="82" x2="76" y2="82" stroke="rgba(255,255,255,0.35)" stroke-width="1.2"/>
                  <circle cx="25" cy="96" r="6" stroke="white" stroke-width="1.8"/>
                  <circle cx="45" cy="96" r="6" stroke="white" stroke-width="1.8"/>
                  <circle cx="65" cy="96" r="6" stroke="white" stroke-width="1.8"/>
                </svg>',
            ],
            'accessoires' => [
                'label' => 'ACCESSOIRES',
                'icon_svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
                'product_svg' => '
                <svg viewBox="0 0 120 110" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-40 group-hover:scale-105 transition-transform duration-300 drop-shadow-xl">
                  <path d="M18 28 Q18 14 28 12 Q38 10 40 20 L42 22 L90 70 L92 72 Q102 74 104 82 Q106 92 96 94 Q86 96 82 88 L80 86 L32 38 L30 36 Q20 38 18 28Z" stroke="white" stroke-width="2.5" fill="rgba(255,255,255,0.06)" stroke-linejoin="round"/>
                  <circle cx="28" cy="22" r="6" stroke="#fbbf24" stroke-width="2" fill="rgba(251,191,36,0.15)"/>
                  <circle cx="92" cy="86" r="6" stroke="#fbbf24" stroke-width="2" fill="rgba(251,191,36,0.15)"/>
                  <line x1="100" y1="14" x2="38" y2="80" stroke="rgba(255,255,255,0.7)" stroke-width="3" stroke-linecap="round"/>
                  <line x1="36" y1="80" x2="30" y2="90" stroke="#fbbf24" stroke-width="3" stroke-linecap="round"/>
                </svg>',
            ],
            'bornes-recharge' => [
                'label' => 'BORNES DE RECHARGE',
                'icon_svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h8a2 2 0 002-2V6l-3-4zm0 10v4m-3-2h6"/>',
                'product_svg' => '
                <svg viewBox="0 0 80 130" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-16 h-28 group-hover:scale-105 transition-transform duration-300 drop-shadow-xl">
                  <rect x="10" y="4" width="60" height="110" rx="10" stroke="white" stroke-width="2.5" fill="rgba(255,255,255,0.04)"/>
                  <rect x="20" y="16" width="40" height="28" rx="4" stroke="#fbbf24" stroke-width="1.5" fill="rgba(251,191,36,0.1)"/>
                  <circle cx="40" cy="30" r="8" stroke="#fbbf24" stroke-width="1.5" fill="rgba(251,191,36,0.15)"/>
                  <path d="M40 24v6l4 4" stroke="#fbbf24" stroke-width="1.5" stroke-linecap="round"/>
                  <rect x="28" y="56" width="24" height="6" rx="3" fill="rgba(255,255,255,0.5)"/>
                  <line x1="40" y1="62" x2="40" y2="80" stroke="rgba(255,255,255,0.6)" stroke-width="2.5" stroke-linecap="round"/>
                  <path d="M30 80 Q40 90 50 80" stroke="rgba(255,255,255,0.6)" stroke-width="2" fill="none" stroke-linecap="round"/>
                  <rect x="22" y="90" width="36" height="18" rx="4" stroke="white" stroke-width="1.5" fill="rgba(255,255,255,0.06)"/>
                  <line x1="22" y1="98" x2="58" y2="98" stroke="rgba(255,255,255,0.4)" stroke-width="1"/>
                </svg>',
            ],
        ];
        @endphp

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($categories as $category)
            @php $d = $catData[$category->slug] ?? null; @endphp
            @if($d)
            <a href="{{ route('products.index', ['category' => $category->slug]) }}"
               class="group relative flex flex-col overflow-hidden rounded-2xl cursor-pointer"
               style="background: linear-gradient(160deg, #0f2d5e 0%, #071a3e 60%, #0a2347 100%); min-height: 220px;">
                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"
                     style="background: radial-gradient(circle at 50% 20%, rgba(251,191,36,0.14) 0%, transparent 65%)"></div>
                <div class="relative z-10 flex flex-col items-center pt-6 px-3">
                    <p class="text-white font-black text-lg uppercase tracking-widest text-center leading-tight mb-4">{{ $d['label'] }}</p>
                    <svg class="w-10 h-10 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $d['icon_svg'] !!}</svg>
                </div>
                <div class="relative z-10 flex-1 flex items-center justify-center px-3 pb-3">
                    {!! $d['product_svg'] !!}
                </div>
                <div class="absolute inset-0 rounded-2xl border-2 border-transparent group-hover:border-yellow-400 transition-colors duration-300 pointer-events-none"></div>
            </a>
            @endif
            @endforeach
        </div>
    </div>
</section>


{{-- ====================================================
     SECTION 4 : PRODUITS EN VEDETTE
     ================================================== --}}
<section class="py-14 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-10">
            <div>
                <h2 class="section-title">Nos experts du solaire vous préconisent</h2>
                <p class="section-subtitle">Sélectionnés pour leurs performances et leur rapport qualité / prix</p>
            </div>
            <a href="{{ route('products.index') }}"
               class="shrink-0 inline-flex items-center gap-1.5 text-sm font-semibold text-navy border border-navy/25 px-4 py-2 rounded-xl hover:bg-navy hover:text-white transition-all">
                Voir tout
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        {{-- Grid --}}
        @if($featuredProducts->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
        @else
        <div class="text-center py-16 text-gray-400">
            <div class="text-5xl mb-4">☀️</div>
            <p class="font-medium">Aucun produit en vedette pour le moment.</p>
        </div>
        @endif

        {{-- CTA mobile --}}
        <div class="text-center mt-8 sm:hidden">
            <a href="{{ route('products.index') }}" class="btn-outline">Voir tous les produits →</a>
        </div>

    </div>
</section>

{{-- ====================================================
     SECTION 5 : TEXTE / EXPERTISE (comme Alma Solar)
     ================================================== --}}
<section class="py-14 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="grid lg:grid-cols-2 gap-12 items-start">
            <div>
                <h2 class="section-title mb-5">Comment réaliser son installation solaire ?</h2>
                <div class="prose prose-sm text-gray-600 space-y-4">
                    <p>Le panneau solaire est la solution pour produire son électricité gratuitement. Notre site en ligne vous offre la possibilité de réaliser votre installation solaire. Que vous soyez particulier ou professionnel, vous obtenez l'offre de prix la moins chère du marché.</p>
                    <p>Pour réaliser ce projet, vous devez commencer par mesurer votre toit. Vous avez la possibilité de le mesurer par vous-même ou bien d'utiliser Google Maps. Ce logiciel vous permet, à distance, de mesurer la longueur et largeur de votre toit.</p>
                    <p>Ensuite, vous devez choisir avec ou sans stockage. En effet, les batteries solaires offrent une solution compétitive pour atteindre le 0 F CFA sur votre facture d'électricité. Les batteries photovoltaïques sont rechargées la journée et vous les déchargez la nuit.</p>
                    <p>Pour faire le bon choix, nous pouvons vous guider sur les différents types d'onduleurs.</p>
                </div>
                <a href="{{ route('products.index') }}" class="btn-primary mt-6">
                    Découvrir nos produits →
                </a>
            </div>
            <div>
                <h2 class="section-title mb-5">Combien ça coûte pour une maison de 100m² ?</h2>
                <div class="prose prose-sm text-gray-600 space-y-4">
                    <p>Il existe différentes configurations pour installer les panneaux solaires. Une maison de 100m² ne veut pas spécialement dire 100m² de toiture disponible. Tout d'abord, vous sélectionnez l'endroit où vous souhaitez installer vos modules photovoltaïques.</p>
                    <p>Si vous êtes capable de réaliser l'installation vous-même, le tarif va diminuer d'environ 30 à 50% de son coût. Avec un installateur certifié RGE, le devis peut être plus élevé mais vous bénéficiez des aides de l'État.</p>
                    <p>Pour une toiture de 30m², le coût sera seulement de 4 000 000 F CFA. Une surface de 50m², vous serez environ à 6 500 000 F CFA. Plus vous installez de puissance solaire, plus le prix F CFA/m² sera bas.</p>
                </div>
                <a href="{{ route('products.index', ['category' => 'kits-solaires']) }}" class="btn-outline mt-6">
                    Voir les kits solaires →
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ====================================================
     SECTION 6 : FAQ
     ================================================== --}}
<section class="py-14 bg-gray-50 border-t border-gray-200">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="text-center mb-10">
            <h2 class="section-title">Questions fréquentes sur l'installation solaire</h2>
        </div>

        <div class="max-w-3xl mx-auto space-y-3" x-data="{ open: 0 }">
            @php
            $faqs = [
                [
                    'q' => 'Quel est le coût d\'une installation solaire ?',
                    'a' => 'Le prix moyen d\'une installation solaire est compris entre 1 600 000 F CFA et 2 300 000 F CFA TTC pour l\'achat du matériel seul. Cela peut augmenter de 3 300 000 F CFA à 4 000 000 F CFA TTC avec stockage. Si vous décidez de faire appel à une société d\'installation, vous allez devoir ajouter à ce prix un tarif d\'environ 1 600 000 F CFA TTC.'
                ],
                [
                    'q' => 'Est-il rentable d\'installer des panneaux solaires ?',
                    'a' => 'La réponse est « Oui » sans hésitation. Il est très rentable de réaliser son installation de panneaux photovoltaïques. Le prix moyen d\'une installation a chuté ces dernières années et le coût de l\'électricité ne fait qu\'augmenter. Une installation est rentabilisée en moins de 5 ans. Du fait de vendre en direct aux clients finaux, nous pouvons réduire cette durée à 2 ans.'
                ],
                [
                    'q' => 'Quelle puissance solaire pour alimenter une maison ?',
                    'a' => 'Les études montrent qu\'en moyenne, une maison consomme 6 500 kWh/an. Pour répondre à ce besoin, il faut environ 6 kW de panneaux solaires sur votre toit. Pour produire cette puissance, vous avez besoin d\'environ seulement 25m². Avec une telle puissance solaire, vous pouvez espérer baisser votre facture d\'énergie jusqu\'à 100%.'
                ],
                [
                    'q' => 'Combien d\'électricité produit une installation de 3 kW ?',
                    'a' => 'Pour estimer la production, nous devons connaître votre lieu géographique. Dans le sud de la France, vous pouvez produire 4 500 kWh/an pour une installation de 3 kW. À l\'inverse, dans le nord, vous pouvez produire jusqu\'à 3 000 kWh/an. Contactez-nous pour obtenir une étude personnalisée.'
                ],
                [
                    'q' => 'Quel est le taux de TVA pour une installation solaire de 6 kW ?',
                    'a' => 'Le taux de TVA en France évolue à partir du 1er Octobre 2025. L\'installation photovoltaïque de moins de 9 kW sera de 5,5% si et seulement si vous commandez le matériel chez un installateur certifié RGE et que vous réalisez un contrat de revente de surplus.'
                ],
                [
                    'q' => 'Quelle puissance dois-je installer pour être autonome ?',
                    'a' => 'Pour devenir autonome, vous allez devoir installer des panneaux solaires et une batterie solaire. Il faut connaître votre consommation d\'électricité journalière à chaque saison. En moyenne, il faut partir sur une installation solaire de 6 kW afin d\'être proche de l\'autonomie énergétique.'
                ],
                [
                    'q' => 'Quelle est la différence entre un panneau solaire et un panneau photovoltaïque ?',
                    'a' => 'Un panneau solaire regroupe 2 catégories : le panneau photovoltaïque et le panneau thermique. Le panneau photovoltaïque produit de l\'électricité grâce à ses cellules photovoltaïques tandis que le panneau thermique réchauffe un fluide utilisé pour chauffer votre eau chaude sanitaire.'
                ],
            ];
            @endphp

            @foreach($faqs as $i => $faq)
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                <button @click="open === {{ $i }} ? open = null : open = {{ $i }}"
                    class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-gray-50 transition-colors group">
                    <span class="font-semibold text-gray-800 group-hover:text-navy transition-colors text-sm md:text-base">{{ $faq['q'] }}</span>
                    <span class="ml-4 shrink-0 w-6 h-6 rounded-full border-2 border-gray-300 group-hover:border-navy flex items-center justify-center transition-all"
                          :class="open === {{ $i }} ? 'bg-navy border-navy rotate-45' : ''">
                        <svg class="w-3 h-3 transition-colors" :class="open === {{ $i }} ? 'text-white' : 'text-gray-400'"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/>
                        </svg>
                    </span>
                </button>
                <div x-show="open === {{ $i }}" x-cloak class="px-6 pb-5 text-sm text-gray-600 leading-relaxed border-t border-gray-100 pt-4">
                    {{ $faq['a'] }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ====================================================
     SECTION 7 : LAISSEZ-VOUS PORTER (5 ÉTAPES)
     ================================================== --}}
<section class="py-14 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="section-title">Laissez-vous porter</h2>
            <p class="section-subtitle">Réalisez votre installation solaire en 5 étapes simples</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 relative">
            {{-- Connecting line --}}
            <div class="hidden md:block absolute top-8 left-[10%] right-[10%] h-0.5 bg-gradient-to-r from-navy via-orange to-solar z-0"></div>

            @php
            $steps = [
                ['num'=>1, 'title'=>'Exploitez le simulateur avancé', 'desc'=>'Grâce à notre configurateur avancé, vous bénéficiez d\'un kit solaire sur mesure en fonction de la surface disponible. Notre simulateur est simple et offre une réponse immédiate.', 'icon'=>'🖥️'],
                ['num'=>2, 'title'=>'Choisissez vos panneaux solaires', 'desc'=>'Avec le configurateur avancé, vous avez le choix de vos panneaux solaires. Sélectionnez le panneau qui vous convient en fonction de son prix et de son rendement.', 'icon'=>'☀️'],
                ['num'=>3, 'title'=>'Sélectionnez vos onduleurs', 'desc'=>'Le configurateur va vous proposer uniquement les onduleurs compatibles avec la puissance des panneaux et la technologie choisie (avec ou sans batterie, mono ou triphasé).', 'icon'=>'⚡'],
                ['num'=>4, 'title'=>'Ajoutez des batteries solaires', 'desc'=>'Si vous avez activé le stockage, vous allez pouvoir sélectionner votre batterie. Le configurateur vous propose uniquement des batteries compatibles avec l\'onduleur choisi.', 'icon'=>'🔋'],
                ['num'=>5, 'title'=>'Profitez de nos installateurs', 'desc'=>'Grâce à nos partenaires certifiés RGE, nous vous proposons l\'installation de notre kit solaire. Aucun montant supplémentaire ne peut être demandé.', 'icon'=>'👷'],
            ];
            @endphp

            @foreach($steps as $step)
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-navy rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                    <span class="text-2xl">{{ $step['icon'] }}</span>
                </div>
                <div class="absolute -top-1 -right-1 w-6 h-6 bg-orange rounded-full flex items-center justify-center text-white text-xs font-bold shadow">
                    {{ $step['num'] }}
                </div>
                <h3 class="font-bold text-navy text-sm mb-2 leading-tight">#{{ $step['num'] }} — {{ $step['title'] }}</h3>
                <p class="text-xs text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('configurateur') }}" class="btn-primary text-base px-10 py-4">
                🔆 Sélectionnez un kit solaire
            </a>
        </div>
    </div>
</section>

{{-- ====================================================
     SECTION 8 : COMMENT ACHETER CHEZ NOUS (3 ÉTAPES)
     ================================================== --}}
<section class="py-14 bg-navy text-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-2xl lg:text-3xl font-bold mb-2">Comment acheter chez nous ?</h2>
            <p class="text-blue-200">Simple, rapide et sécurisé</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
            $buySteps = [
                [
                    'num' => '#1', 'title' => 'Je sélectionne',
                    'icon' => '🛍️',
                    'desc' => 'Vous trouverez nos produits solaires répertoriés par catégorie. Faites un tri par marque, puissance, type de technologie, garantie. Cliquez sur le produit pour voir le détail. Vous trouverez les manuels d\'installation et les vidéos pour l\'installer.',
                ],
                [
                    'num' => '#2', 'title' => 'Je valide ma commande',
                    'icon' => '✅',
                    'desc' => 'Sélectionnez la quantité souhaitée et ajoutez-la dans votre panier. Créez votre compte. Cliquez sur « Mon panier ». À l\'étape 3 de votre panier, vous pouvez sélectionner votre mode de paiement : PayPal, carte de crédit, Klarna, virement bancaire.',
                ],
                [
                    'num' => '#3', 'title' => 'Je reçois ma commande',
                    'icon' => '📦',
                    'desc' => 'Après validation de votre commande, notre logistique prépare votre commande. Dès l\'expédition, vous recevrez un numéro de suivi. Veuillez vérifier votre matériel à la réception afin d\'assurer la qualité de notre service.',
                ],
            ];
            @endphp

            @foreach($buySteps as $bs)
            <div class="bg-white/10 backdrop-blur rounded-2xl p-7 border border-white/20">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-orange rounded-xl flex items-center justify-center text-xl shadow">
                        {{ $bs['icon'] }}
                    </div>
                    <div>
                        <div class="text-xs font-bold text-blue-300 uppercase tracking-widest">Étape {{ $bs['num'] }}</div>
                        <div class="font-bold text-white text-base">{{ $bs['title'] }}</div>
                    </div>
                </div>
                <p class="text-blue-200 text-sm leading-relaxed">{{ $bs['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ====================================================
     SECTION 9 : LES MEILLEURS PRODUITS SOLAIRES
     ================================================== --}}
<section class="py-14 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">

        {{-- Promo blocks --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <div class="col-span-1 lg:col-span-2 bg-gradient-to-br from-amber-500 to-orange rounded-2xl p-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-48 h-48 opacity-10">
                    <div class="text-[120px]">☀️</div>
                </div>
                <div class="relative">
                    <div class="text-xs font-bold uppercase tracking-widest text-amber-200 mb-2">Meilleures ventes</div>
                    <h3 class="text-xl font-extrabold mb-2">Panneaux solaires, la garantie de la performance.</h3>
                    <p class="text-amber-100 text-sm mb-4">Monocristallins PERC haute efficacité, jusqu'à 25 ans de garantie.</p>
                    <a href="{{ route('products.index', ['category' => 'panneaux-solaires']) }}" class="inline-flex items-center gap-2 bg-white text-orange font-bold px-5 py-2.5 rounded-xl hover:bg-amber-50 transition-colors text-sm">
                        Hop, à moi →
                    </a>
                </div>
            </div>

            <div class="bg-gradient-to-br from-navy to-navy-light rounded-2xl p-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 opacity-10 text-[80px]">⚡</div>
                <div class="relative">
                    <div class="text-xs font-bold uppercase tracking-widest text-blue-300 mb-2">Top ventes</div>
                    <h3 class="text-lg font-extrabold mb-2">Onduleurs hybrides, le top pour votre maison.</h3>
                    <a href="{{ route('products.index', ['category' => 'onduleurs']) }}" class="inline-flex items-center gap-2 bg-orange text-white font-bold px-4 py-2 rounded-xl hover:bg-orange-dark transition-colors text-sm mt-2">
                        Montrez-moi ça →
                    </a>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-600 to-emerald-700 rounded-2xl p-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 opacity-10 text-[80px]">🔋</div>
                <div class="relative">
                    <div class="text-xs font-bold uppercase tracking-widest text-green-200 mb-2">Stockage</div>
                    <h3 class="text-lg font-extrabold mb-2">Les batteries solaires, le must du stockage.</h3>
                    <a href="{{ route('products.index', ['category' => 'batteries']) }}" class="inline-flex items-center gap-2 bg-white text-green-700 font-bold px-4 py-2 rounded-xl hover:bg-green-50 transition-colors text-sm mt-2">
                        C'est parti →
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

{{-- ====================================================
     SECTION 10 : DÉCOUVREZ LES COULISSES
     ================================================== --}}
<section class="py-14 bg-gray-50 border-t border-gray-200">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-xs font-bold text-orange uppercase tracking-widest">Notre vision</span>
                <h2 class="section-title mt-2 mb-5">Découvrez les coulisses de Ma Quincaillerie Solaire</h2>
                <p class="text-gray-600 leading-relaxed mb-5">
                    L'innovation au cœur de votre énergie : Entrez dans notre univers et découvrez ce qui fait que nous sommes devenus leader dans la vente en ligne de matériel photovoltaïque.
                </p>
                <p class="text-gray-600 leading-relaxed mb-6">Une stratégie qui repose sur 3 piliers :</p>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-solar/20 rounded-lg flex items-center justify-center shrink-0 text-solar font-bold">1</div>
                        <div>
                            <div class="font-semibold text-navy">Accès à l'énergie gratuite</div>
                            <p class="text-sm text-gray-500">Rendre l'énergie solaire accessible à tous, particuliers et professionnels.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-orange/20 rounded-lg flex items-center justify-center shrink-0 text-orange font-bold">2</div>
                        <div>
                            <div class="font-semibold text-navy">Gestion de votre consommation</div>
                            <p class="text-sm text-gray-500">Des outils intelligents pour optimiser et maîtriser votre production solaire.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-navy/10 rounded-lg flex items-center justify-center shrink-0 text-navy font-bold">3</div>
                        <div>
                            <div class="font-semibold text-navy">Indépendance énergétique</div>
                            <p class="text-sm text-gray-500">Devenez maître de votre consommation électrique avec nos solutions complètes.</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('products.index') }}" class="btn-primary mt-8">
                    J'y vais →
                </a>
            </div>

            {{-- Expert contact card --}}
            <div class="bg-navy rounded-2xl p-8 text-white" id="contact">
                <h3 class="text-xl font-bold mb-2">Une équipe d'experts qui vous accompagne</h3>
                <p class="text-blue-200 text-sm mb-6">Nos conseillers sont disponibles du lundi au samedi de 9h à 18h pour répondre à toutes vos questions.</p>

                <div class="space-y-4 mb-6">
                    @php
                        $cEmail  = setting('site.email');
                        $cPhone1 = setting('site.phone_1');
                        $cPhone2 = setting('site.phone_2');
                        $cPhone3 = setting('site.phone_3');
                        $cTel1   = 'tel:' . str_replace(['+', ' '], '', $cPhone1);
                    @endphp
                    <a href="{{ $cTel1 }}" class="flex items-center gap-4 bg-white/10 rounded-xl p-4 hover:bg-white/20 transition-colors group">
                        <div class="w-10 h-10 bg-orange rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                        </div>
                        <div>
                            <div class="text-xs text-blue-300">{{ $cPhone1 }} / {{ $cPhone2 }} / {{ $cPhone3 }}</div>
                            <div class="font-bold group-hover:text-orange transition-colors">Appelez-nous</div>
                        </div>
                    </a>

                    <a href="mailto:{{ $cEmail }}" class="flex items-center gap-4 bg-white/10 rounded-xl p-4 hover:bg-white/20 transition-colors group">
                        <div class="w-10 h-10 bg-orange rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <div class="text-xs text-blue-300">Réponse sous 24h</div>
                            <div class="font-bold group-hover:text-orange transition-colors">{{ $cEmail }}</div>
                        </div>
                    </a>
                </div>

                <div class="border-t border-white/20 pt-5">
                    <div class="text-xs text-blue-300 mb-3 font-semibold uppercase tracking-wider">Nos certifications</div>
                    <div class="flex flex-wrap gap-2">
                        <span class="text-xs bg-white/10 px-3 py-1.5 rounded-full font-medium">✅ Installateurs certifiés</span>
                        <span class="text-xs bg-white/10 px-3 py-1.5 rounded-full font-medium">🏆 Prix bas garanti</span>
                        <span class="text-xs bg-white/10 px-3 py-1.5 rounded-full font-medium">🚚 Livraison au frais du client</span>
                        <span class="text-xs bg-white/10 px-3 py-1.5 rounded-full font-medium">↩️ Retour 30 jours</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ====================================================
     BANDEAU DÉFILANT : NOS MARQUES
     ================================================== --}}
<section class="py-8 bg-white border-t border-gray-100 overflow-hidden">
    <div class="max-w-screen-xl mx-auto px-4 mb-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest text-center">Nos marques partenaires</p>
    </div>
    <div class="relative">
        {{-- Dégradé gauche/droite --}}
        <div class="absolute left-0 top-0 h-full w-20 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none"></div>
        <div class="absolute right-0 top-0 h-full w-20 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none"></div>

        @php $loopBrands = $brands->concat($brands)->concat($brands); @endphp
        <div class="flex gap-8 marquee-track">
            @foreach($loopBrands as $bl)

            <div class="flex flex-col items-center justify-center gap-2 shrink-0 w-32">
                <div class="w-24 h-14 rounded-xl bg-white border border-gray-100 shadow-sm flex items-center justify-center p-2">
                    @if($bl->logo)
                        <img src="{{ url('storage/app/public/' . $bl->logo) }}"
                             alt="{{ $bl->name }}"
                             class="max-w-full max-h-full object-contain">
                    @else
                        <span class="text-gray-400 font-black text-sm text-center leading-tight px-1">
                            {{ strtoupper(substr($bl->name, 0, 3)) }}
                        </span>
                    @endif
                </div>
                <span class="text-xs font-semibold text-gray-500 text-center leading-tight whitespace-nowrap">{{ $bl->name }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

<style>
.marquee-track {
    animation: marquee-scroll 30s linear infinite;
    width: max-content;
}
.marquee-track:hover {
    animation-play-state: paused;
}
@keyframes marquee-scroll {
    0%   { transform: translateX(0); }
    100% { transform: translateX(-33.333%); }
}
</style>

@endsection
