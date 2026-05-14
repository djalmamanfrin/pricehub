<?php

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\DTO\ParsedInput;
use App\Models\Product;
use App\Models\Synonym;
use Illuminate\Support\Facades\Cache;

class TokenScorer extends AbstractScorer
{
    private array $typeWeights = [
        'brand' => 30,
        'category' => 25,
        'flavor' => 15,
        'unit' => 15,
        'volume' => 15,
    ];

    public function apply(ParsedInput $input, Product $product): self
    {
        $tokensA = $this->tokenizeWithClassification($input->normalized);
        $tokensB = $this->tokenizeWithClassification($product->normalized_name);

        $groupA = $this->groupByType($tokensA);
        $groupB = $this->groupByType($tokensB);

        $score = $this->matchByCoverage($groupA, $groupB);

        $this->setValue((int) round($score));
        $this->setRule('token_similarity');

        return $this;
    }

    private function matchByCoverage(array $a, array $b): float
    {
        $score = 0;
        $scoreStep = [];

        foreach ($this->typeWeights as $type => $weight) {

            $tokensA = $this->uniqueByNormalized($a[$type] ?? []);
            $tokensB = $this->uniqueByNormalized($b[$type] ?? []);

            $mapA = $this->mapTokenWeights($tokensA);
            $mapB = $this->mapTokenWeights($tokensB);

            $intersection = array_intersect(
                array_keys($mapA),
                array_keys($mapB)
            );

            if (empty($intersection)) {
                continue;
            }

            $coverage = count($intersection) / count($mapA);

            $score += $coverage * $weight;
            $scoreStep[] = sprintf(
                    "[%s] coverage=%.2f weight=%d total=%.2f",
                    $type,
                    $coverage,
                    $weight,
                    $score
                ) . PHP_EOL;
        }
//        dd($scoreStep);

        return $score;
    }

    private function tokenizeWithClassification(string $text): array
    {
        $tokens = explode(' ', $text);
        $synonyms = $this->getSynonyms();

        $result = [];

        foreach ($tokens as $token) {
            if ($parsed = $this->parseMeasurementToken($token)) {
                $result[] = $parsed;
                continue;
            }

            if (isset($synonyms[$token])) {

                $best = collect($synonyms[$token])
                    ->sortByDesc('weight')
                    ->first();

                $result[] = [
                    'value' => $token,
                    'normalized' => $best['normalized'],
                    'type' => $best['type'],
                    'weight' => $best['weight'],
                ];

                continue;
            }

            $result[] = [
                'value' => $token,
                'normalized' => $token,
                'type' => 'generic',
                'weight' => 1,
            ];
        }

        return $result;
    }

    private function parseMeasurementToken(string $token): ?array
    {
        if (!preg_match('/^(\d+)(ml|l|g|kg)$/i', $token, $matches)) {
            return null;
        }

        $quantity = (int) $matches[1];
        $unit = strtolower($matches[2]);

        $type = match ($unit) {
            'ml', 'l' => 'volume',
            'g', 'kg' => 'weight',
            default => 'generic'
        };

        return [
            'value' => $token,
            'normalized' => $token,
            'type' => $type,
            'weight' => 2,
            'quantity' => $quantity,
            'unit' => $unit,
        ];
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
            if (
                !isset($unique[$key])
                || $token['weight'] > $unique[$key]['weight']
            ) {
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
        return Cache::remember(
            'synonyms_indexed',
            now()->addHours(6),
            function () {

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
            }
        );
    }
}
