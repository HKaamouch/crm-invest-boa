@extends('layouts.app')

@section('title', 'Gestion des utilisateurs')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Gestion des utilisateurs</h1>
        @can('create', App\Models\User::class)
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvel utilisateur
            </a>
        @endcan
    </div>

    <!-- Alerte de confirmation -->


    @if(session('error'))
        <div class="alert alert-error mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Filtres et recherche -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-6">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-lg font-medium text-slate-800">Recherche et filtres</h2>
        </div>

        <div class="p-6">
            <form action="{{ route('users.index') }}" method="GET">
                <input type="hidden" name="per_page" value="{{ $perPage }}">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Recherche -->
                    <div class="form-control">
                        <label for="search" class="label">Rechercher</label>
                        <input type="text" name="search" id="search"
                               class="input input-bordered w-full"
                               placeholder="Nom, email ou téléphone..."
                               value="{{ request('search') }}">
                    </div>

                    <!-- Rôle -->
                    <div class="form-control">
                        <label for="role" class="label">Rôle</label>
                        <select name="role" id="role" class="select select-bordered w-full">
                            <option value="">Tous les rôles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}"
                                    @selected(request('role') == $role->id)>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Statut -->
                    <div class="form-control">
                        <label for="statut" class="label">Statut</label>
                        <select name="statut" id="statut" class="select select-bordered w-full">
                            <option value="">Tous les statuts</option>
                            <option value="actif" @selected(request('statut') == 'actif')>Actif</option>
                            <option value="inactif" @selected(request('statut') == 'inactif')>Inactif</option>
                        </select>
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
                        <a href="{{ route('users.index') }}" class="btn btn-outline btn-sm">
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

    <!-- Liste des utilisateurs -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-lg font-medium text-slate-800">
                {{ $users->total() }} utilisateur(s) trouvé(s)
            </h2>
        </div>

        @if($users->isEmpty())
            <div class="p-6 text-center">
                <div class="py-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <p class="text-slate-500">Aucun utilisateur trouvé avec ces critères.</p>
                    <a href="{{ route('users.index') }}" class="btn btn-outline btn-sm mt-4">
                        Réinitialiser les filtres
                    </a>
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead class="bg-slate-50 text-xs font-medium text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left">Utilisateur</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Rôle</th>
                        <th class="px-6 py-3 text-left">Téléphone</th>
                        <th class="px-6 py-3 text-center">Statut</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                    @foreach($users as $user)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($user->avatar)
                                            <img class="h-10 w-10 rounded-full" src="{{ Storage::url($user->avatar) }}" alt="{{ $user->nom_complet }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-lg">
                                                {{ strtoupper(substr($user->nom_complet, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-slate-800">
                                            <a href="{{ route('users.show', $user) }}" class="hover:text-blue-600">
                                                {{ $user->nom_complet }}
                                            </a>
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            Créé le {{ $user->created_at->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-800">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4">
                                @foreach($user->roles as $role)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $role->name === 'Administrateur' ? 'bg-red-100 text-red-800' :
                                           ($role->name === 'Éditeur' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-800">
                                {{ $user->telephone ?? '-' }}
                            </td>
                            <td class="text-center">
                                <form method="POST" action="{{ route('users.toggle-status', $user) }}" class="inline toggle-status-form">
                                    @csrf
                                    <button type="submit" class="focus:outline-none group" title="Changer le statut">
                                        <span class="relative inline-flex items-center w-11 h-6 rounded-full transition-colors duration-200
                                            {{ $user->statut === 'actif' ? 'bg-green-500' : 'bg-gray-300' }}">
                                            <span class="absolute left-1 top-1 w-4 h-4 rounded-full transition-transform duration-200
                                                {{ $user->statut === 'actif' ? 'translate-x-5 bg-white' : 'bg-white' }}"></span>
                                        </span>
                                        <span class="ml-2 text-xs font-semibold
                                            {{ $user->statut === 'actif' ? 'text-green-600' : 'text-red-500' }}">
                                            {{ ucfirst($user->statut) }}
                                        </span>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end space-x-2">
                                    @can('view', $user)
                                        <a href="{{ route('users.show', $user) }}" class="text-blue-600 hover:text-blue-800" title="Voir">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('update', $user)
                                        <a href="{{ route('users.edit', $user) }}" class="text-amber-600 hover:text-amber-800" title="Modifier">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('delete', $user)
                                        @if(auth()->id() !== $user->id)
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800" title="Supprimer">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
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
                        {{ $users->firstItem() ?? 0 }} à {{ $users->lastItem() ?? 0 }}
                        sur {{ $users->total() }} résultats
                    </div>

                    <!-- Navigation pagination -->
                    {{ $users->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection

@push('breadcrumbs')
    <li>
        <span class="text-slate-500">Utilisateurs</span>
    </li>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusToggles = document.querySelectorAll('.status-toggle');

        statusToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                if (this.hasAttribute('disabled')) return;

                const userId = this.getAttribute('data-user-id');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/users/${userId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ userId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mettre à jour l'interface utilisateur
                        const statusText = data.status ? 'Actif' : 'Inactif';
                        const statusClass = data.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                        const statusTextClass = data.status ? 'text-green-600' : 'text-red-600';

                        // Mettre à jour le bouton
                        this.classList.remove('text-green-600', 'text-red-600');
                        this.classList.add(statusTextClass);

                        // Mettre à jour le badge de statut
                        const statusBadge = this.querySelector('span');
                        statusBadge.textContent = statusText;
                        statusBadge.className = `inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClass}`;

                        // Afficher une notification (optionnel)
                        // ...
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors de la modification du statut.');
                });
            });
        });
    });

    document.querySelectorAll('.toggle-status-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = form.querySelector('button');
            btn.disabled = true;
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Rafraîchir la page ou mettre à jour le statut dynamiquement
                    location.reload();
                } else {
                    alert(data.message || 'Erreur lors du changement de statut');
                }
            })
            .catch(() => alert('Erreur lors du changement de statut'))
            .finally(() => btn.disabled = false);
        });
    });
</script>
@endpush
