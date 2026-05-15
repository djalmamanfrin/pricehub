<?php

use App\Domain\Matching\Scoring\BrandScorer;

beforeEach(function () {
    $this->scorer = new BrandScorer();
});

it('scores base unit correctly',
    function (
        ?int $input,
        ?int $product,
        float $expectedScore,
        string $expectedRule,
    ) {

        $this->scorer->apply(
            parsed(brandId: $input),
            product(brandId: $product)
        );

        expect($this->scorer->getValue())
            ->toBe($expectedScore)
            ->and($this->scorer->getRule())
            ->toBe("brand_{$expectedRule}");
    }
)->with('simple scorer cases');
