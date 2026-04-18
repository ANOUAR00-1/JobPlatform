<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplyJobRequest;
use App\Models\Candidature;
use App\Models\Offre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\EntretienConvocationMail;
use App\Notifications\NewCandidatureNotification;

class CandidatureController extends Controller
{
    /**
     * [JNV-22] Submit a job application with CV upload.
     */
    public function store(ApplyJobRequest $request): JsonResponse
    {
        $candidatId = $request->user()->candidat->id;
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

        // Trigger Notification to Enterprise User
        if ($offre->entreprise && $offre->entreprise->user) {
            $offre->entreprise->user->notify(new NewCandidatureNotification($candidature));
        }

        return response()->json([
            'success' => true,
            'message' => 'Candidature soumise avec succès.',
            'data' => ['candidature' => $candidature],
        ], 201);
    }

    /**
     * [JNV-14] - Task: En tant qu'entreprise, je veux voir les candidatures reçues afin de les gérer.
     */
    public function indexEntreprise(Request $request): JsonResponse
    {
        $entrepriseId = $request->user()->entreprise->id;

        $candidatures = Candidature::whereHas('offre', function ($query) use ($entrepriseId) {
            $query->where('entreprise_id', $entrepriseId);
        })->with(['offre', 'candidat'])->get();

        return response()->json([
            'success' => true,
            'data' => ['candidatures' => $candidatures]
        ]);
    }

    /**
     * [JNV-24] - Task: En tant qu'entreprise, je veux accepter une candidature afin de recruter le candidat.
     */
    public function accepter(Request $request, Candidature $candidature): JsonResponse
    {
        $entrepriseId = $request->user()->entreprise->id;

        if ($candidature->offre->entreprise_id !== $entrepriseId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $candidature->update(['statut' => 'acceptee']);

        return response()->json([
            'success' => true,
            'message' => 'Candidature acceptée.',
            'data' => ['candidature' => $candidature]
        ]);
    }

    /**
     * [JNV-25] - Task: En tant qu'entreprise, je veux refuser une candidature afin de gérer le recrutement.
     */
    public function refuser(Request $request, Candidature $candidature): JsonResponse
    {
        $entrepriseId = $request->user()->entreprise->id;

        if ($candidature->offre->entreprise_id !== $entrepriseId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $candidature->update(['statut' => 'refusee']);

        return response()->json([
            'success' => true,
            'message' => 'Candidature refusée.',
            'data' => ['candidature' => $candidature]
        ]);
    }

    /**
     * [JNV-30] - Task: Evaluate a candidate
     */
    public function evaluate(Request $request, Candidature $candidature): JsonResponse
    {
        $entrepriseId = $request->user()->entreprise->id;

        if ($candidature->offre->entreprise_id !== $entrepriseId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'note_evaluation' => 'required|integer|min:1|max:5',
            'commentaire_recruteur' => 'nullable|string',
        ]);

        $candidature->update([
            'note_evaluation' => $validated['note_evaluation'],
            'commentaire_recruteur' => $validated['commentaire_recruteur'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Candidature évaluée avec succès.',
            'data' => ['candidature' => $candidature]
        ]);
    }

    /**
     * [JNV-27] - Task: Send an interview convocation
     */
    public function convoquer(Request $request, Candidature $candidature): JsonResponse
    {
        $entrepriseId = $request->user()->entreprise->id;

        if ($candidature->offre->entreprise_id !== $entrepriseId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'location' => 'required|string',
        ]);

        $candidature->update([
            'statut' => 'convoquée' // or 'convoqué' (adopting feminine for candidature: 'convoquée')
        ]);

        Mail::to($candidature->candidat->user->email)->send(
            new EntretienConvocationMail(
                $candidature->candidat->nom . ' ' . $candidature->candidat->prenom,
                $candidature->offre->titre,
                $validated['date'],
                $validated['time'],
                $validated['location']
            )
        );

        return response()->json([
            'success' => true,
            'message' => 'Convocation envoyée avec succès.',
            'data' => ['candidature' => $candidature]
        ]);
    }
}
