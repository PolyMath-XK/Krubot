<?php

namespace KrubiK\Console;
/*
|--------------------------------------------------------------------------
| A Message to the Future Architect of Rebellion... 🚀🌌
|--------------------------------------------------------------------------
|
| Greetings, seeker of knowledge. You have just opened a blueprint
| from the Krubot BotEngine. What you see before you is more
| than just lines of code—it's a pattern for building scalable dreams.
|
| **This is a laboratory of creation.** We are experimenting with the
| very fabric of code here. Use this project as your ultimate training
| ground, a masterclass in *Software Dev Artistry.* It's a powerful template
| for learning, but not yet forged for the final battles of production.
|
| Behold the core principle:
| We Are **Rebuilding The Rebellion** Within S.N.P. *(The Foundation of Pure Power & Revel)*
| This entire library is being reconstructed with intense power,
| on a foundation of pure power **Far Stronger Than Anything That Came Before.**
| Starting with Laravel 12 Capabilities.
|
| What you see here is the **×0.7 ALPHA×** release. Why release it now?
| Because keeping this evolution a secret any longer would be a
| betrayal to the very community it was born to serve.
| 
| Consider this The Foundational Codex for Engineering a New Reality.
| The knowledge is free under the MIT License. Deconstruct its logic and schematics.
| Learn its secrets. Master its power. Command its potential. You are The Architect Now!
|
| * Go build something revolutionary! * 💜⚡️
|
| Let's Shape the Future. 🛠️⚡️🚀
|
*/

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use KrubiK\Helpers\AmethystMatrix; // Import Wise Logger 🔮
use KrubiK\Jobs\FetchDriverUpdates;

/**
 * ⚰️ THE LAZARUS PROTOCOL: SOVEREIGN EDITION (v5.4 Final)
 *
 * "That is not dead which can eternal lie,
 *  And with strange aeons even death may die."
 *
 * This is the ULTIMATE Daemon. It combines the robust process management of v3.1
 * with the intelligent hybrid architecture of v5.0 and the Micro-Optimizations of v5.3.
 *
 * 🧬 DNA Analysis:
 * - Role: Trinity (Poller + Worker + Guardian)
 * - Stealth: Pcntl / Exec / Passthru adaptability (Anti-CageFS)
 * - Brain: Config-Driven Hybrid Logic (Polling vs Webhook Support)
 * - Heart: Atomic Locking via MariaDB/Redis (Advisory Locks supported)
 * - Optimization: Smart DB Pinging Protocol (90% Less Overhead)
 * 
 * @author DoKtor K.
 * @link https://StoryKo.de Official website of engine.
 * @version Krubot: ×v0.7ALPHA×
 * @license MIT
**/
class LazarusProtocol extends Command
{
    /**
     * The Signature.
     * We kept ALL options to maintain backward compatibility and full control.
     */
    protected $signature = 'krubik:lazarus
                            {--driver=rubika : The target driver alias (Standard Protocol)}
                            {--tag=primary : Unique instance ID (Sector Control)}
                            {--stealth : Force PID reset via exec (Invisibility Mode for cPanel)}
                            {--force : Ignore locks (Breach Protocol)}';

    protected $description = 'The Immortal Phoenix. Orchestrates self-resurrection, fetching, and processing.';

    // ⚙️ VITAL SIGNS CONFIGURATION (Optimized for Shared Hosting & MariaDB 11.x, You may want to Change Them Sometimes)
    protected const MAX_LIFE_SECONDS = 110;   // 10s buffer before PHP max_execution_time
    protected const MAX_RAM_MB       = 128;   // Memory Ceiling
    protected const LOOP_DELAY_MS    = 1000;  // 1s Throttle (Aggressive but safe)
    protected const DB_PING_INTERVAL = 10;    // Check DB connection every 10 loops (Performance Boost)
    
    // 🧹 HYGIENE CONFIGURATION
    protected const MAX_LOOPS_BEFORE_REBIRTH = 5000; // The "Clean Slate" Protocol

    // 🔒 STATE VARIABLES
    protected int $bornAt;
    protected string $lockKey;
    protected int $loopCounter = 0;

