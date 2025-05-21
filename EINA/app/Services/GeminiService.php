<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-04-17:generateContent';

    // Enviar el missatge amb l'historial i opcional historyId
    public function enviarMissatge(array $historial, string $apiKey, ?string $historyId = null): array
    {
        $payload = [
            'contents' => $historial,
        ];

        if ($historyId) {
            $payload['history'] = [
                'id' => $historyId
            ];
        }

        $response = Http::post($this->endpoint . '?key=' . $apiKey, $payload);

        if ($response->successful()) {
            $data = $response->json();

            return [
                'text' => $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Error de resposta',
                'history_id' => $data['history']['id'] ?? null
            ];
        }

        Log::error('Gemini API error', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        return [
            'text' => 'Error de comunicació amb Gemini',
            'history_id' => null
        ];
    }

    // Inicie una conversa i recupere un history_id explícitament
    public function iniciarConversa(string $context, string $apiKey): ?string
    {
        $response = Http::post($this->endpoint . '?key=' . $apiKey, [
            'contents' => [[
                'role' => 'user',
                'parts' => [['text' => $context]]
            ]]
        ]);

        if ($response->successful()) {
            return $response->json()['history']['id'] ?? null;
        }

        Log::error('Gemini iniciarConversa error', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        return null;
    }
}
