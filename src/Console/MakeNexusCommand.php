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

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeNexusCommand extends GeneratorCommand
{
    protected $name = 'krubik:nexus-make';
    protected $description = 'Create a new Nexus class for the bot.';
    protected $type = 'Nexus';

    protected function getStub(): string
    {
        return __DIR__ . '/Stubs/nexus.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        // Place new Nexuses in the path defined in the config file.
        // We'll default to 'App\Nexus' if not specified.
        $path = config('krubot.discovery.path');
        
        // If multiple paths, use the first one.
        if (is_array($path)) {
            $path = $path[0] ?? app_path('Nexus');
        }

        // Convert file path to namespace.
        return Str::of($path)
            ->after(app_path())
            ->replace('/', '\\')
            ->prepend($rootNamespace . '\\')
            ->trim('\\');
    }
    
    protected function buildClass($name): string
    {
        $stub = parent::buildClass($name);

        $className = class_basename($name);
        $nameSnake = Str::snake(str_replace('Nexus', '', $className));

        return str_replace(['{{ name_snake }}'], [$nameSnake], $stub);
    }
}
