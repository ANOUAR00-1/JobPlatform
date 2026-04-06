<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOffreRequest;
use App\Models\Offre;
use Illuminate\Http\JsonResponse;

class OffreController extends Controller
{
    /**
     * Create a new job offer (entreprise only).
     *
     * @param CreateOffreRequest $request
     * @return JsonResponse
     */
    public function store(CreateOffreRequest $request): JsonResponse
    {
        try {
            // Get authenticated user's entreprise
            $entreprise = $request->user()->entreprise;

            if (!$entreprise) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil entreprise non trouvé.',
                ], 404);
            }

            // Create the job offer
            $offre = Offre::create([
                'entreprise_id' => $entreprise->id,
                'titre' => $request->titre,
                'description' => $request->description,
                'competences_requises' => $request->competences_requises,
                'localisation' => $request->localisation,
                'salaire' => $request->salaire,
                'type_contrat' => $request->type_contrat,
                'date_expiration' => $request->date_expiration,
                'statut' => 'ouverte',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Offre d\'emploi créée avec succès.',
                'data' => [
                    'offre' => $offre,
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'offre.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
