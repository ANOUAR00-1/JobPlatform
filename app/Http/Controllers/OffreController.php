<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOffreRequest;
use App\Models\Offre;
use Illuminate\Http\JsonResponse;

class OffreController extends Controller
{
    /**
     * [JNV-15] Create a new job offer.
     */
    public function store(CreateOffreRequest $request): JsonResponse
    {
        $entreprise = $request->user()->entreprise;

        if (!$entreprise) {
            return response()->json([
                'success' => false,
                'message' => 'Profil entreprise non trouvé.',
            ], 404);
        }

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
            'data' => ['offre' => $offre],
        ], 201);
    }
}
