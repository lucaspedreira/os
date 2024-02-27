<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::make([
            'name' => 'Lucas Pedreira',
            'email' => 'lucas@perta.io',
            'email_verified_at' => now(),
            'password' => 'lucas@lucas',
        ]);
    }
}
