<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // <-- AJOUTE CETTE LIGNE ICI
use App\Models\User; // Optionnel mais recommandé

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin1',
            'email' => 'admin1@gmail.com',
            'password' => Hash::make('admin1234'),
            'is_admin' => 1,
        ]);
    }
}