@extends('layouts.app')

@section('title', 'Catégories d\'investisseurs')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Catégories d'investisseurs</h1>
        <a href="{{ route('categories-investisseurs.create') }}" class="btn btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Nouvelle catégorie
        </a>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
            <div class="flex items-center">
                <div class="rounded-full bg-blue-100 p-3 mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Total des catégories</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
            <div class="flex items-center">
                <div class="rounded-full bg-green-100 p-3 mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Catégories actives</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['actives'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
            <div class="flex items-center">
                <div class="rounded-full bg-purple-100 p-3 mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Avec investisseurs</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['avec_investors'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-lg font-medium text-slate-800">Liste des catégories</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="table-auto w-full" id="categories-table">
                <thead class="bg-slate-50 text-xs font-medium text-slate-500 uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3 text-left">Ordre</th>
                    <th class="px-6 py-3 text-left">Nom</th>
                    <th class="px-6 py-3 text-left">Couleur</th>
                    <th class="px-6 py-3 text-left">Description</th>
                    <th class="px-6 py-3 text-center">Investisseurs</th>
                    <th class="px-6 py-3 text-center">Statut</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-200" id="categories-tbody">
                @forelse($categories as $categorie)
                    <tr class="hover:bg-slate-50" data-id="{{ $categorie->id }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-800 cursor-move">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                </svg>
                                {{ $categorie->ordre_affichage }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-800">
                            {{ $categorie->nom }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            <span class="inline-block w-6 h-6 rounded-full mr-2" style="background-color: {{ $categorie->couleur_hexa }};"></span>
                            {{ $categorie->couleur_hexa }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 max-w-xs truncate">
                            {{ $categorie->description ?: '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-slate-600">
                            {{ $categorie->investors_count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                            @if($categorie->actif)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-error">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('categories-investisseurs.edit', $categorie) }}" class="btn btn-ghost btn-xs" title="Modifier">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                @if($categorie->investors_count === 0)
                                    <form action="{{ route('categories-investisseurs.destroy', $categorie) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-xs text-red-500"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')"
                                                title="Supprimer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-slate-500">
                            Aucune catégorie trouvée
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialiser le tri par glisser-déposer
            const tbody = document.getElementById('categories-tbody');
            if (tbody) {
                new Sortable(tbody, {
                    animation: 150,
                    handle: 'td:first-child',
                    onEnd: function() {
                        saveNewOrder();
                    }
                });
            }

            // Sauvegarder le nouvel ordre
            function saveNewOrder() {
                const rows = document.querySelectorAll('#categories-tbody tr');
                const categoriesOrder = [];

                rows.forEach((row, index) => {
                    const id = row.getAttribute('data-id');
                    if (id) {
                        categoriesOrder.push({
                            id: id,
                            ordre: index + 1
                        });

                        // Mettre à jour l'affichage de l'ordre
                        const orderCell = row.querySelector('td:first-child');
                        if (orderCell) {
                            const orderText = orderCell.querySelector('div');
                            if (orderText) {
                                orderText.innerHTML = `
                                <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                </svg>
                                ${index + 1}
                            `;
                            }
                        }
                    }
                });

                // Envoyer les données au serveur
                fetch('{{ route('categories-investisseurs.reorder') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ categories: categoriesOrder })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Ordre mis à jour avec succès');
                        } else {
                            console.error('Erreur lors de la mise à jour de l\'ordre');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                    });
            }
        });
    </script>
@endpush

@push('breadcrumbs')
    <li>
        <span class="text-slate-500">Catégories d'investisseurs</span>
    </li>
@endpush
