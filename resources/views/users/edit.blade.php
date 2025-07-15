@extends('layouts.app')

@section('title', 'Modifier l\'utilisateur')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Modifier l'utilisateur</h1>
        <a href="{{ route('users.index') }}" class="btn btn-outline">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulaire principal -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-lg font-medium text-slate-800">Informations de l'utilisateur</h2>
                </div>

                <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Nom complet -->
                        <div class="form-control">
                            <label for="nom_complet" class="label">
                                <span class="label-text">Nom complet <span class="text-red-500">*</span></span>
                            </label>
                            <input type="text" name="nom_complet" id="nom_complet"
                                class="input input-bordered w-full @error('nom_complet') input-error @enderror"
                                value="{{ old('nom_complet', $user->nom_complet) }}" required>
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
                                value="{{ old('email', $user->email) }}" required>
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
                                value="{{ old('telephone', $user->telephone) }}">
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
                                    <option value="{{ $role->id }}"
                                        @selected(old('role', $user->roles->first()->id ?? null) == $role->id)>
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
                                <span class="label-text">Mot de passe</span>
                            </label>
                            <input type="password" name="password" id="password"
                                class="input input-bordered w-full @error('password') input-error @enderror">
                            <div class="text-xs text-gray-500 mt-1">Laissez vide pour conserver le mot de passe actuel</div>
                            @error('password')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Confirmation du mot de passe -->
                        <div class="form-control">
                            <label for="password_confirmation" class="label">
                                <span class="label-text">Confirmation du mot de passe</span>
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="input input-bordered w-full">
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
                                <input type="checkbox" name="actif" class="toggle toggle-primary"
                                    @checked(old('statut', $user->statut))
                                    @disabled(auth()->id() === $user->id)>
                                <span class="label-text">Utilisateur actif</span>
                            </label>
                            <div class="text-xs text-gray-500 ml-16">
                                @if(auth()->id() === $user->id)
                                    Vous ne pouvez pas désactiver votre propre compte.
                                @else
                                    Si désactivé, l'utilisateur ne pourra pas se connecter.
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end border-t border-slate-200 pt-6">
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="text-center mb-4">
                    @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->nom_complet }}"
                            class="mx-auto h-32 w-32 rounded-full object-cover">
                    @else
                        <div class="mx-auto h-32 w-32 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-4xl">
                            {{ strtoupper(substr($user->nom_complet, 0, 1)) }}
                        </div>
                    @endif
                    <h2 class="mt-4 text-lg font-medium text-slate-800">{{ $user->nom_complet }}</h2>
                    <p class="text-sm text-slate-500">{{ $user->email }}</p>
                </div>

                <div class="mt-6 space-y-4">
                    <div class="flex items-center text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>
                            <span class="text-slate-500">Rôle:</span>
                            <span class="font-medium">{{ $user->roles->first()->name ?? 'Aucun rôle' }}</span>
                        </span>
                    </div>

                    <div class="flex items-center text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span>
                            <span class="text-slate-500">Téléphone:</span>
                            <span class="font-medium">{{ $user->telephone ?? 'Non renseigné' }}</span>
                        </span>
                    </div>

                    <div class="flex items-center text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>
                            <span class="text-slate-500">Créé le:</span>
                            <span class="font-medium">{{ $user->created_at->format('d/m/Y à H:i') }}</span>
                        </span>
                    </div>

                    <div class="flex items-center text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>
                            <span class="text-slate-500">Dernière modification:</span>
                            <span class="font-medium">{{ $user->updated_at->format('d/m/Y à H:i') }}</span>
                        </span>
                    </div>

                    <div class="flex items-center text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>
                            <span class="text-slate-500">Statut:</span>
                            <span class="font-medium {{ $user->actif ? 'text-green-600' : 'text-red-600' }}">
                                {{ $user->statut ? 'Actif' : 'Inactif' }}
                            </span>
                        </span>
                    </div>
                </div>

                @can('users.delete')
                    @if(auth()->id() !== $user->id)
                        <div class="mt-6 pt-6 border-t border-slate-200">
                            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-error btn-outline w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Supprimer l'utilisateur
                                </button>
                            </form>
                        </div>
                    @endif
                @endcan
            </div>
        </div>
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
        <span class="text-slate-500">Modifier</span>
    </li>
@endpush

