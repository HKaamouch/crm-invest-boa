<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganisationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Organisation::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'raison_sociale' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'adresse' => ['nullable', 'string'],
            'ville' => ['nullable', 'string', 'max:100'],
            'pays' => ['nullable', 'string', 'max:100'],
            'telephone' => ['nullable', 'string', 'max:50'],
            'fax' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'site_web' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string'],
            'actif' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'raison_sociale.required' => 'La raison sociale est obligatoire.',
            'raison_sociale.max' => 'La raison sociale ne peut pas dépasser 255 caractères.',
            'logo.image' => 'Le logo doit être une image.',
            'logo.mimes' => 'Le logo doit être au format: jpeg, png, jpg, gif ou svg.',
            'logo.max' => 'Le logo ne peut pas dépasser 2 Mo.',
            'email.email' => 'L\'adresse email doit être valide.',
            'site_web.url' => 'Le site web doit être une URL valide.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'raison_sociale' => 'raison sociale',
            'logo' => 'logo',
            'adresse' => 'adresse',
            'ville' => 'ville',
            'pays' => 'pays',
            'telephone' => 'téléphone',
            'fax' => 'fax',
            'email' => 'email',
            'site_web' => 'site web',
            'description' => 'description',
            'actif' => 'statut actif',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // S'assurer que actif est un boolean
        $this->merge([
            'actif' => $this->boolean('actif', true), // true par défaut
        ]);
    }
}
