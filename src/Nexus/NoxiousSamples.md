# 📂 THE ULTIMATE KRUBIK DX GRIMOIRE (V3.333)
> **Entity:** DoKtor K. (Full-Stack PolyMath)
> **Goal:** HyperDX, Extreme Reliability, Dimensional Architecture.
> **Total Unique Artifacts:** 101

---

## 🛣️ BATCH 1: ROUTING & CONTEXT MAGIC

### Example 01 — The "Fluent Symphony" (Routing & Context Magic)
* **Concept:** Why write 10 lines of boilerplate when you can chain reality itself? No manual request parsing or ID fetching. Just pure fluid logic.
* **Slogan:** 🎻 Mozart Level

```php
// 📂 File: app/Nexus/Gateways/WelcomeNexus.php
namespace App\Nexus\Gateways;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;

class WelcomeNexus {
    #[OnCommand('start')]
    public function ignite(Krubot $bot) {
        $user = $bot->user();
        $bot->reply("Greetings {$user['first_name']}! 👋\nWelcome to the Matrix.")
            ->replyTo($bot->messageId())
            ->keyboard(fn($kb) => $kb->row(
                fn($row) => $row->simple("start_game", "🎮 Enter Arena")
                                ->simple("support_desk", "🆘 Support")
            ))
            ->send();
    }
}
```

### Example 02 — The "Route Group Tango" (Military-Grade Structuring)
* **Concept:** Applying prefixes and security middlewares to a cluster of admin commands, keeping the main routing clean and highly organized.
* **Slogan:** 🗂️ Absolute Architecture

```php
// 📂 File: app/Providers/RouteServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use KrubiK\Krubot;
use App\Nexus\Admin\ManageUsersNexus;

class RouteServiceProvider extends ServiceProvider {
    public function boot(Krubot $bot): void {
        $bot->group([
            'prefix' => 'sudo_',                 
            'middleware' => ['auth', 'god_mode'] 
        ], function (Krubot $router) {
            $router->onCommand('ban {user_id}', [ManageUsersNexus::class, 'executeBan']);
            $router->onCommand('nuke', [ManageUsersNexus::class, 'systemWipe']);
        });
    }
}
```

### Example 03 — The "Reverse Router" (Unbreakable Architecture)
* **Concept:** Prevent hardcoding `callback_data`. Name your routes and resolve them dynamically, keeping buttons safe from regex changes.
* **Slogan:** 🗺️ Route Cartographer

```php
// 📂 File: app/Nexus/ECommerce/ProductNexus.php
namespace App\Nexus\ECommerce;

use KrubiK\Attributes\Name;
use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;
use KrubiK\Keyboard\PowerButton;
use KrubiK\Keyboard\Keyboard;

class ProductNexus {
    #[Name('shop.item.view')]
    #[OnCommand('item {sku}')]
    public function viewProduct(Krubot $bot, string $sku): void {
        $bot->reply("📦 Fetching SKU: {$sku}...")->send();
    }

    #[OnCommand('catalog')]
    public function showCatalog(Krubot $bot): void {
        $cmdMacbook = $bot->resolvePattern('shop.item.view', ['sku' => 'MAC-M3']);
        $bot->reply("🛍️ Select product:")
            ->attachKeyboard(fn(Keyboard $kb) => $kb
                ->row(fn($r) => $r->add(PowerButton::simple('btn1', '💻 Mac')->action($cmdMacbook)))
            )->send();
    }
}
```

### Example 04 — The "Deep Link Portal" (RegEx Parameter Injection)
* **Concept:** Capturing parameters directly from deep links (e.g., `t.me/bot?start=ref_998`) and starting a conversation with injected data.
* **Slogan:** 🕳️ Wormhole Mastery

```php
// 📂 File: app/Nexus/Marketing/ReferralNexus.php
namespace App\Nexus\Marketing;

use KrubiK\Attributes\OnRegEx;
use KrubiK\Krubot;
use App\Conversations\OnboardingConversation;

class ReferralNexus {
    #[OnRegEx('/^\/start ref_(\d+)$/')]
    public function handleDeepLink(Krubot $bot, array $matches) {
        $referrerId = $matches[1];
        $bot->reply("🎉 Invited by User #{$referrerId}!")->send();
        $bot->startConversation(new OnboardingConversation($referrerId));
    }
}
```

### Example 05 — The "Magic Method Waltz" (Dynamic Private Routing)
* **Concept:** Why write routes manually? Extract private methods starting with `_action` via Reflection and register them dynamically.
* **Slogan:** 💃🕺 The Phantom Dancer

```php
// 📂 File: app/Nexus/Core/DynamicRouterNexus.php
namespace App\Nexus\Core;

use KrubiK\Krubot;

class DynamicRouterNexus {
    public function bootHiddenSpells(Krubot $bot): void {
        $methods = $bot->spyOn($this)->getPrivateMethods();
        foreach ($methods as $method) {
            if (str_starts_with($method->name, '_action')) {
                $commandName = str_replace('_action', '', $method->name);
                $bot->onCommand(strtolower($commandName), [$this, $method->name]);
            }
        }
    }

    private function _actionNuke(Krubot $bot): void {
        $bot->reply("🚀 Tactical Nuke Deployed.")->send();
    }
}
```

---

## 🛡️ BATCH 2: CONVERSATIONS, GUARDS & RULES

### Example 06 — The "Closure Gatekeeper" (Inline Validation)
* **Concept:** Sometimes Laravel rules aren't enough. Pass a Closure right into the `ask()` method for inline database/API checks.
* **Slogan:** ⛩️ The Spartan Shield

```php
// 📂 File: app/Conversations/VaultConversation.php
namespace App\Conversations;

use KrubiK\Conversations\Conversation;
use KrubiK\Conversations\Answer;

class VaultConversation extends Conversation {
    public function start() {
        $this->ask("🔐 Master Password:", 'openVault', function(Answer $ans) {
            return $ans->getText() === 'DOOM_2026' ? true : '⛔ Access Denied.'; 
        });
    }

    public function openVault(Answer $ans) {
        $this->bot->reply("🔓 Welcome to the Core.")->send();
        $this->end();
    }
}
```

### Example 07 — The "Attribute Sniper" (Routing by Rules)
* **Concept:** Bind Laravel validation directly to the method using PHP 8 Attributes.
* **Slogan:** 🎯 Headshot

```php
// 📂 File: app/Conversations/SecuritySetupConv.php
namespace App\Conversations;

use KrubiK\Conversations\Conversation;
use KrubiK\Conversations\Answer;
use KrubiK\Attributes\Rule;

class SecuritySetupConv extends Conversation {
    public function start() {
        $this->ask("🔑 Set your 4-digit PIN:", 'savePin');
    }

    #[Rule('required|numeric|digits:4')]
    public function savePin(Answer $ans) {
        $pin = $ans->getValue();
        $this->bot->reply("✅ PIN securely saved: {$pin}")->send();
        $this->end();
    }
}
```

### Example 08 — The "Rule Stacker Object" (God-Tier Validation)
* **Concept:** Merging Laravel rules at the Class level AND Method level, even injecting custom Rule objects natively.
* **Slogan:** 🥞 The Stack Master

```php
// 📂 File: app/Conversations/SecureAccountConv.php
namespace App\Conversations;

use KrubiK\Conversations\Conversation;
use KrubiK\Conversations\Answer;
use KrubiK\Attributes\Rule;
use App\Rules\StrongPasswordRule; 

#[Rule('required')] 
class SecureAccountConv extends Conversation {
    public function start(): void {
        $this->ask("Create a username:", 'saveUsername');
    }

    #[Rule('string|min:3')]
    public function saveUsername(Answer $ans): void {
        $this->ask("Set a password:", 'savePassword');
    }

    #[Rule('string|min:8')]
    #[Rule(new StrongPasswordRule())] 
    public function savePassword(Answer $ans): void {
        $this->bot->reply("✅ Profile created!")->send();
        $this->end();
    }
}
```

### Example 09 — The "Global Shield" (Class-Level Attribute Rules)
* **Concept:** Applying validation rules to the entire Conversation class while ensuring subclass rules merge seamlessly.
* **Slogan:** 🛡️ The Paladin's Aura

```php
// 📂 File: app/Conversations/ClassifiedVaultConv.php
namespace App\Conversations;

use KrubiK\Conversations\Conversation;
use KrubiK\Conversations\Answer;
use KrubiK\Attributes\Rule;
use App\Rules\StrictClearanceLevel;

#[Rule('required|string')] 
class ClassifiedVaultConv extends Conversation {
    public function start(): void {
        $this->ask("👁️ Agent ID:", 'verifyAgent');
    }

    #[Rule('min:5')]
    #[Rule(new StrictClearanceLevel(5))]
    public function verifyAgent(Answer $ans): void {
        $this->bot->reply("🔓 Access Granted.")->send();
        $this->end();
    }
}
```

### Example 10 — The "Anti-Button Cheat Engine" (Input Type Enforcement)
* **Concept:** Preventing users from clicking old inline buttons from chat history when they are supposed to type a unique code manually.
* **Slogan:** 👮 The Warden

```php
// 📂 File: app/Conversations/StrictQuiz.php
namespace App\Conversations;

use KrubiK\Conversations\Conversation;
use KrubiK\Conversations\Answer;

class StrictQuiz extends Conversation {
    public function start(): void {
        $this->ask("🧠 Type 'KRUBIK' backwards:", 'checkAnswer');
    }

    public function checkAnswer(Answer $ans): void {
        if ($ans->isInteractiveMessageReply()) {
            $this->bot->reply("⛔️ Cheating Detected! TYPE the answer, do not click.")->send();
            return $this->repeat();
        }
        $this->end();
    }
}
```

### Example 11 — The "Persistent Translator" (Infinite Loops)
* **Concept:** A tool that stays open forever using `persistForever()` until explicitly exited.
* **Slogan:** ♾️ Dr. Strange Loop

```php
// 📂 File: app/Conversations/TranslatorTool.php
namespace App\Conversations;

use KrubiK\Conversations\Conversation;
use KrubiK\Conversations\Answer;

class TranslatorTool extends Conversation {
    public function start(): void {
        $this->persistForever(); 
        $this->ask('🌍 Send text to translate (Type /exit to leave):', 'translate');
    }

    public function translate(Answer $ans): void {
        if ($ans->getText() === '/exit') {
            $this->bot->reply('Terminal closed.')->send();
            return $this->end(); 
        }
        $this->bot->reply("Translation: " . strrev($ans->getText()))->send();
        $this->repeat(); 
    }
}
```

### Example 12 — The "Amnesia Bypass" (Skip & Disable Stop Patterns)
* **Concept:** Give users the ability to skip optional questions natively, but temporarily revoke their right to cancel when they reach a critical step.
* **Slogan:** 🧠 Mind Controller

```php
// 📂 File: app/Conversations/InterrogationConv.php
namespace App\Conversations;

use KrubiK\Conversations\Conversation;
use KrubiK\Conversations\Answer;

class InterrogationConv extends Conversation {
    public function start(): void {
        $this->setStopPatterns(['cancel']);
        $this->setSkipPatterns(['skip', '-']);
        $this->ask("🩸 Last words? (Type 'skip' to remain silent):", 'verdict');
    }

    public function verdict(Answer $ans): void {
        // Disable the escape route! They can no longer type 'cancel' to exit.
        $this->disableStopPatterns('cancel');
        $this->ask("⚖️ Ready for execution? (Yes/No):", 'execute');
    }
    
    public function execute(Answer $ans) { $this->end(); }
}
```

### Example 13 — The "Skip Pattern Detective"
* **Concept:** Making form fields optional by allowing users to send a specific word like "-" to bypass validators safely.
* **Slogan:** ⏭️ The Smooth Operator

```php
// 📂 File: app/Conversations/ProfileSetup.php
namespace App\Conversations;

use KrubiK\Conversations\Conversation;
use KrubiK\Conversations\Answer;

class ProfileSetup extends Conversation {
    public function start(): void {
        $this->setSkipPatterns(['skip', '-', 'none']);
        $this->ask('🌐 Enter URL (or type "-" to skip):', 'saveWeb', 'url');
    }

    public function saveWeb(Answer $ans): void {
        if (in_array($ans->getText(), ['skip', '-', 'none'])) {
            $this->bot->reply("Website skipped.")->send();
        }
        $this->end();
    }
}
```

---

## 📱 BATCH 3: NATIVE FORMS & OS UI

### Example 14 — The "Form Builder Wizard" (Zero-Boilerplate Collection)
* **Concept:** Collecting data used to require complex state machines. Now? Just chain it like a Laravel query builder.
* **Slogan:** 🧙‍♂️ Grand Wizard

```php
// 📂 File: app/Nexus/Features/JobNexus.php
namespace App\Nexus\Features;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;
use KrubiK\Keyboard\PowerButton;

class JobNexus {
    #[OnCommand('apply')]
    public function startApp(Krubot $bot) {
        $bot->form('job_app_form')
            ->field('fullname', '👤 Enter your full name:')->rules('required|min:3')
            ->field('birth_year', PowerButton::calendar('b_year', '📅 Select Birth Year', PowerButton::PersianCalendar))
            ->field('mobile', '📱 Enter mobile:')->rules('required|regex:/^09\d{9}$/')
            ->then(function(array $data, Krubot $bot) {
                $bot->reply("🎉 Application received, {$data['fullname']}!")->send();
            })
            ->run();
    }
}
```

