<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CreateOffreRequest;

class OffreController extends Controller
{
    /**
     * List all public job offers
     */
    public function index()
    {
        $perPage = request()->get('per_page', 10); // Default 10 items per page
        
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

        $offres = $query->latest()->paginate($perPage);

        // Transform the paginated data
        $offres->getCollection()->transform(function (Offre $offre) {
            $data = $offre->toArray();
            $data['company_name'] = $offre->entreprise ? $offre->entreprise->raison_social : null;
            return $data;
        });

        return response()->json($offres);
    }

    /**
     * Show a single job offer
     */
    public function show($id)
    {
        $offre = Offre::with(['entreprise', 'ville'])->find($id);
        
        if (!$offre) {
            return response()->json(['message' => 'Offre non trouvée'], 404);
        }

        $data = $offre->toArray();
        $data['company_name'] = $offre->entreprise ? $offre->entreprise->raison_social : null;

        return response()->json(['data' => $data]);
    }

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
            'ville_id' => $request->ville_id,
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

    /**
     * List offers for the logged in enterprise
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

        $offres = Offre::with(['ville'])
            ->where('entreprise_id', $entreprise->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => ['offres' => $offres]
        ]);
    }
}
