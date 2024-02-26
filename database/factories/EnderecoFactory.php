<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Endereco>
 */
class EnderecoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'enderecoable_id' => Cliente::factory(),
            'enderecoable_type' => $this->faker->randomElement(['App\Models\Funcionario', 'App\Models\Cliente', 'App\Models\Fornecedor']),
            'cep' => $this->faker->postcode,
            'logradouro' => $this->faker->streetName,
            'numero' => $this->faker->buildingNumber,
            'complemento' => $this->faker->optional()->secondaryAddress,
            'bairro' => $this->faker->city,
            'cidade' => $this->faker->city,
            'estado' => $this->faker->stateAbbr,
        ];
    }
}
