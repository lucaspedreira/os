<?php

namespace Database\Seeders;

use App\Models\ContaReceber;
use Illuminate\Database\Seeder;

class ContaReceberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContaReceber::factory(20)->create();
    }
}
