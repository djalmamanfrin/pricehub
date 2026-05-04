<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Synonym;

class SynonymSeeder extends Seeder
{
    public function run(): void
    {
        $synonyms = [

            /*
            |--------------------------------------------------------------------------
            | MARCAS
            |--------------------------------------------------------------------------
            */

            ['term' => 'coca',        'normalized' => 'coca cola', 'type' => 'brand'],
            ['term' => 'coca-cola',   'normalized' => 'coca cola', 'type' => 'brand'],
            ['term' => 'cocacola',    'normalized' => 'coca cola', 'type' => 'brand'],
            ['term' => 'coka',        'normalized' => 'coca cola', 'type' => 'brand'],

            ['term' => 'guarana',     'normalized' => 'guarana', 'type' => 'brand'],
            ['term' => 'guaraná',     'normalized' => 'guarana', 'type' => 'brand'],
            ['term' => 'antarctica',  'normalized' => 'guarana', 'type' => 'brand'],

            /*
            |--------------------------------------------------------------------------
            | CATEGORY
            |--------------------------------------------------------------------------
            */

            ['term' => 'refri',       'normalized' => 'refrigerante', 'type' => 'category'],
            ['term' => 'refr',        'normalized' => 'refrigerante', 'type' => 'category'],

            /*
            |--------------------------------------------------------------------------
            | EMBALAGEM (unit_type)
            |--------------------------------------------------------------------------
            */

            ['term' => 'pet',         'normalized' => 'garrafa', 'type' => 'packaging'],
            ['term' => 'longneck',    'normalized' => 'garrafa', 'type' => 'packaging'],
            ['term' => 'long neck',   'normalized' => 'garrafa', 'type' => 'packaging'],
            ['term' => 'ln',          'normalized' => 'garrafa', 'type' => 'packaging'],

            ['term' => 'latinha',     'normalized' => 'lata', 'type' => 'packaging'],
            ['term' => 'latao',       'normalized' => 'lata', 'type' => 'packaging'],

            ['term' => 'cx',          'normalized' => 'caixa', 'type' => 'packaging'],
            ['term' => 'caixinha',    'normalized' => 'caixa', 'type' => 'packaging'],

            ['term' => 'pct',         'normalized' => 'pacote', 'type' => 'packaging'],

            /*
            |--------------------------------------------------------------------------
            | UNIDADES (BASE UNIT)
            |--------------------------------------------------------------------------
            */

            // volume
            ['term' => 'litro',       'normalized' => 'l', 'type' => 'unit'],
            ['term' => 'litros',      'normalized' => 'l', 'type' => 'unit'],
            ['term' => 'ml',          'normalized' => 'ml', 'type' => 'unit'],
            ['term' => 'mililitro',   'normalized' => 'ml', 'type' => 'unit'],
            ['term' => 'mililitros',  'normalized' => 'ml', 'type' => 'unit'],

            // peso
            ['term' => 'quilo',       'normalized' => 'kg', 'type' => 'unit'],
            ['term' => 'quilos',      'normalized' => 'kg', 'type' => 'unit'],
            ['term' => 'kg',          'normalized' => 'kg', 'type' => 'unit'],
            ['term' => 'grama',       'normalized' => 'g', 'type' => 'unit'],
            ['term' => 'gramas',      'normalized' => 'g', 'type' => 'unit'],
            ['term' => 'g',           'normalized' => 'g', 'type' => 'unit'],

            /*
            |--------------------------------------------------------------------------
            | PACK / QUANTIDADE
            |--------------------------------------------------------------------------
            */

            ['term' => 'un',          'normalized' => 'unidade', 'type' => 'generic'],
            ['term' => 'und',         'normalized' => 'unidade', 'type' => 'generic'],

            ['term' => 'cx com',      'normalized' => 'pack', 'type' => 'generic'],
            ['term' => 'caixa com',   'normalized' => 'pack', 'type' => 'generic'],
            ['term' => 'pacote com',  'normalized' => 'pack', 'type' => 'generic'],
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
