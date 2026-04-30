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

    public function toArray(): array
    {
        return [
            'name' => $this->original,
            'normalized_name' => $this->normalized,
            'barcode' => $this->barcode,
            'brand_id' => $this->brandId,
            'category_id' => $this->categoryId,
            'unit_type_id' => $this->unitTypeId,
        ];
    }
}
