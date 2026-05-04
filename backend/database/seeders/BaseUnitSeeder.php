<?php

namespace Database\Seeders;

use App\Models\UnitType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BaseUnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['name' => 'mililitro', 'symbol' => 'ml', 'type' => 'volume', 'multiplier' => 1],
            ['name' => 'litro', 'symbol' => 'l', 'type' => 'volume', 'multiplier' => 1000],

            ['name' => 'grama', 'symbol' => 'g', 'type' => 'weight', 'multiplier' => 1],
            ['name' => 'quilo', 'symbol' => 'kg', 'type' => 'weight', 'multiplier' => 1000],
            ['name' => 'tonelada', 'symbol' => 't', 'type' => 'weight', 'multiplier' => 1000000],
        ];

        foreach ($units as $unit) {
            UnitType::updateOrCreate(
                ['name' =>$unit['name']],
                $units
            );
        }
    }
}
