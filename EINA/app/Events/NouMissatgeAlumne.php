<?php

namespace App\Events;

use App\Models\Missatge;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class NouMissatgeAlumne implements ShouldBroadcast
{
    use SerializesModels;

    public $missatge;

    public function __construct(Missatge $missatge)
    {
        $this->missatge = $missatge;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('xat-alumne.' . $this->missatge->conversa_id);
    }

    public function broadcastAs()
    {
        return 'nou-missatge';
    }
}
