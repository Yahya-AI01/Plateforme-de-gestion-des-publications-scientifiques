<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EquipeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nom_equipe' => $this->faker->randomElement(['Mathématiques', 'Informatique', 'IA', 'Génie Civil']),
            'description' => $this->faker->paragraph(),
        ];
    }
}