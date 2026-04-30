<?php

namespace Database\Seeders;

use App\Models\UnitType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UnitTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Lata',
            'Garrafa',
            'Pet',
            'Long Neck',
            'Caixa',
            'Pacote',
        ];

        foreach ($types as $type) {
            UnitType::updateOrCreate(
                ['normalized_name' => Str::of($type)->lower()],
                [
                    'name' => $type,
                    'normalized_name' => Str::of($type)->lower()
                ]
            );
        }
    }
}
