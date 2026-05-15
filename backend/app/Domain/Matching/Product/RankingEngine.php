<?php

namespace App\Domain\Matching\Product;

use App\Domain\Matching\DTO\ParsedInput;
use App\Domain\Matching\Scoring\CompositeScorer;
use App\Domain\Product\ProductMatchResult;
use Illuminate\Support\Collection;

readonly class RankingEngine
{
    public function __construct(
        private CompositeScorer $composite,
    ) {}
    public function rank(ParsedInput $input, Collection $candidates): ProductMatchResult
    {
        $best = null;
        $bestScore = PHP_INT_MIN;
        $bestBreakdown = [];

        foreach ($candidates as $product) {
            $result = $this->composite->score($input, $product);

            if ($result['score'] > $bestScore) {
                $best = $product;
                $bestScore = $result['score'];
                $bestBreakdown = $result['breakdown'];
            }
        }

        return ProductMatchResult::make(
            $best->id,
            $bestScore,
            $bestBreakdown
        );
    }
}
