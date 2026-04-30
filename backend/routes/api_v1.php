<?php

use App\Http\Controllers\OfferController;
use App\Http\Controllers\SearchController;

Route::post('/search', [SearchController::class, 'search']);
Route::post('/offers', [OfferController::class, 'store']);
