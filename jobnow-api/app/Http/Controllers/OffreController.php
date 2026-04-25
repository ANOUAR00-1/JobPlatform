<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use App\Services\CacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\CreateOffreRequest;

class OffreController extends Controller
{
    /**
     * List all public job offers (with caching)
     * 
     * @OA\Get(
     *     path="/api/offres",
     *     summary="List all job offers",
     *     description="Returns paginated list of open job offers with optional filters",
     *     operationId="listOffres",
     *     tags={"Job Offers"},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="contract_types",
     *         in="query",
     *         description="Comma-separated contract types (CDI,CDD,Stage,Freelance)",
     *         required=false,
     *         @OA\Schema(type="string", example="CDI,CDD")
     *     ),
     *     @OA\Parameter(
     *         name="locations",
     *         in="query",
     *         description="Comma-separated city names",
     *         required=false,
     *         @OA\Schema(type="string", example="Casablanca,Rabat")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search in title and description",
     *         required=false,
     *         @OA\Schema(type="string", example="developer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Job offers retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="total", type="integer")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $perPage = request()->get('per_page', 10);
        
        // Build cache key based on filters
        $filters = [
            'per_page' => $perPage,
            'contract_types' => request()->get('contract_types'),
            'locations' => request()->get('locations'),
            'search' => request()->get('search'),
            'page' => request()->get('page', 1),
        ];
        
        $cacheKey = CacheService::jobsListKey($filters);
        
        // Cache the results for 1 hour
        $offres = Cache::remember($cacheKey, CacheService::CACHE_JOBS_LIST, function () use ($perPage) {
            $query = Offre::with(['entreprise', 'ville'])
                ->where('statut', 'ouverte');

            // Filter by contract types
            if (request()->has('contract_types') && !empty(request()->get('contract_types'))) {
                $contractTypes = explode(',', request()->get('contract_types'));
                $query->whereIn('type_contrat', $contractTypes);
            }

            // Filter by locations (ville names)
            if (request()->has('locations') && !empty(request()->get('locations'))) {
                $locations = explode(',', request()->get('locations'));
                $query->whereHas('ville', function ($q) use ($locations) {
                    $q->whereIn('nom', $locations);
                });
            }

            // Search by title or description
            if (request()->has('search') && !empty(request()->get('search'))) {
                $search = request()->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('titre', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
                });
            }

            return $query->latest()->paginate($perPage);
        });

        // Transform the paginated data
        $offres->getCollection()->transform(function (Offre $offre) {
            $data = $offre->toArray();
            $data['company_name'] = $offre->entreprise ? $offre->entreprise->raison_social : null;
            return $data;
        });

        return response()->json($offres);
    }

    /**
     * Show a single job offer (with caching)
     * 
     * @OA\Get(
     *     path="/api/offres/{id}",
     *     summary="Get job offer details",
     *     description="Returns detailed information about a specific job offer",
     *     operationId="showOffre",
     *     tags={"Job Offers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Job offer ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Job offer details",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="titre", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="type_contrat", type="string"),
     *                 @OA\Property(property="salaire", type="string"),
     *                 @OA\Property(property="company_name", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Job offer not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Offre non trouvée")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $cacheKey = CacheService::jobDetailKey($id);
        
        $offre = Cache::remember($cacheKey, CacheService::CACHE_JOB_DETAIL, function () use ($id) {
            return Offre::with(['entreprise', 'ville'])->find($id);
        });
        
        if (!$offre) {
            return response()->json(['message' => 'Offre non trouvée'], 404);
        }

        $data = $offre->toArray();
        $data['company_name'] = $offre->entreprise ? $offre->entreprise->raison_social : null;

        return response()->json(['data' => $data]);
    }

    /**
     * [JNV-15] Create a new job offer.
     * 
     * @OA\Post(
     *     path="/api/entreprise/offres",
     *     summary="Create a new job offer",
     *     description="Creates a new job offer for the authenticated company",
     *     operationId="createOffre",
     *     tags={"Job Offers"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"titre","description","type_contrat","ville_id","date_expiration"},
     *             @OA\Property(property="titre", type="string", example="Développeur Full Stack"),
     *             @OA\Property(property="description", type="string", example="Nous recherchons un développeur..."),
     *             @OA\Property(property="type_contrat", type="string", enum={"CDI","CDD","Stage","Freelance"}, example="CDI"),
     *             @OA\Property(property="salaire", type="string", example="15000-20000 MAD"),
     *             @OA\Property(property="ville_id", type="integer", example=1),
     *             @OA\Property(property="date_expiration", type="string", format="date", example="2026-05-20")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Job offer created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Not authorized (not a company account)",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
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
            'ville_id' => $request->ville_id,
            'salaire' => $request->salaire,
            'type_contrat' => $request->type_contrat,
            'date_expiration' => $request->date_expiration,
            'statut' => 'ouverte',
        ]);

        // Clear job caches when new job is created
        CacheService::clearJobCaches();
        CacheService::clearEntrepriseJobsCache($entreprise->id);

        return response()->json([
            'success' => true,
            'message' => 'Offre d\'emploi créée avec succès.',
            'data' => ['offre' => $offre],
        ], 201);
    }

    /**
     * List offers for the logged in enterprise (with caching)
     */
    public function indexEntreprise(\Illuminate\Http\Request $request): JsonResponse
    {
        $entreprise = $request->user()->entreprise;
        
        if (!$entreprise) {
            return response()->json([
                'success' => false,
                'message' => 'Profil entreprise non trouvé.',
            ], 404);
        }

        $cacheKey = CacheService::entrepriseJobsKey($entreprise->id);
        
        $offres = Cache::remember($cacheKey, CacheService::CACHE_ENTREPRISE_JOBS, function () use ($entreprise) {
            return Offre::with(['ville'])
                ->where('entreprise_id', $entreprise->id)
                ->latest()
                ->get();
        });

        return response()->json([
            'success' => true,
            'data' => ['offres' => $offres]
        ]);
    }
}
