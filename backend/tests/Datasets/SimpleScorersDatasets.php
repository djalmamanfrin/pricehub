<?php

dataset('simple scorer cases', [
    'match' => [
        'input' => 1,
        'product' => 1,
        'expectedScore' => 100.0,
        'expectedRule' => 'match',
    ],
    'conflict' => [
        'input' => 1,
        'product' => 2,
        'expectedScore' => -80.0,
        'expectedRule' => 'conflict',
    ],
    'unknown' => [
        'input' => null,
        'product' => 1,
        'expectedScore' => 0.0,
        'expectedRule' => 'unknown',
    ],
]);
