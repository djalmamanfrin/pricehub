<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\FeatureVector;

class NameSimilarityScorer implements ScorerInterface
{
    public function score(FeatureVector $features): float
    {
        return ($features->get('name_similarity') ?? 0) * 0.4;
    }
}
