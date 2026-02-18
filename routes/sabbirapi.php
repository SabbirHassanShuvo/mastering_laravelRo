<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\Sabbir\ProductsController;
use App\Http\Controllers\API\Sabbir\ProfileController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'profile'
], function ($router) {
    
    Route::get('/profile', [ProfileController::class, 'profileRetrieval']);
});
Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'products'
], function ($router) {
    
    Route::post('/store', [ProductsController::class, 'store']);
    Route::get('/all-product', [ProductsController::class, 'index']);
});