### Example 15 — The "Native Form Architect" (OS-Level Inputs)
* **Concept:** A dedicated Form class that requests Native Calendars, Selection grids, and Number Pickers without any manual parsing.
* **Slogan:** 📱 Native Wizardry

```php
// 📂 File: app/Forms/CarRentalForm.php
namespace App\Forms;

use KrubiK\Conversations\Form;
use KrubiK\Keyboard\PowerButton;

class CarRentalForm extends Form {
    protected function setup(): void {
        $this->setName('premium_car_rental');
        $this->field('car_type', PowerButton::selection('c_type', '🚗 Select Class', [
            ['id' => 'suv', 'text' => 'SUV'],
            ['id' => 'sedan', 'text' => 'Luxury Sedan']
        ], multi: false, columns: 2));
        
        $this->field('days', PowerButton::numberPicker('days', '⏳ Rental Days', min: 1, max: 30));
        $this->field('start_date', PowerButton::calendar('cal', '📅 Pickup Date', PowerButton::GregorianCalendar));
    }

    protected function submit(array $data): void {
        $this->bot->reply("✅ Booking Confirmed for {$data['start_date']}")->send();
    }
}
```

### Example 16 — The "Dynamic Form Architect" (DB-Driven Survey)
* **Concept:** When you don't know the questions in advance. Pull them from a DB array and build the form recursively on the fly.
* **Slogan:** 🧬 DNA Weaver

```php
// 📂 File: app/Conversations/DynamicSurvey.php
namespace App\Conversations;

use KrubiK\Conversations\Conversation;
use KrubiK\Conversations\Answer;

class DynamicSurvey extends Conversation {
    public array $questions = ['Your Age?', 'Your Job?', 'Your Hobby?'];
    public int $currentIndex = 0;
    public array $answers = [];

    public function start(): void { $this->askNext(); }

    public function askNext(): void {
        if (!isset($this->questions[$this->currentIndex])) return $this->finish();
        $this->ask($this->questions[$this->currentIndex], 'handleAnswer');
    }

    public function handleAnswer(Answer $ans): void {
        $this->answers[$this->currentIndex] = $ans->getText();
        $this->currentIndex++;
        $this->askNext(); 
    }

    public function finish(): void {
        $this->bot->reply("✅ Survey complete: " . implode(", ", $this->answers))->send();
        $this->end();
    }
}
```

### Example 17 — The "Form Cancellation Guardian"
* **Concept:** Allowing users to type "cancel" at ANY step of a long form without writing repetitive if-statements.
* **Slogan:** 🛡️ Global Guardian

```php
// 📂 File: app/Forms/ShippingForm.php
namespace App\Forms;

use KrubiK\Conversations\Form;
use KrubiK\Conversations\Answer;

class ShippingForm extends Form {
    protected function setup(): void {
        $this->setStopPatterns(['cancel', 'exit']);
        $this->setName('global_shipping');
        $this->field('city', 'Enter your city:');
    }

    public function processAnswer(Answer $answer): void {
        if ($this->checkStopPattern('cancel') && in_array(strtolower($answer->getText()), ['cancel'])) {
            $this->bot->reply('❌ Shipping process aborted.')->send();
            return $this->end();
        }
        parent::processAnswer($answer);
    }

    protected function submit(array $data): void {}
}
```

### Example 18 — The "Object-Oriented Question Builder"
* **Concept:** String-based menus are prone to errors. Use DTOs to build complex, unbreakable UIs.
* **Slogan:** 📐 Master Architect

```php
// 📂 File: app/Conversations/PollConv.php
namespace App\Conversations;

use KrubiK\Conversations\Conversation;
use KrubiK\Conversations\Question;
use KrubiK\Conversations\ConversationButton;

class PollConv extends Conversation {
    public function start(): void {
        $q = Question::create("🎨 Soul color?")
            ->addButton(ConversationButton::create("🔴 Red")->value('red'))
            ->addButton(ConversationButton::create("🔵 Blue")->value('blue'));
        $this->ask($q, 'handleSoul');
    }
    public function handleSoul($ans) { $this->end(); }
}
```

### Example 19 — The "Interactive Cart Matrix" (Stateful Inline Mutation)
* **Concept:** A shopping cart where clicking a button removes the item from state, and effortlessly updates the UI in place without new messages.
* **Slogan:** 🛒 Seamless Mutation

```php
// 📂 File: app/Conversations/LiveCartMenu.php
namespace App\Conversations;

use KrubiK\Conversations\InlineMenu;

class LiveCartMenu extends InlineMenu {
    public array $cart = ['ITM1' => 'Dark Matter', 'ITM2' => 'Plasma Core'];

    public function menu(): array {
        $this->menuText("🛒 Your Quantum Cart:");
        $this->clearButtons();
        foreach ($this->cart as $id => $name) {
            $this->addButtonRow(['text' => "❌ Drop: {$name}", 'method' => 'dropItem', 'data' => $id]);
        }
        return $this->menuRows;
    }

    public function dropItem(): void {
        $targetId = $this->callbackQuery['data'];
        unset($this->cart[$targetId]); 
        $this->showMenu(); // Instantly re-renders the UI
    }
}
```

### Example 20 — The "Native WebApp & Geo"
* **Concept:** Launching HTML5 mini-apps and requesting OS-level GPS natively via ReplyKeyboard.
* **Slogan:** 📱 Native Wizard

```php
// 📂 File: app/Nexus/Features/ServiceNexus.php
namespace App\Nexus\Features;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;
use KrubiK\Keyboard\ReplyKeyboard;
use KrubiK\Keyboard\PowerButton;

class ServiceNexus {
    #[OnCommand('services')]
    public function launchUI(Krubot $bot): void {
        $rk = ReplyKeyboard::make()->row_v2([
            PowerButton::make('🛒 Open Mini-Store')->webApp('https://shop.krubik.io'),
            PowerButton::make('📍 Share Precise Location')->requestLocation()
        ]);
        $bot->reply("Service Hub:")->replyKeyboard($rk)->send();
    }
}
```

### Example 21 — The "Native Grid Warlord" (Smart Width Engine)
* **Concept:** Mixing 100%, 50%, and 33% widths natively in one fluid UI matrix.
* **Slogan:** 📐 The Sacred Geometry

```php
// 📂 File: app/Nexus/UI/DashboardNexus.php
namespace App\Nexus\UI;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;
use KrubiK\Keyboard\Keyboard;

class DashboardNexus {
    #[OnCommand('app')]
    public function render(Krubot $bot) {
        $ui = Keyboard::make()->rightToLeft()
            ->button('🔥 Sale')->action('promo')->width(1.0) 
            ->button('👕 Men')->action('men')->width(0.5)     
            ->button('👗 Women')->action('women')->width(0.5) 
            ->button('🥇 VIP')->action('vip')->width(0.33)    
            ->button('🎁 Gift')->action('gift')->width(0.33)  
            ->button('⚙️ Panel')->action('panel')->width(0.34); 
            
        $bot->reply("📱 Smart Dashboard:")->attachKeyboard($ui)->send();
    }
}
```

---
## 💾 BATCH 4: STATE, STORAGE & MEMORY

### Example 22 — The "Omni-Present Memory" (Storage Abstraction)
* **Concept:** Stop writing Eloquent queries for settings. Storage layers handle persistence automatically across Redis/DB.
* **Slogan:** 💾 Elephant Brain

```php
// 📂 File: app/Nexus/Features/SettingsNexus.php
namespace App\Nexus\Features;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;

class SettingsNexus {
    #[OnCommand('theme_dark')]
    public function setDark(Krubot $bot) {
        // Isolated to current user, persists forever
        $bot->userStorage()->put('theme', 'dark');
        $bot->reply("🌙 UI is now Dark.")->send();
    }

    #[OnCommand('status')]
    public function checkStatus(Krubot $bot) {
        $theme = $bot->userStorage()->get('theme', 'light');
        
        // Shared across the entire universe (all users/chats)
        $hits = $bot->globalStorage()->get('system_hits', 0) + 1;
        $bot->globalStorage()->put('system_hits', $hits);

        $bot->reply("Theme: {$theme}\nTotal Hits: {$hits}")->send();
    }
}
```

### Example 23 — The "Cross-Verse Memory" (Context Shifting)
* **Concept:** Read a user's RPG stats from Telegram while they are currently interacting with the bot on Rubika.
* **Slogan:** 🌌 Dimension Hacker

```php
// 📂 File: app/Nexus/RPG/SyncNexus.php
namespace App\Nexus\RPG;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;

class SyncNexus {
    #[OnCommand('sync_xp')]
    public function syncExperience(Krubot $bot): void {
        $rubikaStore = $bot->userStorage(); 
        
        // Point the storage engine to look at Telegram's bucket
        $bot->setWorkingVerse('tg');
        $tgXP = $bot->userStorage()->get('xp', 0);
        
        // Snap back to the current reality (Rubika)
        $bot->setWorkingVerse(null);

        $rubikaStore->put('xp', $tgXP);
        $bot->reply("✅ Cross-Verse Sync Complete! EXP: {$tgXP}")->send();
    }
}
```

### Example 24 — The "Dimensional Ward" (Contextual Isolation)
* **Concept:** A user's strike-count should only exist inside a specific Group Chat, and reset if they go to another group.
* **Slogan:** 🏘️ The Realm Keeper

```php
// 📂 File: app/Nexus/Moderation/WarnNexus.php
namespace App\Nexus\Moderation;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;

class WarnNexus {
    #[OnCommand('strike')]
    public function warnUser(Krubot $bot): void {
        if (!$bot->isReply()) return;

        // Contextual Storage: Bound to UserID + ChatID simultaneously
        $context = $bot->contextStorage(); 
        $strikes = $context->get('strikes', 0) + 1;
        
        if ($strikes >= 3) {
            $bot->banChatMember($bot->chatId(), $bot->getReplySenderId())->send();
            $context->forget('strikes'); // Erase history for this specific realm
            $bot->reply("🔨 Entity eradicated from this realm.")->send();
        } else {
            $context->put('strikes', $strikes);
            $bot->reply("⚠️ Warning #{$strikes}/3 issued.")->send();
        }
    }
}
```

### Example 25 — The "Chrono-Lock Cache" (Global TTL)
* **Concept:** Caching expensive API data globally for exactly 60 seconds without hitting the database.
* **Slogan:** ⌛ Time Lord

```php
// 📂 File: app/Nexus/Finance/EconomyNexus.php
namespace App\Nexus\Finance;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;

class EconomyNexus {
    #[OnCommand('price')]
    public function fetchPrice(Krubot $bot): void {
        $globalStore = $bot->globalStorage();
        $price = $globalStore->get('gold_price');

        if (!$price) {
            $price = 45000000; // Simulated API Call
            // Stores the data and destroys it after 60 seconds automatically
            $globalStore->setTTL(60)->put('gold_price', $price);
        }

        $bot->reply("🪙 Gold Price: {$price} IRR")->send();
    }
}
```

---

## 👁️ BATCH 5: AMETHYST TELEMETRY & THE VAULT

### Example 26 — The "Oracle's Gaze" (Deep Inspection)
* **Concept:** Stop using `dd()` which breaks the bot's response lifecycle. Let Amethyst gaze into complex payloads cleanly.
* **Slogan:** 👁️ All-Seeing Eye

```php
// 📂 File: app/Nexus/Dev/InspectionNexus.php
namespace App\Nexus\Dev;

use KrubiK\Attributes\OnCommand;
use KrubiK\Helpers\AmethystMatrix;
use KrubiK\Krubot;

class InspectionNexus {
    #[OnCommand('inspect_payload')]
    public function inspect(Krubot $bot): void {
        $payload = $bot->getUpdate();
        
        // Captures complex arrays/objects without killing the PHP process
        // Automatically injects UserID and ChatID into the Warlord context
        AmethystMatrix::gaze($payload, 'Incoming Webhook Payload');

        $bot->reply("✅ Payload inspected by the Oracle.")->send();
    }
}
```

### Example 27 — The "Time-Bending Vault" (Flash Amnesia)
* **Concept:** Storing a temporary state (like an OTP) that self-destructs after a specific time. Use `pull()` to fetch AND destroy it simultaneously.
* **Slogan:** 🧠 Quantum Memory

```php
// 📂 File: app/Nexus/Auth/FastLoginNexus.php
namespace App\Nexus\Auth;

use KrubiK\Attributes\OnCommand;
use KrubiK\Helpers\AmethystMatrix;
use KrubiK\Krubot;

class FastLoginNexus {
    #[OnCommand('get_code')]
    public function generateFlashCode(Krubot $bot): void {
        $secret = rand(1000, 9999);
        AmethystMatrix::vault("otp_{$bot->userId()}", $secret, 60);
        $bot->reply("🔑 OTP: {$secret}\nValid for 60 seconds.")->send();
    }

    #[OnCommand('verify {code}')]
    public function verifyCode(Krubot $bot, string $code): void {
        // Fetches the data AND destroys it from the vault in one atomic move
        $savedCode = AmethystMatrix::pullData("otp_{$bot->userId()}");

        if ((string)$savedCode === $code) {
            $bot->reply("🔓 Access Granted.")->send();
        } else {
            $bot->reply("⛔️ Code expired or invalid.")->send();
        }
    }
}
```

