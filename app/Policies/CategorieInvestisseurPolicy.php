<?php

namespace App\Policies;

use App\Models\CategorieInvestisseur;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategorieInvestisseurPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission([
            'view_categories_investisseurs',
            'create_categories_investisseurs',
            'edit_categories_investisseurs',
            'delete_categories_investisseurs'
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CategorieInvestisseur $categorieInvestisseur): bool
    {
        return $user->hasPermissionTo('view_categories_investisseurs');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_categories_investisseurs');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->hasPermissionTo('edit_categories_investisseurs');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CategorieInvestisseur $categorieInvestisseur): bool
    {
        // Vérifier que la catégorie n'a pas d'investisseurs associés
        if ($categorieInvestisseur->investors_count > 0) {
            return false;
        }

        // Seuls les administrateurs peuvent supprimer
        return $user->hasRole('Administrateur') &&
            $user->hasPermissionTo('delete_categories_investisseurs');
    }

    /**
     * Determine whether the user can reorder categories.
     */
    public function reorder(User $user): bool
    {
        return $user->hasPermissionTo('edit_categories_investisseurs');
    }
}
