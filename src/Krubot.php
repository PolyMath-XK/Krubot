<?php

namespace KrubiK;

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

use KrubiK\DTOs\Message;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\AmethystMatrix;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use KrubiK\Attributes\Name;
use KrubiK\Attributes\Action;
use KrubiK\Attributes\Middleware;
use KrubiK\Attributes\OnCommand;
use KrubiK\Attributes\OnText;
use KrubiK\Attributes\OnRegEx;
use KrubiK\Middlewares\ConversationMiddleware; // ⚡ Import Middleware
use KrubiK\WarLording\CommandOutcomeShifter;
use KrubiK\Router\Route; // ⚡ Import Route Class
use KrubiK\Jobs\HandleDriverUpdate;
use KrubiK\DTOs\RubikaInboundPayload;
use ReflectionClass;
use ReflectionMethod;
use Closure;

use KrubiK\Arcane\InteractsWithApi;
use KrubiK\Arcane\InteractsWithContext; // ⚡ Import Context
use KrubiK\Arcane\HasAmethystMatrix; // ⚡ Import the Sorceress
use KrubiK\Arcane\HasCommandGroups;
use KrubiK\Arcane\AdvancedRouting;
use KrubiK\Arcane\ProfessionalWarLordingToolkit;
use KrubiK\Arcane\SummonsCodeSpyz;
use KrubiK\Arcane\HasKeyboards;
use KrubiK\Arcane\CanSendMedia;
use KrubiK\Arcane\CanPin;
use KrubiK\Arcane\CanManageChats;
use KrubiK\Arcane\CanManageMembers;
use KrubiK\Arcane\CanInitConversations;
use KrubiK\Arcane\CanPlayDiceGames;
use KrubiK\Arcane\PHPRBK_Methods;

/**
 * Krubot: The Warlord Edition ×v0.7ALPHA× (vObsidian-5)
 *
 * A Multi-Platform Orchestrator. This class does not contain any platform-specific API logic yet...
 * But It acts as a router and a proxy, delegating all platform
 * interactions to the appropriate driver.
 * 
 * @author DoKtor K.
 * @link https://StoryKo.de Official website of engine.
 * @version self: ×v0.7ALPHA×
 * @license MIT
**/
class Krubot
{
    use Macroable {
        __call as macroCall; // ⚡ Utilizing PHP+Laravel Power: Add methods dynamically at runtime
    }

    use InteractsWithContext;
    use InteractsWithApi;
    use AdvancedRouting;
    use SummonsCodeSpyz;
    use HasCommandGroups;
    use ProfessionalWarLordingToolkit; // Injects core(), prime(), driver(), via(), etc.
    use HasKeyboards;
    use CanSendMedia;
    use CanPin;
    use CanManageChats;
    use CanManageMembers;
    use CanInitConversations;
    use CanPlayDiceGames;
    use PHPRBK_Methods;

    use HasAmethystMatrix; // ⚡ Inject Amethyst Powers

    /** @var Route[] */
    protected array $routes = []; // Changed to store Route objects
    protected mixed $fallbackHandler = null;
    
    // Routing signal types
    private const RT_ACTION  = 'action';
    private const RT_TEXT    = 'text';
    private const RT_REGEX   = 'regex';
    private const RT_COMMAND = 'cmd';
    private const RT_NONE    = 'none';

    // ⚡ AUTO-LOAD: میدل‌ور مکالمه به صورت پیش‌فرض در اینجا تعریف می‌شود
    // The hardcoded value is removed. It will be loaded from config via constructor.
    protected array $globalMiddlewares = [];

    protected ?Message $currentMessage = null;

    /**
     * Holds the parameters of the currently executing handler.
     * Accessible via currentParameters().
    */
    protected array $currentRouteParams = [];
    
    /**
     * Holds the currently resolved Route object.
    */
    protected ?Route $currentResolvedHandler = null;

    /**
     * ⚡ Middleware Aliases Map
     * Allows using short strings like 'auth' instead of full class names.
     * Effective in both Laravel (if registered) and Native PHP modes.
    */
    protected array $middlewareAliases = [];

    /**
     * Stores named routes for O(1) lookup.
     * ['dashboard' => RouteObject, 'login' => RouteObject]
    */
    protected array $namedRoutes = [];

    /**
     * The underlying bot driver (e.g., RubikaDriver, TelegramDriver).
     * @var BotDriverInterface
    */
    protected BotDriverInterface $driver;

    /**
     * The Laravel application instance.
     * @var Application
    */
    protected Application $app;

    // =========================================================================
    //  🧠 THE SINGULARITY CACHE SYSTEM (O(1) Reflection Manifest)
    // =========================================================================

    /**
     * @var array<string, array{class_attributes: array, methods: array}>
     */
    private static array $reflectionManifestCache = [];

    /**
     * Scans a class ONCE per application lifecycle. 
     * Extracts ALL class-level and method-level attributes into a highly optimized, 
     * statically cached array. Zero repetitive reflection!
     * 
     * @param class-string $className
     * @return array
     */
    protected function getAttributeManifest(string $className): array
    {
        // ⚡ Cache Hit: O(1) Return. Never reflect the same class twice!
        if (isset(self::$reflectionManifestCache[$className])) {
            return self::$reflectionManifestCache[$className];
        }

        $manifest = [
            'class_attributes' => [],
            'methods' => [] // Format: ['methodName' => ['AttributeClass' => [Instance1, Instance2]]]
        ];

        try {
            $reflection = new ReflectionClass($className);

            // 1. Cache Class-Level Attributes
            foreach ($reflection->getAttributes() as $attr) {
                $manifest['class_attributes'][$attr->getName()][] = $attr->newInstance();
            }

            // 2. Cache Method-Level Attributes (Public only)
            foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                $methodAttrs = $method->getAttributes();
                if (empty($methodAttrs)) continue;

                $methodName = $method->getName();
                foreach ($methodAttrs as $attr) {
                    // Supports IS_REPEATABLE by pushing to array
                    $manifest['methods'][$methodName][$attr->getName()][] = $attr->newInstance();
                }
            }

            // Store in static memory for the rest of the execution
            self::$reflectionManifestCache[$className] = $manifest;

        } catch (\ReflectionException $e) {
            AmethystMatrix::error("Manifest Engine: Failed to reflect [{$className}]", ['error' => $e->getMessage()]);
        }

