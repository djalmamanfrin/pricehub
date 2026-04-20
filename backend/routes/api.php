<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:60,1'])->prefix('v1')->group(function () {
    require __DIR__.'/api_v1.php';
});
