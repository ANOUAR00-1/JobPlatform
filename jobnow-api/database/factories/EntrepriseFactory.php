<?php

namespace Database\Factories;

use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Entreprise>
 */
class EntrepriseFactory extends Factory
{
    protected $model = Entreprise::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'raison_social' => fake()->company(),
            'adresse' => fake()->address(),
            'telephone' => '05' . fake()->numerify('########'),
        ];
    }
}
