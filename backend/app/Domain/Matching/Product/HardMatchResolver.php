<?php

namespace App\Domain\Matching\Product;

use App\Domain\Matching\DTO\ParsedInput;
use App\Domain\Product\ProductMatchResult;
use App\Models\Product;

class HardMatchResolver
{
    public function resolve(ParsedInput $input): ?ProductMatchResult
    {
        if ($input->barcode) {
            $product = Product::where('barcode', $input->barcode)->first();
            if ($product) {
                return ProductMatchResult::make($product->id, 100);
            }
        }

        $existing = Product::where('normalized_name', $input->normalized)->first();
        if ($existing) {
            return ProductMatchResult::make($existing->id, 90);
        }

        return null;
    }
}
