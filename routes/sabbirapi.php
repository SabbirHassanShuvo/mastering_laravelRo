<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\Sabbir\GarageSalesController;
use App\Http\Controllers\API\Sabbir\ProductsController;
use App\Http\Controllers\API\Sabbir\ProfileController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api','prefix' => 'profile'], function ($router) {
    Route::get('/get', [ProfileController::class, 'profileRetrieval']);
    Route::get('/notifications', [ProfileController::class, 'notifications']);
});
Route::group(['middleware' => 'auth:api','prefix' => 'products'], function ($router) {
    Route::post('/store', [ProductsController::class, 'store']);
    Route::get('/all-product', [ProductsController::class, 'index']);
    Route::get('/{id}/edit', [ProductsController::class,'edit']);
    Route::post('/update/{id}', [ProductsController::class,'update']);
});
Route::group(['middleware' => 'auth:api','prefix' => 'garage'], function ($router) {
    Route::post('/store', [GarageSalesController::class, 'store']);
    Route::get('/all-product', [GarageSalesController::class, 'index']);
    Route::get('/{id}/edit', [GarageSalesController::class,'edit']);
    Route::post('/update/{id}', [GarageSalesController::class,'update']);
});

