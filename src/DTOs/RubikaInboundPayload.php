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

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

/**
 * RubikaInboundPayload (The Toxic DTO) ☣️
 * 
 * A strict, immutable data carrier optimized for PHP 8.2+.
 * It swallows chaos (raw JSON) and excretes order (Typed Objects).
 * 
 * @author DoKtor K.
 * @link https://StoryKo.de Official website of engine.
 * @version Krubot: ×v0.7ALPHA×
 * @license MIT
**/
readonly class RubikaInboundPayload implements Arrayable
{
    /**
     * DTO Constructor using PHP 8.0 Property Promotion.
     * All properties are public readonly.
     */
    public function __construct(
        public string $source,           // 'update', 'inline_message', 'inline', 'polling'
        public string $type,             // 'TextMessage', 'NewMessage', 'Event', etc.
        public ?string $chatId,          // object_guid / The arena (Object GUID)
        public ?string $messageId,       // message_id / The sacred ID
        public ?string $text,            // The Actual Content
        public ?string $senderId,        //  Who triggered this? Who User sent it?
        public ?int $timestamp,               // When? Timestamp
        public array $effectiveData,     // The actual 'new_message' or 'inline' block (Cleaned)
        public array $auxData,           // Extra meta data
        public array   $rawPayload,       // The raw 'update' block (for legacy parsers)
    ) {}

    /**
     * The Alchemist Method ⚗️
     * Transmutes raw dirty arrays into Gold.
     *
     * The Factory Method: Converts Toxic Arrays into a Clean Object.
     * 
     * @param array $data The raw request body
     * @return self
     */
    public static function forge(array $payload): self
    {
        // 1. Detect Source Strategy using match (PHP 8.0+) - Fast & Toxic
        $strategy = match (true) {
            isset($payload['update'])         => 'update',
            isset($payload['inline_message']) => 'inline',
            isset($payload['data']['updates']) => 'polling_wrapper', // In case complete polling result passed
            default                           => 'unknown',
        };

        // 2. Extract Core Data based on strategy
        return match ($strategy) {
            'update' => self::mapUpdate($payload['update']),
            'inline' => self::mapInline($payload['inline_message']),
            'polling_wrapper' => self::mapPollingWrapper($payload),
            default  => self::mapFallback($payload),
        };
    }

    private static function mapUpdate(array $data): self
    {
        // Typically: $data['new_message'] contains the real juice
        $msg = $data['new_message'] ?? [];
        
        return new self(
            source:        'update',
            type:          $data['type'] ?? 'GenericUpdate',
            messageId:     $msg['message_id'] ?? $data['message_id'] ?? 'N/A',
            chatId:        $data['chat_id'] ?? $data['object_guid'] ?? null,
            text:          $msg['text'] ?? null,
            senderId:      $msg['sender_id'] ?? null,
            timestamp:     $msg['time'] ?? time(),
            auxData: [
                'sender_type' => $msg['sender_type'] ?? null,
                'is_edited'   => $msg['is_edited'] ?? false,
                'button_id' => $msg['aux_data']['button_id'] ?? $data['aux_data']['button_id'] ?? null,
            ],
            effectiveData: $msg, // Pass the inner message block for processing
            rawPayload:       $data // Pass the wrapper for legacy access
        );
    }

    private static function mapInline(array $data): self
    {
        return new self(
            source:        'inline', // Or: 'inline_message'
            type:          'InlineInteraction',
            messageId:     $data['message_id'] ?? 'N/A',
            chatId:        $data['chat_id'] ?? null,
            text:          $data['text'] ?? null, // Often the payload value
            senderId:      $data['sender_id'] ?? null,
            timestamp:     time(), // Inline messages/events often lack timestamp
            auxData: [
                'sender_type' => $data['sender_type'] ?? null,
                'is_edited'   => $data['is_edited'] ?? false,
                'button_id'   => $data['aux_data']['button_id'] ?? null,
            ],
            effectiveData: $data,
            rawPayload:       $data
        );
    }

    private static function mapPollingWrapper(array $payload): self
    {
        $first = $payload['data']['updates'][0] ?? null;

        if (!is_array($first)) {
            return self::mapFallback($payload);
        }

        if (isset($first['update']) && is_array($first['update'])) {
            return self::mapUpdate($first['update']);
        }

        if (isset($first['inline_message']) && is_array($first['inline_message'])) {
            return self::mapInline($first['inline_message']);
        }

        return self::mapFallback($first);
    }

    private static function mapFallback(array $data): self
    {
        // Try to salvage whatever we can from a polling update or unknown blob
        return new self(
            source:        'polling_or_unknown',
            type:          $data['type'] ?? 'Unknown',
            messageId:     $data['message_id'] ?? 'TEMP_' . Str::random(8),
            chatId:        $data['object_guid'] ?? $data['chat_id'] ?? null,
            text:          $data['text'] ?? null,
            senderId:      $data['sender_id'] ?? null,
            timestamp:     $data['time'] ?? time(),
            auxData: [
                'sender_type' => $data['sender_type'] ?? null,
                'is_edited'   => $data['is_edited'] ?? false,
                'button_id'   => $data['aux_data']['button_id'] ?? null,
            ],
            effectiveData: $data,
            rawPayload:       $data
        );

        /*
        // Fallback / Unknown / Polling Raw
        // اگر از Polling دیتا میاد، اینجا میشه هندلش کرد یا یک آبجکت Null Object Pattern داد
        return new self(
            source:     'unknown',
            type:       'RawDump',
            chatId:     $data['object_guid'] ?? null,
            messageId:  $data['message_id'] ?? null,
            text:       $data['text'] ?? null,
            senderId:   null,
            time:       time(),
            auxData:    [],
            rawPayload: $data
        );
        */
    }

    /**
     * Generate a Unique Cache Key for Idempotency Signature.
     */
    public function signature(): string
    {
        if (!$this->messageId) {
            // Fallback for events without ID (like typing updates)
            return 'rb_event:' . md5(json_encode($this->rawPayload));
        }
        return "rb_msg:{$this->source}:{$this->chatId}:{$this->messageId}";

        /// return "rb_sig:{$this->source}:{$this->messageId}";
    }
    
    /**
     * Check if this is a valid processable message.
     */
    public function isValid(): bool
    {
        // return $this->chatId && $this->messageId;

        // Ignore internal events or incomplete payloads
        return $this->messageId !== 'N/A' && $this->chatId !== null;
    }

    /**
     * Laravel Arrayable Implementation.
     * Useful for Logging/Debugging: Log::info('Payload', $dto->toArray());
     */
    public function toArray(): array
    {
        return [
            's' => $this->source,
            'id' => $this->messageId,
            'uid' => $this->senderId,
            'type' => $this->type
        ];
    }
}
