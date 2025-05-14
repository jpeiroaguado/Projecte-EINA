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
            ['email' => 'javierpeiroaguado@gmail.com'],
            [
                'name' => 'Javier Peiró',
                'password' => Hash::make('Natalya-26'),
                'rol' => 'professor',
                'interaccions_restants' => 999,
            ]
        );
    }
}
