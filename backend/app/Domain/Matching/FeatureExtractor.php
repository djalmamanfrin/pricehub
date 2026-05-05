<?php

namespace App\Domain\Matching;

use App\Domain\Matching\DTO\ParsedInput;
use App\Domain\Matching\Scoring\BarcodeScorer;
use App\Domain\Matching\Scoring\BrandScorer;
use App\Domain\Matching\Scoring\PackSizeScorer;
use App\Domain\Matching\Scoring\SynonymScorer;
use App\Domain\Matching\Scoring\UnitTypeScorer;
use App\Models\Product;

class FeatureExtractor
{
    public function extract(ParsedInput $input, Product $product): array
    {
        $breakdown = [];
        foreach ($this->features($input, $product) as $feature) {
            $breakdown[] = $feature->score();
        }
        return $breakdown;
//
//        // Penalty
//        $feature->add(VolumePenalty::NAME_DIFF,
//            abs(($input->volumeMl ?? 0) - ($product->volume_ml ?? 0))
//        );
    }

    private function features(ParsedInput $input, Product $product): array
    {
        return [
            new BarcodeScorer($input, $product),
            new BrandScorer($input, $product),
            new SynonymScorer($input, $product),
            new UnitTypeScorer($input, $product),
            new PackSizeScorer($input, $product),
//            new VolumeDifferenceFeature($input, $product),
        ];
    }
}
