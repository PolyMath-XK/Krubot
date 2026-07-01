<?php

namespace KrubiK\Router;
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

use Closure;

/**
 * Class Route
 *
 * The definitive, consolidated Route object for the KrubiK Routing Engine.
 * 
 * Capabilities:
 * 1. Hybrid Middleware Management: Supports generic attributes array AND dedicated middleware stack.
 * 2. Smart Global Skipping: Allows skipping ALL global middlewares or specific classes.
 * 3. Named Routes (Registrar Bridge): Automatically updates the main Router index when named.
 * 4. Tagging System: For grouping and retrieving routes.
 * 5. Fluent Interface: Fully chainable methods.
 * 
 * @author DoKtor K.
 * @link https://StoryKo.de Official website of engine.
 * @version Krubot: ×v0.7ALPHA×
 * @license MIT
 */
class Route
{
    /**
     * The matching pattern (Regex, Command, or Exact text).
     */
    public string $pattern;

    /**
     * The handler action (Controller array, Closure, or Invokable class string).
     */
    public mixed $action;

    /**
     * General attributes container (recipients, custom drivers, etc.).
     * Note: Middleware definitions are extracted from here to $middlewares for performance,
     * but other metadata remains here.
     */
    public array $attributes = [];

    /**
     * Dedicated middleware stack for this specific route.
     * Optimized for array operations (push/merge) and execution pipeline.
     */
    protected array $middlewares = [];

    /**
     * Tags for categorizing routes (e.g., 'auth', 'admin-panel', 'payment').
     */
    protected array $tags = [];

    /**
     * If true, ALL global middlewares defined in the Bot are ignored for this route.
     */
    protected bool $skipAllGlobalMiddlewares = false;

    /**
     * A list of SPECIFIC global middleware classes to skip.
     * Allows fine-grained control (e.g., keep 'Log' but skip 'Auth').
     */
    protected array $skippedGlobalMiddlewares = [];

    /**
     * Name of the route (if assigned).
     */
    protected ?string $name = null;

    /**
     * The callback to register this route's name back to the main Krubot instance.
     * This creates a bridge between the Route object and the central Router index.
     * This is "hidden" from public export/serialization usually.
     */
    protected ?Closure $nameRegistrar = null;

    /**
     * Route constructor.
     *
     * @param string $pattern The matching pattern (e.g., '/start', '/^hi$/i').
     * @param mixed $action The handler (Closure, [Class, Method], or 'Class@Method').
     * @param array $attributes Metadata (e.g., ['recipient' => 123, 'middleware' => 'auth']).
     * @param Closure|null $nameRegistrar Secret closure to register named routes in the main Router.
     */
    public function __construct(
        string $pattern,
        mixed $action,
        array $attributes = [],
        ?Closure $nameRegistrar = null
    ) {
        $this->pattern = $pattern;
        $this->action = $action;
        $this->nameRegistrar = $nameRegistrar;

        // 1. Extract Middleware for optimized handling
        // We move 'middleware' out of the generic attributes array into the dedicated property
        // to ensure type safety and easier merging later.
        if (isset($attributes['middleware'])) {
            $this->middleware($attributes['middleware']);
            unset($attributes['middleware']);
        }

        // 2. Handle 'withoutGlobalMiddleware' attribute (Compatibility Layer)
        // If the user passed ['withoutGlobalMiddleware' => true/array] in the array definition.
        if (isset($attributes['withoutGlobalMiddleware'])) {
            $val = $attributes['withoutGlobalMiddleware'];
            if ($val === true) {
                $this->skipAllGlobalMiddlewares = true;
            } elseif (is_array($val) || is_string($val)) {
                $this->skipGlobalMiddlewares((array) $val);
            }
            unset($attributes['withoutGlobalMiddleware']);
        }

        // 3. Handle 'as' attribute (Legacy naming)
        if (isset($attributes['as'])) {
            $this->name($attributes['as']);
            // We keep 'as' in attributes for backward compatibility if needed
        }

        // 4. Store remaining attributes (recipients, drivers, limits, etc.)
        $this->attributes = array_merge($this->attributes, $attributes);
    }

    /**
     * Set a name for the route and register it in the main Router.
     * Usage: ->name('dashboard.index')
     */
    public function name(string $name): self
    {
        $this->name = $name;
        $this->attributes['as'] = $name; // Sync for legacy access

        // Communicate back to the Bot/Router to index this route by name
        if ($this->nameRegistrar) {
            ($this->nameRegistrar)($name, $this);
        }

        return $this;
    }

