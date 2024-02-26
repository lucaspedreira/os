<?php

namespace Database\Factories;

use App\Models\Fornecedor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Fornecedor>
 */
class FornecedorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->company,
            'cnpj' => $this->faker->unique()->cnpj,
            'email' => $this->faker->unique()->companyEmail,
            'telefone_fixo' => $this->faker->phoneNumber,
            'telefone_celular' => $this->faker->phoneNumber,
        ];
    }
}
