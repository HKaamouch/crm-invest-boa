<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission([
            'view_users',
            'create_users',
            'edit_users',
            'delete_users'
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->hasPermissionTo('view_users');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_users');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return $user->hasPermissionTo('edit_users');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Empêcher la suppression de son propre compte
        if ($user->id === $model->id) {
            return false;
        }

        return $user->hasPermissionTo('delete_users');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->hasRole('Administrateur');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasRole('Administrateur');
    }

    /**
     * Determine whether the user can toggle user status.
     */
    public function toggleStatus(User $user, User $model): bool
    {
        // Empêcher la désactivation de son propre compte
        if ($user->id === $model->id) {
            return false;
        }

        return $user->hasPermissionTo('edit_users');
    }
}
