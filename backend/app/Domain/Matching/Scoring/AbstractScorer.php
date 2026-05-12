<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\ParsedInput;
use App\Models\Product;

abstract class AbstractScorer implements ScorerInterface
{
    private float $value = 0;
    private string $rule = '';
    abstract public function apply(ParsedInput $input, Product $product): self;

    protected function setValue(float $value): void
    {
        $this->value = $value;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    protected function setRule(string $rule): void
    {
        $this->rule = $rule;
    }

    public function getRule(): string
    {
        return $this->rule;
    }
}
