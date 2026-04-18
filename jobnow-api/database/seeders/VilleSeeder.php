<?php

namespace Database\Seeders;

use App\Models\Ville;
use Illuminate\Database\Seeder;

class VilleSeeder extends Seeder
{
    public function run(): void
    {
        $villes = [
            ['nom' => 'Inezgane'],
            ['nom' => 'Agadir'],
            ['nom' => 'Casablanca'],
            ['nom' => 'Rabat'],
            ['nom' => 'Marrakech'],
            ['nom' => 'Tanger'],
        ];

        foreach ($villes as $ville) {
            Ville::create($ville);
        }
    }
}