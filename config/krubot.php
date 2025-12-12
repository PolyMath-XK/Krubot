<?php

// KrubiK/config/krubot.php

return [
    /*
    |--------------------------------------------------------------------------
    | KrubiK Global Activation
    |--------------------------------------------------------------------------
    |
    | Master switch to enable or disable the entire package.
    |
    */
    'enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | Authentication & Credentials
    |--------------------------------------------------------------------------
    */
    'authtoken'=> env('RUBIKA_BOT_TOKEN', '_'), // backward support for old-method

    /*
    |--------------------------------------------------------------------------
    | Default Bot Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default bot driver that will be used by the
    | framework. You may change this value to switch between drivers.
    |
    | Supported: "rubika", "telegram", "bale" (when implemented)
    |
    */

    'default_driver' => env('KRUBOT_DRIVER', 'rubika'),

    /*
    |--------------------------------------------------------------------------
    | Driver Definitions
    |--------------------------------------------------------------------------
    */
    'drivers' => [

        // ⚡️ TACTICAL ALIAS MAP ⚡️
        // The Single Source of Truth for driver identification.
        'aliases' => [
            'r'        => 'rubika',
            'rubika'   => 'rubika',  // Self-awareness for robust resolution
            'b'        => 'bale',
            'bale'     => 'bale',
            't'        => 'telegram',
            'tg'       => 'telegram',
            'telegram' => 'telegram',
            // Add your new aliases here...
        ],

        // -----------------------------------------------------------------
        // 🟡 RUBIKA (The Vanguard)
        // -----------------------------------------------------------------
        'rubika' => [
            'driver'    => 'rubika',
            'token'     => env('RUBIKA_BOT_TOKEN', '_'),
            'salt'      => env('RUBIKA_BOT_SALT', 'KrubiKSalT'),
            'config'    => [
                'ignore_self_messages' => true,
                'timeout' => 30,
                'max_retries' => 3,
                'parse_mode' => 'Markdown',
                // ... سایر تنظیمات خاص روبیکا
            ],
        ],

        // -----------------------------------------------------------------
        // 🟢 BALE (The Messenger)
        // -----------------------------------------------------------------
        'bale' => [
            'driver'   => 'bale', // نامی که در Manager استفاده می‌شود
            'token'    => env('BALE_BOT_TOKEN'),
            'base_url' => 'https://tapi.bale.ai/', // اختیاری، برای پروکسی
        ],

        // -----------------------------------------------------------------
        // 🔵 TELEGRAM (The Global EcoSystem)
        // -----------------------------------------------------------------
        'telegram' => [
            'driver'   => 'telegram',
            'token'    => env('TELEGRAM_BOT_TOKEN'),
            'base_url' => 'https://api.telegram.org',
            'config' => [
                'timeout' => 45,
                // ... سایر تنظیمات خاص تلگرام
            ]
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | MultiVerse Database Mapping
    |--------------------------------------------------------------------------
    |
    | This map connects a platform's canonical name to the corresponding
    | columns in your User model's database table. It is the single source
    | of truth for the InteractsWithMultiverse trait.
    |
    | 'platform_name' => ['chat' => 'db_chat_column', 'sender' => 'db_sender_column']
    |
    */
    'multiverse_map' => [
        'rubika'   => ['chat' => 'rcid', 'sender' => 'ruid', 'state' => 'rstat'],
        'telegram' => ['chat' => 'tcid', 'sender' => 'tuid', 'state' => 'tstat'],
        'bale'     => ['chat' => 'bcid', 'sender' => 'buid', 'state' => 'bstat'],
        // Add new platforms & columns here without touching the trait code!
    ],

    /*
    |--------------------------------------------------------------------------
    |       Nexus Integration Points
    | The VIP Lane: Static Nexus Registration
    |--------------------------------------------------------------------------
    |
    | This array lists all the Nexus classes that should be automatically
    | discovered and integrated by the Krubot service provider. When the
    | Krubot singleton is booted, it will reflect upon each of these
    | classes and register their command/text handlers.
    | 
    | Nexuses listed here are considered CRITICAL and are loaded first,
    | ensuring they are always available. They are immune to the discovery
    | process, preventing accidental duplicates.
    |
    | Adding a new Nexus class here is all you need to do to activate it.
    |
    */
    // array of handler classes consumed by the package
    'nexuses' => [
        \KrubiK\Nexus\BotLogicNexus::class,
        \KrubiK\Nexus\AdminNexus::class,
        // \App\Nexus\CoreNexus::class,
        // \App\Nexus\AdminNexus::class,
        // \App\Nexus\SurveyNexus::class,
        // Add your new Nexus classes here...
    ],

    /*
    |--------------------------------------------------------------------------
    | The Imperial Legions
    |--------------------------------------------------------------------------
    |
    | Define named groups of drivers (Legions) for easy, reusable command
    | targeting. You can command an entire legion with a single name.
    |
    */
    'legions' => [
        'social_platforms' => ['tg', 'instagram', 'x'],
        'internal_messengers' => ['bale', 'eitaa', 'rubika'],
        'all_fronts' => ['r', 'b', 'tg'],
    ],

    /*
    |--------------------------------------------------------------------------
    | The Multi-Verse Scanner: Automatic Nexus Discovery Engine
    |--------------------------------------------------------------------------
    |
    | Enable this to have KrubiK automatically scan a directory for Nexus
    | classes. This is perfect for modular applications where new Nexuses
    | can be added just by creating a new file.
    |
    | WARNING: This has a performance cost. It is STRONGLY recommended
    | to use the caching mechanism in production.
    |
    | Provide a single path (string) or multiple paths (array) for KrubiK to scan.
    | Any valid Nexus class found will be automatically integrated.
    | 
    | Configurations for the file and class scanner engine
    |
    | Files ending in `.disabled.php` will be ignored.
    |
    */
    'discovery' => [
        'enabled' => env('KRUBOT_NEXUS_DISCOVERY', true),

        // The absolute path to the directory to scan.

        // Example with a single path:
        // 'path' => app_path('Nexus'),
        
        // Example with MULTIPLE paths:
        'path' => [
            app_path('Nexus'),
            app_path('Nexus/Core'),
            app_path('Nexus/Features'),
        ],

        'exclude_suffixes' => [
            'disabled',
            '0',
            // You can add more suffixes here later, like 'bak' or 'old'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Polling Mechanism (The Heartbeat Mode)
    |--------------------------------------------------------------------------
    |
    | Controls whether the bot should actively fetch updates from the server
    | (Long Polling / Loop).
    |
    | Set to 'false' ONLY if you are using Webhooks or want to silence the bot.
    |
    */
    'polling' => [
        'enabled' => env('KRUBOT_POLLING_ENABLED', true),
        // [The Lazarus Protocol] //
        'lazarus' => [
            'enabled' => env('KRUBOT_LAZARUS_ENABLED', true), // سوییچ اصلی خاموش/روشن
            // فاصله‌ی زمانی بین هر درخواست در لوپ لازاروس (میلی‌ثانیه)
            'interval' => env('KRUBOT_LAZARUS_INTERVAL', 3000),
            'kill-kommand' => 'krubik:kill-lazarus'
        ],
        'drivers' => [
            'rubika',
            // 'bale', // commenting + refresh-config ==> disable polling for 'bale' driver-name
        ]
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Performance & Caching
    |--------------------------------------------------------------------------
    |
    | For production environments, caching the discovered Nexus list is
    | crucial. When enabled, KrubiK will read from a single cached file
    | instead of scanning directories on every request.
    |
    | Run `php artisan krubot:nexus-cache` to generate this file.
    |
    */
    'cache' => [
        'enabled' => env('KRUBOT_NEXUS_CACHE', env('APP_ENV') === 'production'),
        'key' => 'krubot::discovered_nexuses',
        'ttl' => \DateInterval::createFromDateString('24 hours'),        
        // The path where the cached Nexus list will be stored.
        'path' => base_path('bootstrap/cache/krubot_nexuses.php'),
    ],

    /*
    |--------------------------------------------------------------------------
    | 🚦 Routing Architecture (Titanium Core)
    |--------------------------------------------------------------------------
    |
    | Defines how KrubiK routes are exposed to the world.
    | The file paths are HARDCODED in KrubotRouteServiceProvider for stability.
    | You control the BEHAVIOR (Prefix, Middleware, Domain) here.
    |
    */
    'routing' => [
        // Master switch for all KrubiK routes
        'enabled' => env('KRUBOT_ROUTING_ENABLED', true),

        'groups' => [
            /*
             * WEB ROUTES (Stateful)
             * Used for: Dashboard, Cache Clearing, Utilities.
             * Location: ./KrubiK/routes/web.php
             */
            'web' => [
                'enabled' => true,
                'prefix'  => null,      // e.g. 'krubik' => /krubik/clear-cache
                'domain'  => null,      // e.g. 'admin.mysite.com'
                
                // Automatically apply Laravel's default 'web' middleware group?
                // Options: 'web', false, or any other middleware group name.
                'apply_laravel_defaults' => 'web', 

                // Additional middleware stack
                'middleware' => [
                    // 'auth',
                    // \App\Http\Middleware\AdminCheck::class,
                ],
            ],

            /*
             * API ROUTES (Stateless)
             * Used for: Webhooks (Incoming updates from messengers).
             * Location: ./KrubiK/routes/api.php
             */
            'api' => [
                'enabled' => true,
                'prefix'  => null,      // e.g. 'api/krubik' => /api/krubik/webhook
                'domain'  => null,
                
                // Automatically apply Laravel's default 'api' middleware group?
                // Options: 'api', false, etc.
                'apply_laravel_defaults' => 'api',

                // Additional middleware stack
                'middleware' => [
                    // 'throttle:api',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Divine Message Sender Configuration
    |--------------------------------------------------------------------------
    */
    'divine_sender' => [
        'enabled' => true,
        
        'allowed_hours_sections' => [
            0 => [9, 10, 11],       // Section 0: Morning
            1 => [14],              // Section 1: Midday
            2 => [17, 18, 19, 20],  // Section 2: Evening
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Global Middlewares Stack (Internal KrubiK Logic)
    |--------------------------------------------------------------------------
    |
    | NOT to be confused with HTTP Routing Middleware. These are pipeline
    | stages for processing incoming updates inside the bot logic.
    |
    */
    'middlewares' => [
        /*
        |
        | Middlewares listed here will run on EVERY request that Krubot handles,
        | before any route-specific or group-specific middlewares.
        | The ConversationMiddleware is often essential for the conversation
        | system to function correctly.
        |
        */
        'globals' => [
            \KrubiK\Middlewares\ConversationMiddleware::class,
            // \App\Http\Middleware\LogAllRubikaRequests::class, // Example of another global middleware
        ],
        'aliases' => [
            /**
             * ⚡ Middleware Aliases Map
             * Allows using short strings like 'auth' instead of full class names.
             * Effective in both Laravel (if registered) and Native PHP modes.
             */
            // ---- DEFAULTS ----
            'auth'     => \App\Http\Middleware\Authenticate::class,
            'admin'    => \App\Http\Middleware\AdminCheck::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,

            // Add your custom aliases here...

            // ---- USER CAN EXTEND HERE ----
            // 'vip' => \App\Http\Middleware\VipGuard::class,
            // 'log' => \App\Http\Middleware\LogAll::class,
        ],
    ],

];
