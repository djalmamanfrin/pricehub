<?php

use App\Domain\Matching\Scoring\BaseUnitScorer;
use App\Domain\Matching\Scoring\BrandScorer;
use App\Domain\Matching\Scoring\PackSizeScorer;
use App\Domain\Matching\Scoring\TokenScorer;
use App\Domain\Matching\Scoring\UnitTypeScorer;

return [
    'scorers' => [
        BrandScorer::class,
        BaseUnitScorer::class,
        UnitTypeScorer::class,
        TokenScorer::class,
        PackSizeScorer::class
    ],
];
