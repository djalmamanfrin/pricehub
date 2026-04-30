<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\FeatureVector;

class BarcodeScorer implements ScorerInterface
{
    public function score(FeatureVector $features): float
    {
        return $features->get('barcode_match') ? 100 : 0;
    }
}
