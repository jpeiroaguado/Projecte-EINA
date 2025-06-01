<?php
use App\Models\Conversa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('conversa.{conversaId}', function ($user, $conversaId) {
    try {
        if (!$user) return false;

        Log::info('Intent de subscripció', [
            'user_id' => $user->id,
            'rol' => $user->rol ?? 'desconegut',
            'conversa_id' => $conversaId,
        ]);

        $conversa = Conversa::find($conversaId);
        if (!$conversa) return false;
        return $conversa->usuari_id === $user->id || $user->rol === 'professor';

    } catch (\Throwable $e) {
        Log::error('Error en canal de conversa', [
            'error' => $e->getMessage(),
            'user_id' => $user->id ?? null,
        ]);
        return false;
    }
});


