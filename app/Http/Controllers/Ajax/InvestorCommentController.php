<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Investor;
use App\Models\InvestisseurCommentaire;
use Illuminate\Http\Request;

class InvestorCommentController extends Controller
{
    /**
     * Store a new comment for an investor.
     */
    public function store(Request $request, Investor $investor)
    {
        $validated = $request->validate([
            'commentaire' => ['required', 'string', 'max:1000'],
            'prive' => ['boolean'],
        ], [
            'commentaire.required' => 'Le commentaire est obligatoire.',
            'commentaire.max' => 'Le commentaire ne peut pas dépasser 1000 caractères.',
        ]);

        try {
            $commentaire = InvestisseurCommentaire::create([
                'investor_id' => $investor->id,
                'user_id' => auth()->id(),
                'commentaire' => $validated['commentaire'],
                'prive' => $request->boolean('prive', false),
            ]);

            // Charger les relations pour la réponse
            $commentaire->load('user');

            return response()->json([
                'success' => true,
                'message' => 'Commentaire ajouté avec succès.',
                'commentaire' => [
                    'id' => $commentaire->id,
                    'commentaire' => $commentaire->commentaire,
                    'prive' => $commentaire->prive,
                    'date_formatee' => $commentaire->date_formatee,
                    'auteur_nom' => $commentaire->auteur_nom,
                    'type_badge' => $commentaire->type_badge,
                    'type_icone' => $commentaire->type_icone,
                    'created_at' => $commentaire->created_at->toISOString(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'ajout du commentaire : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing comment.
     */
    public function update(Request $request, InvestisseurCommentaire $comment)
    {
        // Vérifier que l'utilisateur peut modifier ce commentaire
        if ($comment->user_id !== auth()->id() && !auth()->user()->hasRole('Administrateur')) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à modifier ce commentaire.'
            ], 403);
        }

        $validated = $request->validate([
            'commentaire' => ['required', 'string', 'max:1000'],
            'prive' => ['boolean'],
        ], [
            'commentaire.required' => 'Le commentaire est obligatoire.',
            'commentaire.max' => 'Le commentaire ne peut pas dépasser 1000 caractères.',
        ]);

        try {
            $comment->update([
                'commentaire' => $validated['commentaire'],
                'prive' => $request->boolean('prive', false),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Commentaire mis à jour avec succès.',
                'commentaire' => [
                    'id' => $comment->id,
                    'commentaire' => $comment->commentaire,
                    'prive' => $comment->prive,
                    'type_badge' => $comment->type_badge,
                    'type_icone' => $comment->type_icone,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la mise à jour : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove a comment.
     */
    public function destroy(InvestisseurCommentaire $comment)
    {
        // Vérifier que l'utilisateur peut supprimer ce commentaire
        if ($comment->user_id !== auth()->id() && !auth()->user()->hasRole('Administrateur')) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à supprimer ce commentaire.'
            ], 403);
        }

        try {
            $comment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Commentaire supprimé avec succès.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la suppression : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get comments for an investor (for AJAX refresh).
     */
    public function index(Investor $investor)
    {
        $commentaires = $investor->commentaires()
            ->with('user')
            ->latest()
            ->get()
            ->map(function($commentaire) {
                return [
                    'id' => $commentaire->id,
                    'commentaire' => $commentaire->commentaire,
                    'prive' => $commentaire->prive,
                    'date_formatee' => $commentaire->date_formatee,
                    'auteur_nom' => $commentaire->auteur_nom,
                    'type_badge' => $commentaire->type_badge,
                    'type_icone' => $commentaire->type_icone,
                    'can_edit' => $commentaire->user_id === auth()->id() || auth()->user()->hasRole('Administrateur'),
                    'created_at' => $commentaire->created_at->toISOString(),
                ];
            });

        return response()->json([
            'success' => true,
            'commentaires' => $commentaires,
            'total' => $commentaires->count()
        ]);
    }
}
