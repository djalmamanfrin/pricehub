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

    $original ??= '';
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

function parsedName(
    string $original,
    ?string $normalized = null,
): ParsedInput {

    return parsed(
        original: $original,
        normalized: $normalized
    );
}

function parsedBarcode(
    string $barcode,
    string $original = 'Produto Teste',
): ParsedInput {

    return parsed(
        original: $original,
        barcode: $barcode
    );
}

function parsedBrand(
    string $original,
    int $brandId,
    ?string $normalized = null,
): ParsedInput {

    return parsed(
        original: $original,
        normalized: $normalized,
        brandId: $brandId
    );
}

function parsedCategory(
    string $original,
    int $categoryId,
    ?string $normalized = null,
): ParsedInput {

    return parsed(
        original: $original,
        normalized: $normalized,
        categoryId: $categoryId
    );
}

function parsedUnit(
    string $original,
    int $unitTypeId,
    ?string $normalized = null,
): ParsedInput {

    return parsed(
        original: $original,
        normalized: $normalized,
        unitTypeId: $unitTypeId
    );
}

function parsedVolume(
    string $original,
    int $baseUnitId,
    int $quantity,
    ?string $normalized = null,
): ParsedInput {

    return parsed(
        original: $original,
        normalized: $normalized,
        baseUnitId: $baseUnitId,
        quantity: $quantity
    );
}

function parsedPack(
    string $original,
    int $packSize,
    ?string $normalized = null,
): ParsedInput {

    return parsed(
        original: $original,
        normalized: $normalized,
        packSize: $packSize
    );
}
