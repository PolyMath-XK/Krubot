<?php
// FILE: KrubiK/Enums/Platform.php

namespace KrubiK\Enums;

use Stringable; // Import the interface
use BadMethodCallException;
use ValueError;

/**
 * Sacred 'Platform' Multi-CaseClass (First Origin of FusionPrimes)
 *
 * The Dynamic, Config-driven "Enum-like" Platform class.
 *
 * IT DYNAMICALLY LEARNS FROM YOUR `config/krubot.php` FILE!
 *
 * This is not a native PHP Enum. It is a more powerful, dynamic class
 * that simulates an Enum's behavior with 100% identical DX.
 *
 * - Boots lazily from config('krubot.drivers.aliases')
 * - Supports case-insensitive static calls for aliases and canonical names:
 *      Platform::r(), Platform::R(), Platform::Rubika(), Platform::tg(), Platform::TG(), ...
 * - Supports shorthand for default: Platform::def(), Platform::Default()
 *
 * Usage:
 *   Platform::r() === Platform::Rubika(); // true
 *   (string) Platform::tg() === 'telegram'; 
 *
 * The single source of truth for all driver identities in the multiverse.
 * Eliminates magic strings, provides type-safety, and boosts IDE autocompletion.
 *
 * Now implements Stringable for the ultimate Developer Experience.
 * Allows direct usage in string contexts without calling ->value.
 *
 * How to use:
 * $platform = Platform::Rubika();
 * echo $platform; // 'rubika'
 * $platform === Platform::Rubika(); // true
 * 
 * @author DoKtor K.
 * @link https://StoryKo.de Official website of engine.
 * @version Krubot: ×v0.7ALPHA×
 * @license MIT
**/
final class Platform implements Stringable
{
    /** @var array<string,string> aliasKey(lowercase) => canonicalName(lowercase) */
    private static array $aliasMap = [];

	/**
     * 🧠 The Singleton Instance Registry.
     * Stores the single instance of each platform.
     * Key: 'rubika', Value: Platform object for Rubika.
	 * @var array<string,self> canonicalName => instance
	*/
    private static array $instances = [];

    /**
     * The flag to ensure we only boot once.
    */
	private static bool $booted = false;

    /**
     * The actual canonical string value of the platform (e.g., 'rubika').
    */
	private readonly string $value;

    /**
     * The constructor is private to enforce the Singleton pattern.
     * No one can create a new instance from outside.
    */
	private function __construct(string $canonical)
    {
        $this->value = $canonical;
    }

	/**
     * 🚀 The Bootstrapper.
     * This is the magic that reads from your config file.
     * It runs only ONCE, the very first time a Platform is requested.
	 * Boot once: load aliases from config and normalize them.
    */
    private static function boot(): void
    {
        if (self::$booted) return;

        // Get and Read the list of all canonical platform names & aliases from the config.
		// result expected: ['r'=>'rubika', 'tg'=>'telegram', ...]
        $aliases = config('krubot.drivers.aliases', []);

        // normalize: lower-case keys and values
        $map = [];
        foreach ($aliases as $key => $val) {
            if (!is_string($key) || !is_string($val)) continue;
            $map[strtolower($key)] = strtolower($val);
        }

        // Ensure canonical set includes the canonical keys (values of aliases)
        $canonicals = array_values($map);
        $canonicals = array_unique($canonicals);

        // Create a singleton instance for each canonical platform.
        foreach ($canonicals as $canonical) {
            self::$instances[$canonical] = new self($canonical);
        }

        // store map
        self::$aliasMap = $map;

        // Also ensure that canonical names map to themselves (allow Platform::Rubika())
        foreach (array_keys(self::$instances) as $canonical) {
            self::$aliasMap[$canonical] = $canonical;
        }

        self::$booted = true;
    }

    /**
     * Ensures the class is booted before any operation.
    */
    private static function ensureBooted(): void
    {
        if (!self::$booted) self::boot();
    }

    /**
     * 🎩 The Magic Static Caller.
     * This method is triggered when you call a static method that doesn't exist,
     * like `Platform::Rubika()` or `Platform::Telegram()`, Platform::r(), Platform::Rubika(), Platform::TG(), Platform::Def(), ...
     *
     * - Case-insensitive
     * - If name is 'default'|'def' -> resolve from config('krubot.default_driver')
	 *
     * @param string $name The name of the case (e.g., "Rubika").
     * @return self
    */
    public static function __callStatic(string $name, array $args): self
    {
        self::ensureBooted();

        $lower = strtolower($name); // 'Rubika' -> 'rubika'

        // Handle default aliases
        if (in_array($lower, ['default', 'def', 'd'], true)) {
            $default = strtolower((string) config('krubot.default_driver', 'rubika'));
            return self::fromOrCreate($default);
        }

        // Direct lookup in alias map (case-insensitive keys)
        if (isset(self::$aliasMap[$lower])) {
            $canonical = self::$aliasMap[$lower];
            return self::fromOrCreate($canonical);
        }

        // Try treating the provided name as canonical (lowercased)
        if (isset(self::$instances[$lower])) {
            return self::$instances[$lower];
        }

        throw new BadMethodCallException("Platform [{$name}] is not defined in your `krubot.php` config's aliases and is not a known platform.");
    }

    /** Helper that returns existing instance or creates a new one (defensive) */
    private static function fromOrCreate(string $canonical): self
    {
        $canonical = strtolower($canonical);
        if (!isset(self::$instances[$canonical])) {
            // lazily create (covers case where config aliases didn't include explicit canonical list)
            self::$instances[$canonical] = new self($canonical);
            // also map canonical to itself for future calls
            self::$aliasMap[$canonical] = $canonical;
        }
        return self::$instances[$canonical];
    }

    /**
     * The modern `from` method, like a real enum.
	 * Behaves like enum::from — accepts canonical or any alias (case-insensitive)
    */
    public static function from(string $value): self
    {
        $instance = self::tryFrom($value);
        if ($instance === null) {
            throw new ValueError("\"{$value}\" is not a valid backing value for Platform Enum");
        }
        return $instance;
    }

     /**
     * The modern `tryFrom` method, like a real enum.
	 * Tries to resolve alias or canonical
	*/
    public static function tryFrom(string $value): ?self
    {
        self::ensureBooted();
        $key = strtolower($value);
        if (isset(self::$aliasMap[$key])) {
            return self::fromOrCreate(self::$aliasMap[$key]);
        }
        if (isset(self::$instances[$key])) {
            return self::$instances[$key];
        }
        return null;
    }

	/**
     * The modern `cases` method, like a real enum.
	 *
	 * Return all cases (instances)
     * @return array<int, self>
    */
    public static function cases(): array
    {
        self::ensureBooted();
        return array_values(self::$instances);
    }

    /**
     * Returns the default platform for the entire application.
     * Centralizes the fallback logic.
     *
     * @return self
     */
    public static function default(): self
    {
        self::ensureBooted();
        $default = strtolower((string) config('krubot.default_driver', 'rubika')); // Return default platform instance from config
        return self::fromOrCreate($default);
    }

    /** Get canonical string */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * Magic method that makes this Enum behave like a string.
     * This is the heart of the DX improvement.
     *
     * The magic __toString method for Stringable interface.
     * Allows `echo Platform::Bale;` to print 'bale'.
	 *
	 * Stringable: when cast to string, return canonical
	 * @return string
    */
    public function __toString(): string // 👈 3. Define the conversion logic
    {
        return $this->value;
    }
}
