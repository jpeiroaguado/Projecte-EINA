<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversa extends Model
{
    protected $table = 'converses';

    protected $fillable = ['usuari_id', 'configuracio_ia_id'];

    public function usuari()
    {
        return $this->belongsTo(User::class, 'usuari_id');
    }

    public function configuracioIA()
    {
        return $this->belongsTo(ConfiguracioIA::class, 'configuracio_ia_id');
    }

    public function missatges()
    {
        return $this->hasMany(Missatge::class, 'conversa_id');
    }
}
