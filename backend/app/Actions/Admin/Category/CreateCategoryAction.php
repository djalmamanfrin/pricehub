<?php

namespace App\Actions\Admin\Category;

use App\Models\Category;
use Illuminate\Support\Str;

class CreateCategoryAction
{
    public function __invoke(array $data): Category
    {
        return Category::create([
            'name' => $data['name'],
            'normalized_name' => Str::lower(Str::ascii($data['name'])),
        ]);
    }
}
