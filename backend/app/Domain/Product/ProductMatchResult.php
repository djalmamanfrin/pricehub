<?php

namespace App\Domain\Product;

use App\Models\Product;

readonly class ProductMatchResult
{
    public function __construct(
        public Product $product,
        public int $score,
        public bool $isNew
    ) {
        logger()->info('ProductMatchResult', [
            'product' => $this->product,
            'score' => $score,
            'is_new' => $this->isNew,
        ]);
    }
}
