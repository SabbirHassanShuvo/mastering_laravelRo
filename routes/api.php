<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Web\Frontend\PaymentController;
use App\Http\Controllers\API\SocialLogin\SocialLoginController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    // Profile route protected by auth:api middleware
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/profile/update', [AuthController::class, 'profileUpdate']);

    //Continue with google and facebook login
    // Route::post('/social/login', [SocialLoginController::class, 'SocialLogin']);
    
    Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
    Route::post('/password/resend-otp', [AuthController::class, 'resendOtp']);   
    Route::post('/password/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/password/reset', [AuthController::class, 'resetPassword']);
    Route::post('/password/change', [AuthController::class, 'changePassword']);
    Route::post('/delete-account', [AuthController::class, 'deleteAccount']);
});


require_once __DIR__ .'/sabbirapi.php';
require_once __DIR__ .'/frontend/dashboard.php';
require_once __DIR__ .'/frontend/payments.php';