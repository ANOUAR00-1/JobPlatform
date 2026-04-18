<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Entreprise;
use App\Models\Candidat;
use App\Rules\TurnstileRule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Http\Requests\RegisterEntrepriseRequest;

class AuthController extends Controller
{
    /**
     * Get authenticated user data
     */
    public function getUser(Request $request): JsonResponse
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Load the appropriate profile based on role
        $profile = ($user->role === 'candidat') ? $user->candidat : $user->entreprise;

        return response()->json([
            'user' => $user->only(['id', 'email', 'role']),
            'profile' => $profile,
        ]);
    }

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
            'cf-turnstile-response' => ['required', new TurnstileRule],
        ]);

        $code = str_pad((string)mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'candidat',
        ]);

        $user->verification_code = $code;
        $user->is_verified = false;
        $user->save();

        $candidat = Candidat::create([
           'user_id' => $user->id,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'telephone' => $request->telephone,
            'email' => $request->email, 
            'ville_id' => $request->ville_id,
        ]);

        \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\VerifyCandidatMail($code));

        return response()->json([
            'requires_verification' => true,
            'email' => $user->email,
            'message' => 'Un code de vérification a été envoyé à votre adresse email.',
        ]);
    }

    /**
     * Verify email code
     */
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string',
        ]);

        $user = User::where('email', $request->email)
                    ->where('role', 'candidat')
                    ->first();

        if (!$user || $user->verification_code !== $request->code) {
            return response()->json(['message' => 'Code de vérification invalide ou expiré.'], 400);
        }

        $user->is_verified = true;
        $user->verification_code = null;
        $user->save();

        return response()->json([
            'user' => $user->only(['id', 'email', 'role']),
            'profile' => $user->candidat,
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
            'cf-turnstile-response' => ['required', new TurnstileRule],
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

    /**
     * Send password reset link
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker()->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['success' => true, 'message' => 'Lien de réinitialisation envoyé.'], 200)
            : response()->json(['success' => false, 'message' => trans($status)], 400);
    }

    /**
     * Reset the password
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['success' => true, 'message' => 'Mot de passe réinitialisé.'], 200)
            : response()->json(['success' => false, 'message' => trans($status)], 400);
    }
}