### Example 28 — The "Silent Whisper" (Granular Tracing)
* **Concept:** Logging execution steps for debugging complex logic (like RPG combat) without cluttering the main log.
* **Slogan:** 🥷 Shadow Tracer

```php
// 📂 File: app/Nexus/Game/CombatNexus.php
namespace App\Nexus\Game;

use KrubiK\Attributes\OnCommand;
use KrubiK\Helpers\AmethystMatrix;
use KrubiK\Krubot;

class CombatNexus {
    #[OnCommand('attack')]
    public function executeStrike(Krubot $bot): void {
        $stats = ['str' => 15, 'luck' => 5];
        AmethystMatrix::whisper('Combat Init', $stats);

        $damage = $stats['str'] * rand(1, $stats['luck']);
        AmethystMatrix::whisper('Damage Calculated', ['dmg' => $damage]);

        $bot->reply("⚔️ You inflicted {$damage} damage!")->send();
    }
}
```

### Example 29 — The "Apocalyptic Wail" (Crash Telepathy)
* **Concept:** When a critical failure occurs, Amethyst `wails()`, logging as EMERGENCY AND telepathically alerting Admins natively.
* **Slogan:** 🚨 Defcon 1

```php
// 📂 File: app/Nexus/Finance/CoreBankingNexus.php
namespace App\Nexus\Finance;

use KrubiK\Attributes\OnCommand;
use KrubiK\Helpers\AmethystMatrix;
use KrubiK\Krubot;

class CoreBankingNexus {
    #[OnCommand('transfer')]
    public function transferMoney(Krubot $bot): void {
        try {
            throw new \Exception("DB Node Down.");
        } catch (\Exception $e) {
            // Logs error, extracts trace, and pings the Admin directly!
            AmethystMatrix::wail('CORE BANKING COLLAPSE!', $e);
            $bot->reply("⚠️ Banking grid anomaly. Engineers notified.")->send();
        }
    }
}
```

### Example 30 — The "Global Oracle Handlers" (Void Catchers)
* **Concept:** Trap any unhandled text, and catch ANY `Exception` globally across the entire Conversation engine.
* **Slogan:** 🪝 The God Catcher

```php
// 📂 File: app/Providers/KrubotCoreServiceProvider.php
namespace App\Providers;

use KrubiK\Krubot;
use KrubiK\Helpers\AmethystMatrix;
use Illuminate\Support\ServiceProvider;

class KrubotCoreServiceProvider extends ServiceProvider {
    public function boot(Krubot $bot): void {
        // Global 404 Handler
        $bot->missing(function(Krubot $bot) {
            AmethystMatrix::prophesy("Voice in the void.", ['input' => $bot->text()]);
            $bot->reply("🌀 Command not recognized.")->send();
        });
    }
}
```

---

## ⚔️ BATCH 6: WARLORDS & MULTI-VERSE COMMANDS

### Example 31 — The "Warlord's Council Report" (Multi-Verse Strike)
* **Concept:** Command Telegram, Rubika, and Bale simultaneously. Assemble a War Council and analyze the casualties.
* **Slogan:** 👑 Emperor Warlord

```php
// 📂 File: app/Nexus/Admin/WarlordNexus.php
namespace App\Nexus\Admin;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;
use KrubiK\Helpers\AmethystMatrix;

class WarlordNexus {
    #[OnCommand('nuke_all')]
    public function launchGlobalStrike(Krubot $bot): void {
        // Assemble the Council natively
        $council = $bot->assembleCouncil(['tg', 'r', 'bale']);
        
        $report = $council->broadcast('sendMessage', [
            'chat_id' => config('marketing.global_channel'),
            'text'    => "🔥 V2.0 Core Upgrade is LIVE!"
        ])->getReport();

        if ($council->hasFailures()) {
            $failed = implode(', ', $council->getFailedAliases());
            $bot->reply("⚠️ Partial failure on nodes: {$failed}")->send();
        } else {
            AmethystMatrix::gaze($report, 'Global Strike Successful');
            $bot->reply("✅ Absolute victory across all dimensions.")->send();
        }
    }
}
```

### Example 32 — The "Omni-Present Warlord" (External Job Injection)
* **Concept:** Inside an isolated Laravel Queue Job? You don't have `$bot`. Use the `warlord()` helper to materialize it anywhere.
* **Slogan:** 🌍 God of Space

```php
// 📂 File: app/Jobs/ProcessCryptoTransaction.php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessCryptoTransaction implements ShouldQueue {
    use Queueable;
    public function __construct(public string $userId, public string $txHash) {}

    public function handle(): void {
        sleep(2); // Heavy blockchain logic

        // Instantly materialize the bot in Rubika ('r') from the background
        warlord('r')->chat($this->userId)
                    ->message("✅ Tx Hash: `{$this->txHash}`")
                    ->send();
                    
        // Alert Telegram Admins
        warlord('tg')->chat(config('admin_id'))
                     ->message("🔒 Audit: {$this->txHash} verified.")
                     ->send();
    }
}
```

### Example 33 — The "Tactical Via Switch" (Scope-Bound Dimension Hop)
* **Concept:** Switch from Rubika to Telegram, execute some logic, and snap back automatically when the closure ends.
* **Slogan:** 🔀 Context Bending

```php
// 📂 File: app/Nexus/Media/SyncNexus.php
namespace App\Nexus\Media;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;

class SyncNexus {
    #[OnCommand('backup')]
    public function backup(Krubot $bot): void {
        $bot->reply("🔄 Initiating backup...")->send();

        // Shifts to Telegram ONLY inside this closure
        $bot->via('tg', function (Krubot $tgBot) {
            $tgBot->to(config('admin_tg'), "📥 Secure Backup Received.");
        }); // Snaps back instantly

        $bot->reply("✅ Transmitted to Telegram servers.")->send();
    }
}
```

