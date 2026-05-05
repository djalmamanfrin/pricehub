<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\ParsedInput;
use App\Models\Product;

readonly class PackSizeScorer implements ScorerInterface
{
    public function __construct(
        private ParsedInput $input,
        private Product $product
    ) {}

    public function score(): array
    {
        if (is_null($this->input->packSizeId)) {
            return ['rule' => 'pack_size_unknown', 'score' => 0];
        }
        if ($this->input->packSizeId === $this->product->pack_size_id) {
            return ['rule' => 'pack_size_match', 'score' => 20];
        }

        return ['rule' => 'pack_size_conflict', 'score' => -80];
    }
}