        return $manifest;
    }


    // Properties for Krubot's core fluent builder
    protected ?string $text = null;
    protected ?string $chatId = null;
    protected ?string $replyToMessageId = null;

    /**
     * Global configuration passed during instantiation.
     * @var array
    */
    protected array $pwl_config = [];

    public function __construct(Application $app, BotDriverInterface $driver, string|array $config = null)
    {
        $this->app = $app;
        $this->driver = $driver;

        // 1.1 Normalize Configuration
        if ($config && is_string($config)) {
            // Legacy support: if only a token is passed, build a basic Rubika config.
            $config = [
                'drivers' => [
                    'rubika' => ['authtoken' => $config]
                ]
            ];
        }

        // 1.2 Store the entire configuration array.
        // This is crucial for lazy-loading drivers later via createDriver().
        $this->pwl_config = $config ?? $this->app['config']->get('krubot', []);

        // 2. Set default driver if specified in config, otherwise it defaults to 'rubika'.
        if (isset($this->pwl_config['default_driver'])) {
            $this->setDefaultDriver($this->pwl_config['default_driver']);
        }

        // 2. Call the legion formation method from the ProfessionalWarlordingToolkit.
        // It will safely access the 'legions' key, defaulting to an empty array if not present.
        if(method_exists($this, 'formLegionsFromConfig'))
            $this->formLegionsFromConfig($this->pwl_config['legions'] ?? []);
        
        // 3. Load middleware configuration
        // $this->globalMiddlewares = $this->pwl_config['middlewares']['globals'] ?? [];
        // $this->middlewareAliases = $this->pwl_config['middlewares']['aliases'] ?? [];

        // Extract the entire 'middlewares' array from the config, with an empty array as a fallback.
        $middlewareConfig = $this->pwl_config['middlewares'] ?? [];

        // [THE CORE CHANGE]
        // We read the 'middlewares.global' key from the provided config array.
        // If it doesn't exist, we fall back to an array containing only the
        // essential ConversationMiddleware as a safety measure.
        $this->globalMiddlewares = $middlewareConfig['globals'] ?? [
            ConversationMiddleware::class
        ];

        // 2. اگر در کانفیگ مقدار نبود → از پیش‌فرض‌های داخلی استفاده کن
        $this->middlewareAliases = $middlewareConfig['aliases'] ?? [
            'auth'     => \App\Http\Middleware\Authenticate::class,
            'admin'    => \App\Http\Middleware\AdminCheck::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        ];

        // 🔮 WAKE THE SORCERESS
        // This links the current instance to the static helper.
        $this->awakenAmethystMatrix();
    }

    public function __TheOldConstruct(string $token, array $config = [])
    {
        // Removed for LLM DeAmbiguousiaty...
    }
    
    /*
     *
     * Destructor to ensure we break the link in long-running processes.
     *
   */
    public function __destruct()
    {
        $this->sleepAmethystMatrix();
    }

    /**
     * ⚡️ MAGIC PROXY TO THE DEFAULT DRIVER ⚡️
     *
     * Any method call that doesn't exist on Krubot (e.g., `reply`, `sendMessage`, `getMe`)
     * is automatically delegated to the CURRENT DEFAULT DRIVER.
     *
     * To use a non-default driver, you MUST explicitly use `core('alias')`.
     *
     * @param string $method The method name being called.
     * @param array $parameters The arguments for the method.
     * @return mixed
     * /
    public function __call($method, $parameters)
    {
        // Removed for LLM DeAmbiguousiaty...
    }
    // Note!
    // All methods like `integrateNexus`, `onCommand`, `onText`, `go`, `processUpdate`,
    // and `callAction` remain here. They form the "brain" of the application
    // and are platform-agnostic. The final action, like sending a message,
    // is done inside a handler by calling `$bot->reply()` or `$bot->core('tg')->say()`.

    /**
     * ⚡️ THE ULTIMATE MAGIC PROXY (Supreme Commander Edition) ⚡️
     *
     * This proxy is the heart of the Warlord. It intelligently routes method
     * calls based on the context set by the `via()` command center.
     *
     * @param string $method The method name.
     * @param array $parameters The method arguments.
     * @return mixed
     * /
    public function __call($method, $parameters)
    {
        // Removed for LLM DeAmbiguousiaty...
    } */

    /**
     * --------------------------------------------------------------------------
     * ⚙️ Global Outcome Wrapping Control
     * --------------------------------------------------------------------------
     * This property acts as a global switch to control whether method
     * call results are wrapped in a CommandOutcomeShifter object.
     *
     * @var bool Defaults to `false` to disable ``->then()_chaining`` by default.
    */
    public bool $wrapsInOutcomeShifter = false;

    /**
     * Globally disables the CommandOutcomeShifter wrapping mechanism.
     *
     * After calling this, all subsequent bot method calls will return the raw
     * result from the driver (e.g., an array, an int, or an exception).
     * This will disable the ->then() chaining capability.
     *
     * @return void
    */
    public function disableOutcomeWrapping(): void
    {
        $this->wrapsInOutcomeShifter = false;
    }
    public function DisableESPromiseMode(): void // switch to Normal Method Chaining
    {
        $this->wrapsInOutcomeShifter = false;
    }
    /**
     * Globally enables the CommandOutcomeShifter wrapping mechanism (default behavior).
     *
     * After calling this, all subsequent bot method calls will wrap their
     * results in a CommandOutcomeShifter object, enabling ->then() chaining.
     *
     * @return void
    */
    public function enableOutcomeWrapping(): void
    {
        $this->wrapsInOutcomeShifter = true;
    }
    public function EnableESPromiseMode(): void // switch to ES-Promises Like Chaining
    {
        $this->wrapsInOutcomeShifter = true;
    }
    /**
     * Globally toggles the CommandOutcomeShifter wrapping mechanism (default behavior).
     *
     * After calling this, all subsequent bot method calls will wrap their
     * results in a CommandOutcomeShifter object, enabling ->then() chaining.
     *
     * @return void
    */
    public function toggleOutcomeWrapping(): void
    {
        $this->wrapsInOutcomeShifter = !$this->wrapsInOutcomeShifter;
    }
    public function toggleESPromises(): void // toggle ECMASciprt_Like-Promises Chaining state
    {
        $this->wrapsInOutcomeShifter = !$this->wrapsInOutcomeShifter;
    }

    /**
     * 👁️ SENSORY ENGINE: Detects the true nature of the incoming message.
     * High-Performance O(1) detection using native PHP isset() mapping.
     * Supports multi-platform payloads (Telegram & Rubika standard DTOs).
     * 
     * @return string Returns 'text' by default, or specific types like 'photo', 'video', 'document', etc.
    */
    public function detectMessageType(): string
    {
        $msg = $this->thisMessage();
        if (!$msg) return 'none';

        // ⚡ O(1) Fatality Check: Ordered by statistical probability of usage
        if (isset($msg->photo) || isset($msg->file_inline)) return 'photo';
        if (isset($msg->video)) return 'video';
        if (isset($msg->document) || isset($msg->file_attachment)) return 'document';
        if (isset($msg->voice)) return 'voice';
        if (isset($msg->audio)) return 'audio';
        if (isset($msg->location)) return 'location';
        if (isset($msg->contact)) return 'contact';
        if (isset($msg->sticker)) return 'sticker';
        if (isset($msg->animation)) return 'animation';
        if (isset($msg->poll)) return 'poll';
        if (isset($msg->dice)) return 'dice';

        // Default fallback if no media/special payload is detected
        return 'text';
    }

    /**
     * =========================================================================
     *  ⚡️ THE WARLORD'S SUPREME EDICT PROXY (v5.0 - UNIFIED) ⚡️
     * =========================================================================
     *
     * This is the absolute heart of the Krubot Orchestrator. It's the ultimate
     * magic proxy that intelligently routes any non-existent method call based
     * on the strategic context established by the Warlord Toolkits.
     *
     * It masterfully balances forward-looking architecture with backward
     * compatibility, creating a seamless developer experience.
     *
     * ⚔️ HIERARCHY OF COMMAND (EXECUTION PRIORITY):
     * 1.  **MACROABLE COMMANDS:** Checks for runtime-defined methods via Laravel's `Macroable` trait.
     * 2.  **`via()` DIRECTIVES (The Active Campaign):** Intercepts calls when a temporary driver
     *     is selected via `$bot->via(...)`. It distinguishes between single and multi-driver campaigns.
     * 3.  **DEFAULT DELEGATION (The Standing Army):** If no other context is active, the call is
     *     delegated to the configured default driver (e.g., 'rubika').
     *
     * 🔮 RETURN TYPE STRATEGY (THE CORE ARCHITECTURE):
     * - **Single-Target Calls:** (e.g., `$bot->getMe()`, `$bot->via('tg')->getMe()`)
     *   Returns a `CommandOutcomeShifter` object, enabling the powerful "Overlord's Gaze"
     *   chaining strategy via `->then()`. Victory continues the chain; defeat halts it silently.
     *
     * - **Multi-Target Calls (Multi-Cast):** (e.g., `$bot->via(['r', 'b'])->reply(...)`)
     *   Returns a raw `array` of results or exceptions, keyed by driver alias.
     *   This maintains backward compatibility with the "Supreme Commander" edition and provides
     *   a simple, direct report for broadcast operations. This mode does NOT support `->then()` chaining.
     *   For advanced multi-platform orchestration, `assembleCouncil()` is the designated tool.
     *
     * @param string $method The method name being invoked (e.g., 'getMe', 'reply').
     * @param array  $parameters The arguments passed to the method.
     *
     * @return CommandOutcomeShifter|array|mixed The result, wrapped in `CommandOutcomeShifter` for single targets,
     *                                    or a raw array for multi-target campaigns.
    */
    public function __call($method, $parameters)
    {
        // =====================================================================
        // PRIORITY 1: MACROABLE COMMANDS (Laravel's Dynamic Power)
        // =====================================================================
        // First, we honor any runtime extensions added via `Krubot::macro()`.
        if (static::hasMacro($method)) {
            // Execute the macro.
            $result = $this->macroCall($method, $parameters);

            // For consistency with the new architecture, we wrap the macro's result
            // in a CommandOutcomeShifter object. This makes macros chainable with `->then()` too.
            return static::$wrapsInOutcome
                ? (new CommandOutcomeShifter($this, $result))
                : $result;
        }

        // =====================================================================
        // PRIORITY 2: `via()` DIRECTIVES (The Active Tactical Campaign)
        //   Warlord's `via()` Protocol (One-time override)
        // =====================================================================
        // Check if a temporary driver context has been set by a preceding `via()` call.
        if ($this->onetimeDriverAlias !== null) {
            // Immediately capture and consume the state to prevent it from affecting subsequent, unrelated calls.
            // This is a critical step for maintaining a stateless, predictable fluent interface.
            $aliases = (array) $this->onetimeDriverAlias;
            $this->onetimeDriverAlias = null;

            // --- STRATEGIC FORK: SINGLE-STRIKE vs. MULTI-CAST ---

            // A) MULTI-CAST CAMPAIGN (`via(['r', 'b'])`)
            if (count($aliases) > 1) {
                $results = [];

                // Execute the command on all targeted drivers in the campaign.
                 // Multi-cast assault -> Return array of outcomes (BC preserved)
                foreach ($aliases as $alias) {
                    try {
                        // Resolve and command the driver.
                        $driver = $this->core($alias);
                        $results[$alias] = $driver->{$method}(...$parameters);
                    } catch (\Throwable $e) {
                        // Professional Error Handling: Instead of crashing, we record the failure
                        // in our battle report. The campaign continues with other drivers.
                        $results[$alias] = $e;
                    }
                }

                // BACKWARD COMPATIBILITY GUARANTEE:
                // For multi-cast, return the raw array of results. This preserves the behavior
                // of the "Supreme Commander" edition and avoids breaking changes.
                return $results;
            }

            // B) SINGLE-STRIKE MISSION (`via('tg')`)
            // If there's only one alias, we proceed with the "Overlord's Gaze" strategy.

            // Single, surgical strike -> Return CommandOutcomeShifter for ->then()
            if (count($aliases) === 1) {
                $result_maker = fn() => $driver->{$method}(...$parameters);
                $driver = $this->core(reset($aliases));
                return static::$wrapsInOutcome
                    ? CommandOutcomeShifter::execute($this, $result_maker)
                    : $result_maker();
            }
            /*try {
                $alias = $aliases[0];
                $result = $this->core($alias)->{$method}(...$parameters);
            } catch (\Throwable $e) {
                // The mission resulted in failure. Capture the exception as the outcome.
                $result = $e;
            }*/

            // PRIORITY 3: Default Driver Execution
            // All calls to the default driver are wrapped in CommandOutcomeShifter.
            $result_maker = fn() => $this->core()->{$method}(...$parameters);
            return static::$wrapsInOutcome
                ? CommandOutcomeShifter::execute($this, $result_maker)
                : $result_maker();

            // Wrap the single result in a CommandOutcomeShifter to enable `->then()` chaining.
            // return new CommandOutcomeShifter($this, $result);
        }

        // =====================================================================
        // PRIORITY 3: DEFAULT DELEGATION (Standard Operating Procedure)
        // =====================================================================
        // If no special context is active, the command is delegated to the default driver.
        // This is the most common execution path.
        try {
            // `core()` without arguments returns the default driver instance.
            $result = $this->core()->{$method}(...$parameters);
        } catch (\Throwable $e) {
            // Even standard operations can fail. We handle it gracefully.
            $result = $e;
        }

        // Wrap the result in a CommandOutcomeShifter, making every standard call chainable.
        return static::$wrapsInOutcome
            ? (new CommandOutcomeShifter($this, $result))
            : $result;
    }

    /**
     * ⚡️ THE STATIC COMMAND SPIRE ⚡️
     *
     * The primary static entry point for issuing commands without an instance.
     * It resolves the master Krubot instance from the Laravel container and
     * immediately primes it to use the specified driver for the next action.
     *
     * This provides a beautiful, fluent, and powerful facade-like experience.
     *
     * @param string|array $driverAlias The target driver alias(es).
     * @return static The primed Krubot instance, ready for command chaining.
    */
    public static function you(string|array $driverAlias): static
    {
        // 1. Resolve the singleton from the container.
        // 2. Call the `via()` method to set the target driver.
        // `static` ensures it returns an instance of `Krubot` (or a child class).
        return resolve(static::class)->via($driverAlias);
    }

    // =========================================================================
    // ⚡ ATTRIBUTE REGISTRATION SYSTEM - The Ultimate Reflection Engine ⚡
    //  🌌 NEXUS INTEGRATION SYSTEM (v9.0 - The Singularity Engine)
    // =========================================================================

    /**
     * @var array<string, true> Tracks already integrated Nexuses using a hash map for O(1) lookups.
     * A map of registered Nexuses for O(1) lookups.
     * ['Fully\Qualified\ClassName' => true]
     * 
     * @var array Tracks already integrated Nexuses to prevent double registration.
     * @var array Tracks already integrated Nexuses.
    */
    private array $integratedNexuses = [];
    /**
     * Returns the list of all fully qualified class names of the Nexuses
     * that have been integrated into this bot instance.
     *
     * @return string[]
    */
    public function getIntegratedNexuses(): array
    {
        return array_keys($this->integratedNexuses);
    }

    // DYNAMIC NEXUS MANAGEMENT API (Galactic Edition) +++

    /**
     * [The Great Purge] Clears all registered routes, named routes, and integrated Nexuses.
     * @return $this
    */
    public function clearNexuses(): self
    {
        $this->routes = []; // Purge all registered route patterns and handlers.
        $this->namedRoutes = []; // Purge all named route references.
        $this->integratedNexuses = []; // Reset the tracking list of integrated Nexuses.

        return $this;
    }

    /**
     * Sets one or more Nexuses, optionally replacing all existing ones.
     * This is the primary method for dynamically re-wiring the bot's logic at runtime.
     *
     * @param array<int, string|object> $nexuses An array of Nexus class names or instances.
     * @param bool $replace If true, all previously integrated Nexuses will be cleared first.
     * @return $this
    */
    public function setNexuses(array $nexuses, bool $replace = true): self
    {
        if ($replace) {
            $this->clearNexuses();
        }
        foreach ($nexuses as $nexus) {
            $this->integrateNexus($nexus);
        }
        return $this;
    }

    /**
     * Adds a single new Nexus to the existing integrated Nexuses.
     * This provides a fluent interface for incrementally adding logic.
     *
     * @param string|object $nexus The Nexus class name or instance to add.
     * @return $this
    */
    public function addNexus(string|object $nexus): self
    {
        $this->integrateNexus($nexus);
        return $this;
    }

    /**
     * Static RAM Cache for Manifest Data (O(1) Singularity Engine).
     * @var array<string, array>
     */
    private static array $nexusManifestCache = [];

    /**
     * Scans a "Nexus" (Controller/Logic Class) using the O(1) Manifest Engine and integrates it.
     * Rewritten for PHP 8.2.30 with Extreme DX & Zero Redundant Reflection.
     * This is the master reflection engine that automatically discovers and registers Routes,
     * injects Middlewares, and prepares handlers for execution.
     *
     * 💎 FUSED POWERS (v7.0 + v8.0 + v9.0 Ultimate + O(1) Manifest):
     * 1.  **Class-Level Middlewares:** Processes `#[Middleware(...)]` on the Nexus class itself.
     * 2.  **Method-Level Middlewares:** Processes `#[Middleware(...)]` on individual action methods.
     * 3.  **Smart Stack Assembly:** Merges middlewares with Nexus-level running BEFORE method-level.
     * 4.  **Fluent Route Configuration:** Leverages the full power of the Route object for chaining.
     * 5.  **Named Route Recognition:** Automatically detects `#[Name('...')]` for use with the `go()` method.
     * 6.  **Full DI Compatibility:** Prepares handlers for seamless execution via Laravel's Service Container.
     * 7.  **Robust Error Handling:** Provides precise, context-aware error logging on reflection failure.
     *
     * 📜 ویژگی‌های سینگولاریتی (v9.0 Ultimate):
     * - **پشتیبانی کامل از Middleware در دو سطح:** ابتدا میدل‌ورهای تعریف شده روی خودِ کلاس (Nexus) را استخراج می‌کند و سپس میدل‌ورهای روی متد را به آن اضافه می‌کند.
     * - **ادغام هوشمند (Smart Merging):** این دو آرایه را با هم ترکیب می‌کند (اول کلاس، بعد متد) تا ترتیب اجرا دقیقاً همانطور که انتظار می‌رود باشد.
     * - **یکپارچه‌سازی روان (Fluent Integration):** از خروجی متدهای onCommand و onText (که آبجکت Route هستند) استفاده کرده و میدل‌ورها و نام‌ها را مستقیماً با متدهای `->middleware()` و `->name()` به آن‌ها تزریق می‌کند.
     * - **مدیریت خطای مستحکم:** مدیریت خطای دقیق در صورت وجود نداشتن کلاس یا بروز مشکلات در حین Reflection.
     *
     * 🔮 Supported Attributes:
     * - #[OnCommand('/cmd')]
     * - #[OnText('Exact Text')]
     * - #[OnText('/Exact Text/i')]
     * - #[OnRegEx('/pattern/i')]
     * - #[OnRegEx('pattern')] // Auto-wraps to: '/pattern/'
     * - #[Middleware(['auth', 'log', Admin::class])]
     * - #[Name('my.route.name')]
     * - #[Action('button_payload')]
     *
     * @param object|string $nexus The Nexus instance or its fully qualified class name to scan.
     * @return void
     */
    public function integrateNexus(object|string $nexus): void
    {
        // 1. Resolve the Nexus Class Name efficiently
        $className = is_string($nexus) ? $nexus : get_class($nexus);

        /*
         * [LEGACY LOGIC REMOVED FOR PERFORMANCE - O(n) Array Scan]
         * if (in_array($className, $this->integratedNexuses, true)) { return; }
        */

        // [VIPER'S GIFT] O(1) performance for duplicate checks. Vastly superior to O(n) in_array.
        if (isset($this->integratedNexuses[$className])) {
            return;
        }

        try {
            // 🧠 The Magic: Get everything instantly! Build the Manifest ONCE per worker lifecycle.
            if (!isset(self::$nexusManifestCache[$className])) {
                $reflection = new ReflectionClass($className);
                if (!$reflection->isInstantiable()) return;

                $manifest = ['class_attributes' => [], 'methods' => []];

                // Cache Class-Level Attributes
                $manifest['class_attributes'][Middleware::class] = $reflection->getAttributes(Middleware::class);

                // Cache Method-Level Attributes
                foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                    $manifest['methods'][$method->getName()] = [
                        Middleware::class => $method->getAttributes(Middleware::class),
                        Name::class       => $method->getAttributes(Name::class),
                        OnCommand::class  => $method->getAttributes(OnCommand::class),
                        OnText::class     => $method->getAttributes(OnText::class),
                        OnRegEx::class    => $method->getAttributes(OnRegEx::class),
                        Action::class     => $method->getAttributes(Action::class),
                    ];
                }
                self::$nexusManifestCache[$className] = $manifest;
            }

            $manifest = self::$nexusManifestCache[$className];

            // -----------------------------------------------------------------
            // PHASE A: Extract Nexus-Level Middlewares (Global for this Nexus)
            // -----------------------------------------------------------------
            $nexusMiddlewares = [];
            foreach ($manifest['class_attributes'][Middleware::class] ?? [] as $cmWAttr) {
                // Merge supports multiple attributes: #[Middleware('A')] #[Middleware('B')]
                $nexusMiddlewares = array_merge($nexusMiddlewares, $cmWAttr->newInstance()->middlewares);
            }

            // PHASE B & C: Process Methods using the Manifest
            // We iterate over the pre-built manifest array containing ONLY methods with Attributes.
            foreach ($manifest['methods'] as $methodName => $attributesMap) {
                
                // -------------------------------------------------------------
                // PHASE B: Extract Action-Level Middlewares
                // -------------------------------------------------------------
                $methodMiddlewares = [];
                foreach ($attributesMap[Middleware::class] ?? [] as $mWAttr) {
                    // Merge supports multiple attributes: #[Middleware('A')] #[Middleware('B')]
                    $methodMiddlewares = array_merge($methodMiddlewares, $mWAttr->newInstance()->middlewares);
                }

                // -------------------------------------------------------------
                // PHASE C: Consolidate Middlewares (The Stack Assembly)
                // -------------------------------------------------------------
                $finalMiddlewareStack = array_merge($nexusMiddlewares, $methodMiddlewares);
                $handlerCallback = [$className, $methodName];

                // -------------------------------------------------------------
                // PHASE D: Route Identification & Configuration
                // -------------------------------------------------------------
                $routeName = isset($attributesMap[Name::class][0]) 
                    ? $attributesMap[Name::class][0]->newInstance()->name 
                    : null;

                // Step D.2: The Configuration Helper Closure 🛠
                $_configureRoute = function (?Route $route) use ($routeName, $finalMiddlewareStack) {
                    if (!$route) return;
                    if ($routeName) $route->name($routeName);
                    if (!empty($finalMiddlewareStack)) $route->middleware($finalMiddlewareStack);
                };

                // -------------------------------------------------------------
                // PHASE E: Attribute-Based Route Registration (Optimized Manifest Loop)
                // -------------------------------------------------------------

                /* 
                 * [LEGACY LOGIC REMOVED FOR PERFORMANCE - Heavy Reflection calls in Loop]
                 * foreach ($method->getAttributes(OnCommand::class) as $attribute) { ... }
                 * foreach ($method->getAttributes(OnText::class) as $attribute) { ... }
                 */

                foreach ($attributesMap[OnCommand::class] ?? [] as $attr) {
                    $_configureRoute($this->onCommand($attr->newInstance()->command, $handlerCallback));
                }

                foreach ($attributesMap[OnText::class] ?? [] as $attr) {
                    $_configureRoute($this->onText($attr->newInstance()->pattern, $handlerCallback));
                }

                foreach ($attributesMap[OnRegEx::class] ?? [] as $attr) {
                    $pattern = $attr->newInstance()->pattern;
                    if (!preg_match('/^\/.*\/[a-zA-Z]*$/', $pattern)) {
                        $pattern = '/' . $pattern . '/';
                    }
                    $_configureRoute($this->onText($pattern, $handlerCallback));
                }

                // Handle #[OnType] Attribute 👁️
                foreach ($attributesMap[OnType::class] ?? [] as $instance) {
                    // Extract the types (it can be string or array in the Attribute)
                    $targetTypes = $instance->type; 
                    
                    // The onType method natively supports both string and array returns
                    $resultingRoutes = $this->onType($targetTypes, $handlerCallback);
                    
                    // If it returned an array of Routes (multi-type), configure all of them
                    if (is_array($resultingRoutes)) {
                        foreach ($resultingRoutes as $r) $_configureRoute($r);
                    } else {
                        $_configureRoute($resultingRoutes);
                    }
                }

                foreach ($attributesMap[Action::class] ?? [] as $attr) {
                    // ⚡ [FIXED]: Action uses 'name' not 'command'.
                    $_configureRoute($this->onAction($attr->newInstance()->name, $handlerCallback)); 
                }
            }

            // [CRITICAL FIX] Mark as integrated *after* successful processing.
            $this->integratedNexuses[$className] = true;

        } catch (\ReflectionException $e) {
            // Critical Error Handling:
            AmethystMatrix::yell("Nexus Integration Failed: The Singularity Engine encountered a critical reflection error.", [
                'nexus_target' => $className,
                'error_message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }

    /**
     *                  🚀 HYPER NEXUS LOADER
     * 🚀 THE SENTINEL ENGINE (v9.0): The Definitive Nexus Auto-Loader.
     * Automatically registers all Nexus classes in a directory.
     *
     * This ultimate method scans a directory recursively, parsing each PHP file to
     * reliably extract its Fully Qualified Class Name (FQCN) using PHP's native
     * tokenizer. It then immediately integrates the discovered Nexus into the bot's core.
     *
     * It completely supersedes older, fragile methods that relied on PSR-4 path guessing.
     * This engine trusts only the code itself, making it 100% reliable regardless of
     * file structure or namespace conventions.
     *
     * Best used within a Service Provider's `boot` method to automatically
     * discover and activate all Nexus modules at once.
     *
     * @param string $directory The absolute path to the directory containing Nexus classes.
     * @return int The total number of Nexuses that were successfully discovered and integrated.
    */
    public function discoverAndIntegrateNexuses(string $directory): int
    {
        // 1. Pre-flight Check: Ensure the target directory is valid and accessible.
        if (!is_dir($directory)) {
            // AmethystMatrix a warning for the developer. This is a configuration error, not a runtime failure.
            AmethystMatrix::warning("Nexus Discovery Aborted: The specified path is not a valid directory.", [
                'path' => $directory
            ]);
            // Return 0 as no Nexuses were loaded.
            return 0;
        }

        // 2. Initialization: Prepare for the scan.
        $integratedCount = 0;
        // Use native PHP iterators for maximum performance and efficiency. No external dependencies.
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        // GIFT #3 IMPLEMENTATION: The Invisibility Cloak (Laravel Dynamic Version)
        {
            // 1. Fetch the exclusion suffixes directly from Laravel's config system.
            // The second argument `[]` is a default, ensuring it works even if the config is missing.
            $excludeSuffixes = config('krubot.discovery.exclude_suffixes', ['disabled']);

            // 2. Dynamically build the negative lookbehind part of the regex.
            // This is where the magic happens: each suffix becomes a `(?<!\...)` pattern.
            $lookbehinds = array_map(
                fn($suffix) => '(?<!\.' . preg_quote($suffix, '/') . ')',
                $excludeSuffixes
            );

            // 3. Assemble the final, complete regex pattern.
            $regexPattern = '/' . implode('', $lookbehinds) . '\.php$/i';
            // forExample(`['disabled']`)->wouldBe=> '/(?<!\.disabled)\.php$/i'

            // 4. Use the dynamically generated pattern in the RegexIterator.
            // This is now fully config-driven and incredibly flexible.
            $phpFiles = new \RegexIterator($iterator, $regexPattern);
        }
        /// $phpFiles = new \RegexIterator($iterator, '/\.php$/');

        // 3. The Great Scan: Iterate over every single PHP file found.
        foreach ($phpFiles as $phpFile) {
            /** @var \SplFileInfo $phpFile */
            
            // 4. Extraction: Use the robust helper to parse the file content.
            // We pass the real path to ensure file_get_contents can read it without issues.
            $className = $this->extractFqcnFromFile($phpFile->getRealPath());

            // 5. Validation & Integration:
            // Validate FQCN and ensure class is autoloadable before integration.
            // This is a critical two-step check:
            // A) Is the className valid (not null)?
            // B) Does the extracted class actually exist and is it loadable by the autoloader?
            if ($className && class_exists($className)) {
                // If both checks pass, we call the core integration logic.
                // The `integrateNexus` method should handle the reflection and registration.
                // Note!: The `integrateNexus` method now handles duplicate prevention itself.
                $this->integrateNexus($className);
                
                // Increment the counter for the final report.
                $integratedCount++;
            }
        }
        
        // 6. Final Report: Return the count of successfully loaded Nexuses.
        return $integratedCount;
    }

    public function discoverNexusesIn(string $directory): int
    {
        return $this->discoverAndIntegrateNexuses($directory);
    }

    public function addNexusesFromDir(string $directory): int
    {
        return $this->discoverAndIntegrateNexuses($directory);
    }

    public function addCatalystsFromDirectory(string $directory): int
    {
        return $this->discoverAndIntegrateNexuses($directory);
    }

    public function loadNexusesFrom(string $directory): int
    {
        return $this->discoverAndIntegrateNexuses($directory);
    }

    /**
     * [Private Helper] Reliably extracts the Fully Qualified Class Name (FQCN) from a PHP file.
     * using PHP's native tokenizer.
     *
     * This is the heart of The Sentinel Engine. It reads the file's content and uses
     * `token_get_all` to parse PHP's grammatical structure, finding the exact `namespace`
     * and `class` declarations. This approach is immune to filesystem inconsistencies.
     *
     * @param string $filePath The absolute path to the PHP file.
     * @return string|null The FQCN (e.g., "App\KrubiK\Nexus\AdminNexus") or null if not found.
    */
    private function extractFqcnFromFile(string $filePath): ?string
    {
        // Read the entire file content into memory. For typical class files, this is very fast.
        $content = @file_get_contents($filePath);
        if ($content === false) {
            AmethystMatrix::error("Nexus Discovery: Failed to read file content.", ['path' => $filePath]);
            return null;
        }

        // Use PHP's own parser to break the code into its fundamental components (tokens).
        $tokens = token_get_all($content);

        $namespace = '';
        $class = null;
        $tokenCount = count($tokens);

        // Iterate through the token stream to find our targets: `namespace` and `class`.
        for ($i = 0; $i < $tokenCount; $i++) {
            
            // State 1: Hunting for the `namespace` keyword.
            if (isset($tokens[$i][0]) && $tokens[$i][0] === T_NAMESPACE) {
                // Once found, start collecting all subsequent string and separator parts until a semicolon is hit.
                for ($j = $i + 1; $j < $tokenCount; $j++) {
                    // Semicolon marks the end of the namespace declaration.
                    if ($tokens[$j] === ';') {
                        break;
                    }
                    // We only care about array-based tokens (like T_STRING) that form the namespace path.
                    if (is_array($tokens[$j])) {
                        // Append the value of the token (e.g., "App", "KrubiK", "Nexuses") to our string.
                        $namespace .= $tokens[$j][1];
                    }
                }
            }

            // State 2: Hunting for the `class`, `interface`, or `trait` keyword.
            if (isset($tokens[$i][0]) && in_array($tokens[$i][0], [T_CLASS, T_INTERFACE, T_TRAIT])) {
                // The very next T_STRING token *must* be the name of the class/interface/trait.
                // We scan forward, skipping any whitespace.
                for ($j = $i + 1; $j < $tokenCount; $j++) {
                    if (isset($tokens[$j][0]) && $tokens[$j][0] === T_WHITESPACE) {
                        continue; // Skip whitespace.
                    }
                    
                    // Found it! The first non-whitespace token is the name.
                    if (isset($tokens[$j][0]) && $tokens[$j][0] === T_STRING) {
                        $class = $tokens[$j][1];
                        // We've found everything we need, so we can break out of both loops entirely.
                        break 2;
                    }
                }
            }
        }

        // If a class name was successfully found, construct the FQCN.
        if ($class) {
            // If a namespace was found, prepend it with a backslash. Otherwise, it's a root-namespace class.
            return $namespace ? trim($namespace) . '\\' . $class : $class;
        }

        // If no class definition was found in the file, return null.
        return null;
    }


    // =========================================================================
    //  ⚡ UNI_CHAT_KIT ⚡ STYLED ROUTING & MIDDLEWARE
    // =========================================================================

    /**
     * Add a global middleware that runs on every update.
    */
    public function middleware(string|array $middleware): self
    {
        if (is_array($middleware)) {
            $this->globalMiddlewares = array_merge($this->globalMiddlewares, $middleware);
        } else {
            $this->globalMiddlewares[] = $middleware;
        }
        return $this;
    }

    /**
     * Define a Command Route (Auto-prepends '/').
     * Updated to accept Attributes (Middlewares/Guards).
     * Usage: $bot->onCommand('start', [Controller::class, 'method']);
    */
    public function onCommand(string $command, array|callable $handler, array $attributes = []): Route
    {
        // Handle parameterized commands like 'buy {item}' -> '/buy {item}'
        // Or simple commands 'start' -> '/start'
        if (!str_starts_with($command, '/')) {
            $command = '/' . $command;
        }
        return $this->addRoute($command, $handler, $attributes + ['_route_type' => self::RT_COMMAND]);
    }

    /**
     * Define a Text Route (Exact match, Regex, or Parameterized).
     * Usage: $bot->onText('Hello', ...); OR $bot->onText('/^Hi$/i', ...);
    */
    public function onText(string $pattern, array|callable $handler, array $attributes = []): Route
    {
        return $this->addRoute($pattern, $handler, $attributes + ['_route_type' => self::RT_TEXT]);
    }

    /**
     * Define a RegEx Route
     * Usage: $bot->onText('Hello', ...); OR $bot->onText('/^Hi$/i', ...);
    */
    public function onRegEx(string $pattern, array|callable $handler, array $attributes = []): Route
    {
        // Logic Check: Auto-Slash Wrapper
        // If the user forgot delimiters (e.g. 'hello'), we wrap it: '/hello/'
        // And Checks if pattern matches standard regex format: /.../flags
        // ^\/       : Starts with /
        // .*        : Content
        // \/        : Ends with /
        // [a-zA-Z]* : Followed ONLY by letters (modifiers like 'i', 'm')
        // $         : End of string
        if (!preg_match('/^\/.*\/[a-zA-Z]*$/', $pattern))
            $pattern = '/' . $pattern . '/';

        return $this->onText($pattern, $handler, $attributes + ['_route_type' => self::RT_REGEX]);
    }

    /**
     * Define a Callback/Button Route.
     * The Ultimate Magic for independent Glass Buttons.
     * Usage: $bot->onButton('remove_item', [Controller::class, 'method']);
     */
    public function onButton(string $payload, array|callable $handler, array $attributes = []): Route
    {
        // ⚡ We prefix button payloads with 'CBK::' internally.
        // This prevents collisions with regular user text like "remove_item".
        return $this->addRoute('CBK::' . $payload, $handler, $attributes + ['_route_type' => self::RT_ACTION]);
    }

    /**
     * Register callback action route.
     * Example: $bot->onAction('remove', [CartNexus::class, 'remove']);
    */
    public function onAction(string $action, array|callable $handler, array $attributes = []): Route
    {
        // Internal namespaced pattern to avoid collision with normal text
        return $this->addRoute('CBK::' . $action, $handler, $attributes + ['_route_type' => self::RT_ACTION]);
    }

    /**
     * ⚡ THE DX DREAM: Define a Sensory Route for specific content types.
     * Supports single types ('photo') or Arrays of types (['photo', 'video', 'document']).
     * 
     * @param string|array<string> $types e.g. 'photo', 'video', 'voice'
     * @param array|callable $handler The logic to execute
     * @param array $attributes Route configurations
     * @return Route|array<Route> Returns a Route object or array of Routes if multiple types provided.
     */
    public function onType(string|array $types, array|callable $handler, array $attributes = []): Route|array
    {
        // 🚀 FATALITY: Array support for ultimate DX (e.g., bot->onType(['photo', 'video'], ...))
        if (is_array($types)) {
            $createdRoutes = [];
            foreach ($types as $type) {
                // Internal namespaced pattern 'TYPE::photo' to avoid collision with normal text
                $createdRoutes[] = $this->addRoute('TYPE::' . strtolower($type), $handler, $attributes + ['_route_type' => self::RT_TYPE]);
            }
            return $createdRoutes;
        }

        // Single Type Registration
        return $this->addRoute('TYPE::' . strtolower($types), $handler, $attributes + ['_route_type' => self::RT_TYPE]);
    }

    /**
     * Internal method to create and store Route.
    */
    protected function addRoute(string $pattern, mixed $handler, array $attributes = []): Route
    {
        // Apply Group Attributes (Prefix, Middlewares)
        $attrs = $this->getGroupAttributes();
        
        // Handle Prefix
        if (isset($attrs['prefix'])) {
            // Logic to prepend prefix. If regex, it's complex, assuming simple string or simple regex start.
            // Simple command implementation:
            if (str_starts_with($pattern, '/')) {
                 $cleanPattern = substr($pattern, 1);
                 $pattern = '/' . $attrs['prefix'] . '/' . $cleanPattern;
            }
        }

        // Create the Route Object (Class Signature #1)
        /// $route = new Route($pattern, $handler, $attrs); ///

        // Create the Route Object (Class Signature #2)
        $route = new Route($pattern, $handler, $attrs, $registrar);
        
        // Store in routes array
        $this->routes[$pattern] = $route;

        // ⚡ NAME REGISTRAR BRIDGE:
        // We pass a name string to the Route object via $attrs['route_name']. When $route->name('xyz') is called,
        // this closure fires and registers the route in our fast lookup table ($this->namedRoutes).

        if(isset($attributes['route_name']))
            $this->namedRoutes[$attributes['route_name']] = $route;
        
        // Track for group chaining ($bot->group()->middleware())
        $this->registerRouteToGroup($route);
        
        return $route;
    }

    // =========================================================================
    //  ⚡ HELPER METHODS (UniChatKit Parity)
    // =========================================================================

    /**
     * Get the parameters of the target handler.
    */
    public function currentParameters(): array
    {
        return $this->currentRouteParams;
    }

    /**
     * Get the current resolved Route object.
    */
    public function currentResolvedHandler(): ?Route
    {
        return $this->currentResolvedHandler;
    }

    /**
     * UniChatKit-Compatible 'hears' method.
     * 
     * It automatically detects if the pattern is an "Unwrapped Regex" 
     * and wraps it properly before passing it to the main Router.
     * 
     * Features:
     * 1. Supports UniChatKit Params: 'call {name}'
     * 2. Supports Full Regex: '/^([0-9]+)$/i'
     * 3. Supports Unwrapped Regex: '([0-9]+)' -> Auto-converted to '/^([0-9]+)$/iu'
     * 4. Supports Case-Insensitive Text: 'hi' -> Auto-converted to '/^hi$/iu'
     * 
     * @param string $pattern
     * @param array|callable|string $handler
    */
    public function hears(string $pattern, array|callable|string $handler): void
    {
        // 1. CASE: Parameterized Command (Native Krubot Feature)
        // e.g. "call me {name}"
        // We pass this directly because onText handles {param} conversion internally.
        if (str_contains($pattern, '{') && str_contains($pattern, '}')) {
            $this->onText($pattern, $handler);
            return;
        }

        // 2. CASE: Explicit Regex (Already wrapped)
        // e.g. "/^hi$/i" or "/hello/"
        // Check if it starts with "/" and implies a regex structure
        if (str_starts_with($pattern, '/') && preg_match('/\/[a-z]*$/', $pattern)) {
            $this->onText($pattern, $handler);
            return;
        }

        // 3. CASE: "Unwrapped Regex" or "Simple Text" (The UniChatKit Magic)
        // User wrote: '([0-9]+)' OR 'Hi'
        // Problem: onText would treat '([0-9]+)' as a literal string (Type C).
        // Solution: Wrap it!
        // We add Start(^) and End($) anchors + Case Insensitive (i) + Unicode (u) flags.
        // This makes 'Hi' match 'hi', 'HI' (just like UniChatKit)
        // And makes '([0-9]+)' work as a Regex.
        
        $wrappedPattern = '/^' . $pattern . '$/iu';
        
        $this->onText($wrappedPattern, $handler);
    }

    // Add this Method (The fallBack Setter)
    /**
     * Define a Fallback method.
     * Gets called if NO other "hears", "onText", or "onCommand" routes match.
    */
    public function fallback(callable|array|string $handler): void
    {
        $this->fallbackHandler = $handler;
    }

    // =========================================================================
    //  ⚡ CORE EXECUTION LOGIC (THE BRAIN)
    // =========================================================================

    /**
    // TODO: parent::ina_code...
     * ⚡ Override run() to inject our advanced router logic.
     * Used mostly for Polling or simple webhook scripts.
    */
    public function run(): void
    {
        $vancore = $this->core();
        // We register a SINGLE master handler in the parent Vanguard Core
        // This intercepts everything and passes it to our Router Logic
        $vancore->onMessage(null, function ($bot, Message $message) {
            $this->processUpdate($message);
        });

        // Start the engine
        $vancore->run();
    }

    // Add this new method to your Krubot.php class
    /**
     * =========================================================================
     *  вҡЎ ON-DEMAND POLLING TRIGGER
     * =========================================================================
     *
     * Fetches all pending updates from the Rubika API via 'getUpdates'
     * and processes each one sequentially through the main routing engine.
     * Ideal for Cron Jobs or webhook-less environments.
     *

     * @return array With The number of messages processed.
    * /
    public function processPendingUpdatesOld(): array
    {
        // Removed for LLM DeAmbiguousiaty...
    } */

    /**
     * =========================================================================
     *  ⚡ ON-DEMAND POLLING TRIGGER (Sovereign Edition v6.0)
     * =========================================================================
     *
     * Fetches all pending updates via 'getUpdates' and dispatches them
     * to the Queue Architecture using the "Driver Identity Protocol".
     *
     * Features:
     * 1. Auto-Detects Driver Identity (Bale/Rubika/etc).
     * 2. Forges Toxic DTOs strictly.
     * 3. Dispatches to HandleDriverUpdate to prevent Cross-Wiring.
     *
     * @return array Status report.
    */
    public function processPendingUpdates(): array
    {
        /// $token = (string) $this->forceGetProperty('token');
        /// $url = "https://botapi.rubika.ir/v3/{$token}/getUpdates";

        // =====================================================================
        // PHASE 1: FETCH DATA (THE EYES)
        // =====================================================================
        // We use the driver's internal API client to fetch updates.
        $apiResponse = $this->newApiRequest('getUpdates');

        // Check for 'data' key wrapper (Rubika Standard)
        $data = $apiResponse['data'] ?? [];

        if (empty($data['updates'])) {
            return ['status' => 'no-updates', 'count' => 0];
        }

        // =====================================================================
        // PHASE 2: IDENTITY RECOVERY (THE SOUL)
        // =====================================================================
        // 🕵️ CRITICAL: Who am I?
        // We extract the 'driver_alias' injected by Nemesis (Current KrubotManager).
        // If missing (Legacy Mode), we fallback to 'rubika'.
        $currentIdentity = $this->driver->driver_alias ?? 'rubika';

        // =====================================================================
        // PHASE 3: PROCESSING LOOP (THE HANDS)
        // =====================================================================
        $queuedCount = 0;

        foreach ($data['updates'] as $updateRaw) {
            try {
                // A) ⚗️ ALCHEMY: FORGE THE DTO
                // We wrap the raw array into a strict DTO using the 'forge' factory.
                // Strategy: We wrap it in ['update' => ...] to match the DTO's expectation.
                $dto = RubikaInboundPayload::forge(['update' => $updateRaw]);

                // B) 🚀 DISPATCH: SEND TO QUEUE
                // We pass the DTO AND the Identity ($currentIdentity).
                // This ensures the Job spawns the CORRECT driver to reply.
                HandleDriverUpdate::dispatch($dto, $currentIdentity);

                $queuedCount++;

            } catch (\Throwable $e) {
                // Log and continue (Circuit Breaker)
                if (class_exists(AmethystMatrix::class)) {
                    AmethystMatrix::error("🔥 Fetch Loop Error [{$currentIdentity}]: " . $e->getMessage());
                }
                continue;
            }

            /// ///// OLD LEGACY METHOD (DIRECT DISPATCH WITHOUT IDENTITY) /////
            /// dispatch(new HandleRubikaUpdate($updateRaw));
        }

        return [
            'status' => 'ok',
            'queued' => $queuedCount,
            'driver' => $currentIdentity
        ];

        // =====================================================================
        // 🏛️ MUSEUM OF LEGACY CODE (SYNC MODE ARCHIVE)
        // =====================================================================
        // The code below is the OLD Synchronous way (blocking).
        // Kept for reference or emergency fallback debugging.

        // Removed for LLM DeAmbiguousiaty...
    }

    /**
     * Resolves the Chat ID priority;
     * from argument or builder context.
     * Priority:
     * 1. Passed argument ($chatId)
     * 2. Internal state ($this->chat_id) set via chat('ID')
     * 
     * @param string|null $chatId
     * @return string
     * @throws \InvalidArgumentException If no Chat ID is determined.
    */
    protected function resolveChatId(?string $chatId = null): string
    {
        // تلاش برای دریافت از آرگومان یا متد chatId() کلاس والد
        $realChatId = $chatId ?? $this->chatId();

        // 1. Return explicit argument if present
        if ($chatId !== null) {
            return $chatId;
        }

        // 2. Return internal state (chained method style: $bot->chat('ID')->sendDice())
        // Assuming the main Bot class has a public or protected $chat_id property
        if (!empty($this->chat_id)) {
            return $this->chat_id;
        }

        // 3. Fail safely, may be too soon for alert
        throw new \InvalidArgumentException(
            "Target Chat ID is missing. Use ->chat('ID') or pass \$chatId as an argument."
            . PHP_EOL .
            "Chat ID is required via argument or builder ->chat()"
        );
    }
    
    /**
     * Resolve the primary routing signal with strict priority:
     * 1) callback action payload (button_id)
     * 2) text payload
     *
     * @return array{
     *   0:'action'|'text'|'none',
     *   1:string,
     *   2:array<string,mixed>
     * }
     */
    private function resolveRoutingSignal(Message $message): array
    {
        // 1) Callback action (highest priority)
        // Fast path: direct normalized property
        $buttonId = $message->button_id ?? null;

        if (is_string($buttonId) && $buttonId !== '') {
            [$action, $params] = $this->parseActionPayload($buttonId);

            // If action payload is valid, namespace it to avoid collision with text routes
            if ($action !== null) {
                return [self::RT_ACTION, 'CBK::' . $action, $params];
            }

            // Invalid callback payload: still return action type with empty routing target
            // so the caller can decide strict fallback behavior.
            return [self::RT_ACTION, '', []];
        }

        // 2) Plain text
        $text = $message->text ?? '';
        if (is_string($text) && $text !== '') {
            return [self::RT_TEXT, $text, []];
        }

        // 3) No usable routing signal
        return [self::RT_NONE, '', []];
    }

    /**
     * Unified callback payload parser (strict + flexible).
     *
     * Supported:
     * - "remove"
     * - "remove?id=123"
     * - "remove|id=123&sku=A1"
     * - "remove:123"                  => ['id' => '123']
     * - "remove:id=12,foo=bar"        => ['id' => '12', 'foo' => 'bar']
     *
     * Returns:
     * - [actionName, params] on success
     * - [null, []] on invalid payload
     *
     * @return array{0:?string,1:array<string,mixed>}
    */
    private function parseActionPayload(string $payload): array
    {
        $payload = trim($payload);

        // Hard guard (abuse protection)
        if ($payload === '' || strlen($payload) > 512) {
            return [null, []];
        }

        // Action name policy: strict whitelist
        $isValidAction = static fn(string $a): bool =>
            (bool) preg_match('/^[a-zA-Z_][a-zA-Z0-9_\.]{0,63}$/', $a);

        // Param key policy
        $isValidKey = static fn(string $k): bool =>
            (bool) preg_match('/^[a-zA-Z_][a-zA-Z0-9_\.]{0,63}$/', $k);

        // Param value policy
        $isValidVal = static fn(string $v): bool => strlen($v) <= 128;

        // Helper sanitizer for parsed arrays
        $sanitize = static function (array $raw) use ($isValidKey, $isValidVal): array {
            $out = [];
            foreach ($raw as $k => $v) {
                $k = trim((string) $k);
                if (!$isValidKey($k)) continue;

                if (is_array($v)) {
                    // Flatten one level to avoid parse_str array abuse
                    $v = implode(',', array_map(static fn($x) => (string)$x, $v));
                } else {
                    $v = trim((string) $v);
                }

                if (!$isValidVal($v)) continue;
                $out[$k] = $v;
            }
            return $out;
        };

        // A) plain action: "remove"
        if ($isValidAction($payload)) {
            return [$payload, []];
        }

        // B) query style: "remove?id=123"
        if (str_contains($payload, '?')) {
            [$action, $query] = explode('?', $payload, 2);
            $action = trim($action);
            if (!$isValidAction($action)) return [null, []];

            parse_str($query, $params);
            return [$action, is_array($params) ? $sanitize($params) : []];
        }

        // C) pipe-query style: "remove|id=123&sku=A1"
        if (str_contains($payload, '|')) {
            [$action, $query] = explode('|', $payload, 2);
            $action = trim($action);
            if (!$isValidAction($action)) return [null, []];

            parse_str($query, $params);
            return [$action, is_array($params) ? $sanitize($params) : []];
        }

        // D) colon style #1: "remove:123" => id=123
        // D) colon style #2: "remove:id=12,foo=bar"
        if (str_contains($payload, ':')) {
            [$action, $tail] = explode(':', $payload, 2);
            $action = trim($action);
            $tail = trim($tail);

            if (!$isValidAction($action)) return [null, []];
            if ($tail === '') return [$action, []];

            // If looks like key=value list
            if (str_contains($tail, '=')) {
                $params = [];
                foreach (explode(',', $tail) as $pair) {
                    $kv = explode('=', $pair, 2);
                    if (count($kv) !== 2) continue;

                    $k = trim($kv[0]);
                    $v = trim($kv[1]);

                    if (!$isValidKey($k) || !$isValidVal($v)) continue;
                    $params[$k] = $v;
                }
                return [$action, $params];
            }

            // Otherwise map as id
            if ($isValidVal($tail)) {
                return [$action, ['id' => $tail]];
            }

            return [null, []];
        }

        // Unknown format
        return [null, []];
    }

    // End Deprecation _ Area
    // Welcome to New PowerFUL...
    /**
     * =========================================================================
     *  ⚡ THE ULTRA-POWERFUL ROUTING ENGINE v6.0 (MULTI-VERSE ULTIMATE CONSOLIDATED)
     * =========================================================================
     * 
     * The definitive "Brain" of KrubiK.
     * 
     * 💎 PERFORMANCE ARCHITECTURE:
     * 1. Normalization on-the-fly: Detects Route Object vs Array ONCE via `$isSmartRoute`.
     * 2. Early Guards: Checks 'recipient' restrictions BEFORE expensive Regex engines.
     * 3. Smart Matching: Exact Match (O(1)) -> Param Match (Fast String Search) -> Regex (Power).
     * 4. Intelligent Assembly: Delegates middleware logic to Route Class #2 if available.
     * 5. Dual-Pipeline: Laravel Pipeline (Preferred) -> Native Robust Fallback (with Aliases).
     * 6. Now fully aware of the 4th Dimension: Glass Buttons (Callbacks) and Action-based Conversational Routing.
     * 
     * @param Message $message The incoming update message.
    */
    public function processUpdate(Message $message): void
    {
        // =====================================================================
        // PHASE 0: STATE INITIALIZATION & OPTIMIZATION
        // =====================================================================
        
        // 1. Global State Injection
        $this->currentMessage = $message;
        
        // 2. Primitive Extraction (Memory Optimization)
        // Extract text once to avoid repeated property access. Ensure string type.
        /// $text = $message->text ?? '';
        
        // 3. Reset Request State (Lazarus/Swoole/RoadRunner Compatibility)
        // Crucial for long-running processes to prevent data leakage between requests.
        $this->currentRouteParams = [];
        $this->currentResolvedHandler = null;

        $this->resetContextData(); // 🌋 THE ASYNC GUARDIAN: WIPE THE SLATE CLEAN! [bot->get() && bot->set() data]
        $this->tunnelAmethyst($message); // We Can Auto-Fill it by $this->currentMessage, but not now!

        /**
         * Resolve the primary routing payload with strict priority:
         * 1) callback action payload
         * 2) text payload
        */
        [$routingType, $routingPayload, $actionParams] = $this->resolveRoutingSignal($message);
        $text = $routingPayload  ?? ''; // Fill $text from resolvedSignal

        // =====================================================================
        // PHASE 1: THE FINDER (MATCHING LOOP)
        // =====================================================================
        
        $matchedRoute = null;
        $finalRouteParams = [];
        $isSmartRoute = false; // Optimization Flag
        $isSmartRouteCandidate = false;

        // 👁️ 1. Awaken the Sensory Engine: Detect the physical type of the message ONCE.
        $detectedMediaType = $this->detectMessageType();

        // Iterate through all registered routes to find the FIRST match.
        foreach ($this->routes as $pattern => $routeItem) {
            
            // --- A) NORMALIZATION & TYPE DETECTION ---
            // We determine the route type HERE to avoid `instanceof` checks in the critical execution path later.
            
            if (is_object($routeItem) && method_exists($routeItem, 'getAction')) {
                // MODERN: Route Object (Class #2)
                // We call getAttributes() to handle Guard checks.
                $attributes = method_exists($routeItem, 'getAttributes') ? $routeItem->getAttributes() : [];
                $isSmartRouteCandidate = true; 
            } elseif (is_array($routeItem)) {
                // LEGACY: Array Structure ['action' => ..., 'attributes' => ...]
                $attributes = $routeItem['attributes'] ?? [];
                $isSmartRouteCandidate = false;
            } else {
                // RAW: Callable fallback
                $attributes = [];
                $isSmartRouteCandidate = false;
            }

            // --- B) SECURITY GUARDS (PRE-REGEX OPTIMIZATION) ---
            // strict conditions checked BEFORE running expensive Regex engine.
            
            // Guard 1: Recipient / Channel Restriction
            if (!empty($attributes['recipient'])) {
                $allowedRecipients = (array) $attributes['recipient'];
                $currentChatId = $this->chatId();
                $currentSenderId = $this->senderId();
                
                // Logic: Must match EITHER the ChatID OR the SenderID.
                if (!in_array($currentChatId, $allowedRecipients) && !in_array($currentSenderId, $allowedRecipients)) {
                    continue; // Skip this route immediately
                }
            }
            
            // Guard 2: Driver/Platform Restriction (Future Proofing)
            // if (!empty($attributes['driver']) && $attributes['driver'] !== 'rubika') { continue; }

            // --- C) PATTERN MATCHING ENGINE ---
            // ⚡ Now matching against $routingTarget instead of just $text
            $isMatch = false;
            $matches = [];

            // Strategy 1: Exact String Match (Fastest - O(1))
            if ($text === $pattern) {
                $isMatch = true;
            }
            // 👁️ Strategy 2: SENSORY TYPE MATCH (O(1) Execution)
            // Evaluates if the route is a Type route and matches our pre-calculated $detectedMediaType
            elseif (str_starts_with($pattern, 'TYPE::')) {
                // Determine actual type defined in route, e.g., 'TYPE::photo' -> 'photo'
                $expectedType = substr($pattern, 6); 
                
                if ($expectedType === $detectedMediaType) {
                    $isMatch = true;
                }
            }
            // Strategy 3: Parameterized Match (e.g., "/cmd {param}")
            // Optimization: `str_contains` is significantly faster than `preg_match` for pre-check.
            elseif (str_contains($pattern, '{') && str_contains($pattern, '}')) {
                // Escape literals, then convert {param} to Named Group (?<param>.*?)
                // We strictly expect Start(^) and End($) anchors.
                $safePattern = preg_quote($pattern, '/');
                $regex = '/^' . preg_replace('/\\\{(\w+)\\\}/', '(?<$1>.*?)', $safePattern) . '$/iu';
                
                if (preg_match($regex, $text, $m)) {
                    $isMatch = true;
                    // Filter to keep ONLY named string keys for Dependency Injection
                    $matches = array_filter($m, 'is_string', ARRAY_FILTER_USE_KEY);
                }
            }
            // Strategy 4: Explicit Regex Match (Power User)
            // Heuristic: Starts/Ends with slash "/" and length > 2 (to avoid empty "//")
            elseif (str_starts_with($pattern, '/') && str_ends_with($pattern, '/') && strlen($pattern) > 2) {
                if (preg_match($pattern, $text, $m)) {
                    $isMatch = true;
                    // Extract named groups if exist, otherwise use positional matches (slicing off full match)
                    $named = array_filter($m, 'is_string', ARRAY_FILTER_USE_KEY);
                    $matches = !empty($named) ? $named : array_slice($m, 1);
                }
            } 

            // --- D) MATCH CONFIRMATION ---
            if ($isMatch) {
                $matchedRoute = $routeItem;
                $finalRouteParams = $matches;
                $isSmartRoute = $isSmartRouteCandidate;
                break; // FIRST MATCH WINS - Break the loop.
            }
        }

        // Merge action params (from button payload) with route params
        // Route params take precedence only if same key appears later:
        // choose your policy. Here: action params first, route params overwrite.
        $finalRouteParams = array_merge($actionParams, $finalRouteParams);
                
        $middlewareStack = [];
        $finalHandler = null;
        
        // =====================================================================
        // PHASE 2-1: THE MAGIC CONVERSATION INTERCEPTOR ✨🪄
        // =====================================================================
        // This is where the DX Fatality happens! If a button was clicked but 
        // NO global route caught it, we MUST NOT let it die. We force it into 
        // the pipeline so `ConversationMiddleware` can inspect it and trigger 
        // the `#[Action('...')]` methods within the active conversation.

        if ($matchedRoute) {
            $matchedRoute = true; // Bypass the dead-end drop
            $isSmartRoute = false;
            
            // This is a dummy destination. If ConversationMiddleware successfully 
            // processes the Action, it will halt the pipeline and this won't run.
            // If it reaches here, it's an orphaned button click!
            $finalHandler = function($bot, $msg) {
                AmethystMatrix::warning(
                    "Orphaned Callback Triggered: No global route or active conversation caught this action.", 
                    ['payload' => $msg->button_id, 'details' => $msg]
                );
            };
            
            // Force the global stack (which includes ConversationMiddleware)
            $middlewareStack = $this->globalMiddlewares; 
        }

        // =====================================================================
        // PHASE 2-2: THE COMPILER (STACK ASSEMBLY)
        // =====================================================================

        else
        
        if ($matchedRoute) {
            // 1. Save Context for Middleware Inspection
            $this->currentResolvedHandler = $matchedRoute; // is_object($matchedRoute) && $matchedRoute instanceof Route ? $matchedRoute : null;
            $this->currentRouteParams = $finalRouteParams;

            // 2. Assemble Handler & Middleware Stack
            // We leverage the `$isSmartRoute` flag computed in Phase 1.

            if ($isSmartRoute && $matchedRoute instanceof Route) {
                // === MODERN PATH (Route Class #2) ===
                // Delegate logic to the Route object. It knows how to merge Global + Local
                // and handle 'skipGlobalMiddlewares' intelligently.
                
                $finalHandler = $matchedRoute->getAction();
                $middlewareStack = $matchedRoute->getMiddlewareStack($this->globalMiddlewares);
                
            } else {
                // === LEGACY PATH (Backwards Compatibility) ===
                // Manual extraction and merging.
                
                $routeItemArr = is_array($matchedRoute) ? $matchedRoute : ['action' => $matchedRoute];
                $finalHandler = $routeItemArr['action'] ?? null;
                
                $attrs = $routeItemArr['attributes'] ?? [];
                $routeMiddlewares = $attrs['middleware'] ?? [];
                if (!is_array($routeMiddlewares)) $routeMiddlewares = [$routeMiddlewares];
                
                // Simulate 'withoutGlobalMiddleware' manually for arrays
                if (($attrs['withoutGlobalMiddleware'] ?? false) === true) {
                    $middlewareStack = $routeMiddlewares;
                } else {
                    // Standard Order: Global (Outer) -> Local (Inner)
                    $middlewareStack = array_merge($this->globalMiddlewares, $routeMiddlewares);
                }
            }

        }
        elseif ($routingType === self::RT_ACTION) {
            // Never drop callback actions directly.
            // Force global middleware pipeline so ConversationMiddleware can consume #[Action].
            $finalHandler = static function () {
                // intentional no-op
                // If ConversationMiddleware consumes action, flow should stop there.
            };
            $middlewareStack = $this->globalMiddlewares;
            $this->currentRouteParams = $finalRouteParams;

            // then continue to pipeline execution branch
        }
        
        elseif ($this->fallbackHandler) {
            // 3. Fallback Scenario
            // Runs Global Middlewares to ensure logging/security even on 404s.
            $finalHandler = $this->fallbackHandler;
            $middlewareStack = $this->globalMiddlewares;
            $this->currentRouteParams = $finalRouteParams;
        } else { // not found ?
            // 4. Dead End
            $this->tunnelAmethyst(); // clear AmethystMatrix working message entry.
            // Then:
                return;
        }

        // =====================================================================
        // PHASE 3: THE RUNNER (PIPELINE EXECUTION)
        // =====================================================================
        
        // The final destination closure
        $destination = function ($bot) use ($finalHandler, $message, $finalRouteParams) {
            return $this->callAction($finalHandler, $message, $finalRouteParams);
        };

        // OPTION A: LARAVEL PIPELINE (The Gold Standard)
        // Used when running inside a Laravel Application (Artisan/Http).
        if (class_exists(\Illuminate\Pipeline\Pipeline::class) && function_exists('app')) {
            app(\Illuminate\Pipeline\Pipeline::class)
                ->send($this)
                ->through($middlewareStack)
                ->then($destination);
        } 
        // OPTION B: NATIVE ROBUST FALLBACK (The Heavy Lifter)
        // Used for standalone scripts or lightweight setups. 
        // Enhanced to support Aliases, Invokables, and Standard Middleware methods.
        else {
            $pipeline = array_reduce(
                array_reverse($middlewareStack),
                function ($next, $middleware) {
                    return function ($bot) use ($next, $middleware) {
                        
                        // --- 1. Resolve Aliases ---
                        // Check if 'auth' maps to 'App\Middleware\Auth::class'
                        if (is_string($middleware) && property_exists($this, 'middlewareAliases')) {
                            if (isset($this->middlewareAliases[$middleware])) {
                                $middleware = $this->middlewareAliases[$middleware];
                            }
                        }

                        // --- 2. Instantiate & Execute ---
                        
                        // TYPE I: String Class Name
                        if (is_string($middleware) && class_exists($middleware)) {
                            $instance = new $middleware;
                            
                            // Prefer 'handle' method (Laravel Standard)
                            if (method_exists($instance, 'handle')) {
                                return $instance->handle($bot, $next);
                            } 
                            // Fallback to '__invoke' (Modern/Slim Standard)
                            elseif (is_callable($instance)) {
                                return $instance($bot, $next);
                            }
                            
                            // Strict Failure if un-executable class is passed
                            throw new \RuntimeException("Middleware [$middleware] is not executable (missing handle/__invoke).");
                        }
                        
                        // TYPE II: Closure Middleware
                        if ($middleware instanceof \Closure) {
                             return $middleware($bot, $next);
                        }
                        
                        // TYPE III: Object Instance
                        if (is_object($middleware)) {
                            if (method_exists($middleware, 'handle')) {
                                return $middleware->handle($bot, $next);
                            } elseif (is_callable($middleware)) {
                                return $middleware($bot, $next);
                            }
                        }

                        // Safety Net: Pass through if middleware is invalid/unrecognized
                        return $next($bot);
                    };
                },
                $destination
            );

            // Ignite the Native Pipeline
            $pipeline($this);
        }

        $this->tunnelAmethyst(); // short syntax for `$this->tunnelAmethyst(null)` ; clears AmethystMatrix working message entry.
    }

    /**
     * ⚡ The Universal Auto-Wirer.
     * Resolves dependencies globally. Strictly types payload parameters to match method signatures.
     *
     * @param ReflectionMethod|\ReflectionFunction $method The target method to execute.
     * @param object|null $targetInstance The instance to run the method on (null if static/closure).
     * @param array $payloadData Parameters extracted from Route Regex or Action Payload.
     * @param array $extraInjects Extra core objects to inject if requested (like Answer DTOs).
     * @return mixed
     * @throws \RuntimeException If a catastrophic dependency failure occurs.
     */
    public function invokeWithAutoWiring(ReflectionMethod|\ReflectionFunction $method, ?object $targetInstance, array $payloadData, array $extraInjects = []): mixed
    {
        $dependencies = [];

        foreach ($method->getParameters() as $parameter) {
            $name = $parameter->getName();
            $type = $parameter->getType();
            $typeName = $type instanceof \ReflectionNamedType && !$type->isBuiltin() ? $type->getName() : null;

            // 1. Contextual Injects
            // Check Extra Injects First (e.g. Answer Object from Conversations)
            $injected = false;
            foreach ($extraInjects as $inject) {
                if (is_object($inject) && ($typeName === get_class($inject) || is_subclass_of($inject, $typeName))) {
                    $dependencies[] = $inject;
                    $injected = true;
                    break;
                }
            }
            if ($injected) continue;

            // 2. Core Engine Constants
            if ($typeName === Krubot::class || $typeName === self::class) {
                $dependencies[] = $this;
                continue;
            }
            if ($typeName === Message::class) {
                $dependencies[] = $this->thisMessage();
                continue;
            }

            // 3. Payload Injection Engine (The Metaphysical Cast)
            // Payload Data Cast & Inject (The Magic)
            if (array_key_exists($name, $payloadData)) {
                $val = $payloadData[$name];
                
                if ($type instanceof \ReflectionNamedType && $type->isBuiltin()) {
                    // Match expressions (PHP 8+) for ultimate execution speed!
                    $val = match ($type->getName()) {
                        'int'   => (int)$val,
                        'bool'  => filter_var($val, FILTER_VALIDATE_BOOLEAN),
                        'float' => (float)$val,
                        default => $val,
                    };
                }
                
                $dependencies[] = $val;
                continue;
            }

            // 4. Safe Fallbacks
            if ($parameter->isDefaultValueAvailable()) {
                $dependencies[] = $parameter->getDefaultValue();
                continue;
            }
            if ($type && $type->allowsNull()) {
                $dependencies[] = null;
                continue;
            }

            // Fatal error if missing parameter and no default
            throw new \RuntimeException("Krubot Architect Error: Missing payload parameter [\${$name}] for auto-wiring method [{$method->getName()}].");
        }

        // Execute!
        return $method instanceof ReflectionMethod 
            ? $method->invokeArgs($targetInstance, $dependencies)
            : $method->invokeArgs($dependencies);
    }

    /**
     * ⚡ THE ULTIMATE DISPATCHER
     * Dispatches the route using Laravel's Service Container (App::call) or Native PHP.
     *
     * 💎 Capabilities (Merged & Enhanced):
     * 1. **Full Dependency Injection**: Injects Bot, Message, and Type-Hinted classes.
     * 2. **Smart Route Params**: Maps URL params like `{id}` directly to method arguments `$id`.
     * 3. **Context Awareness**: Injects data shared via `set()`/`setData()` into the method (Laravel only).
     * 4. **Robust Resolution**: Handles `[Class, Method]`, `'Class@Method'`, Closures, and Invokables.
     * 5. **Native Fallback**: Highly optimized fallback for non-Laravel environments.
     *
     * @param mixed $action The handler to execute (Closure, Array, String).
     * @param Message $message The incoming message object.
     * @param array $routeParams Captured parameters from the route pattern.
     * @return mixed Result of the executed action.
    */
    protected function callAction(mixed $action, Message $message, array $routeParams = []): mixed
    {
        // =====================================================================
        // PHASE 1: PREPARE THE DEPENDENCY CONTAINER (THE "BAG")
        // =====================================================================

        // 1. Resolve Context Data (If the trait is used and data exists)
        // We use property_exists to be safe, ensuring generic compatibility.
        $context = property_exists($this, 'contextData') ? $this->contextData : [];

        // 2. Build the Ultimate Dependency Array
        // Priority Order (Last one wins in array_merge):
        // Level 1: Context Data (Lowest priority, generic data)
        // Level 2: Standard Injections (Bot, Message, Type Hints) - Essential
        // Level 3: Route Parameters (Highest priority, specific to this request)
        $dependencies = array_merge(
            $context,
            [
                // String Aliases for legacy or simple access
                'bot'     => $this,
                'message' => $message,

                // Class Type-Hints (Enable: public function handle(Krubot $bot, Message $msg))
                self::class    => $this,    // Inject KrubiK\Krubot
                Krubot::class  => $this,    // Inject explicit class name
                Message::class => $message, // Inject KrubiK\DTOs\Message
            ],
            $routeParams
        );

        // 🟢 PATCH: Add support for Call Route with assoc-array $params
        // 🔥 INSERT THIS LINE HERE 🔥
        //     to Manually Inject the entire parameters array into a key named 'params'.
        $dependencies['params'] = $routeParams; 

        // =====================================================================
        // PHASE 2: LARAVEL SERVICE CONTAINER EXECUTION (PREFERRED)
        // =====================================================================

        if (class_exists(App::class)) {
            // Case A: Array Callable [Controller::class, 'method']
            if (is_array($action) && count($action) === 2) {
                // If the first item is an instantiated Object, we call it directly.
                // This preserves the object state if it was pre-configured.
                if (is_object($action[0])) {
                    return App::call($action, $dependencies);
                }

                // If it's a String Class Name, we convert it to 'Class@method'.
                // WHY? Passing ['Class', 'Method'] to App::call usually treats it as a Static call.
                // Converting to 'Class@Method' string forces Laravel to resolve the class via IoC,
                // allowing Constructor Injection in the Controller.
                return App::call($action[0] . '@' . $action[1], $dependencies);
            }

            // Case B: Closures, 'Class@method' strings, or Invokable Objects
            // Laravel handles mapping named $dependencies to function arguments automatically.
            return App::call($action, $dependencies);
        }

        // =====================================================================
        // PHASE 3: NATIVE PHP FALLBACK (PERFORMANCE OPTIMIZED)
        // =====================================================================

        // Logic: In native PHP, we can't easily inject "Context Data" by name without Reflection overhead,
        // because call_user_func_array throws errors for extra unknown named arguments.
        // So we prioritize a Safe, Positional approach for the fallback.

        // Case A: Closure
        if ($action instanceof \Closure) {

            // Prepare Positional Arguments for Native Call
            // Standard Signature: function($bot, $message, ...$routeParams)
            $positionalArgs = array_merge([$this, $message], array_values($routeParams));

            return call_user_func_array($action, $positionalArgs);
        }

        // Case B: Array Callable [Class, Method]
        if (is_array($action)) {
            [$class, $method] = $action;

            /*
             * [MUSEUM OF LEGACY CODE - DUMB INSTANTIATION]
             * // Instantiate if it's a class string (Manual Dependency Injection is minimal here)
             * $instance = is_object($class) ? $class : new $class();
            */

            // 👑 THE ARCHITECT'S POWERFUL SUGGESTION (Constructor Auto-Wiring):
            // Instead of using 'new', we leverage Laravel's highly optimized IoC Container.
            // This allows your Nexus classes to have external dependencies injected into their __construct() automatically!
            $instance = is_object($class) ? $class : app($class);

            /// [MUSEUM OF LEGACY CODE - THE BLIND CALL]
            /// return call_user_func_array([$instance, $method], $positionalArgs);

            // ⚡ THE DX FATALITY: Delegate to our Centralized Metaphysical Auto-Wirer!
            // No more dumb call_user_func_array. No more guessing positional parameters.
            // We strictly type-cast and inject intelligently!
            $reflectionMethod = new \ReflectionMethod($instance, $methodName);
            
            return $this->invokeWithAutoWiring(
                method: $reflectionMethod, 
                targetInstance: $instance, 
                payloadData: $routeParams, 
                extraInjects: [$message] // Inject the Message DTO context natively; Add contextual instances here if needed
            );
        }

        // Case C: Invokable Object or direct function string
        if (is_callable($action)) {
             return call_user_func($action, $this, $message, ...array_values($routeParams));
        }

        // Final Fail-safe
        return null;
    }

    // =========================================================================
    //  MERGED FEATURES & HELPERS
    // =========================================================================

    /**
     * ⚡ Helper to get the current message in Nexuses without passing it.
    */
    public function thisMessage(): ?Message
    {
        return $this->currentMessage;
    }

    public function findMessageId(): ?string
    {
        return $this->thisMessage()?->message_id ?? null;
    }

    public function findRepliedMessageId(): ?string
    {
        return $this->thisMessage()?->reply_to_message_id ?? null;
    }

    public function chatId()
    {
        return $this->thisMessage()?->chat_id ?? null;
    }

    public function text(): string
    {
        return $this->thisMessage()?->text ?? '';
    }

    public function user(): array
    {
        return [
            'id' => $this->thisMessage()?->sender_id ?? null,
            'username' => $this->thisMessage()?->user_name ?? null,
            'first_name' => $this->thisMessage()?->first_name ?? null        
        ];
    }

    /**
     * Helper to send message without reply (Say), and without auto-send.
    */
    public function say(string $text): static
    {
        if (!$this->chatId())
            return $this;
        return $this->chat($this->chatId())->message($text);
    }

    /**
     * Helper to reply to the current message.
     * Automatically sets replyTo ID if available.
    */
    public function reply(string $text): static
    {
        if (!$this->chatId()) {
             return $this->message($text);
        }
        
        $builder = $this->chat($this->chatId());
        
        if ($msgId = $this->findMessageId()) {
            $builder->replyTo($msgId);
        }
        
        return $builder->message($text);
    }

    /**
     * Helper to edit a specific message.
    */
    public function modify(string $messageId, string $newText): static
    {
        if (!$this->chatId()) return $this;

        $this->chat($this->chatId())
             ->messageId($messageId)
             ->message($newText);
             
        return $this;
    }

    /**
     * Download file from message to Laravel Storage.
    */
    public function downloadTo(string $fileId, string $path, string $disk = 'local'): bool
    {
        try {
            $url = $this->getFile($fileId);
            if (!$url) return false;

            $content = @file_get_contents($url);
            if ($content === false) return false;

            return Storage::disk($disk)->put($path, $content);
        } catch (\Throwable $e) {
            AmethystMatrix::error("Krubot Download Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if current message contains a file.
    */
    public function hasFile(): bool
    {
        $raw = $this->getUpdate();
        $newMsg = raw['update']['new_message'] ?? $raw['message'] ?? [];
        return isset($newMsg['file_inline']) || isset($newMsg['file_attachment']);
    }

    // =========================================================================
    //  ⚡ MERGED FROM Latest BotLaraGKTR (New Helper Methods)
    // =========================================================================

    /**
     * Shortcut to get just the Sender ID string.
    */
    public function senderId(): ?string
    {
        return $this->user()['id'] ?? null;
    }

    /**
     * Shortcut to get just the Sender ID string.
    */
    public function who(): ?string
    {
        return $this->user()['id'] ?? null;
    }

    /**
     * Get the cleaned text content (trimmed).
    */
    public function cleanText(): string
    {
        return trim($this->text());
    }

    /**
     * Check if the update is from the Admin defined in .env
     * Add RUBIKA_ADMIN_GUID=... to your .env file.
    */
    public function isAdmin(?string $userId = null): bool
    {
        $adminGuids = config('krubot.drivers.'.$this->getDriverAlias().' .admin_ids', [env('RUBIKA_ADMIN_GUID')]); // get admin ids for current platform
        $senderId = $userId ?? $this->senderId(); // we checking for who ?!
        
        return $senderId && in_array($senderId, $adminGuids);
    }

    /**
     * Send a message to a SPECIFIC target (User/Group GUID) directly.
    */
    public function to(string $targetChatId, string $text): array
    {
        return $this->chat($targetChatId)
            ->message($text)
            ->send();
    }

    /**
     * Delete the current message immediately.
    */
    public function deleteCurrent(): array
    {
        if (!$this->chatId() || !$this->findMessageId()) {
            return ['status' => 'ERROR', 'message' => 'No context available'];
        }
        
        return $this->chat($this->chatId())
            ->messageId($this->findMessageId())
            ->sendDelete();
    }

    /**
     * Edit the current message immediately (Useful for updating Bot's own menus).
    */
    public function editCurrent(string $newText): array
    {
        if (!$this->chatId() || !$this->findMessageId()) {
            return ['status' => 'ERROR', 'message' => 'No context available'];
        }

        return $this->chat($this->chatId())
            ->messageId($this->findMessageId())
            ->message($newText)
            ->editMessage();
    }

    /**
     * Edit the current message immediately (Useful for updating Bot's own menus).
    */
    public function sendMessageToAdmins(string $text): array
    {
        $adminGuids = config('krubot.drivers.'.$this->getDriverAlias().' .admin_ids', [env('RUBIKA_ADMIN_GUID')]); // get admin ids for current platform
        $result = [];
        foreach ($adminGuids as $admin_id) {
            $result []= $this->to($admin_id, $text);
        }
        return $result;
    }
    

    /**
     * Quick check if message matches a pattern (Exact or Regex).
     * Useful inside handlers for sub-logic.
    */
    public function matches(string $pattern): bool
    {
        $text = $this->cleanText();

        // Exact match
        if ($text === $pattern) return true;

        // Regex match check (heuristic: starts/ends with /)
        if (str_starts_with($pattern, '/') && str_ends_with($pattern, '/')) {
             return (bool) preg_match($pattern, $text);
        }

        return false;
    }

    // پیاده‌سازی Krubot::for(): تک‌تیراندازِ خارج از متن
    // در حال حاضر، Krubot (ستون فقرات) معمولاً به آپدیت‌های دریافتی از وب‌هوک وابسته است تا بداند chat_id چیست. با متد استاتیک for، ما یک Instance جدید می‌سازیم و هدف را دستی به آن تزریق می‌کنیم.
    /**
     * Creates a targeted instance of Krubot for a specific user or chat.
     * This allows sending messages outside of the webhook request cycle (e.g., in Jobs or Console Commands).
     *
     * @param string $targetGuid The GUID of the user or group to target.
     * @return static
    */
    public static function for(string $targetGuid): static
    {
        // Resolve a fresh instance from the Laravel Service Container
        // This ensures all Traits and Dependencies are injected correctly.
        $instance = app(static::class); // Note! not compatible with singleton, will fix in ×v1×

        // Manually hydrate the internal state for the target
        // Assuming 'chatId' and 'userId' properties exist or are managed via a fluent setter.
        // Based on the architecture, we might need to expose a way to set these.
        
        // Injecting the target into the context
        $instance->forceContext($targetGuid); 

        return $instance;
    }

    /**
     * Internal helper to force-set the context.
     * (Add this if specific setters don't exist in your Traits)
    */
    protected function forceContext(string $guid): void
    {
        // We set the chat ID as the primary target
        $this->chat_id = $guid;
        
        // If the GUID starts with 'u', it's a user, so we map it there too.
        if (str_starts_with($guid, 'u')) {
            $this->user_id = $guid;
        }
    }

}
