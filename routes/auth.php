<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->name('auth.')->group(function () {
    Route::post('/login', LoginController::class)->name('login');
    Route::post('/register', RegisterController::class)->name('register');
    Route::post('/logout', LogoutController::class)->name('logout')->middleware('auth:sanctum');

    Route::post('/forgot-password', ForgotPasswordController::class)->name('forgot-password')->middleware('throttle:2,1'); // 2 tentativas por minuto
    Route::post('/reset-password', ResetPasswordController::class)->name('reset-password');
});
