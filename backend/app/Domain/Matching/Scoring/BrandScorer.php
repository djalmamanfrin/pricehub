<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\ParsedInput;
use App\Models\Product;

readonly class BrandScorer implements ScorerInterface
{
    public function __construct(
        private ParsedInput $input,
        private Product $product
    ) {}

    public function score(): array
    {
        if (is_null($this->input->brandId)) {
            return ['rule' => 'brand_unknown', 'score' => 0];
        }
        if ($this->input->brandId === $this->product->brand_id) {
            return ['rule' => 'brand_match', 'score' => 20];
        }

        return ['rule' => 'brand_conflict', 'score' => -80];
    }
}
