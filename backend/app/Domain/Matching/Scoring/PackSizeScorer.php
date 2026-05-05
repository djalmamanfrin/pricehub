<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\ParsedInput;
use App\Models\Product;

class PackSizeScorer extends AbstractScorer
{
    public function apply(ParsedInput $input, Product $product): self
    {
        if (is_null($input->packSizeId)) {
            $this->setValue(0);
            $this->setRule('pack_size_unknown');

            return $this;
        }
        if ($input->packSizeId === $product->pack_size_id) {
            $this->setValue(20);
            $this->setRule('pack_size_match');

            return $this;
        }

        $this->setValue(-80);
        $this->setRule('pack_size_conflict');

        return $this;
    }
}
