<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\Sabbir\GarageSalesController;
use App\Http\Controllers\API\Sabbir\HelpAndSupportController;
use App\Http\Controllers\API\Sabbir\HomeController;
use App\Http\Controllers\API\Sabbir\ProductsController;
use App\Http\Controllers\API\Sabbir\ProfileController;
use App\Http\Controllers\Api\Sabbir\SpotlightController;
use App\Http\Controllers\API\Sabbir\WebhookController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api','prefix' => 'profile'], function ($router) {
    Route::get('/get', [ProfileController::class, 'profileRetrieval']);
    Route::get('/notifications', [ProfileController::class, 'getUserNotifications']);
});
Route::group(['middleware' => 'auth:api','prefix' => 'products'], function ($router) {
    Route::post('/store', [ProductsController::class, 'store']);
    Route::get('/all-product', [ProductsController::class, 'index']);
    Route::get('/{id}/edit', [ProductsController::class,'edit']);
    Route::post('/update/{id}', [ProductsController::class,'update']);

    // Listing management routes
    Route::post('/relist/{id}', [ProductsController::class, 'relist']);
    Route::get('/listed-products-by-status', [ProductsController::class, 'productsByStatus']);
    Route::get('/listed-products/{id}', [ProductsController::class, 'productDetails']);
    Route::get('/{productId}/interests', [ProductsController::class, 'productInterestUsers']);

    Route::get('/search', [ProductsController::class, 'search']);
    Route::post('/filter', [ProductsController::class, 'filterProducts']);

    // Archiving routes
    Route::post('/{product}/archive', [ProductsController::class, 'archive']);
    Route::post('/{product}/unarchive', [ProductsController::class, 'unarchive']);
    Route::get('/my-archived-products', [ProductsController::class, 'myArchivedProducts']);
    Route::get('/archived-product-details/{id}', [ProductsController::class, 'archivedProductDetails']);

    // Product Save And Unsave routes
    Route::post('/{id}/save', [ProductsController::class, 'toggleSave']);
    Route::get('/saved', [ProductsController::class, 'fetchSavedProducts']);

    // Love/Unlove routes
    Route::post('/{id}/love',[ProductsController::class,'toggle']);

    // Category-wise garage sales
    Route::get('/categories', [ProductsController::class, 'categories']);

    // Get spotlight boost fee
    // Route::get('/spotlight/fee', [SpotlightController::class, 'getBoostFee']);

    // Initiate spotlight payment
    Route::post('/spotlight/initiate', [SpotlightController::class, 'initiatePayment']);
    Route::post('/spotlight/confirm', [SpotlightController::class, 'confirmPayment']);
    
});
Route::group(['middleware' => 'auth:api','prefix' => 'garage'], function ($router) {
    Route::post('/store', [GarageSalesController::class, 'store']);
    Route::get('/all-product', [GarageSalesController::class, 'index']);
    Route::get('/{id}/edit', [GarageSalesController::class,'edit']);
    Route::post('/update/{id}', [GarageSalesController::class,'update']);

    // Payment routes
    Route::post('/initiate-payment', [GarageSalesController::class, 'initiatePayment']); // Get Payment Intent
    Route::post('/confirm-payment', [GarageSalesController::class, 'confirmPayment']); // Confirm Payment

    // Additional routes for garage sale management
    Route::post('/relist/{id}', [GarageSalesController::class, 'relist']);

    // Archiving routes
    Route::post('/archive/{garageId}', [GarageSalesController::class, 'archive']);
    Route::post('/unarchive/{garageId}', [GarageSalesController::class, 'unarchive']);
    Route::get('/listed-garage-by-status', [GarageSalesController::class, 'garageByStatus']);
    Route::get('/my-archived-garages', [GarageSalesController::class, 'myArchivedGarages']);

    // Love/Unlove routes
    Route::post('/{garage}/love', [GarageSalesController::class, 'toggle']);
    Route::get('/{garage}/loves', [GarageSalesController::class, 'users']);

    // Garage item details
    Route::get('/{id}/garageitem', [GarageSalesController::class, 'garageItemShow']);

});

// Webhook Route (No Auth Required)
Route::post('/webhooks/stripe', [WebhookController::class, 'handleWebhook']);

// Hoeme page
Route::group(['middleware' => 'auth:api','prefix' => 'home'], function ($router) {
    Route::post('/allproducts', [HomeController::class, 'homeProducts']);
    Route::post('/productsDetails/{id}', [HomeController::class, 'productDetail']);
    Route::post('/allgarages', [HomeController::class, 'homeGarageSales']);
    Route::post('/garageDetails/{id}', [HomeController::class, 'garageDetail']);

});
Route::group(['middleware' => 'auth:api','prefix' => 'help'], function ($router) {
    Route::post('/contact-sent', [HelpAndSupportController::class, 'store']);
    Route::get('/faqs', [HelpAndSupportController::class, 'getFaqs']);
    Route::get('/terms', [HelpAndSupportController::class, 'getTerms']);
});

