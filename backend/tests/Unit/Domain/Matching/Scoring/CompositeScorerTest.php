<?php

namespace Tests\Unit\Domain\Matching\Scoring;

use App\Domain\Matching\Scoring\CompositeScorer;

beforeEach(function () {
    mockSynonyms();
    $this->scorer = app(CompositeScorer::class);
});

dataset('composite scorer cases', [
    [
        'scenario' => 'perfect match',
        'input' => parsed(
            original: 'Refrigerante Coca Cola lata 350ml',
            brandId: 1,
            unitTypeId: 1,
            baseUnitId: 1,
            packSize: 'ml',
            quantity: 350,
        ),
        'product' => product(
            name: 'refrigerante coca cola lata 350ml',
            brandId: 1,
            unitTypeId: 1,
            baseUnitId: 1,
            packSize: 'ml',
            quantity: 350,
        ),
        'minScore' => 100,
        'maxScore' => 100,
    ],
    [
        'scenario' => 'brand conflict',
        'input' => parsed(
            original: 'Refrigerante Pepsi cola lata 350ml',
            brandId: 2,
            unitTypeId: 1,
            baseUnitId: 1,
            packSize: 'ml',
            quantity: 350
        ),
        'product' => product(
            name: 'refrigerante coca cola lata 350ml',
            brandId: 1,
            unitTypeId: 1,
            baseUnitId: 1,
            packSize: 'ml',
            quantity: 350
        ),
        'minScore' => 58,
        'maxScore' => 58,
    ],
    [
        'scenario' => 'missing structured fields but token still helps',
        'input' => parsed(original: 'Refri Coca Lata'),
        'product' => product(name: 'refrigerante coca cola lata 350ml'),
        'minScore' => 14,
        'maxScore' => 14,
    ],
]);

it(
    'scores composite scorer correctly',
    function (
        string $scenario,
        $input,
        $product,
        float $minScore,
        float $maxScore,
    ) {

        $result = $this->scorer->score($input, $product);

        expect($result['score'])
            ->toMatchScore(
                $scenario,
                $minScore,
                $maxScore,
                $input->normalized,
                $product->normalized_name
            )
            ->and($result)
            ->toHaveKeys([
                'score',
                'breakdown',
            ])
            ->and($result['breakdown'])
            ->toBeArray()
            ->not->toBeEmpty();

        foreach ($result['breakdown'] as $item) {
            expect($item)
                ->toHaveKeys([
                    'rule',
                    'score',
                ]);
        }
    }
)->with('composite scorer cases');
