<?php

namespace KrubiK\Controllers;
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

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use KrubiK\Helpers\AmethystMatrix as Log;
use KrubiK\Drivers\Nemesis as KrubotManager;    // 🧠 The Brain
use KrubiK\DTOs\RubikaInboundPayload;           // ⚗️ The Alchemist
use KrubiK\Jobs\HandleDriverUpdate;             // 🚀 The Executor

/**
 * =========================================================================
 *  THE SUPER WEBHOOK CONTROLLER - THE Titanium POINT v2.87 (Ultimate Driver Edition)
 * =========================================================================
 *
 * 👑 The Apex Predator *
 * "The brain of the Legacy, the soul of the Modern, and the mindset of the Universal."
 *
 * This is the ultimate gatekeeper. It stands as the single, unified entry point
 * for webhook updates from ANY supported platform. It is designed for maximum
 * performance, scalability, and resilience under extreme load.
 *
 * 🧬 Its DNA is composed of four core principles:
 * 1.  IDENTIFY: Delegate driver identification to the hyper-intelligent KrubotManager.
 * 2.  INTERCEPT: Flash-check raw IDs to reject duplicate Requests InstanTly.
 * 3.  STANDARDIZE: Forge raw, toxic payloads into safe, immutable DTOs.
 * 4.  DISPATCH: Hand off the standardized DTO to the Laravel Queue.
 *
 * It does nothing else. It thinks about nothing else. It is pure, focused on efficiency.
 * 
 * @author DoKtor K.
 * @link https://StoryKo.de Official website of engine.
 * @version Krubot: ×v0.7ALPHA×
 * @license MIT
**/
class SuperWebhookController extends Controller
{
    /**
     * The one and only entry point for all incoming webhook traffic.
     * The Hyper-Optimized Gatekeeper.
     *
     * @param Request       $request The incoming HTTP request.
     * @param KrubotManager $manager The intelligent driver resolver.
     * @return JsonResponse A swift, immediate response to the calling platform.
     */
    public function __invoke(Request $request, KrubotManager $manager): JsonResponse
    {
        // -----------------------------------------------------------------
        // PHASE 1: 🧠 IDENTITY RESOLUTION (The Brain)
        // We ask the master strategist, "Who is at the gate?"
        // The Manager uses its 3-layered logic (Route -> Payload -> Config).
        // -----------------------------------------------------------------
        $driverIdentity = $manager->getDefaultDriver();
        // Log::info("SuperWebhook identified signal.", ['driver' => $driverIdentity]); // Optional Debug

        // Get raw payload once
        $payload = $request->all();

        // Guard Clause: Acknowledge and ignore empty requests immediately.
        if (empty($payload)) {
            Log::warning("SuperWebhook received empty payload.", ['driver' => $driverIdentity]);
            return response()->json(['status' => 'ignored_empty'], 200);
        }

        // -----------------------------------------------------------------
        // PHASE 2: ⚡ FLASH IDEMPOTENCY CHECK (The Vanguard Optimization)
        // "Stop right there, criminal scum!"
        // Why build the DTO if we've seen this ID 1ms ago?
        // We peek into the raw array to save CPU cycles (O(1) Access).
        // -----------------------------------------------------------------
        $rawMsgId = $payload['update']['message_id']
                 ?? $payload['inline_message']['message_id']
                 ?? $payload['message_update']['message_id'] // Rubika specific
                 ?? $payload['update_id'] // Telegram/Bale specific
                 ?? null;

        // Note: Cast to string ensures consistency across drivers.
        // If identified here, we exit BEFORE the heavy Forge process.
        if ($rawMsgId && $this->isDuplicate((string)$rawMsgId, $driverIdentity)) {
            return response()->json(['ok' => true, 'status' => 'duplicate_fast_fail']);
        }

        // -----------------------------------------------------------------
        // PHASE 3: ⚗️ PAYLOAD STANDARDIZATION (The Alchemist)
        // We take the raw input and pass it to our Alchemist (the DTO)
        // to be transmuted into a standard, safe, and immutable data structure.
        // -----------------------------------------------------------------
        try {
            $dto = RubikaInboundPayload::forge($payload);
        } catch (\Throwable $e) {
            Log::error("SuperWebhook Forge Error [{$driverIdentity}]: " . $e->getMessage());
            // Return 200 to prevent platform retries on malformed data
            return response()->json(['status' => 'error_structure'], 200);
        }

        // -----------------------------------------------------------------
        // PHASE 4: 🛡️ DEEP STRUCTURAL VALIDATION
        // We use the DTO's own intelligence to validate itself.
        // -----------------------------------------------------------------
        if (!$dto->isValid()) {
            Log::warning("SuperWebhook ignored invalid structure.", ['driver' => $driverIdentity]);
            return response()->json(['status' => 'ignored_invalid'], 200);
        }

        // Fallback Idempotency: If Phase 2 missed the ID (e.g. obscured structure),
        // check again using the DTO's signature, BUT only if we didn't check already.
        if (!$rawMsgId && $this->isDuplicate($dto->signature(), $driverIdentity)) {
             return response()->json(['ok' => true, 'status' => 'duplicate_dto_check']);
        }

        // -----------------------------------------------------------------
        // PHASE 5: 🚀 DISPATCH TO THE ABYSS (The Queue)
        // The Gatekeeper's job is done. The pure DTO and its identity are handed
        // off to the asynchronous world of Laravel Queues.
        // -----------------------------------------------------------------
        HandleDriverUpdate::dispatch($dto, $driverIdentity);

        return response()->json(['status' => 'queued'], 200);
    }

    /**
     * Checks if a given unique signature for a specific driver has been processed recently.
     * The cache key is namespaced by the driver to prevent cross-platform collisions.
     *
     * @param string $messageId The unique identifier (from Raw or DTO).
     * @param string $driver    The resolved driver identity.
     * @return bool True if it's a duplicate, false otherwise.
     */
    private function isDuplicate(string $messageId, string $driver): bool
    {
        // The key is a combination of driver and ID for absolute uniqueness.
        // e.g. "processed_msg:rubika:12345" vs "processed_msg:bale:12345"
        $cacheKey = "processed_msg:{$driver}:{$messageId}";

        if (cache()->has($cacheKey)) {
            return true;
        }

        // Lock for 2 minutes.
        // Sufficient to block immediate retries, short enough to keep cache light.
        cache()->put($cacheKey, true, now()->addMinutes(2));

        return false;
    }
}
