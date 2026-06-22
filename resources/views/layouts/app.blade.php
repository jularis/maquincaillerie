<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="cart-add-url" content="{{ route('cart.add') }}">
    <title>@yield('title', 'Ma Quincaillerie Solaire — Experts photovoltaïque et stockage solaire')</title>
    <meta name="description" content="@yield('meta_description', 'Spécialiste en panneaux solaires, onduleurs, batteries et kits solaires depuis 2011. 50 000+ clients satisfaits.')">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <style>
      [x-cloak] { display: none !important; }
      .mega-menu-dropdown { display: none; }
      .mega-menu:hover .mega-menu-dropdown { display: block; }
    </style>
</head>
<body class="bg-white">

{{-- ===== TOP CONTACT BAR ===== --}}
<div class="bg-gray-50 border-b border-gray-200 text-xs text-gray-600">
    <div class="max-w-screen-xl mx-auto px-4 flex items-center justify-between h-9 gap-4">
        <div class="hidden md:flex items-center gap-5">
            <a href="tel:+2252735958998" class="flex items-center gap-1.5 hover:text-navy transition-colors font-medium">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                27 35 95 89 98
            </a>
            <span class="text-gray-300">|</span>
            <a href="tel:+2250769622644" class="flex items-center gap-1.5 hover:text-navy transition-colors font-medium">
                07 69 62 26 44
            </a>
            <span class="text-gray-300">|</span>
            <a href="tel:+2250556651355" class="flex items-center gap-1.5 hover:text-navy transition-colors font-medium">
                05 56 65 13 55
            </a>
        </div>
        <div class="flex items-center gap-4 ml-auto">
            <a href="#" class="flex items-center gap-1 hover:text-navy transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Prendre RDV
            </a>
            <a href="mailto:commerciale@cleanenergyservices.net" class="flex items-center gap-1 hover:text-navy transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                commerciale@cleanenergyservices.net
            </a>
            <a href="#" class="flex items-center gap-1 hover:text-navy transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Notre académie
            </a>
        </div>
    </div>
</div>

