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
            | Brands
            |--------------------------------------------------------------------------
            */

            ['term' => 'coca',        'normalized' => 'coca cola', 'type' => 'brand'],
            ['term' => 'coca-cola',   'normalized' => 'coca cola', 'type' => 'brand'],
            ['term' => 'cocacola',    'normalized' => 'coca cola', 'type' => 'brand'],
            ['term' => 'coka',        'normalized' => 'coca cola', 'type' => 'brand'],

            ['term' => 'pepsi','normalized' => 'pepsi','type' => 'brand'],

            ['term' => 'guarana',     'normalized' => 'guarana', 'type' => 'brand'],
            ['term' => 'antarctica',  'normalized' => 'guarana', 'type' => 'brand'],

            /*
            |--------------------------------------------------------------------------
            | Flavores
            |--------------------------------------------------------------------------
            */
            ['term' => 'cola','normalized' => 'cola','type' => 'flavor'],
            ['term' => 'laranja','normalized' => 'laranja','type' => 'flavor'],
            /*
            |--------------------------------------------------------------------------
            | CATEGORY
            |--------------------------------------------------------------------------
            */
            ['term' => 'refrigerante', 'normalized' => 'refrigerante', 'type' => 'category'],
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
            ['term' => 'litro',       'normalized' => 'l', 'type' => 'volume'],
            ['term' => 'litros',      'normalized' => 'l', 'type' => 'volume'],
            ['term' => 'ml',          'normalized' => 'ml', 'type' => 'volume'],
            ['term' => 'mililitro',   'normalized' => 'ml', 'type' => 'volume'],
            ['term' => 'mililitros',  'normalized' => 'ml', 'type' => 'volume'],

            // peso
            ['term' => 'quilo',       'normalized' => 'kg', 'type' => 'volume'],
            ['term' => 'quilos',      'normalized' => 'kg', 'type' => 'volume'],
            ['term' => 'kg',          'normalized' => 'kg', 'type' => 'volume'],
            ['term' => 'grama',       'normalized' => 'g', 'type' => 'volume'],
            ['term' => 'gramas',      'normalized' => 'g', 'type' => 'volume'],
            ['term' => 'g',           'normalized' => 'g', 'type' => 'volume'],

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
