<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création des permissions pour les utilisateurs
        $userPermissions = [
            'view_users' => 'Voir les utilisateurs',
            'create_users' => 'Créer des utilisateurs',
            'edit_users' => 'Modifier les utilisateurs',
            'delete_users' => 'Supprimer des utilisateurs'
        ];

        foreach ($userPermissions as $name => $description) {
            Permission::firstOrCreate(
                ['name' => $name],
                [
                    'description' => $description,
                    'module' => 'Utilisateurs'
                ]
            );
        }

        // Attribution des permissions au rôle Administrateur
        $adminRole = Role::where('name', 'Administrateur')->first();

        if ($adminRole) {
            $adminRole->givePermissionTo(array_keys($userPermissions));
        }

        // Attribution de certaines permissions au rôle Éditeur
        $editorRole = Role::where('name', 'Éditeur')->first();

        if ($editorRole) {
            $editorRole->givePermissionTo(['view_users']);
        }

        $this->command->info('Permissions utilisateur créées et attribuées aux rôles.');
    }
}
