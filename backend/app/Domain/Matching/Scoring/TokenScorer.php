<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\FeatureVector;

class TokenScorer implements ScorerInterface
{
    public function score(FeatureVector $features): float
    {
        return ($features->get('token_overlap') ?? 0) * 5;
    }
}
