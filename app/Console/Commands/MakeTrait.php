<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class MakeTrait extends Command
{
    protected $signature = 'make:trait {name}';
    protected $description = 'Create a new trait';

    public function handle()
    {
        $name = str_replace('\\', '/', $this->argument('name'));

        $path = app_path("Traits/{$name}.php");
        $directory = dirname($path);

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true, true);
        }

        if (File::exists($path)) {
            $this->error("❌ Trait already exists!");
            return SymfonyCommand::FAILURE;
        }

        $className = class_basename($name);
        $namespace = $this->getNamespace($name);

        File::put($path, $this->getStub($namespace, $className));

        $this->info("✅ Trait created: {$namespace}\\{$className}");

        return SymfonyCommand::SUCCESS;
    }

    protected function getNamespace($name)
    {
        $parts = explode('/', $name);
        array_pop($parts);

        return 'App\\Traits' . (count($parts) ? '\\' . implode('\\', $parts) : '');
    }

    protected function getStub($namespace, $className)
    {
        return <<<PHP
        <?php

        namespace {$namespace};

        trait {$className}
        {
            // Common reusable methods

            public function logInfo(\$message)
            {
                \\Log::info(\$message);
            }
        }
        PHP;
    }
}