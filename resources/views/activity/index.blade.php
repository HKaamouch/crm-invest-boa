@extends('layouts.app')

@section('title', 'Journal des activités')

@php
// Fonctions auxiliaires pour traduire les actions et les noms de modèles
function getReadableAction($action) {
    $actions = [
        'created' => 'Création',
        'updated' => 'Modification',
        'deleted' => 'Suppression',
        'attached' => 'Association',
        'detached' => 'Dissociation'
    ];
    return $actions[$action] ?? $action;
}

function getReadableModelName($modelName) {
    if (!$modelName) return '';

    // Extraire le nom du modèle à partir du chemin complet
    $parts = explode('\\', $modelName);
    $rawModelName = end($parts);

    // Traduire les noms de modèles
    $modelTranslations = [
        'User' => 'Utilisateur',
        'Investor' => 'Investisseur',
        'Organisation' => 'Organisation',
        'Contact' => 'Contact',
        'CategorieInvestisseur' => 'Catégorie',
        'InvestorComment' => 'Commentaire',
        'Interaction' => 'Interaction',
        'Email' => 'Email'
    ];

    return $modelTranslations[$rawModelName] ?? $rawModelName;
}

function getIconClass($action) {
    switch ($action) {
        case 'created': return 'bg-green-100 text-green-600';
        case 'updated': return 'bg-blue-100 text-blue-600';
        case 'deleted': return 'bg-red-100 text-red-600';
        default: return 'bg-purple-100 text-purple-600';
    }
}

// Fonction de débogage pour afficher les options sélectionnées
function isSelected($currentValue, $optionValue) {
    return $currentValue == $optionValue ? 'selected' : '';
}
@endphp

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Journal des activités</h1>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-6">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-lg font-medium text-slate-800">Recherche et filtres</h2>
        </div>

        <div class="p-6">
            <form action="{{ route('activity.index') }}" method="GET">
                <input type="hidden" name="per_page" value="{{ $perPage }}">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Recherche -->
                    <div class="form-control">
                        <label for="search" class="label">Rechercher</label>
                        <input type="text" name="search" id="search"
                               class="input input-bordered w-full"
                               placeholder="Rechercher..."
                               value="{{ request('search') }}">
                    </div>

                    <!-- Utilisateur -->
                    <div class="form-control">
                        <label for="user" class="label">Utilisateur</label>
                        <select name="user" id="user" class="select select-bordered w-full">
                            <option value="">Tous les utilisateurs</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                    @selected(request('user') == $user->id)>
                                    {{ $user->nom_complet }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type d'action -->
                    <div class="form-control">
                        <label for="action" class="label">Action</label>
                        <select name="action" id="action" class="select select-bordered w-full">
                            <option value="">Toutes les actions</option>
                            @foreach($actions as $action)
                                <option value="{{ $action }}"
                                    @selected(request('action') == $action)>
                                    {{ getReadableAction($action) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type d'entité -->
                    <div class="form-control">
                        <label for="entity" class="label">Type d'entité</label>
                        <select name="entity" id="entity" class="select select-bordered w-full">
                            <option value="">Toutes les entités</option>
                            @foreach($entities as $entity)
                                <option value="{{ $entity }}"
                                    @selected(request('entity') == $entity)>
                                    {{ getReadableModelName($entity) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date de début -->
                    <div class="form-control">
                        <label for="date_from" class="label">Date de début</label>
                        <input type="date" name="date_from" id="date_from"
                               class="input input-bordered w-full"
                               value="{{ request('date_from') }}">
                    </div>

                    <!-- Date de fin -->
                    <div class="form-control">
                        <label for="date_to" class="label">Date de fin</label>
                        <input type="date" name="date_to" id="date_to"
                               class="input input-bordered w-full"
                               value="{{ request('date_to') }}">
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <div class="flex space-x-2">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Rechercher
                        </button>
                        <a href="{{ route('activity.index') }}" class="btn btn-outline btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
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

    <!-- Liste des activités -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-lg font-medium text-slate-800">
                {{ $activities->total() }} activité(s) trouvée(s)
            </h2>
        </div>

        @if($activities->isEmpty())
            <div class="p-6 text-center">
                <div class="py-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-slate-500">Aucune activité trouvée avec ces critères.</p>
                    <a href="{{ route('activity.index') }}" class="btn btn-outline btn-sm mt-4">
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
                        <th class="px-6 py-3 text-left">Utilisateur</th>
                        <th class="px-6 py-3 text-left">Action</th>
                        <th class="px-6 py-3 text-left">Entité</th>
                        <th class="px-6 py-3 text-right">Détails</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                    @foreach($activities as $activity)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 text-sm text-slate-800">
                                {{ $activity->created_at->format('d/m/Y H:i') }}
                                <div class="text-xs text-slate-500">
                                    {{ $activity->created_at->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        @if($activity->causer)
                                            @if($activity->causer->avatar)
                                                <img class="h-8 w-8 rounded-full" src="{{ Storage::url($activity->causer->avatar) }}" alt="{{ $activity->causer->nom_complet }}">
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm">
                                                    {{ strtoupper(substr($activity->causer->nom_complet ?? 'U', 0, 1)) }}
                                                </div>
                                            @endif
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-slate-300 flex items-center justify-center text-white font-bold text-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-slate-800">
                                            @if($activity->causer)
                                                <a href="{{ route('users.show', $activity->causer) }}" class="hover:text-blue-600">
                                                    {{ $activity->causer->nom_complet }}
                                                </a>
                                            @else
                                                <span class="text-slate-500">Système</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ getIconClass($activity->description) }}">
                                    {{ getReadableAction($activity->description) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-800">
                                {{ getReadableModelName($activity->subject_type) }}
                                @if($activity->subject_id)
                                    <span class="text-slate-500">#{{ $activity->subject_id }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end">
                                    <a href="{{ route('activity.show', $activity) }}" class="text-blue-600 hover:text-blue-800" title="Voir les détails">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </div>
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
                        {{ $activities->firstItem() ?? 0 }} à {{ $activities->lastItem() ?? 0 }}
                        sur {{ $activities->total() }} résultats
                    </div>

                    <!-- Navigation pagination -->
                    {{ $activities->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection

@push('breadcrumbs')
    <li>
        <span class="text-slate-500">Journal des activités</span>
    </li>
@endpush
