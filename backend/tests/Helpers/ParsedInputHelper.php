<?php

use App\Domain\Matching\DTO\ParsedInput;

function parsed(
    ?string $original = null,
    ?string $normalized = null,
    ?int $brandId = null,
    ?int $categoryId = null,
    ?int $unitTypeId = null,
    ?int $baseUnitId = null,
    ?int $packSize = null,
    ?int $quantity = null,
    ?string $barcode = null,
): ParsedInput {

    $original ??= 'Produto Teste';
    $normalized ??= strtolower($original);

    return new ParsedInput(
        original: $original,
        normalized: $normalized,
        brandId: $brandId,
        categoryId: $categoryId,
        unitTypeId: $unitTypeId,
        baseUnitId: $baseUnitId,
        packSize: $packSize,
        quantity: $quantity,
        barcode: $barcode,
    );
}
