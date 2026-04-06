<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $offre_id
 * @property \Illuminate\Http\UploadedFile $cv
 * @property string|null $lettre_motivation
 */
class ApplyJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isCandidat();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'offre_id' => 'required|exists:offres,id',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120', // Max 5MB
            'lettre_motivation' => 'nullable|string|max:5000',
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
            'offre_id.required' => 'L\'ID de l\'offre est requis.',
            'offre_id.exists' => 'Cette offre n\'existe pas.',
            'cv.required' => 'Le CV est requis.',
            'cv.file' => 'Le CV doit être un fichier.',
            'cv.mimes' => 'Le CV doit être au format PDF, DOC ou DOCX.',
            'cv.max' => 'Le CV ne doit pas dépasser 5MB.',
        ];
    }
}
