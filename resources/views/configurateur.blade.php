@extends('layouts.app')

@section('title', 'Demande de devis — Ma Quincaillerie Solaire')
@section('meta_description', 'Demandez un devis sur mesure pour votre installation solaire. Réponse rapide par email et WhatsApp.')

@section('content')

@php
    $waNumber = str_replace(['+', ' '], '', setting('site.phone_2'));
@endphp

<section class="py-14 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">

        {{-- En-tête --}}
        <div class="text-center mb-12">
            <span class="inline-flex items-center gap-2 text-xs font-bold text-orange uppercase tracking-widest bg-orange/10 px-4 py-2 rounded-full mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Gratuit & sans engagement
            </span>
            <h1 class="text-3xl md:text-4xl font-extrabold text-navy mb-3">Demandez un devis sur mesure et recevez le instantanément</h1>
            <p class="text-gray-500 text-lg max-w-xl mx-auto">Remplissez le formulaire ci-dessous — nous vous répondons par email et WhatsApp.</p>
        </div>

        <div class="max-w-2xl mx-auto"
             x-data="{
                 nom: '',
                 ville: '',
                 type: '',
                 amperage: '',
                 facture: '',
                 toiture: '',
                 sent: false,
                 loading: false,
                 errorMsg: '',
                 get isComplete() {
                     return this.nom && this.ville && this.type && this.amperage && this.facture && this.toiture;
                 },
                 get whatsappUrl() {
                     const msg = 'Bonjour, je souhaite un devis solaire.%0A%0A'
                         + 'Nom : ' + encodeURIComponent(this.nom) + '%0A'
                         + 'Ville : ' + encodeURIComponent(this.ville) + '%0A'
                         + 'Type : ' + encodeURIComponent(this.type) + '%0A'
                         + 'Ampérage : ' + this.amperage + 'A%0A'
                         + 'Facture CIE (2 mois) : ' + encodeURIComponent(this.facture) + ' F CFA%0A'
                         + 'Toiture : ' + encodeURIComponent(this.toiture);
                     return 'https://wa.me/{{ $waNumber }}?text=' + msg;
                 },
                 async submit() {
                     if (!this.isComplete) return;
                     this.loading = true;
                     this.errorMsg = '';
                     try {
                         const res = await fetch('{{ route('devis.send') }}', {
                             method: 'POST',
                             headers: {
                                 'Content-Type': 'application/json',
                                 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                             },
                             body: JSON.stringify({
                                 nom: this.nom,
                                 ville: this.ville,
                                 type: this.type,
                                 amperage: this.amperage,
                                 facture: this.facture,
                                 toiture: this.toiture,
                             })
                         });
                         if (res.ok) {
                             this.sent = true;
                         } else {
                             this.errorMsg = 'Une erreur est survenue. Veuillez réessayer.';
                         }
                     } catch(e) {
                         this.errorMsg = 'Une erreur est survenue. Veuillez réessayer.';
                     }
                     this.loading = false;
                 }
             }">

            {{-- Formulaire --}}
            <div x-show="!sent" class="bg-gray-50 rounded-2xl border border-gray-200 p-6 md:p-10 space-y-6">

                {{-- Nom & Prénom --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nom & Prénom *</label>
                    <input type="text" x-model="nom" placeholder="Ex : Kouamé Jean"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:border-navy focus:outline-none transition-colors bg-white">
                </div>

                {{-- Ville --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ville *</label>
                    <input type="text" x-model="ville" placeholder="Ex : Abidjan, Bouaké, Yamoussoukro..."
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:border-navy focus:outline-none transition-colors bg-white">
                </div>

                {{-- Type d'installation --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Type d'installation *</label>
                    <div class="flex gap-3">
                        <button type="button" @click="type = 'Monophasé'"
                            :class="type === 'Monophasé' ? 'border-navy bg-navy text-white shadow-md' : 'border-gray-200 bg-white text-gray-700 hover:border-navy hover:text-navy'"
                            class="flex-1 py-3 rounded-xl border-2 text-sm font-semibold transition-all">
                            ⚡ Monophasé
                        </button>
                        <button type="button" @click="type = 'Triphasé'"
                            :class="type === 'Triphasé' ? 'border-navy bg-navy text-white shadow-md' : 'border-gray-200 bg-white text-gray-700 hover:border-navy hover:text-navy'"
                            class="flex-1 py-3 rounded-xl border-2 text-sm font-semibold transition-all">
                            ⚡⚡ Triphasé
                        </button>
                    </div>
                </div>

                {{-- Ampérage --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Ampérage du compteur *</label>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach([5, 10, 15, 20, 25, 30, 45, 60] as $amp)
                        <button type="button" @click="amperage = '{{ $amp }}'"
                            :class="amperage === '{{ $amp }}' ? 'bg-navy text-white border-navy shadow-md' : 'bg-white text-gray-700 border-gray-200 hover:border-navy hover:text-navy'"
                            class="py-2.5 rounded-xl border-2 text-sm font-bold transition-all">
                            {{ $amp }}A
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Facture CIE --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Montant de la facture CIE (tous les 2 mois) *</label>
                    <div class="relative">
                        <input type="number" x-model="facture" min="0" placeholder="Ex : 45 000"
                            class="w-full px-4 py-3 pr-20 border-2 border-gray-200 rounded-xl text-sm focus:border-navy focus:outline-none transition-colors bg-white">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-semibold">F CFA</span>
                    </div>
                </div>

                {{-- Type de toiture --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Type de toiture *</label>
                    <div class="flex gap-3">
                        <button type="button" @click="toiture = 'Dalle'"
                            :class="toiture === 'Dalle' ? 'border-navy bg-navy text-white shadow-md' : 'border-gray-200 bg-white text-gray-700 hover:border-navy hover:text-navy'"
                            class="flex-1 py-3 rounded-xl border-2 text-sm font-semibold transition-all">
                            🏢 Dalle
                        </button>
                        <button type="button" @click="toiture = 'Tôle'"
                            :class="toiture === 'Tôle' ? 'border-navy bg-navy text-white shadow-md' : 'border-gray-200 bg-white text-gray-700 hover:border-navy hover:text-navy'"
                            class="flex-1 py-3 rounded-xl border-2 text-sm font-semibold transition-all">
                            🏠 Tôle
                        </button>
                    </div>
                </div>

                {{-- Erreur --}}
                <p x-show="errorMsg" x-text="errorMsg" x-cloak class="text-red-500 text-sm font-medium"></p>

                {{-- Bouton envoi --}}
                <button type="button" @click="submit()"
                    :disabled="!isComplete || loading"
                    :class="!isComplete ? 'opacity-50 cursor-not-allowed' : 'hover:bg-orange-600'"
                    class="w-full py-4 bg-orange text-white font-bold rounded-xl transition-all text-base">
                    <span x-show="!loading">✉️ Envoyer ma demande de devis</span>
                    <span x-show="loading" x-cloak>⏳ Envoi en cours...</span>
                </button>

            </div>

            {{-- Succès --}}
            <div x-show="sent" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="bg-green-50 rounded-2xl border border-green-200 p-10 text-center">
                <div class="text-6xl mb-4">✅</div>
                <h2 class="text-2xl font-extrabold text-navy mb-2">Votre demande a bien été envoyée !</h2>
                <p class="text-gray-500 mb-8">Notre équipe vous répondra dans les plus brefs délais.<br>Vous pouvez aussi nous envoyer votre demande directement sur WhatsApp.</p>
                <a :href="whatsappUrl" target="_blank"
                   class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white font-bold px-7 py-3.5 rounded-xl transition-colors text-sm mb-4">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Envoyer aussi sur WhatsApp
                </a>
                <div>
                    <a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-navy transition-colors">← Retour à l'accueil</a>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
