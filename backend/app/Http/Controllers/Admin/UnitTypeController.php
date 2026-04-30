<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Actions\Admin\UnitType\CreateUnitTypeAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnitTypeController extends Controller
{
    public function store(Request $request, CreateUnitTypeAction $action): JsonResponse
    {
        $unitType = $action($request->all());
        return ApiResponse::success('UnitType cadastrada com sucesso', $unitType);
    }
}
