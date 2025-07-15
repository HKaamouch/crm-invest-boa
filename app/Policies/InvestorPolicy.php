<?php

namespace App\Policies;

use App\Models\Investor;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InvestorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission([
            'view_investors',
            'create_investors',
            'edit_investors',
            'delete_investors'
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Investor $investor): bool
    {
        return $user->hasPermissionTo('view_investors');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_investors');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Investor $investor): bool
    {
        return $user->hasPermissionTo('edit_investors');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Investor $investor): bool
    {
        // Seuls les administrateurs peuvent supprimer
        return $user->hasRole('Administrateur') &&
            $user->hasPermissionTo('delete_investors');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Investor $investor): bool
    {
        return $user->hasRole('Administrateur');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Investor $investor): bool
    {
        return $user->hasRole('Administrateur');
    }

    /**
     * Determine whether the user can export investor data.
     */
    public function export(User $user): bool
    {
        return $user->hasPermissionTo('export_data');
    }

    /**
     * Determine whether the user can view the timeline.
     */
    public function viewTimeline(User $user, Investor $investor): bool
    {
        return $user->hasPermissionTo('view_investors');
    }

    /**
     * Determine whether the user can manage investor-organisation relationships.
     */
    public function manageRelationships(User $user, Investor $investor): bool
    {
        return $user->hasAnyPermission(['edit_investors', 'edit_organisations']);
    }

    /**
     * Determine whether the user can manage investor categories.
     */
    public function manageCategories(User $user, Investor $investor): bool
    {
        return $user->hasPermissionTo('edit_investors');
    }
}
