<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Escolaridade>
 */
class EscolaridadeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker
                ->randomElement([
                    'Ensino Fundamental',
                    'Ensino Médio',
                    'Ensino Superior',
                    'Pós-Graduação',
                    'Mestrado',
                    'Doutorado'
                ]),
        ];
    }
}
