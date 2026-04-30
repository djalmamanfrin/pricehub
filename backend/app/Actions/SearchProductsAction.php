<?php

namespace App\Actions;

class SearchProductsAction
{
    public function __invoke(string $query): array
    {
        $results = $this->mockSearch($query);

        return [
            'query' => $query,
            'results' => array_values($results), // normaliza índice
            'text' => $this->formatForWhatsApp($results),
        ];
    }

    private function mockSearch(string $query): array
    {
        $products = [
            [
                'name' => 'Coca-Cola 2L',
                'offers' => [
                    ['market' => 'Condor', 'price' => 9.49],
                    ['market' => 'Muffato', 'price' => 8.99],
                ]
            ],
            [
                'name' => 'Coca-Cola 600ml',
                'offers' => [
                    ['market' => 'Condor', 'price' => 4.99],
                    ['market' => 'Muffato', 'price' => 4.79],
                ]
            ]
        ];

        return array_filter($products, function ($product) use ($query) {
            return str_contains(
                strtolower($product['name']),
                strtolower($query)
            );
        });
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
}
