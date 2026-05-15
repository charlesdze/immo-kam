<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Appartement',
            'Studio',
            'Chambre',
            'Villa',
            'Terrain',
            'Bureau / Commerce'
        ];

        foreach ($categories as $cat) {
            Category::create([
                'name' => $cat,
                'slug' => Str::slug($cat) // Crée "appartement" pour l'URL
            ]);
        }
    }
}