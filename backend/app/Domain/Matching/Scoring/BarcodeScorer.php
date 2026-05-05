<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\FeatureVector;
use App\Domain\Matching\DTO\ParsedInput;
use App\Models\Product;

class BarcodeScorer implements ScorerInterface
{
    public function __construct(
        private ParsedInput $input,
        private Product $product
    ) {}
    public function score(): array
    {
        if (is_null($this->input->barcode)) {
            return ['rule' => 'barcode_unknown', 'score' => 0];
        }
        if ($this->input->barcode === $this->product->barcode) {
            return ['rule' => 'barcode_match', 'score' => 100];
        }

        return ['rule' => 'barcode_conflict', 'score' => -80];
    }
}
