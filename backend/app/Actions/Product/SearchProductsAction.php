<?php

namespace App\Actions\Product;

use App\Models\Product;

class SearchProductsAction
{
    public function __invoke(string $query): array
    {
        $products = $this->searchInDatabase($query);

        $results = $products->map(function ($product) {
            return [
                'name' => $product->name,
                'offers' => $product->offers->map(function ($offer) {
                    return [
                        'market' => $offer->market->name,
                        'price' => $offer->price,
                    ];
                })->sortBy('price')->values()->toArray()
            ];
        })->toArray();

        return [
            'query' => $query,
            'results' => $results,
            'text' => $this->formatForWhatsApp($results),
        ];
    }

    private function searchInDatabase(string $query)
    {
        $normalized = $this->normalize($query);

        return Product::with(['offers.market'])
            ->where('normalized_name', 'like', "%{$normalized}%")
            ->limit(5)
            ->get();
    }

    private function formatForWhatsApp(array $results): string
    {
        $text = "";

        foreach ($results as $product) {
            $text .= "*{$product['name']}*\n";

            foreach ($product['offers'] as $offer) {
                $text .= "- {$offer['market']}: R$ {$offer['price']}\n";
            }

            $text .= "\n";
        }

        return trim($text);
    }

    private function normalize(string $text): string
    {
        return strtolower(trim($text));
    }
}
