<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\ParsedInput;
use App\Models\Product;

class BaseUnitScorer extends AbstractScorer
{
    public function apply(ParsedInput $input, Product $product): self
    {
        if (is_null($input->baseUnitId)) {
            $this->setValue(0);
            $this->setRule('base_unit_unknown');

            return $this;
        }
        if ($input->baseUnitId === $product->base_unit_id) {
            $this->setValue(20);
            $this->setRule('base_unit_match');

            return $this;
        }

        $this->setValue(-80);
        $this->setRule('base_unit_conflict');
        return $this;
    }
}
