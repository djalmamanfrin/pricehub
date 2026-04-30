<?php

use App\Http\Controllers\SearchController;

Route::post('/search', [SearchController::class, 'search']);
