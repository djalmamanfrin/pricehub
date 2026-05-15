<?php

use App\Domain\Matching\Scoring\BaseUnitScorer;

beforeEach(function () {
    $this->scorer = new BaseUnitScorer();
});

it('scores base unit correctly',
    function (
        ?int $input,
        ?int $product,
        float $expectedScore,
        string $expectedRule,
    ) {

        $this->scorer->apply(
            parsed(baseUnitId: $input),
            product(baseUnitId: $product)
        );

        expect($this->scorer->getValue())
            ->toBe($expectedScore)
            ->and($this->scorer->getRule())
            ->toBe("base_unit_{$expectedRule}");
    }
)->with('simple scorer cases');
