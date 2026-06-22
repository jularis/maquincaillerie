<div class="card group flex flex-col">
    {{-- Image --}}
    <div class="relative overflow-hidden bg-gray-50 aspect-[4/3]">
        @if($product->image)
            <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}"
                 class="w-full h-full object-contain p-4 group-hover:scale-105 transition-transform duration-300">
        @else
            <div class="w-full h-full flex flex-col items-center justify-center">
                <span class="text-6xl opacity-40">{{ $product->category->icon ?? '📦' }}</span>
            </div>
        @endif
        {{-- Badges --}}
        <div class="absolute top-3 left-3 flex flex-col gap-1.5">
            @if($product->old_price)
            <span class="badge bg-red-500 text-white">-{{ $product->discount_percent }}%</span>
            @endif
            @if($product->featured)
            <span class="badge bg-orange text-white">⭐ Vedette</span>
            @endif
        </div>
        {{-- Quick add --}}
        <div class="absolute inset-x-0 bottom-0 p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-200"
             x-data="addToCart({{ $product->id }}, {{ $product->stock > 0 ? 'true' : 'false' }})">
            <button @click="submit()"
                :disabled="loading || !{{ $product->stock > 0 ? 'true' : 'false' }}"
                :class="added ? 'bg-green-600' : 'bg-navy hover:bg-orange'"
                class="w-full py-2.5 text-white text-sm font-semibold rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                <svg x-show="loading" class="animate-spin w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                </svg>
                <span x-text="loading ? 'Ajout…' : (added ? '✓ Ajouté !' : '🛒 Ajouter au panier')">🛒 Ajouter au panier</span>
            </button>
        </div>
    </div>

    {{-- Content --}}
    <div class="p-4 flex flex-col flex-1">
        @if($product->brand)
        <div class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">{{ $product->brand->name }}</div>
        @endif

        <h3 class="font-semibold text-gray-800 text-sm leading-snug mb-2 line-clamp-2 flex-1 group-hover:text-navy transition-colors">
            <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
        </h3>

        @if($product->power)
        <div class="flex items-center gap-1.5 text-xs text-gray-400 mb-2">
            <span class="w-1.5 h-1.5 bg-orange rounded-full"></span>
            <span>{{ number_format($product->power) }} W</span>
        </div>
        @endif

        {{-- Stock indicator --}}
        @if($product->stock > 5)
        <div class="flex items-center gap-1 text-xs text-green-600 mb-2.5">
            <span class="w-1.5 h-1.5 bg-green-500 rounded-full inline-block"></span> En stock
        </div>
        @elseif($product->stock > 0)
        <div class="flex items-center gap-1 text-xs text-orange mb-2.5">
            <span class="w-1.5 h-1.5 bg-orange rounded-full inline-block"></span> Stock limité ({{ $product->stock }})
        </div>
        @else
        <div class="flex items-center gap-1 text-xs text-red-500 mb-2.5">
            <span class="w-1.5 h-1.5 bg-red-500 rounded-full inline-block"></span> Rupture de stock
        </div>
        @endif

        {{-- Price --}}
        <div class="flex items-end justify-between mt-auto pt-3 border-t border-gray-100">
            <div>
                <div class="text-xl font-extrabold text-navy leading-none">{{ fcfa($product->price) }}</div>
                @if($product->old_price)
                <div class="text-xs text-gray-400 line-through mt-0.5">{{ fcfa($product->old_price) }}</div>
                @endif
            </div>
            <a href="{{ route('products.show', $product->slug) }}"
               class="text-xs font-semibold text-orange hover:text-orange-dark transition-colors border-b border-orange/30 hover:border-orange">
                Voir détails
            </a>
        </div>
    </div>
</div>
