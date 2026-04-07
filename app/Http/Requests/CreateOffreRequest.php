<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOffreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'type_contrat' => ['required', Rule::in(['CDI', 'CDD', 'Stage', 'Freelance'])],
            'competences_requises' => 'nullable|array',
            'competences_requises.*' => 'string|max:255',
            'localisation' => 'nullable|string|max:255',
            'salaire' => 'nullable|string|max:100',
            'date_expiration' => 'nullable|date|after:today',
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required' => 'Le titre de l\'offre est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'type_contrat.required' => 'Le type de contrat est obligatoire.',
            'type_contrat.in' => 'Le type de contrat doit être CDI, CDD, Stage ou Freelance.',
            'date_expiration.after' => 'La date d\'expiration doit être postérieure à aujourd\'hui.',
        ];
    }
}
