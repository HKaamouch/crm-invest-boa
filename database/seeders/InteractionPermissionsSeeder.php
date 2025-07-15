<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InteractionPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création des permissions pour les interactions
        $interactionPermissions = [
            'view_interactions' => 'Voir les interactions',
            'create_interactions' => 'Créer des interactions',
            'edit_interactions' => 'Modifier les interactions',
            'delete_interactions' => 'Supprimer des interactions'
        ];

        foreach ($interactionPermissions as $name => $description) {
            Permission::firstOrCreate(
                ['name' => $name],
                [
                    'description' => $description,
                    'module' => 'Interactions'
                ]
            );
        }

        // Attribution des permissions au rôle Administrateur
        $adminRole = Role::where('name', 'Administrateur')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo(array_keys($interactionPermissions));
        }

        // Attribution des permissions au rôle Éditeur
        $editorRole = Role::where('name', 'Éditeur')->first();
        if ($editorRole) {
            $editorRole->givePermissionTo(['view_interactions', 'create_interactions', 'edit_interactions']);
        }

        // Attribution des permissions au rôle Lecteur
        $readerRole = Role::where('name', 'Lecteur')->first();
        if ($readerRole) {
            $readerRole->givePermissionTo(['view_interactions']);
        }

        $this->command->info('Permissions d\'interactions créées et attribuées aux rôles.');
    }
}

