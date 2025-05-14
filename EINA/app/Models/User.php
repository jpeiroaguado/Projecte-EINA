<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    //@use HasFactory<\Database\Factories\UserFactory>
    use HasApiTokens, HasFactory, Notifiable;

    // Taula personalitzada de usuaris
    protected $table = 'usuaris';

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'interaccions_restants',
    ];

    /* Camps amagats*/
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /* Camps automàtics */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /* Un usuari pot tindre molts contextos */
    public function contextes()
    {
        return $this->hasMany(ContexteClasse::class, 'creat_per');
    }

    /* Un usuari pot tindre moltes conversacions */
    public function converses()
    {
        return $this->hasMany(Conversa::class, 'usuari_id');
    }
}
