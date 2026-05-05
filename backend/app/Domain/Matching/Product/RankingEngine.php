<?php

namespace App\Domain\Matching\Product;

use App\Domain\Matching\DTO\ParsedInput;
use App\Domain\Matching\FeatureExtractor;
use App\Domain\Matching\Scoring\CompositeScorer;
use App\Domain\Product\ProductMatchResult;
use Illuminate\Support\Collection;

class RankingEngine
{
    public function __construct(
        private readonly CompositeScorer $composite,
    ) {}
    public function rank(ParsedInput $input, Collection $candidates): ProductMatchResult
    {
        $best = null;
        $bestScore = 0;
        $bestBreakdown = [];

        foreach ($candidates as $product) {
            $result = $this->composite->score($input, $product);

            if ($result['score'] > $bestScore) {
                $best = $product;
                $bestScore = $result['score'];
                $bestBreakdown = $result['rule'];
            }
        }

        return ProductMatchResult::make(
            $best->id,
            $bestScore,
            $bestBreakdown
        );
    }
}
