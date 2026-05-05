<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\ParsedInput;
use App\Models\Product;
use App\Models\Synonym;
use Illuminate\Support\Facades\Cache;

readonly class SynonymScorer implements ScorerInterface
{
    public function __construct(
        private ParsedInput $input,
        private Product $product
    ) {}
    public function score(): array
    {
        $score = $this->synonymSimilarity($this->input->normalized, $this->product->normalized_name);

        return [
            'score' => $score,
            'rule' => 'synonym_similarity'
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
