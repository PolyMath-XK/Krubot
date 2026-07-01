<?php

namespace KrubiK\Drivers\Contracts\Layers;

/**
 * Bale VIP Interface
 * 
 * The 5 methods where Bale deviates from the Telegram standard signature.
 */
interface BaleVipInterface
{
    /**
     * Send Invoice (Bale specific signature: provider_token logic).
     */
    public function sendInvoice(array $params): mixed;

    /**
     * Upload Sticker File (Supports Zip/Specific formats).
     */
    public function uploadStickerFile(array $params): mixed;

    /**
     * Create New Sticker Set (Unique package naming rules).
     */
    public function createNewStickerSet(array $params): mixed;

    /**
     * Add Sticker To Set.
     */
    public function addStickerToSet(array $params): mixed;

    /**
     * Set Chat Sticker Set (Group specific behavior).
     */
    public function setChatStickerSet(array $params): mixed;
}
