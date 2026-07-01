<?php

namespace KrubiK\DTOs;
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
 * یک DTO اتمیک و تغییرناپذیر برای آیتم‌های لیست انتخابی.
 * در PHP 8.2، کلاس‌های readonly سربار حافظه بسیار کمی دارند.
 * 
 * @author DoKtor K.
 * @link https://StoryKo.de Official website of engine.
 * @version Krubot: ×v0.7ALPHA×
 * @license MIT
**/
readonly class SelectionItem
{
    public function __construct(
        public string $id,
        public string $title,
        public ?string $description = null // آپشنال برای آینده‌نگری
    ) {}

    /**
     * تبدیل سریع به آرایه برای فرمت نهایی جیسون
     */
    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
        ], fn($v) => !is_null($v));
    }
}
