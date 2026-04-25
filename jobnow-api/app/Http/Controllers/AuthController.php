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
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Get(
        path: "/api/user",
        summary: "Get authenticated user profile",
        description: "Returns the authenticated user's profile data with role-specific information",
        security: [["sanctum" => []]],
        tags: ["Authentication"],
        responses: [
            new OA\Response(
                response: 200,
                description: "User profile retrieved successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "user", type: "object"),
                        new OA\Property(property: "profile", type: "object")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            )
        ]
    )]
    public function getUser(Request $request): JsonResponse
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $cacheKey = \App\Services\CacheService::userProfileKey($user->id);
        
        $userData = \Illuminate\Support\Facades\Cache::remember($cacheKey, \App\Services\CacheService::CACHE_USER_PROFILE, function () use ($user) {
            // Load the appropriate profile based on role
            $profile = ($user->role === 'candidat') ? $user->candidat : $user->entreprise;

            return [
                'user' => $user->only(['id', 'email', 'role']),
                'profile' => $profile,
            ];
        });

        return response()->json($userData);
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
     * 
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new candidate account",
     *     description="Creates a new candidate account and sends email verification code",
     *     operationId="registerCandidat",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password","nom","prenom","telephone","ville_id","cf-turnstile-response"},
     *             @OA\Property(property="email", type="string", format="email", example="candidat@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="nom", type="string", example="Alami"),
     *             @OA\Property(property="prenom", type="string", example="Ahmed"),
     *             @OA\Property(property="telephone", type="string", example="+212612345678"),
     *             @OA\Property(property="ville_id", type="integer", example=1),
     *             @OA\Property(property="cf-turnstile-response", type="string", example="turnstile_token")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registration successful, verification code sent",
     *         @OA\JsonContent(
     *             @OA\Property(property="requires_verification", type="boolean", example=true),
     *             @OA\Property(property="email", type="string", example="candidat@example.com"),
     *             @OA\Property(property="message", type="string", example="Un code de vérification a été envoyé à votre adresse email.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The email has already been taken.")
     *         )
     *     )
     * )
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

        // Send verification email in background
        \App\Jobs\SendEmailJob::dispatch($user->email, new \App\Mail\VerifyCandidatMail($code));

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


    #[OA\Post(
        path: "/api/login",
        summary: "Login to account",
        description: "Authenticates user and returns access token",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email", "password", "cf-turnstile-response"],
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", example: "user@example.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "password123"),
                    new OA\Property(property: "cf-turnstile-response", type: "string", example: "turnstile_token")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Login successful",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "user", type: "object"),
                        new OA\Property(property: "profile", type: "object"),
                        new OA\Property(property: "access_token", type: "string")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Invalid credentials"
            )
        ]
    )]
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
