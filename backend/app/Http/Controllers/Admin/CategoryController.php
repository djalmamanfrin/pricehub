<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Actions\Admin\Category\CreateCategoryAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request, CreateCategoryAction $action): JsonResponse
    {
        $category = $action($request->all());
        return ApiResponse::success('Categoria cadastrada com sucesso', $category);
    }
}
