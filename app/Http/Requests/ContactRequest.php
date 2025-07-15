<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $contactId = $this->route('contact')?->id;

        return [
            'prenom' => ['required', 'string', 'max:255'],
            'nom' => ['required', 'string', 'max:255'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:contacts,email,' . $contactId
            ],
            'telephone' => ['nullable', 'string', 'max:20'],
            'mobile' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'actif' => ['boolean'],

            // Organisations
            'organisations' => ['nullable', 'array'],
            'organisations.*.organisation_id' => ['nullable', 'exists:organisations,id'],
            'organisations.*.poste' => ['nullable', 'string', 'max:255'],
            'organisations.*.date_debut' => ['nullable', 'date'],
            'organisations.*.date_fin' => ['nullable', 'date', 'after_or_equal:organisations.*.date_debut'],
            'organisations.*.actuel' => ['boolean'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'prenom.required' => 'Le prénom est obligatoire.',
            'nom.required' => 'Le nom est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'telephone.max' => 'Le numéro de téléphone ne peut pas dépasser 20 caractères.',
            'mobile.max' => 'Le numéro de mobile ne peut pas dépasser 20 caractères.',
            'notes.max' => 'Les notes ne peuvent pas dépasser 2000 caractères.',

            'organisations.*.organisation_id.exists' => 'L\'organisation sélectionnée n\'existe pas.',
            'organisations.*.poste.max' => 'Le poste ne peut pas dépasser 255 caractères.',
            'organisations.*.date_debut.date' => 'La date de début doit être une date valide.',
            'organisations.*.date_fin.date' => 'La date de fin doit être une date valide.',
            'organisations.*.date_fin.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Nettoyer les données d'organisations
        if ($this->has('organisations')) {
            $organisations = [];
            foreach ($this->input('organisations', []) as $org) {
                if (!empty($org['organisation_id'])) {
                    $organisations[] = [
                        'organisation_id' => $org['organisation_id'],
                        'poste' => trim($org['poste'] ?? '') ?: null,
                        'date_debut' => $org['date_debut'] ?: null,
                        'date_fin' => $org['date_fin'] ?: null,
                        'actuel' => (bool) ($org['actuel'] ?? true),
                    ];
                }
            }
            $this->merge(['organisations' => $organisations]);
        }

        // Normaliser les booléens
        $this->merge([
            'actif' => $this->boolean('actif', true),
        ]);

        // Nettoyer les champs texte
        $this->merge([
            'prenom' => trim($this->input('prenom', '')),
            'nom' => trim($this->input('nom', '')),
            'email' => trim($this->input('email', '')) ?: null,
            'telephone' => trim($this->input('telephone', '')) ?: null,
            'mobile' => trim($this->input('mobile', '')) ?: null,
            'notes' => trim($this->input('notes', '')) ?: null,
        ]);
    }
}
