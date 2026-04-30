<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\FeatureVector;

class CompositeScorer
{
    public function __construct(
        private array $scorers
    ) {}

    public function score(FeatureVector $features): float
    {
        $total = 0;
        foreach ($this->scorers as $scorer) {
            $total += $scorer->score($features);
        }

        return $total;
    }
}
