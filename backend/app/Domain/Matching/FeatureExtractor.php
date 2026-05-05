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

//        $feature->add(SynonymScorer::SIMILARITY,
//            $this->synonymSimilarity($input->normalized, $product->normalized_name));
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
//            new SynonymSimilarityFeature($input, $product, $this->getSynonyms()),
//            new VolumeDifferenceFeature($input, $product),
//            new UnitTypeMatchFeature($input, $product),
//            new PackSizeDifferenceFeature($input, $product),
        ];
    }

    private function synonymSimilarity(string $input, string $target): float
    {
        $map = $this->getSynonyms();
        $tokensA = explode(' ', $input);
        $tokensB = explode(' ', $target);

        $score = 0;
        $max = count($tokensA);

        foreach ($tokensA as $token) {

            // match exato
            if (in_array($token, $tokensB)) {
                $score += 1;
                continue;
            }

            // match via synonym
            if (isset($map[$token])) {
                $normalized = $map[$token]['normalized'];
                $weight = $map[$token]['weight'];

                if (in_array($normalized, $tokensB)) {
                    $score += $weight;
                }
            }
        }

        return $max > 0 ? ($score / $max) * 100 : 0;
    }

    private function getSynonyms(): array
    {
        return Cache::remember('synonyms_flat', now()->addHours(6), function () {
            return Synonym::all()
                ->mapWithKeys(fn ($s) => [
                    $s->term => [
                        'normalized' => $s->normalized,
                        'weight' => (float) $s->weight,
                        'type' => $s->type,
                    ]
                ])
                ->toArray();
        });
    }
}
