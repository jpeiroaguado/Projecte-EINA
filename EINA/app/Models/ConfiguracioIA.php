<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracioIA extends Model
{
    protected $table = 'configuracions_ia';

    protected $fillable = ['activa', 'max_interaccions', 'context_id'];

    public function context()
    {
        return $this->belongsTo(ContexteClasse::class, 'context_id');
    }

    public $timestamps = true;
}
