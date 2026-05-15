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
        public ?int $baseUnitId,
        public ?int $quantity,
        public ?string $packSize,
        public ?string $barcode
    ) {}

    public function toArray(): array
    {
        return [
            'brand_id' => $this->brandId,
            'category_id' => $this->categoryId,
            'unit_type_id' => $this->unitTypeId,
            'base_unit_id' => $this->baseUnitId,
            'name' => $this->original,
            'normalized_name' => $this->normalized,
            'quantity' => $this->quantity,
            'pack_size' => $this->packSize,
            'barcode' => $this->barcode,
        ];
    }
}
