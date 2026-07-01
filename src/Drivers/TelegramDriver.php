<?php

namespace KrubiK\Drivers;
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

use Telegram\Bot\Api as TGCore;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;
use Telegram\Bot\Objects\User;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Illuminate\Support\Collection;

// Contracts & Traits
use KrubiK\Drivers\Contracts\BotDriverInterface; // For General Polymorphism
use KrubiK\Drivers\Contracts\Layers\TelegramExclusiveInterface;
use KrubiK\Drivers\Arcane\NeonVitality;

// KrubiK Keyboards (For Adapter Logic)
use KrubiK\Keyboard\Keyboard as KrubiKInlineKeyboard;
use KrubiK\Keyboard\ReplyKeyboard as KrubiKReplyKeyboard;

/**
 * Class TelegramDriver - Titan implementation
 *
 * The "Strongest" implementation of the Telegram Driver for KrubiK (v5 Obsidian).
 *
 * This class is a High-Level Adapter that bridges the gap between the KrubiK
 * Meta-Framework and the native Telegram Bot SDK. It handles:
 * 1. Auto-conversion of Local File Paths to InputFile objects.
 * 2. Real-time translation of KrubiK Keyboards to Telegram ReplyMarkup JSON.
 * 3. Standardization of responses to Arrays for the Warlord Pipeline.
 * 
 * @author DoKtor K.
 * @link https://StoryKo.de Official website of engine.
 * @version Krubot: ×v0.7ALPHA×
 * @license MIT
**/
class TelegramDriver extends TGCore implements BotDriverInterface, TelegramExclusiveInterface
{
    // 💉 Inject the Soul: NeonVitality adds Context, Macroability, and Magic.
    use NeonVitality;

    /**
     * The specific configuration for this driver instance.
     * @var array
     */
    protected array $config;

    /**
     * TelegramDriver constructor.
     *
     * Handles both direct Token injection (legacy/simple) and Full Config Array (Manager style).
     *
     * @param array|string|null $config Config array or Token string.
     * @param bool $async Should the request be asynchronous (if supported).
     * @param \Telegram\Bot\HttpClients\HttpClientInterface|null $httpClientHandler Custom HTTP Client.
     * @param string|null $baseBotUrl Custom Base URL (for local bot servers).
     *
     * @throws TelegramSDKException
     */
    public function __construct($config = null, bool $async = false, $httpClientHandler = null, $baseBotUrl = null)
    {
        // 1. Normalize Configuration & Extract Token
        $token = null;

        if (is_array($config)) {
            $this->config = $config;
            $token = $config['token'] ?? $config['authtoken'] ?? null;
            // Override defaults if present in config array
            $async = $config['async'] ?? $async;
            $baseBotUrl = $config['base_url'] ?? $baseBotUrl;
        } elseif (is_string($config)) {
            $token = $config;
            $this->config = ['token' => $token];
        }

        // 2. Critical Security Check
        if (empty($token)) {
            throw new \InvalidArgumentException("Telegram Token is missing in driver configuration.");
        }

        // 3. Call the Old God (TGCore/Parent) constructor
        parent::__construct($token, $async, $httpClientHandler, $baseBotUrl);

        // 4. Ignite the NeonSoul Engine (Initialize Arcane/Context)
        if (method_exists($this, 'igniteNeon')) {
            $this->igniteNeon($this->config);
        }
    }

    /**
     * ⚡️ THE GREAT ADAPTER CORE: makeRequest() ⚡️
     *
     * This is the central dispatch method used by all KrubiK Arcane (e.g., CanPin, InteractsWithApi).
     * It acts as a middleware between the Framework and the SDK.
     *
     * @param string $method The Telegram API method name (e.g., 'sendMessage').
     * @param array $params The parameters array.
     * @return array The standardized response as an array.
     * @throws TelegramSDKException
     */
    public function makeRequest(string $method, array $params = []): array
    {
        // 1. Normalize & Translate Payload (Files + Keyboards)
        $params = $this->normalizePayload($params);

        try {
            // 2. Execute via Parent (SDK)
            // We call the method directly on the parent. The parent's __call or explicit methods
            // will handle the HTTP request.
            $response = $this->{$method}($params);

        } catch (\Exception $e) {
            // Rethrow to be handled by the Warlord's try-catch blocks
            throw $e;
        }

        // 3. Standardize Response (Object -> Array)
        // KrubiK's CommandOutcomeShifter expects an array to perform its magic.
        if ($response instanceof \Telegram\Bot\Objects\BaseObject) {
            return $response->toArray();
        }

        if ($response instanceof Collection) {
            return $response->toArray();
        }

        // Handle boolean/scalar responses (e.g., from deleteMessage)
        if (is_bool($response) || is_string($response) || is_numeric($response)) {
            return ['result' => $response, 'ok' => true];
        }

        return (array) $response;
    }

