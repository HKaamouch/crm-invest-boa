@extends('layouts.app')

@section('title', $organisation->raison_sociale)

@section('page-header')
    <div class="flex justify-between items-start">
        <div class="flex items-start space-x-4">
            @if($organisation->logo_path)
                <img src="{{ $organisation->logo_url }}"
                     alt="Logo {{ $organisation->raison_sociale }}"
                     class="w-16 h-16 rounded-xl object-cover border border-slate-200">
            @else
                <div class="w-16 h-16 bg-slate-200 rounded-xl flex items-center justify-center border border-slate-200">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            @endif
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ $organisation->raison_sociale }}</h1>
                <div class="flex items-center space-x-4 mt-2">
                    @if($organisation->actif)
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-error">Inactive</span>
                    @endif
                    @if($organisation->ville || $organisation->pays)
                        <span class="text-slate-600">
                            {{ $organisation->ville }}{{ $organisation->ville && $organisation->pays ? ', ' : '' }}{{ $organisation->pays }}
                        </span>
                    @endif
                </div>
                @if($organisation->site_web)
                    <a href="{{ $organisation->site_web }}" target="_blank"
                       class="text-blue-600 hover:text-blue-800 text-sm mt-1 inline-flex items-center">
                        {{ $organisation->site_web }}
                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('organisations.index') }}" class="btn btn-outline btn-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour à la liste
            </a>
            @can('update', $organisation)
                <a href="{{ route('organisations.edit', $organisation) }}" class="btn btn-primary btn-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Modifier
                </a>
            @endcan
        </div>
    </div>
@endsection

