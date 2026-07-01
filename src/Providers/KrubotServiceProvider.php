<?php

namespace KrubiK\Providers;
/*
| Krubot BotEngine: The Architect's Lexicon [×0.7 ALPHA×] 🚀📜
|--------------------------------------------------------------------------
| This is **a Playground For Mastery**, a laboratory of ***Software Dev Artistry***;
| not a weapon for production's final battles.
|
| Our Bond: ***"Rebuilding The Rebellion"*** Within S.N.P. (The Foundation of Pure Power & Revel).
| Your Mandate [MIT]: Deconstruct Krubot. Command it. Master it. You are The Architect Now!
|
| *Go build something revolutionary!* 💜⚡️
*/

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Arr;
use KrubiK\Krubot;
use KrubiK\Drivers\Nemesis as KrubotManager;
use KrubiK\Console\KrubiKPulse;
use KrubiK\Console\LazarusProtocol;
use KrubiK\Console\MakeMigrationsCommand;
use KrubiK\Console\CacheNexusesCommand;
use KrubiK\Console\ListNexusesCommand;
use KrubiK\Console\MakeNexusCommand;
use KrubiK\Helpers\AmethystMatrix;

/**
 * =========================================================================
 *  KRUBIK GALACTIC COMMAND CENTER
 * =========================================================================
 *     v3.6.48 "The Galactic Titan"
 * 
 * This Service Provider is the heart of the KrubiK package. It bootstraps
 * the bot, discovers and integrates Nexuses, provides Artisan commands for
 * a superior Developer Experience (DX), and handles asset publishing.
 * It is designed for maximum performance, flexibility, and clarity.
 *
 * @author DoKtor K.
 * @link https://StoryKo.de Official website of engine.
 * @version Krubot: ×v0.7ALPHA×
 * @license MIT
**/
class KrubotServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register any application services.
     *
     * This method is for binding things into the container. It should be FAST.
     * We will bind the Krubot instance here, but defer the heavy-lifting
     * of Nexus integration to the `boot` method.
     */
    public function register(): void
    {
        // Merge the default package config with the user's published version.
        $this->mergeConfigFrom(__DIR__ . '/../../config/krubot.php', 'krubot');

        // Register the core bindings for the Krubot engine.
        $this->registerBindings();
    }

    /**
     * Bootstrap any application services.
     *
     * This is where the magic happens. After the app is booted, we
     * can safely resolve the Krubot instance and perform the heavy
     * logic of discovering and integrating all Nexuses.
     */
    public function boot(): void
    {
        // These actions are only relevant when running in a console environment.
        if ($this->app->runningInConsole()) {
            $this->offerPublishing();
            $this->registerCommands();
        }

        // Boot the Nexus integration engine.
        // This is done in the `boot` method to ensure all engine services are available.
        // $this->app->make(\KrubiK\Krubot::class); // Eager Loading // دستور ساخت فوری
        $this->bootNexuses(); // Call the Nexus Integration Core to set up the 'resolving' listener.

        /// Load web routes of the package
        /// $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        /// Moved To: KrubotRouteProvider
    }

    /**
     * Binds the core Krubot singleton and its alias to the service container.
     * This method focuses *only* on the instantiation logic, making it extremely fast.
     */
    protected function registerBindings(): void
    {
        // 2. KrubotManager را به عنوان یک Singleton ثبت می‌کنیم.
        // این مغز متفکر سیستم است.
        $this->app->singleton('krubot.manager', function ($app) {
            // منیجر خودش شعور دارد، نیازی به تغذیه دستی نیست.
            return new KrubotManager($app);

            // $manager = new KrubotManager($app);
            // تزریق کانفیگ به تریت داخل منیجر (طبق بحث قبلی)
            // $manager->setConfig($app['config']['krubot']); 
            // return $manager;
        });

        $this->app->singleton('nemesis', function ($app) {
            return app('krubot.manager');
        });

        // 3. اینترفیس را به درایور *پیش‌فرض* متصل می‌کنیم.
        // این برای تزریق وابستگی در کنترلرها و جاب‌ها حیاتی است.
        // وقتی در متدی تایپ-هینت BotDriverInterface بدهید، لاراول می‌داند چه چیزی بسازد.
        $this->app->bind(BotDriverInterface::class, function ($app) {
            return $app['krubot.manager']->driver();
        });

        // Bind Krubot as a Singleton. This ensures the same bot instance is used
        // throughout the entire application request lifecycle.
        // The closure is kept lean and mean for maximum performance.
        $this->app->singleton(\KrubiK\Krubot::class, function ($app) {

            // 4. KRUBOT BINDING (The Independent Commander)
            // ###. ⚡ نقطه ادغام حیاتی (The Fusion Point) ⚡ .###
            // اینجا جایی است که مفهوم "Krubot" (کد دوم) را با "Manager" (کد اول) یکی می‌کنیم.
            // وقتی کسی Krubot::class را صدا می‌زند، ما او را به مدیر ارجاع می‌دهیم.
            // مدیر مسئول است که با استفاده از منطق دقیق (توکن و...) نمونه را بسازد.

             // دریافت درایور فعال از مدیر
            $driver = $app['krubot.manager']->driver();

            // گارد ایمنی: اطمینان از اینکه درایور فعلی واقعاً از جنس Krubot است
            // (مثلاً اگر درایور روی تلگرام باشد و کسی Krubot (روبیکا) بخواهد، اینجا مدیریت می‌شود)
            if (! $driver instanceof \KrubiK\Krubot) {
                // در حالت ایده‌آل، Krubot باید کلاس والد یا اینترفیس همه باشد،
                // اما اگر Krubot مختص روبیکا است، اینجا همان نمونه را برمی‌گردانیم.
                return $driver;
            }
            
            return $driver;
        });

        // The Oracle is born once, and lives forever (Singleton).
        $this->app->singleton('amethyst.empress', function ($app) {
            return new AmethystMatrix();
        });

        // Create a convenient alias for easier resolution or for the Facade.
        $this->app->alias(\KrubiK\Krubot::class, 'krubot');
        $this->app->alias(BotDriverInterface::class, 'krubot.driver');
    }

    /**
     *                  The Ultimate bootNexuses method
     *                    The Nexus Integration Core.
     * 
     *    This is the brain that loads all Nexus modules into the bot.
     *
     * Lazily configures the Krubot instance right after it's been resolved.
     * This method represents the architectural core of Nexus integration, combining
     * a performance-first caching strategy with flexible discovery mechanisms.
     * All expensive operations (filesystem I/O) are deferred until the bot is
     * actually requested, and are completely bypassed in production when a cache is present.
    */
    protected function bootNexuses(): void
    {
        $this->app->resolving(\KrubiK\Krubot::class, function (\KrubiK\Krubot $krubot, $app) {
            // Fetch the entire package configuration once to minimize overhead.
            $config = $app['config']->get('krubot');

            // PHASE 0: PRODUCTION-FIRST CACHE RETRIEVAL
            // This is the fastest execution path. If caching is enabled and the
            // cache file exists, we load it and terminate the configuration process immediately.
            $cachePath = $config['cache']['path'] ?? null;
            if (($config['cache']['enabled'] ?? false) && $cachePath && file_exists($cachePath)) {
                $cachedNexuses = require $cachePath;
                // Use setNexuses for a bulk, high-performance assignment.
                if (is_array($cachedNexuses) && !empty($cachedNexuses)) {
                    $krubot->setNexuses($cachedNexuses, true); // true => clear before fill
                }
                return; // Mission accomplished. The bot is ready from cache.
            }

            // --- If cache is not hit, proceed with manual loading ---

            // PHASE 1: THE VIP LANE - EXPLICIT NEXUSES
            // Load statically defined Nexuses from the config file. These are considered
            // critical and are always loaded first (when not using cache).
            $staticNexuses = Arr::wrap($config['nexuses'] ?? []);
            if (!empty($staticNexuses)) {
                // We start by setting this list as the definitive base.
                $krubot->setNexuses($staticNexuses, true); // true => clear before fill
            }

            // PHASE 2: THE DISCOVERY ENGINE - AUTOMATIC SCANNING
            // If discovery is enabled, scan the defined paths for Nexus classes.
            // This is an I/O-heavy operation, perfectly placed inside this lazy-loaded callback.
            if ($config['discovery']['enabled'] ?? false) {
                $discoveryPaths = Arr::wrap($config['discovery']['paths'] ?? [app_path('Nexus')]);

                foreach ($discoveryPaths as $path) {
                    // Failsafe check: ensure the path is a valid, readable directory before scanning.
                    // This prevents errors if the config contains invalid or inaccessible paths.
                    if (is_string($path) && is_dir($path) && is_readable($path)) {
                        // This method should APPEND discovered nexuses to the existing list.
                        $krubot->discoverAndIntegrateNexuses($path);
                    }
                }
            }
        });
    }

    /**
     * Sets up the assets that can be published by the user.
     */
    protected function offerPublishing(): void
    {
        // 3. Publish Command Logic

        // This allows users to publish the configuration file to their own
        // config directory for customization using: `php artisan vendor:publish`
        $this->publishes([
            // مسیر فایل مبدأ (Source) => مسیر فایل مقصد (Destination)
            __DIR__ . '/../../config/krubot.php' => config_path('krubot.php'),
        ], 'krubot-config'); // تگ اختصاصی برای پابلیش

        // php artisan vendor:publish --tag=krubot-config
        // => Copied File [/app/KrubiK/config/krubot.php] To [/config/krubot.php]
        // => Publishing complete.

        // [FEATURE PRESERVED] Commented-out suggestion from Provider #1 for future expansion.
        // This offers to publish a default Nexus directory for a quick start.
        /*
        $this->publishes([
            __DIR__ . '/../../stubs/Nexus' => app_path('Nexus'),
        ], 'krubot-nexuses');
        */

        // Publish the migrations
        $this->publishes([
            __DIR__ . '/../DivineMessageSender/Migrations/DivineMessageMigration.php' => database_path('migrations/' . date('Y_m_d_His') . '_divine_messages.php'),
            __DIR__ . '/../DivineMessageSender/Migrations/DivineDispatchQueueMigration.php' => database_path('migrations/' . date('Y_m_d_His', time() + 1) . '_divine_dispatch_queue.php'),
        ], 'krubot-migrations');
    }

    /**
     * Registers the package's "Ammunition" - the Artisan commands.
     */
    protected function registerCommands(): void
    {
        // [FEATURE MERGE] Full command list from Provider #3.
        // This provides a complete toolkit for managing Nexuses.
        $this->commands([
            KrubiKPulse::class,
            LazarusProtocol::class,
            CacheNexusesCommand::class, // The performance booster
            ListNexusesCommand::class,  // The debugging tool
            MakeNexusCommand::class,    // The workflow accelerator
            MakeMigrationsCommand::class,
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * Being a DeferrableProvider improves application performance by only loading
     * this provider when one of its services is explicitly requested.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [\KrubiK\Krubot::class, 'krubot'];
    }
}
