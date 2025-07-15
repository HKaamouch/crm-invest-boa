<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;
use Carbon\Carbon;

class ActivityController extends Controller
{
    /**
     * Récupère les activités d'un utilisateur avec pagination
     */
    public function getUserActivities(Request $request, User $user)
    {
        // Vérifier l'autorisation

        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 15);

        $activities = Activity::causedBy($user)
            ->orderBy('created_at', 'desc')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        // Formater les données pour la réponse JSON
        $formattedActivities = $activities->map(function ($activity) {
            return [
                'id' => $activity->id,
                'description' => $activity->description,
                'subject_type' => $activity->subject_type,
                'subject_id' => $activity->subject_id,
                'causer_type' => $activity->causer_type,
                'causer_id' => $activity->causer_id,
                'properties' => $activity->properties,
                'created_at' => $activity->created_at,
                'created_at_human' => $activity->created_at->diffForHumans()
            ];
        });

        return response()->json([
            'activities' => $formattedActivities,
            'page' => $page,
            'hasMore' => $activities->count() === $perPage
        ]);
    }

    /**
     * Récupère les détails d'une activité spécifique
     */
    public function getActivityDetails(Activity $activity)
    {
        // Vérifier l'autorisation


        return response()->json([
            'id' => $activity->id,
            'description' => $activity->description,
            'subject_type' => $activity->subject_type,
            'subject_id' => $activity->subject_id,
            'causer_type' => $activity->causer_type,
            'causer_id' => $activity->causer_id,
            'properties' => $activity->properties,
            'created_at' => $activity->created_at,
            'created_at_human' => $activity->created_at->diffForHumans()
        ]);
    }

    /**
     * Récupère toutes les activités avec filtres et pagination
     */
    public function getAllActivities(Request $request)
    {
        // Vérifier l'autorisation
        if (!auth()->user()->can('view_logs')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 15);
        $userId = $request->get('user_id');
        $subjectType = $request->get('subject_type');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = Activity::with('causer')->orderBy('created_at', 'desc');

        // Appliquer les filtres
        if ($userId) {
            $query->where('causer_id', $userId)->where('causer_type', User::class);
        }

        if ($subjectType) {
            $query->where('subject_type', $subjectType);
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $activities = $query->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        $total = $query->count();

        // Formater les données
        $formattedActivities = $activities->map(function ($activity) {
            return [
                'id' => $activity->id,
                'description' => $activity->description,
                'subject_type' => $activity->subject_type,
                'subject_id' => $activity->subject_id,
                'causer' => $activity->causer ? [
                    'id' => $activity->causer->id,
                    'name' => $activity->causer->name,
                ] : null,
                'properties' => $activity->properties,
                'created_at' => $activity->created_at,
                'created_at_human' => $activity->created_at->diffForHumans()
            ];
        });

        return response()->json([
            'activities' => $formattedActivities,
            'page' => $page,
            'total' => $total,
            'hasMore' => $activities->count() === $perPage
        ]);
    }
}
