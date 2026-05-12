<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\ParsedInput;
use App\Models\Product;
use App\Models\Synonym;
use Illuminate\Support\Facades\Cache;

/**
 * TODO
 * O próximo salto grande será: ranking normalization
 * E depois: candidate retrieval otimizado
 */
class TokenScorer extends AbstractScorer
{
    private array $typeWeights = [
        'brand' => 8,
        'product' => 7,
        'flavor' => 4,
        'unit' => 3,
        'volume' => 8,
        'generic' => 1,
    ];

    public function apply(ParsedInput $input, Product $product): self
    {
        $tokensA = $this->tokenizeWithClassification($input->normalized);
        $tokensB = $this->tokenizeWithClassification($product->normalized_name);

        $groupA = $this->groupByType($tokensA);
        $groupB = $this->groupByType($tokensB);

        $score = $this->matchByType($groupA, $groupB);

        $this->setValue($score);
        $this->setRule('token_similarity');

        return $this;
    }

    private function matchByType(array $a, array $b): float
    {
        $score = 0;

        foreach ($this->typeWeights as $type => $weight) {

            $tokensA = $this->uniqueByNormalized($a[$type] ?? []);
            $tokensB = $this->uniqueByNormalized($b[$type] ?? []);

            if (empty($tokensA) || empty($tokensB)) {

                $inputHasType = !empty($tokensA);
                $productHasType = !empty($tokensB);

                // penaliza somente quando input possui
                // e produto não possui
                if (
                    $inputHasType &&
                    !$productHasType &&
                    in_array($type, ['brand', 'volume', 'unit'])
                ) {
                    $score -= 10 * $weight;
                }

                continue;
            }

            // mapas: normalized => weight
            $mapA = $this->mapTokenWeights($tokensA);
            $mapB = $this->mapTokenWeights($tokensB);

            $intersectionKeys = array_intersect(array_keys($mapA), array_keys($mapB));
            $unionKeys = array_unique(array_merge(array_keys($mapA), array_keys($mapB)));

            if (empty($unionKeys)) {
                continue;
            }

            // Jaccard ponderado
            $intersectionWeight = 0;
            foreach ($intersectionKeys as $key) {
                $intersectionWeight += min($mapA[$key], $mapB[$key]);
            }

            $unionWeight = 0;
            foreach ($unionKeys as $key) {
                $unionWeight += max($mapA[$key] ?? 0, $mapB[$key] ?? 0);
            }

            $jaccard = $unionWeight > 0 ? $intersectionWeight / $unionWeight : 0;

            $score += $jaccard * $weight * 12;
        }

        return $score;
    }

    private function tokenizeWithClassification(string $text): array
    {
        $tokens = explode(' ', $text);
        $synonyms = $this->getSynonyms();

        $result = [];

        foreach ($tokens as $token) {

            if (isset($synonyms[$token])) {

                // pega o melhor synonym (maior peso)
                /**
                 * TODO
                 * Transformar token em:
                 *
                 * [
                 *      classifications => []
                 * ]
                 *
                 * Em vez de escolher uma só.
                 */
                $best = collect($synonyms[$token])
                    ->sortByDesc('weight')
                    ->first();

                $result[] = [
                    'value' => $token,
                    'normalized' => $best['normalized'],
                    'type' => $best['type'],
                    'weight' => $best['weight']
                ];

                continue;
            }

            $result[] = [
                'value' => $token,
                'normalized' => $token,
                'type' => 'generic',
                'weight' => 1
            ];
        }

        return $result;
    }

    private function groupByType(array $tokens): array
    {
        $grouped = [];

        foreach ($tokens as $token) {
            $grouped[$token['type']][] = $token;
        }

        return $grouped;
    }

    private function uniqueByNormalized(array $tokens): array
    {
        $unique = [];

        foreach ($tokens as $token) {
            $key = $token['normalized'];

            // mantém o maior peso caso duplicado
            if (!isset($unique[$key]) || $token['weight'] > $unique[$key]['weight']) {
                $unique[$key] = $token;
            }
        }

        return array_values($unique);
    }

    private function mapTokenWeights(array $tokens): array
    {
        $map = [];

        foreach ($tokens as $token) {
            $map[$token['normalized']] = $token['weight'];
        }

        return $map;
    }

    private function getSynonyms(): array
    {
        return Cache::remember('synonyms_indexed', now()->addHours(6), function () {
            return Synonym::all()
                ->groupBy('term')
                ->map(function ($items) {
                    return $items->map(fn ($s) => [
                        'normalized' => $s->normalized,
                        'weight' => (float) $s->weight,
                        'type' => $s->type,
                    ])->toArray();
                })
                ->toArray();
        });
    }
}
