<?php

namespace Tests\Unit\Domain\Matching\Scoring;

use App\Domain\Matching\Scoring\TokenScorer;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    mockSynonyms();

    $this->scorer = new TokenScorer();
});

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


it('scores token similarity correctly',
    function (
        string $scenario,
        string $input,
        string $product,
        float $minScore,
        float $maxScore,
    ) {

        $this->scorer->apply(
            parsedName($input),
            product($product)
        );

        $score = $this->scorer->getValue();

        expect($score)
            ->toMatchScore(
                $scenario,
                $minScore,
                $maxScore,
                $input,
                $product
            )
            ->and($this->scorer->getRule())
            ->toBe('token_similarity');
    }
)->with('token_scorer_cases');
