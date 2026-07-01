<?php

use Illuminate\Support\Facades\Route;
use KrubiK\Controllers\SuperWebhookController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

// -----------------------------------------------------------------------------
// 🧹 UTILITY ROUTES
// -----------------------------------------------------------------------------
/*
|--------------------------------------------------------------------------
| KrubiK Web Routes (Dashboard & Utils)
|--------------------------------------------------------------------------
|
| These routes are loaded by KrubotRouteServiceProvider with the "web"
| middleware group. Designed for browser-based interaction.
|
*/

Route::get('/clear-cache', function () {
    $commands = [
        'optimize:clear',
        'config:clear',
        // 'cache:clear', // Fu**s History of Conversations,Forms,Chains,InlineMenus,...! a !_Dangerous_CMD_!
        'route:clear',
        'view:clear',
        'event:clear',
        'schedule:clear-cache',

        // Re-cache for performance
        'krubik:nexus-cache',
        'config:cache',
    ];

    $outputBuffer = collect();

    // \Illuminate\Support\Facades\Cache::flush();
    foreach ($commands as $command) {
        try {
            Artisan::call($command);
            $outputBuffer->push(trim(Artisan::output()));
        } catch (\Throwable $e) {
            $outputBuffer->push("❌ Error running {$command}: " . $e->getMessage());
        }
    }

    // return nl2br("<b>Cache Cleared Successfully!</b> ::: " . $output);
    return Response::make(
        str($outputBuffer->implode(PHP_EOL . PHP_EOL))
            ->prepend("✅ **SYSTEM PURGE COMPLETE** ✅" . PHP_EOL . "================================" . PHP_EOL)
            ->append(PHP_EOL . "================================" . PHP_EOL . "🚀 Ready for deployment.")
            ->replace(PHP_EOL, '<br>')
    );
})->name('krubik.clear-cache');
