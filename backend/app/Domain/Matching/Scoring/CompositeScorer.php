<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\ParsedInput;
use App\Models\Product;

readonly class CompositeScorer
{
    public function __construct(
        private array $scorers
    ) {}

    public function score(ParsedInput $input, Product $product): array
    {
        $total = 0;
        $breakdown = [];

        /** @var ScorerInterface $scorer */
        foreach ($this->scorers as $scorer) {
            $scorer = $scorer->apply($input, $product);
            $total += $scorer->getValue();
            $breakdown[] = [
                'rule' => $scorer->getRule(),
                'score' => $scorer->getValue(),
            ];
        }

        return [
            'score' => $total,
            'breakdown' => $breakdown
        ];
    }
}
