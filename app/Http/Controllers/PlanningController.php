<?php

namespace App\Http\Controllers;

use App\Models\Interaction;
use App\Models\Investor;
use App\Models\CategorieInvestisseur;
use Illuminate\Http\Request;

class PlanningController extends Controller
{
    public function index()
    {
        $investors = Investor::all();
        $categories = CategorieInvestisseur::all();
        
        return view('planning.index', compact('investors', 'categories'));
    }

    public function getEvents(Request $request)
    {
        $query = Interaction::with(['investor.categorie', 'user']);

        // Filtres
        if ($request->investor_id) {
            $query->where('investor_id', $request->investor_id);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }
        
        if ($request->statut) {
            $query->where('statut', $request->statut);
        }
        
        if ($request->category_id) {
            $query->whereHas('investor', function($q) use ($request) {
                $q->where('categorie_id', $request->category_id);
            });
        }
        
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('type', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('investor', function($subQ) use ($search) {
                      $subQ->where('nom_complet', 'like', "%{$search}%")
                           ->orWhere('prenom', 'like', "%{$search}%")
                           ->orWhere('nom', 'like', "%{$search}%");
                  });
            });
        }

        $interactions = $query->get();

        $events = $interactions->map(function ($interaction) {
            $investorName = $interaction->investor ? $interaction->investor->nom_complet : 'Investisseur inconnu';
            return [
                'id' => $interaction->id,
                'title' => $interaction->type . ' - ' . $investorName,
                'start' => $interaction->date_interaction->format('Y-m-d'),
                'backgroundColor' => $this->getColorByType($interaction->type),
                'borderColor' => $this->getColorByType($interaction->type),
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'type' => $interaction->type,
                    'investor' => $investorName,
                    'description' => $interaction->description ?? 'Aucune description',
                ]
            ];
        });

        return response()->json($events);
    }

    private function getColorByType($type)
    {
        $colors = [
            'Email' => '#3B82F6',
            'Email envoyé' => '#3B82F6',
            'Réunion' => '#10B981',
            'Appel' => '#F59E0B',
        ];

        return $colors[$type] ?? '#6B7280';
    }
}