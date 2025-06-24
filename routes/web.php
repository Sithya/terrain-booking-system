<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TerrainController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('terrains.index');
});

// Resource routes
Route::resource('terrains', TerrainController::class);
Route::resource('bookings', BookingController::class);
Route::resource('payments', PaymentController::class);
Route::resource('reviews', ReviewController::class);
Route::resource('favorites', FavoriteController::class)->except(['create', 'edit', 'update']);

// Add auth routes if needed
// Auth::routes();
