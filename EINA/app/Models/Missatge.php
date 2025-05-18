<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Missatge extends Model
{
    protected $table = 'missatges';

    protected $fillable = ['conversa_id', 'remitent', 'cos'];

    public function conversa()
    {
        return $this->belongsTo(Conversa::class, 'conversa_id');
    }
}
