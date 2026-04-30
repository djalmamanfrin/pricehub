<?php

namespace App\Domain\Matching;

use App\Domain\Matching\DTO\FeatureVector;
use App\Domain\Matching\DTO\ParsedInput;
use App\Domain\Matching\Scoring\BarcodeScorer;
use App\Domain\Matching\Scoring\BrandScorer;
use App\Domain\Matching\Scoring\NameSimilarityScorer;
use App\Domain\Matching\Scoring\TokenScorer;
use App\Domain\Matching\Scoring\VolumeScorer;
use App\Models\Product;

class FeatureExtractor
{
    public function extract(ParsedInput $input, Product $product): FeatureVector
    {
        $feature = new FeatureVector();

        // =========================
        // 1. BOOLEAN FEATURES
        // =========================

        $feature->add(BarcodeScorer::MATCH,
            $input->barcode !== null && $input->barcode === $product->barcode
        );

        $feature->add(BrandScorer::MATCH,
            $input->brandId !== null && $input->brandId === $product->brand_id
        );

//        $feature->add(VolumeScorer::MATCH,
//            $input->volumeMl !== null && $input->volumeMl === $product->volume_ml
//        );

        // =========================
        // 2. NUMERIC FEATURES
        // =========================

        similar_text($input->normalized, $product->normalized_name, $percent);
        $feature->add(NameSimilarityScorer::SIMILARITY, $percent);

        // =========================
        // 3. TOKEN FEATURES
        // =========================

        $tokensA = explode(' ', $input->normalized);
        $tokensB = explode(' ', $product->normalized_name);

        $intersection = array_intersect($tokensA, $tokensB);

        $feature->add(TokenScorer::OVERLAP, count($intersection));
        $feature->add(TokenScorer::TOTAL, count($tokensA));

        // =========================
        // 4. DERIVED FEATURES
        // =========================

//        $feature->add(VolumeScorer::MISMATCH,
//            abs(($input->volumeMl ?? 0) - ($product->volume_ml ?? 0))
//        );

        return $feature;
    }
}
