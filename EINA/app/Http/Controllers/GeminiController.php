<?php

namespace App\Http\Controllers;

use App\Events\NouMissatgeAlumne;
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

        // Bloquejem si no hi ha interaccions restants
        if ($conversa->interaccions_restants <= 0) {
            return response()->json(['error' => 'Interaccions esgotades.'], 403);
        }

        // Historial de missatges
        $historial = [];

        // Afegixc context inicial si NO hi ha history_id
        if (!$conversa->gemini_history_id) {
            $context = $conversa->context;
            $historial[] = [
                'role' => 'user',
                'parts' => [[
                    'text' => $context->descripcio ?? 'Actua com a professor amable i útil.'
                ]]
            ];
        }

        // Missatges anteriors a l'historial
        foreach ($conversa->missatges()->orderBy('created_at')->get() as $m) {
            $historial[] = [
                'role' => $m->remitent === 'alumne' ? 'user' : 'model',
                'parts' => [[ 'text' => $m->cos ]]
            ];
        }

        // El nou missatge de l'alumne
        $nouMissatge = $request->input('missatge');
        $historial[] = [
            'role' => 'user',
            'parts' => [[ 'text' => $nouMissatge ]]
        ];

        // Envie a Gemini
        $resposta = $gemini->enviarMissatge(
            $historial,
            env('GEMINI_API_KEY'),
            $conversa->gemini_history_id
        );

        // Si és la primera interacció i tenim history_id, el guardem
        if (!$conversa->gemini_history_id && $resposta['history_id']) {
            $conversa->update(['gemini_history_id' => $resposta['history_id']]);
        }
        //Guarde tant el missatge com la resposta de la
        Missatge::create([
            'conversa_id' => $conversa->id,
            'remitent' => 'alumne',
            'cos' => $nouMissatge,
        ]);

        // Guarde la resposta de la IA
        $missatgeNovaIA = Missatge::create([
            'conversa_id' => $conversa->id,
            'remitent' => 'ia',
            'cos' => $resposta['text'],
        ]);

        // Emet l'esdeveniment per broadcast
        event(new NouMissatgeAlumne($missatgeNovaIA));
        // Reste una interacció
        $conversa->decrement('interaccions_restants');

        return response()->json([
            'resposta' => $resposta['text'],
            'interaccions_restants' => $conversa->interaccions_restants,
        ]);
    }
}
