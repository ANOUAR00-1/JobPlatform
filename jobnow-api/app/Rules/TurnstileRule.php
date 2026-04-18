<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Cloudflare Turnstile token verification rule.
 *
 * Validates the `cf-turnstile-response` token against
 * Cloudflare's /turnstile/v0/siteverify endpoint.
 */
class TurnstileRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secretKey = config('services.turnstile.secret_key');

        if (empty($secretKey)) {
            Log::warning('Turnstile secret key is not configured. Skipping verification.');
            return; // Fail-open in dev if key isn't set
        }

        if (empty($value)) {
            $fail('Le captcha est obligatoire.');
            return;
        }

        try {
            $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $secretKey,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            $body = $response->json();

            if (!($body['success'] ?? false)) {
                Log::info('Turnstile verification failed', ['errors' => $body['error-codes'] ?? []]);
                $fail('Vérification captcha échouée. Veuillez réessayer.');
            }
        } catch (\Exception $e) {
            Log::error('Turnstile API call failed', ['error' => $e->getMessage()]);
            $fail('Erreur de vérification du captcha. Veuillez réessayer.');
        }
    }
}
