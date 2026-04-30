<?php

namespace App\Http\Controllers;

use App\Actions\SearchProductsAction;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use Illuminate\Support\Arr;

class SearchController extends Controller
{
    public function search(Request $request, SearchProductsAction $action)
    {
        $query = $request->input('query');

        if (!$query) {
            return ApiResponse::error('Informe o produto', 422);
        }

        $data = $action($query);

        if (empty($data)) {
            return ApiResponse::error('Nenhum produto encontrado', 404);
        }

        return ApiResponse::success(
           'Resultados',
            $data
        );
    }
}
