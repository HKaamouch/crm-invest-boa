<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrganisationRequest;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrganisationController extends Controller
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

        // Construction de la query
        $query = Organisation::withCount(['investors', 'contacts'])
            ->orderBy('raison_sociale');

        // Filtres
        if ($request->filled('search')) {
            $query->recherche($request->search);
        }

        if ($request->filled('pays')) {
            $query->parPays($request->pays);
        }

        if ($request->filled('actif')) {
            if ($request->actif === '1') {
                $query->actifs();
            } else {
                $query->where('actif', false);
            }
        }

        // Pagination
        $organisations = $query->paginate($perPage)->appends($request->query());

        // Données pour les filtres
        $pays = Organisation::distinct()->pluck('pays')->filter()->sort()->values();

        // Statistiques
        $stats = [
            'total' => Organisation::count(),
            'actives' => Organisation::where('actif', true)->count(),
            'avec_investors' => Organisation::has('investors')->count(),
            'avec_contacts' => Organisation::has('contacts')->count(),
        ];

        return view('organisations.index', compact(
            'organisations', 'view', 'perPage', 'pays', 'stats'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pays = $this->getPaysList();

        return view('organisations.create', compact('pays'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrganisationRequest $request)
    {
        try {
            $data = $request->validated();

            // Gérer l'upload du logo
            if ($request->hasFile('logo')) {
                $data['logo_path'] = $this->handleLogoUpload($request->file('logo'));
            }

            $organisation = Organisation::create($data);

            return redirect()
                ->route('organisations.show', $organisation)
                ->with('success', 'L\'organisation a été créée avec succès.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création : ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Organisation $organisation)
    {
        $organisation->load([
            'investors' => function($query) {
                $query->withTimestamps()->orderBy('investisseur_organisations.created_at', 'desc');
            },
            'contacts' => function($query) {
                $query->withTimestamps()->orderBy('contact_organisations.created_at', 'desc');
            }
        ]);

        // Statistiques de l'organisation
        $stats = [
            'investors_total' => $organisation->investors()->count(),
            'investors_actuels' => $organisation->investorsActuels()->count(),
            'contacts_total' => $organisation->contacts()->count(),
            'contacts_actuels' => $organisation->contactsActuels()->count(),
        ];

        return view('organisations.show', compact('organisation', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organisation $organisation)
    {
        $pays = $this->getPaysList();

        return view('organisations.edit', compact('organisation', 'pays'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrganisationRequest $request, Organisation $organisation)
    {
        try {
            $data = $request->validated();

            // Gérer l'upload du logo
            if ($request->hasFile('logo')) {
                // Supprimer l'ancien logo
                if ($organisation->logo_path) {
                    Storage::disk('public')->delete('logos/' . $organisation->logo_path);
                }

                $data['logo_path'] = $this->handleLogoUpload($request->file('logo'));
            }

            $organisation->update($data);

            return redirect()
                ->route('organisations.show', $organisation)
                ->with('success', 'L\'organisation a été mise à jour avec succès.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organisation $organisation)
    {
        try {
            // Vérifier s'il y a des relations
            if ($organisation->investors()->count() > 0 || $organisation->contacts()->count() > 0) {
                return back()->with('error',
                    'Impossible de supprimer cette organisation car elle est liée à des investisseurs ou contacts.
                     Veuillez d\'abord supprimer ces relations.'
                );
            }

            $nom = $organisation->raison_sociale;

            // Supprimer le logo s'il existe
            if ($organisation->logo_path) {
                Storage::disk('public')->delete('logos/' . $organisation->logo_path);
            }

            $organisation->delete();

            return redirect()
                ->route('organisations.index')
                ->with('success', "L'organisation {$nom} a été supprimée avec succès.");

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Une erreur est survenue lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Gérer l'upload du logo.
     */
    private function handleLogoUpload($file): string
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('logos', $filename, 'public');

        return $filename;
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
}
