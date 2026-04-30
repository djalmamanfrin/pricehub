<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'product_id',
        'market_id',
        'score',
        'price',
        'breakdown',
        'collected_at'
    ];

    protected $casts = [
        'breakdown' => 'array'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function market()
    {
        return $this->belongsTo(Market::class);
    }
}
