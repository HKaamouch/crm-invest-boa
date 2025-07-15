<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInvestorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('investor'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $investor = $this->route('investor');

        return [
            'civilite' => ['nullable', Rule::in(['M', 'Mme', 'Dr', 'Prof'])],
            'prenom' => ['required', 'string', 'max:255'],
            'nom' => ['required', 'string', 'max:255'],
            'categorie_id' => ['required', 'exists:categories_investisseurs,id'],
            'pays' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('investors')->ignore($investor)],
            'telephone' => ['nullable', 'string', 'max:50'],
            'mobile' => ['nullable', 'string', 'max:50'],
            'fonction' => ['nullable', 'string', 'max:255'],
            'langue_preferee' => ['required', Rule::in(['Français', 'Anglais', 'Arabe'])],
            'niveau_influence' => ['required', Rule::in(['Faible', 'Moyen', 'Élevé', 'Critique'])],
            'remarques' => ['nullable', 'string'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],

            // Organisations (nouvelles relations)
            'organisations' => ['nullable', 'array'],
            'organisations.*.organisation_id' => ['required_with:organisations', 'exists:organisations,id'],
            'organisations.*.poste' => ['nullable', 'string', 'max:255'],
            'organisations.*.date_debut' => ['nullable', 'date'],
            'organisations.*.actuel' => ['boolean'],
            'organisations.*.notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'prenom.required' => 'Le prénom est obligatoire.',
            'nom.required' => 'Le nom est obligatoire.',
            'categorie_id.required' => 'La catégorie est obligatoire.',
            'categorie_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
            'pays.required' => 'Le pays est obligatoire.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'langue_preferee.required' => 'La langue préférée est obligatoire.',
            'niveau_influence.required' => 'Le niveau d\'influence est obligatoire.',
            'organisations.*.organisation_id.exists' => 'L\'organisation sélectionnée n\'existe pas.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'prenom' => 'prénom',
            'nom' => 'nom',
            'categorie_id' => 'catégorie',
            'pays' => 'pays',
            'email' => 'email',
            'telephone' => 'téléphone',
            'mobile' => 'mobile',
            'fonction' => 'fonction',
            'langue_preferee' => 'langue préférée',
            'niveau_influence' => 'niveau d\'influence',
            'remarques' => 'remarques',
            'tags' => 'étiquettes',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Nettoyer les tags
        if ($this->has('tags') && is_string($this->tags)) {
            $tags = array_map('trim', explode(',', $this->tags));
            $tags = array_filter($tags); // Supprimer les valeurs vides
            $this->merge(['tags' => $tags]);
        }

        // Assurer que les organisations est un array
        if ($this->has('organisations') && !is_array($this->organisations)) {
            $this->merge(['organisations' => []]);
        }
    }
}
