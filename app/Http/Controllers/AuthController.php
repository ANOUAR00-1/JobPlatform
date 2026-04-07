<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Entreprise;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\RegisterCandidatRequest;
use App\Http\Requests\RegisterEntrepriseRequest;

class AuthController extends Controller
{
    /**
     * Register a new entreprise (company) account.
     *
     * @param RegisterEntrepriseRequest $request
     * @return JsonResponse
     */
    public function registerEntreprise(RegisterEntrepriseRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Create user account with entreprise role
            $user = User::create([
                'email' => $request->email,
                'password' => $request->password,
                'role' => 'entreprise',
            ]);

            // Create entreprise profile
            $entreprise = Entreprise::create([
                'user_id' => $user->id,
                'raison_social' => $request->raison_social,
                'adresse' => $request->adresse,
                'telephone' => $request->telephone,
            ]);

            // Generate Sanctum API token
            $token = $user->createToken('entreprise-token')->plainTextToken;

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Compte entreprise créé avec succès.',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'email' => $user->email,
                        'role' => $user->role,
                    ],
                    'entreprise' => [
                        'id' => $entreprise->id,
                        'raison_social' => $entreprise->raison_social,
                        'adresse' => $entreprise->adresse,
                        'telephone' => $entreprise->telephone,
                    ],
                    'token' => $token,
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du compte.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Register a new candidat (job seeker) account.
     *
     * @param RegisterCandidatRequest $request
     * @return JsonResponse
     */
    public function registerCandidat(RegisterCandidatRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Create user account with candidat role
            $user = User::create([
                'email' => $request->email,
                'password' => $request->password,
                'role' => 'candidat',
            ]);

            // Generate Sanctum API token
            $token = $user->createToken('candidat-token')->plainTextToken;

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Compte candidat créé avec succès.',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'email' => $user->email,
                        'role' => $user->role,
                        'nom' => $request->nom,
                        'prenom' => $request->prenom,
                    ],
                    'token' => $token,
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du compte.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
