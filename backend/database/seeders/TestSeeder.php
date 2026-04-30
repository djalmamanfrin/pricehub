<?php

namespace Database\Seeders;

use App\Models\Market;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    public function run()
    {
        $product = Product::create([
            'name' => 'Coca-Cola 2L',
            'normalized_name' => 'coca cola 2l'
        ]);

        $market1 = Market::create(['name' => 'Condor']);
        $market2 = Market::create(['name' => 'Muffato']);

        Offer::create([
            'product_id' => $product->id,
            'market_id' => $market1->id,
            'price' => 9.49
        ]);

        Offer::create([
            'product_id' => $product->id,
            'market_id' => $market2->id,
            'price' => 8.99
        ]);
    }
}
