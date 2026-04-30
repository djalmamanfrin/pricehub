<?php

namespace App\Domain\Matching\Product;

use App\Domain\Matching\DTO\ParsedInput;
use App\Domain\Product\ProductMatchResult;
use App\Models\Product;

readonly class ProductMatchingPipeline
{
    public function __construct(
        private HardMatchResolver $hard,
        private CandidateFinder $candidates,
        private RankingEngine $ranking
    ) {}

    public function match(ParsedInput $input): ProductMatchResult
    {
        // 1. HARD MATCH
        if ($result = $this->hard->resolve($input)) {
            return $result;
        }

        // 2. SOFT MATCH (candidates)
        $candidates = $this->candidates->find($input);
        if ($candidates->isEmpty()) {
            $product = Product::create($input->toArray());
            return ProductMatchResult::make($product->id, 0);
        }

        // 3. RANKING
        return $this->ranking->rank($input, $candidates);
    }
}
