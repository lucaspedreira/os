<?php

namespace Database\Factories;

use App\Models\Cargo;
use App\Models\Escolaridade;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Funcionario>
 */
class FuncionarioFactory extends Factory
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
            'cpf' => $this->faker->unique()->randomNumber(9),
            'telefone_celular' => $this->faker->phoneNumber,
            'telefone_fixo' => $this->faker->phoneNumber,
            'cargo_id' => Cargo::factory(),
            'escolaridade_id' => Escolaridade::factory(),
            'email' => $this->faker->unique()->safeEmail,
        ];
    }
}
