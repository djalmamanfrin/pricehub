<?php

namespace App\Actions\Product;

use App\Domain\Product\ProductMatchResult;
use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;

class ProductMatchingEngine
{
    public function match(array $data): ProductMatchResult
    {
        $productName = Arr::get($data, 'product_name')
            ?? throw new InvalidArgumentException('Product name must be provided');

        $normalizedName = $this->normalize($productName);
        $candidates = $this->findSimilar($normalizedName);

        $best = null;
        $bestScore = 0;

        foreach ($candidates as $candidate) {
            $score = $this->calculateScore($data, $candidate);

            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $candidate;
            }
        }

        if ($best && $bestScore >= 80) {
            return new ProductMatchResult($best, $bestScore, false);
        }

        $product = Product::create([
            'name' => $data['product_name'],
            'normalized_name' => $normalizedName,
            'barcode' => $data['barcode'] ?? null
        ]);

        return new ProductMatchResult($product, 0, true);
    }

    private function applySynonyms(string $text): string
    {
        $synonyms = config('synonyms');

        $tokens = explode(' ', $text);

        $normalizedTokens = array_map(function ($token) use ($synonyms) {
            return $synonyms[$token] ?? $token;
        }, $tokens);

        return implode(' ', $normalizedTokens);
    }

    private function calculateScore(array $data, Product $product): int
    {
        $score = 0;

        // 1. Barcode
        if (!empty($data['barcode']) && $product->barcode === $data['barcode']) {
            $score += 100;
        }

        // 2. Nome (similaridade)
        $input = $this->normalize($data['product_name']);
        $target = $product->normalized_name;

        similar_text($input, $target, $percent);
        $score += ($percent * 0.4); // até 40 pontos

        // 3. Volume
        $inputVol = $this->extractVolume($data['product_name']);
        $targetVol = $this->extractVolume($product->name);

        if ($inputVol && $targetVol) {
            if ($inputVol === $targetVol) {
                $score += 40;
            } else {
                $score -= 40; // penalização forte
            }
        }

        // 4. Tokens (palavras em comum)
        $score += $this->tokenScore($input, $target);

        return (int) $score;
    }

    private function extractVolume(string $text): ?string
    {
        if (preg_match('/(\d+)\s?(ml|l)/i', $text, $matches)) {
            return strtolower($matches[1] . $matches[2]);
        }

        return null;
    }

    private function tokenScore(string $a, string $b): int
    {
        $tokensA = explode(' ', $this->applySynonyms($a));
        $tokensB = explode(' ', $this->applySynonyms($b));

        $intersection = array_intersect($tokensA, $tokensB);

        return count($intersection) * 5;
    }

    private function normalize(string $text): string
    {
        $normalized = Str::of($text)
            ->lower()
            ->ascii()
            ->replace(['-', '_', '/', ','], ' ')
            ->replaceMatches('/[^a-z0-9\s]/', '')
            ->replaceMatches('/\s+/', ' ')
            ->trim()
            ->toString();

        return $this->applySynonyms($normalized);
    }

    private function findSimilar(string $normalizedName): Collection
    {
        $query = Product::query();
        $tokens = explode(' ', $normalizedName);
        foreach ($tokens as $token) {
            $query->orWhere('normalized_name', 'like', "%{$token}%");
        }
        return $query->limit(50)->get();
    }
}