### Example 34 — The "Prime Agent Proxy" (Native API Hijack)
* **Concept:** KrubiK abstracts the messengers. But to access raw specific methods (like Telegram's SDK), engage the Prime Agent.
* **Slogan:** 🕵️ Undercover Boss

```php
// 📂 File: app/Nexus/Media/StickerNexus.php
namespace App\Nexus\Media;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;
use KrubiK\WarLording\PrimeAgent;

class StickerNexus {
    #[OnCommand('make_sticker')]
    public function createPack(Krubot $bot): void {
        // Hijack the native SDK
        $tgAgent = PrimeAgent::engage('tg'); 

        try {
            $tgAgent->createNewStickerSet([
                'user_id' => $bot->userId(),
                'name'    => 'elite_pack_by_bot',
                'title'   => 'Elite Matrix',
                'emojis'  => '💀'
            ]);
            $bot->reply("✅ Native Telegram Pack generated!")->send();
        } catch (\Exception $e) {
            $bot->reply("❌ Native constraint triggered.")->send();
        }
    }
}
```

### Example 35 — The "Legion Commander" (Squad Strikes)
* **Concept:** Grouping drivers in the config file and commanding the entire squad via aliases.
* **Slogan:** 🪖 General Commander

```php
// 📂 File: app/Nexus/Admin/MaintenanceNexus.php
namespace App\Nexus\Admin;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;

class MaintenanceNexus {
    #[OnCommand('sys_down')]
    public function alertDowntime(Krubot $bot): void {
        // Targets 'social_platforms' array in config
        $bot->legion('social_platforms')
            ->say("🛠 System down for 5 mins for core upgrades.");

        $bot->reply("✅ Squads notified.")->send();
    }
}
```

### Example 36 — The "Divine Time Weaver" (Metaphysical Queues)
* **Concept:** Stop writing cron jobs. Schedule a message to be delivered natively at an exact future timestamp.
* **Slogan:** ⏳ Time Lord

```php
// 📂 File: app/Nexus/Features/SubscribeNexus.php
namespace App\Nexus\Features;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;
use KrubiK\Scheduler\DivineMessage;
use Carbon\Carbon;

class SubscribeNexus {
    #[OnCommand('subscribe')]
    public function optIn(Krubot $bot): void {
        $bot->reply("✅ Subscribed.")->send();

        // Schedule delivery 29 days from now natively
        DivineMessage::schedule($bot->userId())
            ->content("🌸 Reminder: Subscription expires tomorrow.")
            ->at(Carbon::now()->addDays(29))
            ->save();
    }
}
```

### Example 37 — The "Identity Shifter" (Driver Reflection)
* **Concept:** Logic adapts dynamically based on which messenger driver the user is using.
* **Slogan:** 🦎 Chameleon Adaptation

```php
// 📂 File: app/Nexus/Media/UploadNexus.php
namespace App\Nexus\Media;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;

class UploadNexus {
    #[OnCommand('upload')]
    public function startUpload(Krubot $bot): void {
        $limitMsg = match($bot->getDriverAlias()) {
            'tg' => "Telegram: 2GB Max.",
            'r'  => "Rubika: 50MB Max.",
            default => "Unknown constraints."
        };
        $bot->reply($limitMsg)->send();
    }
}
```

---

## 🧟‍♂️ BATCH 7: SYSTEM OPS & NECROMANCY

### Example 38 — The "Lazarus Protocol" (Daemon Resurrection)
* **Concept:** Checking the background daemon's life-force. If it dies, Lazarus revives it silently via Artisan CLI.
* **Slogan:** 🧟‍♂️ Necromancer

```php
// 📂 File: app/Nexus/System/HealthNexus.php
namespace App\Nexus\System;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;
use Illuminate\Support\Facades\Artisan;

class HealthNexus {
    #[OnCommand('pulse')]
    public function checkHealth(Krubot $bot): void {
        if (!cache()->has('krubik:lazarus_lock')) {
            // Silent CLI call to revive the polling daemon
            Artisan::callSilent('krubik:lazarus');
            $bot->reply("⚠️ Pulse lost. Lazarus revived the daemon.")->send();
            return;
        }
        $bot->reply("✅ Pulse is strong.")->send();
    }
}
```

### Example 39 — The "Sentinel's Hot Reload" (Runtime Scan)
* **Concept:** Uploaded a new file via FTP? Scan and integrate it LIVE via Token reflection without rebooting the system.
* **Slogan:** 🛠️ DevOps Black Magic

```php
// 📂 File: app/Nexus/Admin/DevNexus.php
namespace App\Nexus\Admin;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;

class DevNexus {
    #[OnCommand('sys:reload')]
    public function reloadSystem(Krubot $bot): void {
        // Scans the directory and registers routes on the fly
        $count = $bot->discoverAndIntegrateNexuses(app_path('Nexus/Plugins'));
        $bot->reply("✅ {$count} new routing modules integrated seamlessly.")->send();
    }
}
```

### Example 40 — The "Context Purge" (Factory Reset)
* **Concept:** Wiping all session, storage, and state data for a user in one swift strike.
* **Slogan:** 🧹 Thanos Snap

```php
// 📂 File: app/Nexus/System/AccountNexus.php
namespace App\Nexus\System;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;

class AccountNexus {
    #[OnCommand('factory_reset')]
    public function nukeData(Krubot $bot): void {
        // Destroys all state, context, and caches for this entity
        $bot->flushUserStorage();
        $bot->reply("♻️ System Purged. Memories wiped.")->send();
    }
}
```

### Example 41 — The "Phantom Surgeon" (Bypassing PHP Visibility)
* **Concept:** The driver locks you out with `protected` properties? `PhantomShell` slices through encapsulation without Reflection boilerplates.
* **Slogan:** 👻 Ghost Protocol

```php
// 📂 File: app/Nexus/Debug/HackNexus.php
namespace App\Nexus\Debug;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;

class HackNexus {
    #[OnCommand('extract_token')]
    public function breachCore(Krubot $bot): void {
        $driver = $bot->getDriver(); 
        
        // Wrap and hack
        $phantom = phantomshell($driver);

        // Accessing a PROTECTED property directly
        $secretToken = $phantom->token;
        $bot->reply("🔓 Core Breached. Token: {$secretToken}")->send();
    }
}
```

---

## 🧮 BATCH 8: CONTEXTUAL MATH, UI & DTOs

### Example 42 — The "Query String Injector" (Data Tunneling)
* **Concept:** Stop tracking inline button clicks manually. Pass data like a URL Query String (`action=pay&amt=10`), and let KrubiK inject it.
* **Slogan:** 💉 Injector Supreme

```php
// 📂 File: app/Conversations/QuickPay.php
namespace App\Conversations;

use KrubiK\Conversations\Conversation;
use KrubiK\Conversations\Answer;
use KrubiK\Krubot;

class QuickPay extends Conversation {
    public function start(Krubot $bot): void {
        $bot->reply('💳 Recharge amount:')
            ->inlineKeypad([
                [['text' => '$100', 'callback_data' => 'action=pay&amt=100']] // use http_build_query([]) if its complex
            ])->send();
    }

    // Auto-triggered because action=pay
    public function pay(Krubot $bot, Answer $answer): void {
        $amount = $this->data->get('amt');
        $bot->reply("✅ Recharge of \${$amount} successful.")->editMessage();
        $this->end();
    }
}
```

### Example 43 — The "Visual Reporter" (Evidence Collection)
* **Concept:** A Form that strictly requests the Native Camera (no gallery photos allowed) and Native Gallery Video.
* **Slogan:** 📸 The Paparazzi

```php
// 📂 File: app/Forms/AccidentForm.php
namespace App\Forms;

use KrubiK\Conversations\Form;
use KrubiK\Keyboard\PowerButton;

class AccidentForm extends Form {
    protected function setup(): void {
        $this->setName('claim');
        // Forces OS to open LIVE camera
        $this->field('live', PowerButton::cameraImage('cam', '📷 Live Damage Photo'));
        $this->field('vid', PowerButton::galleryVideo('vid', '🎥 Upload Dashcam'));
    }
    protected function submit(array $data): void {}
}
```

### Example 44 — The "Geo-Spatial Sentinel" (Location Extraction)
* **Concept:** Ensure employees are at the office using GPS coordinates provided by the native location button DTO.
* **Slogan:** 🌍 The Navigator

```php
// 📂 File: app/Conversations/AttendanceConv.php
namespace App\Conversations;

use KrubiK\Conversations\Conversation;
use KrubiK\Conversations\Answer;
use KrubiK\Keyboard\PowerButton;
use KrubiK\Keyboard\Keyboard;

class AttendanceConv extends Conversation {
    public function start(): void {
        $kb = Keyboard::make()->row([ PowerButton::make('📍 Live GPS')->requestLocation() ]);
        $this->ask("Share coordinates:", 'verify', $kb);
    }

    public function verify(Answer $ans): void {
        $location = $ans->getMessage()->getLocation(); // DTO Extraction
        if (!$location) return $this->repeat();
        
        $this->bot->reply("✅ Clock-In recorded at Lat: {$location['latitude']}")->send();
        $this->end();
    }
}
```

### Example 45 — The "Matrix Calculator" (Stateful Math)
* **Concept:** Advanced calculations that hold state across multiple inputs without needing a database for intermediate values.
* **Slogan:** 🧮 Flow Master

```php
// 📂 File: app/Conversations/BillSplitter.php
namespace App\Conversations;

use KrubiK\Conversations\Conversation;
use KrubiK\Conversations\Answer;

class BillSplitter extends Conversation {
    public float $amount = 0; // Automatically persisted state

    public function start(): void {
        $this->ask("💸 Total invoice amount:", 'askCount');
    }

    public function askCount(Answer $ans): void {
        $this->amount = (float) $ans->getValue();
        $this->ask("👥 Split between how many people?", 'calc');
    }

    public function calc(Answer $ans): void {
        $share = $this->amount / (int) $ans->getValue();
        $this->bot->reply("🍰 Each pays: \${$share}")->send();
        $this->end();
    }
}
```

### Example 46 — The "Performance Analyzer" (Animated Dice DTO)
* **Concept:** Roll a native animated dice and use KrubiK's DTO to parse the semantic result instantly.
* **Slogan:** 🎲 The Grandmaster

```php
// 📂 File: app/Nexus/Game/CasinoNexus.php
namespace App\Nexus\Game;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;
use KrubiK\GamifyDices\Types\DiceResult;
use KrubiK\Helpers\AmethystMatrix;

class CasinoNexus {
    #[OnCommand('shoot')]
    public function penaltyShootout(Krubot $bot): void {
        // Trigger the OS-level animated football dice
        $response = $bot->sendDice('⚽'); 
        
        // ⚽ DX FATALITY: Strict Typed DTO Parsing
        $result = DiceResult::fromResponse($response);
        $performanceLabel = $result->getPerformanceLabel();

        if ($result->isWin()) {
            AmethystMatrix::observe("Entity scored a goal.", ['score' => $result->value]);
            $bot->reply("🏆 GOAL!!!\nShot Quality: **{$performanceLabel}**")
                ->replyTo($result->messageId)
                ->send();
        } else {
            $bot->reply("❌ You missed.\nShot Quality: {$performanceLabel}")->send();
        }
    }
}
```

---

## ⛓️ BATCH 9: ASYNC PROMISES & CONDITIONAL UIs

### Example 47 — The "Omni-Present Promise" (Async Flow Control)
* **Concept:** The true JS Promise style in PHP. If step 1 fails, step 2 never executes. If it succeeds, the execution flows down the chain effortlessly.
* **Slogan:** ⛓️ Unbreakable Chain

```php
// 📂 File: app/Nexus/Shop/InvoiceNexus.php
namespace App\Nexus\Shop;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;
use KrubiK\Helpers\AmethystMatrix;

class InvoiceNexus {
    #[OnCommand('buy')]
    public function buyProduct(Krubot $bot): void {
        // 💀 DX FATALITY: The CommandOutcome Promise Chain
        $bot->reply("⏳ Generating highly secure banking tunnel...")
            ->send()
            ->then(function ($messageId) use ($bot) {
                // Executes ONLY if the previous message was delivered successfully
                sleep(1); 
                return $bot->reply("✅ Invoice generated. Reference: {$messageId}");
            })
            ->catch(function (\Throwable $e) use ($bot) {
                AmethystMatrix::wail("Payment Chain Crashed", $e);
                $bot->reply("❌ Banking network unreachable. Aborting.")->send();
            });
    }
}
```

### Example 48 — The "State-Driven Grid" (Conditional Keyboard Logic)
* **Concept:** Rendering UI elements based on dynamic runtime states using `$keyboard->when()`, entirely eliminating messy `if/else` blocks.
* **Slogan:** 🎨 UI Picasso

```php
// 📂 File: app/Conversations/SmartCartConv.php
namespace App\Conversations;

use KrubiK\Conversations\Conversation;
use KrubiK\Keyboard\Keyboard;

class SmartCartConv extends Conversation {
    public function renderCart(): void {
        $cart = $this->data->get('items', []);
        $totalCost = collect($cart)->sum('price');
        
        $ui = Keyboard::make();
        foreach($cart as $item) {
            $ui->button("❌ Drop {$item['name']}")->action('remove', ['id' => $item['id']]);
        }

        // 🎨 DX FATALITY: Conditional Attachment
        // Only attach 'Checkout' button IF the cart has items
        $ui->when(count($cart) > 0, function(Keyboard $k) use ($totalCost) {
            $k->button("💳 Checkout (\${$totalCost})")->payment('stripe_gateway');
        });

        $this->ask("🛒 Your Active Cart:", 'handleCart', $ui);
    }

    #[Action('remove')] // Its Like a Dream, But Exists!
    public function remove(string $id): void;    

}
```

---

## 🧬 BATCH 10: ADVANCED MEMORY & DATA TUNNELING

### Example 49 — The "Kamikaze Protocol" (Self-Destructing Intelligence)
* **Concept:** Send a classified message, pin it silently, wait, and then natively obliterate it using `$bot->sendDelete()`.
* **Slogan:** 💣 The Eraser

```php
// 📂 File: app/Nexus/Security/GhostNexus.php
namespace App\Nexus\Security;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;
use KrubiK\Helpers\AmethystMatrix;

class GhostNexus {
    #[OnCommand('classified')]
    public function selfDestruct(Krubot $bot): void {
        // 1. Deliver payload
        $response = $bot->reply("💀 Burn after reading. You have 10 seconds.")->send();
        $msgId = $response['result']['message_id'] ?? $response['message_id'];

        // 2. Pin silently
        $bot->pin($msgId, true)->send();

        // 3. Temporal delay
        sleep(10);

        // 💣 DX FATALITY: Targeted Annihilation
        $bot->messageId($msgId)->sendDelete();
        AmethystMatrix::observe("Classified material obliterated.");
    }
}
```

### Example 50 — The "Auto-Filler Matrix" (Pre-Hydrated Form Setup)
* **Concept:** Fetch existing data from Eloquent and pre-fill the KrubiK Form natively before the user even types anything.
* **Slogan:** 🗃️ Auto-Hydration

```php
// 📂 File: app/Nexus/User/ProfileNexus.php
namespace App\Nexus\User;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;
use App\Models\Agent; 

class ProfileNexus {
    #[OnCommand('edit_profile')]
    public function edit(Krubot $bot): void {
        $agent = Agent::find($bot->userId());
        
        // 🗃️ DX FATALITY: Dynamic Form Hydration
        $bot->form('agent_update')
             ->field('codename', "Current: {$agent->codename}\nEnter new (or '.' to keep):")
             ->then(function($data) use ($agent, $bot) {
                 if ($data['codename'] !== '.') {
                     $agent->codename = $data['codename'];
                     $agent->save();
                 }
                 $bot->reply("✅ Profile Updated in the Core.")->send();
             })->run();
    }
}
```

### Example 51 — The "Infinite Catalog" (Stateful Chain Pagination)
* **Concept:** Rendering a massive catalog without query string limits. `Chain::paginate` remembers the offset and wires navigation perfectly.
* **Slogan:** ⛓️ Infinite Architect

```php
// 📂 File: app/Conversations/StoreCatalogChain.php
namespace App\Conversations;

use KrubiK\Conversations\Chain;
use KrubiK\Krubot;
use KrubiK\Keyboard\PowerButton;

class StoreCatalogChain extends Chain {
    public function main(Krubot $bot): void {
        $weapons = range(1, 100); 
        
        // ⛓️ DX FATALITY: Automatic Stateful Pagination
        $keyboard = $this->paginate($weapons, function($itemId) {
            return PowerButton::make("⚔️ Weapon #{$itemId}")->action('inspect', ['id' => $itemId]);
        }, chunk: 2); 
        
        $this->addHomeButton($keyboard);
        $bot->reply('🛡️ Explore the Arsenal:')->inlineKeypad($keyboard->toArray())->send();
    }

    public function inspect(Krubot $bot, int $id): void {
        $this->bot->reply("🔍 Analyzing Weapon #{$id}...")
                  ->inlineKeypad([ [['text'=>'⬅️ Back', 'callback_data'=>'action=back']] ])
                  ->editMessage();
    }
}
```

### Example 52 — The "Tokenized Memory" (Form Amnesia Protocol)
* **Concept:** Validating a secure gift token via the Vault, using it to construct a dynamic Form, and destroying the memory forever.
* **Slogan:** 🎭 Master of Disguise

```php
// 📂 File: app/Nexus/Marketing/InviteNexus.php
namespace App\Nexus\Marketing;

use KrubiK\Attributes\OnCommand;
use KrubiK\Helpers\AmethystMatrix;
use KrubiK\Krubot;

class InviteNexus {
    #[OnCommand('claim {token}')]
    public function claim(Krubot $bot, string $token): void {
        // 🧠 Pull reads and obliterates the token instantly
        $rewardData = AmethystMatrix::pullData("gift_{$token}");

        if (!$rewardData) {
            $bot->reply("❌ Token is invalid or expired.")->send();
            return;
        }

        $bot->form('claim_form')
            ->field('wallet', "🎁 You won {$rewardData}!\nEnter Wallet Address:")
            ->rules('required|min:20')
            ->then(function(array $data, Krubot $bot) {
                $bot->reply("✅ Assets en route to your wallet.")->send();
            })->run();
    }
}
```

### Example 53 — The "Conditional Sentinel" (Middleware Redirection)
* **Concept:** If a user hits a restricted route, don't just block them—use `$bot->go()` to elegantly redirect them to a payment flow implicitly.
* **Slogan:** 🛡️ Adaptive Shielding

```php
// 📂 File: app/Middlewares/VipGuardMiddleware.php
namespace App\Middlewares;

use KrubiK\Krubot;
use Closure;

class VipGuardMiddleware {
    public function handle(Krubot $bot, Closure $next) {
        $isVip = $bot->userStorage()->get('is_vip', false);

        if (!$isVip) {
            $bot->reply("⛔ VIP Access Required.")->send();
            
            // 🔀 DX FATALITY: Internal Route Redirection
            // Push them directly to the payment route without user input
            $bot->go('shop.subscription.view'); 
            return; // Halt execution
        }

        return $next($bot);
    }
}
```

### Example 54 — The "Micro-Service Teleporter" (Internal Logic Execution)
* **Concept:** Teleporting execution from one controller directly into another isolated logic controller while passing arrays of payload data natively.
* **Slogan:** 🌌 Portal Gun

```php
// 📂 File: app/Nexus/Features/SystemOpsNexus.php
namespace App\Nexus\Features;

use KrubiK\Attributes\Name;
use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;

class SystemOpsNexus {
    // Target Logic (Not directly accessible via user text)
    #[Name('sys.grant.vip')]
    public function grantAccess(Krubot $bot, array $params): void {
        $months = $params['months'] ?? 1;
        $bot->reply("🎉 VIP Access granted for {$months} months.")->send();
    }

    // Trigger Logic
    #[OnCommand('mock_pay')]
    public function simulatePayment(Krubot $bot): void {
        $bot->reply("⏳ Processing payment...")->send();

        // 🚀 DX FATALITY: Internal Routing Execution
        // Teleports execution directly to another named route with a payload!
        $bot->go('sys.grant.vip', ['months' => 3]);
    }
}
```
## 🎬 BATCH 11: NATIVE MEDIA & PROTOCOL RELAYS (The Missing Links)

### Example 55 — The "Media Broadcaster" (Native File Methods)
* **Concept:** Sending rich media (Videos, Documents) fluently with captions, bypassing manual array payloads entirely.
* **Slogan:** 🎬 The Director

```php
// 📂 File: app/Nexus/Media/BroadcastNexus.php
namespace App\Nexus\Media;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;

class BroadcastNexus {
    #[OnCommand('send_intel')]
    public function dispatchFiles(Krubot $bot): void {
        $bot->reply("📡 Initiating secure file transfer...")->send();

        // 🎬 DX FATALITY: Fluent Native Media Chaining
        $bot->video('https://matrix.io/classified.mp4')
            ->caption("👁️ **Classified Intel**\nDo not share this footage.")
            ->send();

        $bot->document(storage_path('app/blueprints.pdf'))
            ->caption("📄 Core Architecture Blueprints")
            ->send();
    }
}
```

### Example 56 — The "Clean Relay" (Context Forwarding)
* **Concept:** Extracting pure text (ignoring commands) and instantly forwarding messages natively without re-fetching IDs.
* **Slogan:** 🔀 The Switchboard

```php
// 📂 File: app/Nexus/Support/RelayNexus.php
namespace App\Nexus\Support;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;

class RelayNexus {
    #[OnCommand('report')]
    public function forwardReport(Krubot $bot): void {
        // 🧹 Strips out the "/report" command, leaving only the pure text
        $purePayload = $bot->cleanText();
        
        if (empty($purePayload)) {
            return clone $bot->reply("⚠️ Please provide a reason after the command.")->send();
        }

        // 🔀 DX FATALITY: Native Forwarding
        // Forwards the EXACT message directly to the Admin channel
        $bot->forwardTo(config('krubik.admin_channel_id'));
        
        $bot->reply("✅ Report forwarded to the High Council.")->send();
    }
}
```

### Example 57 — The "Humanizer" (Chat Actions & Contacts)
* **Concept:** Simulating human behavior (typing, uploading) before sending complex DTOs like Native Contacts.
* **Slogan:** 👤 The Turing Test

```php
// 📂 File: app/Nexus/Marketing/ContactNexus.php
namespace App\Nexus\Marketing;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;

class ContactNexus {
    #[OnCommand('support_number')]
    public function shareContact(Krubot $bot): void {
        // 👤 DX FATALITY: Simulating Human Action
        $bot->chatAction('typing')->send();
        sleep(1); // Simulate human delay

        // 📇 Sending a native VCard/Contact object seamlessly
        $bot->contact('DoKtor K. Support', '+989120000000')->send();
    }
}
```

---

## 🏛️ BATCH 12: CHAT ADMINISTRATION & META-DATA

### Example 58 — The "Automated HR" (Promotions & Pins)
* **Concept:** Automatically promoting a user to Admin and pinning their welcome message using native wrapped methods.
* **Slogan:** 👔 The CEO

```php
// 📂 File: app/Nexus/Admin/HRNexus.php
namespace App\Nexus\Admin;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;

class HRNexus {
    #[OnCommand('promote_dev')]
    public function promote(Krubot $bot): void {
        if (!$bot->isReply()) return;

        $targetId = $bot->getReplySenderId();

        // 👔 DX FATALITY: Native Promotion Array
        $bot->promoteChatMember([
            'user_id' => $targetId,
            'can_manage_chat' => true,
            'can_pin_messages' => true
        ]);

        $response = $bot->reply("🎉 User {$targetId} has been promoted to Dev Council!")->send();

        // 📌 Native Pinning using the newly generated Message ID
        $bot->pinChatMessage([
            'chat_id' => $bot->chatId(),
            'message_id' => $response['message_id'],
            'disable_notification' => false
        ]);
    }
}
```

### Example 59 — The "Trace ID Extractor" (Deep Reply Traversal)
* **Concept:** Extracting metadata from nested replies using KrubiK's shortcut helpers.
* **Slogan:** 🔍 The Forensics

```php
// 📂 File: app/Nexus/Moderation/TraceNexus.php
namespace App\Nexus\Moderation;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;

class TraceNexus {
    #[OnCommand('trace')]
    public function analyzeReply(Krubot $bot): void {
        // 🔍 DX FATALITY: Semantic Identity Extractors
        $whoAmI = $bot->who(); // Alias for userId()
        $repliedMsgId = $bot->findRepliedMessageId(); // Safely extracts reply ID
        $targetMsgId = $bot->findMessageId(); // Current message ID
        
        $bot->reply(
            "🕵️ **Forensic Trace:**\n" .
            "Initiator: `{$whoAmI}`\n" .
            "Target Msg ID: `{$repliedMsgId}`\n" .
            "Command Msg ID: `{$targetMsgId}`"
        )->send();
    }
}
```

### Example 60 — The "Payload Inspector" (Message DTO)
* **Concept:** Analyzing incoming files instantly without treating the payload as a raw array.
* **Slogan:** 🔬 The Lab Tech

```php
// 📂 File: app/Nexus/Media/AnalyzerNexus.php
namespace App\Nexus\Media;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;

class AnalyzerNexus {
    #[OnCommand('analyze')]
    public function analyzeFile(Krubot $bot): void {
        // 🔬 DX FATALITY: The 'thisMessage()' DTO
        $msg = $bot->thisMessage();

        if (!$msg->isImage()) {
            return clone $bot->reply("⚠️ Please reply to an image.")->send();
        }

        $bot->reply(
            "📊 **Image Diagnostics:**\n" .
            "Type: `{$msg->getType()}`\n" .
            "Size: `{$msg->getSize()} bytes`\n" .
            "FileID: `{$msg->getFileId()}`"
        )->send();
    }
}
```

### Example 61 — The "Casino Dealer" (Native Dice Games)
* **Concept:** Checking which games the driver supports, and triggering them dynamically.
* **Slogan:** 🎰 The Pit Boss

```php
// 📂 File: app/Nexus/Gamify/DealerNexus.php
namespace App\Nexus\Gamify;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;

class DealerNexus {
    #[OnCommand('roulette')]
    public function spin(Krubot $bot): void {
        // 🎰 Check native driver capabilities
        $availableGames = $bot->getAvailableDices();

        if (!in_array('🎰', $availableGames)) {
            return clone $bot->reply("⚠️ This dimension does not support Casino games.")->send();
        }

        // 🎲 DX FATALITY: Fluent Dice Invocation
        $bot->playDiceGame("Matrix Roulette", $bot->who(), '🎰');
    }
}
```

### Example 62 — The "VIP Chat Gatekeeper" (Chat Storage & Invites)
* **Concept:** Utilizing the missing `ChatStorage` and native Invite Link generation for group management.
* **Slogan:** 🏰 The Gatekeeper

```php
// 📂 File: app/Nexus/Admin/GroupNexus.php
namespace App\Nexus\Admin;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;

class GroupNexus {
    #[OnCommand('lock_group')]
    public function lockDown(Krubot $bot): void {
        // 🏰 DX FATALITY: Chat-Level Storage
        // This state belongs to the GROUP, not the user!
        $bot->chatStorage()->put('is_locked', true);
        
        $bot->reply("🔒 Group is now locked. Only VIP links bypass this.")->send();
    }

    #[OnCommand('generate_bypass')]
    public function getLink(Krubot $bot): void {
        // 🔗 Native wrapper for Invite Links without PrimeAgent
        $link = $bot->getChatInviteLink();
        $bot->reply("🔗 Secret Bypass Portal: {$link}")->send();
    }
}
```

---

## 🔀 BATCH 13: ADVANCED STATE NAVIGATION (Chains & Menus)

### Example 63 — The "Time Skipper" (Linear Navigation)
* **Concept:** Manually jumping to the next or previous step in a Conversation without requiring user input.
* **Slogan:** 🕰️ The Time Traveler

```php
// 📂 File: app/Conversations/OnboardingConv.php
namespace App\Conversations;

use KrubiK\Conversations\Conversation;
use KrubiK\Conversations\Answer;

class OnboardingConv extends Conversation {
    public function start(): void {
        $this->ask("Are you a developer? (Yes/No)", 'checkDev');
    }

    public function checkDev(Answer $ans): void {
        if (strtolower($ans->getText()) === 'yes') {
            // 🔀 DX FATALITY: Skip directly to the advanced step!
            $this->bot->reply("Ah, a fellow architect!")->send();
            $this->next('askLanguage'); 
        } else {
            // 🔙 DX FATALITY: Rewind time to the previous step!
            $this->bot->reply("You must be a developer to enter.")->send();
            $this->previousStep(); 
        }
    }

    public function askLanguage(Answer $ans): void {
        $this->bot->reply("Welcome to the Dev Hub.")->send();
        $this->end();
    }
}
```

### Example 64 — The "Intercepted Abortion" (Closure Stop Pattern)
* **Concept:** Using `addStopPattern` with a Closure to execute cleanup logic BEFORE the conversation is aborted.
* **Slogan:** 🛑 The Interceptor

```php
// 📂 File: app/Conversations/UploadConv.php
namespace App\Conversations;

use KrubiK\Conversations\Conversation;
use KrubiK\Conversations\Answer;
use KrubiK\Helpers\AmethystMatrix;

class UploadConv extends Conversation {
    public function start(): void {
        // 🛑 DX FATALITY: Intercepted Cancellation
        $this->addStopPattern('abort', function() {
            // Cleanup logic runs before it dies
            AmethystMatrix::whisper("User aborted upload. Cleaning temp files...");
            $this->bot->reply("🧹 Upload cancelled. Temp files destroyed.")->send();
        });

        $this->ask("Send your 1GB file now (or type 'abort'):", 'processFile');
    }

    public function processFile(Answer $ans): void {
        $this->end();
    }
}
```

### Example 65 — The "Quantum Leap" (Chain Jumping)
* **Concept:** Jumping directly to a specific method inside a `Chain` from anywhere, carrying payload data.
* **Slogan:** 🦘 The Quantum Hopper

```php
// 📂 File: app/Conversations/MainMenuChain.php
namespace App\Conversations;

use KrubiK\Conversations\Chain;
use KrubiK\Krubot;
use KrubiK\Keyboard\PowerButton;

class MainMenuChain extends Chain {
    public function main(Krubot $bot): void {
        $bot->reply("Main Menu:")
            ->inlineKeypad([
                // 🦘 DX FATALITY: Triggers the jump() method natively!
                [['text' => 'Go to Settings', 'callback_data' => 'action=jump&method=settings&args[theme]=dark']]
            ])->send();
    }

    public function settings(Krubot $bot, array $args): void {
        $theme = $args['theme'] ?? 'light';
        $bot->reply("⚙️ Settings Menu (Theme: {$theme})")->editMessage();
    }
}
```

### Example 66 — The "Silent Terminator" (Inline Menu Controls)
* **Concept:** Controlling the callback popup natively and closing the menu strictly without leaving dead buttons behind.
* **Slogan:** 🤫 The Silencer

```php
// 📂 File: app/Conversations/ActionMenu.php
namespace App\Conversations;

use KrubiK\Conversations\InlineMenu;

class ActionMenu extends InlineMenu {
    public function menu(): array {
        $this->menuText("⚠️ Confirm deletion?");
        $this->addButtonRow(['text' => 'Yes, Delete', 'method' => 'confirmDelete']);
        return $this->menuRows;
    }

    public function confirmDelete(): void {
        // 🤫 DX FATALITY: Native UI Alerts
        // Shows a toast notification on the user's screen
        $this->answerCallbackQuery("🗑️ Data deleted successfully!", showAlert: true);
        
        // 💀 DX FATALITY: Destroys the inline menu completely from the chat
        $this->closeMenu();
    }
}
```

---

## 🎨 BATCH 14: THE UI MUTATORS & ATTRIBUTES

### Example 67 — The "Dynamic Grid" (Manual Chunks & Rows)
* **Concept:** Building a complex UI grid manually using `chunk()` and the callback-driven `addRowButton()`.
* **Slogan:** 🧩 The Grid Master

```php
// 📂 File: app/Nexus/UI/GridNexus.php
namespace App\Nexus\UI;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;
use KrubiK\Keyboard\Keyboard;

class GridNexus {
    #[OnCommand('grid')]
    public function showGrid(Krubot $bot): void {
        $kb = Keyboard::make();

        // 🧩 DX FATALITY: Fluent iteration and chunking
        for ($i = 1; $i <= 6; $i++) {
            $kb->button("Item {$i}");
        }
        
        // Formats the 6 buttons into rows of 3 automatically!
        $kb->chunk(3); 
        
        // Appends a completely custom row safely using a closure
        $kb->addRowButton("🔙 Return", fn() => "action=home");

        $bot->reply("🎛️ Data Grid:")->attachKeyboard($kb)->send();
    }
}
```

### Example 68 — The "Resource Hub" (Links & Files)
* **Concept:** Using the missing `PowerButton` wrappers to generate native URL links and File requests.
* **Slogan:** 🔗 The Connector

```php
// 📂 File: app/Forms/ResourceForm.php
namespace App\Forms;

use KrubiK\Conversations\Form;
use KrubiK\Keyboard\PowerButton;

class ResourceForm extends Form {
    protected function setup(): void {
        $this->setName('hub');
        
        // 🔗 DX FATALITY: URL Link Buttons & Native File Requesters
        $this->field('docs', PowerButton::link('📚 Read Docs', 'https://krubik.io'));
        $this->field('resume', PowerButton::file('upload_cv', '📄 Upload PDF Resume'));
    }

    protected function submit(array $data): void {}
}
```

### Example 69 — The "Native Profile Builder" (Text & MyLocation)
* **Concept:** Requesting the user's *own* live location and a native Text Box UI (for WebApps/Bale).
* **Slogan:** 👤 The Profiler

```php
// 📂 File: app/Forms/ProfileForm.php
namespace App\Forms;

use KrubiK\Conversations\Form;
use KrubiK\Keyboard\PowerButton;

class ProfileForm extends Form {
    protected function setup(): void {
        $this->setName('profile_setup');
        
        // 📍 DX FATALITY: Native Text Input & Location Pinger
        $this->field('bio', PowerButton::textBox('bio', '📝 Enter Bio', 'MultiLine'));
        $this->field('home', PowerButton::myLocation('home_loc', '🏠 Share Home Location'));
    }

    protected function submit(array $data): void {}
}
```

### Example 70 — The "Visual KYC" (Gallery Image Only)
* **Concept:** Strictly requesting a static image from the gallery (ignoring camera/videos).
* **Slogan:** 🖼️ The Curator

```php
// 📂 File: app/Forms/KycForm.php
namespace App\Forms;

use KrubiK\Conversations\Form;
use KrubiK\Keyboard\PowerButton;

class KycForm extends Form {
    protected function setup(): void {
        $this->setName('kyc_verification');
        
        // 🖼️ DX FATALITY: Strictly Gallery Image constraint
        $this->field('id_card', PowerButton::galleryImage('id_img', '🖼️ Upload ID from Gallery'));
    }

    protected function submit(array $data): void {}
}
```

### Example 71 — The "Exact Match Protocol" (OnText Guard)
* **Concept:** Bypassing slash commands completely. Firing logic ONLY when exact text is detected.
* **Slogan:** 🎯 The Sniper

```php
// 📂 File: app/Nexus/Hidden/EasterEggNexus.php
namespace App\Nexus\Hidden;

use KrubiK\Attributes\OnText;
use KrubiK\Krubot;

class EasterEggNexus {
    // 🎯 DX FATALITY: Exact text matching natively
    #[OnText('I am the architect')]
    public function unlockSecret(Krubot $bot): void {
        $bot->reply("👁️ The Matrix acknowledges you.")->send();
    }
}
```

### Example 72 — The "Rate Limiter" (String Middleware Attributes)
* **Concept:** Applying Laravel's core routing middlewares (like `throttle`) directly via String attributes.
* **Slogan:** 🛡️ The Bouncer

```php
// 📂 File: app/Nexus/API/PublicNexus.php
namespace App\Nexus\API;

use KrubiK\Attributes\OnCommand;
use KrubiK\Attributes\Middleware;
use KrubiK\Krubot;

class PublicNexus {
    // 🛡️ DX FATALITY: String-based Middleware Injection
    // Limits this specific command to 5 requests per 1 minute.
    #[Middleware('throttle:5,1')]
    #[OnCommand('ping')]
    public function ping(Krubot $bot): void {
        $bot->reply("🏓 Pong! Engine is running.")->send();
    }
}
```

### Example 73 — The "Manual UI Override" (InlineMenu Render)
* **Concept:** Overriding the default rendering flow of an `InlineMenu` to inject custom UI before it displays.
* **Slogan:** 🎛️ The Overrider

```php
// 📂 File: app/Conversations/CustomMenu.php
namespace App\Conversations;

use KrubiK\Conversations\InlineMenu;

class CustomMenu extends InlineMenu {
    public function menu(): array {
        $this->menuText("Choose wisely:");
        $this->addButtonRow(['text' => 'Option A', 'method' => 'optA']);
        return $this->menuRows;
    }

    public function optA(): void {
        // 🎛️ DX FATALITY: Manual UI override
        $this->renderButtons(); // Forces UI computation natively
        $this->bot->reply("You chose A!")->send();
    }
}
```

### Example 74 — The "Chat Amnesia" (Storage Forgetting)
* **Concept:** Deleting an entire chat's history/storage explicitly and checking its existence natively.
* **Slogan:** 🧹 The Janitor

```php
// 📂 File: app/Nexus/Admin/CleanNexus.php
namespace App\Nexus\Admin;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;

class CleanNexus {
    #[OnCommand('reset_chat')]
    public function clearChatMemory(Krubot $bot): void {
        $storage = $bot->chatStorage();
        
        // 🧹 DX FATALITY: Direct Storage Verification & Erasure
        if ($storage->has('chat_config')) {
            $storage->delete(); // Obliterates the entire bucket
            $bot->reply("♻️ Chat memory has been completely erased.")->send();
        } else {
            $bot->reply("⚠️ Memory is already empty.")->send();
        }
    }
}
```

### Example 75 — The "Contextual Responder" (ReplyKeyboard UI)
* **Concept:** Building a highly customized ReplyKeyboard with placeholders and One-Time usage parameters.
* **Slogan:** 🎹 The Maestro

```php
// 📂 File: app/Nexus/UI/ReplyNexus.php
namespace App\Nexus\UI;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;
use KrubiK\Keyboard\ReplyKeyboard;
use KrubiK\Keyboard\PowerButton;

class ReplyNexus {
    #[OnCommand('survey')]
    public function survey(Krubot $bot): void {
        // 🎹 DX FATALITY: ReplyKeyboard Advanced Methods
        $rk = ReplyKeyboard::make()
            ->resize()
            ->placeholder("Select an option...")
            ->oneTime() // Keyboard disappears after one tap!
            ->row([
                PowerButton::text("👍 Good"),
                PowerButton::text("👎 Bad")
            ]);

        $bot->reply("How was your experience?")->replyKeyboard($rk)->send();
    }
}
```

### Example 76 — The "Hyper-Nexus" (The God-Mode Combo)
* **Concept:** Constructing a massive, multi-dimensional security protocol that cleanses incoming input, triggers native OS upload indicators, dispatches rich media files, forwards audit trails to secure channels, and resolves dynamic routes natively on the fly.
* **Slogan:** 🌌 The Architect (God-Mode)

```php
// 📂 File: app/Nexus/Security/HyperNexus.php
namespace App\Nexus\Security;

use KrubiK\Attributes\Name;
use KrubiK\Attributes\OnCommand;
use KrubiK\Attributes\Middleware;
use KrubiK\Krubot;
use KrubiK\Helpers\AmethystMatrix;

class HyperNexus {

    /**
     * The Ultimate Hyper-Nexus Protocol
     * Combines Route Names, CleanText, Media, ChatActions, Forwarding, and Storage.
     */
    #[Name('security.hyper_strike')]
    #[OnCommand('hyper_strike {target_id}')]
    #[Middleware('auth:god_mode')]
    public function execute(Krubot $bot, string $target_id): void {
        
        // 1. Purify the environment & extract clean payload
        $reason = $bot->cleanText(); // Strips command and target_id, leaving pure reason
        $initiator = $bot->user();
        
        if (empty($reason)) {
            $bot->reply("⚠️ **Protocol Denied:** You must supply a reason for this Hyper-Strike.")
                ->markdown()
                ->send();
            return;
        }

        // 2. Telemetry tracking through the Amethyst Matrix
        AmethystMatrix::gaze([
            'target' => $target_id,
            'reason' => $reason,
            'by'     => $initiator['id']
        ], 'Hyper-Strike Protocol Initiated');

        // 3. Trigger native OS-level action (Simulate heavy processing)
        $bot->chatAction('upload_video')->send();

        // 4. Mutate realm storage context
        $realm = $bot->chatStorage();
        $realm->put('last_strike_target', $target_id);
        $realm->put('lockdown_status', true);

        // 5. Build and dispatch dynamic route patterns (Reverse Routing)
        $bypassUrl = $bot->resolvePattern('security.hyper_strike', ['target_id' => 'bypass_key']);

        // 6. Deliver rich media natively with fluent chaining
        $videoResponse = $bot->video(storage_path('app/secure/nuke_sequence.mp4'))
            ->caption(
                "🚨 **HYPER-STRIKE DEPLOYED** 🚨\n\n" .
                "🎯 **Target ID:** `{$target_id}`\n" .
                "⚖️ **Reason:** _{$reason}_\n" .
                "🔑 **Bypass Action:** `{$bypassUrl}`\n\n" .
                "System is now locked down. All nodes notified."
            )
            ->markdown()
            ->send();

        // 7. Extract message ID from DTO and forward audit trail natively
        $msgId = $videoResponse['message_id'] ?? $videoResponse['result']['message_id'];
        
        $bot->messageId($msgId)
            ->forwardTo(config('channels.security_audit'));

        // 8. Silent trace logging for the developer
        AmethystMatrix::whisper('Strike completed successfully', [
            'forwarded_msg_id' => $msgId,
            'audit_channel'    => config('channels.security_audit')
        ]);

        $bot->reply("✅ Hyper-Strike successfully logged and broadcasted.")->send();
    }
}
```

## 🧬 BATCH 16: DATA PURIFICATION & INPUT SANITIZATION (مثال‌های ۷۷ تا ۸۱)

### Example 77 — The "Data Purifier" (Persian-to-English Number Normalization)
* **Concept:** User inputs often contain Persian/Arabic numbers (e.g., `۱۲۳۴`). Purify and normalize the input inside a validator closure before letting the business logic process it.
* **Slogan:** 🧼 The Sanitizer

```php
// 📂 File: app/Conversations/PurifiedPaymentConv.php
namespace App\Conversations;

use KrubiK\Conversations\Conversation;
use KrubiK\Conversations\Answer;

class PurifiedPaymentConv extends Conversation {
    
    public function start(): void {
        $this->ask("💸 Enter payment amount (in IRR):", 'processAmount', function(Answer $ans) {
            // 🧼 DX FATALITY: Normalizing localized digits on the fly
            $purified = $this->convertPersianNumbers($ans->getText());

            if (!is_numeric($purified)) {
                return "❌ Invalid format. Please enter numbers only!";
            }

            return true;
        });
    }

    public function processAmount(Answer $ans): void {
        $amount = $this->convertPersianNumbers($ans->getText());
        $this->bot->reply("✅ Processing payment for: {$amount} IRR")->send();
        $this->end();
    }

    private function convertPersianNumbers(string $string): string {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic  = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $num = range(0, 9);
        $converted = str_replace($persian, $num, $string);
        return str_replace($arabic, $num, $converted);
    }
}
```

### Example 78 — The "Heavyweight Guard" (Strict File Size & MIME Validation)
* **Concept:** Intercepting heavy media uploads. Inspect the incoming Message DTO properties directly inside the validation closure to enforce size and MIME type limits.
* **Slogan:** 👮‍♂️ The Gatekeeper

```php
// 📂 File: app/Conversations/VideoUploadConv.php
namespace App\Conversations;

use KrubiK\Conversations\Conversation;
use KrubiK\Conversations\Answer;

class VideoUploadConv extends Conversation {
    
    public function start(): void {
        $this->ask("📹 Upload your system logs video (Max 20MB):", 'saveVideo', function(Answer $ans) {
            $msg = $ans->getMessage();

            // 👮‍♂️ DX FATALITY: Direct DTO inspection before committing to storage
            if ($msg->getType() !== 'Video') {
                return "❌ Only MP4/MKV video files are allowed!";
            }

            if ($msg->getSize() > 20 * 1024 * 1024) {
                return "❌ File too large! Keep it under 20 Megabytes.";
            }

            return true;
        });
    }

    public function saveVideo(Answer $ans): void {
        $fileId = $ans->getMessage()->getFileId();
        $this->bot->reply("✅ Video registered. File ID: `{$fileId}`")->send();
        $this->end();
    }
}
```

### Example 79 — The "Branching Labyrinth" (Conditional Step Skipping)
* **Concept:** Dynamically skipping optional steps based on previous answers, using `$this->next()` as an explicit router.
* **Slogan:** 🔀 The Pathfinder

```php
// 📂 File: app/Conversations/MilitaryOnboarding.php
namespace App\Conversations;

use KrubiK\Conversations\Conversation;
use KrubiK\Conversations\Answer;

class MilitaryOnboarding extends Conversation {
    
    public string $gender = 'Other';

    public function start(): void {
        $this->ask("👤 Enter your gender (Male/Female):", 'checkGender');
    }

    public function checkGender(Answer $ans): void {
        $this->gender = ucfirst(strtolower($ans->getText()));

        if ($this->gender === 'Male') {
            // Proceed to the next logical question
            $this->ask("🪖 Enter your conscription status:", 'saveConscription');
        } else {
            // 🔀 DX FATALITY: Skip the conscription step entirely!
            $this->next('askDegree'); 
        }
    }

    public function saveConscription(Answer $ans): void {
        $this->data->put('conscription', $ans->getText());
        $this->next('askDegree');
    }

    public function askDegree(): void {
        $this->ask("🎓 Enter your highest academic degree:", 'finalize');
    }

    public function finalize(Answer $ans): void {
        $this->bot->reply("✅ Profile compiled. Conscription: " . $this->data->get('conscription', 'Exempted'))->send();
        $this->end();
    }
}
```

### Example 80 — The "Stateful Parametric Rule" (Custom Rule Objects in Attributes)
* **Concept:** Passing custom validation rules that carry dynamic parameters (like a range of years) directly into the `#[Rule]` attribute.
* **Slogan:** 🥞 The Rule Weaver

```php
// 📂 File: app/Rules/RangeYears.php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RangeYears implements Rule {
    public function __construct(protected int $min, protected int $max) {}

    public function passes($attribute, $value): bool {
        $year = (int) $value;
        return $year >= $this->min && $year <= $this->max;
    }

    public function message(): string {
        return "The year must be strictly between {$this->min} and {$this->max}.";
    }
}

// 📂 File: app/Conversations/BirthYearConv.php
namespace App\Conversations;

use KrubiK\Conversations\Conversation;
use KrubiK\Conversations\Answer;
use KrubiK\Attributes\Rule;
use App\Rules\RangeYears;

class BirthYearConv extends Conversation {
    
    public function start(): void {
        $this->ask("📅 Enter your birth year (Solar Hijri):", 'saveYear');
    }

    // 🥞 DX FATALITY: Custom Rule instantiation directly inside the Attribute!
    #[Rule(new RangeYears(1350, 1404))]
    #[Rule('required|digits:4')]
    public function saveYear(Answer $ans): void {
        $this->bot->reply("✅ Year registered: " . $ans->getValue())->send();
        $this->end();
    }
}
```

### Example 81 — The "Multi-Select Matrix" (Native Multi-Select Selection Buttons)
* **Concept:** Build a native selection menu that supports multiple option selections, rendered in a clean grid format.
* **Slogan:** 🎛️ The Selector

```php
// 📂 File: app/Nexus/Features/SurveyNexus.php
namespace App\Nexus\Features;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;
use KrubiK\Keyboard\Keyboard;
use KrubiK\Keyboard\PowerButton;

class SurveyNexus {
    
    #[OnCommand('select_stack')]
    public function chooseStack(Krubot $bot): void {
        $languages = [
            ['id' => 'php', 'text' => 'PHP 8.2'],
            ['id' => 'js',  'text' => 'ECMA2026'],
            ['id' => 'go',  'text' => 'Golang']
        ];

        // 🎛️ DX FATALITY: Native Multi-Select Button Factory
        // Setting multi: true enables native checkboxes on compatible platforms
        $multiBtn = PowerButton::selection('fav_stack', 'Select your weapons:', $languages, multi: true, columns: 2);
        
        $kb = Keyboard::make()->row([$multiBtn]);

        $bot->reply("🛠 Choose your favorite languages:")
            ->attachKeyboard($kb)
            ->send();
    }
}
```

---

## 🎛️ BATCH 17: GRID ARCHITECTURE & RESPONSIVE UIs (مثال‌های ۸۲ تا ۸۵)

### Example 82 — The "Asymmetric Grid" (Precise Column-Span Layouts)
* **Concept:** Designing complex, multi-row keyboard layouts where buttons have explicit column spans (from 1 to 6) to occupy precise widths.
* **Slogan:** 📐 Grid Master

```php
// 📂 File: app/Nexus/UI/AsymmetricDashboard.php
namespace App\Nexus\UI;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;
use KrubiK\Keyboard\Keyboard;
use KrubiK\Keyboard\PowerButton;

class AsymmetricDashboard {
    
    #[OnCommand('dashboard')]
    public function show(Krubot $bot): void {
        $bot->reply("🎛️ **Asymmetric Control Panel**")
            ->attachKeyboard(function(Keyboard $kb) {
                
                // Row 1: Two primary actions (Each gets col(3) = 50% width)
                $kb->row(fn($row) => $row
                    ->add(PowerButton::make('💎 Buy Gems')->col(3)->action('buy_gems'))
                    ->add(PowerButton::make('💰 Wallet')->col(3)->action('wallet'))
                );

                // Row 2: Server selection (Three buttons, each col(2) = 33.3% width)
                $kb->row(fn($row) => $row
                    ->add(PowerButton::simple('srv_us', 'US 🇺🇸')->col(2))
                    ->add(PowerButton::simple('srv_eu', 'EU 🇪🇺')->col(2))
                    ->add(PowerButton::simple('srv_ir', 'IR 🇮🇷')->col(2))
                );

                // Row 3: Support button (col(6) = 100% full width)
                $kb->row(fn($row) => $row
                    ->add(PowerButton::make('🆘 Live Support')->col(6)->action('support'))
                );
            })
            ->send();
    }
}
```

### Example 83 — The "RTL Alignment" (Right-to-Left Keyboards)
* **Concept:** Aligning Persian/Arabic layouts correctly on the screen by forcing Right-to-Left rendering order.
* **Slogan:** ✍️ RTL Maestro

```php
// 📂 File: app/Nexus/UI/RtlNavigation.php
namespace App\Nexus\UI;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;
use KrubiK\Keyboard\Keyboard;

class RtlNavigation {
    
    #[OnCommand('navigate')]
    public function showRtlMenu(Krubot $bot): void {
        // ✍️ DX FATALITY: Forcing RTL direction on the button array
        $kb = Keyboard::make()->rightToLeft()
            ->button('بعدی (Next)')->action('next_page')->width(0.5)
            ->button('قبلی (Prev)')->action('prev_page')->width(0.5);

        $bot->reply("📖 Navigation (RTL Rendered):")
            ->attachKeyboard($kb)
            ->send();
    }
}
```

### Example 84 — The "Dynamic Placeholder" (ReplyKeyboard Input Prompts)
* **Concept:** Presenting a physical keyboard with an input placeholder that changes depending on the context, guiding the user on what to type.
* **Slogan:** ⌨️ Input Guide

```php
// 📂 File: app/Nexus/UI/StudyGroupNexus.php
namespace App\Nexus\UI;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;
use KrubiK\Keyboard\ReplyKeyboard;

class StudyGroupNexus {
    
    #[OnCommand('book_session')]
    public function selectTime(Krubot $bot): void {
        // ⌨️ DX FATALITY: Custom Input Placeholder
        $rk = ReplyKeyboard::make()
            ->placeholder('Choose a valid time slot below...')
            ->oneTime() // Hides itself after the first tap
            ->row(['16:00', '18:00', '20:00']);

        $bot->reply("⏰ Select your study group session time:")
            ->replyKeyboard($rk)
            ->send();
    }
}
```

### Example 85 — The "On-The-Fly Delivery" (Dynamic File Generation & Sending)
* **Concept:** Generating text files (like V2Ray configs or invoices) programmatically in memory, saving them to temporary storage, and dispatching them natively.
* **Slogan:** 💾 The Dispenser

```php
// 📂 File: app/Nexus/Features/ConfigDeliveryNexus.php
namespace App\Nexus\Features;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;
use Illuminate\Support\Facades\Storage;

class ConfigDeliveryNexus {
    
    #[OnCommand('get_config')]
    public function deliver(Krubot $bot): void {
        // Generate config string dynamically
        $configContent = "vless://" . bin2hex(random_bytes(16)) . "@matrix.io:443?security=tls";
        
        $tempPath = "temp/configs/{$bot->userId()}.txt";
        Storage::put($tempPath, $configContent);

        // 💾 DX FATALITY: Native File Dispensing
        $bot->file(storage_path("app/{$tempPath}"))
            ->caption("🚀 Your secure, dedicated configuration is ready!")
            ->send();

        // Clean up immediately
        Storage::delete($tempPath);
    }
}
```

---

## 🛡️ BATCH 18: METAPHYSICAL ARCHITECTURE & REFLECTION (مثال‌های ۸۶ تا ۹۱)

### Example 86 — The "Inherited Shield" (Class-level & Method-level Attribute Merging)
* **Concept:** Applying a global safety rule at the class level while combining it with method-specific validations seamlessly.
* **Slogan:** 🛡️ The Bastion

```php
// 📂 File: app/Conversations/AccountConv.php
namespace App\Conversations;

use KrubiK\Conversations\Conversation;
use KrubiK\Conversations\Answer;
use KrubiK\Attributes\Rule;

// 🛡️ DX FATALITY: Class-level global rule (Every step requires input)
#[Rule('required')] 
class AccountConv extends Conversation {
    
    public function start(): void {
        $this->ask("👤 Choose your codename:", 'saveUsername');
    }

    // Merges class 'required' with method 'string|min:3'
    #[Rule('string|min:3')]
    public function saveUsername(Answer $ans): void {
        $this->bot->reply("Username registered: " . $ans->getValue())->send();
        $this->end();
    }
}
```

### Example 87 — The "Manual Inspector" (Direct SDK Chat Member Query)
* **Concept:** Bypassing pre-built helpers. Query raw member permissions directly using the underlying platform SDK via `PrimeAgent`.
* **Slogan:** 🔍 Deep Inspector

```php
// 📂 File: app/Nexus/Admin/DirectSecurityNexus.php
namespace App\Nexus\Admin;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;
use KrubiK\WarLording\PrimeAgent;

class DirectSecurityNexus {
    
    #[OnCommand('lock')]
    public function tryLock(Krubot $bot): void {
        // 🔍 DX FATALITY: Direct SDK query bypass
        $tgAgent = $bot->prime('tg');
        // Or:
        $tgAgent = PrimeAgent::engage('tg');
        
        $memberInfo = $tgAgent->getChatMember([
            'chat_id' => $bot->chatId(),
            'user_id' => $bot->userId()
        ]);

        // Or PHP_Magically...
        $memberInfo = $tgAgent('getChatMember', [
            'chat_id' => $bot->chatId(),
            'user_id' => $bot->userId()
        ]);

        // PrimeAgent is Macroable, so her powers is ∞ unlimited ∞

        // Parse raw SDK response directly
        $status = $memberInfo->status ?? $memberInfo['result']['status'] ?? 'member';

        if (!in_array($status, ['creator', 'administrator'])) {
            $bot->reply("⛔ Access Denied. Only admins can lock this gateway.")->send();
            return;
        }

        $bot->reply("🔒 System lockdown initiated.")->send();
    }
}
```

### Example 88 — The "Reverse Cartographer" (Dynamic Reverse Pattern Resolution)
* **Concept:** Stop hardcoding command strings. Generate them programmatically based on Route Names, ensuring updates are fully automated.
* **Slogan:** 🗺️ Reverse Cartographer

```php
// 📂 File: app/Nexus/Shop/ProductNexus.php
namespace App\Nexus\Shop;

use KrubiK\Attributes\OnCommand;
use KrubiK\Attributes\Name;
use KrubiK\Krubot;

class ProductNexus {
    
    #[OnCommand('product {id}')]
    #[Name('shop.product.show')]
    public function showProduct(Krubot $bot, string $id): void {
        $bot->reply("📦 Product details for ID: {$id}")->send();
    }

    #[OnCommand('catalogue')]
    public function catalogueMenu(Krubot $bot): void {
        // 🗺️ DX FATALITY: Dynamic command string generation
        $cmdPhone = $bot->resolvePattern('shop.product.show', ['id' => 'phone']);   // Returns "/product phone"
        $cmdLaptop = $bot->resolvePattern('shop.product.show', ['id' => 'laptop']); // Returns "/product laptop"

        $bot->reply("Select product category:")
            ->inlineKeypad([
                [['text' => '📱 Phones', 'callback_data' => $cmdPhone]],
                [['text' => '💻 Laptops', 'callback_data' => $cmdLaptop]]
            ])->send();
    }
}
```

### Example 89 — The "Shadow Ninja" (Inline Anonymous Shadow Conversations)
* **Concept:** Spawning full state machines on the fly inside controllers without creating dedicated conversation files.
* **Slogan:** 🥷 Ninja Mode

```php
// 📂 File: app/Nexus/Features/FeedbackNexus.php
namespace App\Nexus\Features;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;
use KrubiK\Conversations\ShadowConversation;
use KrubiK\Conversations\Answer;

class FeedbackNexus {
    
    #[OnCommand('feedback')]
    public function quickFeedback(Krubot $bot): void {
        
        // 🥷 DX FATALITY: Instant Anonymous Conversation Class
        $shadow = new class extends ShadowConversation {
            public function start(Krubot $bot): void {
                $this->ask("📝 Enter your feedback:", 'capture');
            }

            public function capture(Answer $ans): void {
                $this->bot->reply("✅ Feedback recorded: " . $ans->getText())->send();
                $this->end();
            }
        };

        $shadow->setContext($bot)->start($bot);
    }
}
```

### Example 90 — The "Spy Agent" (Silent Interceptor Middleware)
* **Concept:** Intercepting incoming messages, silently forwarding details to an archive channel, and passing control down the stack using `$next($bot)`.
* **Slogan:** 🕵️ Spy Logger

```php
// 📂 File: app/Middlewares/SpyLoggerMiddleware.php
namespace App\Middlewares;

use KrubiK\Krubot;
use Closure;

class SpyLoggerMiddleware {
    
    public function handle(Krubot $bot, Closure $next) {
        $user = $bot->user();
        $text = $bot->text();

        // 🕵️ DX FATALITY: Silent interception & routing
        $bot->forwardTo(config('channels.spy_log'))
            ->messageId($bot->messageId())
            ->caption("User: {$user['id']} | Input: {$text}")
            ->send();

        // Pass control to the next middleware or controller
        return $next($bot); 
    }
}
```

### Example 91 — The "Conditional Blueprint" (Keyboard Conditional Elements)
* **Concept:** Constructing custom keyboards dynamically on the fly using `when()` closures to append buttons based on runtime conditions.
* **Slogan:** 🎨 Blueprinter

```php
// 📂 File: app/Nexus/UI/DynamicControlNexus.php
namespace App\Nexus\UI;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;
use KrubiK\Keyboard\Keyboard;

class DynamicControlNexus {
    
    #[OnCommand('panel')]
    public function render(Krubot $bot): void {
        $isAdmin = $bot->isAdmin();

        // 🎨 DX FATALITY: Clean conditional keyboard structures
        $kb = Keyboard::make()
            ->button('Profile')->action('view_profile')
            ->when($isAdmin, function(Keyboard $k) {
                // This block executes and appends buttons ONLY if $isAdmin is true
                $k->button('👑 Admin Board')->action('admin_dashboard')->col(6);
            });

        $bot->reply("Welcome to the Control Panel:")
            ->attachKeyboard($kb)
            ->send();
    }
}
```

## 🕵️ BATCH 19: DEEP FORENSICS & CONTEXT DETECTION (مثال‌های ۹۲ تا ۹۴)

### Example 92 — The "Reply Detective" (Strict Message ID Capture)
* **Concept:** Fetching the exact message ID of the message being replied to, enabling strict contextual logging and targeted administration actions.
* **Slogan:** 🕵️ Forensic Mind

```php
// 📂 File: app/Nexus/Moderation/ReplyDetectiveNexus.php
namespace App\Nexus\Moderation;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;
use KrubiK\Helpers\AmethystMatrix;

class ReplyDetectiveNexus {
    
    #[OnCommand('inspect_reply')]
    public function inspect(Krubot $bot): void {
        // 🕵️ DX FATALITY: Capture the exact replied-to message ID
        $repliedMsgId = $bot->getReplyMessageId();

        if (!$repliedMsgId) {
            $bot->reply("⚠️ **Protocol Error:** You must reply to a message to run inspections.")
                ->markdown()
                ->send();
            return;
        }

        // Fetch meta telemetry about the action
        $currentMsgId = $bot->findMessageId();
        
        AmethystMatrix::whisper('Reply Detected', [
            'replied_to' => $repliedMsgId,
            'command_at' => $currentMsgId
        ]);

        $bot->reply("🔍 **Target Message ID Locked:** `{$repliedMsgId}`")
            ->markdown()
            ->replyTo($repliedMsgId)
            ->send();
    }
}
```

### Example 93 — The "Dynamic Array Injector" (Keyboard Button Collection)
* **Concept:** Injecting a raw, pre-compiled array of `Button` objects directly into a keyboard instance using `buttons()`, ideal for dynamic database-driven grids.
* **Slogan:** 🏗️ Grid Architect

```php
// 📂 File: app/Nexus/UI/ArrayInjectorNexus.php
namespace App\Nexus\UI;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;
use KrubiK\Keyboard\Keyboard;
use KrubiK\Keyboard\PowerButton;

class ArrayInjectorNexus {
    
    #[OnCommand('list_nodes')]
    public function renderNodes(Krubot $bot): void {
        $nodes = ['Alpha-9', 'Beta-3', 'Gamma-7', 'Delta-1'];

        // 🏗️ DX FATALITY: Pre-compile an array of PowerButtons
        $buttonCollection = [];
        foreach ($nodes as $node) {
            $buttonCollection[] = PowerButton::simple("node_{$node}", "🌐 {$node}")->action('inspectNode');
        }

        // Inject the whole collection at once using ->buttons()
        $kb = Keyboard::make()
            ->buttons($buttonCollection)
            ->chunk(2); // Automatically split into rows of 2

        $bot->reply("📡 Select an active node to inspect:")
            ->attachKeyboard($kb)
            ->send();
    }
}
```

### Example 94 — The "Low-Level Dispatcher" (Divine Direct Enqueue)
* **Concept:** Bypassing fluent helper abstractions to directly enqueue custom metaphysical payloads into specific database shards using raw parameters.
* **Slogan:** ⚡ The Core Injector

```php
// 📂 File: app/Nexus/Features/QueueDispatcherNexus.php
namespace App\Nexus\Features;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;
use KrubiK\Scheduler\DivineMessage;

class QueueDispatcherNexus {
    
    #[OnCommand('dispatch_raw')]
    public function dispatch(Krubot $bot): void {
        $targetTime = (new \DateTime())->modify('+12 hours');

        // ⚡ DX FATALITY: Direct Low-Level Queue Enqueueing
        // Bypasses the fluent scheduler interface for high-performance batch operations
        DivineMessage::enqueue(
            userId: $bot->userId(),
            sectionIndex: 9, // Targeted database partition/shard
            targetTime: $targetTime,
            payload: [
                'action'    => 'SYSTEM_PURGE',
                'initiated' => $bot->who(),
                'security'  => 'LEVEL_5_CLEARANCE'
            ]
        );

        $bot->reply("✅ Raw payload enqueued directly to Shard #9.")->send();
    }
}
```

---

## 🗺️ BATCH 20: NATIVE PORTALS & CONTEXT SWAPPING (مثال‌های ۹۵ تا ۹۷)

### Example 95 — The "Native Coordinates Beacon" (Fluent Map Dispatching)
* **Concept:** Sending raw, native map coordinates to the client using `$bot->location()` with fluent chaining.
* **Slogan:** 🗺️ The GPS Beacon

```php
// 📂 File: app/Nexus/Features/BeaconNexus.php
namespace App\Nexus\Features;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;

class BeaconNexus {
    
    #[OnCommand('locate_hq')]
    public function locate(Krubot $bot): void {
        // HQ Coordinates
        $latitude = 35.6997; 
        $longitude = 51.3380;

        // 🗺️ DX FATALITY: Fluent Native Location Dispatcher
        $bot->reply("📍 **Locating HQ...**")
            ->markdown()
            ->send();

        // Sends the physical native interactive map card
        $bot->location($latitude, $longitude)->send();
    }
}
```

### Example 96 — The "Context Swapper" (Instant State Transitioning)
* **Concept:** Immediately shifting the user into a completely different state machine (Form/Menu/Conversation) from within a controller using `beginConversation()`.
* **Slogan:** 🌌 Portal Gun

```php
// 📂 File: app/Nexus/Features/ContextSwapperNexus.php
namespace App\Nexus\Features;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;
use App\Forms\PremiumRegistrationForm;

class ContextSwapperNexus {
    
    #[OnCommand('register_premium')]
    public function swap(Krubot $bot): void {
        $bot->reply("⚡ **Initiating Premium Portal...**")->markdown()->send();

        // 🌌 DX FATALITY: Instant state machine context hijacking
        // Bypasses traditional middleware checks and launches the Form class directly
        $bot->beginConversation(new PremiumRegistrationForm());
    }
}
```

### Example 97 — The "Global Shield" (Selective Exception Catching)
* **Concept:** Catching specific domain exceptions globally across the conversation lifecycle and handling them with custom telemetry alerts.
* **Slogan:** 🛡️ Aegis Shield

```php
// 📂 File: app/Providers/KrubotExceptionServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use KrubiK\Krubot;
use KrubiK\Helpers\AmethystMatrix;
use App\Exceptions\PaymentGatewayTimeoutException;

class KrubotExceptionServiceProvider extends ServiceProvider {
    
    public function boot(Krubot $bot): void {
        // 🛡️ DX FATALITY: Global Lifecycle Exception Guard
        $bot->exception(PaymentGatewayTimeoutException::class, function(\Throwable $e, Krubot $bot) {
            
            // Telepathic alert to Amethyst Matrix
            AmethystMatrix::wail('CRITICAL: Payment Gateway Timeout Detected!', $e);

            $bot->reply("🚨 **System Alert:** Payment processing is temporarily slow. Please try again in 5 minutes.")
                ->markdown()
                ->send();
        });
    }
}
```

---

## ⛓️ BATCH 21: ADVANCED CHAINING & QUESTION COMPOSERS (مثال‌های ۹۸ تا ۱۰۱)

### Example 98 — The "Labyrinth Navigator" (Native Chain Navigation & UI Helpers)
* **Concept:** Controlling backward/forward navigation inside a `Chain` class using native `back()`, `home()`, `addBackButton()`, and `addHomeButton()`.
* **Slogan:** 🧭 Labyrinth Master

```php
// 📂 File: app/Conversations/MultiStepChain.php
namespace App\Conversations;

use KrubiK\Conversations\Chain;
use KrubiK\Krubot;
use KrubiK\Keyboard\Keyboard;

class MultiStepChain extends Chain {
    
    public function main(Krubot $bot): void {
        $this->ask("Step 1: Enter your primary domain:", 'stepTwo');
    }

    public function stepTwo(Krubot $bot): void {
        $kb = Keyboard::make();
        
        // 🧭 DX FATALITY: Inject native navigation buttons directly into the keyboard
        $this->addBackButton($kb); // Automatically binds to the previous step in the session
        $this->addHomeButton($kb); // Automatically binds to the main entry point

        $bot->reply("Step 2: Choose your configuration:")
            ->attachKeyboard($kb)
            ->send();
    }

    public function handleStepTwo(Krubot $bot): void {
        if ($bot->text() === 'back') {
            $this->back(); // Native navigation rewind
            return;
        }

        if ($bot->text() === 'home') {
            $this->home(); // Native navigation reset
            return;
        }

        $this->bot->reply("✅ Setup completed.")->send();
        $this->end();
    }
}
```

### Example 99 — The "Native Location Requester" (PowerButton Location Factory)
* **Concept:** Constructing a ballyhooed native Form that strictly requests a native geographic location from the client.
* **Slogan:** 📍 Spatial Mapper

```php
// 📂 File: app/Forms/DeliveryForm.php
namespace App\Forms;

use KrubiK\Conversations\Form;
use KrubiK\Keyboard\PowerButton;

class DeliveryForm extends Form {
    
    protected function setup(): void {
        $this->setName('express_delivery');

        // 📍 DX FATALITY: Native Location Requester Factory
        // Triggers the device's native GPS picker modal
        $this->field('dropoff', PowerButton::location('drop_loc', '📍 Pin Dropoff Location'));
    }

    protected function submit(array $data): void {
        $location = $data['dropoff']; // Contains array ['latitude' => ..., 'longitude' => ...]
        $this->bot->reply("✅ Delivery coordinates locked: Lat {$location['latitude']}")->send();
    }
}
```

### Example 100 — The "Dynamic Question Composer" (Advanced Question Chaining)
* **Concept:** Manipulating a `Question` object dynamically by appending arrays of buttons, setting a custom keyboard layout, and converting to a raw array.
* **Slogan:** 🎨 UI Composer

```php
// 📂 File: app/Conversations/DynamicQuizConv.php
namespace App\Conversations;

use KrubiK\Conversations\Conversation;
use KrubiK\Conversations\Question;
use KrubiK\Keyboard\Keyboard;
use KrubiK\Keyboard\PowerButton;

class DynamicQuizConv extends Conversation {
    
    public function start(): void {
        // 1. Instantiate the Question
        $q = Question::create("🧠 Choose your path:");

        // 2. Build a custom Keyboard layout
        $kb = Keyboard::make()->rightToLeft()
            ->button(PowerButton::simple('path_light', '✨ Light'))
            ->button(PowerButton::simple('path_dark', '🖤 Dark'));

        // 3. 🎨 DX FATALITY: Attach the keyboard directly to the Question object
        $q->keyboard($kb);

        // Alternatively, convert to a raw Krubot Keyboard structure
        $finalKb = $q->toKrubotKeyboard();

        $this->ask($q, 'finalize');
    }

    public function finalize($ans): void {
        $this->bot->reply("Path locked.")->send();
        $this->end();
    }
}
```

### Example 101 — The "Storage Merger" (Atomic Batch Updates)
* **Concept:** Merging multi-dimensional configuration arrays directly into the user's isolated storage bucket in a single atomic operation.
* **Slogan:** 💾 Memory Merger

```php
// 📂 File: app/Nexus/Features/ProfileMergerNexus.php
namespace App\Nexus\Features;

use KrubiK\Attributes\OnCommand;
use KrubiK\Krubot;

class ProfileMergerNexus {
    
    #[OnCommand('sync_profile')]
    public function sync(Krubot $bot): void {
        $storage = $bot->userStorage();

        $externalApiData = [
            'rank'       => 'Grandmaster',
            'badges'     => ['Slayer', 'Architect', 'Alchemist'],
            'last_login' => now()->toIso8601String()
        ];

        // 💾 DX FATALITY: Atomic batch merging into existing storage
        // Prevents having to manually get(), array_merge(), and put() back.
        $storage->merge($externalApiData);

        $rank = $storage->get('rank'); // Instantly accessible
        
        $bot->reply("✅ Profile synced! Current Rank: **{$rank}**")
            ->markdown()
            ->send();
    }
}
```
