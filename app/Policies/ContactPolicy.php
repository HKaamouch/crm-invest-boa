<?php

namespace App\Policies;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ContactPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission([
            'view_contacts',
            'create_contacts',
            'edit_contacts',
            'delete_contacts'
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Contact $contact): bool
    {
        return $user->hasPermissionTo('view_contacts');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_contacts');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Contact $contact): bool
    {
        return $user->hasPermissionTo('edit_contacts');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Contact $contact): bool
    {
        // Seuls les administrateurs peuvent supprimer
        return $user->hasRole('Administrateur') &&
            $user->hasPermissionTo('delete_contacts');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Contact $contact): bool
    {
        return $user->hasRole('Administrateur');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Contact $contact): bool
    {
        return $user->hasRole('Administrateur');
    }

    /**
     * Determine whether the user can export contact data.
     */
    public function export(User $user): bool
    {
        return $user->hasPermissionTo('export_data');
    }

    /**
     * Determine whether the user can manage contact-organisation relationships.
     */
    public function manageRelationships(User $user, Contact $contact): bool
    {
        return $user->hasAnyPermission(['edit_contacts', 'edit_organisations']);
    }

    /**
     * Determine whether the user can toggle contact status.
     */
    public function toggleStatus(User $user, Contact $contact): bool
    {
        return $user->hasPermissionTo('edit_contacts');
    }
}
