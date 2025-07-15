<?php

namespace Database\Seeders;

use App\Models\CategorieInvestisseur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorieInvestisseurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'nom' => 'Institutionnel',
                'description' => 'Investisseurs institutionnels (fonds de pension, assurances, etc.)',
                'couleur_hexa' => '#3B82F6',
                'ordre_affichage' => 1,
                'actif' => true
            ],
            [
                'nom' => 'Analyste',
                'description' => 'Analystes financiers et de recherche',
                'couleur_hexa' => '#10B981',
                'ordre_affichage' => 2,
                'actif' => true
            ],
            [
                'nom' => 'Fonds',
                'description' => 'Fonds d\'investissement et gestionnaires d\'actifs',
                'couleur_hexa' => '#F59E0B',
                'ordre_affichage' => 3,
                'actif' => true
            ],
            [
                'nom' => 'Banque',
                'description' => 'Institutions bancaires et financières',
                'couleur_hexa' => '#8B5CF6',
                'ordre_affichage' => 4,
                'actif' => true
            ],
            [
                'nom' => 'Particulier',
                'description' => 'Investisseurs particuliers fortunés',
                'couleur_hexa' => '#EF4444',
                'ordre_affichage' => 5,
                'actif' => true
            ],
            [
                'nom' => 'Souverain',
                'description' => 'Fonds souverains et entités gouvernementales',
                'couleur_hexa' => '#6366F1',
                'ordre_affichage' => 6,
                'actif' => true
            ]
        ];

        foreach ($categories as $categorie) {
            CategorieInvestisseur::create($categorie);
        }
    }
}
