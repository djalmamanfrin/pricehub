<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\FeatureVector;

class TokenScorer implements ScorerInterface
{
    const string OVERLAP = 'token_overlap';
    const string TOTAL = 'token_total';
    public function score(FeatureVector $features): array
    {
        $overlap = $features->get(self::OVERLAP) ?? 0;
        $total = max($features->get(self::TOTAL) ?? 1, 1);

        $ratio = $overlap / $total;
        $score = $ratio * 20;

        return [
            'score' => $score,
            'rule' => 'token_ratio',
        ];
    }
}
