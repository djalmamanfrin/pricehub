<?php

namespace App\Domain\Matching\Product;

use App\Domain\Matching\DTO\ParsedInput;
use App\Models\Product;
use Illuminate\Support\Collection;

class CandidateFinder
{
    public function find(ParsedInput $input): Collection
    {
        return Product::query()
            ->where(function ($q) use ($input) {
                foreach (explode(' ', $input->normalized) as $token) {
                    $q->orWhere('normalized_name', 'ILIKE', "%{$token}%");
                }
            })
            ->limit(50)
            ->get();
    }
}
