<?php

use App\Http\Controllers\Review\CreateReviewController;
use App\Http\Controllers\Review\GetBusinessReviewsController;
use App\Http\Controllers\Review\RespondToReviewController;
use Illuminate\Support\Facades\Route;

Route::prefix('reviews')->name('reviews.')->group(function () {
    Route::post('/', CreateReviewController::class)->name('create');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/business/{id}', GetBusinessReviewsController::class)->name('get');
        Route::post('/{id}/respond', RespondToReviewController::class)->name('respond');
    });
});
