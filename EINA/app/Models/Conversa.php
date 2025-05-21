<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversa extends Model
{
    protected $table = 'converses';

    protected $fillable = ['usuari_id', 'context_id', 'interaccions_restants', 'gemini_history_id'];

    public function usuari()
    {
        return $this->belongsTo(User::class, 'usuari_id');
    }

    public function missatges()
    {
        return $this->hasMany(Missatge::class, 'conversa_id');
    }
    public function context()
    {
        return $this->belongsTo(ContextClasse::class, 'context_id');
    }
}
