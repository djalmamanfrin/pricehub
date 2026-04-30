<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Synonym;

class SynonymSeeder extends Seeder
{
    public function run(): void
    {
        $synonyms = [
            // Coca-Cola
            ['term' => 'coca', 'normalized' => 'coca cola'],
            ['term' => 'coca-cola', 'normalized' => 'coca cola'],
            ['term' => 'coca cola', 'normalized' => 'coca cola'],
            ['term' => 'cocacola', 'normalized' => 'coca cola'],
            ['term' => 'coka', 'normalized' => 'coca cola'],

            // Embalagem
            ['term' => 'pet', 'normalized' => 'garrafa'],
            ['term' => 'longneck', 'normalized' => 'garrafa'],
            ['term' => 'ln', 'normalized' => 'garrafa'],

            // Volume
            ['term' => 'litro', 'normalized' => 'l'],
            ['term' => 'litros', 'normalized' => 'l'],
            ['term' => 'ml', 'normalized' => 'ml'],
        ];

        foreach ($synonyms as $synonym) {
            Synonym::updateOrCreate(
                ['term' => $synonym['term']],
                [
                    'normalized' => $synonym['normalized'],
                    'weight' => 1
                ]
            );
        }
    }
}
