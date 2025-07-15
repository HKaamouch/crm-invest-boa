@extends('layouts.app')

@section('title', $contact->nom_complet)

@section('page-header')
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-6">
        <div class="flex items-start space-x-4">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-lg">
                {{ $contact->initiales }}
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-3xl font-bold text-slate-900">{{ $contact->nom_complet }}</h1>
                    @if($contact->actif)
                        <span class="badge badge-success">Actif</span>
                    @else
                        <span class="badge badge-error">Inactif</span>
                    @endif
                </div>

                <div class="flex flex-wrap items-center gap-4 text-slate-600 mb-3">
                    @if($contact->email)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <a href="mailto:{{ $contact->email }}" class="text-blue-600 hover:text-blue-800">
                                {{ $contact->email }}
                            </a>
                        </div>
                    @endif

                    @if($contact->telephone_principal)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <a href="tel:{{ $contact->telephone_principal }}" class="text-blue-600 hover:text-blue-800">
                                {{ $contact->telephone_principal }}
                            </a>
                        </div>
                    @endif

                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span>{{ $stats['organisations_total'] }} organisation(s)</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('contacts.index') }}" class="btn btn-outline btn-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour à la liste
            </a>

            <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-primary btn-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Modifier
            </a>

            @if($contact->email)
                <a href="mailto:{{ $contact->email }}" class="btn btn-success btn-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Envoyer Email
                </a>
            @endif

            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-outline btn-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                    </svg>
                    Actions
                </div>
                <ul tabindex="0" class="dropdown-content menu bg-white rounded-box z-50 w-52 p-2 shadow-xl border border-slate-200">
                    <li><button onclick="toggleStatus()" class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                            </svg>
                            {{ $contact->actif ? 'Désactiver' : 'Activer' }}
                        </button></li>
                    <li><hr class="my-1"></li>
                    <li><button onclick="confirmDelete()" class="text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Supprimer
                        </button></li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-50 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-slate-600">Total Organisations</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['organisations_total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-50 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-slate-600">Postes Actuels</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['organisations_actuelles'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-50 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-slate-600">Statut</p>
                    <p class="text-2xl font-bold {{ $contact->actif ? 'text-green-600' : 'text-red-600' }}">
                        {{ $contact->actif ? 'Actif' : 'Inactif' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contenu principal -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Informations détaillées -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900">Informations détaillées</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email -->
                        @if($contact->email)
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Email</dt>
                                <dd class="mt-1 text-sm text-slate-900">
                                    <a href="mailto:{{ $contact->email }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $contact->email }}
                                    </a>
                                </dd>
                            </div>
                        @endif

                        <!-- Téléphone -->
                        @if($contact->telephone)
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Téléphone fixe</dt>
                                <dd class="mt-1 text-sm text-slate-900">
                                    <a href="tel:{{ $contact->telephone }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $contact->telephone }}
                                    </a>
                                </dd>
                            </div>
                        @endif

                        <!-- Mobile -->
                        @if($contact->mobile)
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Mobile</dt>
                                <dd class="mt-1 text-sm text-slate-900">
                                    <a href="tel:{{ $contact->mobile }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $contact->mobile }}
                                    </a>
                                </dd>
                            </div>
                        @endif

                        <!-- Téléphone principal -->
                        @if($contact->telephone_principal)
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Téléphone principal</dt>
                                <dd class="mt-1 text-sm text-slate-900">
                                    <a href="tel:{{ $contact->telephone_principal }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $contact->telephone_principal }}
                                    </a>
                                </dd>
                            </div>
                        @endif
                    </dl>

                    <!-- Notes -->
                    @if($contact->notes)
                        <div class="mt-6 pt-6 border-t border-slate-200">
                            <dt class="text-sm font-medium text-slate-500 mb-2">Notes</dt>
                            <dd class="text-sm text-slate-900 leading-relaxed">{{ $contact->notes }}</dd>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Organisations liées -->
            @if($contact->organisations->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                    <div class="px-6 py-4 border-b border-slate-200">
                        <div class="flex justify-between items-center">
                            <h2 class="text-lg font-semibold text-slate-900">
                                Organisations ({{ $contact->organisations->count() }})
                            </h2>
                            <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-outline btn-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Gérer
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($contact->organisations as $organisation)
                                <div class="flex items-center justify-between p-4 border border-slate-200 rounded-lg hover:shadow-md transition-shadow">
                                    <div class="flex items-center space-x-4">
                                        @if($organisation->logo_path)
                                            <img src="{{ $organisation->logo_url }}"
                                                 alt="Logo {{ $organisation->raison_sociale }}"
                                                 class="w-12 h-12 rounded-lg object-cover border border-slate-200">
                                        @else
                                            <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center border border-slate-200">
                                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </div>
                                        @endif

                                        <div class="flex-1">
                                            <h3 class="font-semibold text-slate-900">{{ $organisation->raison_sociale }}</h3>
                                            <div class="flex flex-col text-sm text-slate-600 mt-1">
                                                @if($organisation->pivot->poste)
                                                    <span class="font-medium">{{ $organisation->pivot->poste }}</span>
                                                @endif
                                                <span class="text-xs">
                                                    @if($organisation->pivot->date_debut && $organisation->pivot->date_fin)
                                                        De {{ \Carbon\Carbon::parse($organisation->pivot->date_debut)->format('m/Y') }}
                                                        à {{ \Carbon\Carbon::parse($organisation->pivot->date_fin)->format('m/Y') }}
                                                    @elseif($organisation->pivot->date_debut)
                                                        {{ $organisation->pivot->actuel ? 'Depuis' : 'À partir de' }}
                                                        {{ \Carbon\Carbon::parse($organisation->pivot->date_debut)->format('m/Y') }}
                                                    @elseif($organisation->pivot->date_fin)
                                                        Jusqu'à {{ \Carbon\Carbon::parse($organisation->pivot->date_fin)->format('m/Y') }}
                                                    @else
                                                        {{ $organisation->pivot->actuel ? 'Période actuelle' : 'Période non précisée' }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-3">
                                        @if($organisation->pivot->actuel)
                                            <span class="badge badge-success badge-sm">Actuel</span>
                                        @else
                                            <span class="badge badge-outline badge-sm">Ancien</span>
                                        @endif

                                        <a href="{{ route('organisations.show', $organisation) }}"
                                           class="btn btn-ghost btn-xs"
                                           title="Voir l'organisation">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                    <div class="px-6 py-4 border-b border-slate-200">
                        <h2 class="text-lg font-semibold text-slate-900">Organisations</h2>
                    </div>
                    <div class="p-6 text-center">
                        <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <p class="text-slate-500 mb-4">Aucune organisation liée</p>
                        <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-primary btn-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Ajouter des organisations
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Actions rapides -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Actions rapides</h3>
                <div class="space-y-3">
                    @if($contact->email)
                        <a href="mailto:{{ $contact->email }}" class="btn btn-primary btn-sm w-full justify-start">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Envoyer un email
                        </a>
                    @endif

                    @if($contact->telephone_principal)
                        <a href="tel:{{ $contact->telephone_principal }}" class="btn btn-outline btn-sm w-full justify-start">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            Appeler
                        </a>
                    @endif

                    <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-outline btn-sm w-full justify-start">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Modifier la fiche
                    </a>

                    <!-- Divider -->
                    <div class="border-t border-slate-200 my-3"></div>

                    <button onclick="toggleStatus()" class="btn btn-outline btn-sm w-full justify-start {{ $contact->actif ? 'btn-warning' : 'btn-success' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                        </svg>
                        {{ $contact->actif ? 'Désactiver' : 'Activer' }}
                    </button>

                    <button onclick="confirmDelete()" class="btn btn-error btn-outline btn-sm w-full justify-start">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Supprimer
                    </button>
                </div>
            </div>

            <!-- Métadonnées système -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Informations système</h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Créé le</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $contact->created_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Modifié le</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $contact->updated_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Statut</dt>
                        <dd class="mt-1">
                            @if($contact->actif)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Actif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Inactif
                                </span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <dialog id="delete-modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg text-red-600">Confirmer la suppression</h3>
            <p class="py-4">
                Êtes-vous sûr de vouloir supprimer le contact <strong>{{ $contact->nom_complet }}</strong> ?
                Cette action est irréversible.
            </p>
            <div class="modal-action">
                <form method="POST" action="{{ route('contacts.destroy', $contact) }}">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn" onclick="document.getElementById('delete-modal').close()">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-error">
                        Supprimer définitivement
                    </button>
                </form>
            </div>
        </div>
    </dialog>
@endsection

@push('breadcrumbs')
    <li>
        <a href="{{ route('contacts.index') }}" class="text-blue-600 hover:text-blue-700">
            Contacts
        </a>
    </li>
    <li>
        <span class="text-slate-500">{{ $contact->nom_complet }}</span>
    </li>
@endpush

@push('scripts')
    <script>
        function confirmDelete() {
            document.getElementById('delete-modal').showModal();
        }

        function toggleStatus() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('contacts.toggle-status', $contact) }}';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
@endpush
