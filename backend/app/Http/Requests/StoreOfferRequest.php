<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_name' => 'required|string|max:255',
            'market_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',

            'barcode' => 'nullable|string|max:50',

            // opcional (quando vier estruturado)
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'nullable|exists:categories,id',
            'unit_type_id' => 'nullable|exists:unit_types,id',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'product_name' => trim($this->product_name),
            'market_name' => trim($this->market_name),
        ]);
    }
}
