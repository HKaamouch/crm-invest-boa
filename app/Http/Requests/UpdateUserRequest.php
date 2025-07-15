<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom_complet' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user)
            ],
            'telephone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,id',
            'statut' => 'sometimes|boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nom_complet' => 'nom complet',
            'email' => 'adresse email',
            'telephone' => 'numéro de téléphone',
            'password' => 'mot de passe',
            'role' => 'rôle',
            'statut' => 'statut',
            'avatar' => 'photo de profil',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'Cette adresse email est déjà utilisée par un autre utilisateur.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password.min' => 'Le mot de passe doit contenir au moins :min caractères.',
            'role.required' => 'Veuillez sélectionner un rôle pour cet utilisateur.',
            'role.exists' => 'Le rôle sélectionné n\'existe pas.',
            'avatar.image' => 'Le fichier doit être une image.',
            'avatar.mimes' => 'L\'image doit être au format : jpeg, png, jpg ou gif.',
            'avatar.max' => 'L\'image ne doit pas dépasser 2 Mo.',
        ];
    }
}

