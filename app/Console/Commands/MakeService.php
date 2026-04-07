<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class MakeService extends Command
{
    /**
     * Command Signature
     */
    protected $signature = 'make:service {name}';

    /**
     * Description
     */
    protected $description = 'Create a new service class';

    /**
     * Execute Command
     */
    public function handle()
    {
        $name = $this->argument('name');

        // Convert namespace style (Admin/UserService)
        $name = str_replace('\\', '/', $name);

        $path = app_path("Services/{$name}.php");

        $directory = dirname($path);

        // Create directory if not exists
        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true, true);
        }

        // Check if already exists
        if (File::exists($path)) {
            $this->error("❌ Service already exists!");
            return SymfonyCommand::FAILURE;
        }

        // Generate class name
        $className = class_basename($name);

        // Generate namespace
        $namespace = $this->getNamespace($name);

        // Create file
        File::put($path, $this->getStub($namespace, $className));

        $this->info("✅ Service created: {$namespace}\\{$className}");

        return SymfonyCommand::SUCCESS;
    }

    /**
     * Generate Namespace
     */
    protected function getNamespace($name)
    {
        $parts = explode('/', $name);
        array_pop($parts);

        return 'App\\Services' . (count($parts) ? '\\' . implode('\\', $parts) : '');
    }

    /**
     * Stub Content
     */
    protected function getStub($namespace, $className)
    {
        return <<<PHP
            <?php

            namespace {$namespace};

            use Illuminate\Support\Facades\DB;
            use Illuminate\Support\Facades\Log;

            class {$className}
            {
                public function store(array \$data)
                {
                    DB::beginTransaction();

                    try {

                        // TODO: Implement store logic

                        DB::commit();
                        return true;

                    } catch (\\Throwable \$e) {
                        DB::rollBack();

                        Log::error('{$className} Store Failed', [
                            'error' => \$e->getMessage()
                        ]);

                        throw \$e;
                    }
                }

                public function update(\$model, array \$data)
                {
                    DB::beginTransaction();

                    try {

                        // TODO: Implement update logic

                        DB::commit();
                        return true;

                    } catch (\\Throwable \$e) {
                        DB::rollBack();

                        Log::error('{$className} Update Failed', [
                            'error' => \$e->getMessage()
                        ]);

                        throw \$e;
                    }
                }

                public function delete(\$model)
                {
                    DB::beginTransaction();

                    try {

                        // TODO: Implement delete logic

                        DB::commit();
                        return true;

                    } catch (\\Throwable \$e) {
                        DB::rollBack();

                        Log::error('{$className} Delete Failed', [
                            'error' => \$e->getMessage()
                        ]);

                        throw \$e;
                    }
                }
            }
        PHP;
    }
}
