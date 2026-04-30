<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\FeatureVector;

readonly class CompositeScorer
{
    public function __construct(
        private array $scorers
    ) {}

    public function score(FeatureVector $features): array
    {
        $total = 0;
        $breakdown = [];
        foreach ($this->scorers as $scorer) {
            $result = $scorer->score($features);
            $total += $result['score'];
            $breakdown[] = [
                'rule' => $result['rule'],
                'score' => $result['score'],
            ];
        }

        return [
            'score' => $total,
            'breakdown' => $breakdown
        ];
    }
}
