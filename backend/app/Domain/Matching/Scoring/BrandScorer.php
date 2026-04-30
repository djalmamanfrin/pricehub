<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\FeatureVector;

class BrandScorer implements ScorerInterface
{
    const MATCH = 'brand_match';
    public function score(FeatureVector $features): array
    {
        // penalização forte
        $score = $features->get(self::MATCH) ? 30 : -80;
        return [
            'score' => $score,
            'rule' => self::MATCH
        ];
    }
}
