<?php

use App\Domain\Matching\Scoring\BarcodeScorer;
use App\Domain\Matching\Scoring\BaseUnitScorer;
use App\Domain\Matching\Scoring\BrandScorer;
use App\Domain\Matching\Scoring\PackSizeScorer;
use App\Domain\Matching\Scoring\TokenScorer;
use App\Domain\Matching\Scoring\UnitTypeScorer;

return [
    'scorers' => [
        BarcodeScorer::class,
        BrandScorer::class,
        BaseUnitScorer::class,
        UnitTypeScorer::class,
        TokenScorer::class,
        PackSizeScorer::class
    ],
];
