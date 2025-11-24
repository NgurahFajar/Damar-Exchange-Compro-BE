<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Responses\CurrencyResponse;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Public Routes - No Authentication Required
    |--------------------------------------------------------------------------
    */
    // Contact Form Route
    Route::post('/contact', [ContactController::class, 'send'])
        ->middleware('throttle:20,1')  // Max request 20 per minute
        ->name('contact.send');

    // Public Currency Routes - For viewing only
    Route::controller(CurrencyController::class)->group(function () {
        Route::get('/currencies', 'index')
            ->middleware('throttle:300,1')  // Max request 300 per minute
            ->name('currencies.index');

        Route::get('/currencies/{currency}', 'show')
            ->where('currency', '[A-Z]{3}(?:p\/w|_?\d+)?')
            ->middleware('throttle:300,1')  // Max request 300 per minute
            ->name('currencies.show');
    });

    Route::get('/images', [ImageController::class, 'index'])
        ->middleware('throttle:300,1')  // Max request 300 per minute
        ->name('images.index');

    // Authentication Routes
    Route::controller(AuthController::class)->group(function () {
        Route::post('/auth/login', 'login')
            ->middleware('throttle:30,1')  // Max request 30 per minute
            ->name('login');

        // Add refresh token route - Keep it outside auth middleware but with rate limiting
        Route::post('/auth/refresh', 'refresh')
            ->middleware('throttle:30,1')  // Max request 30 per minute
            ->name('auth.refresh');
    });

    /*
    |--------------------------------------------------------------------------
    | Protected Routes - Authentication Required
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth:sanctum', 'check.token.expiration'])->group(function () {
        // Auth Management Routes
        Route::controller(AuthController::class)->prefix('auth')->group(function () {
            Route::post('/logout', 'logout')->name('auth.logout');
            Route::get('/user', 'user')->name('auth.user');
        });

        // Protected Currency Management Routes
        Route::controller(CurrencyController::class)
            ->prefix('currencies')
            ->middleware(['admin', 'throttle:100,10'])  // Max request 100 per 10 minutes
            ->group(function () {
                Route::post('/', 'store')->name('currencies.store');
                Route::put('/{currency}', 'update')
                    ->where('currency', '[A-Z]{3}(?:p\/w|_?\d+)?')
                    ->name('currencies.update');
                Route::delete('/{currency}', 'destroy')
                    ->where('currency', '[A-Z]{3}(?:p\/w|_?\d+)?')
                    ->name('currencies.destroy');
                Route::delete('/', 'deleteAll')->name('currencies.deleteAll');
            });
        //Protected Image Management Routes
        Route::controller(ImageController::class)
            ->prefix('images')
            ->middleware(['auth:sanctum'])
            ->group(function () {
                Route::post('/{id}', 'update')->name('images.update');
                Route::post('/', 'store')->name('images.store');
                Route::delete('/{id}', 'destroy')->name('images.destroy');
            });
    });
});

/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return CurrencyResponse::methodNotAllowed(request()->method());
})->middleware('api');
