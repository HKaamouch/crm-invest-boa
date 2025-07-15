<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InvestorPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les permissions pour les investisseurs
        $investorPermissions = [
            'view_investors',
            'create_investors',
            'edit_investors',
            'delete_investors',
        ];

        foreach ($investorPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Récupérer les rôles existants
        $adminRole = Role::where('name', 'Administrateur')->first();
        $editeurRole = Role::where('name', 'Éditeur')->first();
        $lectureRole = Role::where('name', 'Lecture seule')->first();

        if ($adminRole) {
            // L'administrateur a tous les droits sur les investisseurs
            $adminRole->givePermissionTo($investorPermissions);
        }

        if ($editeurRole) {
            // L'éditeur peut voir, créer et modifier les investisseurs
            $editeurRole->givePermissionTo([
                'view_investors',
                'create_investors',
                'edit_investors'
            ]);
        }

        if ($lectureRole) {
            // La lecture seule peut seulement voir les investisseurs
            $lectureRole->givePermissionTo(['view_investors']);
        }

        $this->command->info('Permissions des investisseurs créées et assignées avec succès.');
    }
}
