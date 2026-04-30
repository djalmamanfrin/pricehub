<?php

use App\Domain\Matching\Scoring\BarcodeScorer;
use App\Domain\Matching\Scoring\BrandScorer;
use App\Domain\Matching\Scoring\NameSimilarityScorer;
use App\Domain\Matching\Scoring\TokenScorer;

return [
    'scorers' => [
        BarcodeScorer::class,
        BrandScorer::class,
        NameSimilarityScorer::class,
        TokenScorer::class,
    ],
];
