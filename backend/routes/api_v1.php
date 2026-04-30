<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SynonymController;
use App\Http\Controllers\Admin\UnitTypeController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\SearchController;

Route::post('/search', [SearchController::class, 'search']);
Route::post('/offers', [OfferController::class, 'store']);

Route::prefix('admin')->group(function () {

    Route::post('/synonyms', [SynonymController::class, 'store']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::post('/unit-types', [UnitTypeController::class, 'store']);

});
