<?php

namespace Database\Factories;

use App\Models\Os;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContaReceber>
 */
class ContaReceberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'os_id' => Os::factory(),
            'valor' => $this->faker->randomNumber(7),
        ];
    }
}
