<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [


            // Permissions Interactions
            'view_interactions',
            'create_interactions',
            'edit_interactions',
            'delete_interactions',

            // Permissions Exports
            'export_data',

            // Permissions Administration
            'manage_users',
            'view_logs',
            'manage_roles',

            // Permissions Emails
            'send_emails',
            'view_email_logs',
        ];

        foreach ($permissions as $permission) {
            // Créer les permissions si elles n'existent pas déjà
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Créer les rôles et assigner les permissions

        // ADMINISTRATEUR - Tous les droits
        $adminRole = Role::firstOrCreate(['name' => 'Administrateur']);
        $adminRole->givePermissionTo(Permission::all());

        // ÉDITEUR - Peut tout faire sauf admin
        $editeurRole = Role::firstOrCreate(['name' => 'Éditeur']);
        $editeurRole->givePermissionTo([
            'view_interactions', 'create_interactions', 'edit_interactions',
            'send_emails', 'view_email_logs'
        ]);

        // LECTURE SEULE - Consultation uniquement
        $lectureRole = Role::firstOrCreate(['name' => 'Lecture seule']);
        $lectureRole->givePermissionTo([
            'view_interactions'
        ]);
    }
}
