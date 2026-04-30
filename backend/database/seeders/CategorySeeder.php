<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Refrigerante',
            'Cerveja',
            'Suco',
            'Água',
            'Energético',
            'Leite',
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['normalized_name' => Str::of($category)->lower()->ascii()],
                [
                    'name' => $category,
                    'normalized_name' => Str::of($category)->lower()->ascii()
                ]
            );
        }
    }
}
