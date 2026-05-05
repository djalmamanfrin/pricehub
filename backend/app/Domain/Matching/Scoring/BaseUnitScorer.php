<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\ParsedInput;
use App\Models\Product;

readonly class BaseUnitScorer implements ScorerInterface
{
    public function __construct(
        private ParsedInput $input,
        private Product $product
    ) {}

    public function score(): array
    {
        if (is_null($this->input->baseUnitId)) {
            return ['rule' => 'base_unit_unknown', 'score' => 0];
        }
        if ($this->input->baseUnitId === $this->product->base_unit_id) {
            return ['rule' => 'base_unit_match', 'score' => 20];
        }

        return ['rule' => 'base_unit_conflict', 'score' => -80];
    }
}
