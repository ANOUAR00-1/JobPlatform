<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $titre
 * @property string $description
 * @property array|null $competences_requises
 * @property string|null $localisation
 * @property string|null $salaire
 * @property string $type_contrat
 * @property string|null $date_expiration
 */
class CreateOffreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isEntreprise();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'competences_requises' => 'nullable|array',
            'competences_requises.*' => 'string',
            'localisation' => 'nullable|string|max:255',
            'salaire' => 'nullable|string|max:100',
            'type_contrat' => 'required|in:CDI,CDD,Stage,Freelance',
            'date_expiration' => 'nullable|date|after:today',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'titre.required' => 'Le titre de l\'offre est requis.',
            'description.required' => 'La description est requise.',
            'type_contrat.required' => 'Le type de contrat est requis.',
            'type_contrat.in' => 'Le type de contrat doit être CDI, CDD, Stage ou Freelance.',
            'date_expiration.after' => 'La date d\'expiration doit être dans le futur.',
        ];
    }
}
