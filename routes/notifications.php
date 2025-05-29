<?php

use App\Http\Controllers\Notification\DeleteNotificationController;
use App\Http\Controllers\Notification\ListNotificationsController;
use App\Http\Controllers\Notification\MarkAllNotificationsAsReadController;
use App\Http\Controllers\Notification\MarkNotificationAsReadController;
use App\Http\Controllers\Notification\MarkNotificationAsUnreadController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', ListNotificationsController::class)->name('index');
        Route::post('/{notificationId}/mark-as-read', MarkNotificationAsReadController::class)->name('mark-as-read');
        Route::post('/{notificationId}/mark-as-unread', MarkNotificationAsUnreadController::class)->name('mark-as-unread');
        Route::post('/mark-all-as-read', MarkAllNotificationsAsReadController::class)->name('mark-all-as-read');
        Route::delete('/{notificationId}', DeleteNotificationController::class)->name('destroy');
    });
});
