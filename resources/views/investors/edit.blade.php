@extends('layouts.app')

@section('title', 'Modifier l\'investisseur')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Modifier l'investisseur</h1>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <form action="{{ route('investors.update', $investor) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="p-6 space-y-6">
                    <!-- Informations personnelles -->
                    <div>
                        <h2 class="text-lg font-semibold text-slate-800 mb-4">Informations personnelles</h2>
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                            <!-- Civilité -->
                            <div class="form-control md:col-span-2">
                                <label for="civilite" class="label">Civilité</label>
                                <select name="civilite" id="civilite" class="select select-bordered w-full @error('civilite') select-error @enderror">
                                    <option value="">-</option>
                                    <option value="M" @selected(old('civilite', $investor->civilite) == 'M')>M</option>
                                    <option value="Mme" @selected(old('civilite', $investor->civilite) == 'Mme')>Mme</option>
                                    <option value="Dr" @selected(old('civilite', $investor->civilite) == 'Dr')>Dr</option>
                                    <option value="Prof" @selected(old('civilite', $investor->civilite) == 'Prof')>Prof</option>
                                </select>
                                @error('civilite')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Prénom -->
                            <div class="form-control md:col-span-5">
                                <label for="prenom" class="label">Prénom <span class="text-red-500">*</span></label>
                                <input type="text" name="prenom" id="prenom"
                                       class="input input-bordered w-full @error('prenom') input-error @enderror"
                                       value="{{ old('prenom', $investor->prenom) }}"
                                       required>
                                @error('prenom')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Nom -->
                            <div class="form-control md:col-span-5">
                                <label for="nom" class="label">Nom <span class="text-red-500">*</span></label>
                                <input type="text" name="nom" id="nom"
                                       class="input input-bordered w-full @error('nom') input-error @enderror"
                                       value="{{ old('nom', $investor->nom) }}"
                                       required>
                                @error('nom')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Catégorie -->
                            <div class="form-control md:col-span-6">
                                <label for="categorie_id" class="label">Catégorie <span class="text-red-500">*</span></label>
                                <select name="categorie_id" id="categorie_id"
                                        class="select select-bordered w-full @error('categorie_id') select-error @enderror"
                                        required>
                                    <option value="">Sélectionner une catégorie</option>
                                    @foreach($categories as $categorie)
                                        <option value="{{ $categorie->id }}"
                                                @selected(old('categorie_id', $investor->categorie_id) == $categorie->id)
                                                style="background-color: {{ $categorie->couleur_hexa }}; color: white;">
                                            {{ $categorie->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categorie_id')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Pays -->
                            <div class="form-control md:col-span-6">
                                <label for="pays" class="label">Pays <span class="text-red-500">*</span></label>
                                <select name="pays" id="pays"
                                        class="select select-bordered w-full @error('pays') select-error @enderror"
                                        required>
                                    <option value="">Sélectionner un pays</option>
                                    @foreach($pays as $pays_item)
                                        <option value="{{ $pays_item }}" @selected(old('pays', $investor->pays) == $pays_item)>
                                            {{ $pays_item }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pays')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Coordonnées -->
                    <div>
                        <h2 class="text-lg font-semibold text-slate-800 mb-4">Coordonnées</h2>
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                            <!-- Email -->
                            <div class="form-control md:col-span-6">
                                <label for="email" class="label">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" id="email"
                                       class="input input-bordered w-full @error('email') input-error @enderror"
                                       value="{{ old('email', $investor->email) }}"
                                       required>
                                @error('email')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Téléphone -->
                            <div class="form-control md:col-span-3">
                                <label for="telephone" class="label">Téléphone</label>
                                <input type="text" name="telephone" id="telephone"
                                       class="input input-bordered w-full @error('telephone') input-error @enderror"
                                       value="{{ old('telephone', $investor->telephone) }}">
                                @error('telephone')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Mobile -->
                            <div class="form-control md:col-span-3">
                                <label for="mobile" class="label">Mobile</label>
                                <input type="text" name="mobile" id="mobile"
                                       class="input input-bordered w-full @error('mobile') input-error @enderror"
                                       value="{{ old('mobile', $investor->mobile) }}">
                                @error('mobile')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Langue préférée -->
                            <div class="form-control md:col-span-6">
                                <label for="langue_preferee" class="label">Langue préférée <span class="text-red-500">*</span></label>
                                <select name="langue_preferee" id="langue_preferee"
                                        class="select select-bordered w-full @error('langue_preferee') select-error @enderror"
                                        required>
                                    @foreach($langues as $langue)
                                        <option value="{{ $langue }}" @selected(old('langue_preferee', $investor->langue_preferee) == $langue)>
                                            {{ $langue }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('langue_preferee')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Niveau d'influence -->
                            <div class="form-control md:col-span-6">
                                <label for="niveau_influence" class="label">Niveau d'influence <span class="text-red-500">*</span></label>
                                <select name="niveau_influence" id="niveau_influence"
                                        class="select select-bordered w-full @error('niveau_influence') select-error @enderror"
                                        required>
                                    @foreach($niveauxInfluence as $niveau)
                                        <option value="{{ $niveau }}" @selected(old('niveau_influence', $investor->niveau_influence) == $niveau)>
                                            {{ $niveau }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('niveau_influence')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Organisations -->
                    <div>
                        <h2 class="text-lg font-semibold text-slate-800 mb-4">Organisations</h2>
                        <div id="organisations-container">
                            @forelse($investor->organisations as $key => $org)
                                <div class="organisation-form bg-slate-50 p-4 rounded-lg mb-4">
                                    <div class="flex justify-between mb-2">
                                        <h3 class="font-medium">{{ $org->raison_sociale }}</h3>
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
                                                   value="{{ old("organisations.{$key}.poste", $org->pivot->poste) }}">
                                        </div>

                                        <!-- Date début -->
                                        <div class="form-control md:col-span-4">
                                            <label class="label">Date de début</label>
                                            <input type="date" name="organisations[{{ $key }}][date_debut]"
                                                   class="input input-bordered w-full"
                                                   value="{{ old("organisations.{$key}.date_debut", $org->pivot->date_debut) }}">
                                        </div>

                                        <!-- Notes -->
                                        <div class="form-control md:col-span-7">
                                            <label class="label">Notes</label>
                                            <input type="text" name="organisations[{{ $key }}][notes]"
                                                   class="input input-bordered w-full"
                                                   value="{{ old("organisations.{$key}.notes", $org->pivot->notes) }}">
                                        </div>

                                        <!-- Actuel -->
                                        <div class="form-control md:col-span-1 flex items-center md:items-end mb-2 md:mb-0">
                                            <label class="label cursor-pointer justify-start space-x-2 w-full">
                                                <input type="checkbox" name="organisations[{{ $key }}][actuel]" value="1"
                                                       class="checkbox"
                                                    @checked(old("organisations.{$key}.actuel", $org->pivot->actuel))>
                                                <span>Actuel</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-slate-500 italic mb-4">Aucune organisation associée</p>
                            @endforelse
                        </div>

                        <button type="button" id="add-organisation" class="btn btn-outline btn-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Ajouter une organisation
                        </button>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <!-- Organisation (champ texte) -->

                            <!-- Fonction -->
                            <div class="form-control">
                                <label for="fonction" class="label">Fonction</label>
                                <input type="text" name="fonction" id="fonction"
                                       class="input input-bordered w-full @error('fonction') input-error @enderror"
                                       value="{{ old('fonction', $investor->fonction) }}">
                                @error('fonction')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Informations complémentaires -->
                    <div>
                        <h2 class="text-lg font-semibold text-slate-800 mb-4">Informations complémentaires</h2>

                        <!-- Tags -->
                        <div class="form-control mb-4">
                            <label for="tags" class="label">Tags</label>
                            <input type="text" name="tags" id="tags"
                                   class="input input-bordered w-full @error('tags') input-error @enderror"
                                   placeholder="Exemple: VIP, Comité stratégique, Partenaire, etc."
                                   value="{{ old('tags', is_array($investor->tags) ? implode(', ', $investor->tags) : $investor->tags) }}"><p class="text-xs text-slate-500 mt-1">
                                Séparez les tags par des virgules.
                            </p>
                            @error('tags')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Remarques -->
                        <div class="form-control">
                            <label for="remarques" class="label">Remarques</label>
                            <textarea name="remarques" id="remarques" rows="3"
                                      class="textarea textarea-bordered w-full @error('remarques') textarea-error @enderror">{{ old('remarques', $investor->remarques) }}</textarea>
                            @error('remarques')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t border-slate-200 flex justify-between">
                    <div class="flex space-x-2">
                        <a href="{{ route('investors.index') }}" class="btn btn-ghost">Annuler</a>

                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Récupérer le dernier index d'organisation
            let organisationCount = {{ $investor->organisations->count() }};
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
                        <h3 class="font-medium">Organisation supplémentaire</h3>
                        <button type="button" class="remove-organisation text-red-500 hover:text-red-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                        <!-- Organisation -->
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

            <!-- Poste -->
            <div class="form-control md:col-span-6">
                <label class="label">Poste / Fonction</label>
                <input type="text" name="organisations[${organisationCount}][poste]"
                                   class="input input-bordered w-full">
                        </div>

                        <!-- Date début -->
                        <div class="form-control md:col-span-4">
                            <label class="label">Date de début</label>
                            <input type="date" name="organisations[${organisationCount}][date_debut]"
                                   class="input input-bordered w-full">
                        </div>

                        <!-- Notes -->
                        <div class="form-control md:col-span-7">
                            <label class="label">Notes</label>
                            <input type="text" name="organisations[${organisationCount}][notes]"
                                   class="input input-bordered w-full">
                        </div>

                        <!-- Actuel -->
                        <div class="form-control md:col-span-1 flex items-end mb-2">
                            <label class="label cursor-pointer justify-start space-x-2">
                                <input type="checkbox" name="organisations[${organisationCount}][actuel]" value="1"
                                       class="checkbox" checked>
                                <span>Actuel</span>
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

@push('breadcrumbs')
    <li>
        <a href="{{ route('investors.index') }}" class="text-blue-600 hover:text-blue-700">
            Investisseurs
        </a>
    </li>
    <li class="text-slate-400 mx-2">/</li>
    <li>
        <span class="text-slate-500">{{ $investor->prenom }} {{ $investor->nom }}</span>
    </li>
@endpush
