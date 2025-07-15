<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
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
        $query = Contact::withCount(['organisations'])
            ->orderBy('nom')
            ->orderBy('prenom');

        // Filtres
        if ($request->filled('search')) {
            $query->recherche($request->search);
        }

        if ($request->filled('actif')) {
            if ($request->actif === '1') {
                $query->actifs();
            } else {
                $query->where('actif', false);
            }
        }

        // Pagination
        $contacts = $query->paginate($perPage)->appends($request->query());

        // Statistiques
        $stats = [
            'total' => Contact::count(),
            'actifs' => Contact::where('actif', true)->count(),
            'avec_organisations' => Contact::has('organisations')->count(),
            'nouveau_mois' => Contact::whereMonth('created_at', now()->month)->count(),
        ];

        return view('contacts.index', compact(
            'contacts', 'view', 'perPage', 'stats'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $organisations = Organisation::actifs()->orderBy('raison_sociale')->get();

        return view('contacts.create', compact('organisations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactRequest $request)
    {
        try {
            DB::beginTransaction();

            $contact = Contact::create($request->validated());

            // Synchroniser les organisations
            $this->syncOrganisations($contact, $request->input('organisations', []));

            DB::commit();

            return redirect()
                ->route('contacts.show', $contact)
                ->with('success', 'Le contact a été créé avec succès.');

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
    public function show(Contact $contact)
    {
        $contact->load([
            'organisations' => function($query) {
                $query->withTimestamps()->orderBy('contact_organisations.created_at', 'desc');
            }
        ]);

        // Statistiques du contact
        $stats = [
            'organisations_total' => $contact->organisations()->count(),
            'organisations_actuelles' => $contact->organisationsActuelles()->count(),
        ];

        return view('contacts.show', compact('contact', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        $contact->load(['organisations']);
        $organisations = Organisation::actifs()->orderBy('raison_sociale')->get();

        return view('contacts.edit', compact('contact', 'organisations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactRequest $request, Contact $contact)
    {
        try {
            DB::beginTransaction();

            $contact->update($request->validated());

            // Synchroniser les organisations
            $this->syncOrganisations($contact, $request->input('organisations', []));

            DB::commit();

            return redirect()
                ->route('contacts.show', $contact)
                ->with('success', 'Le contact a été mis à jour avec succès.');

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
    public function destroy(Contact $contact)
    {
        try {
            $nom = $contact->nom_complet;

            // Vérifier s'il y a des relations
            if ($contact->organisations()->count() > 0) {
                return back()->with('error',
                    'Impossible de supprimer ce contact car il est lié à des organisations.
                     Veuillez d\'abord supprimer ces relations.'
                );
            }

            $contact->delete();

            return redirect()
                ->route('contacts.index')
                ->with('success', "Le contact {$nom} a été supprimé avec succès.");

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Une erreur est survenue lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Synchroniser les organisations du contact.
     */
    private function syncOrganisations(Contact $contact, array $organisations): void
    {
        $syncData = [];

        foreach ($organisations as $org) {
            if (!empty($org['organisation_id'])) {
                $syncData[$org['organisation_id']] = [
                    'poste' => $org['poste'] ?? null,
                    'date_debut' => $org['date_debut'] ?? null,
                    'date_fin' => $org['date_fin'] ?? null,
                    'actuel' => (bool) ($org['actuel'] ?? true),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        $contact->organisations()->sync($syncData);
    }

    /**
     * Toggle contact status.
     */
    public function toggleStatus(Contact $contact)
    {
        $contact->update([
            'actif' => !$contact->actif
        ]);

        $status = $contact->actif ? 'activé' : 'désactivé';

        return back()->with('success', "Le contact a été {$status} avec succès.");
    }

    /**
     * Attacher une organisation à un contact.
     *
     * @param Request $request
     * @param Contact $contact
     * @return \Illuminate\Http\RedirectResponse
     */
    public function attach(Request $request, Contact $contact)
    {
        $request->validate([
            'organisation_id' => 'required|exists:organisations,id',
            'poste' => 'nullable|string|max:255',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'notes_organisation' => 'nullable|string',
        ]);

        // Vérifier si l'organisation est déjà attachée
        if ($contact->organisations()->where('organisation_id', $request->organisation_id)->exists()) {
            return back()->with('error', 'Cette organisation est déjà associée à ce contact.');
        }

        $actuel = $request->has('actuel');
        $date_fin = $actuel ? null : $request->date_fin;

        $contact->organisations()->attach($request->organisation_id, [
            'poste' => $request->poste,
            'date_debut' => $request->date_debut,
            'date_fin' => $date_fin,
            'actuel' => $actuel,
            'notes' => $request->notes_organisation,
        ]);

        return back()->with('success', 'Organisation ajoutée avec succès.');
    }

    /**
     * Détacher une organisation d'un contact.
     *
     * @param Contact $contact
     * @param Organisation $organisation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function detach(Contact $contact, Organisation $organisation)
    {
        $contact->organisations()->detach($organisation->id);

        return back()->with('success', 'Organisation détachée avec succès.');
    }
}
