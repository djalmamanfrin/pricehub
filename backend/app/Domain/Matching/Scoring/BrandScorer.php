<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\ParsedInput;
use App\Models\Product;

class BrandScorer extends AbstractScorer
{

    public function apply(ParsedInput $input, Product $product): self
    {
        if (is_null($input->brandId)) {
            $this->setValue(0);
            $this->setRule('brand_unknown');

            return $this;
        }
        if ($input->brandId === $product->brand_id) {
            $this->setValue(20);
            $this->setRule('brand_match');

            return $this;
        }

        $this->setValue(-80);
        $this->setRule('brand_conflict');

        return $this;
    }
}
