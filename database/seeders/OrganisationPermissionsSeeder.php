<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class OrganisationPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les permissions pour les organisations
        $organisationPermissions = [
            'view_organisations',
            'create_organisations',
            'edit_organisations',
            'delete_organisations',
        ];

        foreach ($organisationPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Récupérer les rôles existants
        $adminRole = Role::where('name', 'Administrateur')->first();
        $editeurRole = Role::where('name', 'Éditeur')->first();
        $lectureRole = Role::where('name', 'Lecture seule')->first();

        if ($adminRole) {
            // L'administrateur a tous les droits sur les organisations
            $adminRole->givePermissionTo($organisationPermissions);
        }

        if ($editeurRole) {
            // L'éditeur peut voir, créer et modifier les organisations
            $editeurRole->givePermissionTo([
                'view_organisations',
                'create_organisations',
                'edit_organisations'
            ]);
        }

        if ($lectureRole) {
            // La lecture seule peut seulement voir les organisations
            $lectureRole->givePermissionTo(['view_organisations']);
        }

        $this->command->info('Permissions des organisations créées et assignées avec succès.');
    }
}
