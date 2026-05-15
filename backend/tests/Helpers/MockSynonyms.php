<?php

use Illuminate\Support\Facades\Cache;

function mockSynonyms(): void
{
    Cache::shouldReceive('remember')
        ->andReturn([
            'coca' => [
                ['normalized' => 'coca', 'type' => 'brand', 'weight' => 5]
            ],
            'pepsi' => [
                ['normalized' => 'pepsi', 'type' => 'brand', 'weight' => 5]
            ],
            'guarana' => [
                ['normalized' => 'guarana', 'type' => 'brand', 'weight' => 5]
            ],
            'cola' => [
                ['normalized' => 'cola', 'type' => 'flavor', 'weight' => 4]
            ],
            'lata' => [
                ['normalized' => 'lata', 'type' => 'unit', 'weight' => 2]
            ],
            'pet' => [
                ['normalized' => 'pet', 'type' => 'unit', 'weight' => 2]
            ],
            'ml' => [
                ['normalized' => 'ml', 'type' => 'volume', 'weight' => 3]
            ],
            'refrigerante' => [
                ['normalized' => 'refrigerante', 'type' => 'category', 'weight' => 4]
            ],
            'refri' => [
                ['normalized' => 'refrigerante', 'type' => 'category', 'weight' => 4]
            ],
        ]);
}