{{-- ===== MAIN HEADER ===== --}}
<header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm"
        x-data="{ mobileOpen: false, searchOpen: false, accountOpen: false }">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="flex items-center gap-4 h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="Ma Quincaillerie Solaire" style="height: 52px; width: auto;">
            </a>

            {{-- Search --}}
            <form action="{{ route('products.index') }}" method="GET" class="hidden lg:flex flex-1 max-w-lg">
                <div class="flex w-full h-10 rounded-lg overflow-hidden border border-gray-300 focus-within:border-navy focus-within:ring-2 focus-within:ring-navy/20 transition-all">
                    <select name="category" class="border-0 bg-gray-50 text-xs text-gray-600 px-3 border-r border-gray-300 outline-none min-w-0 w-36 cursor-pointer">
                        <option value="">Toutes catégories</option>
                        @php $searchCats = \App\Models\Category::where('active', true)->orderBy('order')->get(); @endphp
                        @foreach($searchCats as $sc)
                        <option value="{{ $sc->slug }}" {{ request('category') === $sc->slug ? 'selected' : '' }}>{{ $sc->name }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="search" placeholder="Recherchez un produit..."
                        value="{{ request('search') }}"
                        class="flex-1 px-4 text-sm outline-none border-0 bg-white">
                    <button type="submit" class="px-4 bg-navy text-white hover:bg-navy-dark transition-colors shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                </div>
            </form>

            {{-- Right section --}}
            <div class="flex items-center gap-2 ml-auto">

                {{-- Phone (hidden on mobile) --}}
                <a href="tel:+2252735958998" class="hidden xl:flex items-center gap-2 text-sm font-semibold text-navy hover:text-navy-dark transition-colors pr-4 border-r border-gray-200">
                    <svg class="w-4 h-4 text-orange" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                    +225 27 35 95 89 98
                </a>

                {{-- RDV --}}
                <a href="#" class="hidden md:flex items-center gap-1.5 text-xs font-medium text-gray-600 hover:text-navy transition-colors px-2 py-1.5 rounded hover:bg-gray-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Prendre RDV
                </a>

                {{-- Account --}}
                @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center gap-1.5 text-xs font-medium text-gray-600 hover:text-navy transition-colors px-2 py-1.5 rounded hover:bg-gray-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span class="hidden md:block max-w-24 truncate">{{ auth()->user()->name }}</span>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-cloak
                        class="absolute right-0 mt-1 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-1 z-50">
                        <div class="px-4 py-2.5 border-b border-gray-100">
                            <p class="text-xs text-gray-400">Connecté en tant que</p>
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="flex items-center gap-1.5 text-xs font-medium text-gray-600 hover:text-navy transition-colors px-2 py-1.5 rounded hover:bg-gray-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span class="hidden md:block">Votre compte</span>
                </a>
                @endauth

                {{-- Cart --}}
                <a href="{{ route('cart.index') }}" x-data
                    class="relative flex items-center gap-2 bg-navy text-white px-4 py-2 rounded-lg hover:bg-navy-dark transition-colors text-sm font-semibold ml-1 shrink-0">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span class="hidden sm:inline">Panier</span>
                    <span x-show="$store.cart.count > 0" x-cloak x-text="$store.cart.count"
                        class="flex items-center justify-center min-w-[1.25rem] h-5 bg-orange text-white text-xs rounded-full font-bold px-1 leading-none">
                    </span>
                </a>

                {{-- Mobile burger --}}
                <button @click="mobileOpen = !mobileOpen" class="lg:hidden p-2 text-gray-600 hover:text-navy ml-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ===== NAVIGATION BAR ===== --}}
    <nav class="hidden lg:block bg-white border-t border-gray-100">
        <div class="max-w-screen-xl mx-auto px-4">
            <div class="flex items-center">

                {{-- Configurateur --}}
                <div class="mega-menu relative">
                    <a href="{{ route('products.index', ['category' => 'kits-solaires']) }}"
                       class="nav-link flex items-center gap-1.5 text-orange font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        Configurateur
                    </a>
                </div>

                {{-- Déstockage --}}
                <a href="{{ route('products.index') }}" class="nav-link text-red-600 font-bold flex items-center gap-1">
                    <span class="inline-block w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    Destockage
                </a>

                {{-- Produits mega-menu --}}
                <div class="mega-menu relative group">
                    <a href="{{ route('products.index') }}" class="nav-link flex items-center gap-1">
                        Produits
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </a>
                    <div class="mega-menu-dropdown absolute left-0 top-full w-[640px] bg-white shadow-2xl border border-gray-100 rounded-b-xl z-50 p-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">☀️ SOLAIRE</div>
                                @php $megaCats = \App\Models\Category::where('active', true)->orderBy('order')->get(); @endphp
                                @foreach($megaCats->take(5) as $mc)
                                <a href="{{ route('products.index', ['category' => $mc->slug]) }}"
                                   class="flex items-center gap-2.5 py-2 text-sm text-gray-700 hover:text-navy hover:bg-gray-50 px-2 rounded-lg transition-colors group">
                                    <span class="text-base">{{ $mc->icon }}</span>
                                    <span>{{ $mc->name }}</span>
                                    <span class="ml-auto text-[10px] bg-navy text-white px-1.5 py-0.5 rounded font-bold opacity-0 group-hover:opacity-100 transition-opacity">NEW</span>
                                </a>
                                @endforeach
                            </div>
                            <div>
                                <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">⚡ ÉLECTRICITÉ & AUTRE</div>
                                @foreach($megaCats->skip(5) as $mc)
                                <a href="{{ route('products.index', ['category' => $mc->slug]) }}"
                                   class="flex items-center gap-2.5 py-2 text-sm text-gray-700 hover:text-navy hover:bg-gray-50 px-2 rounded-lg transition-colors">
                                    <span class="text-base">{{ $mc->icon }}</span>
                                    <span>{{ $mc->name }}</span>
                                </a>
                                @endforeach
                                <a href="{{ route('products.index') }}" class="flex items-center gap-2 mt-3 px-2 py-2 text-sm font-semibold text-orange hover:text-orange-dark transition-colors">
                                    Catalogue complet →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Separator --}}
                <div class="flex-1"></div>

                {{-- Quick categories right side --}}
                @php $navCats = \App\Models\Category::where('active', true)->orderBy('order')->take(4)->get(); @endphp
                @foreach($navCats as $nc)
                <a href="{{ route('products.index', ['category' => $nc->slug]) }}"
                   class="nav-link text-gray-600 text-xs">
                    {{ $nc->icon }} {{ $nc->name }}
                </a>
                @endforeach
            </div>
        </div>
    </nav>

    {{-- ===== MOBILE MENU ===== --}}
    <div x-show="mobileOpen" x-cloak class="lg:hidden bg-white border-t border-gray-100 shadow-lg">

        {{-- Mobile search --}}
        <div class="p-4 border-b border-gray-100">
            <form action="{{ route('products.index') }}" method="GET">
                <div class="flex h-10 rounded-lg overflow-hidden border border-gray-300">
                    <input type="text" name="search" placeholder="Rechercher un produit..."
                        class="flex-1 px-4 text-sm outline-none border-0">
                    <button type="submit" class="px-4 bg-navy text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                </div>
            </form>
        </div>

        <div class="p-4 space-y-1">
            <a href="{{ route('products.index', ['category' => 'kits-solaires']) }}" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-bold text-orange bg-orange/5">
                ⚡ Configurateur
            </a>
            <a href="{{ route('products.index') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-bold text-red-600 bg-red-50">
                🏷️ Destockage
            </a>
            <div class="pt-2 pb-1 px-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Produits</div>
            @php $mobCats = \App\Models\Category::where('active', true)->orderBy('order')->get(); @endphp
            @foreach($mobCats as $mc)
            <a href="{{ route('products.index', ['category' => $mc->slug]) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50 hover:text-navy">
                <span class="text-base">{{ $mc->icon }}</span> {{ $mc->name }}
            </a>
            @endforeach
            <div class="pt-3 pb-1 border-t border-gray-100">
                <div class="flex gap-2">
                    <a href="tel:+2252735958998" class="flex-1 text-center py-2.5 text-sm font-medium bg-gray-50 text-navy rounded-lg border border-gray-200">📞 27 35 95 89 98</a>
                </div>
                @guest
                <div class="flex gap-2 mt-2">
                    <a href="{{ route('login') }}" class="flex-1 text-center py-2.5 border border-navy text-navy rounded-lg text-sm font-semibold">Connexion</a>
                    <a href="{{ route('register') }}" class="flex-1 text-center py-2.5 bg-navy text-white rounded-lg text-sm font-semibold">Inscription</a>
                </div>
                @endguest
            </div>
        </div>
    </div>
