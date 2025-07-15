@extends('layouts.app')

@section('title', 'Modifier le contact')

@section('page-header')
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Modifier le contact</h1>
            <p class="text-slate-600 mt-1">{{ $contact->nom_complet }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('contacts.show', $contact) }}" class="btn btn-outline btn-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour aux détails
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        <form method="POST" action="{{ route('contacts.update', $contact) }}"
              class="bg-white rounded-xl shadow-sm border border-slate-200">
            @csrf
            @method('PUT')

            <div class="p-6 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">Informations personnelles</h2>
                <p class="text-sm text-slate-600 mt-1">Modifiez les informations du contact</p>
            </div>

            <div class="p-6 space-y-6">
                <!-- Nom et Prénom -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Prénom <span class="text-red-500">*</span></span>
                        </label>
                        <input type="text"
                               name="prenom"
                               value="{{ old('prenom', $contact->prenom) }}"
                               class="input input-bordered w-full @error('prenom') input-error @enderror"
                               placeholder="Prénom du contact"
                               required>
                        @error('prenom')
                        <label class="label">
                            <span class="label-text-alt text-red-500">{{ $message }}</span>
                        </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Nom <span class="text-red-500">*</span></span>
                        </label>
                        <input type="text"
                               name="nom"
                               value="{{ old('nom', $contact->nom) }}"
                               class="input input-bordered w-full @error('nom') input-error @enderror"
                               placeholder="Nom du contact"
                               required>
                        @error('nom')
                        <label class="label">
                            <span class="label-text-alt text-red-500">{{ $message }}</span>
                        </label>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Email</span>
                    </label>
                    <input type="email"
                           name="email"
                           value="{{ old('email', $contact->email) }}"
                           class="input input-bordered w-full @error('email') input-error @enderror"
                           placeholder="email@example.com">
                    @error('email')
                    <label class="label">
                        <span class="label-text-alt text-red-500">{{ $message }}</span>
                    </label>
                    @enderror
                </div>

                <!-- Téléphones -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Téléphone fixe</span>
                        </label>
                        <input type="tel"
                               name="telephone"
                               value="{{ old('telephone', $contact->telephone) }}"
                               class="input input-bordered w-full @error('telephone') input-error @enderror"
                               placeholder="+33 1 23 45 67 89">
                        @error('telephone')
                        <label class="label">
                            <span class="label-text-alt text-red-500">{{ $message }}</span>
                        </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Mobile</span>
                        </label>
                        <input type="tel"
                               name="mobile"
                               value="{{ old('mobile', $contact->mobile) }}"
                               class="input input-bordered w-full @error('mobile') input-error @enderror"
                               placeholder="+33 6 12 34 56 78">
                        @error('mobile')
                        <label class="label">
                            <span class="label-text-alt text-red-500">{{ $message }}</span>
                        </label>
                        @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Notes</span>
                    </label>
                    <textarea name="notes"
                              rows="4"
                              class="textarea textarea-bordered @error('notes') textarea-error @enderror"
                              placeholder="Notes personnelles sur ce contact...">{{ old('notes', $contact->notes) }}</textarea>
                    @error('notes')
                    <label class="label">
                        <span class="label-text-alt text-red-500">{{ $message }}</span>
                    </label>
                    @enderror
                </div>

                <!-- Organisations -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Organisations</h3>
                    <div id="organisations-container">
                        @forelse($contact->organisations as $key => $org)
                            <div class="organisation-form bg-slate-50 p-4 rounded-lg mb-4">
                                <div class="flex justify-between mb-2">
                                    <h4 class="font-medium">{{ $org->raison_sociale }}</h4>
                                    @if($key > 0)
                                        <button type="button" class="remove-organisation text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                                    <!-- Organisation -->
                                    <div class="form-control md:col-span-6">
                                        <label class="label">Organisation</label>
                                        <select name="organisations[{{ $key }}][organisation_id]"
                                                class="select select-bordered w-full">
                                            <option value="">Sélectionner une organisation</option>
                                            @foreach($organisations as $organisation)
                                                <option value="{{ $organisation->id }}"
                                                    @selected(old("organisations.{$key}.organisation_id", $org->id) == $organisation->id)>
                                                    {{ $organisation->raison_sociale }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Poste -->
                                    <div class="form-control md:col-span-6">
                                        <label class="label">Poste / Fonction</label>
                                        <input type="text" name="organisations[{{ $key }}][poste]"
                                               class="input input-bordered w-full"
                                               value="{{ old("organisations.{$key}.poste", $org->pivot->poste) }}"
                                               placeholder="Directeur, Responsable...">
                                    </div>

                                    <!-- Date début -->
                                    <div class="form-control md:col-span-4">
                                        <label class="label">Date de début</label>
                                        <input type="date" name="organisations[{{ $key }}][date_debut]"
                                               class="input input-bordered w-full"
                                               value="{{ old("organisations.{$key}.date_debut", $org->pivot->date_debut) }}">
                                    </div>

                                    <!-- Date fin -->
                                    <div class="form-control md:col-span-4">
                                        <label class="label">Date de fin</label>
                                        <input type="date" name="organisations[{{ $key }}][date_fin]"
                                               class="input input-bordered w-full"
                                               value="{{ old("organisations.{$key}.date_fin", $org->pivot->date_fin) }}">
                                    </div>


                                    <!-- Actuel -->
                                    <div class="form-control md:col-span-4 flex items-end mb-2">
                                        <label class="label cursor-pointer justify-start space-x-2">
                                            <input type="hidden" name="organisations[{{ $key }}][actuel]" value="0">
                                            <input type="checkbox" name="organisations[{{ $key }}][actuel]" value="1"
                                                   class="checkbox checkbox-primary"
                                                {{ old("organisations.{$key}.actuel", $org->pivot->actuel) ? 'checked' : '' }}>
                                            <span>Poste actuel</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="organisation-form bg-slate-50 p-4 rounded-lg mb-4">
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                                    <!-- Organisation -->
                                    <div class="form-control md:col-span-6">
                                        <label class="label">Organisation</label>
                                        <select name="organisations[0][organisation_id]"
                                                class="select select-bordered w-full">
                                            <option value="">Sélectionner une organisation</option>
                                            @foreach($organisations as $organisation)
                                                <option value="{{ $organisation->id }}">
                                                    {{ $organisation->raison_sociale }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Poste -->
                                    <div class="form-control md:col-span-6">
                                        <label class="label">Poste / Fonction</label>
                                        <input type="text" name="organisations[0][poste]"
                                               class="input input-bordered w-full"
                                               placeholder="Directeur, Responsable...">
                                    </div>

                                    <!-- Date début -->
                                    <div class="form-control md:col-span-4">
                                        <label class="label">Date de début</label>
                                        <input type="date" name="organisations[0][date_debut]"
                                               class="input input-bordered w-full">
                                    </div>

                                    <!-- Date fin -->
                                    <div class="form-control md:col-span-4">
                                        <label class="label">Date de fin</label>
                                        <input type="date" name="organisations[0][date_fin]"
                                               class="input input-bordered w-full">
                                    </div>

                                    <!-- Actuel -->
                                    <div class="form-control md:col-span-4 flex items-end mb-2">
                                        <label class="label cursor-pointer justify-start space-x-2">
                                            <input type="checkbox" name="organisations[0][actuel]" value="1"
                                                   class="checkbox checkbox-primary" checked>
                                            <span>Poste actuel kkkkk</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <button type="button" id="add-organisation" class="btn btn-outline btn-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Ajouter une organisation
                    </button>
                </div>

                <!-- Statut -->
                <div class="form-control">
                    <label class="label cursor-pointer justify-start">
                        <input type="checkbox"
                               name="actif"
                               value="1"
                               {{ old('actif', $contact->actif) ? 'checked' : '' }}
                               class="checkbox checkbox-primary mr-3">
                        <div>
                            <span class="label-text font-semibold">Contact actif</span>
                            <div class="text-sm text-slate-500">Cochez pour marquer le contact comme actif</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="p-6 border-t border-slate-200 bg-slate-50 rounded-b-xl">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-slate-500">
                        <p>Créé le {{ $contact->created_at->format('d/m/Y à H:i') }}</p>
                        <p>Modifié le {{ $contact->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('contacts.show', $contact) }}" class="btn btn-outline">
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('breadcrumbs')
    <li>
        <a href="{{ route('contacts.index') }}" class="text-blue-600 hover:text-blue-700">
            Contacts
        </a>
    </li>
    <li>
        <a href="{{ route('contacts.show', $contact) }}" class="text-blue-600 hover:text-blue-700">
            {{ $contact->nom_complet }}
        </a>
    </li>
    <li>
        <span class="text-slate-500">Modifier</span>
    </li>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Récupérer le dernier index d'organisation
            let organisationCount = {{ $contact->organisations->count() > 0 ? $contact->organisations->count() : 1 }};
            const container = document.getElementById('organisations-container');
            const addButton = document.getElementById('add-organisation');

            // Ajouter des écouteurs d'événements pour les boutons de suppression existants
            document.querySelectorAll('.remove-organisation').forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('.organisation-form').remove();
                });
            });

            addButton.addEventListener('click', function() {
                const template = `
                <div class="organisation-form bg-slate-50 p-4 rounded-lg mb-4">
                    <div class="flex justify-between mb-2">
                        <h4 class="font-medium">Organisation supplémentaire</h4>
                        <button type="button" class="remove-organisation text-red-500 hover:text-red-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                        <div class="form-control md:col-span-6">
                            <label class="label">Organisation</label>
                            <select name="organisations[${organisationCount}][organisation_id]"
                                    class="select select-bordered w-full">
                                <option value="">Sélectionner une organisation</option>
                                @foreach($organisations as $organisation)
                <option value="{{ $organisation->id }}">
                                        {{ $organisation->raison_sociale }}
                </option>
@endforeach
                </select>
            </div>
            <div class="form-control md:col-span-6">
                <label class="label">Poste / Fonction</label>
                <input type="text" name="organisations[${organisationCount}][poste]"
                                   class="input input-bordered w-full"
                                   placeholder="Directeur, Responsable...">
                        </div>
                        <div class="form-control md:col-span-4">
                            <label class="label">Date de début</label>
                            <input type="date" name="organisations[${organisationCount}][date_debut]"
                                   class="input input-bordered w-full">
                        </div>
                        <div class="form-control md:col-span-4">
                            <label class="label">Date de fin</label>
                            <input type="date" name="organisations[${organisationCount}][date_fin]"
                                   class="input input-bordered w-full">
                        </div>
                        <div class="form-control md:col-span-4 flex items-end mb-2">
                            <label class="label cursor-pointer justify-start space-x-2">
                                <input type="hidden" name="organisations[${organisationCount}][actuel]" value="0">
                                <input type="checkbox" name="organisations[${organisationCount}][actuel]" value="1"
                                       class="checkbox checkbox-primary" checked>
                                <span>Poste actuel</span>
                            </label>
                        </div>
                    </div>
                </div>
                `;

                container.insertAdjacentHTML('beforeend', template);
                organisationCount++;

                // Ajouter des écouteurs d'événements pour les boutons de suppression
                const removeButtons = document.querySelectorAll('.remove-organisation');
                removeButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        this.closest('.organisation-form').remove();
                    });
                });
            });
        });
    </script>
@endpush
