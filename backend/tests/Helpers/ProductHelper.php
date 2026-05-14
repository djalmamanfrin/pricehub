<?php

use App\Models\Product;

function product(
    ?string $name = null,
    ?string $normalized = null,
    ?string $barcode = null,
    ?int $brandId = null,
    ?int $categoryId = null,
    ?int $unitTypeId = null,
    ?int $baseUnitId = null,
    ?int $packSize = null,
    ?int $quantity = null,
    array $attributes = [],
): Product {

    $name ??= 'Produto Teste';
    $normalized ??= strtolower($name);

    return new Product(array_merge([
        'name' => $name,
        'normalized_name' => $normalized,
        'barcode' => $barcode,
        'brand_id' => $brandId,
        'category_id' => $categoryId,
        'unit_type_id' => $unitTypeId,
        'base_unit_id' => $baseUnitId,
        'pack_size' => $packSize,
        'quantity' => $quantity,
    ], $attributes));
}

function productName(
    string $normalized,
): Product {

    return product(
        normalized: strtolower($normalized)
    );
}

function productBarcode(
    string $barcode,
    string $name = 'Produto Teste',
): Product {

    return product(
        name: $name,
        barcode: $barcode
    );
}

function productBrand(
    string $name,
    int $brandId,
    ?string $normalized = null,
): Product {

    return product(
        name: $name,
        normalized: $normalized,
        brandId: $brandId
    );
}

function productCategory(
    string $name,
    int $categoryId,
    ?string $normalized = null,
): Product {

    return product(
        name: $name,
        normalized: $normalized,
        categoryId: $categoryId
    );
}

function productUnit(
    string $name,
    int $unitTypeId,
    ?string $normalized = null,
): Product {

    return product(
        name: $name,
        normalized: $normalized,
        unitTypeId: $unitTypeId
    );
}

function productVolume(
    string $name,
    int $baseUnitId,
    int $quantity,
    ?string $normalized = null,
): Product {

    return product(
        name: $name,
        normalized: $normalized,
        baseUnitId: $baseUnitId,
        quantity: $quantity
    );
}

function productPack(
    string $name,
    int $packSize,
    ?string $normalized = null,
): Product {

    return product(
        name: $name,
        normalized: $normalized,
        packSize: $packSize
    );
}
