<?php

namespace App\Domain\Matching\Product;

use App\Domain\Matching\DTO\ParsedInput;
use App\Domain\Matching\Scoring\BarcodeScorer;
use App\Domain\Matching\Scoring\NameSimilarityScorer;
use App\Domain\Product\ProductMatchResult;
use App\Models\Product;

class HardMatchResolver
{
    public function resolve(ParsedInput $input): ?ProductMatchResult
    {
        if ($input->barcode) {
            $product = Product::where('barcode', $input->barcode)->first();
            if ($product) {
                $score = 100;
                return ProductMatchResult::make(
                    $product->id,
                    $score,
                    ['rule' => BarcodeScorer::MATCH, 'score' => $score]
                );
            }
        }

        $existing = Product::where('normalized_name', $input->normalized)->first();
        if ($existing) {
            $score = 90;
            return ProductMatchResult::make(
                $existing->id,
                $score,
                ['rule' => NameSimilarityScorer::SIMILARITY, 'score' => $score]
            );
        }

        return null;
    }
}
