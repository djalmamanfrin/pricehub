<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\FeatureVector;

class VolumeScorer implements ScorerInterface
{
    const string MATCH = 'volume_match';
    const string MISMATCH = 'volume_mismatch';
    public function score(FeatureVector $features): array
    {
        if ($features->get(self::MATCH)) {
            return [
                'score' => 40,
                'rule' => self::MATCH,
            ];
        }

        return [
            'score' => -30,
            'rule' => self::MISMATCH,
        ];
    }
}
