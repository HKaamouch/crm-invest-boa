<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvestorRequest;
use App\Http\Requests\UpdateInvestorRequest;
use App\Models\CategorieInvestisseur;
use App\Models\Interaction;
use App\Models\Investor;
use App\Models\InvestorEmailAddress;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InvestorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Paramètres de vue et pagination
        $view = $request->get('view', 'list'); // list ou grid
        $perPage = (int) $request->get('per_page', 12);
        $perPage = in_array($perPage, [12, 24, 48, 96]) ? $perPage : 12;

        // Construction de la query avec filtres
        $query = Investor::with(['categorie', 'organisationsActuelles'])
            ->orderBy('created_at', 'desc');

        // Filtres
        if ($request->filled('search')) {
            $query->recherche($request->search);
        }

        if ($request->filled('categorie_id')) {
            $query->parCategorie($request->categorie_id);
        }

        if ($request->filled('pays')) {
            $query->parPays($request->pays);
        }

        if ($request->filled('langue')) {
            $query->parLangue($request->langue);
        }

        if ($request->filled('influence')) {
            $query->parInfluence($request->influence);
        }

        if ($request->filled('tags')) {
            $tags = is_array($request->tags) ? $request->tags : [$request->tags];
            $query->avecTags($tags);
        }

        // Pagination
        $investors = $query->paginate($perPage)->appends($request->query());

        // Données pour les filtres
        $categories = CategorieInvestisseur::actifs()->ordered()->get();
        $pays = Investor::distinct()->pluck('pays')->filter()->sort()->values();
        $organisations = Organisation::actifs()->orderBy('raison_sociale')->get();
        $langues = ['Français', 'Anglais', 'Arabe'];
        $niveauxInfluence = ['Faible', 'Moyen', 'Élevé', 'Critique'];

        // Statistiques rapides
        $stats = [
            'total' => Investor::count(),
            'nouveau_mois' => Investor::whereMonth('created_at', now()->month)->count(),
            'actifs_90j' => Investor::actifsDepuis(90)->count(),
        ];

        return view('investors.index', compact(
            'investors', 'view', 'perPage', 'categories', 'pays',
            'langues', 'niveauxInfluence', 'stats', 'organisations'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = CategorieInvestisseur::actifs()->ordered()->get();
        $organisations = Organisation::actifs()->orderBy('raison_sociale')->get();
        $pays = $this->getPaysList();
        $langues = ['Français', 'Anglais', 'Arabe'];
        $niveauxInfluence = ['Faible', 'Moyen', 'Élevé', 'Critique'];

        return view('investors.create', compact(
            'categories', 'organisations', 'pays', 'langues', 'niveauxInfluence'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvestorRequest $request)
    {
        try {
            DB::beginTransaction();

            // Créer l'investisseur
            $investor = Investor::create($request->validated());

            // Créer l'adresse email unique
            $this->createUniqueEmailAddress($investor);

            // Synchroniser les organisations
            $this->syncOrganisations($investor, $request->input('organisations', []));

            DB::commit();

            return redirect()
                ->route('investors.show', $investor)
                ->with('success', 'L\'investisseur a été créé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création : ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Investor $investor)
    {
        $investor->load([
            'categorie',
            'organisations' => function($query) {
                $query->withTimestamps();
            },
            'interactions' => function($query) {
                $query->with('user')->latest('date_interaction')->take(10);
            },
            'commentaires' => function($query) {
                $query->with('user')->latest();
            },
            'emailAddress'
        ]);

        // Statistiques de l'investisseur
        $stats = [
            'interactions_total' => $investor->interactions()->count(),
            'interactions_mois' => $investor->interactions()
                ->whereMonth('date_interaction', now()->month)->count(),
            'commentaires_total' => $investor->commentaires()->count(),
            'score_engagement' => $investor->score_engagement,
        ];

        $investor->tags = is_string($investor->tags) ? explode(',', $investor->tags) : $investor->tags;
        // remove " from tags and [ also
        $investor->tags = array_map(function($tag) {
            return trim($tag, '"[]');
        }, $investor->tags);

        return view('investors.show', compact('investor', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Investor $investor)
    {
        $investor->load(['organisations', 'categorie']);

        $categories = CategorieInvestisseur::actifs()->ordered()->get();
        $organisations = Organisation::actifs()->orderBy('raison_sociale')->get();
        $pays = $this->getPaysList();
        $langues = ['Français', 'Anglais', 'Arabe'];
        $niveauxInfluence = ['Faible', 'Moyen', 'Élevé', 'Critique'];

        return view('investors.edit', compact(
            'investor', 'categories', 'organisations', 'pays', 'langues', 'niveauxInfluence'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvestorRequest $request, Investor $investor)
    {
        try {
            DB::beginTransaction();

            // Mettre à jour l'investisseur
            $investor->update($request->validated());

            // Synchroniser les organisations
            $this->syncOrganisations($investor, $request->input('organisations', []));

            DB::commit();

            return redirect()
                ->route('investors.index')
                ->with('success', 'L\'investisseur a été mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Investor $investor)
    {
        try {
            $nom = $investor->nom_complet;

            // Supprimer l'investisseur (les relations seront supprimées en cascade)
            $investor->delete();

            return redirect()
                ->route('investors.index')
                ->with('success', "L'investisseur {$nom} a été supprimé avec succès.");

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Une erreur est survenue lors de la suppression : ' . $e->getMessage());
        }
    }



    /**
     * Créer une adresse email unique pour l'investisseur.
     */
    private function createUniqueEmailAddress(Investor $investor): void
    {
        $identifier = str_pad($investor->id, 4, '0', STR_PAD_LEFT);

        InvestorEmailAddress::create([
            'investor_id' => $investor->id,
            'unique_email' => "investor-{$identifier}@crm.ir-boa.com",
            'identifier' => $identifier,
            'is_active' => true
        ]);
    }

    /**
     * Synchroniser les organisations de l'investisseur.
     */
    private function syncOrganisations(Investor $investor, array $organisations): void
    {
        $syncData = [];

        foreach ($organisations as $org) {
            if (!empty($org['organisation_id'])) {
                $syncData[$org['organisation_id']] = [
                    'poste' => $org['poste'] ?? null,
                    'date_debut' => $org['date_debut'] ?? null,
                    'date_fin' => $org['date_fin'] ?? null,
                    'actuel' => (bool) ($org['actuel'] ?? true),
                    'notes' => $org['notes'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        $investor->organisations()->sync($syncData);
    }

    /**
     * Obtenir la liste des pays.
     */
    private function getPaysList(): array
    {
        return [
            'France', 'Maroc', 'États-Unis', 'Royaume-Uni', 'Allemagne',
            'Italie', 'Espagne', 'Suisse', 'Belgique', 'Canada',
            'Singapour', 'Japon', 'Chine', 'Inde', 'Brésil',
            'Afrique du Sud', 'Égypte', 'Tunisie', 'Algérie', 'Sénégal',
            'Côte d\'Ivoire', 'Ghana', 'Nigeria', 'Kenya', 'Émirats arabes unis'
        ];
    }



// Ajouter ces méthodes dans InvestorController

    /**
     * Export investor data as PDF.
     */
    public function exportPdf(Investor $investor)
    {
        $investor->load([
            'categorie',
            'organisations', // Retirez '.pivot', les données pivot seront déjà accessibles
            'interactions' => function ($query) {
                $query->with('user')->orderBy('date_interaction', 'desc');
            },
            'commentaires' => function ($query) {
                $query->with('user')->where('prive', false)->orderBy('created_at', 'desc');
            },
            'emailAddress'
        ]);
        //tags string to array and remove quotes and brackets
        $investor->tags = is_string($investor->tags) ? explode(',', $investor->tags) : $investor->tags;
        $investor->tags = array_map(function($tag) {
            return trim($tag, '"[]');
        }, $investor->tags);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('investors.export-pdf', compact('investor'));

        $filename = 'investisseur_' . \Str::slug($investor->nom_complet) . '_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Download interaction attachment.
     */
    public function downloadAttachment(Interaction $interaction)
    {
        if (!$interaction->piece_jointe || !Storage::disk('private')->exists($interaction->piece_jointe)) {
            abort(404, 'Fichier introuvable');
        }

        $filename = basename($interaction->piece_jointe);
        return Storage::disk('private')->download($interaction->piece_jointe, $filename);
    }

    /**
     * Send email to investor.
     */
    public function sendEmail(Request $request, Investor $investor)
    {
        $validated = $request->validate([
            'objet' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'pieces_jointes' => ['nullable', 'array'],
            'pieces_jointes.*' => ['file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
        ]);

        try {
            // TODO: Implémenter l'envoi d'email via le service configuré
            // Pour l'instant, on simule l'envoi et on enregistre l'interaction

            $interaction = Interaction::create([
                'investor_id' => $investor->id,
                'user_id' => auth()->id(),
                'type' => 'Email envoyé',
                'date_interaction' => now(),
                'description' => "Objet: {$validated['objet']}\n\n{$validated['message']}",
                'metadata' => json_encode([
                    'objet' => $validated['objet'],
                    'statut' => 'envoyé',
                    'destinataire' => $investor->email
                ])
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email envoyé avec succès.',
                'interaction_id' => $interaction->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de l\'email : ' . $e->getMessage()
            ], 500);
        }
    }
}
