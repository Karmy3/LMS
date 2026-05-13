<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    public function generateMarketingDescription($course)
    {
        $apiKey = config('services.gemini.key');
        
        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent";

        $specialty = $course->instructor ? $course->instructor->specialty : 'Expert';

        $systemPrompt = "Tu es un expert en marketing de formation. Tu rédiges des descriptions courtes et percutantes. Réponds uniquement en français. Maximum 4 phrases.";
        
        $userPrompt = "Génère une description marketing pour ce cours : 
        Titre : {$course->title}
        Durée : {$course->duration_hours} heures
        Formateur spécialisé en : {$specialty}";

        try {
            $response = Http::withoutVerifying()->post("{$endpoint}?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $systemPrompt . "\n\n" . $userPrompt]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                return $response->json('candidates.0.content.parts.0.text');
            }

            Log::error("Gemini API Error: " . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error("Gemini Exception: " . $e->getMessage());
            return null;
        }
    }
}