    /**
     * 🧠 PAYLOAD NORMALIZER: The Brain of the Adapter.
     *
     * Inspects the parameters and transforms KrubiK-specific structures (like Keypads)
     * or Local Paths into Telegram-compatible formats (JSON Strings, InputFiles).
     *
     * @param array $params
     * @return array
     */
    protected function normalizePayload(array $params): array
    {
        // --- Phase 1: File Handling ---
        // List of fields that might contain file paths/resources
        $fileFields = ['photo', 'audio', 'document', 'video', 'animation', 'voice', 'sticker', 'video_note', 'certificate', 'thumb'];

        foreach ($fileFields as $field) {
            if (isset($params[$field])) {
                $params[$field] = $this->ensureInputFile($params[$field]);
            }
        }

        // --- Phase 2: Keyboard Translation (The Magic) ---
        // KrubiK uses 'keypad', Telegram uses 'reply_markup'.

        // 2.1 Map 'keypad' to 'reply_markup' if present
        if (isset($params['keypad'])) {
            $params['reply_markup'] = $params['keypad'];
            unset($params['keypad']);
        }

        // 2.2 Transform the Markup Object to JSON
        if (isset($params['reply_markup'])) {
            $markup = $params['reply_markup'];

            // A) KrubiK Inline Keyboard -> Telegram Inline JSON
            if ($markup instanceof KrubiKInlineKeyboard) {
                $params['reply_markup'] = json_encode($this->transformInlineKeyboard($markup));
            }
            // B) KrubiK Reply Keyboard -> Telegram Reply JSON
            elseif ($markup instanceof KrubiKReplyKeyboard) {
                $params['reply_markup'] = json_encode($this->transformReplyKeyboard($markup));
            }
            // C) Raw Array (Manual Construction)
            elseif (is_array($markup)) {
                // Detect Rubika-style 'rows' structure inside an array and convert it
                if (isset($markup['rows']) && !isset($markup['inline_keyboard']) && !isset($markup['keyboard'])) {
                    $params['reply_markup'] = json_encode(['inline_keyboard' => $this->convertRowsToInline($markup['rows'])]);
                } else {
                    // Already structured or unknown, just encode it.
                    $params['reply_markup'] = json_encode($markup);
                }
            }
            // D) If it's already a JSON string or null, leave it alone.
        }

        return $params;
    }

    /**
     * Helper to smart-convert local paths to Telegram InputFile objects.
     *
     * @param mixed $file
     * @return mixed
     */
    protected function ensureInputFile(mixed $file): mixed
    {
        // If it's a string path and the file exists locally -> Create InputFile
        if (is_string($file) && file_exists($file)) {
            return InputFile::create($file);
        }

        // If it's a PHP Resource (Stream) -> Create InputFile
        if (is_resource($file)) {
            return InputFile::create($file);
        }

        // Otherwise (File ID, URL, or already InputFile) -> Return as is
        return $file;
    }

    // ========================================================================
    // 🎹 KEYBOARD TRANSFORMATION LOGIC (KrubiK -> Telegram)
    // ========================================================================

    /**
     * Transform KrubiK Inline Keyboard Object to Telegram Array Structure.
     */
    protected function transformInlineKeyboard(KrubiKInlineKeyboard $keyboard): array
    {
        // Extract raw data: ['rows' => [...]]
        $data = $keyboard->toArray();
        $rows = $data['rows'] ?? [];

        return [
            'inline_keyboard' => $this->convertRowsToInline($rows)
        ];
    }

