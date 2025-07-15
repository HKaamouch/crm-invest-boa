<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Interaction;
use App\Models\Investor;
use Illuminate\Http\Request;

class InteractionController extends Controller
{
    /**
     * Store a new interaction for an investor.
     */
    public function store(Request $request, Investor $investor)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:Email,Appel,Réunion,Email envoyé,Email reçu,Autre'],
            'date_interaction' => ['required', 'date'],
            'description' => ['required', 'string', 'max:2000'],
            'piece_jointe' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
        ], [
            'type.required' => 'Le type d\'interaction est obligatoire.',
            'type.in' => 'Le type d\'interaction sélectionné n\'est pas valide.',
            'date_interaction.required' => 'La date est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'description.max' => 'La description ne peut pas dépasser 2000 caractères.',
            'piece_jointe.mimes' => 'Le fichier doit être au format: PDF, Word, JPEG, PNG.',
            'piece_jointe.max' => 'Le fichier ne peut pas dépasser 10 Mo.',
        ]);

        try {
            $data = $validated;
            $data['investor_id'] = $investor->id;
            $data['user_id'] = auth()->id();

            // Gérer l'upload du fichier
            if ($request->hasFile('piece_jointe')) {
                $file = $request->file('piece_jointe');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('interactions', $filename, 'private');
                $data['piece_jointe'] = $path;
            }

            $interaction = Interaction::create($data);

            // Mettre à jour la dernière interaction de l'investisseur
            $investor->update(['derniere_interaction' => $data['date_interaction']]);

            // Charger les relations pour la réponse
            $interaction->load('user');

            return response()->json([
                'success' => true,
                'message' => 'Interaction ajoutée avec succès.',
                'interaction' => [
                    'id' => $interaction->id,
                    'type' => $interaction->type,
                    'date_interaction' => $interaction->date_interaction->format('d/m/Y'),
                    'date_interaction_formatee' => $interaction->date_interaction_formatee,
                    'description' => $interaction->description,
                    'description_courte' => $interaction->description_courte,
                    'piece_jointe' => $interaction->piece_jointe,
                    'user_name' => $interaction->user->nom_complet ?? $interaction->user->name,
                    'created_at' => $interaction->created_at->toISOString(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'ajout de l\'interaction : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get interactions for an investor (for AJAX refresh/pagination).
     */
    public function index(Request $request, Investor $investor)
    {
        $perPage = $request->get('per_page', 10);

        $interactions = $investor->interactions()
            ->with('user')
            ->orderBy('date_interaction', 'desc')
            ->paginate($perPage);

        $formattedInteractions = $interactions->getCollection()->map(function($interaction) {
            return [
                'id' => $interaction->id,
                'type' => $interaction->type,
                'date_interaction' => $interaction->date_interaction->format('d/m/Y'),
                'date_interaction_formatee' => $interaction->date_interaction_formatee,
                'description' => $interaction->description,
                'description_courte' => $interaction->description_courte,
                'piece_jointe' => $interaction->piece_jointe,
                'user_name' => $interaction->user->nom_complet ?? $interaction->user->name,
                'created_at' => $interaction->created_at->toISOString(),
                'can_edit' => auth()->user()->hasRole('Administrateur') ||
                    auth()->user()->hasRole('Éditeur'),
            ];
        });

        return response()->json([
            'success' => true,
            'interactions' => $formattedInteractions,
            'pagination' => [
                'current_page' => $interactions->currentPage(),
                'last_page' => $interactions->lastPage(),
                'per_page' => $interactions->perPage(),
                'total' => $interactions->total(),
            ]
        ]);
    }
}
