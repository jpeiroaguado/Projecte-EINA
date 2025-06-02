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
            ['name' => 'Eloy', 'email' => 'eloy@eina.es'],
            ['name' => 'Kike', 'email' => 'kike@eina.es'],
            ['name' => 'Carles', 'email'=> 'carles@eina.es'],
        ];

        foreach ($alumnes as $alumne) {
            User::create([
                'name' => $alumne['name'],
                'email' => $alumne['email'],
                'password' => Hash::make('alumne'),
                'rol' => 'alumne',
            ]);
        }
    }
}
