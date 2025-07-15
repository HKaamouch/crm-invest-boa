@extends('layouts.app')

@section('title', 'Détails de l\'utilisateur')

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
@endphp

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Détails de l'utilisateur</h1>
        <div class="flex space-x-2">
            <a href="{{ route('users.index') }}" class="btn btn-outline">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour
            </a>
            @can('update', $user)
                <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Modifier
                </a>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations utilisateur -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-lg font-medium text-slate-800">Informations utilisateur</h2>
                </div>

                <div class="p-6 flex items-start">
                    <div class="flex-none mr-6">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->nom_complet }}" class="h-32 w-32 rounded-full object-cover">
                        @else
                            <div class="h-32 w-32 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-4xl">
                                {{ strtoupper(substr($user->nom_complet, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow">
                        <div class="mb-6">
                            <h3 class="text-xl font-semibold text-slate-800">{{ $user->nom_complet }}</h3>
                            <div class="flex items-center space-x-2 text-sm text-slate-500 mt-1">
                                <span>{{ $user->email }}</span>
                                <span class="text-slate-300">•</span>
                                <span>{{ $user->telephone ?? 'Pas de téléphone' }}</span>
                            </div>

                            <div class="mt-3">
                                @foreach($user->roles as $role)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $role->name === 'Administrateur' ? 'bg-red-100 text-red-800' :
                                           ($role->name === 'Éditeur' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                                        {{ $role->name }}
                                    </span>
                                @endforeach

                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ml-2
                                    {{ $user->actif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->actif ? 'Actif' : 'Inactif' }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-slate-500 mb-1">Création du compte</p>
                                <p class="font-medium">{{ $user->created_at->format('d/m/Y à H:i') }}</p>
                            </div>

                            <div>
                                <p class="text-slate-500 mb-1">Dernière mise à jour</p>
                                <p class="font-medium">{{ $user->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permissions de l'utilisateur -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mt-6">
                <div class="p-6 border-b border-slate-200 flex justify-between items-center">
                    <h2 class="text-lg font-medium text-slate-800">Permissions</h2>
                    <div class="text-sm text-slate-500">
                        {{ $user->getAllPermissions()->count() }} permissions
                    </div>
                </div>

                <div class="p-6">
                    @if($user->getAllPermissions()->isEmpty() && $user->roles->isEmpty())
                        <p class="text-slate-500">Aucune permission attribuée à cet utilisateur.</p>
                    @else
                        <div class="space-y-6">
                            @php
                                $permissionsByModule = $user->getAllPermissions()->groupBy('module');
                            @endphp

                            @foreach($permissionsByModule as $module => $permissions)
                                <div class="border border-slate-200 rounded-lg overflow-hidden">
                                    <div class="bg-slate-50 px-4 py-3 border-b border-slate-200">
                                        <h3 class="text-md font-medium text-slate-700">{{ $module ?? 'Autres' }}</h3>
                                    </div>
                                    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                                        @foreach($permissions as $permission)
                                            <div class="flex items-center bg-slate-50 rounded p-3">
                                                <div class="rounded-full bg-green-100 p-1.5 mr-3">
                                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-slate-700">{{ $permission->description ?? $permission->name }}</p>
                                                    <p class="text-xs text-slate-500">{{ $permission->name }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Activités récentes -->
        <div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex justify-between items-center">
                    <h2 class="text-lg font-medium text-slate-800">Activités récentes</h2>
                </div>

                <div class="divide-y divide-slate-100" id="activities-container">
                    @if($activities->isEmpty())
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-slate-500">Aucune activité enregistrée pour cet utilisateur.</p>
                        </div>
                    @else
                        @foreach($activities as $activity)
                            <div class="p-4 hover:bg-slate-50 activity-item">
                                <div class="flex items-start">
                                    <div class="flex-none mr-3 mt-1">
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full
                                            {{ $activity->description === 'created' ? 'bg-green-100 text-green-600' :
                                               ($activity->description === 'updated' ? 'bg-blue-100 text-blue-600' :
                                               ($activity->description === 'deleted' ? 'bg-red-100 text-red-600' : 'bg-purple-100 text-purple-600')) }}">
                                            @if($activity->description === 'created')
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                            @elseif($activity->description === 'updated')
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            @elseif($activity->description === 'deleted')
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex-grow">
                                        <div class="flex justify-between">
                                            <div class="text-sm">
                                                <span class="font-medium text-slate-800">
                                                    {{ getReadableAction($activity->description) }} {{ getReadableModelName($activity->subject_type) }}
                                                </span>
                                            </div>
                                            <div class="text-xs text-slate-500">
                                                {{ $activity->created_at->diffForHumans() }}
                                            </div>
                                        </div>

                                        <div class="mt-1 text-xs text-slate-500">
                                            @if($activity->properties->isNotEmpty() && count($activity->properties->except(['user_id'])) > 0)
                                                <button type="button"
                                                        class="text-blue-600 hover:text-blue-800 view-details"
                                                        data-activity="{{ $activity->id }}">
                                                    Voir les détails
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div id="load-more-wrapper" class="py-4 text-center">
                    <button type="button" id="load-more-activities" class="text-sm text-blue-600 hover:text-blue-800 flex items-center justify-center mx-auto">
                        <svg id="load-more-spinner" class="animate-spin h-5 w-5 text-blue-600 mr-2 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span id="load-more-label">Voir plus</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour les détails d'activité -->
    <div id="activity-details-modal" class="modal" data-state="closed">
        <div class="modal-box">
            <h3 class="font-bold text-lg" id="modal-title">Détails de l'activité</h3>
            <div class="py-4" id="modal-content">
                <!-- Le contenu sera inséré dynamiquement ici -->
            </div>
            <div class="modal-action">
                <button type="button" class="btn" id="close-modal">Fermer</button>
            </div>
        </div>
        <div class="modal-backdrop"></div>
    </div>
@endsection

@push('breadcrumbs')
    <li>
        <a href="{{ route('users.index') }}" class="text-blue-600 hover:text-blue-700">
            Utilisateurs
        </a>
    </li>
    <li class="mx-2 text-slate-400">/</li>
    <li>
        <span class="text-slate-500">{{ $user->nom_complet }}</span>
    </li>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let page = 1;
        let hasMore = true;
        const userId = {{ $user->id }};
        const loadMoreBtn = document.getElementById('load-more-activities');
        const loadMoreLabel = document.getElementById('load-more-label');
        const loadMoreSpinner = document.getElementById('load-more-spinner');
        const activitiesContainer = document.getElementById('activities-container');
        const modal = document.getElementById('activity-details-modal');
        const modalTitle = document.getElementById('modal-title');
        const modalContent = document.getElementById('modal-content');
        const closeModalBtn = document.getElementById('close-modal');

        // Initialiser la visibilité du bouton "Voir plus"
        if (@json($activities->count()) < 3) {
            document.getElementById('load-more-wrapper').classList.add('hidden');
        }

        loadMoreBtn.addEventListener('click', function() {
            if (!hasMore) return;
            loadMoreSpinner.classList.remove('hidden');
            loadMoreLabel.textContent = 'Chargement...';
            loadMoreBtn.disabled = true;
            loadMoreBtn.classList.add('text-slate-400');
            loadMoreBtn.classList.remove('text-blue-600', 'hover:text-blue-800');
            loadMoreActivities();
        });

        closeModalBtn.addEventListener('click', function() {
            closeModal();
        });

        document.querySelector('.modal-backdrop').addEventListener('click', function() {
            closeModal();
        });

        // Fonctions JS pour traduire les actions et modèles (corrige l'erreur ReferenceError)
        function getReadableAction(action) {
            const actions = {
                'created': 'Création',
                'updated': 'Modification',
                'deleted': 'Suppression',
                'attached': 'Association',
                'detached': 'Dissociation'
            };
            return actions[action] ?? action;
        }

        function getReadableModelName(modelName) {
            if (!modelName) return '';
            const parts = modelName.split('\\');
            const rawModelName = parts[parts.length - 1];
            const modelTranslations = {
                'User': 'Utilisateur',
                'Investor': 'Investisseur',
                'Organisation': 'Organisation',
                'Contact': 'Contact',
                'CategorieInvestisseur': 'Catégorie',
                'InvestorComment': 'Commentaire',
                'Interaction': 'Interaction',
                'Email': 'Email'
            };
            return modelTranslations[rawModelName] ?? rawModelName;
        }

        function loadMoreActivities() {
            page++;
            fetch(`/ajax/users/${userId}/activities?page=${page}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                loadMoreSpinner.classList.add('hidden');
                if (data.activities.length === 0) {
                    hasMore = false;
                    loadMoreLabel.textContent = 'Toutes les activités sont chargées';
                    loadMoreBtn.disabled = true;
                    loadMoreBtn.classList.add('text-slate-400');
                    loadMoreBtn.classList.remove('text-blue-600', 'hover:text-blue-800');
                    return;
                }
                // Ajouter les nouvelles activités à la suite
                data.activities.forEach(activity => {
                    const activityHtml = createActivityHTML(activity);
                    activitiesContainer.insertAdjacentHTML('beforeend', activityHtml);
                });
                // Réactiver les écouteurs d'événement pour les nouveaux éléments
                addEventListenersToDetailsButtons();
                // Gérer le bouton "Voir plus"
                if (!data.hasMore) {
                    hasMore = false;
                    loadMoreLabel.textContent = 'Toutes les activités sont chargées';
                    loadMoreBtn.disabled = true;
                    loadMoreBtn.classList.add('text-slate-400');
                    loadMoreBtn.classList.remove('text-blue-600', 'hover:text-blue-800');
                } else {
                    loadMoreLabel.textContent = 'Voir plus';
                    loadMoreBtn.disabled = false;
                    loadMoreBtn.classList.remove('text-slate-400');
                    loadMoreBtn.classList.add('text-blue-600', 'hover:text-blue-800');
                }
            })
            .catch(error => {
                loadMoreSpinner.classList.add('hidden');
                loadMoreLabel.textContent = 'Voir plus';
                loadMoreBtn.disabled = false;
                loadMoreBtn.classList.remove('text-slate-400');
                loadMoreBtn.classList.add('text-blue-600', 'hover:text-blue-800');
                console.error('Erreur lors du chargement des activités:', error);
            });
        }

        // Fonction pour créer le HTML d'une activité
        function createActivityHTML(activity) {
            const iconHtml = getActivityIconHTML(activity.description);
            const className = getActivityClassName(activity.description);

            return `
                <div class="p-4 hover:bg-slate-50 activity-item">
                    <div class="flex items-start">
                        <div class="flex-none mr-3 mt-1">
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full ${className}">
                                ${iconHtml}
                            </span>
                        </div>
                        <div class="flex-grow">
                            <div class="flex justify-between">
                                <div class="text-sm">
                                    <span class="font-medium text-slate-800">
                                        ${getReadableAction(activity.description)} ${getReadableModelName(activity.subject_type)}
                                    </span>
                                </div>
                                <div class="text-xs text-slate-500">
                                    ${activity.created_at_human}
                                </div>
                            </div>

                            <div class="mt-1 text-xs text-slate-500">
                                ${hasDetailsToShow(activity) ?
                                    `<button type="button"
                                        class="text-blue-600 hover:text-blue-800 view-details"
                                        data-activity="${activity.id}">
                                        Voir les détails
                                    </button>` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function getActivityIconHTML(description) {
            switch (description) {
                case 'created':
                    return `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>`;
                case 'updated':
                    return `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>`;
                case 'deleted':
                    return `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>`;
                default:
                    return `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>`;
            }
        }

        function getActivityClassName(description) {
            switch (description) {
                case 'created':
                    return 'bg-green-100 text-green-600';
                case 'updated':
                    return 'bg-blue-100 text-blue-600';
                case 'deleted':
                    return 'bg-red-100 text-red-600';
                default:
                    return 'bg-purple-100 text-purple-600';
            }
        }

        function hasDetailsToShow(activity) {
            if (!activity.properties || Object.keys(activity.properties).length === 0) {
                return false;
            }

            // Exclure user_id du comptage
            const props = { ...activity.properties };
            delete props.user_id;

            return Object.keys(props).length > 0;
        }

        // Fonction pour ajouter des écouteurs d'événement aux boutons de détails
        function addEventListenersToDetailsButtons() {
            document.querySelectorAll('.view-details').forEach(button => {
                button.addEventListener('click', function() {
                    const activityId = this.getAttribute('data-activity');
                    showActivityDetails(activityId);
                });
            });
        }

        // Fonction pour afficher les détails d'une activité dans le modal
        function showActivityDetails(activityId) {
            fetch(`/ajax/activities/${activityId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Erreur API');
                return response.json();
            })
            .then(data => {
                modalTitle.textContent = `${getReadableAction(data.description)} ${getReadableModelName(data.subject_type)}`;

                // Construire le contenu du modal
                let contentHtml = '<div class="space-y-4">';

                if (data.properties && Object.keys(data.properties).length > 0) {
                    // Exclure user_id de l'affichage
                    const props = { ...data.properties };
                    delete props.user_id;

                    if (props.attributes) {
                        contentHtml += '<div class="bg-slate-50 p-4 rounded-lg">';
                        contentHtml += '<h4 class="font-medium text-slate-700 mb-2">Attributs</h4>';
                        contentHtml += '<div class="space-y-2">';

                        Object.entries(props.attributes).forEach(([key, value]) => {
                            if (key !== 'user_id' && key !== 'created_at' && key !== 'updated_at' && key !== 'password') {
                                contentHtml += `
                                    <div class="grid grid-cols-3 gap-4">
                                        <div class="font-medium text-slate-600">${formatKey(key)}</div>
                                        <div class="col-span-2 text-slate-800">${formatValue(value)}</div>
                                    </div>
                                `;
                            }
                        });

                        contentHtml += '</div></div>';
                    }

                    if (props.old) {
                        contentHtml += '<div class="bg-amber-50 p-4 rounded-lg mt-4">';
                        contentHtml += '<h4 class="font-medium text-amber-700 mb-2">Anciennes valeurs</h4>';
                        contentHtml += '<div class="space-y-2">';

                        Object.entries(props.old).forEach(([key, value]) => {
                            if (key !== 'user_id' && key !== 'created_at' && key !== 'updated_at' && key !== 'password') {
                                contentHtml += `
                                    <div class="grid grid-cols-3 gap-4">
                                        <div class="font-medium text-amber-600">${formatKey(key)}</div>
                                        <div class="col-span-2 text-amber-800">${formatValue(value)}</div>
                                    </div>
                                `;
                            }
                        });

                        contentHtml += '</div></div>';
                    }
                } else {
                    contentHtml += '<p class="text-slate-500">Aucun détail disponible pour cette activité.</p>';
                }

                contentHtml += '</div>';
                modalContent.innerHTML = contentHtml;

                // Ouvrir le modal
                openModal();
            })
            .catch(error => {
                console.error('Erreur lors du chargement des détails:', error);
                modalContent.innerHTML = '<p class="text-red-500">Une erreur est survenue lors du chargement des détails.</p>';
                openModal();
            });
        }

        function formatKey(key) {
            // Convertir snake_case en format lisible
            return key
                .replace(/_/g, ' ')
                .replace(/\b\w/g, l => l.toUpperCase());
        }

        function formatValue(value) {
            if (value === null || value === undefined) return '<span class="text-slate-400">Non défini</span>';
            if (typeof value === 'boolean') return value ? 'Oui' : 'Non';
            if (typeof value === 'object') return JSON.stringify(value, null, 2);
            return value.toString();
        }

        function openModal() {
            modal.setAttribute('data-state', 'open');
            modal.classList.add('modal-open');
        }

        function closeModal() {
            modal.setAttribute('data-state', 'closed');
            modal.classList.remove('modal-open');
        }

        // Initialiser les écouteurs d'événement
        addEventListenersToDetailsButtons();
    });
</script>
@endpush
