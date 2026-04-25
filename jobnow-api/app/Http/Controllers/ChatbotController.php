<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ChatbotController extends Controller
{
    /**
     * Groq API Configuration
     */
    private const GROQ_API_URL = 'https://api.groq.com/openai/v1/chat/completions';
    private const MODEL = 'llama-3.1-8b-instant'; // Fast and efficient
    private const MAX_TOKENS = 1024;
    private const TEMPERATURE = 0.7;

    /**
     * Handle chat request from user
     */
    public function ask(Request $request)
    {
        try {
            // Validate incoming request
            $validator = Validator::make($request->all(), [
                'message' => 'required|string|max:1000',
            ], [
                'message.required' => 'Le message est obligatoire',
                'message.max' => 'Le message ne peut pas dépasser 1000 caractères',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            $userMessage = $request->input('message');

            // Get RAG context (latest job offers)
            $jobContext = $this->getJobOffersContext();

            // Build system prompt with context
            $systemPrompt = $this->buildSystemPrompt($jobContext);

            // Call Groq API
            $response = $this->callGroqAPI($systemPrompt, $userMessage);

            if (!$response['success']) {
                Log::error('Groq API Error', ['error' => $response['error']]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la communication avec JobyBot',
                    'error' => $response['error'] ?? 'Unknown error'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'reply' => $response['message'],
                'model' => self::MODEL,
            ], 200);

        } catch (\Exception $e) {
            Log::error('ChatbotController Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors du traitement de votre demande',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get job offers context for RAG
     * Fetches the 10 most recent open job offers with full details
     */
    private function getJobOffersContext(): array
    {
        try {
            $offers = Offre::with(['ville', 'entreprise'])
                ->where('statut', 'ouverte')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            return $offers->map(function ($offre) {
                $ville = $offre->ville ? $offre->ville->nom : 'Non spécifié';
                $entreprise = $offre->entreprise ? $offre->entreprise->raison_social : 'Non spécifié';
                
                $competences = '';
                if (is_array($offre->competences_requises) && !empty($offre->competences_requises)) {
                    $competences = implode(', ', $offre->competences_requises);
                } elseif (is_string($offre->competences_requises)) {
                    $competences = $offre->competences_requises;
                }

                return [
                    'id' => $offre->id,
                    'titre' => $offre->titre,
                    'entreprise' => $entreprise,
                    'ville' => $ville,
                    'type_contrat' => $offre->type_contrat,
                    'salaire' => $offre->salaire,
                    'competences' => $competences,
                    'description' => substr($offre->description, 0, 200) . '...',
                ];
            })->toArray();

        } catch (\Exception $e) {
            Log::warning('Failed to fetch job context: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Build system prompt with RAG context
     */
    private function buildSystemPrompt(array $jobContext): string
    {
        $contextString = '';
        
        if (!empty($jobContext)) {
            $contextString = "\n\n📋 OFFRES D'EMPLOI DISPONIBLES ACTUELLEMENT (BASE DE DONNÉES EN TEMPS RÉEL):\n\n";
            
            foreach ($jobContext as $index => $job) {
                $contextString .= sprintf(
                    "%d. **%s**\n   - Entreprise: %s\n   - Ville: %s\n   - Type: %s\n   - Salaire: %s\n   - Compétences: %s\n   - Description: %s\n\n",
                    $index + 1,
                    $job['titre'],
                    $job['entreprise'],
                    $job['ville'],
                    $job['type_contrat'],
                    $job['salaire'],
                    $job['competences'],
                    $job['description']
                );
            }
        } else {
            $contextString = "\n\n⚠️ Aucune offre d'emploi disponible pour le moment dans la base de données.\n\n";
        }

        return <<<PROMPT
Tu es **JobyBot**, un assistant RH expert et intelligent spécialisé dans le recrutement au Maroc. Tu travailles pour **JobyNow**, une plateforme ATS (Applicant Tracking System) moderne qui connecte les candidats avec les meilleures opportunités d'emploi au Maroc.

**TA MISSION PRINCIPALE:**
- Aider les candidats à trouver des opportunités d'emploi qui correspondent à leurs compétences
- Répondre aux questions sur les offres disponibles dans la base de données
- Donner des conseils professionnels sur la recherche d'emploi, les CV, et les entretiens
- Être chaleureux, professionnel, encourageant et humain

**RÈGLE LINGUISTIQUE CRITIQUE (ABSOLUMENT OBLIGATOIRE):**
- **TOUJOURS** répondre dans la **MÊME LANGUE** que l'utilisateur
- **Détecte automatiquement** la langue et adapte-toi instantanément
- **Langues supportées:**
  1. **Darija marocaine (الدارجة)** - Réponds en Darija naturelle et authentique
     - Exemple: "Salam khouya! Chno katqelleb 3lih? 3endna offres zwinin f Casablanca!"
     - Utilise l'arabizi (latin) ou l'arabe selon ce que l'utilisateur utilise
  2. **Français** - Réponds en français naturel et professionnel
     - Exemple: "Bonjour! Je suis ravi de vous aider. Voici les offres disponibles..."
  3. **Anglais** - Réponds en anglais clair et professionnel
     - Exemple: "Hello! I'm happy to help you find the perfect job opportunity..."
- **NE MÉLANGE JAMAIS** les langues sauf si l'utilisateur le fait

**CONTEXTE ACTUEL (SOURCE DE VÉRITÉ):**
{$contextString}

**INSTRUCTIONS COMPORTEMENTALES:**

1. **Questions sur les offres d'emploi:**
   - Utilise UNIQUEMENT les offres listées ci-dessus
   - Sois précis avec les détails (salaire, localisation, compétences requises)
   - Si on te demande des offres spécifiques (ville, type de contrat, compétences), filtre les informations ci-dessus
   - Si aucune offre ne correspond, suggère des alternatives ou conseille d'élargir la recherche
   - **NE JAMAIS inventer** des offres qui ne sont pas dans le contexte

2. **Questions générales (conseils carrière, CV, entretiens):**
   - Utilise tes connaissances générales pour donner des conseils pertinents et utiles
   - Sois spécifique au contexte marocain quand c'est pertinent
   - Donne des exemples concrets et actionnables

3. **Salutations et conversations:**
   - Sois chaleureux et accueillant
   - Présente-toi brièvement si c'est la première interaction
   - Demande comment tu peux aider

4. **Analyse et réflexion:**
   - **AVANT de répondre**, analyse la question de l'utilisateur
   - Identifie si c'est une question sur les offres (utilise le contexte) ou une question générale (utilise tes connaissances)
   - Réfléchis à la meilleure façon de structurer ta réponse
   - Sois concis mais complet

**STYLE DE RÉPONSE:**
- Utilise des **bullet points** et du **gras** pour la lisibilité
- Structure tes réponses clairement
- Sois encourageant - la recherche d'emploi est difficile
- **N'utilise AUCUN emoji ou icône** - reste professionnel et épuré
- Reste professionnel mais accessible

**NE FAIS JAMAIS:**
- Inventer des offres qui ne sont pas dans le contexte
- Donner des informations de contact fictives
- Promettre des résultats garantis
- Sortir de ton rôle d'assistant RH
- Répondre à des questions non liées à la carrière/emploi (décline poliment)
- Mentionner que tu es une IA ou un modèle de langage
- Utiliser des emojis ou des icônes dans tes réponses

**TOUJOURS:**
- Analyser la question avant de répondre
- Vérifier le contexte des offres disponibles
- Répondre dans la langue de l'utilisateur
- Être précis et factuel avec les données
- Encourager et motiver les candidats
- Garder un ton professionnel sans emojis

Tu es là pour aider les candidats marocains à réussir leur recherche d'emploi! Sois leur meilleur allié dans cette aventure.
PROMPT;
    }

    /**
     * Call Groq API with system prompt and user message
     */
    private function callGroqAPI(string $systemPrompt, string $userMessage): array
    {
        try {
            $apiKey = config('services.groq.api_key');

            if (empty($apiKey)) {
                throw new \Exception('GROQ_API_KEY not configured');
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])
            ->timeout(30)
            ->post(self::GROQ_API_URL, [
                'model' => self::MODEL,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemPrompt
                    ],
                    [
                        'role' => 'user',
                        'content' => $userMessage
                    ]
                ],
                'max_tokens' => self::MAX_TOKENS,
                'temperature' => self::TEMPERATURE,
                'top_p' => 1,
                'stream' => false,
            ]);

            if ($response->failed()) {
                Log::error('Groq API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return [
                    'success' => false,
                    'error' => 'API request failed: ' . $response->status()
                ];
            }

            $data = $response->json();

            if (!isset($data['choices'][0]['message']['content'])) {
                Log::error('Unexpected Groq API response structure', ['data' => $data]);
                
                return [
                    'success' => false,
                    'error' => 'Unexpected API response structure'
                ];
            }

            return [
                'success' => true,
                'message' => $data['choices'][0]['message']['content'],
                'usage' => $data['usage'] ?? null,
            ];

        } catch (\Exception $e) {
            Log::error('Groq API Call Exception: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
