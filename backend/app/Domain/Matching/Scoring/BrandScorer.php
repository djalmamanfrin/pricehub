<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\FeatureVector;

class BrandScorer implements ScorerInterface
{
    public function score(FeatureVector $features): float
    {
        // penalização forte
        return $features->get('brand_match') ? 30 : -80;
    }
}
