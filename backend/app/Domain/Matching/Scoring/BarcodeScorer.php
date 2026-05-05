<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\ParsedInput;
use App\Models\Product;

class BarcodeScorer extends AbstractScorer
{
    public function apply(ParsedInput $input, Product $product): self
    {
        if (is_null($input->barcode)) {
            $this->setValue(0);
            $this->setRule('barcode_unknown');

            return $this;
        }
        if ($input->barcode === $product->barcode) {
            $this->setValue(100);
            $this->setRule('barcode_match');
            return $this;
        }

        $this->setValue(-80);
        $this->setRule('barcode_conflict');

        return $this;
    }
}
