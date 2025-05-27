<?php

use App\Http\Controllers\Stripe\CancelSubscriptionController;
use App\Http\Controllers\Stripe\StripeCheckoutController;
use App\Http\Controllers\Stripe\SubscriptionHistoryController;
use App\Http\Controllers\Stripe\SwapSubscriptionController;
use Illuminate\Support\Facades\Route;
use Laravel\Cashier\Http\Controllers\WebhookController as StripeWebhookController;

Route::middleware('auth:sanctum')->prefix('/stripe')->name('stripe.')->group(function () {
    Route::post('/checkout', StripeCheckoutController::class)->name('checkout');
    Route::post('/swap-subscription', SwapSubscriptionController::class)->name('swap-subscription');
    Route::post('/cancel-subscription', CancelSubscriptionController::class)->name('cancel-subscription');
    Route::get('/subscription-history', SubscriptionHistoryController::class)->name('subscription-history');
});

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])->name('stripe.webhook');
