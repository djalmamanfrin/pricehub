<?php

namespace App\Domain\Product;

readonly class ProductMatchResult
{
    public function __construct(
        public ?int $productId,
        public int $score,
        public array $breakdown
    ) {}

    public static function make(?int $productId, int $score, array $breakdown): self
    {
        return new self($productId, $score, $breakdown);
    }

    public function toArray(): array
    {
        return [
            'product_id' => $this->productId,
            'score' => $this->score,
            'breakdown' => $this->breakdown,
        ];
    }
}
