<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PublicationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'titre' => $this->faker->sentence(6),
            'type' => $this->faker->randomElement(['Article', 'Conférence', 'Chapitre', 'Thèse']),
            'annee' => $this->faker->year(),
            'domaine' => $this->faker->randomElement(['IA', 'Informatique', 'Mathématiques']),
            'resume' => $this->faker->paragraph(3),
        ];
    }
}