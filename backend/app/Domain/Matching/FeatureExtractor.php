<?php

namespace App\Domain\Matching;

use App\Domain\Matching\DTO\FeatureVector;
use App\Domain\Matching\DTO\ParsedInput;
use App\Domain\Matching\Scoring\BarcodeScorer;
use App\Domain\Matching\Scoring\BrandScorer;
use App\Domain\Matching\Scoring\SynonymScorer;
use App\Domain\Matching\Scoring\TokenScorer;
use App\Models\Product;
use App\Models\Synonym;
use Illuminate\Support\Facades\Cache;

class FeatureExtractor
{
    public function extract(ParsedInput $input, Product $product): FeatureVector
    {
        $feature = new FeatureVector();

        $feature->add(BarcodeScorer::MATCH,
            $input->barcode !== null && $input->barcode === $product->barcode
        );

        $feature->add(BrandScorer::MATCH,
            $input->brandId !== null && $input->brandId === $product->brand_id
        );

        $feature->add(SynonymScorer::SIMILARITY,
            $this->synonymSimilarity($input->normalized, $product->normalized_name));

        // =========================
        // 3. TOKEN FEATURES
        // =========================

        $tokensA = explode(' ', $input->normalized);
        $tokensB = explode(' ', $product->normalized_name);

        $intersection = array_intersect($tokensA, $tokensB);

        $feature->add(TokenScorer::OVERLAP, count($intersection));
        $feature->add(TokenScorer::TOTAL, count($tokensA));

        return $feature;
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
