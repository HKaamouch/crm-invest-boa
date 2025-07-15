<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Organisation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contacts = [
            [
                'prenom' => 'Marie',
                'nom' => 'Dubois',
                'email' => 'marie.dubois@gs.com',
                'telephone' => '+33 1 42 86 10 25',
                'mobile' => '+33 6 78 90 12 34',
                'notes' => 'Responsable des relations clients institutionnels'
            ],
            [
                'prenom' => 'James',
                'nom' => 'Wilson',
                'email' => 'james.wilson@blackrock.com',
                'telephone' => '+1 212 810 5320',
                'mobile' => '+1 646 555 0123',
                'notes' => 'Spécialiste des marchés émergents'
            ],
            [
                'prenom' => 'Fatima',
                'nom' => 'Zahra',
                'email' => 'f.zahra@cdg.ma',
                'telephone' => '+212 5 37 56 88 15',
                'mobile' => '+212 6 12 34 56 78',
                'notes' => 'Directrice adjointe des investissements'
            ],
            [
                'prenom' => 'Marco',
                'nom' => 'Rossi',
                'email' => 'marco.rossi@intesasanpaolo.com',
                'telephone' => '+39 02 87943 250',
                'mobile' => '+39 335 123 4567',
                'notes' => 'Analyste senior secteur bancaire'
            ],
            [
                'prenom' => 'Li',
                'nom' => 'Zhang',
                'email' => 'li.zhang@temasek.com.sg',
                'telephone' => '+65 6828 6850',
                'mobile' => '+65 9123 4567',
                'notes' => 'Investment Manager - Africa'
            ],
            [
                'prenom' => 'Sarah',
                'nom' => 'Johnson',
                'email' => 's.johnson@jpmchase.com',
                'telephone' => '+1 212 270 6100',
                'mobile' => '+1 917 555 0198',
                'notes' => 'VP Investor Relations'
            ],
            [
                'prenom' => 'Hans',
                'nom' => 'Mueller',
                'email' => 'hans.mueller@credit-suisse.com',
                'telephone' => '+41 44 333 1150',
                'mobile' => '+41 79 123 4567',
                'notes' => 'Private Banking Director'
            ],
            [
                'prenom' => 'Emma',
                'nom' => 'Thompson',
                'email' => 'emma.thompson@vanguard.com',
                'telephone' => '+1 610 648 6100',
                'mobile' => '+1 267 555 0176',
                'notes' => 'Portfolio Manager - International Equity'
            ]
        ];

        foreach ($contacts as $contactData) {
            Contact::firstOrCreate(
                ['email' => $contactData['email']],
                [
                    'prenom' => $contactData['prenom'],
                    'nom' => $contactData['nom'],
                    'telephone' => $contactData['telephone'],
                    'mobile' => $contactData['mobile'],
                    'notes' => $contactData['notes']
                ]
            );
        }

        // Associer les contacts aux organisations
        $this->attachContactsToOrganisations();
    }

    private function attachContactsToOrganisations()
    {
        $associations = [
            ['contact_nom' => 'Dubois', 'organisation_nom' => 'Goldman Sachs France', 'poste' => 'Directrice Relations Clients'],
            ['contact_nom' => 'Wilson', 'organisation_nom' => 'BlackRock Inc.', 'poste' => 'Senior Portfolio Manager'],
            ['contact_nom' => 'Zahra', 'organisation_nom' => 'Caisse de Dépôt et de Gestion', 'poste' => 'Directrice Adjointe'],
            ['contact_nom' => 'Rossi', 'organisation_nom' => 'Intesa Sanpaolo', 'poste' => 'Analyste Senior'],
            ['contact_nom' => 'Zhang', 'organisation_nom' => 'Temasek Holdings', 'poste' => 'Investment Manager'],
            ['contact_nom' => 'Johnson', 'organisation_nom' => 'JP Morgan Chase & Co', 'poste' => 'VP Investor Relations'],
            ['contact_nom' => 'Mueller', 'organisation_nom' => 'Credit Suisse', 'poste' => 'Director Private Banking'],
            ['contact_nom' => 'Thompson', 'organisation_nom' => 'Vanguard Group', 'poste' => 'Portfolio Manager']
        ];

        foreach ($associations as $assoc) {
            $contact = Contact::where('nom', $assoc['contact_nom'])->first();
            $organisation = Organisation::where('raison_sociale', $assoc['organisation_nom'])->first();

            if ($contact && $organisation) {
                // Vérifier si la relation existe déjà
                if ($contact->organisations()->where('organisation_id', $organisation->id)->exists()) {
                    continue; // Relation déjà existante, passer à la suivante
                }
                $contact->organisations()->attach($organisation->id, [
                    'poste' => $assoc['poste'],
                    'date_debut' => now()->subYears(rand(1, 5)),
                    'actuel' => true
                ]);
            }
        }
    }
}
