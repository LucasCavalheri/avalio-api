<?php

use App\Http\Controllers\Business\CreateBusinessController;
use App\Http\Controllers\Business\DeleteBusinessController;
use App\Http\Controllers\Business\GetBusinessController;
use App\Http\Controllers\Business\GetUserBusinessesController;
use App\Http\Controllers\Business\UpdateBusinessController;
use App\Http\Controllers\Business\UploadBusinessCoverImageController;
use App\Http\Controllers\Business\UploadBusinessLogoImageController;
use App\Http\Controllers\Business\BusinessStatsController;
use Illuminate\Support\Facades\Route;

Route::prefix('businesses')->name('businesses.')->group(function () {
    Route::middleware(['is.subscribed', 'auth:sanctum'])->group(function () {
        Route::post('/', CreateBusinessController::class)->name('create');
        Route::get('/user', GetUserBusinessesController::class)->name('index');
        Route::patch('/{id}', UpdateBusinessController::class)->name('update');
        Route::delete('/{id}', DeleteBusinessController::class)->name('delete');
        Route::post('/{id}/logo-image', UploadBusinessLogoImageController::class)->name('upload-logo-image');
        Route::post('/{id}/cover-image', UploadBusinessCoverImageController::class)->name('upload-cover-image');
    });

    Route::get('/{id}', GetBusinessController::class)->name('show');
});
