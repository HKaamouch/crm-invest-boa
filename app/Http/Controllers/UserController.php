<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;

class UserController extends Controller
{
    /**
     * Affiche la liste des utilisateurs
     */
    public function index(Request $request)
    {
        // Paramètres de pagination
        $perPage = (int) $request->get('per_page', 25);
        $perPage = in_array($perPage, [25, 50, 100]) ? $perPage : 25;

        // Construction de la query avec filtres
        $query = User::query();

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom_complet', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('id', $request->role);
            });
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut == 'actif');
        }

        // Tri
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        $allowedSortFields = ['nom_complet', 'email', 'created_at', 'updated_at'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }

        // Récupération des utilisateurs paginés
        $users = $query->paginate($perPage)->appends($request->query());

        // Récupération des rôles pour le filtre
        $roles = Role::all();

        return view('users.index', compact('users', 'roles', 'perPage'));
    }

    /**
     * Affiche le formulaire de création d'un utilisateur
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Enregistre un nouvel utilisateur
     */
    public function store(StoreUserRequest $request)
    {
        // Création de l'utilisateur
        $user = User::create([
            'nom_complet' => $request->nom_complet,
            'name' => $request->nom_complet,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'statut' => $request->has('actif') ? 'actif' : 'inactif',
            'password' => Hash::make($request->password),
        ]);

        // Attribution du rôle (vérification existence)
        if ($request->role) {
            $role = \Spatie\Permission\Models\Role::find($request->role);
            if ($role) {
                $user->assignRole($role->name);
            }
        }

        // Upload de la photo de profil si fournie
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            //TODO add feature to save avatar path in user model using media library
            //$user->avatar = $avatarPath;
            //$user->save();
        }

        return redirect()->route('users.index')
                        ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Affiche les détails d'un utilisateur
     */
    public function show(User $user)
    {
        $user->load('roles', 'permissions');

        // Récupérer les dernières activités de l'utilisateur
        // Utiliser la bonne méthode selon votre implémentation d'historique d'activités
        if (class_exists(Activity::class)) {
            // Si vous utilisez spatie/laravel-activitylog
            $activities = Activity::causedBy($user)
                ->orderBy('created_at', 'desc')
                ->take(15)
                ->get();
        } else {
            // Fallback si aucun système d'activité n'est disponible
            $activities = collect();
        }

        return view('users.show', compact('user', 'activities'));
    }

    /**
     * Affiche le formulaire d'édition d'un utilisateur
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Met à jour un utilisateur
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        // Mise à jour des informations de base
        $user->nom_complet = $request->nom_complet;
        $user->email = $request->email;
        $user->telephone = $request->telephone;
        $user->statut = $request->has('actif') ? 'actif' : 'inactif';

        // Mise à jour du mot de passe si fourni
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Mise à jour du rôle (en supprimant les anciens, vérification existence)
        if ($request->role) {
            $role = \Spatie\Permission\Models\Role::find($request->role);
            if ($role) {
                $user->syncRoles([$role->name]);
            } else {
                $user->syncRoles([]); // Retire tous les rôles si l'ID n'est pas valide
            }
        }

        // Upload de la photo de profil si fournie
        if ($request->hasFile('avatar')) {
            // Supprimer l'ancienne image si elle existe
            if ($user->avatar) {
                \Storage::disk('public')->delete($user->avatar);
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
            $user->save();
        }

        return redirect()->route('users.index')
                        ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Supprime un utilisateur
     */
    public function destroy(User $user)
    {
        // Vérifier que l'utilisateur n'est pas l'utilisateur connecté
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // Supprimer l'avatar si présent
        if ($user->avatar) {
            \Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('users.index')
                        ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Active ou désactive un utilisateur (AJAX)
     */
    public function toggleStatus(User $user)
    {
        // Empêcher la désactivation de son propre compte
        if (auth()->id() === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas désactiver votre propre compte.'
            ], 403);
        }

        $user->statut = $user->statut === 'actif' ? 'inactif' : 'actif';
        $user->save();

        return response()->json([
            'success' => true,
            'status' => $user->statut,
            'message' => $user->statut === 'actif' ? 'Utilisateur activé.' : 'Utilisateur désactivé.'
        ]);
    }
}
