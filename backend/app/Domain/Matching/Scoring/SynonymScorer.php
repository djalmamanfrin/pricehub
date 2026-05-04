<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\FeatureVector;

class SynonymScorer implements ScorerInterface
{
    const string SIMILARITY = 'synonym_similarity';
    public function score(FeatureVector $features): array
    {
        $value = $features->get(self::SIMILARITY) ?? 0;

        return [
            'score' => $value * 0.3,
            'rule' => self::SIMILARITY
        ];
    }
}
