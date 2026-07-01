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

use RubikaBot\Message as BaseMessage;
use KrubiK\DTOs\RubikaInboundPayload;

class Message extends BaseMessage // we use VanGuard-Rubika Message Entity cause it's vast enough to support other messangers, however it's not the bestway possible. for now we just leave it until Revolution.
{
	/**
	 * Build legacy Message from normalized RubikaInboundPayload DTO.
	 * Backward-compatible bridge for queue-driven pipeline.
	*/
	public static function fromInboundPayload(RubikaInboundPayload $dto): self
	{
	    // Build a constructor-compatible shape expected by __construct(array $updateData)
	    // We intentionally keep this compact for performance and compatibility.
	    if ($dto->source === 'inline') {
	        $payload = [
	            'inline_message' => array_merge(
	                $dto->effectiveData,
	                [
	                    // Ensure required fallback keys exist
	                    'type' => $dto->type,
	                    'chat_id' => $dto->chatId,
	                    'sender_id' => $dto->senderId,
	                    'text' => $dto->text,
	                    'message_id' => $dto->messageId,
	                    'aux_data' => array_merge(
	                        $dto->effectiveData['aux_data'] ?? [],
	                        ['button_id' => $dto->auxData['button_id'] ?? null]
	                    ),
	                ]
	            ),
	        ];

	        return new self($payload);
	    }

	    // update / fallback path
	    $newMessage = array_merge(
	        $dto->effectiveData,
	        [
	            'sender_id' => $dto->senderId,
	            'text' => $dto->text,
	            'message_id' => $dto->messageId,
	            'time' => $dto->timestamp,
	        ]
	    );

	    // Propagate optional button_id if present (for action routing)
	    if (!isset($newMessage['aux_data']) || !is_array($newMessage['aux_data'])) {
	        $newMessage['aux_data'] = [];
	    }
	    if (!isset($newMessage['aux_data']['button_id']) && isset($dto->auxData['button_id'])) {
	        $newMessage['aux_data']['button_id'] = $dto->auxData['button_id'];
	    }

	    $payload = [
	        'update' => [
	            'type' => $dto->type,
	            'chat_id' => $dto->chatId,
	            'new_message' => $newMessage,
	        ],
	        // Keep compatibility with constructor fallback read:
	        'chat_id' => $dto->chatId,
	    ];

	    return new self($payload);
	}
}
