<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\FeatureVector;

interface ScorerInterface
{
    public function score(FeatureVector $features): array;
}
