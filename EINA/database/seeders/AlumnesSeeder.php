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
            ['name' => 'Pedro', 'email' => 'pedro@eina.es'],
            ['name' => 'Giselle', 'email' => 'giselle@eina.es'],
            ['name' => 'Jorge', 'email'=> 'jorge@eina.es'],
        ];

        foreach ($alumnes as $alumne) {
            User::create([
                'name' => $alumne['name'],
                'email' => $alumne['email'],
                'password' => Hash::make('1234'),
                'rol' => 'alumne',
            ]);
        }
    }
}
