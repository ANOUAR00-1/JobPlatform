<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Entreprise;
use App\Models\Candidat;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

    /**
     * Register candidat (from feature/dev)
     */
    public function register(Request $request)
    {
        $request->validate([
           'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'telephone' => 'required|string',
            'ville_id' => 'required|exists:villes,id',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'candidat',
        ]);

        $candidat = Candidat::create([
           'user_id' => $user->id,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'telephone' => $request->telephone,
            'email' => $request->email, 
            'ville_id' => $request->ville_id,
        ]);

        return response()->json([
            'user' => $user,
            'candidat' => $candidat,
            'access_token' => $user->createToken('auth_token')->plainTextToken,
        ]);
    }

    /**
     * Login (from feature/dev)
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Les identifiants sont incorrects.'], 401);
    }
        $profile = ($user->role === 'candidat') ? $user->candidat : $user->entreprise;

            return response()->json([
                'user' => $user->only(['id', 'email', 'role']),
                'profile' => $profile,
                'access_token' => $user->createToken('auth_token')->plainTextToken,
            ]);
    }
}
