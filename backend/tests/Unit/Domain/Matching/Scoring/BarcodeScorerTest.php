<?php

use App\Domain\Matching\Scoring\BarcodeScorer;

beforeEach(function () {
    $this->scorer = new BarcodeScorer();
});

dataset('barcode scorer cases', [

    'exact barcode match' => [
        'inputBarcode' => '7894900011517',
        'productBarcode' => '7894900011517',
        'expectedScore' => 100.0,
        'expectedRule' => 'barcode_match',
    ],

    'barcode conflict' => [
        'inputBarcode' => '7894900011517',
        'productBarcode' => '1234567899999',
        'expectedScore' => -80.0,
        'expectedRule' => 'barcode_conflict',
    ],

    'missing input barcode' => [
        'inputBarcode' => null,
        'productBarcode' => '7894900011517',
        'expectedScore' => 0.0,
        'expectedRule' => 'barcode_unknown',
    ],

    'both barcodes missing' => [
        'inputBarcode' => null,
        'productBarcode' => null,
        'expectedScore' => 0.0,
        'expectedRule' => 'barcode_unknown',
    ],

]);

it('scores barcode correctly',
    function (
        ?string $inputBarcode,
        ?string $productBarcode,
        float $expectedScore,
        string $expectedRule,
    ) {
        $input = parsed(barcode: $inputBarcode);
        $product = product(barcode: $productBarcode);

        $this->scorer->apply($input, $product);

        expect($this->scorer->getValue())
            ->toBe($expectedScore)
            ->and($this->scorer->getRule())
            ->toBe($expectedRule);
    }
)->with('barcode scorer cases');
