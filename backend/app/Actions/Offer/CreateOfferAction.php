<?php

namespace App\Actions\Offer;

use App\Actions\Product\ProductMatchingEngine;
use App\Models\Market;
use App\Models\Offer;

readonly class CreateOfferAction
{
    public function __construct(
        private ProductMatchingEngine $engine
    ) {}

    public function __invoke(array $data): Offer
    {
        $result = $this->engine->match($data);

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
