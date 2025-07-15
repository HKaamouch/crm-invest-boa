<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EmailPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les permissions pour les emails
        $emailPermissions = [
            'view emails',           // Voir les emails
            'send emails',           // Envoyer des emails individuels
            'manage emails',         // Gérer tous les emails (voir, modifier, supprimer)
            'send bulk emails',      // Envoyer des emails en masse
            'view email stats',      // Voir les statistiques des emails
            'manage email templates', // Gérer les modèles d'emails
            'download email attachments', // Télécharger les pièces jointes
            'export emails',         // Exporter les emails
        ];

        foreach ($emailPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Récupérer les rôles existants
        $adminRole = Role::where('name', 'Administrateur')->first();
        $editeurRole = Role::where('name', 'Éditeur')->first();
        $lectureRole = Role::where('name', 'Lecture seule')->first();

        if ($adminRole) {
            // L'administrateur a tous les droits sur les emails
            $adminRole->givePermissionTo($emailPermissions);
            $this->command->info('✓ Permissions emails assignées au rôle Administrateur');
        }

        if ($editeurRole) {
            // L'éditeur peut voir, envoyer et gérer les emails de base
            $editeurRole->givePermissionTo([
                'view emails',
                'send emails',
                'send bulk emails',
                'view email stats',
                'download email attachments',
            ]);
            $this->command->info('✓ Permissions emails assignées au rôle Éditeur');
        }

        if ($lectureRole) {
            // La lecture seule peut seulement voir les emails et télécharger les pièces jointes
            $lectureRole->givePermissionTo([
                'view emails',
                'download email attachments',
            ]);
            $this->command->info('✓ Permissions emails assignées au rôle Lecture seule');
        }

        // Afficher un résumé des permissions créées
        $this->command->info('');
        $this->command->info('📧 Résumé des permissions emails créées :');
        $this->command->table(
            ['Permission', 'Description'],
            [
                ['view emails', 'Consulter la liste et le détail des emails'],
                ['send emails', 'Envoyer des emails individuels aux investisseurs'],
                ['manage emails', 'Gérer tous les emails (modification, suppression)'],
                ['send bulk emails', 'Envoyer des emails groupés à plusieurs destinataires'],
                ['view email stats', 'Consulter les statistiques et analytics des emails'],
                ['manage email templates', 'Créer et gérer les modèles d\'emails'],
                ['download email attachments', 'Télécharger les pièces jointes des emails'],
                ['export emails', 'Exporter les données des emails'],
            ]
        );

        $this->command->info('');
        $this->command->info('🎯 Répartition par rôle :');
        $this->command->info('👑 Administrateur : Toutes les permissions');
        $this->command->info('✏️  Éditeur : Consultation, envoi, envoi groupé, stats, téléchargements');
        $this->command->info('👁️  Lecture seule : Consultation et téléchargements uniquement');

        $this->command->info('');
        $this->command->info('✅ Permissions des emails créées et assignées avec succès.');
    }
}
