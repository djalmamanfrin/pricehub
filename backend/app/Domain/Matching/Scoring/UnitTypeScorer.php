<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\ParsedInput;
use App\Models\Product;

class UnitTypeScorer extends AbstractScorer
{
    public function apply(ParsedInput $input, Product $product): self
    {
        if (is_null($input->unitTypeId)) {
            $this->setValue(0);
            $this->setRule('unit_type_unknown');

            return $this;
        }
        if ($input->unitTypeId === $product->unit_type_id) {
            $this->setValue(20);
            $this->setRule('unit_type_match');

            return $this;
        }

        $this->setValue(-80);
        $this->setRule('unit_type_conflict');

        return $this;
    }
}
