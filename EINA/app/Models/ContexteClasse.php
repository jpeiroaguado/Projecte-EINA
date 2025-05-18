<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContexteClasse extends Model
{
    protected $table = 'contexts';

    protected $fillable = [
        'titol',
        'descripcio',
        'interaccions_max',
        'actiu',
        'creat_per',
    ];

    public function configuracionsIA()
    {
        return $this->hasMany(ConfiguracioIA::class, 'context_id');
    }
}
