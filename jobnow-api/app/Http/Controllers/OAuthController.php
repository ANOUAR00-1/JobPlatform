<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Candidat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    /**
     * Redirect to Google OAuth page
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->stateless()
            ->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->user();

            // Check if user exists by email
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // User exists - log them in
                $token = $user->createToken('auth_token')->plainTextToken;
            } else {
                // Create new user
                $user = User::create([
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(uniqid()), // Random password for OAuth users
                    'role' => 'candidat', // Default role
                    'email_verified_at' => now(), // Auto-verify OAuth users
                ]);

                // Create candidat profile
                $names = explode(' ', $googleUser->getName());
                $firstName = $names[0] ?? '';
                $lastName = isset($names[1]) ? implode(' ', array_slice($names, 1)) : '';

                Candidat::create([
                    'user_id' => $user->id,
                    'prenom' => $firstName,
                    'nom' => $lastName,
                    'telephone' => null,
                    'ville_id' => null,
                ]);

                $token = $user->createToken('auth_token')->plainTextToken;
            }

            // Redirect to frontend with token
            $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');
            return redirect()->away("{$frontendUrl}/auth/callback?token={$token}");

        } catch (\Exception $e) {
            $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');
            return redirect()->away("{$frontendUrl}/login?error=oauth_failed");
        }
    }
}
