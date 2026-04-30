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
        private readonly FeatureExtractor $extractor,
        private readonly CompositeScorer $scorer,
    ) {}
    public function rank(ParsedInput $input, Collection $candidates): ProductMatchResult
    {
        $best = null;
        $bestScore = 0;
        $bestBreakdown = [];

        foreach ($candidates as $product) {
            $features = $this->extractor->extract($input, $product);
            $result = $this->scorer->score($features);

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