    /**
     * The Entry Point.
    */
    public function handle(): int
    {
        // returns true when `php artisan down` is activated
        if (app()->isDownForMaintenance()) {
            $this->info("Maintenance mode activated. Dying peacefully...");
            return self::SUCCESS; // یا break;
            // نکته مهم: اینجا دیگر reincarnate را صدا نمی‌زند و زنجیره قطع می‌شود.
        }

        // --- [ دژبان ورودی: چک کردن مجوز فعالیت ] ---
    
        // خواندن وضعیت از کانفیگ (که از .env می‌خواند)
        $isLazarusEnabled = config('krubot.polling.lazarus.enabled', true);

        if ($isLazarusEnabled === false) {
            // اگر غیرفعال بود، فقط یک پیام در لاگ می‌گذارد و تمام.
            // هیچ حلقه‌ای شروع نمی‌شود، هیچ فرزندی متولد نمی‌شود.
            $this->warn("⛔ Lazarus is DISABLED in config. Resting...");
            return self::SUCCESS; // خروج موفقیت‌آمیز
            
            // نکته: چون اینجا return می‌کنیم، به کد reincarnate پایین هرگز نمی‌رسد.
            // زنجیره مرگ و زندگی اینجا قطع می‌شود.
        }

        // 1. Initialization & DNA Check
        $this->bornAt = time();
        $driver = $this->option('driver');
        $tag = $this->option('tag');
        $isStealth = $this->option('stealth');

        // 🔒 ATOMIC LOCKING (MariaDB Optimized)
        // We use Cache::lock to guarantee SINGLE INSTANCE execution per tag.
        // On MariaDB, ensure your cache driver is 'database' or 'redis' for true Atomic Locks (GET_LOCK).
        $this->lockKey = "krubik:lazarus_lock:{$tag}";
        
        // 60s TTL allows the lock to auto-expire if the server crashes hard.
        $lock = Cache::lock($this->lockKey, 60);

        // 2. Overlap Protection (Atomic Sentry)
        if (!$this->option('force') && !$lock->get()) {
            // Silent exit is preferred for overlapping cron runs.
            // $this->warn("⚠️  Sector [{$tag}] occupied. Standing down.");
            return self::SUCCESS;
        }

        // Display Operational Mode (Cyberpunk Style)
        $mode = $isStealth ? '👻 STEALTH (Exec/NewPID)' : '⚡ SPEED (Pcntl/SamePID)';
        $this->info("🔥 Lazarus v5.3 Sovereign Online. Tag: [{$tag}] | Mode: {$mode} | PID: " . getmypid());
        
        // 3. Fail-Safe: The Emergency Parachute
        register_shutdown_function(fn() => $this->handleShutdown($driver, $tag, $isStealth));

        // 4. The Loop of Eternity 🌌
        try {

            // Lazarus Configurable Heartbeat
            $interval = config('krubot.polling.lazarus.interval', self::LOOP_DELAY_MS);

            while (true) {
                
                // [A] ANTI-DISCONNECT PROTOCOL 🔌
                // Optimized: Only ping DB every N loops to save IOPS on MariaDB.
                if ($this->loopCounter % self::DB_PING_INTERVAL === 0) {
                    $this->ensureDatabaseConnection();

                    // --- [ بخش جدید: دکمه مرگ ] ---
                    // چک کردن کش برای دستور قتل
                    // [0] KILL SWITCH PROTOCOL 💀

                    if (Cache::has(config('krubot.polling.lazarus.kill-kommand', 'krubik:kill-lazarus'))) {
                        $this->warn("💀 Kill Switch Detected & Activated! I am dying voluntarily... Bye.");
                        
                        // پاک کردن کلید تا اگر بعدا خواستیم روشن کنیم، دوباره نمیرد
                        Cache::forget(config('krubot.polling.lazarus.kill-kommand', 'krubik:kill-lazarus')); 
                        
                        // خروج کامل از متد handle (بدون تولید مثل)
                        return self::SUCCESS;  // مرگ فوری و تمیز
                    }

                    /*
                    // یک آدرس مخفی و سخت برای امنیت
                    put in routes.php
                    Route::get('/secret-kill-switch-x99', function () {
                        Cache::put('krubot:kill', true, 60); // گذاشتن قرص سیانور در کش // 60 ثانیه اعتبار دارد
                        return "دستور قتل صادر شد! لازاروس در کمتر از ۱ ثانیه خواهد مرد. 💀";
                    });
                    */

                }

                // [B] REFRESH LOCK 🛡️
                // We re-acquire (extend) the lock to prove we are still alive.
                // If we lose the lock here, we must abort to prevent split-brain.
                if (!$lock->get()) {
                    AmethystMatrix::yell("Lazarus [{$tag}]: Lock lost during cycle! Aborting.");
                    break;
                }

                // [C] CORE EXECUTION (The Hybrid Engine) 🫀
                // Lazarus Main Operations Here ::
                try {
                    // C-1. Clear FileSystem Cache (Essential for config/log consistency)
                    clearstatcache();
                    
                    // C-2. THE HUNTER: Fetch Updates (Conditional)
                    // This is controlled by the Config Switch.
                    if (config('krubot.polling.enabled', true)) {
                        // FetchRubikaUpdates::dispatchSync();

                        // 🎯 TARGETED POLLING STRATEGY
                        // Iterate through active drivers defined in config.
                        $targets = config('krubot.polling.drivers', ['rubika']);
                        
                        foreach ($targets as $targetDriver) {
                             FetchDriverUpdates::dispatchSync($targetDriver);
                        }
                    }
                    
                    // C-3. THE DEVOURER: Process The Queue (Always On)
                    // Even if polling is OFF (Webhook Mode), we MUST process the queue.
                    // This replaces the need for a separate 'queue:work' daemon.
                    $this->processQueue();
                    
                } catch (\Throwable $e) {
                    // Mission Priority: Survival. Log and Continue.
                    AmethystMatrix::yell("Lazarus Operation Failed [{$tag}]: " . $e->getMessage());
                    
                    // Force DB reconnect on exception, just in case that was the cause.
                    $this->ensureDatabaseConnection(true);
                }

                // =========================================================
                // ✨ SECTION D: Phoenix-Mode / Scheduled Hygiene (The Counter Check)
                // =========================================================
                $this->loopCounter++;
                
                // Check 1: The Loop Limit (Prevent subtle fragmentation)
                if ($this->loopCounter >= self::MAX_LOOPS_BEFORE_REBIRTH) {
                    $this->info("♻️ Scheduled Rebirth (Loop Limit Reached).");
                    $this->reincarnate($driver, $tag, $isStealth); // 🧪☣
                    break;
                }

                // Check 2: Vital Signs (RAM & Time limit)
                if ($this->shouldReincarnate()) {
                    $this->reincarnate($driver, $tag, $isStealth); // 🧪☣
                    break;
                }
                // =========================================================

                // [E] TACTICAL PAUSE (Configurable Heartbeat)
                usleep($interval * 1000);
            }
        } finally {
            // Polite cleanup
            optional($lock)->release();
        }

        return self::SUCCESS;
    }

