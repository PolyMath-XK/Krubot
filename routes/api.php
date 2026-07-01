<?php

use Illuminate\Support\Facades\Route;
use KrubiK\Controllers\SuperWebhookController;
// use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

/*
|--------------------------------------------------------------------------
| KrubiK API Routes (Webhooks)
|--------------------------------------------------------------------------
|
| Here is where the "SuperWebhookController" lives. These routes are
| loaded by the KrubotRouteProvider within a group which
| is assigned the "api" middleware group by default.
|
*/

// The Main Entry Point for all Incoming Updates to Nemesis/Krubot

// This single route now governs all incoming webhook traffic.
// The '{driver?}' parameter makes it configurable optional,
// allowing KrubotManager's payload-sniffing to work its magic for legacy webhooks.

Route::post('/run-krubik/{driver?}', SuperWebhookController::class)
    // ->withoutMiddleware([VerifyCsrfToken::class); // 🛡️ Bypass CSRF for Webhooks, Not Needed For API Routes
    ->name('krubik.superwebhook.run');
