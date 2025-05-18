<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;
use App\Models\Conversa;
use App\Models\Missatge;

class GeminiController extends Controller
{
    public function enviar(Request $request, $id, GeminiService $gemini)
    {
        $conversa = Conversa::findOrFail($id);
        $usuari = auth()->user();

        // Agafem la configuració IA
        $config = $conversa->configuracioIA;

        // Historial + context inicial
        $historial = [];

        // Instrucció inicial (context del professor)
        $historial[] = [
            'role' => 'user',
            'parts' => [[ 'text' => $config->context ?? 'Actua com a professor amable i útil.' ]]
        ];

        // Missatges anteriors
        foreach ($conversa->missatges()->orderBy('created_at')->get() as $m) {
            $historial[] = [
                'role' => $m->remitent === 'alumne' ? 'user' : 'model',
                'parts' => [[ 'text' => $m->cos ]]
            ];
        }

        // Nou missatge
        $nou = $request->input('missatge');

        $historial[] = [
            'role' => 'user',
            'parts' => [[ 'text' => $nou ]]
        ];

        // Enviem a Gemini
        $resposta = $gemini->enviarMissatge($historial, env('GEMINI_API_KEY'));

        // Guardem
        Missatge::create([
            'conversa_id' => $conversa->id,
            'remitent' => 'alumne',
            'cos' => $nou,
        ]);

        Missatge::create([
            'conversa_id' => $conversa->id,
            'remitent' => 'ia',
            'cos' => $resposta,
        ]);

        return response()->json(['resposta' => $resposta]);
    }
}
