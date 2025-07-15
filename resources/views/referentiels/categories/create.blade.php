@extends('layouts.app')

@section('title', 'Nouvelle catégorie d\'investisseur')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Nouvelle catégorie d'investisseur</h1>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <form action="{{ route('categories-investisseurs.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nom -->
                        <div class="form-control">
                            <label for="nom" class="label">Nom <span class="text-red-500">*</span></label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom') }}"
                                   class="input input-bordered @error('nom') input-error @enderror" required>
                            @error('nom')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Ordre d'affichage -->
                        <div class="form-control">
                            <label for="ordre_affichage" class="label">Ordre d'affichage <span class="text-red-500">*</span></label>
                            <input type="number" name="ordre_affichage" id="ordre_affichage" value="{{ old('ordre_affichage', $prochainOrdre) }}"
                                   class="input input-bordered @error('ordre_affichage') input-error @enderror" min="0" required>
                            @error('ordre_affichage')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Couleur -->
                    <div class="form-control">
                        <label class="label">Couleur <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
                            @foreach($couleurs as $code => $nom)
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="couleur_hexa" value="{{ $code }}"
                                           {{ old('couleur_hexa') == $code ? 'checked' : '' }}
                                           class="radio" required>
                                    <span class="inline-block w-6 h-6 rounded-full" style="background-color: {{ $code }};"></span>
                                    <span class="text-sm">{{ $nom }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('couleur_hexa')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="form-control">
                        <label for="description" class="label">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="textarea textarea-bordered @error('description') textarea-error @enderror">{{ old('description') }}</textarea>
                        @error('description')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Actif -->
                    <div class="form-control">
                        <label class="label cursor-pointer justify-start space-x-2">
                            <input type="checkbox" name="actif" value="1" class="checkbox"
                                {{ old('actif', '1') == '1' ? 'checked' : '' }}>
                            <span>Catégorie active</span>
                        </label>
                    </div>
                </div>

                <div class="p-6 border-t border-slate-200 flex justify-between">
                    <a href="{{ route('categories-investisseurs.index') }}" class="btn btn-ghost">Annuler</a>
                    <button type="submit" class="btn btn-primary">Créer la catégorie</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('breadcrumbs')
    <li>
        <a href="{{ route('categories-investisseurs.index') }}" class="text-blue-600 hover:text-blue-700">
            Catégories d'investisseurs
        </a>
    </li>
    <li class="text-slate-400 mx-2">/</li>
    <li>
        <span class="text-slate-500">Nouvelle catégorie</span>
    </li>
@endpush
