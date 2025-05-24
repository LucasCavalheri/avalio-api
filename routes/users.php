<?php

use App\Http\Controllers\User\DeleteAccountController;
use App\Http\Controllers\User\UpdateUserController;
use App\Http\Controllers\User\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('users')->name('users.')->group(function () {
    Route::get('/profile', UserProfileController::class)->name('profile');
    Route::patch('/', UpdateUserController::class)->name('update');
    Route::delete('/', DeleteAccountController::class)->name('delete');
});
