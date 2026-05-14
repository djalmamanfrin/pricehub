<?php

use App\Domain\Matching\DTO\ParsedInput;
use App\Models\Product;

function parsed(
    string $original,
    ?string $normalized = null
): ParsedInput {

    return new ParsedInput(
        original: $original,
        normalized: $normalized ?? strtolower($original),
        brandId: null,
        categoryId: null,
        unitTypeId: null,
        baseUnitId: null,
        packSize: null,
        quantity: null,
        barcode: null
    );
}

function product(
    string $normalized
): Product {

    return new Product([
        'normalized_name' => strtolower($normalized)
    ]);
}
