@php
$menu = [
    ['route' => 'account.dashboard', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>', 'label' => 'Tableau de bord'],
    ['route' => 'account.orders',    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>', 'label' => 'Mes commandes'],
    ['route' => 'addresses.index',   'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>', 'label' => 'Mes adresses'],
    ['route' => 'account.profile',   'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>', 'label' => 'Mon profil'],
];
@endphp

{{-- Sidebar desktop --}}
<aside class="hidden lg:flex flex-col w-60 shrink-0">
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden sticky top-24">
        {{-- Avatar --}}
        <div class="bg-gradient-to-br from-navy to-navy-light p-5 text-white">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mb-3 text-xl font-extrabold">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <p class="font-bold text-sm truncate">{{ auth()->user()->name }}</p>
            <p class="text-blue-300 text-xs truncate">{{ auth()->user()->email }}</p>
        </div>
        {{-- Navigation --}}
        <nav class="p-2">
            @foreach($menu as $item)
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
                      {{ request()->routeIs($item['route']) ? 'bg-navy text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-navy' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $item['icon'] !!}</svg>
                {{ $item['label'] }}
            </a>
            @endforeach
        </nav>
        {{-- Déconnexion --}}
        <div class="p-2 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 w-full px-3 py-2.5 rounded-xl text-sm font-medium text-red-500 hover:bg-red-50 transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Déconnexion
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- Mobile nav bar --}}
<div class="lg:hidden flex gap-1 bg-white rounded-2xl border border-gray-200 p-1.5 mb-6 overflow-x-auto">
    @foreach($menu as $item)
    <a href="{{ route($item['route']) }}"
       class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all
              {{ request()->routeIs($item['route']) ? 'bg-navy text-white' : 'text-gray-500 hover:bg-gray-50' }}">
        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $item['icon'] !!}</svg>
        {{ $item['label'] }}
    </a>
    @endforeach
</div>
