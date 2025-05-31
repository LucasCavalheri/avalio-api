<?php

use App\Http\Controllers\User\DeleteAccountController;
use App\Http\Controllers\User\ListUserReviewsController;
use App\Http\Controllers\User\UpdateUserController;
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\User\UserStatsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('users')->name('users.')->group(function () {
    Route::get('/profile', UserProfileController::class)->name('profile');
    Route::get('/stats', UserStatsController::class)->name('stats');
    Route::get('/reviews', ListUserReviewsController::class)->name('reviews');
    Route::patch('/', UpdateUserController::class)->name('update');
    Route::delete('/', DeleteAccountController::class)->name('delete');
});