    /**
     * Get the assigned name of the route.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Tag this route for later retrieval or grouping logic.
     * Usage: ->tag('admin')
     */
    public function tag(string $tag): self
    {
        if (!in_array($tag, $this->tags)) {
            $this->tags[] = $tag;
        }
        return $this;
    }

    /**
     * Check if the route has a specific tag.
     */
    public function hasTag(string $tag): bool
    {
        return in_array($tag, $this->tags);
    }

    /**
     * Add middleware(s) to this specific route.
     * Supports Chaining: ->middleware(A::class)->middleware(B::class)
     * Supports Arrays: ->middleware([A::class, B::class])
     */
    public function middleware(string|array|callable $middleware): self
    {
        $middlewares = is_array($middleware) ? $middleware : [$middleware];
        
        // Merge using array unpacking (Fast & Clean)
        $this->middlewares = [...$this->middlewares, ...$middlewares];
        
        return $this;
    }

    /**
     * Configure skipping of global middlewares.
     * 
     * - If called with no args or empty array: Skips ALL globals.
     * - If called with class names: Skips only those specific globals.
     * 
     * @param array|string $middlewares Class names to skip (optional)
     */
    public function skipGlobalMiddlewares(array|string $middlewares = []): self
    {
        $middlewares = is_array($middlewares) ? $middlewares : [$middlewares];

        if (empty($middlewares)) {
            $this->skipAllGlobalMiddlewares = true;
        } else {
            // Merge new skips with existing skips
            $this->skippedGlobalMiddlewares = array_merge(
                $this->skippedGlobalMiddlewares, 
                $middlewares
            );
        }
        return $this;
    }

    /**
     * Alias for skipGlobalMiddlewares (Laravel style naming).
     */
    public function withoutMiddleware(array|string $middlewares = []): self
    {
        return $this->skipGlobalMiddlewares($middlewares);
    }

    /**
     * Get the action handler.
     */
    public function getAction(): mixed
    {
        return $this->action;
    }

    /**
     * Get all attributes.
     * Automatically injects the current middleware stack into the returned array
     * to ensure consumers of this method (like processUpdate) see the full picture.
     */
    public function getAttributes(): array
    {
        return array_merge($this->attributes, [
            'middleware' => $this->middlewares,
            'withoutGlobalMiddleware' => $this->skipAllGlobalMiddlewares ? true : $this->skippedGlobalMiddlewares
        ]);
    }

    /**
     * THE CORE LOGIC: Compute the final executable middleware stack.
     * 
     * Merges global middlewares with local ones, respecting all skip logic.
     * This is the brain of the middleware resolution.
     * 
     * @param array $globalMiddlewares The list of middlewares defined globally in the Bot.
     * @return array The final ordered list of middlewares to execute.
     */
    public function getMiddlewareStack(array $globalMiddlewares): array
    {
        // 1. Process Globals
        $globalsToRun = [];

        if (!$this->skipAllGlobalMiddlewares) {
            if (empty($this->skippedGlobalMiddlewares)) {
                // Optimization: If no specific skips, use all globals directly
                $globalsToRun = $globalMiddlewares;
            } else {
                // Filter out specific globals
                foreach ($globalMiddlewares as $gm) {
                    // We check if the class name exists in the skipped list
                    if (!in_array($gm, $this->skippedGlobalMiddlewares)) {
                        $globalsToRun[] = $gm;
                    }
                }
            }
        }

        // 2. Merge: Global (First) -> Local Route Middlewares (Second)
        // This ensures globals run first (outer layer), then route specifics (inner layer).
        return array_merge($globalsToRun, $this->middlewares);
    }

    /**
     * 🔥 CRITICAL METHOD FOR processUpdate v5.1
     * Merges Global + Local middlewares correctly.
     */
    public function getMiddlewareStack_v51(array $globalMiddlewares): array
    {
        // 1. Process Globals
        $globalsToRun = [];

        if (!$this->skipAllGlobalMiddlewares) {
            if (empty($this->skippedGlobalMiddlewares)) {
                $globalsToRun = $globalMiddlewares;
            } else {
                foreach ($globalMiddlewares as $gm) {
                    if (!in_array($gm, $this->skippedGlobalMiddlewares)) {
                        $globalsToRun[] = $gm;
                    }
                }
            }
        }

        // 2. Merge: Global (Outer) -> Local (Inner)
        return array_merge($globalsToRun, $this->middlewares);
    }
}
