<?php

namespace App\Domain\Matching;

use App\Domain\Matching\DTO\FeatureVector;
use App\Domain\Matching\DTO\ParsedInput;
use App\Models\Product;

class FeatureExtractor
{
    public function extract(ParsedInput $input, Product $product): FeatureVector
    {
        $feature = new FeatureVector();

        // Barcode
        $feature->add('barcode_match',
            $input->barcode && $product->barcode === $input->barcode
        );

        // Brand
        $feature->add('brand_match',
            $input->brandId && $product->brand_id === $input->brandId
        );

        // Volume
//        $feature->add('volume_match',
//            $input->volumeMl && $product->volume_ml === $input->volumeMl
//        );

        // Volume diff (mais avançado)
//        $feature->add('volume_diff',
//            abs(($input->volumeMl ?? 0) - ($product->volume_ml ?? 0))
//        );

        // Name similarity
        similar_text($input->normalized, $product->normalized_name, $percent);
        $feature->add('name_similarity', $percent);

        // Tokens
        $tokensA = explode(' ', $input->normalized);
        $tokensB = explode(' ', $product->normalized_name);

        $intersection = array_intersect($tokensA, $tokensB);

        $feature->add('token_overlap', count($intersection));
        $feature->add('token_total', count($tokensA));

        return $feature;
    }
}
