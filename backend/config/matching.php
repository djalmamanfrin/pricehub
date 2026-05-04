<?php

use App\Domain\Matching\Scoring\BarcodeScorer;
use App\Domain\Matching\Scoring\BrandScorer;
use App\Domain\Matching\Scoring\SynonymScorer;
use App\Domain\Matching\Scoring\TokenScorer;

return [
    'scorers' => [
        BarcodeScorer::class,
        BrandScorer::class,
        SynonymScorer::class,
        TokenScorer::class,
    ],
];
