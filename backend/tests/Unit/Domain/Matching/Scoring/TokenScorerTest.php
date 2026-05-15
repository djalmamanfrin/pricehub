<?php

namespace Tests\Unit\Domain\Matching\Scoring;

use App\Domain\Matching\Scoring\TokenScorer;

beforeEach(function () {
    mockSynonyms();
    $this->scorer = new TokenScorer();
});


it('scores token similarity correctly',
    function (
        string $scenario,
        string $input,
        string $product,
        float $minScore,
        float $maxScore,
    ) {

        $this->scorer->apply(
            parsed($input),
            product(name: $product)
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
