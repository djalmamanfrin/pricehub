<?php

namespace App\Services\Product;

use App\Models\Brand;
use App\Models\Category;
use App\Models\UnitType;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * TODO
 * str_contains: futuramente ajustar para longest match primeiro (ex: "coca cola" antes de "coca")
 * volume mais complexo: Ainda não cobre:1.5L 350 ml pack 6
 * Category ainda simples: hoje depende de normalized_name. No futuro: tabela de regras e relação com brand
 */
class ProductAttributeParser
{
    /*
    |--------------------------------------------------------------------------
    | PUBLIC API
    |--------------------------------------------------------------------------
    */

    public function parse(string $text): array
    {
        $normalized = $this->normalize($text);

        return [
            'normalized'   => $normalized,
            'brand_id'     => $this->extractBrandId($normalized),
            'category_id'  => $this->extractCategoryId($normalized),
            'unit_type_id' => $this->extractUnitTypeId($normalized),
            'volume_ml'    => $this->extractVolumeMl($normalized),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | NORMALIZATION
    |--------------------------------------------------------------------------
    */

    public function normalize(string $text): string
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

    /*
    |--------------------------------------------------------------------------
    | SYNONYMS (DB + CACHE)
    |--------------------------------------------------------------------------
    */

    private function applySynonyms(string $text): string
    {
        $synonyms = $this->getSynonyms();

        // ordenar por tamanho do termo (maiores primeiro)
        uksort($synonyms, fn ($a, $b) => strlen($b) <=> strlen($a));

        foreach ($synonyms as $term => $synonym) {
            $normalized = $synonym['normalized'];

            // replace seguro (evita substituir dentro de palavras)
            $text = preg_replace(
                '/\b' . preg_quote($term, '/') . '\b/',
                $normalized,
                $text
            );
        }

        return $text;
    }

    private function getSynonyms(): array
    {
        return Cache::remember('synonyms', now()->addHours(6), function () {
            return \App\Models\Synonym::all()
                ->groupBy('term')
                ->map(fn ($items) => $items->first()->toArray())
                ->toArray();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | BRAND
    |--------------------------------------------------------------------------
    */

    public function extractBrandId(string $text): ?int
    {
        $brands = $this->getBrands();

        foreach ($brands as $brand) {
            if (str_contains($text, $brand['normalized_name'])) {
                return $brand['id'];
            }
        }

        return null;
    }

    private function getBrands(): array
    {
        return Cache::remember('brands', now()->addHours(6), function () {
            return Brand::all()->map(fn ($b) => [
                'id' => $b->id,
                'normalized_name' => $b->normalized_name
            ])->toArray();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | CATEGORY
    |--------------------------------------------------------------------------
    */

    public function extractCategoryId(string $text): ?int
    {
        $categories = $this->getCategories();

        foreach ($categories as $category) {
            if (str_contains($text, $category['normalized_name'])) {
                return $category['id'];
            }
        }

        return null;
    }

    private function getCategories(): array
    {
        return Cache::remember('categories', now()->addHours(6), function () {
            return Category::all()->map(fn ($c) => [
                'id' => $c->id,
                'normalized_name' => $c->normalized_name
            ])->toArray();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | UNIT TYPE
    |--------------------------------------------------------------------------
    */

    public function extractUnitTypeId(string $text): ?int
    {
        $types = $this->getUnitTypes();

        foreach ($types as $type) {
            if (str_contains($text, $type['normalized_name'])) {
                return $type['id'];
            }
        }

        return null;
    }

    private function getUnitTypes(): array
    {
        return Cache::remember('unit_types', now()->addHours(6), function () {
            return UnitType::all()->map(fn ($t) => [
                'id' => $t->id,
                'normalized_name' => $t->normalized_name
            ])->toArray();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | VOLUME
    |--------------------------------------------------------------------------
    */

    public function extractVolumeMl(string $text): ?int
    {
        // 350ml
        if (preg_match('/(\d+)\s?(ml)/i', $text, $m)) {
            return (int) $m[1];
        }

        // 2l, 2 litros
        if (preg_match('/(\d+)\s?(l|litro|litros)/i', $text, $m)) {
            return (int) $m[1] * 1000;
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | TOKENIZATION (opcional futuro)
    |--------------------------------------------------------------------------
    */

    public function tokens(string $text): array
    {
        return explode(' ', $this->normalize($text));
    }
}
