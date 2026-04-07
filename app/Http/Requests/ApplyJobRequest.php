<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'offre_id' => 'required|integer|exists:offres,id',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'lettre_motivation' => 'nullable|string|max:5000',
        ];
    }

    public function messages(): array
    {
        return [
            'offre_id.required' => 'L\'offre d\'emploi est obligatoire.',
            'offre_id.exists' => 'L\'offre d\'emploi sélectionnée n\'existe pas.',
            'cv.required' => 'Le CV est obligatoire.',
            'cv.mimes' => 'Le CV doit être au format PDF, DOC ou DOCX.',
            'cv.max' => 'Le CV ne peut pas dépasser 5 Mo.',
        ];
    }
}
