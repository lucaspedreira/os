<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('users')->insert([
            'name' => 'Lucas Pedreira',
            'email' => 'lucas@perta.io',
            'email_verified_at' => now(),
            'password' => Hash::make('lucas@lucas'),
        ]);
    }
}
