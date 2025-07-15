<?php

namespace App\Policies;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrganisationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission([
            'view_organisations',
            'create_organisations',
            'edit_organisations',
            'delete_organisations'
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Organisation $organisation): bool
    {
        return $user->hasPermissionTo('view_organisations');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_organisations');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Organisation $organisation): bool
    {
        return $user->hasPermissionTo('edit_organisations');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Organisation $organisation): bool
    {
        // Seuls les administrateurs peuvent supprimer
        return $user->hasRole('Administrateur') &&
            $user->hasPermissionTo('delete_organisations');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Organisation $organisation): bool
    {
        return $user->hasRole('Administrateur');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Organisation $organisation): bool
    {
        return $user->hasRole('Administrateur');
    }

    /**
     * Determine whether the user can export organisation data.
     */
    public function export(User $user): bool
    {
        return $user->hasPermissionTo('export_data');
    }

    /**
     * Determine whether the user can manage organisation-investor relationships.
     */
    public function manageRelationships(User $user, Organisation $organisation): bool
    {
        return $user->hasAnyPermission(['edit_organisations', 'edit_investors']);
    }
}
