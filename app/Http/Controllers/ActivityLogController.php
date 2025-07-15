<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;

class ActivityLogController extends Controller
{
    /**
     * Affiche la liste de toutes les activités
     */
    public function index(Request $request)
    {
        // Vérification des permissions
        if (!auth()->user()->can('view_logs')) {
            abort(403);
        }

        // Récupérer les paramètres de filtrage et pagination
        $search = $request->get('search');
        $userFilter = $request->get('user');
        $actionFilter = $request->get('action');
        $entityFilter = $request->get('entity');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $perPage = $request->get('per_page', 25);

        // Construire la requête avec les filtres
        $query = Activity::with('causer')
            ->orderBy('created_at', 'desc');

        // Appliquer les filtres
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('subject_type', 'like', "%{$search}%");
            });
        }

        if ($userFilter) {
            $query->where('causer_id', $userFilter)->where('causer_type', User::class);
        }

        if ($actionFilter) {
            $query->where('description', $actionFilter);
        }

        if ($entityFilter) {
            // Correction du filtre par type d'entité - utiliser l'égalité exacte
            $query->where('subject_type', $entityFilter);
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Pagination des résultats
        $activities = $query->paginate($perPage)->withQueryString();

        // Données pour les filtres
        $users = User::orderBy('nom_complet')->get();
        $actions = Activity::select('description')->distinct()->pluck('description');
        $entities = Activity::select('subject_type')->distinct()->pluck('subject_type');

        return view('activity.index', compact(
            'activities',
            'users',
            'actions',
            'entities',
            'perPage'
        ));
    }

    /**
     * Affiche les détails d'une activité spécifique
     */
    public function show(Activity $activity)
    {
        // Vérification des permissions
        if (!auth()->user()->can('view_logs')) {
            abort(403);
        }

        return view('activity.show', compact('activity'));
    }
}
