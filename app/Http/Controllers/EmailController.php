<?php

namespace App\Http\Controllers;

use App\Models\Interaction;
use App\Models\Investor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmailController extends Controller
{
    /**
     * Display a listing of all emails.
     */
    public function index(Request $request)
    {
        // Paramètres de pagination
        $perPage = (int) $request->get('per_page', 25);
        $perPage = in_array($perPage, [6, 50, 100]) ? $perPage : 25;

        // Construction de la query des emails (interactions de type email)
        $query = Interaction::with(['investor', 'user'])
            ->whereIn('type', ['Email envoyé', 'Email reçu'])
            ->orderBy('date_interaction', 'desc');

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhereHas('investor', function($investorQuery) use ($search) {
                        $investorQuery->where('nom', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereJsonContains('metadata->objet', $search);
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('statut')) {
            $query->whereJsonContains('metadata->statut', $request->statut);
        }

        if ($request->filled('expediteur')) {
            $query->where('user_id', $request->expediteur);
        }

        if ($request->filled('destinataire')) {
            $query->where('investor_id', $request->destinataire);
        }

        if ($request->filled('date_debut')) {
            $query->where('date_interaction', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->where('date_interaction', '<=', $request->date_fin);
        }

        // Pagination
        $emails = $query->paginate($perPage)->appends($request->query());

        // Statistiques
        $stats = $this->getEmailStats();

        // Données pour les filtres
        $expediteurs = User::select('id', 'nom_complet')->orderBy('nom_complet')->get();
        $destinataires = Investor::select('id', 'nom', 'email')->orderBy('nom')->get();

        return view('emails.index', compact(
            'emails', 'perPage', 'stats', 'expediteurs', 'destinataires'
        ));
    }

    /**
     * Show the form for composing a new email.
     */
    public function compose()
    {
        $investors = Investor::select('id', 'nom', 'email', 'pays')
            ->orderBy('nom')
            ->get();

        return view('emails.compose', compact('investors'));
    }

    /**
     * Send a new email to multiple investors.
     */
    public function send(Request $request)
    {

        $validated = $request->validate([
             'to' => ['required', 'array', 'min:1'],
             'to.*' => ['exists:investors,id'],
             'cc' => ['nullable', 'array'],
             'cc.*' => ['exists:investors,id'],
             'cci' => ['nullable', 'array'],
             'cci.*' => ['exists:investors,id'],
             'objet' => ['required', 'string', 'max:255'],
             'message' => ['required', 'string'],
             'pieces_jointes' => ['nullable', 'array'],
             'pieces_jointes.*' => ['file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
        ], [
            'to.required' => 'Au moins un destinataire est requis.',
            'to.*.exists' => 'Un destinataire sélectionné n\'existe pas.',
            'objet.required' => 'L\'objet est obligatoire.',
            'message.required' => 'Le message est obligatoire.',
        ]);


        try {
            DB::beginTransaction();

            $sentEmails = [];
            $failedEmails = [];

            // Préparer les métadonnées communes
            $baseMetadata = [
                'objet' => $validated['objet'],
                'statut' => 'envoyé',
                'envoi_groupe' => true,
                'groupe_id' => uniqid(),
                'timestamp' => now()->toISOString()
            ];

            // Collecter tous les to (TO + CC + BCC)
            $allDestinataireIds = collect($validated['to'])
                ->merge($validated['cc'] ?? [])
                ->merge($validated['cci'] ?? [])
                ->unique()
                ->values();

            $destinataires = Investor::whereIn('id', $allDestinataireIds)->get();

            foreach ($destinataires as $investor) {
                try {
                    // Déterminer le type de destinataire
                    $typeDestinataire = 'to';
                    if (in_array($investor->id, $validated['cc'] ?? [])) {
                        $typeDestinataire = 'cc';
                    } elseif (in_array($investor->id, $validated['cci'] ?? [])) {
                        $typeDestinataire = 'bcc';
                    }

                    $metadata = array_merge($baseMetadata, [
                        'destinataire' => $investor->email,
                        'destinataire_nom' => $investor->nom,
                        'type_destinataire' => $typeDestinataire,
                        'nb_destinataires_total' => count($allDestinataireIds)
                    ]);

                    $interaction = Interaction::create([
                        'investor_id' => $investor->id,
                        'user_id' => auth()->id(),
                        'type' => 'Email envoyé',
                        'date_interaction' => now(),
                        'description' => "Objet: {$validated['objet']}\n\n{$validated['message']}",
                        'metadata' => $metadata
                    ]);

                    // Mettre à jour la dernière interaction
                    $investor->update(['derniere_interaction' => now()]);

                    $sentEmails[] = $investor->email;

                } catch (\Exception $e) {
                    $failedEmails[] = [
                        'email' => $investor->email,
                        'error' => $e->getMessage()
                    ];
                }
            }

            DB::commit();

            $message = count($sentEmails) . ' email(s) envoyé(s) avec succès.';
            if (count($failedEmails) > 0) {
                $message .= ' ' . count($failedEmails) . ' email(s) ont échoué.';
            }
            return response()->json([
                'success' => true,
                'message' => $message,
                'sent_count' => count($sentEmails),
                'failed_count' => count($failedEmails),
                'failed_emails' => $failedEmails
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi des emails : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show a specific email interaction.
     */
    public function show(Interaction $email)
    {
        // Vérifier que c'est bien un email
        if (!in_array($email->type, ['Email envoyé', 'Email reçu'])) {
            abort(404, 'Email non trouvé');
        }

        $email->load(['investor', 'user']);

        return view('emails.show', compact('email'));
    }

    /**
     * Search investors for autocomplete.
     */
    public function searchInvestors(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $investors = Investor::where(function($q) use ($query) {
            $q->where('nom', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%");
        })
            ->select('id', 'nom', 'email', 'pays', 'fonction')
            ->limit(10)
            ->get()
            ->map(function($investor) {
                return [
                    'id' => $investor->id,
                    'text' => $investor->nom . ' (' . $investor->email . ')',
                    'nom' => $investor->nom,
                    'email' => $investor->email,
                    'pays' => $investor->pays,
                    'fonction' => $investor->fonction,
                    'avatar_url' => $investor->avatar_url
                ];
            });

        return response()->json($investors);
    }

    /**
     * Get email statistics.
     */
    private function getEmailStats(): array
    {
        $baseQuery = Interaction::whereIn('type', ['Email envoyé', 'Email reçu']);

        return [
            'total' => $baseQuery->count(),
            'envoyes' => Interaction::where('type', 'Email envoyé')->count(),
            'recus' => Interaction::where('type', 'Email reçu')->count(),
            'ce_mois' => $baseQuery->whereMonth('date_interaction', now()->month)
                ->whereYear('date_interaction', now()->year)
                ->count(),
            'cette_semaine' => $baseQuery->whereBetween('date_interaction', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'aujourd_hui' => $baseQuery->whereDate('date_interaction', now())->count(),
            'expediteurs_actifs' => $baseQuery->distinct('user_id')->count('user_id'),
            'destinataires_actifs' => $baseQuery->distinct('investor_id')->count('investor_id')
        ];
    }
}
