<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\Sabbir\ProductsController;
use App\Http\Controllers\API\Sabbir\ProfileController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'profile'
], function ($router) {
    
    Route::get('/get', [ProfileController::class, 'profileRetrieval']);
});
Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'products'
], function ($router) {
    
    Route::post('/store', [ProductsController::class, 'store']);
    Route::get('/all-product', [ProductsController::class, 'index']);
    Route::get('/{id}/edit', [ProductsController::class,'edit']);
    Route::post('/update/{id}', [ProductsController::class,'update']);
});