<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\FeatureVector;

class VolumeScorer implements ScorerInterface
{
    public function score(FeatureVector $features): float
    {
        if ($features->get('volume_match')) {
            return 40;
        }

        if ($features->get('volume_diff') > 0) {
            return -60;
        }

        return 0;
    }
}
