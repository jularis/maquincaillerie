@extends('layouts.app')

@section('title', 'Configurateur de kit solaire — Ma Quincaillerie Solaire')
@section('meta_description', 'Configurez votre installation solaire en 3 étapes : type d\'installation, ampérage et consommation journalière. Obtenez immédiatement le kit adapté.')

@section('content')

<section class="py-14 bg-white"
    x-data="{ productsBaseUrl: '{{ rtrim(config('app.url'), '/') }}/produits',
        type: null,
        amperage: null,
        consumption: '',
        get pvNeeded() {
            const c = parseFloat(this.consumption);
            if (!c || c <= 0) return null;
            return c / 5 * 1.3;
        },
        get kitRecommande() {
            const c = parseFloat(this.consumption);
            if (!this.type || !this.amperage || !c || c <= 0) return null;
            const kits = [
                { name:'ACCESS',    slug:'kit-solaire-access',    price:'2 000 000',  maxKwh:4,   phases:['mono'] },
                { name:'FREE',      slug:'kit-solaire-free',      price:'3 700 000',  maxKwh:8,   phases:['mono'] },
                { name:'ECO',       slug:'kit-solaire-eco',       price:'4 300 000',  maxKwh:13,  phases:['mono'] },
                { name:'ECO FRESH', slug:'kit-solaire-eco-fresh', price:'6 000 000',  maxKwh:18,  phases:['mono'] },
                { name:'CONFORT',   slug:'kit-solaire-confort',   price:'8 500 000',  maxKwh:27,  phases:['mono','tri'] },
                { name:'BRONZE',    slug:'kit-solaire-bronze',    price:'9 000 000',  maxKwh:38,  phases:['mono','tri'] },
                { name:'SYLVER',    slug:'kit-solaire-sylver',    price:'11 500 000', maxKwh:48,  phases:['mono','tri'] },
                { name:'GOLD',      slug:'kit-solaire-gold',      price:'14 700 000', maxKwh:62,  phases:['mono','tri'] },
                { name:'PLATINIUM', slug:'kit-solaire-platinium', price:'16 900 000', maxKwh:80,  phases:['mono','tri'] },
                { name:'SAPHIRE',   slug:'kit-solaire-saphire',   price:'18 000 000', maxKwh:105, phases:['mono','tri'] },
                { name:'TITANIUM',  slug:'kit-solaire-titanium',  price:'21 500 000', maxKwh:160, phases:['mono','tri'] },
                { name:'DIAMOND',   slug:'kit-solaire-diamond',   price:'33 000 000', maxKwh:260, phases:['mono','tri'] },
                { name:'BUSINESS',  slug:'kit-solaire-business',  price:'75 000 000', maxKwh:9999,phases:['mono','tri'] },
            ];
            const eligible = kits.filter(k => k.phases.includes(this.type));
            return eligible.find(k => c <= k.maxKwh) || eligible[eligible.length - 1];
        },
        get isComplete() {
            return this.type && this.amperage && parseFloat(this.consumption) > 0;
        }
    }">
    <div class="max-w-screen-xl mx-auto px-4">

        {{-- En-tête --}}
        <div class="text-center mb-12">
            <span class="inline-flex items-center gap-2 text-xs font-bold text-orange uppercase tracking-widest bg-orange/10 px-4 py-2 rounded-full mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                Outil gratuit & instantané
            </span>
            <h1 class="text-3xl md:text-4xl font-extrabold text-navy mb-3">Configurez votre installation solaire</h1>
            <p class="text-gray-500 text-lg max-w-xl mx-auto">Renseignez 3 informations pour obtenir le kit parfaitement adapté à vos besoins et à votre budget.</p>
        </div>

        <div class="max-w-4xl mx-auto bg-gray-50 rounded-2xl border border-gray-200 p-6 md:p-10">
            <div class="grid md:grid-cols-3 gap-8">

                {{-- Étape 1 : Type --}}
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-7 h-7 rounded-full bg-navy text-white text-xs font-bold flex items-center justify-center shrink-0">1</span>
                        <span class="font-semibold text-gray-700 text-sm">Type d'installation</span>
                    </div>
                    <div class="flex flex-col gap-3">
                        <button @click="type = 'mono'"
                            :class="type === 'mono' ? 'border-navy bg-navy text-white shadow-md' : 'border-gray-200 bg-white text-gray-700 hover:border-navy hover:text-navy'"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl border-2 transition-all text-sm font-medium w-full">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Monophasé
                        </button>
                        <button @click="type = 'tri'"
                            :class="type === 'tri' ? 'border-navy bg-navy text-white shadow-md' : 'border-gray-200 bg-white text-gray-700 hover:border-navy hover:text-navy'"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl border-2 transition-all text-sm font-medium w-full">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 10V3l-9 11h7v7l9-11h-7z" opacity=".4"/></svg>
                            Triphasé
                        </button>
                    </div>
                </div>

                {{-- Étape 2 : Ampérage --}}
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-7 h-7 rounded-full bg-navy text-white text-xs font-bold flex items-center justify-center shrink-0">2</span>
                        <span class="font-semibold text-gray-700 text-sm">Ampérage du compteur</span>
                    </div>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach([5, 10, 15, 20, 25, 30, 45, 60] as $amp)
                        <button @click="amperage = '{{ $amp }}'"
                            :class="amperage === '{{ $amp }}' ? 'bg-navy text-white border-navy shadow-md' : 'bg-white text-gray-700 border-gray-200 hover:border-navy hover:text-navy'"
                            class="py-2 rounded-lg border-2 text-sm font-semibold transition-all">
                            {{ $amp }}A
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Étape 3 : Consommation --}}
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-7 h-7 rounded-full bg-navy text-white text-xs font-bold flex items-center justify-center shrink-0">3</span>
                        <span class="font-semibold text-gray-700 text-sm">Consommation journalière</span>
                    </div>
                    <div class="relative">
                        <input type="number" x-model="consumption" min="1" max="9999" placeholder="Ex : 15"
                            class="w-full px-4 py-3 pr-16 border-2 border-gray-200 rounded-xl text-sm font-medium focus:border-navy focus:outline-none transition-colors bg-white">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-semibold">kWh</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Relevez votre facture ou estimez votre usage quotidien</p>
                    <div class="mt-3 flex flex-wrap gap-1.5">
                        <button @click="consumption = '5'"  class="text-xs px-2 py-1 bg-gray-100 hover:bg-navy hover:text-white rounded-lg transition-colors text-gray-500">5 kWh</button>
                        <button @click="consumption = '10'" class="text-xs px-2 py-1 bg-gray-100 hover:bg-navy hover:text-white rounded-lg transition-colors text-gray-500">10 kWh</button>
                        <button @click="consumption = '20'" class="text-xs px-2 py-1 bg-gray-100 hover:bg-navy hover:text-white rounded-lg transition-colors text-gray-500">20 kWh</button>
                        <button @click="consumption = '40'" class="text-xs px-2 py-1 bg-gray-100 hover:bg-navy hover:text-white rounded-lg transition-colors text-gray-500">40 kWh</button>
                    </div>
                </div>
            </div>

            {{-- Résultat --}}
            <div x-show="isComplete" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="mt-8 border-t border-gray-200 pt-8">
                <template x-if="kitRecommande">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 bg-gradient-to-br from-navy to-navy-light rounded-2xl p-6 text-white">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-orange rounded-xl flex items-center justify-center shrink-0 shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-blue-300 uppercase tracking-widest mb-0.5">Kit recommandé pour vous</div>
                                <div class="text-2xl font-extrabold" x-text="'Kit Solaire ' + kitRecommande.name"></div>
                                <div class="text-blue-200 text-sm mt-0.5">
                                    <span x-text="type === 'mono' ? 'Monophasé' : 'Triphasé'"></span>
                                    &bull; <span x-text="amperage + 'A'"></span>
                                    &bull; <span x-text="consumption + ' kWh/jour'"></span>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col items-start sm:items-end gap-3 shrink-0">
                            <div>
                                <div class="text-xs text-blue-300 uppercase tracking-widest">À partir de</div>
                                <div class="text-xl font-extrabold text-solar" x-text="kitRecommande.price + ' F CFA'"></div>
                            </div>
                            <a :href="productsBaseUrl + '/' + kitRecommande.slug"
                               class="inline-flex items-center gap-2 bg-orange hover:bg-orange-dark text-white font-semibold px-5 py-2.5 rounded-xl transition-colors text-sm">
                                Voir ce kit
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </template>
            </div>

            {{-- État incomplet --}}
            <div x-show="!isComplete" class="mt-6 flex items-center justify-center gap-2 text-gray-400 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Renseignez les 3 champs pour obtenir votre recommandation
            </div>
        </div>

        {{-- CTA contact --}}
        <div class="text-center mt-10">
            <p class="text-gray-500 text-sm mb-3">Besoin d'aide pour choisir ? Nos experts sont disponibles.</p>
            <div class="flex flex-wrap items-center justify-center gap-3">
                <a href="https://wa.me/2250769622644?text=Bonjour%2C%20je%20souhaite%20de%20l%27aide%20pour%20choisir%20mon%20kit%20solaire."
                   target="_blank"
                   class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white font-semibold px-5 py-2.5 rounded-xl transition-colors text-sm">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Contacter via WhatsApp
                </a>
                <a href="mailto:commerciale@cleanenergyservices.net?subject=Aide%20choix%20kit%20solaire"
                   class="inline-flex items-center gap-2 bg-navy hover:bg-navy-dark text-white font-semibold px-5 py-2.5 rounded-xl transition-colors text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Envoyer un email
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
