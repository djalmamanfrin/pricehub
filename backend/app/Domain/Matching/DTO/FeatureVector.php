<?php

namespace App\Domain\Matching\DTO;

class FeatureVector
{
    public array $features = [];

    public function add(string $name, mixed $value): void
    {
        $this->features[$name] = $value;
    }

    public function get(string $name): mixed
    {
        return $this->features[$name] ?? null;
    }

    public function all(): array
    {
        return $this->features;
    }
}
