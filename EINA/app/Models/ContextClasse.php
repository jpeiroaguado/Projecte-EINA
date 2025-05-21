<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContextClasse extends Model
{
    protected $table = 'contexts';

    protected $fillable = [
        'titol',
        'descripcio',
        'descripcio_curta',
        'interaccions_max',
        'actiu',
        'creat_per',
    ];

    public function configuracionsIA()
    {
        return $this->hasMany(ConfiguracioIA::class, 'context_id');
    }
}
