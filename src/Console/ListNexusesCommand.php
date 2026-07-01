<?php

namespace KrubiK\Console;
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

use Illuminate\Console\Command;
use KrubiK\Krubot;

class ListNexusesCommand extends Command
{
    protected $signature = 'krubik:nexus-list';
    protected $description = 'Display a list of all registered Nexus classes.';

    public function handle(Krubot $krubot): int
    {
        $nexuses = $krubot->getIntegratedNexuses();

        if (empty($nexuses)) {
            $this->info("No Nexuses are registered.");
            return self::SUCCESS;
        }

        $this->comment("Registered Nexuses (" . count($nexuses) . " total):");

        $tableData = collect($nexuses)->map(function ($nexus, $index) {
            return ['#' => $index + 1, 'Nexus Class' => $nexus];
        });

        $this->table(['#', 'Nexus Class'], $tableData);

        return self::SUCCESS;
    }
}