    /**
     * Transform KrubiK Reply Keyboard Object to Telegram Array Structure.
     */
    protected function transformReplyKeyboard(KrubiKReplyKeyboard $keyboard): array
    {
        // KrubiK ReplyKeyboard structure is compatible with Telegram's logic,
        // assuming it produces ['keyboard' => [...], 'resize_keyboard' => ...].
        // We just return the array.
        return $keyboard->toArray();
    }

    /**
     * Convert generic Rows (from KrubiK) to Telegram Inline Rows.
     */
    protected function convertRowsToInline(array $rows): array
    {
        $tgRows = [];
        foreach ($rows as $row) {
            // KrubiK rows might be objects or arrays like ['buttons' => [...]] or just [...]
            $buttons = isset($row['buttons']) ? $row['buttons'] : $row;

            $tgRow = [];
            foreach ($buttons as $btn) {
                $tgRow[] = $this->convertButtonToInline($btn);
            }
            $tgRows[] = $tgRow;
        }
        return $tgRows;
    }

    /**
     * Convert a single KrubiK Button to a Telegram Inline Button.
     * Handles 'action_id' vs 'callback_data' and 'type: Link'.
     */
    protected function convertButtonToInline(array $btn): array
    {
        $tgBtn = ['text' => $btn['text']];

        // 1. Handle Links (URL)
        // Check for 'type' => 'Link' (Rubika style) OR explicit 'url' key.
        if ((isset($btn['type']) && $btn['type'] === 'Link') || isset($btn['url'])) {
            $url = $btn['url'] ?? ($btn['link_data']['url'] ?? null);
            if ($url) {
                $tgBtn['url'] = $url;
                return $tgBtn; // Links don't need callback_data
            }
        }

        // 2. Handle Callback Data
        // KrubiK uses 'action_id' primarily.
        if (isset($btn['action_id'])) {
            $tgBtn['callback_data'] = $btn['action_id'];
        }
        // Fallback for JSON data
        elseif (isset($btn['action_data'])) {
            $tgBtn['callback_data'] = is_array($btn['action_data'])
                ? json_encode($btn['action_data'])
                : $btn['action_data'];
        }
        // Default Safety
        else {
            $tgBtn['callback_data'] = 'NO_ACTION';
        }

        return $tgBtn;
    }

    // ========================================================================
    // 🚀 EXPLICIT METHOD OVERRIDES (For Strict Type Handling)
    // ========================================================================
    // While makeRequest handles most things, overriding these ensures that even
    // direct calls to $driver->sendPhoto() use our File Logic.

    /**
     * {@inheritDoc}
     */
    public function sendPhoto(array $params): Message
    {
        if (isset($params['photo'])) {
            $params['photo'] = $this->ensureInputFile($params['photo']);
        }
        return parent::sendPhoto($params);
    }

    /**
     * {@inheritDoc}
     */
    public function sendAudio(array $params): Message
    {
        if (isset($params['audio'])) {
            $params['audio'] = $this->ensureInputFile($params['audio']);
        }
        return parent::sendAudio($params);
    }

    /**
     * {@inheritDoc}
     */
    public function sendDocument(array $params): Message
    {
        if (isset($params['document'])) {
            $params['document'] = $this->ensureInputFile($params['document']);
        }
        return parent::sendDocument($params);
    }

    /**
     * {@inheritDoc}
     */
    public function sendVideo(array $params): Message
    {
        if (isset($params['video'])) {
            $params['video'] = $this->ensureInputFile($params['video']);
        }
        return parent::sendVideo($params);
    }

    /**
     * {@inheritDoc}
     */
    public function sendVoice(array $params): Message
    {
        if (isset($params['voice'])) {
            $params['voice'] = $this->ensureInputFile($params['voice']);
        }
        return parent::sendVoice($params);
    }

    /**
     * {@inheritDoc}
     */
    public function sendAnimation(array $params): Message
    {
        if (isset($params['animation'])) {
            $params['animation'] = $this->ensureInputFile($params['animation']);
        }
        return parent::sendAnimation($params);
    }

