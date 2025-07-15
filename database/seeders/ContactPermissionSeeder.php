<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ContactPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les permissions pour les contacts
        $contactPermissions = [
            'view_contacts',
            'create_contacts',
            'edit_contacts',
            'delete_contacts',
        ];

        foreach ($contactPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Récupérer les rôles existants
        $adminRole = Role::where('name', 'Administrateur')->first();
        $editeurRole = Role::where('name', 'Éditeur')->first();
        $lectureRole = Role::where('name', 'Lecture seule')->first();

        if ($adminRole) {
            // L'administrateur a tous les droits sur les contacts
            $adminRole->givePermissionTo($contactPermissions);
        }

        if ($editeurRole) {
            // L'éditeur peut voir, créer et modifier les contacts
            $editeurRole->givePermissionTo([
                'view_contacts',
                'create_contacts',
                'edit_contacts'
            ]);
        }

        if ($lectureRole) {
            // La lecture seule peut seulement voir les contacts
            $lectureRole->givePermissionTo(['view_contacts']);
        }

        $this->command->info('Permissions des contacts créées et assignées avec succès.');
    }
}
