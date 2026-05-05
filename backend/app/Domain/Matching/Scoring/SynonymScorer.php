<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\ParsedInput;
use App\Models\Product;
use App\Models\Synonym;
use Illuminate\Support\Facades\Cache;

class SynonymScorer extends AbstractScorer
{
    public function apply(ParsedInput $input, Product $product): self
    {
        $score = $this->synonymSimilarity($input->normalized, $product->normalized_name);
        $this->setValue($score);
        $this->setRule('synonym_similarity');

        return $this;
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
