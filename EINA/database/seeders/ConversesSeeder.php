<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Conversa;
use App\Models\Missatge;
use Faker\Factory as Faker;
use Illuminate\Support\Carbon;

class ConversesSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $conversa = Conversa::create([
            'usuari_id' => 1, // alumne amb id 1
            'context_id' => 2, // context ja creat
            'interaccions_restants' => 5,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Missatges d'exemple
        Missatge::create([
            'conversa_id' => $conversa->id,
            'remitent' => 'alumne',
            'cos' => 'What is a responsive layout?',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Missatge::create([
            'conversa_id' => $conversa->id,
            'remitent' => 'ia',
            'cos' => 'A responsive layout adapts to different screen sizes using techniques like media queries, flexible grids, and fluid images.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
