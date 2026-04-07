<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Entreprise;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\RegisterEntrepriseRequest;

class AuthController extends Controller
{
    /**
     * [JNV-2] Register a new entreprise account.
     */
    public function registerEntreprise(RegisterEntrepriseRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                'email' => $request->email,
                'password' => $request->password,
                'role' => 'entreprise',
            ]);

            $entreprise = Entreprise::create([
                'user_id' => $user->id,
                'raison_social' => $request->raison_social,
                'adresse' => $request->adresse,
                'telephone' => $request->telephone,
            ]);

            $token = $user->createToken('auth-token')->plainTextToken;

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Compte entreprise créé avec succès.',
                'data' => [
                    'user' => $user->only(['id', 'email', 'role']),
                    'entreprise' => $entreprise,
                    'token' => $token,
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du compte.',
            ], 500);
        }
    }
}
