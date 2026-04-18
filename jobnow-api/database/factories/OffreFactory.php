<?php

namespace Database\Factories;

use App\Models\Offre;
use App\Models\Entreprise;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Offre>
 */
class OffreFactory extends Factory
{
    protected $model = Offre::class;

    public function definition(): array
    {
        return [
            'entreprise_id' => Entreprise::factory(),
            'titre' => fake()->jobTitle(),
            'description' => fake()->paragraph(3),
            'type_contrat' => fake()->randomElement(['CDI', 'CDD', 'Stage', 'Freelance']),
            'niveau_etude' => fake()->randomElement(['Bac', 'Bac+2', 'Bac+3', 'Bac+5']),
            'experience_requise' => fake()->randomElement(['Débutant', '1-3 ans', '3-5 ans', '5+ ans']),
            'salaire' => fake()->numberBetween(5000, 25000),
            'ville_id' => 1, // Default to first ville
            'date_expiration' => now()->addDays(30),
        ];
    }
}
