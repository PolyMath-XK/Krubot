<?php

namespace KrubiK\Arcane;
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

///

trait AdvancedRouting
{
    // =========================================================================
    //  🔮 THE METAPHYSICAL CORE: CENTRALIZED REFLECTION & AUTO-WIRING (v10.0 Ultimate)
    //  Google/Microsoft Architect Level. Zero DRY Violations. PHP 8.2.30 Optimized.
    // =========================================================================

    // =========================================================================
    //  🔮 THE METAPHYSICAL CORE: CENTRALIZED REFLECTION & AUTO-WIRING
    //  No more DRY violations! Every component uses this core engine.
    // =========================================================================

    /**
     * Static Memory Cache for Reflection Data.
     * In RoadRunner/Swoole/Octane, this persists across requests for TRUE O(1) speed!
     * @var array<string, array<string, ReflectionMethod>> 
     */
    private static array $actionMethodCache = [];

    /**
     * ⚡ Universal Action Discoverer (O(1) after first scan).
     * Scans any class ONCE to find the method matching an #[Action] or naming convention.
    */

    public function discoverActionMethod(object $targetInstance, string $actionName): ?ReflectionMethod
    {
        $className = $targetInstance::class;
        $cacheKey = $className . '::' . $actionName;

        // O(1) Memory Cache Return
        if (isset(self::$actionMethodCache[$cacheKey])) {
            return self::$actionMethodCache[$cacheKey];
        }

        $reflection = new ReflectionClass($className);

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            // 1. Primary Strategy: PHP 8 Attributes
            $attributes = $method->getAttributes(Action::class);
            if (!empty($attributes)) {
                if ($attributes[0]->newInstance()->name === $actionName) {
                    self::$actionMethodCache[$cacheKey] = $method;
                    return $method;
                }
            }

            // 2. Legacy / Fallback Strategy
            $expectedName = 'on' . str_replace('_', '', ucwords($actionName, '_'));
            if ($method->getName() === $expectedName || $method->getName() === $actionName) {
                self::$actionMethodCache[$cacheKey] = $method;
                return $method;
            }
        }

