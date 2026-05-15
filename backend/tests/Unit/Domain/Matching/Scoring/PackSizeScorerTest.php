<?php

use App\Domain\Matching\Scoring\PackSizeScorer;

beforeEach(function () {
    $this->scorer = new PackSizeScorer();
});

it('scores base unit correctly',
    function (
        ?int $input,
        ?int $product,
        float $expectedScore,
        string $expectedRule,
    ) {

        $this->scorer->apply(
            parsed(packSize: $input),
            product(packSize: $product)
        );

        expect($this->scorer->getValue())
            ->toBe($expectedScore)
            ->and($this->scorer->getRule())
            ->toBe("pack_size_{$expectedRule}");
    }
)->with('simple scorer cases');
