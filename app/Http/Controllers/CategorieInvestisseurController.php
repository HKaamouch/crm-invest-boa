<?php

namespace App\Http\Controllers;

use App\Models\CategorieInvestisseur;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategorieInvestisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = CategorieInvestisseur::withCount('investors')
            ->ordered()
            ->get();

        $stats = [
            'total' => $categories->count(),
            'actives' => $categories->where('actif', true)->count(),
            'avec_investors' => $categories->where('investors_count', '>', 0)->count(),
        ];

        return view('referentiels.categories.index', compact('categories', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $couleurs = $this->getCouleursList();
        $prochainOrdre = CategorieInvestisseur::max('ordre_affichage') + 1;

        return view('referentiels.categories.create', compact('couleurs', 'prochainOrdre'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:categories_investisseurs'],
            'description' => ['nullable', 'string'],
            'couleur_hexa' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'ordre_affichage' => ['required', 'integer', 'min:0'],
            'actif' => ['boolean'],
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.unique' => 'Cette catégorie existe déjà.',
            'couleur_hexa.required' => 'La couleur est obligatoire.',
            'couleur_hexa.regex' => 'La couleur doit être au format hexadécimal (#RRGGBB).',
            'ordre_affichage.required' => 'L\'ordre d\'affichage est obligatoire.',
        ]);

        // Assurer que actif est un boolean
        $validated['actif'] = $request->boolean('actif', true);

        try {
            $categorie = CategorieInvestisseur::create($validated);

            return redirect()
                ->route('categories-investisseurs.index')
                ->with('success', 'La catégorie a été créée avec succès.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création : ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CategorieInvestisseur $categorieInvestisseur)
    {
        $couleurs = $this->getCouleursList();

        return view('referentiels.categories.edit', compact('categorieInvestisseur', 'couleurs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CategorieInvestisseur $categoriesInvestisseur)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', Rule::unique('categories_investisseurs')->ignore($categoriesInvestisseur)],
            'description' => ['nullable', 'string'],
            'couleur_hexa' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'ordre_affichage' => ['required', 'integer', 'min:0'],
            'actif' => ['boolean'],
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.unique' => 'Cette catégorie existe déjà.',
            'couleur_hexa.required' => 'La couleur est obligatoire.',
            'couleur_hexa.regex' => 'La couleur doit être au format hexadécimal (#RRGGBB).',
            'ordre_affichage.required' => 'L\'ordre d\'affichage est obligatoire.',
        ]);

        // Assurer que actif est un boolean
        $validated['actif'] = $request->boolean('actif', true);

        try {
            $categoriesInvestisseur->update($validated);

            return redirect()
                ->route('categories-investisseurs.index')
                ->with('success', 'La catégorie a été mise à jour avec succès.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategorieInvestisseur $categoriesInvestisseur)
    {
        try {
            // Vérifier s'il y a des investisseurs dans cette catégorie
            if ($categoriesInvestisseur->investors()->count() > 0) {
                return back()->with('error',
                    'Impossible de supprimer cette catégorie car elle contient des investisseurs.
                     Veuillez d\'abord déplacer ces investisseurs vers une autre catégorie.'
                );
            }

            $nom = $categoriesInvestisseur->nom;
            $categoriesInvestisseur->delete();

            return redirect()
                ->route('categories-investisseurs.index')
                ->with('success', "La catégorie {$nom} a été supprimée avec succès.");

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Une erreur est survenue lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Réorganiser l'ordre des catégories via AJAX.
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'categories' => ['required', 'array'],
            'categories.*.id' => ['required', 'exists:categories_investisseurs,id'],
            'categories.*.ordre' => ['required', 'integer', 'min:0'],
        ]);

        try {
            foreach ($validated['categories'] as $categorieData) {
                CategorieInvestisseur::where('id', $categorieData['id'])
                    ->update(['ordre_affichage' => $categorieData['ordre']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'L\'ordre des catégories a été mis à jour.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la réorganisation : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir la liste des couleurs prédéfinies.
     */
    private function getCouleursList(): array
    {
        return [
            '#3B82F6' => 'Bleu',
            '#10B981' => 'Vert',
            '#F59E0B' => 'Ambre',
            '#EF4444' => 'Rouge',
            '#8B5CF6' => 'Violet',
            '#6366F1' => 'Indigo',
            '#EC4899' => 'Rose',
            '#14B8A6' => 'Teal',
            '#F97316' => 'Orange',
            '#84CC16' => 'Lime',
            '#6B7280' => 'Gris',
            '#1F2937' => 'Gris foncé',
        ];
    }
}
