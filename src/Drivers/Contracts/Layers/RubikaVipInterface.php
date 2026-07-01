<?php

namespace KrubiK\Drivers\Contracts\Layers;

/**
 * Rubika VIP Interface
 * 
 * The "Rebel" methods unique to Rubika architecture.
 */
interface RubikaVipInterface
{
    /**
     * Unified method for sending any media (Photo, Video, File, Voice).
     */
    public function sendFile(array $params): mixed;

    /**
     * Request permission to upload a file (Pre-upload step).
     */
    public function requestSendFile(array $params): mixed;

    /**
     * Edit the inline keyboard (Keypad).
     * Replaces editMessageReplyMarkup.
     */
    public function editMessageKeypad(array $params): mixed;

    /**
     * Manage bot webhooks and endpoints internally.
     */
    public function updateBotEndpoints(array $params): mixed;
}
