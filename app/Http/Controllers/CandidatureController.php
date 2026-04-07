<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplyJobRequest;
use App\Models\Candidature;
use App\Models\Offre;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class CandidatureController extends Controller
{
    /**
     * [JNV-22] Submit a job application with CV upload.
     */
    public function store(ApplyJobRequest $request): JsonResponse
    {
        $candidatId = $request->user()->id;
        $offreId = $request->offre_id;

        // Check duplicate application
        if (Candidature::where('offre_id', $offreId)->where('candidat_id', $candidatId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà postulé à cette offre.',
            ], 422);
        }

        // Check job offer status
        $offre = Offre::findOrFail($offreId);
        if ($offre->statut !== 'ouverte') {
            return response()->json([
                'success' => false,
                'message' => 'Cette offre n\'est plus disponible.',
            ], 422);
        }

        // Upload CV
        $cvPath = $request->file('cv')->store('cvs', 'public');

        $candidature = Candidature::create([
            'offre_id' => $offreId,
            'candidat_id' => $candidatId,
            'cv_path' => $cvPath,
            'lettre_motivation' => $request->lettre_motivation,
            'statut' => 'en_attente',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Candidature soumise avec succès.',
            'data' => ['candidature' => $candidature],
        ], 201);
    }
}
