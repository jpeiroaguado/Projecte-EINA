<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Support\Facades\Log;
use App\Models\Conversa;

class ContextCanviat implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public int $conversa_id;
    public ?int $context_id;
    public string $context_descripcio;
    public int $interaccions_restants;

    public function __construct(Conversa $conversa)
    {
        // ⚠️ Carrega les relacions abans de llegir-les
        $conversa->loadMissing('context');

        $this->conversa_id = $conversa->id;
        $this->context_id = $conversa->context->id ?? null;
        $this->context_descripcio = $conversa->context->descripcio_curta ?? '';
        $this->interaccions_restants = $conversa->interaccions_restants;
    }

    public function broadcastOn()
    {
        return new PrivateChannel("conversa.{$this->conversa_id}");
    }

    public function broadcastWith(): array
    {
        return [
            'conversa_id' => $this->conversa_id,
            'context_id' => $this->context_id,
            'context_descripcio' => $this->context_descripcio,
            'interaccions_restants' => $this->interaccions_restants,
        ];
    }

    public function broadcastAs()
    {
        return 'ContextCanviat';
    }
}
