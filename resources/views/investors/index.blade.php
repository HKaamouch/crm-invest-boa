@extends('layouts.app')

@section('title', 'Investisseurs')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Investisseurs</h1>
        <a href="{{ route('investors.create') }}" class="btn btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Nouvel investisseur
        </a>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
            <div class="flex items-center">
                <div class="rounded-full bg-blue-100 p-3 mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Total des investisseurs</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
            <div class="flex items-center">
                <div class="rounded-full bg-green-100 p-3 mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Nouveaux ce mois</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['nouveau_mois'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
            <div class="flex items-center">
                <div class="rounded-full bg-purple-100 p-3 mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Actifs (90 jours)</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['actifs_90j'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-6">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-lg font-medium text-slate-800">Recherche et filtres</h2>
        </div>

        <div class="p-6">
            <form action="{{ route('investors.index') }}" method="GET">
                <input type="hidden" name="view" value="{{ $view }}">
                <input type="hidden" name="per_page" value="{{ $perPage }}">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Recherche -->
                    <div class="form-control">
                        <label for="search" class="label">Rechercher</label>
                        <input type="text" name="search" id="search"
                               class="input input-bordered w-full"
                               placeholder="Nom, email, fonction..."
                               value="{{ request('search') }}">
                    </div>

                    <!-- Catégorie -->
                    <div class="form-control">
                        <label for="categorie_id" class="label">Catégorie</label>
                        <select name="categorie_id" id="categorie_id" class="select select-bordered w-full">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie->id }}"
                                    @selected(request('categorie_id') == $categorie->id)>
                                    {{ $categorie->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pays -->
                    <div class="form-control">
                        <label for="pays" class="label">Pays</label>
                        <select name="pays" id="pays" class="select select-bordered w-full">
                            <option value="">Tous les pays</option>
                            @foreach($pays as $pays_item)
                                <option value="{{ $pays_item }}"
                                    @selected(request('pays') == $pays_item)>
                                    {{ $pays_item }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Langue -->
                    <div class="form-control">
                        <label for="langue" class="label">Langue préférée</label>
                        <select name="langue" id="langue" class="select select-bordered w-full">
                            <option value="">Toutes les langues</option>
                            @foreach($langues as $langue)
                                <option value="{{ $langue }}"
                                    @selected(request('langue') == $langue)>
                                    {{ $langue }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Niveau d'influence -->
                    <div class="form-control">
                        <label for="influence" class="label">Niveau d'influence</label>
                        <select name="influence" id="influence" class="select select-bordered w-full">
                            <option value="">Tous les niveaux</option>
                            @foreach($niveauxInfluence as $niveau)
                                <option value="{{ $niveau }}"
                                    @selected(request('influence') == $niveau)>
                                    {{ $niveau }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tags -->
                    <div class="form-control">
                        <label for="tags" class="label">Tags</label>
                        <input type="text" name="tags" id="tags"
                               class="input input-bordered w-full"
                               placeholder="Séparer par des virgules"
                               value="{{ is_array(request('tags')) ? implode(', ', request('tags')) : request('tags') }}">
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <div class="flex space-x-2">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Rechercher
                        </button>
                        <a href="{{ route('investors.index') }}" class="btn btn-outline btn-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Réinitialiser
                        </a>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Sélecteur de vue -->
                        <div class="flex rounded-lg border border-slate-300 overflow-hidden">
                            <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}"
                               class="flex items-center justify-center px-3 py-2 {{ $view === 'list' ? 'bg-blue-500 text-white' : 'bg-white text-slate-600 hover:bg-slate-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}"
                               class="flex items-center justify-center px-3 py-2 {{ $view === 'grid' ? 'bg-blue-500 text-white' : 'bg-white text-slate-600 hover:bg-slate-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                </svg>
                            </a>
                        </div>

                        <!-- Nombre par page -->
                        <div class="flex items-center">
                            <select name="per_page" onchange="this.form.submit()" class="select select-bordered select-sm py-0 min-h-0 h-9">
                                <option value="12" {{ $perPage == 12 ? 'selected' : '' }}>12 par page</option>
                                <option value="24" {{ $perPage == 24 ? 'selected' : '' }}>24 par page</option>
                                <option value="48" {{ $perPage == 48 ? 'selected' : '' }}>48 par page</option>
                                <option value="96" {{ $perPage == 96 ? 'selected' : '' }}>96 par page</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des investisseurs -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-lg font-medium text-slate-800">
                {{ $investors->total() }} investisseur(s) trouvé(s)
            </h2>
        </div>

        @if($investors->isEmpty())
            <div class="p-6 text-center">
                <div class="py-8">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-slate-500">Aucun investisseur trouvé avec ces critères.</p>
                    <a href="{{ route('investors.index') }}" class="btn btn-outline btn-sm mt-4">
                        Réinitialiser les filtres
                    </a>
                </div>
            </div>
        @else
            @if($view === 'grid')
                <div class="p-4 sm:p-6 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    @foreach($investors as $investor)
                        <div class="border border-slate-200 rounded-lg hover:shadow-md transition-shadow flex flex-col h-full">
                            <div class="p-4 flex flex-col h-full">
                                <!-- En-tête de la carte -->
                                <div class="flex items-start mb-3">
                                    <div class="flex-shrink-0 mr-3">
                                        <img src="{{ $investor->avatar_url }}" alt="{{ $investor->nom_complet }}"
                                             class="w-12 h-12 rounded-full object-cover">
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-base font-semibold text-slate-800 mb-1 line-clamp-2 break-words">
                                            @if($investor->organisation_principale)
                                                <a href="{{ route('investors.show', $investor) }}" class="hover:text-blue-600">
                                                    {{ $investor->organisation_principale->raison_sociale }}
                                                </a>
                                            @elseif($investor->organisation)
                                                <a href="{{ route('investors.show', $investor) }}" class="hover:text-blue-600">
                                                    {{ $investor->organisation }}
                                                </a>
                                            @else
                                                <a href="{{ route('investors.show', $investor) }}" class="hover:text-blue-600">
                                                    {{ $investor->nom_complet }}
                                                </a>
                                            @endif
                                        </h3>
                                        <p class="text-sm text-slate-500 line-clamp-1">
                                            {{ $investor->nom_complet }} - {{ $investor->fonction ?: 'Non précisé' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Contenu de la carte -->
                                <div class="flex flex-col space-y-2 mb-3 flex-grow">
                                    <div class="flex items-center text-sm">
                                        <span class="w-4 h-4 inline-flex mr-2 flex-shrink-0 text-blue-600">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                        </span>
                                        <span class="truncate">{{ $investor->email }}</span>
                                    </div>
                                    @if($investor->telephone_principal)
                                        <div class="flex items-center text-sm">
                                            <span class="w-4 h-4 inline-flex mr-2 flex-shrink-0 text-blue-600">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                            </span>
                                            <span class="truncate">{{ $investor->telephone_principal }}</span>
                                        </div>
                                    @endif
                                    @if(!$investor->organisation_principale && !$investor->organisation)
                                        <div class="flex items-center text-sm">
                                            <span class="w-4 h-4 inline-flex mr-2 flex-shrink-0 text-blue-600">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </span>
                                            <span class="truncate text-slate-500">Organisation non précisée</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Pied de la carte -->
                                <div class="pt-2 border-t border-slate-100 mt-auto">
                                    <div class="flex flex-wrap justify-between items-center gap-2">
                                        <div class="flex flex-wrap gap-1">
                                            <span class="badge badge-sm {{ $investor->influence_badge }}">
                                                {{ $investor->niveau_influence }}
                                            </span>
                                            @if($investor->categorie)
                                                <span class="badge badge-sm" style="background-color: {{ $investor->categorie->couleur_hexa }}; color: white;">
                                                    {{ $investor->categorie->nom }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="text-xs text-slate-500">
                                            {{ $investor->pays }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="table-auto w-full">
                        <thead class="bg-slate-50 text-xs font-medium text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 text-left">Point de contact</th>
                            <th class="px-6 py-3 text-left">Contact</th>
                            <th class="px-6 py-3 text-left">Organisation</th>
                            <th class="px-6 py-3 text-center">Catégorie</th>
                            <th class="px-6 py-3 text-center">Influence</th>
                            <th class="px-6 py-3 text-center">Interactions</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                        @foreach($investors as $investor)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <img src="{{ $investor->avatar_url }}" alt="{{ $investor->nom_complet }}"
                                                 class="w-10 h-10 rounded-full object-cover">
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-800">
                                                <a href="{{ route('investors.show', $investor) }}" class="hover:text-blue-600">
                                                    {{ $investor->nom_complet }}
                                                </a>
                                            </p>
                                            <p class="text-xs text-slate-500">{{ $investor->pays }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-slate-800">{{ $investor->email }}</p>
                                    @if($investor->telephone_principal)
                                        <p class="text-xs text-slate-500">{{ $investor->telephone_principal }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($investor->organisation_principale)
                                        <p class="text-sm text-slate-800">
                                            {{ $investor->organisation_principale->raison_sociale }}
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            {{ $investor->organisation_principale->pivot->poste ?: $investor->fonction }}
                                        </p>
                                    @elseif($investor->organisation)
                                        <p class="text-sm text-slate-800">{{ $investor->organisation }}</p>
                                        <p class="text-xs text-slate-500">{{ $investor->fonction }}</p>
                                    @else
                                        <p class="text-sm text-slate-400">Non précisé</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($investor->categorie)
                                        <span class="badge" style="background-color: {{ $investor->categorie->couleur_hexa }}; color: white;">
                                                {{ $investor->categorie->nom }}
                                            </span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                        <span class="badge {{ $investor->influence_badge }}">
                                            {{ $investor->niveau_influence }}
                                        </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center items-center space-x-1">
                                        <span class="text-sm font-medium">{{ $investor->interactions_count }}</span>
                                        @if($investor->derniere_interaction)
                                            <span class="text-xs text-slate-500">({{ $investor->derniere_interaction_formatee }})</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="flex justify-end items-center space-x-2">
                                        <a href="{{ route('investors.show', $investor) }}"
                                           class="btn btn-sm btn-outline btn-square">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('investors.edit', $investor) }}"
                                           class="btn btn-sm btn-outline btn-square">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif


                <div class="px-4 sm:px-6 py-4 border-t border-slate-200 bg-slate-50">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <!-- Informations sur la pagination -->
                        <div class="text-sm text-slate-800">
                             {{ $investors->firstItem() ?? 0 }} à {{ $investors->lastItem() ?? 0 }}
                            sur {{ $investors->total() }} résultats
                        </div>

                        <!-- Navigation pagination -->
                        @if ($investors->hasPages())
                            <nav class="pagination-nav" aria-label="Navigation de pagination">
                                <div class="flex items-center space-x-1">
                                    {{-- Bouton précédent --}}
                                    @if ($investors->onFirstPage())
                                        <span class="pagination-btn pagination-btn-disabled">
                                       <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                       </svg>
                                       <span class="hidden sm:inline ml-1">Précédent</span>
                                   </span>
                                    @else
                                        <a href="{{ $investors->previousPageUrl() }}" class="pagination-btn pagination-btn-nav">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                            </svg>
                                            <span class="hidden sm:inline ml-1">Précédent</span>
                                        </a>
                                    @endif

                                    {{-- Numéros de page --}}
                                    @foreach ($investors->getUrlRange(1, $investors->lastPage()) as $page => $url)
                                        @if ($page == $investors->currentPage())
                                            <span class="pagination-btn pagination-btn-active">{{ $page }}</span>
                                        @else
                                            <a href="{{ $url }}" class="pagination-btn pagination-btn-page">{{ $page }}</a>
                                        @endif
                                    @endforeach

                                    {{-- Bouton suivant --}}
                                    @if ($investors->hasMorePages())
                                        <a href="{{ $investors->nextPageUrl() }}" class="pagination-btn pagination-btn-nav">
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
@endsection

@push('breadcrumbs')
    <li>
        <span class="text-slate-500">Investisseurs</span>
    </li>
@endpush

@push('styles')
<style>
    /* Utilitaires pour limiter le nombre de lignes et gérer les débordements de texte */
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .break-words {
        word-break: break-word;
    }
</style>
@endpush
