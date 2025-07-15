<?php

namespace App\Policies;

use App\Models\Interaction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EmailPolicy
{
    /**
     * Determine whether the user can view any emails.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['view emails', 'manage emails', 'admin']);
    }

    /**
     * Determine whether the user can view the email.
     */
    public function view(User $user): bool
    {


        // Les admins peuvent tout voir
        if ($user->hasPermissionTo('admin')) {
            return true;
        }

        // L'utilisateur peut voir ses propres emails


        // Les utilisateurs avec permission 'manage emails' peuvent voir tous les emails
        if ($user->hasPermissionTo('manage emails')) {
            return true;
        }

        // Les utilisateurs avec permission 'view emails' peuvent voir tous les emails
        if ($user->hasPermissionTo('view emails')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create emails.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyPermission(['send emails', 'manage emails', 'admin']);
    }

    /**
     * Determine whether the user can send emails.
     */
    public function send(User $user): bool
    {
        return $user->hasAnyPermission(['send emails', 'manage emails', 'admin']);
    }

    /**
     * Determine whether the user can update the email.
     */
    public function update(User $user, Interaction $email): bool
    {
        // Vérifier que c'est bien un email
        if (!in_array($email->type, ['Email envoyé', 'Email reçu'])) {
            return false;
        }

        // Les admins peuvent tout modifier
        if ($user->hasPermissionTo('admin')) {
            return true;
        }

        // L'utilisateur peut modifier ses propres emails (pendant un délai limité)
        if ($email->user_id === $user->id && $email->created_at->diffInHours() < 24) {
            return $user->hasAnyPermission(['send emails', 'manage emails']);
        }

        // Les utilisateurs avec permission 'manage emails' peuvent modifier tous les emails
        if ($user->hasPermissionTo('manage emails')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the email.
     */
    public function delete(User $user, Interaction $email): bool
    {
        // Vérifier que c'est bien un email
        if (!in_array($email->type, ['Email envoyé', 'Email reçu'])) {
            return false;
        }

        // Seuls les admins peuvent supprimer des emails
        if ($user->hasPermissionTo('admin')) {
            return true;
        }

        // Les utilisateurs avec permission 'manage emails' peuvent supprimer
        if ($user->hasPermissionTo('manage emails')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the email.
     */
    public function restore(User $user, Interaction $email): bool
    {
        return $user->hasPermissionTo('admin');
    }

    /**
     * Determine whether the user can permanently delete the email.
     */
    public function forceDelete(User $user, Interaction $email): bool
    {
        return $user->hasPermissionTo('admin');
    }

    /**
     * Determine whether the user can download email attachments.
     */
    public function downloadAttachment(User $user, Interaction $email): bool
    {
        return $this->view($user, $email);
    }

    /**
     * Determine whether the user can export emails.
     */
    public function export(User $user): bool
    {
        return $user->hasAnyPermission(['manage emails', 'admin']);
    }

    /**
     * Determine whether the user can send bulk emails.
     */
    public function sendBulk(User $user): bool
    {
        return $user->hasAnyPermission(['send bulk emails', 'manage emails', 'admin']);
    }

    /**
     * Determine whether the user can view email statistics.
     */
    public function viewStats(User $user): bool
    {
        return $user->hasAnyPermission(['view email stats', 'manage emails', 'admin']);
    }

    /**
     * Determine whether the user can access email templates.
     */
    public function manageTemplates(User $user): bool
    {
        return $user->hasAnyPermission(['manage email templates', 'manage emails', 'admin']);
    }

    /**
     * Determine whether the user can configure email settings.
     */
    public function configureSettings(User $user): bool
    {
        return $user->hasPermissionTo('admin');
    }
}
