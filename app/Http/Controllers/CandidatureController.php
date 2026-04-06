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
     * Submit a job application with CV upload.
     *
     * @param ApplyJobRequest $request
     * @return JsonResponse
     */
    public function store(ApplyJobRequest $request): JsonResponse
    {
        try {
            $candidatId = $request->user()->id;
            $offreId = $request->offre_id;

            // Check if user already applied for this job
            $existingApplication = Candidature::where('offre_id', $offreId)
                ->where('candidat_id', $candidatId)
                ->first();

            if ($existingApplication) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous avez déjà postulé à cette offre.',
                ], 422);
            }

            // Check if the job offer is still open
            $offre = Offre::find($offreId);
            if ($offre->statut !== 'ouverte') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette offre n\'est plus disponible.',
                ], 422);
            }

            // Handle CV file upload
            $cvFile = $request->file('cv');
            $cvPath = $cvFile->store('cvs', 'public'); // Store in storage/app/public/cvs

            // Create the application
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
                'data' => [
                    'candidature' => [
                        'id' => $candidature->id,
                        'offre_id' => $candidature->offre_id,
                        'cv_path' => $candidature->cv_path,
                        'statut' => $candidature->statut,
                        'created_at' => $candidature->created_at,
                    ],
                ],
            ], 201);

        } catch (\Exception $e) {
            // Clean up uploaded file if something goes wrong
            if (isset($cvPath)) {
                Storage::disk('public')->delete($cvPath);
            }

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la soumission de la candidature.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
