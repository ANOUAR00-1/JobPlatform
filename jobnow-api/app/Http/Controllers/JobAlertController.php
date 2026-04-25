<?php

namespace App\Http\Controllers;

use App\Models\JobAlert;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class JobAlertController extends Controller
{
    #[OA\Get(
        path: "/api/candidat/job-alerts",
        summary: "Get all job alerts for authenticated candidat",
        description: "Returns list of job alerts configured by the candidat",
        security: [["sanctum" => []]],
        tags: ["Job Alerts"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Job alerts retrieved successfully"
            )
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

        $alerts = JobAlert::where('candidat_id', $candidat->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => ['alerts' => $alerts],
        ]);
    }

    #[OA\Post(
        path: "/api/candidat/job-alerts",
        summary: "Create a new job alert",
        description: "Create a job alert with specific criteria",
        security: [["sanctum" => []]],
        tags: ["Job Alerts"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "keywords", type: "string", example: "developer"),
                    new OA\Property(property: "contract_types", type: "array", items: new OA\Items(type: "string")),
                    new OA\Property(property: "locations", type: "array", items: new OA\Items(type: "integer")),
                    new OA\Property(property: "frequency", type: "string", enum: ["instant", "daily", "weekly"], example: "daily")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Job alert created successfully")
        ]
    )]
    public function store(Request $request): JsonResponse
    {
        $candidat = $request->user()->candidat;

        if (!$candidat) {
            return response()->json([
                'success' => false,
                'message' => 'Profil candidat non trouvé.',
            ], 404);
        }

        $validated = $request->validate([
            'keywords' => 'nullable|string|max:255',
            'contract_types' => 'nullable|array',
            'contract_types.*' => 'in:CDI,CDD,Stage,Freelance',
            'locations' => 'nullable|array',
            'locations.*' => 'integer|exists:villes,id',
            'frequency' => 'required|in:instant,daily,weekly',
        ]);

        $alert = JobAlert::create([
            'candidat_id' => $candidat->id,
            'keywords' => $validated['keywords'] ?? null,
            'contract_types' => $validated['contract_types'] ?? null,
            'locations' => $validated['locations'] ?? null,
            'frequency' => $validated['frequency'],
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Alerte créée avec succès.',
            'data' => ['alert' => $alert],
        ], 201);
    }

    #[OA\Put(
        path: "/api/candidat/job-alerts/{id}",
        summary: "Update a job alert",
        description: "Update an existing job alert",
        security: [["sanctum" => []]],
        tags: ["Job Alerts"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Job alert updated successfully")
        ]
    )]
    public function update(Request $request, $id): JsonResponse
    {
        $candidat = $request->user()->candidat;

        if (!$candidat) {
            return response()->json([
                'success' => false,
                'message' => 'Profil candidat non trouvé.',
            ], 404);
        }

        $alert = JobAlert::where('candidat_id', $candidat->id)
            ->where('id', $id)
            ->first();

        if (!$alert) {
            return response()->json([
                'success' => false,
                'message' => 'Alerte non trouvée.',
            ], 404);
        }

        $validated = $request->validate([
            'keywords' => 'nullable|string|max:255',
            'contract_types' => 'nullable|array',
            'contract_types.*' => 'in:CDI,CDD,Stage,Freelance',
            'locations' => 'nullable|array',
            'locations.*' => 'integer|exists:villes,id',
            'frequency' => 'sometimes|in:instant,daily,weekly',
            'is_active' => 'sometimes|boolean',
        ]);

        $alert->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Alerte mise à jour avec succès.',
            'data' => ['alert' => $alert],
        ]);
    }

    #[OA\Delete(
        path: "/api/candidat/job-alerts/{id}",
        summary: "Delete a job alert",
        description: "Delete an existing job alert",
        security: [["sanctum" => []]],
        tags: ["Job Alerts"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Job alert deleted successfully")
        ]
    )]
    public function destroy(Request $request, $id): JsonResponse
    {
        $candidat = $request->user()->candidat;

        if (!$candidat) {
            return response()->json([
                'success' => false,
                'message' => 'Profil candidat non trouvé.',
            ], 404);
        }

        $alert = JobAlert::where('candidat_id', $candidat->id)
            ->where('id', $id)
            ->first();

        if (!$alert) {
            return response()->json([
                'success' => false,
                'message' => 'Alerte non trouvée.',
            ], 404);
        }

        $alert->delete();

        return response()->json([
            'success' => true,
            'message' => 'Alerte supprimée avec succès.',
        ]);
    }
}