    /**
     * {@inheritDoc}
     */
    public function sendSticker(array $params): Message
    {
        if (isset($params['sticker'])) {
            $params['sticker'] = $this->ensureInputFile($params['sticker']);
        }
        return parent::sendSticker($params);
    }

    /**
     * {@inheritDoc}
     */
    public function sendVideoNote(array $params): Message
    {
        if (isset($params['video_note'])) {
            $params['video_note'] = $this->ensureInputFile($params['video_note']);
        }
        return parent::sendVideoNote($params);
    }

    /**
     * {@inheritDoc}
     */
    public function setWebhook(array $params): bool
    {
        // مدیریت هوشمند آپلود سرتیفیکیت
         if (isset($params['certificate'])) {
            $params['certificate'] = $this->ensureInputFile($params['certificate']);
        }
        return parent::setWebhook($params);
    }

    // ========================================================================
    // 🔮 MAGIC FALLBACK
    // ========================================================================

    /**
     * Handles dynamic method calls to support SDK updates without code changes.
     * This makes the driver "Forward Compatible" and supports the 108-method interface.
     */
    public function __call($method, $parameters)
    {
        // Parent's __call handles the command bus and macro calls logic.
        return parent::__call($method, $parameters);
    }

        /* -------------------------------------------------------------------------- */
    /*                            1. UPDATES & WEBHOOK                            */
    /* -------------------------------------------------------------------------- */

    public function getUpdates(array $params = []): array
    {
        return parent::getUpdates($params);
    }

    public function deleteWebhook(array $params = []): bool
    {
        return parent::deleteWebhook($params);
    }

    public function getWebhookInfo(): object
    {
        return parent::getWebhookInfo();
    }

    /* -------------------------------------------------------------------------- */
    /*                             2. BASE & AUTH                                 */
    /* -------------------------------------------------------------------------- */

    public function getMe(): object
    {
        return parent::getMe();
    }

    public function logOut(): bool
    {
        return parent::logOut();
    }

    public function close(): bool
    {
        return parent::close();
    }

    public function getFile(array $params): object
    {
        return parent::getFile($params);
    }

    public function getUserProfilePhotos(array $params): object
    {
        return parent::getUserProfilePhotos($params);
    }

    /* -------------------------------------------------------------------------- */
    /*                          3. SENDING MESSAGES                               */
    /* -------------------------------------------------------------------------- */

    public function sendMessage(array $params): object
    {
        return parent::sendMessage($params);
    }

    public function forwardMessage(array $params): object
    {
        return parent::forwardMessage($params);
    }

    public function copyMessage(array $params): object
    {
        return parent::copyMessage($params);
    }

    public function sendPhoto(array $params): object
    {
        if (isset($params['photo'])) {
            $params['photo'] = $this->ensureInputFile($params['photo']);
        }
        return parent::sendPhoto($params);
    }

    public function sendAudio(array $params): object
    {
        if (isset($params['audio'])) {
            $params['audio'] = $this->ensureInputFile($params['audio']);
        }
        return parent::sendAudio($params);
    }

    public function sendDocument(array $params): object
    {
        if (isset($params['document'])) {
            $params['document'] = $this->ensureInputFile($params['document']);
        }
        return parent::sendDocument($params);
    }

    public function sendVideo(array $params): object
    {
        if (isset($params['video'])) {
            $params['video'] = $this->ensureInputFile($params['video']);
        }
        return parent::sendVideo($params);
    }

    public function sendAnimation(array $params): object
    {
        if (isset($params['animation'])) {
            $params['animation'] = $this->ensureInputFile($params['animation']);
        }
        return parent::sendAnimation($params);
    }

    public function sendVoice(array $params): object
    {
        if (isset($params['voice'])) {
            $params['voice'] = $this->ensureInputFile($params['voice']);
        }
        return parent::sendVoice($params);
    }

    public function sendVideoNote(array $params): object
    {
        if (isset($params['video_note'])) {
            $params['video_note'] = $this->ensureInputFile($params['video_note']);
        }
        return parent::sendVideoNote($params);
    }

