@extends('layouts.app')

@section('title', 'Détails de l\'activité')

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

function formatKey($key) {
    // Convertir snake_case en format lisible
    return ucfirst(str_replace('_', ' ', $key));
}

function formatValue($value) {
    if ($value === null || $value === '') return '<span class="text-slate-400">Non défini</span>';
    if (is_bool($value)) return $value ? 'Oui' : 'Non';
    if (is_array($value) || is_object($value)) return json_encode($value, JSON_PRETTY_PRINT);
    return htmlspecialchars($value);
}
@endphp

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Détails de l'activité</h1>
        <div class="flex space-x-2">
            <a href="{{ route('activity.index') }}" class="btn btn-outline">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations sur l'activité -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-lg font-medium text-slate-800">Informations sur l'activité</h2>
                </div>

                <div class="p-6">
                    <div class="mb-6 flex items-start">
                        <div class="flex-none mr-6">
                            @php
                                $iconClass = 'bg-blue-100 text-blue-600';
                                if ($activity->description === 'created') {
                                    $iconClass = 'bg-green-100 text-green-600';
                                } elseif ($activity->description === 'deleted') {
                                    $iconClass = 'bg-red-100 text-red-600';
                                } elseif ($activity->description === 'updated') {
                                    $iconClass = 'bg-blue-100 text-blue-600';
                                }
                            @endphp
                            <div class="h-16 w-16 rounded-full {{ $iconClass }} flex items-center justify-center">
                                @if($activity->description === 'created')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                @elseif($activity->description === 'updated')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                @elseif($activity->description === 'deleted')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                @endif
                            </div>
                        </div>
                        <div class="flex-grow">
                            <h3 class="text-xl font-semibold text-slate-800">
                                {{ getReadableAction($activity->description) }} {{ getReadableModelName($activity->subject_type) }}
                            </h3>

                            <div class="flex items-center space-x-2 text-sm text-slate-500 mt-1">
                                <span>{{ $activity->created_at->format('d/m/Y H:i:s') }}</span>
                                <span class="text-slate-300">•</span>
                                <span>{{ $activity->created_at->diffForHumans() }}</span>
                            </div>

                            <div class="mt-4">
                                <div class="text-sm text-slate-600">
                                    <span class="font-medium">ID de l'entité:</span>
                                    <span>{{ $activity->subject_id ?? 'N/A' }}</span>
                                </div>
                                <div class="text-sm text-slate-600 mt-1">
                                    <span class="font-medium">Type d'entité:</span>
                                    <span>{{ getReadableModelName($activity->subject_type) }} ({{ $activity->subject_type }})</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-slate-500 mb-1">Utilisateur</p>
                            <p class="font-medium">
                                @if($activity->causer)
                                    <a href="{{ route('users.show', $activity->causer) }}" class="link link-primary">
                                        {{ $activity->causer->nom_complet }}
                                    </a>
                                @else
                                    <span class="text-slate-500">Système</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <p class="text-slate-500 mb-1">Action</p>
                            <p class="font-medium">{{ getReadableAction($activity->description) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Propriétés de l'activité -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mt-6">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-lg font-medium text-slate-800">Détails des changements</h2>
                </div>

                <div class="p-6">
                    @if($activity->properties->isEmpty())
                        <p class="text-slate-500">Aucun détail disponible pour cette activité.</p>
                    @else
                        <div class="space-y-6">
                            @if($activity->properties->has('attributes'))
                                <div class="border border-slate-200 rounded-lg overflow-hidden">
                                    <div class="bg-slate-50 px-4 py-3 border-b border-slate-200">
                                        <h3 class="text-md font-medium text-slate-700">Attributs</h3>
                                    </div>
                                    <div class="p-4 space-y-3">
                                        @foreach($activity->properties->get('attributes') as $key => $value)
                                            @if(!in_array($key, ['password', 'remember_token', 'two_factor_secret']))
                                                <div class="grid grid-cols-3 gap-4">
                                                    <div class="font-medium text-slate-600">{{ formatKey($key) }}</div>
                                                    <div class="col-span-2 text-slate-800">{!! formatValue($value) !!}</div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($activity->properties->has('old'))
                                <div class="border border-slate-200 rounded-lg overflow-hidden">
                                    <div class="bg-amber-50 px-4 py-3 border-b border-amber-200">
                                        <h3 class="text-md font-medium text-amber-800">Anciennes valeurs</h3>
                                    </div>
                                    <div class="p-4 space-y-3 bg-amber-50">
                                        @foreach($activity->properties->get('old') as $key => $value)
                                            @if(!in_array($key, ['password', 'remember_token', 'two_factor_secret']))
                                                <div class="grid grid-cols-3 gap-4">
                                                    <div class="font-medium text-amber-700">{{ formatKey($key) }}</div>
                                                    <div class="col-span-2 text-amber-900">{!! formatValue($value) !!}</div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Informations complémentaires -->
        <div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-lg font-medium text-slate-800">Informations techniques</h2>
                </div>

                <div class="p-6">
                    <ul class="space-y-4">
                        <li class="flex justify-between">
                            <span class="text-slate-600">ID</span>
                            <span class="font-medium">{{ $activity->id }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-slate-600">Log Name</span>
                            <span class="font-medium">{{ $activity->log_name ?? 'default' }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-slate-600">Type d'action</span>
                            <span class="font-medium">{{ $activity->description }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-slate-600">Date de création</span>
                            <span class="font-medium">{{ $activity->created_at->format('d/m/Y H:i:s') }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-slate-600">Type d'entité</span>
                            <span class="font-medium">{{ $activity->subject_type }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-slate-600">ID d'entité</span>
                            <span class="font-medium">{{ $activity->subject_id ?? 'N/A' }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-slate-600">Type d'auteur</span>
                            <span class="font-medium">{{ $activity->causer_type ?? 'N/A' }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-slate-600">ID d'auteur</span>
                            <span class="font-medium">{{ $activity->causer_id ?? 'N/A' }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            @if($activity->causer)
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mt-6">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-lg font-medium text-slate-800">Auteur de l'action</h2>
                    </div>

                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12">
                                @if($activity->causer->avatar)
                                    <img class="h-12 w-12 rounded-full" src="{{ Storage::url($activity->causer->avatar) }}" alt="{{ $activity->causer->nom_complet }}">
                                @else
                                    <div class="h-12 w-12 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-lg">
                                        {{ strtoupper(substr($activity->causer->nom_complet, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-slate-800">
                                    <a href="{{ route('users.show', $activity->causer) }}" class="hover:text-blue-600">
                                        {{ $activity->causer->nom_complet }}
                                    </a>
                                </h3>
                                <p class="text-sm text-slate-500">{{ $activity->causer->email }}</p>

                                <div class="mt-2">
                                    @foreach($activity->causer->roles as $role)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $role->name === 'Administrateur' ? 'bg-red-100 text-red-800' :
                                               ($role->name === 'Éditeur' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('breadcrumbs')
    <li>
        <a href="{{ route('activity.index') }}" class="text-blue-600 hover:text-blue-700">
            Journal des activités
        </a>
    </li>
    <li class="mx-2 text-slate-400">/</li>
    <li>
        <span class="text-slate-500">Détails</span>
    </li>
@endpush
