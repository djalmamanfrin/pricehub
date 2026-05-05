<?php

namespace App\Domain\Matching;

use App\Domain\Matching\DTO\FeatureVector;
use App\Domain\Matching\DTO\ParsedInput;
use App\Domain\Matching\Penalty\PackPenalty;
use App\Domain\Matching\Penalty\UnitTypePenalty;
use App\Domain\Matching\Penalty\VolumePenalty;
use App\Domain\Matching\Scoring\BarcodeScorer;
use App\Domain\Matching\Scoring\BrandScorer;
use App\Domain\Matching\Scoring\SynonymScorer;
use App\Domain\Matching\Scoring\TokenScorer;
use App\Models\Product;
use App\Models\Synonym;
use Illuminate\Support\Facades\Cache;

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
//        $feature->add(UnitTypePenalty::MATCH,
//            $input->unitTypeId && $product->unit_type_id === $input->unitTypeId
//        );
//        $feature->add(PackPenalty::PACK_SIZE_DIFF,
//            abs(($input->packSize ?? 1) - ($product->pack_size ?? 1))
//        );
    }

    private function features(ParsedInput $input, Product $product): array
    {
        return [
            new BarcodeScorer($input, $product),
            new BrandScorer($input, $product),
            new SynonymScorer($input, $product),
//            new VolumeDifferenceFeature($input, $product),
//            new UnitTypeMatchFeature($input, $product),
//            new PackSizeDifferenceFeature($input, $product),
        ];
    }
}
