<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cliente>
 */
class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->name,
            'sexo' => $this->faker->randomElement(['M', 'F']),
            'data_nascimento' => $this->faker->date(),
            'cpf' => $this->faker->unique()->cpf,
            'email' => $this->faker->unique()->safeEmail,
            'telefone_fixo' => $this->faker->phoneNumber,
            'telefone_celular' => $this->faker->phoneNumber,
        ];
    }
}
