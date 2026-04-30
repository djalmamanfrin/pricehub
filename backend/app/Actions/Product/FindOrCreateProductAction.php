<?php

namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Support\Str;

class FindOrCreateProductAction
{
    public function execute(array $data): Product
    {
        // 1. Barcode (prioridade máxima)
        if (!empty($data['barcode'])) {
            $product = Product::where('barcode', $data['barcode'])->first();

            if ($product) {
                return $product;
            }
        }

        $normalized = $this->normalize($data['product_name']);

        // 2. Match exato
        $product = Product::where('normalized_name', $normalized)->first();
        if ($product) {
            return $product;
        }

        // 3. Match aproximado (fuzzy)
        $product = $this->findSimilar($normalized);
        if ($product) {
            return $product;
        }

        return Product::create([
            'name' => $data['product_name'],
            'normalized_name' => $normalized,
            'barcode' => $data['barcode'] ?? null
        ]);
    }

    private function normalize(string $text): string
    {
        return Str::of($text)
            ->lower()
            ->ascii()
            ->replace(['-', '_'], ' ')
            ->replaceMatches('/\s+/', ' ')
            ->trim()
            ->toString();
    }

    private function findSimilar(string $normalized): ?Product
    {
        $candidates = Product::limit(50)->get();

        $best = null;
        $bestScore = 0;

        foreach ($candidates as $candidate) {
            similar_text($normalized, $candidate->normalized_name, $percent);

            if ($percent > 80 && $percent > $bestScore) {
                $best = $candidate;
                $bestScore = $percent;
            }
        }

        return $best;
    }
}