</header>

{{-- Flash messages --}}
@if(session('success'))
<div class="max-w-screen-xl mx-auto px-4 mt-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between text-sm">
        <span>✅ {{ session('success') }}</span>
        <button @click="show = false" class="text-green-500 hover:text-green-700 ml-4">✕</button>
    </div>
</div>
@endif
@if(session('error'))
<div class="max-w-screen-xl mx-auto px-4 mt-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center justify-between text-sm">
        <span>❌ {{ session('error') }}</span>
        <button @click="show = false" class="text-red-500 hover:text-red-700 ml-4">✕</button>
    </div>
</div>
@endif

<main>@yield('content')</main>

{{-- ===== FOOTER ===== --}}
<footer class="bg-navy-dark text-white mt-16">
    <div class="max-w-screen-xl mx-auto px-4 py-14">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

            {{-- Brand --}}
            <div>
                <div class="mb-5">
                    <img src="{{ asset('images/logo.png') }}" alt="Ma Quincaillerie Solaire" style="height: 52px; width: auto;">
                </div>
                <p class="text-gray-400 text-sm leading-relaxed mb-4">Experts photovoltaïque et stockage solaire depuis 2011. 50 000+ clients satisfaits en France et en Europe.</p>
                <div class="flex gap-2.5">
                    <a href="#" class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center hover:bg-orange transition-colors text-xs font-bold">YT</a>
                    <a href="#" class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center hover:bg-orange transition-colors text-xs font-bold">f</a>
                    <a href="#" class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center hover:bg-orange transition-colors text-xs font-bold">in</a>
                    <a href="#" class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center hover:bg-orange transition-colors text-xs font-bold">ig</a>
                </div>
                <div class="mt-4 flex flex-wrap gap-1.5">
                    <span class="text-xs px-2 py-1 bg-white/10 rounded text-gray-400 cursor-pointer hover:bg-white/20">FR</span>
                    <span class="text-xs px-2 py-1 bg-white/10 rounded text-gray-400 cursor-pointer hover:bg-white/20">EN</span>
                    <span class="text-xs px-2 py-1 bg-white/10 rounded text-gray-400 cursor-pointer hover:bg-white/20">BE-FR</span>
                    <span class="text-xs px-2 py-1 bg-white/10 rounded text-gray-400 cursor-pointer hover:bg-white/20">DE</span>
                    <span class="text-xs px-2 py-1 bg-white/10 rounded text-gray-400 cursor-pointer hover:bg-white/20">IT</span>
                </div>
            </div>

            {{-- Products --}}
            <div>
                <h4 class="font-bold mb-4 text-white text-sm uppercase tracking-wider">Nos produits</h4>
                <ul class="space-y-2.5 text-sm text-gray-400">
                    @php $footerCats = \App\Models\Category::where('active', true)->orderBy('order')->get(); @endphp
                    @foreach($footerCats as $cat)
                    <li>
                        <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
                           class="hover:text-white transition-colors flex items-center gap-2">
                            <span>{{ $cat->icon }}</span> {{ $cat->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Help --}}
            <div>
                <h4 class="font-bold mb-4 text-white text-sm uppercase tracking-wider">Besoin d'aide ?</h4>
                <ul class="space-y-3 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-white transition-colors flex items-center gap-2">📚 Votre assistance</a></li>
                    <li><a href="#" class="hover:text-white transition-colors flex items-center gap-2">🎓 Formez-vous sur le solaire</a></li>
                    <li><a href="mailto:commerciale@cleanenergyservices.net" class="hover:text-white transition-colors flex items-center gap-2">✉️ commerciale@cleanenergyservices.net</a></li>
                    <li>
                        <div class="flex items-center gap-2 mb-1">📞 Téléphone :</div>
                        <a href="tel:+2252735958998" class="hover:text-white transition-colors block pl-6">+225 27 35 95 89 98</a>
                        <a href="tel:+2250769622644" class="hover:text-white transition-colors block pl-6">+225 07 69 62 26 44</a>
                        <a href="tel:+2250556651355" class="hover:text-white transition-colors block pl-6">+225 05 56 65 13 55</a>
                    </li>
                </ul>
            </div>

            {{-- Payments --}}
            <div>
                <h4 class="font-bold mb-4 text-white text-sm uppercase tracking-wider">Nos paiements</h4>
                <div class="grid grid-cols-3 gap-2 mb-5">
                    @foreach(['CB', 'Visa', 'MC', 'PayPal', 'Klarna', 'Virement'] as $pay)
                    <div class="bg-white rounded px-2 py-1.5 flex items-center justify-center">
                        <span class="text-navy text-[10px] font-bold">{{ $pay }}</span>
                    </div>
                    @endforeach
                </div>
                <h4 class="font-bold mb-3 text-white text-sm uppercase tracking-wider">Nos garanties</h4>
                <div class="space-y-2 text-sm text-gray-400">
                    <div class="flex items-center gap-2">🔒 Paiement 100% sécurisé</div>
                    <div class="flex items-center gap-2">🚚 Livraison rapide 24-48h</div>
                    <div class="flex items-center gap-2">↩️ Retour 30 jours</div>
                    <div class="flex items-center gap-2">🏆 Prix bas garanti</div>
                </div>
            </div>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="max-w-screen-xl mx-auto px-4 py-4 flex flex-col sm:flex-row items-center justify-between gap-3">
            <span class="text-xs text-gray-500">© Ma Quincaillerie Solaire 2011 – {{ date('Y') }}</span>
            <div class="flex flex-wrap justify-center gap-4 text-xs text-gray-500">
                <a href="#" class="hover:text-gray-300 transition-colors">Droit de rétractation</a>
                <a href="#" class="hover:text-gray-300 transition-colors">Vos données</a>
                <a href="#" class="hover:text-gray-300 transition-colors">Mentions légales</a>
                <a href="#" class="hover:text-gray-300 transition-colors">CGV</a>
                <a href="#" class="hover:text-gray-300 transition-colors">GDPR</a>
                <a href="#" class="hover:text-gray-300 transition-colors">Sitemap</a>
            </div>
        </div>
    </div>
</footer>

<script src="{{ mix('js/app.js') }}"></script>

{{-- Toast --}}
<div x-data x-show="$store.toast.show" x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-4"
    :class="$store.toast.type === 'error' ? 'bg-red-600' : 'bg-green-600'"
    class="fixed bottom-6 right-6 z-[9999] flex items-center gap-3 text-white px-5 py-3.5 rounded-xl shadow-2xl text-sm font-semibold max-w-xs">
    <span x-text="$store.toast.type === 'error' ? '✗' : '✓'" class="text-base shrink-0"></span>
    <span x-text="$store.toast.message"></span>
    <button @click="$store.toast.show = false" class="ml-2 opacity-70 hover:opacity-100 text-lg leading-none shrink-0">×</button>
</div>
</body>
</html>