    /**
     * 🧠 INTERNAL: The Worker Logic
     * Executes the queue worker in short bursts.
    */
    private function processQueue(): void
    {
        // We use 'call' instead of 'callSilent' if you want debug output,
        // but for production, 'callSilent' is cleaner.
        Artisan::call('queue:work', [
            '--stop-when-empty' => true, // CRITICAL: Do not block loop!
            '--timeout'         => 20,
            '--memory'          => 128,
            '--tries'           => 3,
            '--sleep'           => 0,    // Machine Gun Mode (No sleep between jobs)
        ]);
    }

    /**
     * 🔌 INTERNAL: Database Defibrillator
     * @param bool $force If true, forces a reconnection immediately.
    */
    private function ensureDatabaseConnection(bool $force = false)
    {
        try {
            // If forced, or if PDO is missing/dead
            if ($force || !DB::connection()->getPdo()) {
                 throw new \Exception("Force Reconnect");
            }
            
            // Lightweight ping (Only runs every 10 loops)
            DB::connection()->getPdo()->query('SELECT 1');
            
        } catch (\Throwable $e) {
            try {
                DB::reconnect();
            } catch (\Throwable $z) {
                // If reconnect fails, we don't crash yet. The next loop cycle might fix it.
                AmethystMatrix::gaze($z, "Lazarus DB Defibrillation-Reconnect Failed");
            }
        }
    }

