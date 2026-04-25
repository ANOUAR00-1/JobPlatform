<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\Offre;
use App\Models\SavedJob;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

class AnalyticsController extends Controller
{
    #[OA\Get(
        path: "/api/entreprise/analytics",
        summary: "Get entreprise analytics dashboard",
        description: "Returns comprehensive analytics for the authenticated entreprise",
        security: [["sanctum" => []]],
        tags: ["Analytics"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Analytics retrieved successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            )
        ]
    )]
    public function entrepriseAnalytics(Request $request): JsonResponse
    {
        $entreprise = $request->user()->entreprise;

        if (!$entreprise) {
            return response()->json([
                'success' => false,
                'message' => 'Profil entreprise non trouvé.',
            ], 404);
        }

        // Cache analytics for 30 minutes
        $cacheKey = "analytics:entreprise:{$entreprise->id}";
        
        $analytics = Cache::remember($cacheKey, CacheService::CACHE_STATS, function () use ($entreprise) {
            // Total jobs posted
            $totalJobs = Offre::where('entreprise_id', $entreprise->id)->count();
            $activeJobs = Offre::where('entreprise_id', $entreprise->id)
                ->where('statut', 'ouverte')
                ->count();
            $closedJobs = Offre::where('entreprise_id', $entreprise->id)
                ->where('statut', 'fermee')
                ->count();

            // Applications stats
            $totalApplications = Candidature::whereHas('offre', function ($query) use ($entreprise) {
                $query->where('entreprise_id', $entreprise->id);
            })->count();

            $applicationsByStatus = Candidature::whereHas('offre', function ($query) use ($entreprise) {
                $query->where('entreprise_id', $entreprise->id);
            })
            ->select('statut', DB::raw('count(*) as count'))
            ->groupBy('statut')
            ->get()
            ->pluck('count', 'statut');

            // Recent applications (last 30 days)
            $recentApplications = Candidature::whereHas('offre', function ($query) use ($entreprise) {
                $query->where('entreprise_id', $entreprise->id);
            })
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

            // Most popular jobs (by application count)
            $popularJobs = Offre::where('entreprise_id', $entreprise->id)
                ->withCount('candidatures')
                ->orderBy('candidatures_count', 'desc')
                ->limit(5)
                ->get(['id', 'titre', 'type_contrat', 'created_at'])
                ->map(function ($job) {
                    return [
                        'id' => $job->id,
                        'titre' => $job->titre,
                        'type_contrat' => $job->type_contrat,
                        'applications_count' => $job->candidatures_count,
                        'created_at' => $job->created_at->format('Y-m-d'),
                    ];
                });

            // Applications by contract type
            $applicationsByContractType = Offre::where('entreprise_id', $entreprise->id)
                ->withCount('candidatures')
                ->select('type_contrat', DB::raw('SUM(candidatures_count) as total'))
                ->groupBy('type_contrat')
                ->get()
                ->pluck('total', 'type_contrat');

            // Average time to first application (in days)
            $avgTimeToFirstApp = Offre::where('entreprise_id', $entreprise->id)
                ->whereHas('candidatures')
                ->get()
                ->map(function ($offre) {
                    $firstApp = $offre->candidatures()->oldest()->first();
                    if ($firstApp) {
                        return $offre->created_at->diffInDays($firstApp->created_at);
                    }
                    return null;
                })
                ->filter()
                ->avg();

            // Acceptance rate
            $acceptedCount = $applicationsByStatus['acceptee'] ?? 0;
            $acceptanceRate = $totalApplications > 0 
                ? round(($acceptedCount / $totalApplications) * 100, 2) 
                : 0;

            return [
                'overview' => [
                    'total_jobs' => $totalJobs,
                    'active_jobs' => $activeJobs,
                    'closed_jobs' => $closedJobs,
                    'total_applications' => $totalApplications,
                    'recent_applications' => $recentApplications,
                    'acceptance_rate' => $acceptanceRate,
                    'avg_time_to_first_application_days' => round($avgTimeToFirstApp ?? 0, 1),
                ],
                'applications_by_status' => [
                    'en_attente' => $applicationsByStatus['en_attente'] ?? 0,
                    'acceptee' => $applicationsByStatus['acceptee'] ?? 0,
                    'refusee' => $applicationsByStatus['refusee'] ?? 0,
                ],
                'applications_by_contract_type' => $applicationsByContractType,
                'popular_jobs' => $popularJobs,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $analytics,
        ]);
    }

    #[OA\Get(
        path: "/api/candidat/analytics",
        summary: "Get candidat analytics dashboard",
        description: "Returns comprehensive analytics for the authenticated candidat",
        security: [["sanctum" => []]],
        tags: ["Analytics"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Analytics retrieved successfully"
            )
        ]
    )]
    public function candidatAnalytics(Request $request): JsonResponse
    {
        $candidat = $request->user()->candidat;

        if (!$candidat) {
            return response()->json([
                'success' => false,
                'message' => 'Profil candidat non trouvé.',
            ], 404);
        }

        // Cache analytics for 30 minutes
        $cacheKey = "analytics:candidat:{$candidat->id}";
        
        $analytics = Cache::remember($cacheKey, CacheService::CACHE_STATS, function () use ($candidat) {
            // Total applications
            $totalApplications = Candidature::where('candidat_id', $candidat->id)->count();

            // Applications by status
            $applicationsByStatus = Candidature::where('candidat_id', $candidat->id)
                ->select('statut', DB::raw('count(*) as count'))
                ->groupBy('statut')
                ->get()
                ->pluck('count', 'statut');

            // Recent applications (last 30 days)
            $recentApplications = Candidature::where('candidat_id', $candidat->id)
                ->where('created_at', '>=', now()->subDays(30))
                ->count();

            // Success rate
            $acceptedCount = $applicationsByStatus['acceptee'] ?? 0;
            $successRate = $totalApplications > 0 
                ? round(($acceptedCount / $totalApplications) * 100, 2) 
                : 0;

            // Saved jobs count
            $savedJobsCount = SavedJob::where('candidat_id', $candidat->id)->count();

            // Applications by contract type
            $applicationsByContractType = Candidature::where('candidat_id', $candidat->id)
                ->join('offres', 'candidatures.offre_id', '=', 'offres.id')
                ->select('offres.type_contrat', DB::raw('count(*) as count'))
                ->groupBy('offres.type_contrat')
                ->get()
                ->pluck('count', 'type_contrat');

            // Recent applications with details
            $recentApplicationsList = Candidature::where('candidat_id', $candidat->id)
                ->with(['offre.entreprise', 'offre.ville'])
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($candidature) {
                    return [
                        'id' => $candidature->id,
                        'job_title' => $candidature->offre->titre,
                        'company' => $candidature->offre->entreprise->raison_social ?? 'N/A',
                        'location' => $candidature->offre->ville->nom ?? 'N/A',
                        'status' => $candidature->statut,
                        'applied_at' => $candidature->created_at->format('Y-m-d'),
                    ];
                });

            // Average response time (days from application to status change)
            $avgResponseTime = Candidature::where('candidat_id', $candidat->id)
                ->whereIn('statut', ['acceptee', 'refusee'])
                ->get()
                ->map(function ($candidature) {
                    return $candidature->created_at->diffInDays($candidature->updated_at);
                })
                ->avg();

            return [
                'overview' => [
                    'total_applications' => $totalApplications,
                    'recent_applications' => $recentApplications,
                    'success_rate' => $successRate,
                    'saved_jobs_count' => $savedJobsCount,
                    'avg_response_time_days' => round($avgResponseTime ?? 0, 1),
                ],
                'applications_by_status' => [
                    'en_attente' => $applicationsByStatus['en_attente'] ?? 0,
                    'acceptee' => $applicationsByStatus['acceptee'] ?? 0,
                    'refusee' => $applicationsByStatus['refusee'] ?? 0,
                ],
                'applications_by_contract_type' => $applicationsByContractType,
                'recent_applications' => $recentApplicationsList,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $analytics,
        ]);
    }

    /**
     * Clear analytics cache for a user
     */
    public static function clearAnalyticsCache($userId, $role): void
    {
        if ($role === 'entreprise') {
            $entreprise = \App\Models\Entreprise::where('user_id', $userId)->first();
            if ($entreprise) {
                Cache::forget("analytics:entreprise:{$entreprise->id}");
            }
        } elseif ($role === 'candidat') {
            $candidat = \App\Models\Candidat::where('user_id', $userId)->first();
            if ($candidat) {
                Cache::forget("analytics:candidat:{$candidat->id}");
            }
        }
    }
}
