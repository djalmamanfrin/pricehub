<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = ['name', 'normalized_name'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
