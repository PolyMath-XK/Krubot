<?php

namespace KrubiK\Nexus\NoxiousSamples;
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

use KrubiK\Krubot;
use KrubiK\Attributes\OnCommand;

class SimpleSampleNexus
{
    #[OnCommand('start')]
    public function start(Krubot $bot)
    {
        $bot->reply("سلام! من آماده‌ام. 🚀\nاین پاسخ از طریق صف لاراول (HandleDriverUpdate Job) ارسال شد.")
            ->send();
    }

    #[OnCommand('info')]
    public function info(Krubot $bot)
    {
        $user = $bot->user();
        $bot->say("اطلاعات شما:\nنام: {$user['first_name']}")->send();
    }

    #[OnCommand('reverse {param1}')]
    public function zmod(Krubot $bot, string $param1)
    {
        $usertext = $bot->text() . '    <<=>>   ' . strrev($param1);
        $bot->reply($usertext)->send();
    }
    
    #[OnCommand('keyboard')]
    public function menu(Krubot $bot)
    {
        $bot->attachKeyboard(function($kb) {
             $kb->row(fn($r) => $r->simple('دکمه 1', 'btn_1'));
        })->reply("کیبورد باز شد")->send();
    }
}
