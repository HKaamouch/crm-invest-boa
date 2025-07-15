<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CategorieInvestisseurPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les permissions pour les catégories d'investisseurs
        $categoriePermissions = [
            'view_categories_investisseurs',
            'create_categories_investisseurs',
            'edit_categories_investisseurs',
            'delete_categories_investisseurs',
        ];

        foreach ($categoriePermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Récupérer les rôles existants
        $adminRole = Role::where('name', 'Administrateur')->first();
        $editeurRole = Role::where('name', 'Éditeur')->first();
        $lectureRole = Role::where('name', 'Lecture seule')->first();

        if ($adminRole) {
            // L'administrateur a tous les droits sur les catégories
            $adminRole->givePermissionTo($categoriePermissions);
        }

        if ($editeurRole) {
            // L'éditeur peut voir, créer et modifier les catégories
            $editeurRole->givePermissionTo([
                'view_categories_investisseurs',
                'create_categories_investisseurs',
                'edit_categories_investisseurs'
            ]);
        }

        if ($lectureRole) {
            // La lecture seule peut seulement voir les catégories
            $lectureRole->givePermissionTo(['view_categories_investisseurs']);
        }

        $this->command->info('Permissions des catégories d\'investisseurs créées et assignées avec succès.');
    }
}
