<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\ParsedInput;
use App\Models\Product;

interface ScorerInterface
{
    public function apply(ParsedInput $input, Product $product): self;
    public function getValue(): int;
    public function getRule(): string;
}