        return null;
    }


    // =========================================================================
    //  🚀 CORE EXECUTION ENGINE: THE "GO" SYSTEM (v8.1 ULTIMATE)
    // =========================================================================

    /**
     * 🚀 GO v8.1 (THE ULTIMATE ROUTER): Execute a named route immediately.
     * Performs an internal forward with complete context safety, dynamic middleware injection,
     * and robust error handling.
     *
     * 💎 Consolidated Powers:
     * 1. **Context Safety:** Backs up & restores caller state (Safe for nested calls).
     * 2. **Tri-State Middleware:**
     *    - `true`: Run target's original stack (Default).
     *    - `false`: Run NO middleware (Direct Action - Fastest).
     *    - `array`: Run a custom injected stack for this call only.
     * 3. **Hybrid Logging:** Detects environment for proper error reporting without crashing.
     * 4. **Native Pipeline:** Supports Aliases, Closures, Invokables, and Classes without Laravel dependency.
     * 5. **Smart Parameter Merging:** Inherits caller params unless overwritten.
     *
     * @param string $routeName The name defined via ->name('...')
     * @param array $params Parameters to inject/overwrite (e.g. ['id' => 5])
     * @param bool|array $middlewareStrategy Strategy for middleware execution (Default: true)
     * @return mixed The result of the executed action or null if failed/blocked.
    */
    public function go(string $routeName, array $params = [], bool|array $middlewareStrategy = true): mixed
    {
        // ---------------------------------------------------------------------
        // 1. LOOKUP & VALIDATION (O(1) HashMap Access)
        // ---------------------------------------------------------------------
        if (!isset($this->namedRoutes[$routeName])) {
            $errorMsg = "Krubot was Unable To Redirect: Route named [{$routeName}] not found.";

            // Intelligent Logging: Check available loggers without crashing
            AmethystMatrix::warning($errorMsg);
            /* if (function_exists('logger')) {
                logger()->warning($errorMsg);
            } elseif (class_exists(Log::class)) {
                Log::warning($errorMsg);
            } */

            // Dev-Mode Feedback: Tell the admin directly in chat
            /* if (config('app.debug') === true) {
                $this->to(XBot::Admins[0], "⚠️ System Error: Route '{$routeName}' not found."); // to() == auto send()
            } */
            return null;
        }

        /** @var Route $route */
        $route = $this->namedRoutes[$routeName];

        // ---------------------------------------------------------------------
        // 2. CONTEXT BACKUP (Save State) 🛡️
        // ---------------------------------------------------------------------
        // We must save the current state because 'go' might be called inside another route.
        // When 'go' finishes, the previous route must continue exactly where it left off.
        $backupHandler = $this->currentResolvedHandler;
        $backupParams = $this->currentRouteParams;

        // Prepare New Context: Merge current params with new overrides
        // Priority: New Params > Old Params
        $finalParams = array_merge($this->currentRouteParams ?? [], $params);

        // ---------------------------------------------------------------------
        // 3. EXECUTION BLOCK (The Safe Runner)
        // ---------------------------------------------------------------------
        try {
            // A) Switch Context to the Target Route
            $this->currentResolvedHandler = $route;
            $this->currentRouteParams = $finalParams;

            // B) Define the Final Destination (The Action Caller)
            // This closure actually runs the controller method via DI.
            $destination = function ($bot) use ($route, $finalParams) {
                return $this->callAction($route->getAction(), $this->currentMessage, $finalParams);
            };

            // C) DETERMINE MIDDLEWARE STRATEGY
            $stackToRun = [];

            if (is_array($middlewareStrategy)) {
                // MODE: Custom Injection (Run exactly what is passed via argument)
                $stackToRun = $middlewareStrategy;
            } elseif ($middlewareStrategy === true) {
                // MODE: Standard (Run route's own defined stack + Globals)
                // We fetch the calculated stack from the Route object itself.
                $stackToRun = $route->getMiddlewareStack($this->globalMiddlewares);
            }
            // MODE: False -> $stackToRun stays empty (Direct Execution).

            // D) FAST PATH OPTIMIZATION
            // If there are no middlewares to run, skip the pipeline overhead completely.
            if (empty($stackToRun)) {
                return $destination($this);
            }

            // E) RUN THE PIPELINE (Merged & Reinforced)
            
            // Method 1: Laravel Pipeline (Preferred & Most Compatible)
            if (class_exists(Pipeline::class) && function_exists('app')) {
                return app(Pipeline::class)
                    ->send($this)
                    ->through($stackToRun)
                    ->then($destination);
            }

            // Method 2: Native PHP Pipeline (Robust Fallback)
            // Iterates through the stack in reverse, wrapping the destination in onion layers.
            $pipeline = array_reduce(
                array_reverse($stackToRun),
                function ($next, $middleware) {
                    return function ($bot) use ($next, $middleware) {
                        
                        // 1. RESOLVE ALIASES
                        // Allows passing strings like 'auth' instead of full class names.
                        if (is_string($middleware) && property_exists($this, 'middlewareAliases') && isset($this->middlewareAliases[$middleware])) {
                            $middleware = $this->middlewareAliases[$middleware];
                        }

                        // 2. INSTANTIATE & EXECUTE
                        
                        // Case I: String Class Name
                        if (is_string($middleware) && class_exists($middleware)) {
                            $instance = new $middleware;
                            
                            // Sub-Case: Standard 'handle' method
                            if (method_exists($instance, 'handle')) {
                                return $instance->handle($bot, $next);
                            } 
                            // Sub-Case: Invokable Class (__invoke)
                            elseif (is_callable($instance)) {
                                return $instance($bot, $next);
                            }
                        }

                        // Case II: Closure Middleware
                        if ($middleware instanceof \Closure) {
                            return $middleware($bot, $next);
                        }

                        // Case III: Instantiated Object
                        if (is_object($middleware)) {
                            if (method_exists($middleware, 'handle')) {
                                return $middleware->handle($bot, $next);
                            } elseif (is_callable($middleware)) {
                                return $middleware($bot, $next);
                            }
                        }

                        // Fail-Safe: If middleware is invalid/unresolvable, don't crash.
                        // Just proceed to the next step.
                        return $next($bot);
                    };
                },
                $destination
            );

            // Ignite the Native Pipeline
            return $pipeline($this);

        } finally {
            // -----------------------------------------------------------------
            // 4. CONTEXT RESTORE (Restore State) 🛡️
            // -----------------------------------------------------------------
            // This runs ALWAYS, even if the destination controller throws an Exception.
            // Ensures the bot never gets stuck in the "wrong" route context.
            $this->currentResolvedHandler = $backupHandler;
            $this->currentRouteParams = $backupParams;
        }
    }

    /**
     * ⚡ REVERSE ROUTING HELPER
     * Find the raw pattern/command for a named route and inject parameters.
     * 
     * Example: resolvePattern('product.show', ['id' => 50]) => "/product 50"
     * 
     * @param string $name The route name defined via ->name()
     * @param array $params Key-value pairs to replace in the pattern
     * @return string|null The ready-to-use command string or null if not found.
    */
    public function resolvePattern(string $name, array $params = []): ?string
    {
        // 1. Lookup Route
        if (!isset($this->namedRoutes[$name])) {
            return null;
        }
        
        /** @var \KrubiK\Router\Route $route */
        $route = $this->namedRoutes[$name];
        $pattern = $route->pattern; // e.g. "/product {id}"
        
        // 2. Return raw if no params
        if (empty($params)) {
            return $pattern;
        }
        
        // 3. Inject Parameters
        foreach ($params as $key => $value) {
            // Handles {id} and :{id} variations
            $pattern = str_replace(
                ['{' . $key . '}', ':{' . $key . '}'], 
                $value, 
                $pattern
            );
        }
        
        return $pattern;
    }
}
