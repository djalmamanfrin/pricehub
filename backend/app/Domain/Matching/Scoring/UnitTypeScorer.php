<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\ParsedInput;
use App\Models\Product;

readonly class UnitTypeScorer implements ScorerInterface
{
    public function __construct(
        private ParsedInput $input,
        private Product $product
    ) {}
    public function score(): array
    {
        if (is_null($this->input->unitTypeId)) {
            return ['rule' => 'unit_type_unknown', 'score' => 0];
        }
        if ($this->input->unitTypeId === $this->product->unit_type_id) {
            return ['rule' => 'unit_type_match', 'score' => 20];
        }

        return ['rule' => 'unit_type_conflict', 'score' => -80];
    }
}
