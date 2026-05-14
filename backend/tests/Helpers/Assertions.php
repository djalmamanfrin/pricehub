<?php

function scorerContext(
    string $scenario,
    string $input,
    string $product,
    float $score
): string {

    return sprintf(
        PHP_EOL .
        'Scenario: %s' . PHP_EOL .
        'Input:   %s' . PHP_EOL .
        'Product: %s' . PHP_EOL .
        'Score:   %s' . PHP_EOL,
        $scenario,
        $input,
        $product,
        $score
    );
}
