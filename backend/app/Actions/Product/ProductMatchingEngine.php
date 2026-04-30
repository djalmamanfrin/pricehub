<?php

namespace App\Actions\Product;

use App\Domain\Matching\DTO\ParsedInput;
use App\Domain\Matching\FeatureExtractor;
use App\Domain\Matching\Scoring\CompositeScorer;
use App\Domain\Product\ProductMatchResult;
use App\Models\Product;
use App\Services\Product\ProductAttributeParser;
use Illuminate\Support\Collection;

class ProductMatchingEngine
{
    public function __construct(
        private FeatureExtractor $extractor,
        private CompositeScorer $scorer,
        private ProductAttributeParser $parser
    ) {}

    public function match(array $data): ProductMatchResult
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
                return ProductMatchResult::make($existing->id, 100, $existing->breakdown);
            }
        }

        // 2. Nome exato
        $existing = Product::where('normalized_name', $parsed->normalized)->first();
        if ($existing) {
            return ProductMatchResult::make($existing->id, 100, $existing->breakdown);
        }

        // 3. Matching com score
        $candidates = $this->findCandidates($parsed->normalized);

        $best = null;
        $bestScore = 0;
        $bestBreakdown = [];

        foreach ($candidates as $product) {
            $features = $this->extractor->extract($parsed, $product);
            $result = $this->scorer->score($features);
            $score = $result['score'];

            if ($score > $bestScore) {
                $best = $product;
                $bestScore = $score;
                $bestBreakdown = $result['breakdown'];
            }
        }

        if ($bestScore >= 80) {
            return ProductMatchResult::make($best->id, $bestScore, $bestBreakdown);
        }

        $product = Product::create([
            'name' => $parsed->original,
            'normalized_name' => $parsed->normalized,
            'barcode' => $parsed->barcode,
            'brand_id' => $parsed->brandId,
            'category_id' => $parsed->categoryId,
            'unit_type_id' => $parsed->unitTypeId,
        ]);

        return ProductMatchResult::make($product->id, $bestScore, $bestBreakdown);
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
