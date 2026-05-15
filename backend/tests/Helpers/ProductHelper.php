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
): Product {

    $name ??= 'Produto Teste';
    $normalized ??= strtolower($name);

    return new Product([
        'name' => $name,
        'normalized_name' => $normalized,
        'barcode' => $barcode,
        'brand_id' => $brandId,
        'category_id' => $categoryId,
        'unit_type_id' => $unitTypeId,
        'base_unit_id' => $baseUnitId,
        'pack_size' => $packSize,
        'quantity' => $quantity,
    ]);
}
