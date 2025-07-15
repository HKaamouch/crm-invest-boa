<?php

namespace App\Http\Controllers;

use App\Models\Interaction;
use App\Models\Investor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InteractionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Paramètres de pagination
        $perPage = (int) $request->get('per_page', 25);
        $perPage = in_array($perPage, [25, 50, 100]) ? $perPage : 25;

        // Construction de la query avec filtres
        $query = Interaction::with(['investor', 'user'])
            ->orderBy('date_interaction', 'desc');

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhereHas('investor', function($investorQuery) use ($search) {
                        $investorQuery->where('nom', 'like', "%{$search}%")
                            ->orWhere('prenom', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('investisseur')) {
            $query->where('investor_id', $request->investisseur);
        }

        if ($request->filled('utilisateur')) {
            $query->where('user_id', $request->utilisateur);
        }

        if ($request->filled('date_debut')) {
            $query->where('date_interaction', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->where('date_interaction', '<=', $request->date_fin);
        }

        // Pagination
        $interactions = $query->paginate($perPage)->appends($request->query());

        // Données pour les filtres
        $types = Interaction::distinct('type')->pluck('type')->sort()->values();
        $investisseurs = Investor::select('id', 'nom', 'prenom', 'email')->orderBy('nom')->get();
        $utilisateurs = User::select('id', 'nom_complet')->orderBy('nom_complet')->get();

        // Statistiques
        $stats = [
            'total' => Interaction::count(),
            'ce_mois' => Interaction::whereMonth('date_interaction', now()->month)
                ->whereYear('date_interaction', now()->year)
                ->count(),
            'cette_semaine' => Interaction::whereBetween('date_interaction', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'aujourd_hui' => Interaction::whereDate('date_interaction', now())->count(),
        ];

        return view('interactions.index', compact(
            'interactions', 'perPage', 'types', 'investisseurs', 'utilisateurs', 'stats'
        ));
    }

    /**
     * Display the specified resource.
     */
    public function show(Interaction $interaction)
    {
        $interaction->load(['investor', 'user']);

        return view('interactions.show', compact('interaction'));
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
}
