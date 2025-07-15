@extends('layouts.app')

@section('title', 'Nouvelle Organisation')

@section('page-header')
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Nouvelle Organisation</h1>
            <p class="text-slate-600 mt-1">Ajouter une organisation au référentiel</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('organisations.index') }}" class="btn btn-outline btn-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour à la liste
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        <form method="POST" action="{{ route('organisations.store') }}" enctype="multipart/form-data"
              class="bg-white rounded-xl shadow-sm border border-slate-200">
            @csrf

            <div class="p-6 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">Informations générales</h2>
                <p class="text-sm text-slate-600 mt-1">Les informations de base de l'organisation</p>
            </div>

            <div class="p-6 space-y-6">
                <!-- Raison sociale -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Raison sociale <span class="text-red-500">*</span></span>
                    </label>
                    <input type="text"
                           name="raison_sociale"
                           value="{{ old('raison_sociale') }}"
                           class="input input-bordered w-full @error('raison_sociale') input-error @enderror"
                           placeholder="Nom de l'organisation"
                           required>
                    @error('raison_sociale')
                    <label class="label">
                        <span class="label-text-alt text-red-500">{{ $message }}</span>
                    </label>
                    @enderror
                </div>

                <!-- Logo -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Logo</span>
                        <span class="label-text-alt">JPEG, PNG, GIF, SVG - Max 2MB</span>
                    </label>
                    <input type="file"
                           name="logo"
                           accept="image/*"
                           class="file-input file-input-bordered w-full @error('logo') file-input-error @enderror">
                    @error('logo')
                    <label class="label">
                        <span class="label-text-alt text-red-500">{{ $message }}</span>
                    </label>
                    @enderror
                </div>

                <!-- Adresse complète -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Adresse</span>
                    </label>
                    <textarea name="adresse"
                              rows="3"
                              class="textarea textarea-bordered @error('adresse') textarea-error @enderror"
                              placeholder="Adresse complète de l'organisation">{{ old('adresse') }}</textarea>
                    @error('adresse')
                    <label class="label">
                        <span class="label-text-alt text-red-500">{{ $message }}</span>
                    </label>
                    @enderror
                </div>

                <!-- Ville et Pays -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Ville</span>
                        </label>
                        <input type="text"
                               name="ville"
                               value="{{ old('ville') }}"
                               class="input input-bordered w-full @error('ville') input-error @enderror"
                               placeholder="Ville">
                        @error('ville')
                        <label class="label">
                            <span class="label-text-alt text-red-500">{{ $message }}</span>
                        </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Pays</span>
                        </label>
                        <select name="pays" class="select select-bordered w-full @error('pays') select-error @enderror">
                            <option value="">Sélectionner un pays</option>
                            @foreach($pays as $p)
                                <option value="{{ $p }}" {{ old('pays') == $p ? 'selected' : '' }}>
                                    {{ $p }}
                                </option>
                            @endforeach
                        </select>
                        @error('pays')
                        <label class="label">
                            <span class="label-text-alt text-red-500">{{ $message }}</span>
                        </label>
                        @enderror
                    </div>
                </div>

                <!-- Contact -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Téléphone</span>
                        </label>
                        <input type="tel"
                               name="telephone"
                               value="{{ old('telephone') }}"
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
                            <span class="label-text font-semibold">Fax</span>
                        </label>
                        <input type="tel"
                               name="fax"
                               value="{{ old('fax') }}"
                               class="input input-bordered w-full @error('fax') input-error @enderror"
                               placeholder="+33 1 23 45 67 89">
                        @error('fax')
                        <label class="label">
                            <span class="label-text-alt text-red-500">{{ $message }}</span>
                        </label>
                        @enderror
                    </div>
                </div>

                <!-- Email et Site web -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Email</span>
                        </label>
                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="input input-bordered w-full @error('email') input-error @enderror"
                               placeholder="contact@organisation.com">
                        @error('email')
                        <label class="label">
                            <span class="label-text-alt text-red-500">{{ $message }}</span>
                        </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Site web</span>
                        </label>
                        <input type="url"
                               name="site_web"
                               value="{{ old('site_web') }}"
                               class="input input-bordered w-full @error('site_web') input-error @enderror"
                               placeholder="https://www.organisation.com">
                        @error('site_web')
                        <label class="label">
                            <span class="label-text-alt text-red-500">{{ $message }}</span>
                        </label>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Description</span>
                    </label>
                    <textarea name="description"
                              rows="4"
                              class="textarea textarea-bordered @error('description') textarea-error @enderror"
                              placeholder="Description de l'organisation, ses activités, sa taille...">{{ old('description') }}</textarea>
                    @error('description')
                    <label class="label">
                        <span class="label-text-alt text-red-500">{{ $message }}</span>
                    </label>
                    @enderror
                </div>

                <!-- Statut -->
                <div class="form-control">
                    <label class="label cursor-pointer justify-start">
                        <input type="checkbox"
                               name="actif"
                               value="1"
                               {{ old('actif', true) ? 'checked' : '' }}
                               class="checkbox checkbox-primary mr-3">
                        <div>
                            <span class="label-text font-semibold">Organisation active</span>
                            <div class="text-sm text-slate-500">Cochez pour marquer l'organisation comme active</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="p-6 border-t border-slate-200 bg-slate-50 rounded-b-xl">
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('organisations.index') }}" class="btn btn-outline">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Créer l'organisation
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('breadcrumbs')
    <li>
        <a href="{{ route('organisations.index') }}" class="text-blue-600 hover:text-blue-700">
            Organisations
        </a>
    </li>
    <li>
        <span class="text-slate-500">Nouvelle organisation</span>
    </li>
@endpush
