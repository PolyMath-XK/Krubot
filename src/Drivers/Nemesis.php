<?php

namespace KrubiK\Drivers;
/*
|--------------------------------------------------------------------------
| A Message to the Future Architect of Rebellion... 🚀🌌
|--------------------------------------------------------------------------
|
| Greetings, seeker of knowledge. You have just opened a blueprint
| from the Krubot BotEngine. What you see before you is more
| than just lines of code—it's a pattern for building scalable dreams.
|
| **This is a laboratory of creation.** We are experimenting with the
| very fabric of code here. Use this project as your ultimate training
| ground, a masterclass in *Software Dev Artistry.* It's a powerful template
| for learning, but not yet forged for the final battles of production.
|
| Behold the core principle:
| We Are **Rebuilding The Rebellion** Within S.N.P. *(The Foundation of Pure Power & Revel)*
| This entire library is being reconstructed with intense power,
| on a foundation of pure power **Far Stronger Than Anything That Came Before.**
| Starting with Laravel 12 Capabilities.
|
| What you see here is the **×0.7 ALPHA×** release. Why release it now?
| Because keeping this evolution a secret any longer would be a
| betrayal to the very community it was born to serve.
| 
| Consider this The Foundational Codex for Engineering a New Reality.
| The knowledge is free under the MIT License. Deconstruct its logic and schematics.
| Learn its secrets. Master its power. Command its potential. You are The Architect Now!
|
| * Go build something revolutionary! * 💜⚡️
|
| Let's Shape the Future. 🛠️⚡️🚀
|
*/

use Illuminate\Support\Manager;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use KrubiK\Drivers\RubikaDriver;
use KrubiK\Drivers\BaleDriver;
use KrubiK\Drivers\TelegramDriver;
use KrubiK\Enums\Platform;

/**
 * 🧠 Nemesis - THE Manager v10.0 (The Neuro-Link Singularity Edition)
 * The Autonomous Nervous System of KrubiK.
 *
 * --------------------------------------------------------------------------
 * The central intelligence that orchestrates Bio-Organic Weapons (BOWs).
 * Unlike a standard manager, Nemesis actively hunts for the correct driver,
 * infects it with identity protocols, and deploys it into the battlefield.
 *
 * This manager is not just a factory; it is a sentient entity that resolves,
 * identifies, and stamps drivers with their multiverse identity.
 *
 * ⚔️ Capabilities:
 * - 📡 Route-Aware Resolution (The Force Mode)
 * - 🕵️ Payload Sniffing & Bio-Metrics (The Detective Mode)
 * - 🏷️ Atomic Identity Stamping (Driver knows itself)
 * - 🛡️ Double-Tap Configuration Injection
 * 
 * @author DoKtor K.
 * @link https://StoryKo.de Official website of engine.
 * @version Krubot: ×v0.7ALPHA×
 * @license MIT
**/
class Nemesis extends Manager
{
    /**
     * 🧠 CORTEX INTERFACE (Required by Laravel)
     *
     * This method acts as the brain stem. It delegates the complex
     * decision-making to the advanced AI logic below.
     *
     * @return string The dominant virus strain name.
     */
    public function getDefaultDriver(): string
    {
        return $this->assessThreatEnvironment();
    }

    /**
     * 📡 THREAT ASSESSMENT (The Logic Core)
     *
     * Scans the environment (Routes & Payloads) to decide which
     * Bio-Organic Weapon (BOW) is best suited for the current combat scenario.
     *
     * @return string
     */
    protected function assessThreatEnvironment(): string
    {
        // 1. INTERCEPT SIGNAL (Route Parameter Priority)
        // If the neural network (Route) explicitly demands a specific strain.
        if ($targetStrain = Route::current()?->parameter('driver')) {
            if ($platform = Platform::tryFrom($targetStrain)) {
                return (string) $platform;
            }
        }

        // 2. ANALYZE BIO-METRICS (Payload Sniffing)
        // If no orders are given, Nemesis smells the blood (JSON Payload) to find the prey.
        if (Request::isMethod('post') && Request::isJson()) {
            return $this->performAutopsy(Request::all(), Request::header('User-Agent'));
        }

        // 3. DORMANT PROTOCOL (Fallback)
        // If the environment is silent, deploy the default sleeper agent.
        return $this->config->get('krubot.default_driver', (string) Platform::default());
    }

