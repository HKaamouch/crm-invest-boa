@extends('layouts.app')

@section('title', 'Créer un utilisateur')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Créer un nouvel utilisateur</h1>
        <a href="{{ route('users.index') }}" class="btn btn-outline">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-lg font-medium text-slate-800">Informations de l'utilisateur</h2>
        </div>

        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Nom complet -->
                <div class="form-control">
                    <label for="nom_complet" class="label">
                        <span class="label-text">Nom complet <span class="text-red-500">*</span></span>
                    </label>
                    <input type="text" name="nom_complet" id="nom_complet"
                           class="input input-bordered w-full @error('nom_complet') input-error @enderror"
                           value="{{ old('nom_complet') }}" required>
                    @error('nom_complet')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-control">
                    <label for="email" class="label">
                        <span class="label-text">Email <span class="text-red-500">*</span></span>
                    </label>
                    <input type="email" name="email" id="email"
                           class="input input-bordered w-full @error('email') input-error @enderror"
                           value="{{ old('email') }}" required>
                    @error('email')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Téléphone -->
                <div class="form-control">
                    <label for="telephone" class="label">
                        <span class="label-text">Téléphone</span>
                    </label>
                    <input type="text" name="telephone" id="telephone"
                           class="input input-bordered w-full @error('telephone') input-error @enderror"
                           value="{{ old('telephone') }}">
                    @error('telephone')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Rôle -->
                <div class="form-control">
                    <label for="role" class="label">
                        <span class="label-text">Rôle <span class="text-red-500">*</span></span>
                    </label>
                    <select name="role" id="role"
                            class="select select-bordered w-full @error('role') select-error @enderror"
                            required>
                        <option value="">Sélectionner un rôle</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" @selected(old('role') == $role->id)>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Mot de passe -->
                <div class="form-control">
                    <label for="password" class="label">
                        <span class="label-text">Mot de passe <span class="text-red-500">*</span></span>
                    </label>
                    <input type="password" name="password" id="password"
                           class="input input-bordered w-full @error('password') input-error @enderror"
                           required>
                    @error('password')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirmation du mot de passe -->
                <div class="form-control">
                    <label for="password_confirmation" class="label">
                        <span class="label-text">Confirmation du mot de passe <span class="text-red-500">*</span></span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="input input-bordered w-full"
                           required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Photo de profil -->
                <div class="form-control">
                    <label for="avatar" class="label">
                        <span class="label-text">Photo de profil</span>
                    </label>
                    <input type="file" name="avatar" id="avatar"
                           class="file-input file-input-bordered w-full @error('avatar') file-input-error @enderror"
                           accept="image/*">
                    <div class="text-xs text-gray-500 mt-1">Formats acceptés: JPG, PNG, GIF (max 2Mo)</div>
                    @error('avatar')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Statut -->
                <div class="form-control">
                    <label class="label cursor-pointer justify-start gap-4">
                        <input type="checkbox" name="statut" class="toggle toggle-primary"
                               @checked(old('statut', true))>
                        <span class="label-text">Utilisateur actif</span>
                    </label>
                    <div class="text-xs text-gray-500 ml-16">Si désactivé, l'utilisateur ne pourra pas se connecter.</div>
                </div>
            </div>

            <div class="flex justify-end border-t border-slate-200 pt-6">
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Créer l'utilisateur
                </button>
            </div>
        </form>
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
        <span class="text-slate-500">Créer</span>
    </li>
@endpush

