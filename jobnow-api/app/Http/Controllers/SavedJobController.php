<?php

namespace App\Http\Controllers;

use App\Models\SavedJob;
use App\Models\Offre;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class SavedJobController extends Controller
{
    #[OA\Get(
        path: "/api/candidat/saved-jobs",
        summary: "Get all saved jobs for authenticated candidat",
        description: "Returns list of jobs saved by the candidat",
        security: [["sanctum" => []]],
        tags: ["Saved Jobs"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Saved jobs retrieved successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $candidat = $request->user()->candidat;

        if (!$candidat) {
            return response()->json([
                'success' => false,
                'message' => 'Profil candidat non trouvé.',
            ], 404);
        }

        $savedJobs = SavedJob::with(['offre.entreprise', 'offre.ville'])
            ->where('candidat_id', $candidat->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => ['saved_jobs' => $savedJobs],
        ]);
    }

    #[OA\Post(
        path: "/api/candidat/saved-jobs/{offreId}",
        summary: "Save a job offer",
        description: "Add a job offer to candidat's saved jobs",
        security: [["sanctum" => []]],
        tags: ["Saved Jobs"],
        parameters: [
            new OA\Parameter(
                name: "offreId",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 201,
                description: "Job saved successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Offre sauvegardée avec succès.")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Job offer not found"),
            new OA\Response(response: 409, description: "Job already saved")
        ]
    )]
    public function store(Request $request, $offreId): JsonResponse
    {
        $candidat = $request->user()->candidat;

        if (!$candidat) {
            return response()->json([
                'success' => false,
                'message' => 'Profil candidat non trouvé.',
            ], 404);
        }

        $offre = Offre::find($offreId);

        if (!$offre) {
            return response()->json([
                'success' => false,
                'message' => 'Offre non trouvée.',
            ], 404);
        }

        // Check if already saved
        if ($candidat->hasSavedJob($offreId)) {
            return response()->json([
                'success' => false,
                'message' => 'Cette offre est déjà sauvegardée.',
            ], 409);
        }

        SavedJob::create([
            'candidat_id' => $candidat->id,
            'offre_id' => $offreId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Offre sauvegardée avec succès.',
        ], 201);
    }

    #[OA\Delete(
        path: "/api/candidat/saved-jobs/{offreId}",
        summary: "Remove a saved job",
        description: "Remove a job offer from candidat's saved jobs",
        security: [["sanctum" => []]],
        tags: ["Saved Jobs"],
        parameters: [
            new OA\Parameter(
                name: "offreId",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Job removed from saved successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Offre retirée des favoris.")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Saved job not found")
        ]
    )]
    public function destroy(Request $request, $offreId): JsonResponse
    {
        $candidat = $request->user()->candidat;

        if (!$candidat) {
            return response()->json([
                'success' => false,
                'message' => 'Profil candidat non trouvé.',
            ], 404);
        }

        $savedJob = SavedJob::where('candidat_id', $candidat->id)
            ->where('offre_id', $offreId)
            ->first();

        if (!$savedJob) {
            return response()->json([
                'success' => false,
                'message' => 'Offre non trouvée dans vos favoris.',
            ], 404);
        }

        $savedJob->delete();

        return response()->json([
            'success' => true,
            'message' => 'Offre retirée des favoris.',
        ]);
    }

    #[OA\Get(
        path: "/api/candidat/saved-jobs/check/{offreId}",
        summary: "Check if job is saved",
        description: "Check if a specific job is saved by the candidat",
        security: [["sanctum" => []]],
        tags: ["Saved Jobs"],
        parameters: [
            new OA\Parameter(
                name: "offreId",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Check result",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "is_saved", type: "boolean", example: true)
                    ]
                )
            )
        ]
    )]
    public function check(Request $request, $offreId): JsonResponse
    {
        $candidat = $request->user()->candidat;

        if (!$candidat) {
            return response()->json(['is_saved' => false]);
        }

        $isSaved = $candidat->hasSavedJob($offreId);

        return response()->json(['is_saved' => $isSaved]);
    }
}
