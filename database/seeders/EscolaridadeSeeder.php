<?php

namespace Database\Seeders;

use App\Models\Escolaridade;
use Illuminate\Database\Seeder;

class EscolaridadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Escolaridade::factory(6)->create();
    }
}
