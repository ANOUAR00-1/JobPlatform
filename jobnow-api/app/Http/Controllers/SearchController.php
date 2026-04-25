<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use App\Models\Entreprise;
use App\Models\Ville;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

class SearchController extends Controller
{
    #[OA\Get(
        path: "/api/search/autocomplete/jobs",
        summary: "Autocomplete job titles",
        description: "Returns job title suggestions based on search query",
        tags: ["Search"],
        parameters: [
            new OA\Parameter(
                name: "q",
                in: "query",
                required: true,
                description: "Search query (minimum 2 characters)",
                schema: new OA\Schema(type: "string", minLength: 2)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Autocomplete suggestions",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "suggestions", type: "array", items: new OA\Items(type: "string"))
                    ]
                )
            )
        ]
    )]
    public function autocompleteJobs(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json(['suggestions' => []]);
        }

        $cacheKey = "autocomplete:jobs:" . md5($query);
        
        $suggestions = Cache::remember($cacheKey, 3600, function () use ($query) {
            return Offre::where('statut', 'ouverte')
                ->where('titre', 'like', '%' . $query . '%')
                ->select('titre')
                ->distinct()
                ->limit(10)
                ->pluck('titre')
                ->toArray();
        });

        return response()->json(['suggestions' => $suggestions]);
    }

    #[OA\Get(
        path: "/api/search/autocomplete/locations",
        summary: "Autocomplete locations",
        description: "Returns city suggestions based on search query",
        tags: ["Search"],
        parameters: [
            new OA\Parameter(
                name: "q",
                in: "query",
                required: true,
                description: "Search query (minimum 2 characters)",
                schema: new OA\Schema(type: "string", minLength: 2)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Location suggestions"
            )
        ]
    )]
    public function autocompleteLocations(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json(['suggestions' => []]);
        }

        $cacheKey = "autocomplete:locations:" . md5($query);
        
        $suggestions = Cache::remember($cacheKey, 86400, function () use ($query) {
            return Ville::where('nom', 'like', '%' . $query . '%')
                ->select('id', 'nom')
                ->limit(10)
                ->get()
                ->map(function ($ville) {
                    return [
                        'id' => $ville->id,
                        'name' => $ville->nom,
                    ];
                })
                ->toArray();
        });

        return response()->json(['suggestions' => $suggestions]);
    }

    #[OA\Get(
        path: "/api/search/autocomplete/companies",
        summary: "Autocomplete company names",
        description: "Returns company name suggestions based on search query",
        tags: ["Search"],
        parameters: [
            new OA\Parameter(
                name: "q",
                in: "query",
                required: true,
                description: "Search query (minimum 2 characters)",
                schema: new OA\Schema(type: "string", minLength: 2)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Company suggestions"
            )
        ]
    )]
    public function autocompleteCompanies(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json(['suggestions' => []]);
        }

        $cacheKey = "autocomplete:companies:" . md5($query);
        
        $suggestions = Cache::remember($cacheKey, 3600, function () use ($query) {
            return Entreprise::where('raison_social', 'like', '%' . $query . '%')
                ->whereHas('offres', function ($q) {
                    $q->where('statut', 'ouverte');
                })
                ->select('id', 'raison_social')
                ->distinct()
                ->limit(10)
                ->get()
                ->map(function ($entreprise) {
                    return [
                        'id' => $entreprise->id,
                        'name' => $entreprise->raison_social,
                    ];
                })
                ->toArray();
        });

        return response()->json(['suggestions' => $suggestions]);
    }

    #[OA\Get(
        path: "/api/search/popular",
        summary: "Get popular search terms",
        description: "Returns popular job titles and locations",
        tags: ["Search"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Popular search terms"
            )
        ]
    )]
    public function popularSearches(): JsonResponse
    {
        $cacheKey = "search:popular";
        
        $popular = Cache::remember($cacheKey, 3600, function () {
            // Most common job titles
            $popularJobs = Offre::where('statut', 'ouverte')
                ->select('titre', DB::raw('count(*) as count'))
                ->groupBy('titre')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->pluck('titre')
                ->toArray();

            // Most common locations
            $popularLocations = Offre::where('statut', 'ouverte')
                ->join('villes', 'offres.ville_id', '=', 'villes.id')
                ->select('villes.id', 'villes.nom', DB::raw('count(*) as count'))
                ->groupBy('villes.id', 'villes.nom')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($ville) {
                    return [
                        'id' => $ville->id,
                        'name' => $ville->nom,
                    ];
                })
                ->toArray();

            // Most common contract types
            $popularContractTypes = Offre::where('statut', 'ouverte')
                ->select('type_contrat', DB::raw('count(*) as count'))
                ->groupBy('type_contrat')
                ->orderBy('count', 'desc')
                ->pluck('type_contrat')
                ->toArray();

            return [
                'popular_jobs' => $popularJobs,
                'popular_locations' => $popularLocations,
                'popular_contract_types' => $popularContractTypes,
            ];
        });

        return response()->json($popular);
    }
}
