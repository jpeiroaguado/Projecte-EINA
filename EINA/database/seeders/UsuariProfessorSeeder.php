<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsuariProfessorSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'javierpeiroaguado@eina.es'],
            [
                'name' => 'Javier Peiró',
                'password' => Hash::make('professor'),
                'rol' => 'professor',
                'interaccions_restants' => 9999,
            ]
        );
    }
}
