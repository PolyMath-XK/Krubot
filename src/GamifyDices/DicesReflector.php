<?php

namespace KrubiK\GamifyDices;
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

use KrubiK\GamifyDices\Types\DiceVariant;
use ReflectionClass;

/**
 * Helper class to manage Dice variants using Native Constants & Reflection.
 * Eliminates the need for a separate config array.
 * 
 * Usage:
 * - Constant: DicesReflector::Soccer
 * - Method:   DicesReflector::Soccer()
 * - Dynamic:  DicesReflector::fromEmoji('⚽')
 * 
 * @method static DiceVariant Dice()
 * @method static DiceVariant Cube()
 * @method static DiceVariant Target()
 * @method static DiceVariant Basketball()
 * @method static DiceVariant Soccer()
 * @method static DiceVariant Football()
 * @method static DiceVariant Bowling()
 * @method static DiceVariant Slot()
 * ... covers all defined constants.
 * 
 * @author DoKtor K.
 * @link https://StoryKo.de Official website of engine.
 * @version Krubot: ×v0.7ALPHA×
 * @license MIT
 */
class DicesReflector
{
    // =========================================================================
    //  1. SINGLE SOURCE OF TRUTH (Constants)
    // =========================================================================

    // 🎲 Group
    public const Dice    = new DiceVariant('🎲', 'Dice', 6);
    public const Cube    = self::Dice;
    public const Regular = self::Dice;

    // 🎯 Group
    public const Target   = new DiceVariant('🎯', 'Dart', 6);
    public const Dart     = self::Target;
    public const Darts    = self::Target;
    public const Bullseye = self::Target;

    // 🏀 Group
    public const Basketball = new DiceVariant('🏀', 'Basketball', 5);
    public const Basket     = self::Basketball;
    public const Nba        = self::Basketball;

    // ⚽ Group
    public const Soccer     = new DiceVariant('⚽', 'Soccer', 5);
    public const Football   = self::Soccer;
    public const SoccerBall = self::Soccer;
    public const Goal       = self::Soccer;

    // 🎳 Group
    public const Bowling = new DiceVariant('🎳', 'Bowling', 6);
    public const Pins    = self::Bowling;
    public const Strike  = self::Bowling;

    // 🎰 Group
    public const Slot        = new DiceVariant('🎰', 'Slot', 64);
    public const SlotMachine = self::Slot;
    public const Casino      = self::Slot;
    public const Jackpot     = self::Slot;

    // =========================================================================
    //  2. MAGIC METHODS & REFLECTION
    // =========================================================================

    /**
     * Magic method to handle static calls like Dices::Soccer().
     * It scans defined constants to find a match (Case-Insensitive).
     */
    public static function __callStatic(string $name, array $arguments): DiceVariant
    {
        // Fast Path: Check exact match first
        if (defined("static::{$name}")) {
            return constant("static::{$name}");
        }

        // Slow Path: Case-Insensitive Search via Reflection
        // This allows Dices::soccer() even if const is Soccer
        $reflection = new ReflectionClass(static::class);
        $constants = $reflection->getConstants();

        foreach ($constants as $constName => $value) {
            if ($value instanceof DiceVariant && strcasecmp($constName, $name) === 0) {
                return $value;
            }
        }

        throw new \BadMethodCallException("Dice variant '{$name}' not found in " . static::class);
    }

    /**
     * Reverse lookup: Find a DiceVariant by its Emoji string.
     */
    public static function fromEmoji(string $emoji): ?DiceVariant
    {
        $reflection = new ReflectionClass(static::class);
        foreach ($reflection->getConstants() as $value) {
            if ($value instanceof DiceVariant && $value->emoji === $emoji) {
                return $value;
            }
        }
        return null;
    }

    /**
     * Returns a list of unique supported dices for documentation or UI.
     * Returns format: [['🎲', 6], ['🎯', 6], ...]
     * 
     * @return array<int, array{0: string, 1: int}>
     */
    public static function getAvailableList(): array
    {
        $unique = [];
        $reflection = new ReflectionClass(static::class);
        
        foreach ($reflection->getConstants() as $value) {
            if ($value instanceof DiceVariant) {
                // Use emoji as key to ensure uniqueness (deduplicate aliases)
                $unique[$value->emoji] = [$value->emoji, $value->max];
            }
        }

        return array_values($unique);
    }
}