    /**
     * ⚖️ JUDGEMENT FUNC: Should we die to live again?
     */
    protected function shouldReincarnate(): bool
    {
        // 1. Time Limit (Reset PID/Timer to avoid Host Kill)
        if ((time() - $this->bornAt) >= self::MAX_LIFE_SECONDS) {
            return true;
        }

        // 2. Memory Limit (Prevent OOM Kills)
        // Explicitly trigger GC to free up cyclic references before measuring
        if (function_exists('gc_collect_cycles')) {
            gc_collect_cycles();
        }

        $mem = memory_get_usage(true) / 1024 / 1024;
        if ($mem >= self::MAX_RAM_MB) {
            AmethystMatrix::observe("Lazarus: Memory Limit Reached ({$mem}MB). Initiating Rebirth.");
            return true;
        }

        // 3. Manual Kill Switch (File Based Termination ⚡🔌)
        if (file_exists(storage_path('krubik_stop'))) {
            AmethystMatrix::whisper("🛑 Kill switch detected. Lazarus terminating gracefully.");
            exit(0);
        }

        return false;
    }

    /**
     * 🔥 THE PHOENIX RITUAL: Reincarnation Strategy
     * Preserved fully from v3.6
     */
    protected function reincarnate(string $driver, string $tag, bool $stealthMode): void
    {
        // 🔓 Force Release lock so the child can take it immediately
        Cache::lock($this->lockKey)->forceRelease();

        $php = PHP_BINARY;
        $artisan = base_path('artisan');
        
        $args = [
            'krubik:lazarus',
            "--driver={$driver}",
            "--tag={$tag}",
        ];
        if ($stealthMode) $args[] = '--stealth';

        // --- STRATEGY A: WARP SPEED (PCNTL) ---
        if (!$stealthMode && function_exists('pcntl_exec')) {
            pcntl_exec($php, array_merge([$artisan], $args));
        }

        // --- STRATEGY B: PHANTOM (SPAWN/EXEC) ---
        $cmdString = implode(' ', $args);
        $fullCmd = "{$php} {$artisan} {$cmdString} > /dev/null 2>&1 &";

        if (!$this->spawnProcess($fullCmd)) {
            AmethystMatrix::yell("Lazarus Failed to Spawn! Relying on Cron fallback.");
        }

        exit(0);
    }

    /**
     * 🛠️ TOOL: Smart Process Spawning
     * Tries every trick in the book to bypass 'disable_functions'.
    */
    protected function spawnProcess(string $cmd): bool
    {
        try {
            if ($this->functionEnabled('exec')) {
                exec($cmd);
                return true;
            } 
            elseif ($this->functionEnabled('passthru')) {
                passthru($cmd);
                return true;
            } 
            elseif ($this->functionEnabled('proc_open')) {
                proc_open($cmd, [], $pipes);
                return true;
            }
        } catch (\Throwable $e) {
            AmethystMatrix::gaze($e, "Spawn Error");
        }
        return false;
    }

    /**
     * 🔍 CHECK: Is function usable?
     */
    protected function functionEnabled(string $func): bool
    {
        if (!function_exists($func)) return false;
        $disabled = explode(',', ini_get('disable_functions'));
        return !in_array($func, array_map('trim', $disabled));
    }

    /**
     * 🚑 EMERGENCY: Handle Fatal Crashes
     */
    protected function handleShutdown(string $driver, string $tag, bool $stealthMode): void
    {
        $error = error_get_last();
        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            Log::channel('emergency')->alert("💀 Lazarus Flatlined! Rebooting...", $error);
            $this->reincarnate($driver, $tag, $stealthMode);
        }
    }
}
