@extends('layouts.app')

@section('title', 'Contacts')

@section('page-header')
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Contacts</h1>
            <p class="text-slate-600 mt-1">Gérez vos contacts professionnels</p>
        </div>
        <div class="flex space-x-3">
            @can('create', App\Models\Contact::class)
                <a href="{{ route('contacts.create') }}" class="btn btn-primary btn-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nouveau contact
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
                <div class="p-2 bg-blue-50 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-slate-600">Total</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['total'] }}</p>
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
                    <p class="text-sm font-medium text-slate-600">Actifs</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['actifs'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-50 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-slate-600">Avec organisations</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['avec_organisations'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-amber-50 rounded-lg">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-slate-600">Nouveaux ce mois</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['nouveau_mois'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
        <form method="GET" action="{{ route('contacts.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Recherche -->
                <div class="md:col-span-2">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Rechercher un contact..."
                           class="input input-bordered w-full">
                </div>

                <!-- Filtre par statut -->
                <div>
                    <select name="actif" class="select select-bordered w-full">
                        <option value="">Tous les statuts</option>
                        <option value="1" {{ request('actif') === '1' ? 'selected' : '' }}>Actifs</option>
                        <option value="0" {{ request('actif') === '0' ? 'selected' : '' }}>Inactifs</option>
                    </select>
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
                    <a href="{{ route('contacts.index') }}" class="btn btn-outline btn-sm">
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

    <!-- Liste des contacts -->
    @if($view === 'list')
        <!-- Vue en liste -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table table-hover w-full">
                    <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left">Contact</th>
                        <th class="text-left">Email</th>
                        <th class="text-left">Téléphone</th>
                        <th class="text-center">Organisations</th>
                        <th class="text-center">Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($contacts as $contact)
                        <tr>
                            <td>
                                <div class="flex items-center">
                                    <img src="{{ $contact->avatar_url }}"
                                         alt="{{ $contact->nom_complet }}"
                                         class="w-10 h-10 rounded-full object-cover mr-3">
                                    <div>
                                        <div class="font-semibold text-slate-900">{{ $contact->nom_complet }}</div>
                                        @if($contact->organisationsActuelles->first())
                                            <div class="text-xs text-slate-500">
                                                {{ $contact->organisationsActuelles->first()->raison_sociale }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($contact->email)
                                    <a href="mailto:{{ $contact->email }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $contact->email }}
                                    </a>
                                @else
                                    <span class="text-slate-400">Non renseigné</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-sm">
                                    @if($contact->telephone_principal)
                                        <a href="tel:{{ $contact->telephone_principal }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $contact->telephone_principal }}
                                        </a>
                                    @else
                                        <span class="text-slate-400">Non renseigné</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-primary">{{ $contact->organisations_count }}</span>
                            </td>
                            <td class="text-center">
                                @if($contact->actif)
                                    <span class="badge badge-success">Actif</span>
                                @else
                                    <span class="badge badge-error">Inactif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="flex justify-center space-x-1">
                                    <a href="{{ route('contacts.show', $contact) }}"
                                       class="btn btn-ghost btn-xs" title="Voir">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @can('update', $contact)
                                        <a href="{{ route('contacts.edit', $contact) }}"
                                           class="btn btn-ghost btn-xs" title="Modifier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-500">
                                Aucun contact trouvé
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- Vue en grille -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($contacts as $contact)
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-start justify-between mb-4">
                        <img src="{{ $contact->avatar_url }}"
                             alt="{{ $contact->nom_complet }}"
                             class="w-12 h-12 rounded-full object-cover">
                        @if($contact->actif)
                            <span class="badge badge-success badge-sm">Actif</span>
                        @else
                            <span class="badge badge-error badge-sm">Inactif</span>
                        @endif
                    </div>

                    <h3 class="font-semibold text-slate-900 mb-2">{{ $contact->nom_complet }}</h3>

                    @if($contact->email)
                        <p class="text-sm text-slate-600 mb-2">
                            <a href="mailto:{{ $contact->email }}" class="hover:text-blue-600">
                                {{ $contact->email }}
                            </a>
                        </p>
                    @endif

                    @if($contact->telephone_principal)
                        <p class="text-sm text-slate-600 mb-2">
                            <a href="tel:{{ $contact->telephone_principal }}" class="hover:text-blue-600">
                                {{ $contact->telephone_principal }}
                            </a>
                        </p>
                    @endif

                    @if($contact->organisationsActuelles->first())
                        <p class="text-sm text-slate-500 mb-4">
                            {{ $contact->organisationsActuelles->first()->raison_sociale }}
                        </p>
                    @endif

                    <div class="flex justify-between text-xs text-slate-500 mb-4">
                        <span>{{ $contact->organisations_count }} organisations</span>
                        <span>{{ $contact->created_at->format('d/m/Y') }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <div class="flex space-x-1">
                            <a href="{{ route('contacts.show', $contact) }}"
                               class="btn btn-ghost btn-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            @can('update', $contact)
                                <a href="{{ route('contacts.edit', $contact) }}"
                                   class="btn btn-ghost btn-xs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                            @endcan
                        </div>

                        @if($contact->email)
                            <a href="mailto:{{ $contact->email }}" class="btn btn-primary btn-xs">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Email
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-8 text-slate-500">
                    Aucun contact trouvé
                </div>
            @endforelse
        </div>
    @endif

    <!-- Pagination -->
    @if($contacts->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $contacts->links() }}
        </div>
    @endif
@endsection

@push('breadcrumbs')
    <li>
        <span class="text-slate-500">Contacts</span>
    </li>
@endpush
