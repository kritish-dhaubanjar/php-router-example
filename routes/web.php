<?php

use Lapetus\Routing\Route;

use App\Http\Controllers\HomeController;

/**
 * Examples:
 *
 * Route::get('/{id}', function(Request $request, $id){ })
 * Route::post('/{id}', [PostController::class, 'index'])
 */

Route::get('/', [HomeController::class, 'index']);
