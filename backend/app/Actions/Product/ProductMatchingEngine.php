<?php

namespace App\Actions\Product;

use App\Domain\Matching\DTO\ParsedInput;
use App\Domain\Matching\FeatureExtractor;
use App\Domain\Matching\Scoring\CompositeScorer;
use App\Domain\Product\ProductMatchResult;
use App\Models\Product;
use App\Models\Synonym;
use App\Services\Product\ProductAttributeParser;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use InvalidArgumentException;

class ProductMatchingEngine
{
    public function __construct(
        private FeatureExtractor $extractor,
        private CompositeScorer $scorer,
        private ProductAttributeParser $parser
    ) {}

    public function match(array $data): Product
    {
        $parsed = new ParsedInput(
            original: $data['product_name'],
            normalized: $this->parser->normalize($data['product_name']),
            brandId: $this->parser->extractBrandId($data['product_name']),
            categoryId: $this->parser->extractCategoryId($data['product_name']),
            unitTypeId: $this->parser->extractUnitTypeId($data['product_name']),
            volumeMl: $this->parser->extractVolumeMl($data['product_name']),
            barcode: $data['barcode'] ?? null
        );

        // 1. Barcode match (hard match)
        if ($parsed->barcode) {
            $existing = Product::where('barcode', $parsed->barcode)->first();

            if ($existing) {
                $existing->match_score = 100;
                return $existing;
            }
        }

        // 2. Nome exato
        $existing = Product::where('normalized_name', $parsed->normalized)->first();

        if ($existing) {
            $existing->match_score = 90;
            return $existing;
        }

        // 3. Matching com score
        $candidates = $this->findCandidates($parsed->normalized);

        $best = null;
        $bestScore = -INF;

        foreach ($candidates as $product) {
            $features = $this->extractor->extract($parsed, $product);
            $score = $this->scorer->score($features);

            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $product;
                $best->match_score = $score;
            }
        }

        if ($bestScore >= 80) {
            return $best;
        }

        return Product::create([
            'name' => $parsed->original,
            'normalized_name' => $parsed->normalized,
            'barcode' => $parsed->barcode,

            'brand_id' => $parsed->brandId,
            'category_id' => $parsed->categoryId,
            'unit_type_id' => $parsed->unitTypeId,
        ]);
    }

    private function findCandidates(string $normalizedName): Collection
    {
        $query = Product::query();
        $tokens = explode(' ', $normalizedName);
        foreach ($tokens as $token) {
            $query->orWhere('normalized_name', 'like', "%{$token}%");
        }
        return $query->limit(50)->get();
    }
}
