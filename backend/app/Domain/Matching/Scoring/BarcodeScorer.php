<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\FeatureVector;

class BarcodeScorer implements ScorerInterface
{
    const MATCH = 'barcode_match';
    public function score(FeatureVector $features): array
    {
        $score = $features->get(self::MATCH) ? 100 : 0;
        return [
            'score' => $score,
            'rule' => self::MATCH
        ];
    }
}
