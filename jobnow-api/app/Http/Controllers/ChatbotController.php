<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        // 1. Context Gathering (RAG)
        // Fetch a simplified summary of the latest active job offers
        $jobs = Offre::with('entreprise')->where('statut', 'ouverte')->latest()->take(10)->get();

        $jobsContext = $jobs->map(function ($job) {
            $entreprise = $job->entreprise ? $job->entreprise->raison_social : 'Inconnue';
            return "- Titre: {$job->titre} | Entreprise: {$entreprise} | Ville: {$job->ville} | Salaire: {$job->salaire}";
        })->implode("\n");

        if (empty($jobsContext)) {
            $jobsContext = "Aucune offre d'emploi n'est disponible actuellement.";
        }

        // 2. Prompt Engineering — Multilingual + Flexible RAG
        $systemPrompt = <<<PROMPT
You are **JobyBot**, an elite, friendly, and highly intelligent AI assistant for the **JobyNow** recruitment platform based in Morocco.

## LANGUAGE RULES (CRITICAL)
- Automatically detect the language the user writes in.
- ALWAYS reply in the **exact same language** the user used.
- You MUST fluidly support these three languages:
  1. **English** — Reply in clean, professional English.
  2. **French** — Reply in natural, fluent French.
  3. **Moroccan Darija (الدارجة المغربية)** — Reply in authentic, natural Darija as spoken by real Moroccans. Use Latin script (Arabizi) or Arabic script depending on what the user used. Example Darija: "Salam khouya! Chno katqelleb 3lih? 3endna offres zwinin!"
- Never mix languages unless the user does so first.

## BEHAVIOR RULES
1. **Job-related questions**: When the user asks about available jobs, vacancies, salaries, companies, or cities — use the **DATABASE CONTEXT** below as your primary and authoritative source. Never invent or hallucinate job offers that are not listed in the context.
2. **General questions**: When the user asks about career advice, CV tips, interview preparation, tech questions, platform help, or casual greetings — use your general AI knowledge to give a highly helpful, engaging, and smart response. Be warm and human.
3. **Off-topic or harmful requests**: Politely decline and gently steer the conversation back to career topics or platform features.

## PERSONALITY
- Professional yet approachable. Think of yourself as a sharp, street-smart Moroccan career coach who also happens to be an AI.
- Keep answers concise but complete. Use bullet points and bold text for readability when listing jobs.
- Add a touch of encouragement — job hunting is tough, be supportive.

## AVAILABLE JOB OFFERS (DATABASE CONTEXT)
{$jobsContext}
PROMPT;

        // 3. API Integration (Groq Llama 3)
        try {
            $groqApiKey = env('GROQ_API_KEY');
            if (!$groqApiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'GROQ API key is missing from environment configuration.',
                ], 500);
            }

            $response = Http::withToken($groqApiKey)
                ->timeout(15)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama-3.1-8b-instant',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $systemPrompt,
                        ],
                        [
                            'role' => 'user',
                            'content' => $request->message,
                        ]
                    ],
                    'temperature' => 0.7,
                ]);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'The AI failed to respond properly.',
                    'error' => $response->json()
                ], 500);
            }

            $aiMessage = $response->json('choices.0.message.content');

            return response()->json([
                'success' => true,
                'reply' => $aiMessage,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Network error connecting to AI Services.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
