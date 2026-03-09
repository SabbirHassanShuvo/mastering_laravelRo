<?php

use App\Http\Controllers\Web\Backend\GarageSalesController;
use App\Http\Controllers\Web\Backend\ProductsController;
use App\Http\Controllers\Web\Backend\SpotlightPaymentsController;
use Illuminate\Support\Facades\Route;

Route::prefix('backend')->name('backend.')->group(function () {

    Route::prefix('products')->name('products.')->group(function () {

        Route::get('/', [ProductsController::class, 'index'])->name('index');
        Route::get('/create', [ProductsController::class, 'create'])->name('create');
        Route::post('/store', [ProductsController::class, 'store'])->name('store');
        Route::get('/export/csv', [ProductsController::class, 'exportCsv'])->name('export.csv');
        Route::get('/export/pdf', [ProductsController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/{product}', [ProductsController::class, 'show'])->name('show');
        Route::post('/{id}/status', [ProductsController::class, 'changeStatus'])->name('status');
        Route::delete('/{product}', [ProductsController::class, 'destroy'])->name('destroy');

    });

    // Garage Sales Management
    Route::prefix('garage-sales')->name('garage.')->group(function () {
        Route::get('/', [GarageSalesController::class, 'index'])->name('index');
        Route::get('/create', [GarageSalesController::class, 'create'])->name('create');
        Route::post('/store', [GarageSalesController::class, 'store'])->name('store');
        Route::get('/{id}', [GarageSalesController::class, 'show'])->name('show');
        Route::delete('/{id}', [GarageSalesController::class, 'destroy'])->name('destroy');
    });

    // Spotlight & Boost Payments
    Route::prefix('spotlight-payments')->name('spotlight.')->group(function () {
        Route::get('/', [SpotlightPaymentsController::class, 'index'])->name('index');
        Route::get('/cities', [SpotlightPaymentsController::class, 'cityAnalytics'])->name('cities');
        Route::get('/export/csv', [SpotlightPaymentsController::class, 'exportCsv'])->name('export.csv');
        Route::get('/export/pdf', [SpotlightPaymentsController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/{id}', [SpotlightPaymentsController::class, 'show'])->name('show');
    });

});