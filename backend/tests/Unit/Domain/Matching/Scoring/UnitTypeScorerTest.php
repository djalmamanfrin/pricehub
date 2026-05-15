<?php

use App\Domain\Matching\Scoring\UnitTypeScorer;

beforeEach(function () {
    $this->scorer = new UnitTypeScorer();
});

it('scores base unit correctly',
    function (
        ?int $input,
        ?int $product,
        float $expectedScore,
        string $expectedRule,
    ) {

        $this->scorer->apply(
            parsed(unitTypeId: $input),
            product(unitTypeId: $product)
        );

        expect($this->scorer->getValue())
            ->toBe($expectedScore)
            ->and($this->scorer->getRule())
            ->toBe("unit_type_{$expectedRule}");
    }
)->with('simple scorer cases');
