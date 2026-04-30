<?php

namespace App\Domain\Matching\DTO;

class ParsedInput
{
    public function __construct(
        public string $original,
        public string $normalized,
        public ?int $brandId,
        public ?int $categoryId,
        public ?int $unitTypeId,
        public ?int $volumeMl,
        public ?string $barcode
    ) {}
}