    public function sendMediaGroup(array $params): object
    {
        // در اینجا فرض بر این است که InputMediaها قبلاً ساخته شده‌اند
        // یا کاربر آرایه خام فرستاده که SDK هندل می‌کند.
        return parent::sendMediaGroup($params);
    }

    public function sendLocation(array $params): object
    {
        return parent::sendLocation($params);
    }

    public function sendVenue(array $params): object
    {
        return parent::sendVenue($params);
    }

    public function sendContact(array $params): object
    {
        return parent::sendContact($params);
    }

    public function sendPoll(array $params): object
    {
        return parent::sendPoll($params);
    }

    public function sendDice(array $params): object
    {
        return parent::sendDice($params);
    }

    public function sendChatAction(array $params): bool
    {
        return parent::sendChatAction($params);
    }

    public function setMessageReaction(array $params): bool
    {
        return parent::setMessageReaction($params);
    }

    /* -------------------------------------------------------------------------- */
    /*                          4. EDITING MESSAGES                               */
    /* -------------------------------------------------------------------------- */

    public function editMessageText(array $params): mixed
    {
        return parent::editMessageText($params);
    }

    public function editMessageCaption(array $params): mixed
    {
        return parent::editMessageCaption($params);
    }

    public function editMessageMedia(array $params): mixed
    {
        return parent::editMessageMedia($params);
    }

    public function editMessageReplyMarkup(array $params): mixed
    {
        return parent::editMessageReplyMarkup($params);
    }

    public function stopPoll(array $params): object
    {
        return parent::stopPoll($params);
    }

    public function deleteMessage(array $params): bool
    {
        return parent::deleteMessage($params);
    }

    public function deleteMessages(array $params): bool
    {
        // اگر SDK متد deleteMessages را نداشت، از __call والد استفاده می‌کند
        return parent::deleteMessages($params);
    }

    /* -------------------------------------------------------------------------- */
    /*                          5. CHAT ADMINISTRATION                            */
    /* -------------------------------------------------------------------------- */

    public function banChatMember(array $params): bool
    {
        return parent::banChatMember($params);
    }

    public function unbanChatMember(array $params): bool
    {
        return parent::unbanChatMember($params);
    }

    public function restrictChatMember(array $params): bool
    {
        return parent::restrictChatMember($params);
    }

    public function promoteChatMember(array $params): bool
    {
        return parent::promoteChatMember($params);
    }

    public function setChatAdministratorCustomTitle(array $params): bool
    {
        return parent::setChatAdministratorCustomTitle($params);
    }

    public function banChatSenderChat(array $params): bool
    {
        return parent::banChatSenderChat($params);
    }

    public function unbanChatSenderChat(array $params): bool
    {
        return parent::unbanChatSenderChat($params);
    }

    public function setChatPermissions(array $params): bool
    {
        return parent::setChatPermissions($params);
    }

    public function exportChatInviteLink(array $params): string
    {
        return parent::exportChatInviteLink($params);
    }

    public function createChatInviteLink(array $params): object
    {
        return parent::createChatInviteLink($params);
    }

    public function editChatInviteLink(array $params): object
    {
        return parent::editChatInviteLink($params);
    }

    public function revokeChatInviteLink(array $params): object
    {
        return parent::revokeChatInviteLink($params);
    }

    public function approveChatJoinRequest(array $params): bool
    {
        return parent::approveChatJoinRequest($params);
    }

    public function declineChatJoinRequest(array $params): bool
    {
        return parent::declineChatJoinRequest($params);
    }

    public function setChatPhoto(array $params): bool
    {
        if (isset($params['photo'])) {
            $params['photo'] = $this->ensureInputFile($params['photo']);
        }
        return parent::setChatPhoto($params);
    }

    public function deleteChatPhoto(array $params): bool
    {
        return parent::deleteChatPhoto($params);
    }

    public function setChatTitle(array $params): bool
    {
        return parent::setChatTitle($params);
    }

    public function setChatDescription(array $params): bool
    {
        return parent::setChatDescription($params);
    }

    public function pinChatMessage(array $params): bool
    {
        return parent::pinChatMessage($params);
    }

    public function unpinChatMessage(array $params): bool
    {
        return parent::unpinChatMessage($params);
    }

