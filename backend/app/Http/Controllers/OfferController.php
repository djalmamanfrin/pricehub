<?php

namespace App\Http\Controllers;

use App\Actions\Offer\CreateOfferAction;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function store(Request $request, CreateOfferAction $action)
    {
        $data = $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'product_name' => 'required|string',
            'market_name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'barcode' => 'nullable|string'
        ]);

        $offer = $action($data);

        return ApiResponse::success('Oferta cadastrada com sucesso', ['offer_id' => $offer->id]);
    }
}
