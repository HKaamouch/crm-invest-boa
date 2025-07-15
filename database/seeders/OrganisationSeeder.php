<?php

namespace Database\Seeders;

use App\Models\Organisation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganisationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organisations = [
            [
                'raison_sociale' => 'Goldman Sachs France',
                'adresse' => '28-32 Pl. de la Madeleine',
                'ville' => 'Paris',
                'pays' => 'France',
                'telephone' => '+33 1 42 86 10 20',
                'fax' => '+33 1 42 86 10 21',
                'email' => 'contact@gs.com',
                'site_web' => 'https://www.goldmansachs.com',
                'description' => 'Banque d\'investissement américaine de premier plan'
            ],
            [
                'raison_sociale' => 'BlackRock Inc.',
                'adresse' => '55 East 52nd Street',
                'ville' => 'New York',
                'pays' => 'États-Unis',
                'telephone' => '+1 212 810 5300',
                'email' => 'contact@blackrock.com',
                'site_web' => 'https://www.blackrock.com',
                'description' => 'Plus grand gestionnaire d\'actifs au monde'
            ],
            [
                'raison_sociale' => 'Caisse de Dépôt et de Gestion',
                'adresse' => 'Place Moulay Hassan',
                'ville' => 'Rabat',
                'pays' => 'Maroc',
                'telephone' => '+212 5 37 56 88 00',
                'fax' => '+212 5 37 56 88 99',
                'email' => 'contact@cdg.ma',
                'site_web' => 'https://www.cdg.ma',
                'description' => 'Institution financière publique marocaine'
            ],
            [
                'raison_sociale' => 'Intesa Sanpaolo',
                'adresse' => 'Piazza San Carlo 156',
                'ville' => 'Turin',
                'pays' => 'Italie',
                'telephone' => '+39 02 87943 200',
                'email' => 'info@intesasanpaolo.com',
                'site_web' => 'https://www.intesasanpaolo.com',
                'description' => 'Groupe bancaire italien de référence'
            ],
            [
                'raison_sociale' => 'Temasek Holdings',
                'adresse' => '60B Orchard Road',
                'ville' => 'Singapour',
                'pays' => 'Singapour',
                'telephone' => '+65 6828 6828',
                'email' => 'contact@temasek.com.sg',
                'site_web' => 'https://www.temasek.com.sg',
                'description' => 'Fonds souverain de Singapour'
            ],
            [
                'raison_sociale' => 'JP Morgan Chase & Co',
                'adresse' => '383 Madison Avenue',
                'ville' => 'New York',
                'pays' => 'États-Unis',
                'telephone' => '+1 212 270 6000',
                'email' => 'investor.relations@jpmchase.com',
                'site_web' => 'https://www.jpmorganchase.com',
                'description' => 'Banque d\'investissement mondiale'
            ],
            [
                'raison_sociale' => 'Credit Suisse',
                'adresse' => 'Paradeplatz 8',
                'ville' => 'Zurich',
                'pays' => 'Suisse',
                'telephone' => '+41 44 333 1111',
                'email' => 'info@credit-suisse.com',
                'site_web' => 'https://www.credit-suisse.com',
                'description' => 'Banque suisse spécialisée dans la gestion de fortune'
            ],
            [
                'raison_sociale' => 'Vanguard Group',
                'adresse' => '100 Vanguard Blvd',
                'ville' => 'Malvern',
                'pays' => 'États-Unis',
                'telephone' => '+1 610 648 6000',
                'email' => 'contact@vanguard.com',
                'site_web' => 'https://www.vanguard.com',
                'description' => 'Société de gestion d\'investissement américaine'
            ]
        ];

        foreach ($organisations as $organisation) {
            Organisation::create($organisation);
        }
    }
}
