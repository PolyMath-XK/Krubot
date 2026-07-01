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

/**
 * @author DoKtor K.
 * @link https://StoryKo.de Official website of engine.
 * @version Krubot: ×v0.7ALPHA×
 * @license MIT
**/
class RouteGroup
{
    /** @var Route[] */
    protected array $routes = [];

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Apply middleware to ALL routes in this group.
     */
    public function middleware(string|array $middleware): self
    {
        foreach ($this->routes as $route) {
            $route->middleware($middleware);
        }
        return $this;
    }

    /**
     * Apply tag to ALL routes in this group.
     */
    public function tag(string $tag): self
    {
        foreach ($this->routes as $route) {
            $route->tag($tag);
        }
        return $this;
    }
    
    /**
     * Scope logic (Mock for now, can be expanded for Chat Types).
     */
    public function scope(mixed $scope): self
    {
        // Implementation depends on Scope logic requirements
        return $this;
    }
}
