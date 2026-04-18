<?php

namespace Database\Factories;

use App\Models\Candidat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Candidat>
 */
class CandidatFactory extends Factory
{
    protected $model = Candidat::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'telephone' => '06' . fake()->numerify('########'),
            'email' => fake()->unique()->safeEmail(),
            'ville_id' => 1, // Default to first ville
        ];
    }
}
