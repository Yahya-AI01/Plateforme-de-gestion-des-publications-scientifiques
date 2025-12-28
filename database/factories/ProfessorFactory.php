<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class ProfessorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nom' => $this->faker->lastName(),
            'prenom' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password123'),
            'grade' => $this->faker->randomElement(['Docteur', 'Doctorant']),
            'domaine' => $this->faker->randomElement(['IA', 'Informatique', 'Mathématiques', 'Génie Civil']),
        ];
    }
}