    public function unpinAllChatMessages(array $params): bool
    {
        return parent::unpinAllChatMessages($params);
    }

    public function leaveChat(array $params): bool
    {
        return parent::leaveChat($params);
    }

    public function getChat(array $params): object
    {
        return parent::getChat($params);
    }

    public function getChatAdministrators(array $params): array
    {
        return parent::getChatAdministrators($params);
    }

    public function getChatMemberCount(array $params): int
    {
        return parent::getChatMemberCount($params);
    }

    public function getChatMember(array $params): object
    {
        return parent::getChatMember($params);
    }

    public function setChatStickerSet(array $params): bool
    {
        return parent::setChatStickerSet($params);
    }

    public function deleteChatStickerSet(array $params): bool
    {
        return parent::deleteChatStickerSet($params);
    }

    /* -------------------------------------------------------------------------- */
    /*                          6. FORUM & TOPICS                                 */
    /* -------------------------------------------------------------------------- */

    public function getForumTopicIconStickers(array $params = []): array
    {
        return parent::getForumTopicIconStickers($params);
    }

    public function createForumTopic(array $params): object
    {
        return parent::createForumTopic($params);
    }

    public function editForumTopic(array $params): bool
    {
        return parent::editForumTopic($params);
    }

    public function closeForumTopic(array $params): bool
    {
        return parent::closeForumTopic($params);
    }

    public function reopenForumTopic(array $params): bool
    {
        return parent::reopenForumTopic($params);
    }

    public function deleteForumTopic(array $params): bool
    {
        return parent::deleteForumTopic($params);
    }

    public function unpinAllForumTopicMessages(array $params): bool
    {
        return parent::unpinAllForumTopicMessages($params);
    }

    public function editGeneralForumTopic(array $params): bool
    {
        return parent::editGeneralForumTopic($params);
    }

    public function closeGeneralForumTopic(array $params): bool
    {
        return parent::closeGeneralForumTopic($params);
    }

    public function reopenGeneralForumTopic(array $params): bool
    {
        return parent::reopenGeneralForumTopic($params);
    }

    public function hideGeneralForumTopic(array $params): bool
    {
        return parent::hideGeneralForumTopic($params);
    }

    public function unhideGeneralForumTopic(array $params): bool
    {
        return parent::unhideGeneralForumTopic($params);
    }

    /* -------------------------------------------------------------------------- */
    /*                             7. STICKERS                                    */
    /* -------------------------------------------------------------------------- */

    public function sendSticker(array $params): object
    {
        if (isset($params['sticker'])) {
            $params['sticker'] = $this->ensureInputFile($params['sticker']);
        }
        return parent::sendSticker($params);
    }

    public function getStickerSet(array $params): object
    {
        return parent::getStickerSet($params);
    }

    public function uploadStickerFile(array $params): object
    {
        if (isset($params['png_sticker'])) {
            $params['png_sticker'] = $this->ensureInputFile($params['png_sticker']);
        }
        // پشتیبانی از فرمت TGS برای استیکرهای متحرک
        if (isset($params['tgs_sticker'])) {
            $params['tgs_sticker'] = $this->ensureInputFile($params['tgs_sticker']);
        }
        if (isset($params['webm_sticker'])) {
            $params['webm_sticker'] = $this->ensureInputFile($params['webm_sticker']);
        }
        return parent::uploadStickerFile($params);
    }

    public function createNewStickerSet(array $params): bool
    {
        if (isset($params['png_sticker'])) $params['png_sticker'] = $this->ensureInputFile($params['png_sticker']);
        if (isset($params['tgs_sticker'])) $params['tgs_sticker'] = $this->ensureInputFile($params['tgs_sticker']);
        if (isset($params['webm_sticker'])) $params['webm_sticker'] = $this->ensureInputFile($params['webm_sticker']);
        return parent::createNewStickerSet($params);
    }

    public function addStickerToSet(array $params): bool
    {
        if (isset($params['png_sticker'])) $params['png_sticker'] = $this->ensureInputFile($params['png_sticker']);
        if (isset($params['tgs_sticker'])) $params['tgs_sticker'] = $this->ensureInputFile($params['tgs_sticker']);
        if (isset($params['webm_sticker'])) $params['webm_sticker'] = $this->ensureInputFile($params['webm_sticker']);
        return parent::addStickerToSet($params);
    }

