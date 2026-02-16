<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\Sabbir\ProfileController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'profile'
], function ($router) {
    
    Route::get('/profile', [ProfileController::class, 'profileRetrieval']);
});