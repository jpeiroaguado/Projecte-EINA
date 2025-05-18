<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    protected string $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

    public function enviarMissatge(array $historial, string $apiKey): string
    {
        $response = Http::withToken($apiKey)
            ->post($this->endpoint, [
                'contents' => $historial,
            ]);

        if ($response->successful()) {
            return $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? 'Error de resposta';
        }

        return 'Error de comunicació amb Gemini';
    }
}
