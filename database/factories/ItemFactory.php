<?php

namespace Database\Factories;

use App\Models\Os;
use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'produto_id' => Produto::factory(),
            'os_id' => Os::factory(),
            'quantidade' => $this->faker->randomNumber(3),
            'subtotal' => $this->faker->randomNumber(7),
        ];
    }
}