    /**
     * 🔬 AUTOPSY (Deep Inspection)
     *
     * Dissects the request body to identify the platform signature.
     *
     * @param array $tissueSample The request data
     * @param string|null $dnaSignature The User-Agent
     * @return string|null
     */
    private function performAutopsy(array $tissueSample, ?string $dnaSignature): ?string
    {
        // Case A: The Telegram/Bale Genotype (update_id based)
        if (isset($tissueSample['update_id'])) {
            // Check for Bale's specific genetic marker in the header
            if ($dnaSignature && stripos($dnaSignature, (string) Platform::Bale()) !== false) {
                return (string) Platform::Bale();
            }
            // Otherwise, it's the progenitor virus (Telegram)
            return (string) Platform::Telegram();
        }

        // Case B: The Rubika Genotype (Encryption based)
        if (isset($tissueSample['message_update']) || isset($tissueSample['enc_data'])) {
            return (string) Platform::Rubika();
        }

        return null;
    }

    /**
     * 🏭 SPAWN CHAMBER (Factory Override)
     *
     * Intercepts the birth of a new driver to forcefully inject
     * the Nemesis identity protocol before release.
     *
     * @param string $strain The name of the driver to create
     * @return mixed The mutated BOW instance
     */
    protected function createDriver($strain)
    {
        // 1. Spawning Phase: Let the base factory cultivate the organims
        // (Calls createRubikaDriver, etc.)
        $bow = parent::createDriver($strain);

        // 2. Mutation Phase: The Tentacle strikes
        // We inject the identity so the weapon knows its master and its name.
        $this->tentacle($bow, $strain);

        return $bow;
    }

    /**
     * 🦑 THE TENTACLE (Identity Injection)
     *
     * Wraps around the Bio-Organic Weapon and forces the identity DNA directly into its core.
     * This ensures the BOW acts with self-awareness of its platform.
     *
     * @param object $bow The Bio-Organic Weapon (Driver Instance)
     * @param string $viralCode The Strain Name (rubika, bale, etc.)
     */
    protected function tentacle(object $bow, string $viralCode): void
    {
        // Protocol Alpha: Neural Link (Setter)
        if (method_exists($bow, 'setDriverAlias')) {
            $bow->setDriverAlias($viralCode);
        }
        // Protocol Beta: Brute Force Mutation (Direct Property)
        elseif (property_exists($bow, 'driver_alias')) {
            $bow->driver_alias = $viralCode;
        }
        // Protocol Gamma: Legacy Infection (Backward Compat)
        elseif (method_exists($bow, 'setName')) {
            $bow->setName($viralCode);
        }
    }

    // =========================================================================
    //  🧪 INCUBATION CHAMBERS (Standard Factories)
    //  NOTE: Method names must adhere to Laravel's "create{Name}Driver" convention.
    //  However, the internal logic is pure chemical engineering.
    // =========================================================================

    /**
     * 🟡 RUBIKA FACTORY
     * 🟡 INCUBATE: RUBIKA
     * @return RubikaDriver
     */
    protected function createRubikaDriver(): RubikaDriver
    {
        // Extract genetic material
        $dna = $this->config->get('krubot.drivers.rubika', []);

        // Pre-injection of identity
        $dna['config'] = $dna['config'] ?? [];
        $dna['config']['driver_alias'] = (string) Platform::Rubika();

        return new RubikaDriver($dna);
    }

    /**
     * 🟢 BALE FACTORY
     * 🟢 INCUBATE: BALE
     * @return BaleDriver
     */
    protected function createBaleDriver(): BaleDriver
    {
        $dna = $this->config->get('krubot.drivers.bale', []);
        $dna['driver_alias'] = (string) Platform::Bale();

        return new BaleDriver($dna);
    }

    /**
     * 🔵 TELEGRAM FACTORY
     * 🔵 INCUBATE: TELEGRAM
     * @return TelegramDriver
     */
    protected function createTelegramDriver(): TelegramDriver
    {
        $dna = $this->config->get('krubot.drivers.telegram', []);
        
        // Adaptive mutation for config structure
        if (isset($dna['config'])) {
            $dna['config']['driver_alias'] = (string) Platform::Telegram();
        } else {
            $dna['driver_alias'] = (string) Platform::Telegram();
        }

        return new TelegramDriver($dna);
    }
}
