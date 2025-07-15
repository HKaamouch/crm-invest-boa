@extends('layouts.app')

@section('title', 'Gestion des Emails')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Gestion des Emails</h1>
        <button type="button" id="compose-email-btn" class="btn btn-primary w-full sm:w-auto">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Composer un email
        </button>
    </div>

    <!-- Statistiques - Responsive Grid amélioré -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-3 sm:p-4 border border-slate-200">
            <div class="flex items-center justify-between">
                <div class="min-w-0 flex-1 mr-2">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide truncate">Total</p>
                    <p class="text-lg sm:text-xl font-bold text-slate-800">{{ number_format($stats['total']) }}</p>
                </div>
                <div class="rounded-full bg-blue-100 p-2 flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-3 sm:p-4 border border-slate-200">
            <div class="flex items-center justify-between">
                <div class="min-w-0 flex-1 mr-2">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide truncate">Envoyés</p>
                    <p class="text-lg sm:text-xl font-bold text-green-600">{{ number_format($stats['envoyes']) }}</p>
                </div>
                <div class="rounded-full bg-green-100 p-2 flex-shrink-0">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-3 sm:p-4 border border-slate-200">
            <div class="flex items-center justify-between">
                <div class="min-w-0 flex-1 mr-2">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide truncate">Reçus</p>
                    <p class="text-lg sm:text-xl font-bold text-blue-600">{{ number_format($stats['recus']) }}</p>
                </div>
                <div class="rounded-full bg-blue-100 p-2 flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-3 sm:p-4 border border-slate-200">
            <div class="flex items-center justify-between">
                <div class="min-w-0 flex-1 mr-2">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide truncate">Ce mois</p>
                    <p class="text-lg sm:text-xl font-bold text-purple-600">{{ number_format($stats['ce_mois']) }}</p>
                </div>
                <div class="rounded-full bg-purple-100 p-2 flex-shrink-0">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-3 sm:p-4 border border-slate-200">
            <div class="flex items-center justify-between">
                <div class="min-w-0 flex-1 mr-2">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide truncate">Cette semaine</p>
                    <p class="text-lg sm:text-xl font-bold text-orange-600">{{ number_format($stats['cette_semaine']) }}</p>
                </div>
                <div class="rounded-full bg-orange-100 p-2 flex-shrink-0">
                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-3 sm:p-4 border border-slate-200">
            <div class="flex items-center justify-between">
                <div class="min-w-0 flex-1 mr-2">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide truncate">Aujourd'hui</p>
                    <p class="text-lg sm:text-xl font-bold text-emerald-600">{{ number_format($stats['aujourd_hui']) }}</p>
                </div>
                <div class="rounded-full bg-emerald-100 p-2 flex-shrink-0">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-6">
        <div class="p-4 sm:p-6 border-b border-slate-200">
            <h2 class="text-lg font-medium text-slate-800">Recherche et filtres</h2>
        </div>

        <div class="p-4 sm:p-6">
            <form action="{{ route('emails.index') }}" method="GET">
                <input type="hidden" name="per_page" value="{{ $perPage }}">

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-4 sm:mb-6">
                    <!-- Recherche -->
                    <div class="form-control lg:col-span-2">
                        <label for="search" class="label">Rechercher</label>
                        <input type="text" name="search" id="search"
                               class="input input-bordered w-full"
                               placeholder="Objet, contenu, destinataire..."
                               value="{{ request('search') }}">
                    </div>

                    <!-- Type -->
                    <div class="form-control">
                        <label for="type" class="label">Type</label>
                        <select name="type" id="type" class="select select-bordered w-full">
                            <option value="">Tous les types</option>
                            <option value="Email envoyé" @selected(request('type') === 'Email envoyé')>Email envoyé</option>
                            <option value="Email reçu" @selected(request('type') === 'Email reçu')>Email reçu</option>
                        </select>
                    </div>

                    <!-- Statut -->
                    <div class="form-control">
                        <label for="statut" class="label">Statut</label>
                        <select name="statut" id="statut" class="select select-bordered w-full">
                            <option value="">Tous les statuts</option>
                            <option value="envoyé" @selected(request('statut') === 'envoyé')>Envoyé</option>
                            <option value="reçu" @selected(request('statut') === 'reçu')>Reçu</option>
                            <option value="en_attente" @selected(request('statut') === 'en_attente')>En attente</option>
                            <option value="erreur" @selected(request('statut') === 'erreur')>Erreur</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-4 sm:mb-6">
                    <!-- Expéditeur -->
                    <div class="form-control">
                        <label for="expediteur" class="label">Expéditeur</label>
                        <select name="expediteur" id="expediteur" class="select select-bordered w-full">
                            <option value="">Tous les expéditeurs</option>
                            @foreach($expediteurs as $expediteur)
                                <option value="{{ $expediteur->id }}" @selected(request('expediteur') == $expediteur->id)>
                                    {{ $expediteur->nom_complet }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Destinataire -->
                    <div class="form-control">
                        <label for="destinataire" class="label">Destinataire</label>
                        <select name="destinataire" id="destinataire" class="select select-bordered w-full">
                            <option value="">Tous les destinataires</option>
                            @foreach($destinataires as $destinataire)
                                <option value="{{ $destinataire->id }}" @selected(request('destinataire') == $destinataire->id)>
                                    {{ $destinataire->nom_complet }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date début -->
                    <div class="form-control">
                        <label for="date_debut" class="label">Date début</label>
                        <input type="date" name="date_debut" id="date_debut"
                               class="input input-bordered w-full"
                               value="{{ request('date_debut') }}">
                    </div>

                    <!-- Date fin -->
                    <div class="form-control">
                        <label for="date_fin" class="label">Date fin</label>
                        <input type="date" name="date_fin" id="date_fin"
                               class="input input-bordered w-full"
                               value="{{ request('date_fin') }}">
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                        <button type="submit" class="btn btn-primary btn-sm w-full sm:w-auto">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Rechercher
                        </button>
                        <a href="{{ route('emails.index') }}" class="btn btn-outline btn-sm w-full sm:w-auto">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Réinitialiser
                        </a>
                    </div>

                    <!-- Nombre par page -->
                    <div class="flex items-center w-full sm:w-auto justify-end">
                        <select name="per_page" onchange="this.form.submit()" class="select select-bordered select-sm py-0 min-h-0 h-9 w-full sm:w-auto">
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 par page</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 par page</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 par page</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des emails -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-slate-200">
            <h2 class="text-lg font-medium text-slate-800">
                {{ $emails->total() }} email(s) trouvé(s)
            </h2>
        </div>

        @if($emails->isEmpty())
            <div class="p-4 sm:p-6 text-center">
                <div class="py-8">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-slate-500">Aucun email trouvé avec ces critères.</p>
                    <a href="{{ route('emails.index') }}" class="btn btn-outline btn-sm mt-4">
                        Réinitialiser les filtres
                    </a>
                </div>
            </div>
        @else
            <!-- Table responsive avec scroll horizontal -->
            <div class="overflow-x-auto">
                <table class="table-auto w-full min-w-full">
                    <thead class="bg-slate-50 text-xs font-medium text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left whitespace-nowrap">Type</th>
                        <th class="px-4 sm:px-6 py-3 text-left whitespace-nowrap">Objet</th>
                        <th class="px-4 sm:px-6 py-3 text-left whitespace-nowrap">Expéditeur</th>
                        <th class="px-4 sm:px-6 py-3 text-left whitespace-nowrap">Destinataire</th>
                        <th class="px-4 sm:px-6 py-3 text-center whitespace-nowrap">Date</th>
                        <th class="px-4 sm:px-6 py-3 text-right whitespace-nowrap">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                    @foreach($emails as $email)
                        @php
                            $metadata = is_array($email->metadata) ? $email->metadata : [];
                            $objet = $metadata['objet'] ?? 'Sans objet';
                            $statut = $metadata['statut'] ?? 'inconnu';
                            $isGroupEmail = $metadata['envoi_groupe'] ?? false;
                        @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($email->type === 'Email envoyé')
                                        <div class="rounded-full bg-green-100 p-1 mr-2 flex-shrink-0">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-green-700">Envoyé</span>
                                    @else
                                        <div class="rounded-full bg-blue-100 p-1 mr-2 flex-shrink-0">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-blue-700">Reçu</span>
                                    @endif
                                    @if($isGroupEmail)
                                        <span class="ml-2 badge badge-xs badge-info">Groupe</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                <div class="min-w-0">
                                    <p class="font-medium text-slate-800 truncate max-w-xs">{{ $objet }}</p>
                                    <p class="text-xs text-slate-500 truncate max-w-xs">
                                        {{ Str::limit(strip_tags($email->description), 25) }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                @if($email->user)
                                    <div class="flex items-center space-x-2 min-w-0">
                                        <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-medium flex-shrink-0">
                                            {{ $email->user->initiales ?? substr($email->user->nom_complet, 0, 2) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-slate-800 truncate">{{ $email->user->nom_complet }}</p>
                                            <p class="text-xs text-slate-500 truncate">{{ $email->user->email }}</p>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-slate-400">Système</span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                @if($email->investor)
                                    <div class="flex items-center space-x-2 min-w-0">
                                        <img src="{{ $email->investor->avatar_url }}"
                                             alt="{{ $email->investor->nom_complet }}"
                                             class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-slate-800 truncate">{{ $email->investor->nom_complet }}</p>
                                            <p class="text-xs text-slate-500 truncate">{{ $email->investor->email }}</p>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-slate-400">Destinataire supprimé</span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-center whitespace-nowrap">
                                <div class="text-sm text-slate-800">{{ $email->date_interaction->format('d/m/Y') }}</div>
                                <div class="text-xs text-slate-500">{{ $email->date_interaction->format('H:i') }}</div>
                            </td>

                            <td class="px-4 sm:px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex justify-end items-center space-x-2">
                                    <a href="{{ route('emails.show', $email) }}"
                                       class="btn btn-sm btn-outline btn-square"
                                       title="Voir l'email">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @if($email->investor)
                                        <a href="{{ route('investors.show', $email->investor) }}"
                                           class="btn btn-sm btn-outline btn-square"
                                           title="Voir l'investisseur">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination améliorée -->
            <div class="px-4 sm:px-6 py-4 border-t border-slate-200 bg-slate-50">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <!-- Informations sur la pagination -->
                    <div class="text-sm text-slate-600">
                        Affichage de {{ $emails->firstItem() ?? 0 }} à {{ $emails->lastItem() ?? 0 }}
                        sur {{ $emails->total() }} résultats
                    </div>

                    <!-- Navigation pagination -->
                    @if ($emails->hasPages())
                        <nav class="pagination-nav" aria-label="Navigation de pagination">
                            <div class="flex items-center space-x-1">
                                {{-- Bouton précédent --}}
                                @if ($emails->onFirstPage())
                                    <span class="pagination-btn pagination-btn-disabled">
                                       <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                       </svg>
                                       <span class="hidden sm:inline ml-1">Précédent</span>
                                   </span>
                                @else
                                    <a href="{{ $emails->previousPageUrl() }}" class="pagination-btn pagination-btn-nav">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                        <span class="hidden sm:inline ml-1">Précédent</span>
                                    </a>
                                @endif

                                {{-- Numéros de page --}}
                                @foreach ($emails->getUrlRange(1, $emails->lastPage()) as $page => $url)
                                    @if ($page == $emails->currentPage())
                                        <span class="pagination-btn pagination-btn-active">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}" class="pagination-btn pagination-btn-page">{{ $page }}</a>
                                    @endif
                                @endforeach

                                {{-- Bouton suivant --}}
                                @if ($emails->hasMorePages())
                                    <a href="{{ $emails->nextPageUrl() }}" class="pagination-btn pagination-btn-nav">
                                        <span class="hidden sm:inline mr-1">Suivant</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                @else
                                    <span class="pagination-btn pagination-btn-disabled">
                                       <span class="hidden sm:inline mr-1">Suivant</span>
                                       <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                       </svg>
                                   </span>
                                @endif
                            </div>
                        </nav>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Modal de composition -->
    @include('emails.compose-modal')
@endsection

@push('breadcrumbs')
    <li>
        <span class="text-slate-500">Emails</span>
    </li>
@endpush

@push('scripts')
    @vite(['resources/js/emails.js'])
    <script>
        // Script d'initialisation côté page
        document.addEventListener('DOMContentLoaded', function() {
            // Ajout de l'événement click au bouton de composition
            const composeBtn = document.getElementById('compose-email-btn');
            if (composeBtn) {
                composeBtn.addEventListener('click', function() {
                    if (typeof window.openComposeModal === 'function') {
                        window.openComposeModal();
                    } else {
                        console.error('La fonction openComposeModal n\'est pas disponible');
                        // Fallback si la fonction n'est pas disponible
                        const modal = document.getElementById('compose-modal');
                        if (modal) modal.showModal();
                    }
                });
            }

            // Vérification que le modal existe
            if (!document.getElementById('compose-modal')) {
                console.error('Le modal de composition n\'est pas présent dans le DOM');
            }

            // Amélioration de l'accessibilité pour la pagination
            const paginationBtns = document.querySelectorAll('.pagination-btn');
            paginationBtns.forEach(btn => {
                if (btn.classList.contains('pagination-btn-active')) {
                    btn.setAttribute('aria-current', 'page');
                }
            });
        });
    </script>
@endpush
