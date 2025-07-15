<?php

namespace Database\Seeders;

use App\Models\CategorieInvestisseur;
use App\Models\Interaction;
use App\Models\Investor;
use App\Models\InvestorEmailAddress;
use App\Models\InvestisseurCommentaire;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvestorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les catégories et organisations
        $categories = CategorieInvestisseur::all();
        $organisations = Organisation::all();
        $adminUser = User::where('email', 'admin@bankofafrica.com')->first();

        $investors = [
            [
                'civilite' => 'M',
                'prenom' => 'Jean-Pierre',
                'nom' => 'Dubois',
                'pays' => 'France',
                'email' => 'jp.dubois@analyste-finance.fr',
                'telephone' => '+33 1 42 86 10 20',
                'mobile' => '+33 6 78 90 12 34',
                'fonction' => 'Analyste Senior',
                'langue_preferee' => 'Français',
                'niveau_influence' => 'Élevé',
                'tags' => ['VIP', 'Secteur Bancaire', 'Influence Media'],
                'remarques' => 'Très intéressé par le secteur bancaire africain. Contact prioritaire pour les communications trimestrielles.',
                'categorie_nom' => 'Analyste',
                'organisations_relations' => [
                    ['nom' => 'Goldman Sachs France', 'poste' => 'Analyste Senior', 'actuel' => true]
                ]
            ],
            [
                'civilite' => 'Mme',
                'prenom' => 'Sarah',
                'nom' => 'Mitchell',
                'pays' => 'États-Unis',
                'email' => 'sarah.mitchell@blackrock.com',
                'telephone' => '+1 212 810 5300',
                'mobile' => '+1 646 555 0123',
                'fonction' => 'Portfolio Manager',
                'langue_preferee' => 'Anglais',
                'niveau_influence' => 'Critique',
                'tags' => ['Marchés Émergents', 'ESG', 'Large Cap'],
                'remarques' => 'Gestionnaire de portefeuille pour les marchés émergents. Demande des rapports détaillés.',
                'categorie_nom' => 'Institutionnel',
                'organisations_relations' => [
                    ['nom' => 'BlackRock Inc.', 'poste' => 'Portfolio Manager', 'actuel' => true]
                ]
            ],
            [
                'civilite' => 'M',
                'prenom' => 'Mohammed',
                'nom' => 'El Fassi',
                'pays' => 'Maroc',
                'email' => 'mel.fassi@cdg.ma',
                'telephone' => '+212 5 37 56 88 00',
                'mobile' => '+212 6 12 34 56 78',
                'fonction' => 'Directeur des Investissements',
                'langue_preferee' => 'Français',
                'niveau_influence' => 'Critique',
                'tags' => ['Partenaire Stratégique', 'Maroc', 'Infrastructure'],
                'remarques' => 'Partenaire stratégique local. Relations privilégiées avec la direction.',
                'categorie_nom' => 'Institutionnel',
                'organisations_relations' => [
                    ['nom' => 'Caisse de Dépôt et de Gestion', 'poste' => 'Directeur des Investissements', 'actuel' => true]
                ]
            ],
            [
                'civilite' => 'Mme',
                'prenom' => 'Laura',
                'nom' => 'Rossi',
                'pays' => 'Italie',
                'email' => 'laura.rossi@intesasanpaolo.com',
                'telephone' => '+39 02 87943 200',
                'mobile' => '+39 335 123 4567',
                'fonction' => 'Research Analyst',
                'langue_preferee' => 'Anglais',
                'niveau_influence' => 'Élevé',
                'tags' => ['Research', 'Méditerranée', 'Publications'],
                'remarques' => 'Spécialiste du secteur bancaire méditerranéen. Publie des notes de recherche influentes.',
                'categorie_nom' => 'Analyste',
                'organisations_relations' => [
                    ['nom' => 'Intesa Sanpaolo', 'poste' => 'Research Analyst', 'actuel' => true]
                ]
            ],
            [
                'civilite' => 'M',
                'prenom' => 'David',
                'nom' => 'Chen',
                'pays' => 'Singapour',
                'email' => 'david.chen@temasek.com.sg',
                'telephone' => '+65 6828 6828',
                'mobile' => '+65 9123 4567',
                'fonction' => 'Investment Director',
                'langue_preferee' => 'Anglais',
                'niveau_influence' => 'Critique',
                'tags' => ['Fonds Souverain', 'Asie', 'Growth Capital'],
                'remarques' => 'Fonds souverain intéressé par les investissements en Afrique. Potentiel d\'investissement significatif.',
                'categorie_nom' => 'Souverain',
                'organisations_relations' => [
                    ['nom' => 'Temasek Holdings', 'poste' => 'Investment Director', 'actuel' => true]
                ]
            ],
            [
                'civilite' => 'Dr',
                'prenom' => 'Klaus',
                'nom' => 'Weber',
                'pays' => 'Allemagne',
                'email' => 'klaus.weber@db.com',
                'telephone' => '+49 69 910 00',
                'mobile' => '+49 172 123 4567',
                'fonction' => 'Managing Director',
                'langue_preferee' => 'Anglais',
                'niveau_influence' => 'Élevé',
                'tags' => ['Europe', 'Corporate Banking', 'Structured Products'],
                'remarques' => 'Directeur pour l\'Afrique chez Deutsche Bank. Expert en financements structurés.',
                'categorie_nom' => 'Banque',
                'organisations_relations' => []
            ],
            [
                'civilite' => 'Mme',
                'prenom' => 'Aisha',
                'nom' => 'Patel',
                'pays' => 'Royaume-Uni',
                'email' => 'aisha.patel@hsbc.com',
                'telephone' => '+44 20 7991 8888',
                'mobile' => '+44 77 1234 5678',
                'fonction' => 'Fund Manager',
                'langue_preferee' => 'Anglais',
                'niveau_influence' => 'Moyen',
                'tags' => ['ESG Investing', 'Emerging Markets', 'Fixed Income'],
                'remarques' => 'Gestionnaire de fonds spécialisée en investissement responsable.',
                'categorie_nom' => 'Fonds',
                'organisations_relations' => []
            ],
            [
                'civilite' => 'M',
                'prenom' => 'Youssef',
                'nom' => 'Benali',
                'pays' => 'Maroc',
                'email' => 'y.benali@investprive.ma',
                'telephone' => '+212 5 22 95 00 00',
                'mobile' => '+212 6 61 23 45 67',
                'fonction' => 'Président',
                'langue_preferee' => 'Français',
                'niveau_influence' => 'Moyen',
                'tags' => ['HNWI', 'Family Office', 'Real Estate'],
                'remarques' => 'Investisseur privé fortuné. Intéressé par les opportunités de croissance.',
                'categorie_nom' => 'Particulier',
                'organisations_relations' => []
            ]
        ];

        foreach ($investors as $investorData) {
            // Récupérer la catégorie
            $categorie = $categories->where('nom', $investorData['categorie_nom'])->first();

            // Préparer les données pour la création
            $investorToCreate = [
                'civilite' => $investorData['civilite'],
                'prenom' => $investorData['prenom'],
                'nom' => $investorData['nom'],
                'pays' => $investorData['pays'],
                'email' => $investorData['email'],
                'telephone' => $investorData['telephone'],
                'mobile' => $investorData['mobile'],
                'fonction' => $investorData['fonction'],
                'langue_preferee' => $investorData['langue_preferee'],
                'remarques' => $investorData['remarques'],
                'niveau_influence' => $investorData['niveau_influence'],
                'tags' => json_encode($investorData['tags']),
                'categorie_id' => $categorie?->id,
            ];

            $investor = Investor::create($investorToCreate);

            // Créer l'adresse email unique
            $identifier = str_pad($investor->id, 4, '0', STR_PAD_LEFT);
            InvestorEmailAddress::create([
                'investor_id' => $investor->id,
                'unique_email' => "investor-{$identifier}@crm.ir-boa.com",
                'identifier' => $identifier
            ]);

            // Associer aux organisations
            if (!empty($investorData['organisations_relations'])) {
                foreach ($investorData['organisations_relations'] as $orgRelation) {
                    $organisation = $organisations->where('raison_sociale', $orgRelation['nom'])->first();
                    if ($organisation) {
                        $investor->organisations()->attach($organisation->id, [
                            'poste' => $orgRelation['poste'],
                            'date_debut' => now()->subYears(rand(1, 3)),
                            'actuel' => $orgRelation['actuel'],
                            'notes' => 'Relation établie via seeder'
                        ]);
                    }
                }
            }

            // Ajouter des interactions de test
            $this->createInteractions($investor, $adminUser);

            // Ajouter des commentaires de test
            $this->createComments($investor, $adminUser);
        }
    }

    private function createInteractions($investor, $user)
    {
        $interactionTypes = ['Email', 'Appel', 'Réunion', 'Email envoyé'];
        $descriptions = [
            'Envoi du rapport trimestriel et discussion des résultats financiers.',
            'Appel de suivi concernant les perspectives d\'investissement.',
            'Réunion de présentation de la stratégie 2025.',
            'Email de remerciement suite à la conférence investisseurs.',
            'Appel pour clarification sur les nouveaux produits financiers.',
            'Présentation des résultats semestriels en visioconférence.',
            'Échange sur les opportunités du marché africain.',
            'Suivi post-meeting et envoi de documentation complémentaire.'
        ];

        for ($i = 0; $i < rand(2, 5); $i++) {
            Interaction::create([
                'investor_id' => $investor->id,
                'user_id' => $user->id,
                'type' => $interactionTypes[array_rand($interactionTypes)],
                'date_interaction' => now()->subDays(rand(1, 180)),
                'description' => $descriptions[array_rand($descriptions)]
            ]);
        }

        // Mettre à jour la dernière interaction
        $derniereInteraction = $investor->interactions()->orderBy('date_interaction', 'desc')->first();
        if ($derniereInteraction) {
            $investor->update(['derniere_interaction' => $derniereInteraction->date_interaction]);
        }
    }

    private function createComments($investor, $user)
    {
        $commentaires = [
            'Investisseur très réactif et professionnel dans ses échanges.',
            'Montre un intérêt particulier pour nos initiatives ESG.',
            'Demande régulièrement des analyses sectorielles approfondies.',
            'Contact privilégié pour nos communications stratégiques.',
            'Très influent dans son réseau, peut ouvrir de nouvelles opportunités.',
            'Apprécie la transparence et la régularité de nos communications.',
            'Potentiel d\'investissement significatif à moyen terme.'
        ];

        for ($i = 0; $i < rand(1, 3); $i++) {
            InvestisseurCommentaire::create([
                'investor_id' => $investor->id,
                'user_id' => $user->id,
                'commentaire' => $commentaires[array_rand($commentaires)],
                'prive' => rand(0, 1) === 1,
                'created_at' => now()->subDays(rand(1, 60))
            ]);
        }
    }
}
