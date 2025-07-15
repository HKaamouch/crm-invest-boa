@extends('layouts.app')

@section('title', 'Interactions')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Interactions</h1>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
            <div class="flex items-center">
                <div class="rounded-full bg-blue-100 p-3 mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Total des interactions</p>
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
                    <p class="text-sm font-medium text-slate-500">Ce mois</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['ce_mois'] }}</p>
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
                    <p class="text-sm font-medium text-slate-500">Cette semaine</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['cette_semaine'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
            <div class="flex items-center">
                <div class="rounded-full bg-amber-100 p-3 mr-4">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Aujourd'hui</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['aujourd_hui'] }}</p>
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
            <form action="{{ route('interactions.index') }}" method="GET">
                <input type="hidden" name="per_page" value="{{ $perPage }}">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Recherche -->
                    <div class="form-control">
                        <label for="search" class="label">Rechercher</label>
                        <input type="text" name="search" id="search"
                               class="input input-bordered w-full"
                               placeholder="Description, investisseur..."
                               value="{{ request('search') }}">
                    </div>

                    <!-- Type d'interaction -->
                    <div class="form-control">
                        <label for="type" class="label">Type d'interaction</label>
                        <select name="type" id="type" class="select select-bordered w-full">
                            <option value="">Tous les types</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}"
                                    @selected(request('type') == $type)>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Investisseur -->
                    <div class="form-control">
                        <label for="investisseur" class="label">Investisseur</label>
                        <select name="investisseur" id="investisseur" class="select select-bordered w-full">
                            <option value="">Tous les investisseurs</option>
                            @foreach($investisseurs as $investisseur)
                                <option value="{{ $investisseur->id }}"
                                    @selected(request('investisseur') == $investisseur->id)>
                                    {{ $investisseur->nom }} {{ $investisseur->prenom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Utilisateur -->
                    <div class="form-control">
                        <label for="utilisateur" class="label">Agent</label>
                        <select name="utilisateur" id="utilisateur" class="select select-bordered w-full">
                            <option value="">Tous les agents</option>
                            @foreach($utilisateurs as $utilisateur)
                                <option value="{{ $utilisateur->id }}"
                                    @selected(request('utilisateur') == $utilisateur->id)>
                                    {{ $utilisateur->nom_complet }}
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

                <div class="flex justify-between items-center">
                    <div class="flex space-x-2">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Rechercher
                        </button>
                        <a href="{{ route('interactions.index') }}" class="btn btn-outline btn-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Réinitialiser
                        </a>
                    </div>

                    <!-- Nombre par page -->
                    <div class="flex items-center">
                        <select name="per_page" onchange="this.form.submit()" class="select select-bordered select-sm py-0 min-h-0 h-9">
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 par page</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 par page</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 par page</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des interactions -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-lg font-medium text-slate-800">
                {{ $interactions->total() }} interaction(s) trouvée(s)
            </h2>
        </div>

        @if($interactions->isEmpty())
            <div class="p-6 text-center">
                <div class="py-8">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-slate-500">Aucune interaction trouvée avec ces critères.</p>
                    <a href="{{ route('interactions.index') }}" class="btn btn-outline btn-sm mt-4">
                        Réinitialiser les filtres
                    </a>
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead class="bg-slate-50 text-xs font-medium text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left">Date</th>
                        <th class="px-6 py-3 text-left">Type</th>
                        <th class="px-6 py-3 text-left">Investisseur</th>
                        <th class="px-6 py-3 text-left">Description</th>
                        <th class="px-6 py-3 text-left">Agent</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                    @foreach($interactions as $interaction)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-800">
                                    {{ \Carbon\Carbon::parse($interaction->date_interaction)->format('d/m/Y') }}
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{ \Carbon\Carbon::parse($interaction->date_interaction)->format('H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $interaction->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="{{ $interaction->investor->avatar_url ?? asset('images/avatar-placeholder.png') }}" alt="{{ $interaction->investor->nom_complet }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-slate-800">
                                            <a href="{{ route('investors.show', $interaction->investor) }}" class="hover:text-blue-600">
                                                {{ $interaction->investor->nom_complet }}
                                            </a>
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            {{ $interaction->investor->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-800 line-clamp-2">
                                    {{ Str::limit($interaction->description, 100) }}
                                </div>
                                @if($interaction->piece_jointe)
                                    <div class="text-xs text-slate-500 flex items-center mt-1">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                        </svg>
                                        Pièce jointe
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-800">
                                    {{ $interaction->user->nom_complet }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('interactions.show', $interaction) }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Détails
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 sm:px-6 py-4 border-t border-slate-200 bg-slate-50">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <!-- Informations sur la pagination -->
                    <div class="text-sm text-slate-800">
                        {{ $interactions->firstItem() ?? 0 }} à {{ $interactions->lastItem() ?? 0 }}
                        sur {{ $interactions->total() }} résultats
                    </div>

                    <!-- Navigation pagination -->
                    {{ $interactions->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection

@push('breadcrumbs')
    <li>
        <span class="text-slate-500">Interactions</span>
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

