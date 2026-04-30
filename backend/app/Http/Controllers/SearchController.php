<?php

namespace App\Http\Controllers;

use App\Actions\SearchProductsAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class SearchController extends Controller
{
    public function search(Request $request, SearchProductsAction $action): JsonResponse
    {
        $query = $request->input('query');

        if (!$query) {
            return ApiResponse::error('Informe o produto', 422);
        }

        $data = $action($query);

        if (empty($data)) {
            return ApiResponse::error('Nenhum produto encontrado', 404);
        }

        return ApiResponse::success('Resultados', $data);
    }
}
