<?php

namespace App\Actions\Admin\UnitType;

use App\Models\UnitType;
use Illuminate\Support\Str;

class CreateUnitTypeAction
{
    public function __invoke(array $data): UnitType
    {
        return UnitType::create([
            'name' => $data['name'],
            'normalized_name' => Str::lower(Str::ascii($data['name'])),
        ]);
    }
}