    public function setStickerPositionInSet(array $params): bool
    {
        return parent::setStickerPositionInSet($params);
    }

    public function deleteStickerFromSet(array $params): bool
    {
        return parent::deleteStickerFromSet($params);
    }

    public function setStickerSetThumb(array $params): bool
    {
        if (isset($params['thumb'])) {
            $params['thumb'] = $this->ensureInputFile($params['thumb']);
        }
        return parent::setStickerSetThumb($params);
    }

    /* -------------------------------------------------------------------------- */
    /*                       8. INLINE, WEBAPPS & CALLBACKS                       */
    /* -------------------------------------------------------------------------- */

    public function answerCallbackQuery(array $params): bool
    {
        return parent::answerCallbackQuery($params);
    }

    public function answerInlineQuery(array $params): bool
    {
        return parent::answerInlineQuery($params);
    }

    public function answerWebAppQuery(array $params): object
    {
        return parent::answerWebAppQuery($params);
    }

    /* -------------------------------------------------------------------------- */
    /*                             9. PAYMENTS                                    */
    /* -------------------------------------------------------------------------- */

    public function sendInvoice(array $params): object
    {
        return parent::sendInvoice($params);
    }

    public function createInvoiceLink(array $params): string
    {
        return parent::createInvoiceLink($params);
    }

    public function answerShippingQuery(array $params): bool
    {
        return parent::answerShippingQuery($params);
    }

    public function answerPreCheckoutQuery(array $params): bool
    {
        return parent::answerPreCheckoutQuery($params);
    }

    /* -------------------------------------------------------------------------- */
    /*                        10. GAMES & PASSPORT                                */
    /* -------------------------------------------------------------------------- */

    public function sendGame(array $params): object
    {
        return parent::sendGame($params);
    }

    public function setGameScore(array $params): mixed
    {
        return parent::setGameScore($params);
    }

    public function getGameHighScores(array $params): array
    {
        return parent::getGameHighScores($params);
    }

    public function setPassportDataErrors(array $params): bool
    {
        return parent::setPassportDataErrors($params);
    }

    /* -------------------------------------------------------------------------- */
    /*                          11. LOCATION (LIVE)                               */
    /* -------------------------------------------------------------------------- */

    public function editMessageLiveLocation(array $params): mixed
    {
        return parent::editMessageLiveLocation($params);
    }

    public function stopMessageLiveLocation(array $params): mixed
    {
        return parent::stopMessageLiveLocation($params);
    }

    /* -------------------------------------------------------------------------- */
    /*                        12. BOT COMMANDS & MENUS                            */
    /* -------------------------------------------------------------------------- */

    public function setMyCommands(array $params): bool
    {
        return parent::setMyCommands($params);
    }

    public function deleteMyCommands(array $params): bool
    {
        return parent::deleteMyCommands($params);
    }

    public function getMyCommands(array $params): array
    {
        return parent::getMyCommands($params);
    }

    public function setMyName(array $params): bool
    {
        return parent::setMyName($params);
    }

    public function getMyName(array $params): object
    {
        return parent::getMyName($params);
    }

    public function setMyDescription(array $params): bool
    {
        return parent::setMyDescription($params);
    }

    public function getMyDescription(array $params): object
    {
        return parent::getMyDescription($params);
    }

    public function setMyShortDescription(array $params): bool
    {
        return parent::setMyShortDescription($params);
    }

    public function getMyShortDescription(array $params): object
    {
        return parent::getMyShortDescription($params);
    }

    public function setChatMenuButton(array $params): bool
    {
        return parent::setChatMenuButton($params);
    }

    public function getChatMenuButton(array $params): object
    {
        return parent::getChatMenuButton($params);
    }

    public function setMyDefaultAdministratorRights(array $params): bool
    {
        return parent::setMyDefaultAdministratorRights($params);
    }

    public function getMyDefaultAdministratorRights(array $params): object
    {
        return parent::getMyDefaultAdministratorRights($params);
    }

}
