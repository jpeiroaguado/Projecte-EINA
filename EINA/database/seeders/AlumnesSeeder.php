<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AlumnesSeeder extends Seeder
{
    public function run(): void
    {
        $alumnes = [
            ['name' => 'Pedro', 'email' => 'pedro@example.com'],
            ['name' => 'Guiselle', 'email' => 'guiselle@example.com'],
            ['name' => 'Jorge', 'email' => 'jorge@example.com'],
        ];

        foreach ($alumnes as $alumne) {
            User::create([
                'name' => $alumne['name'],
                'email' => $alumne['email'],
                'password' => Hash::make('password'), // pots canviar-ho després
                'rol' => 'alumne',
            ]);
        }
    }
}
