<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return ApiResponse::error('Informe o produto', 422);
        }

        // MOCK inicial (depois vem banco/Elastic)
        $results = $this->mockSearch($query);

        if (empty($results)) {
            return ApiResponse::error('Nenhum produto encontrado', 404);
        }

        return ApiResponse::success(
            'Produtos encontrados',
            [
                'query' => $query,
                'results' => $results
            ]
        );
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
            return str_contains(strtolower($product['name']), strtolower($query));
        });
    }
}
