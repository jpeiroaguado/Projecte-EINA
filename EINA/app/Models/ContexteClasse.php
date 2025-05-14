<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContexteClasse extends Model
{
    protected $table = 'contexts';

    public function configuracionsIA()
    {
        return $this->hasMany(ConfiguracioIA::class, 'context_id');
    }
}
