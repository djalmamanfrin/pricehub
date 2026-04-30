<?php

namespace App\Actions\Offer;

use App\Actions\Product\FindOrCreateProductAction;
use App\Models\Market;
use App\Models\Offer;

class CreateOfferAction
{
    public function __construct(
        private readonly FindOrCreateProductAction $findProduct
    ) {}

    public function __invoke(array $data): Offer
    {
        $product = $this->findProduct->execute($data);

        $market = Market::firstOrCreate([
            'name' => $this->normalize($data['market_name'])
        ]);

        return Offer::updateOrCreate(
            [
                'product_id' => $product->id,
                'market_id' => $market->id,
            ],
            [
                'price' => $data['price'],
                'collected_at' => now()
            ]
        );
    }

    private function normalize(string $text): string
    {
        return strtolower(trim($text));
    }
}
