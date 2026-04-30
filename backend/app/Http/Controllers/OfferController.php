<?php

namespace App\Http\Controllers;

use App\Actions\Offer\CreateOfferAction;
use App\Helpers\ApiResponse;
use App\Http\Requests\StoreOfferRequest;
use Illuminate\Http\JsonResponse;

class OfferController extends Controller
{
    public function store(StoreOfferRequest $request, CreateOfferAction $action): JsonResponse
    {
        $offer = $action($request->validated());
        return ApiResponse::success('Oferta cadastrada com sucesso', ['offer_id' => $offer->id]);
    }
}
