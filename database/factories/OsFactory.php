<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Funcionario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Os>
 */
class OsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'funcionario_id' => Funcionario::factory(),
            'cliente_id' => Cliente::factory(),
            'descricao' => $this->faker->text,
            'data' => $this->faker->dateTime,
            'status' => $this->faker->randomElement(['aberto', 'fechado', 'cancelado', 'aguardando_peca', 'aguardando_aprovacao', 'aguardando_orcamento', 'aguardando_retirada', 'aguardando_entrega']),
            'valor_total' => $this->faker->randomNumber(6),
        ];
    }
}
