<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création des permissions groupées par module
        $permissionsByModule = [
            'Investisseurs' => [
                'view_investors' => 'Voir les investisseurs',
                'create_investors' => 'Créer des investisseurs',
                'edit_investors' => 'Modifier les investisseurs',
                'delete_investors' => 'Supprimer des investisseurs',
                'export_investors' => 'Exporter les investisseurs'
            ],
            'Organisations' => [
                'view_organisations' => 'Voir les organisations',
                'create_organisations' => 'Créer des organisations',
                'edit_organisations' => 'Modifier les organisations',
                'delete_organisations' => 'Supprimer des organisations'
            ],
            'Contacts' => [
                'view_contacts' => 'Voir les contacts',
                'create_contacts' => 'Créer des contacts',
                'edit_contacts' => 'Modifier les contacts',
                'delete_contacts' => 'Supprimer des contacts'
            ],
            'Emails' => [
                'view_emails' => 'Voir les emails',
                'send_emails' => 'Envoyer des emails',
                'send_bulk_emails' => 'Envoyer des emails en masse',
                'export_emails' => 'Exporter les emails',
                'view_email_stats' => 'Voir les statistiques d\'emails',
                'manage_email_templates' => 'Gérer les modèles d\'emails'
            ],
            'Catégories' => [
                'view_categories' => 'Voir les catégories',
                'create_categories' => 'Créer des catégories',
                'edit_categories' => 'Modifier les catégories',
                'delete_categories' => 'Supprimer des catégories',
                'reorder_categories' => 'Réorganiser les catégories'
            ],
            'Système' => [
                'export_data' => 'Exporter des données',
                'import_data' => 'Importer des données',
                'view_logs' => 'Voir les logs système',
                'manage_settings' => 'Gérer les paramètres système'
            ]
        ];

        // Création des rôles s'ils n'existent pas déjà
        $roles = [
            'Administrateur' => 'Accès complet à toutes les fonctionnalités',
            'Éditeur' => 'Peut voir et modifier la plupart des données',
            'Lecteur' => 'Peut uniquement consulter les données'
        ];

        foreach ($roles as $name => $description) {
            Role::firstOrCreate(['name' => $name], ['guard_name' => 'web']);
        }

        // Création des permissions
        foreach ($permissionsByModule as $module => $permissions) {
            foreach ($permissions as $name => $description) {
                Permission::firstOrCreate(
                    ['name' => $name],
                    [
                        'description' => $description,
                        'module' => $module
                    ]
                );
            }
        }

        // Attribution des permissions aux rôles
        $adminRole = Role::where('name', 'Administrateur')->first();
        $editorRole = Role::where('name', 'Éditeur')->first();
        $readerRole = Role::where('name', 'Lecteur')->first();

        // Administrateur a toutes les permissions
        if ($adminRole) {
            $allPermissions = Permission::all()->pluck('name')->toArray();
            $adminRole->syncPermissions($allPermissions);
        }

        // Éditeur a des permissions de lecture et d'édition, mais pas de suppression
        if ($editorRole) {
            $editorPermissions = [
                // Investisseurs
                'view_investors', 'create_investors', 'edit_investors',
                // Organisations
                'view_organisations', 'create_organisations', 'edit_organisations',
                // Contacts
                'view_contacts', 'create_contacts', 'edit_contacts',
                // Emails
                'view_emails', 'send_emails', 'view_email_stats',
                // Catégories
                'view_categories', 'create_categories', 'edit_categories',
                // Système
                'export_data'
            ];
            $editorRole->syncPermissions($editorPermissions);
        }

        // Lecteur a seulement des permissions de lecture
        if ($readerRole) {
            $readerPermissions = [
                'view_investors', 'view_organisations', 'view_contacts',
                'view_emails', 'view_categories'
            ];
            $readerRole->syncPermissions($readerPermissions);
        }

        $this->command->info('Permissions et rôles créés avec succès.');
    }
}
