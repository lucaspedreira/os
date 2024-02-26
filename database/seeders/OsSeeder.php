<?php

namespace Database\Seeders;

use App\Models\Os;
use Illuminate\Database\Seeder;

class OsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Os::factory(20)->create();
    }
}
