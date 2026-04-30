<?php

namespace App\Actions\Admin\Synonyms;

use App\Models\Synonym;
use Illuminate\Support\Str;

class CreateSynonymAction
{
    public function __invoke(array $data): Synonym
    {
        return Synonym::create([
            'term' => Str::lower(trim($data['term'])),
            'normalized' => Str::lower(trim($data['normalized'])),
            'weight' => $data['weight'] ?? 1,
        ]);
    }
}