@section('content')
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-50 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-slate-600">Total Investisseurs</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['investors_total'] }}</p>
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
                    <p class="text-sm font-medium text-slate-600">Investisseurs Actuels</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['investors_actuels'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-50 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-slate-600">Total Contacts</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['contacts_total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-amber-50 rounded-lg">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-slate-600">Contacts Actuels</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['contacts_actuels'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Détails de l'organisation -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900">Informations détaillées</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($organisation->adresse)
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Adresse</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $organisation->adresse }}</dd>
                            </div>
                        @endif

                        @if($organisation->ville)
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Ville</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $organisation->ville }}</dd>
                            </div>
                        @endif

                        @if($organisation->pays)
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Pays</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $organisation->pays }}</dd>
                            </div>
                        @endif

                        @if($organisation->telephone)
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Téléphone</dt>
                                <dd class="mt-1 text-sm text-slate-900">
                                    <a href="tel:{{ $organisation->telephone }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $organisation->telephone }}
                                    </a>
                                </dd>
                            </div>
                        @endif

                        @if($organisation->fax)
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Fax</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $organisation->fax }}</dd>
                            </div>
                        @endif

                        @if($organisation->email)
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Email</dt>
                                <dd class="mt-1 text-sm text-slate-900">
                                    <a href="mailto:{{ $organisation->email }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $organisation->email }}
                                    </a>
                                </dd>
                            </div>
                        @endif
                    </dl>

                    @if($organisation->description)
                        <div class="mt-6 pt-6 border-t border-slate-200">
                            <dt class="text-sm font-medium text-slate-500 mb-2">Description</dt>
                            <dd class="text-sm text-slate-900 leading-relaxed">{{ $organisation->description }}</dd>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Investisseurs liés -->
            @if($organisation->investors->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                    <div class="px-6 py-4 border-b border-slate-200">
                        <h2 class="text-lg font-semibold text-slate-900">
                            Investisseurs ({{ $organisation->investors->count() }})
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($organisation->investors as $investor)
                                <div class="flex items-center justify-between p-4 border border-slate-200 rounded-lg">
                                    <div class="flex items-center">
                                        <img class="h-10 w-10 rounded-full object-cover"
                                             src="https://ui-avatars.com/api/?name={{ urlencode($investor->nom_complet) }}&color=2563eb&background=e0e7ff"
                                             alt="{{ $investor->nom_complet }}">
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-slate-900">{{ $investor->nom_complet }}</p>
                                            @if($investor->pivot->poste)
                                                <p class="text-sm text-slate-500">{{ $investor->pivot->poste }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if($investor->pivot->actuel)
                                            <span class="badge badge-success badge-sm">Actuel</span>
                                        @else
                                            <span class="badge badge-outline badge-sm">Ancien</span>
                                        @endif
                                        <a href="#"
                                           class="btn btn-ghost btn-xs">
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
            @endif

            <!-- Contacts liés -->
            @if($organisation->contacts->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                    <div class="px-6 py-4 border-b border-slate-200">
                        <div class="flex justify-between items-center">
                            <h2 class="text-lg font-semibold text-slate-900">
                                Contacts ({{ $organisation->contacts->count() }})
                            </h2>
                            <div class="flex items-center space-x-2 text-sm text-slate-500">
                    <span class="flex items-center">
                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                        Actuels: {{ $organisation->contactsActuels->count() }}
                    </span>
                                @if($organisation->contacts->count() > $organisation->contactsActuels->count())
                                    <span class="flex items-center">
                            <div class="w-2 h-2 bg-slate-400 rounded-full mr-2"></div>
                            Anciens: {{ $organisation->contacts->count() - $organisation->contactsActuels->count() }}
                        </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($organisation->contacts as $contact)
                                <div class="flex items-center justify-between p-4 border border-slate-200 rounded-lg transition-all duration-200
                                {{ $contact->pivot->actuel ? 'bg-white hover:shadow-md' : 'bg-slate-50 border-slate-300 opacity-75' }}">
                                    <div class="flex items-center space-x-4">
                                        <!-- Avatar -->
                                        <div class="relative">
                                            <img class="h-12 w-12 rounded-full object-cover {{ $contact->pivot->actuel ? '' : 'grayscale' }}"
                                                 src="https://ui-avatars.com/api/?name={{ urlencode($contact->nom_complet) }}&color={{ $contact->pivot->actuel ? '2563eb' : '64748b' }}&background={{ $contact->pivot->actuel ? 'e0e7ff' : 'e2e8f0' }}"
                                                 alt="{{ $contact->nom_complet }}">
                                            @if(!$contact->pivot->actuel)
                                                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-slate-400 rounded-full flex items-center justify-center">
                                                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Informations du contact -->
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2">
                                                <h3 class="text-sm font-semibold {{ $contact->pivot->actuel ? 'text-slate-900' : 'text-slate-600' }}">
                                                    {{ $contact->nom_complet }}
                                                </h3>
                                                @if(!$contact->pivot->actuel)
                                                    <span class="text-xs text-slate-500 italic">(Ancien)</span>
                                                @endif
                                            </div>

                                            @if($contact->pivot->poste)
                                                <p class="text-sm {{ $contact->pivot->actuel ? 'text-slate-600' : 'text-slate-500' }} font-medium">
                                                    {{ $contact->pivot->poste }}
                                                </p>
                                            @endif

                                            <!-- Informations de contact -->
                                            <div class="flex flex-col mt-2 space-y-1">
                                                @if($contact->email)
                                                    <div class="flex items-center text-xs {{ $contact->pivot->actuel ? 'text-slate-600' : 'text-slate-500' }}">
                                                        <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                        </svg>
                                                        <a href="mailto:{{ $contact->email }}"
                                                           class="{{ $contact->pivot->actuel ? 'text-blue-600 hover:text-blue-800' : 'text-slate-500 hover:text-slate-600' }}">
                                                            {{ $contact->email }}
                                                        </a>
                                                    </div>
                                                @endif

                                                @if($contact->telephone_principal)
                                                    <div class="flex items-center text-xs {{ $contact->pivot->actuel ? 'text-slate-600' : 'text-slate-500' }}">
                                                        <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                        </svg>
                                                        <a href="tel:{{ $contact->telephone_principal }}"
                                                           class="{{ $contact->pivot->actuel ? 'text-blue-600 hover:text-blue-800' : 'text-slate-500 hover:text-slate-600' }}">
                                                            {{ $contact->telephone_principal }}
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Période -->
                                            @if($contact->pivot->date_debut || $contact->pivot->date_fin)
                                                <div class="mt-2 text-xs {{ $contact->pivot->actuel ? 'text-slate-500' : 'text-slate-400' }}">
                                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    @if($contact->pivot->date_debut && $contact->pivot->date_fin)
                                                        Du {{ \Carbon\Carbon::parse($contact->pivot->date_debut)->format('m/Y') }}
                                                        au {{ \Carbon\Carbon::parse($contact->pivot->date_fin)->format('m/Y') }}
                                                    @elseif($contact->pivot->date_debut)
                                                        {{ $contact->pivot->actuel ? 'Depuis' : 'À partir de' }} {{ \Carbon\Carbon::parse($contact->pivot->date_debut)->format('m/Y') }}
                                                    @elseif($contact->pivot->date_fin)
                                                        Jusqu'à {{ \Carbon\Carbon::parse($contact->pivot->date_fin)->format('m/Y') }}
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Actions et statut -->
                                    <div class="flex items-center space-x-3">
                                        <!-- Badge de statut -->
                                        @if($contact->pivot->actuel)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                    <div class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1.5"></div>
                                    Actuel
                                </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                    <div class="w-1.5 h-1.5 bg-slate-400 rounded-full mr-1.5"></div>
                                    Ancien
                                </span>
                                        @endif

                                        <!-- Actions -->
                                        <div class="flex space-x-1">
                                            @if($contact->pivot->actuel)
                                                <!-- Actions pour contacts actuels -->
                                                @if($contact->email)
                                                    <a href="mailto:{{ $contact->email }}"
                                                       class="btn btn-ghost btn-xs"
                                                       title="Envoyer un email">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </a>
                                                @endif

                                                @if($contact->telephone_principal)
                                                    <a href="tel:{{ $contact->telephone_principal }}"
                                                       class="btn btn-ghost btn-xs"
                                                       title="Appeler">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                        </svg>
                                                    </a>
                                                @endif
                                            @endif

                                            <!-- Voir le profil du contact (si route existe) -->
                                            {{-- Décommentez si vous avez une route pour les contacts
                                            <a href="{{ route('contacts.show', $contact) }}"
                                               class="btn btn-ghost btn-xs {{ $contact->pivot->actuel ? '' : 'opacity-50' }}"
                                               title="Voir le profil">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            --}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Résumé en bas si il y a des contacts non actuels -->
                        @if($organisation->contacts->count() > $organisation->contactsActuels->count())
                            <div class="mt-6 pt-4 border-t border-slate-200">
                                <div class="flex items-center justify-center text-sm text-slate-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Les contacts marqués comme "Ancien" ne font plus partie de cette organisation
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>



        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Actions rapides -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Actions</h3>
                <div class="space-y-3">
                    @can('update', $organisation)
                        <a href="{{ route('organisations.edit', $organisation) }}"
                           class="btn btn-outline btn-sm w-full justify-start">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Modifier l'organisation
                        </a>
                    @endcan

                    @if($organisation->email)
                        <a href="mailto:{{ $organisation->email }}"
                           class="btn btn-outline btn-sm w-full justify-start">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Envoyer un email
                        </a>
                    @endif

                    @if($organisation->site_web)
                        <a href="{{ $organisation->site_web }}" target="_blank"
                           class="btn btn-outline btn-sm w-full justify-start">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                            </svg>
                            Visiter le site web
                        </a>
                    @endif

                    @can('delete', $organisation)
                        <button onclick="confirmDelete()"
                                class="btn btn-error btn-outline btn-sm w-full justify-start">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Supprimer
                        </button>
                    @endcan
                </div>
            </div>

            <!-- Métadonnées -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Informations système</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Créée le</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $organisation->created_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Modifiée le</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $organisation->updated_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

    </div>

    <!-- Modal de confirmation de suppression -->
    @can('delete', $organisation)
        <dialog id="delete-modal" class="modal">
            <div class="modal-box">
                <h3 class="font-bold text-lg text-red-600">Confirmer la suppression</h3>
                <p class="py-4">
                    Êtes-vous sûr de vouloir supprimer l'organisation <strong>{{ $organisation->raison_sociale }}</strong> ?
                    Cette action est irréversible.
                </p>
                <div class="modal-action">
                    <form method="POST" action="{{ route('organisations.destroy', $organisation) }}">
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
    @endcan
@endsection

@push('breadcrumbs')
    <li>
        <a href="{{ route('organisations.index') }}" class="text-blue-600 hover:text-blue-700">
            Organisations
        </a>
    </li>
    <li>
        <span class="text-slate-500">{{ $organisation->raison_sociale }}</span>
    </li>
@endpush

@push('scripts')
    <script>
        function confirmDelete() {
            document.getElementById('delete-modal').showModal();
        }
    </script>
@endpush
