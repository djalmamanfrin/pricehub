<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\FeatureVector;

class NameSimilarityScorer implements ScorerInterface
{
    const SIMILARITY = 'name_similarity';
    public function score(FeatureVector $features): array
    {
        // penalização forte
        $score = ($features->get(self::SIMILARITY) ?? 0) * 0.4;
        return [
            'score' => $score,
            'rule' => self::SIMILARITY
        ];
    }
}
