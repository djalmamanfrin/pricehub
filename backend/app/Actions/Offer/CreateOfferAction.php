<?php

namespace App\Actions\Offer;

use App\Actions\Product\ProductMatchingEngine;
use App\Domain\Matching\DTO\ParsedInput;
use App\Domain\Matching\Product\ProductMatchingPipeline;
use App\Models\Market;
use App\Models\Offer;
use App\Services\Product\ProductAttributeParser;

readonly class CreateOfferAction
{
    public function __construct(
        private ProductAttributeParser $parser,
        private ProductMatchingPipeline $pipeline
    ) {}

    public function __invoke(array $data): Offer
    {
        $parsedInput = new ParsedInput(
            original: $data['product_name'],
            normalized: $this->parser->normalize($data['product_name']),
            brandId: $this->parser->extractBrandId($data['product_name']),
            categoryId: $this->parser->extractCategoryId($data['product_name']),
            unitTypeId: $this->parser->extractUnitTypeId($data['product_name']),
            volumeMl: $this->parser->extractVolumeMl($data['product_name']),
            barcode: $data['barcode'] ?? null
        );

        $result = $this->pipeline->match($parsedInput);

        $market = Market::firstOrCreate([
            'name' => $this->normalize($data['market_name'])
        ]);

        return Offer::updateOrCreate(
            [
                'product_id' => $result->productId,
                'market_id' => $market->id,
            ],
            [
                'price' => $data['price'],
                'score' => $result->score,
                'breakdown' => $result->breakdown,
                'collected_at' => now()
            ]
        );
    }

    private function normalize(string $text): string
    {
        return strtolower(trim($text));
    }
}
