<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\Web\Backend\Auth\AuthController;
use App\Http\Controllers\Web\Backend\FaqController;
use App\Http\Controllers\Web\Backend\ProjectController;
use App\Http\Controllers\Web\Backend\SiteController;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Artisan;


Route::get('/{page?}', function ($page = null) {
    return redirect()->route('backend.dashboard.index');
})->where('page', 'home|index');


Route::get('/run-migrate-fresh', function () {

    Artisan::call('migrate:fresh', [
        '--seed' => true
    ]);

    return "Database migrated fresh and seeded successfully!";
});

require_once __DIR__ .'/auth.php';


