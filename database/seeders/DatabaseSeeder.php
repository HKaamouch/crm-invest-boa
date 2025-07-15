<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            // Seeders existants
            RolePermissionSeeder::class,
            UserSeeder::class,
            OrganisationPermissionsSeeder::class,
            InvestorPermissionsSeeder::class,
            CategorieInvestisseurPermissionsSeeder::class,
            ContactPermissionSeeder::class,


            // Nouveaux seeders pour le module investisseurs
            CategorieInvestisseurSeeder::class,
            OrganisationSeeder::class,
            ContactSeeder::class,
            InvestorSeeder::class,
        ]);

        $this->command->info('🎉 Base de données peuplée avec succès !');
        $this->command->info('📊 Données créées :');
        $this->command->info('   - Catégories d\'investisseurs : ' . \App\Models\CategorieInvestisseur::count());
        $this->command->info('   - Organisations : ' . \App\Models\Organisation::count());
        $this->command->info('   - Contacts : ' . \App\Models\Contact::count());
        $this->command->info('   - Investisseurs : ' . \App\Models\Investor::count());
        $this->command->info('   - Interactions : ' . \App\Models\Interaction::count());
        $this->command->info('   - Commentaires : ' . \App\Models\InvestisseurCommentaire::count());
    }
}
