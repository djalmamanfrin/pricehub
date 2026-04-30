<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Actions\Admin\Synonyms\CreateSynonymAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SynonymController extends Controller
{
    public function store(Request $request, CreateSynonymAction $action): JsonResponse
    {
        $synonym = $action($request->all());
        return ApiResponse::success('Synonym cadastrada com sucesso', $synonym);
    }
}
