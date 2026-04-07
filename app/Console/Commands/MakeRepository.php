<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class MakeRepository extends Command
{
    protected $signature = 'make:repository {name}';
    protected $description = 'Create a new repository class';

    public function handle()
    {
        $name = str_replace('\\', '/', $this->argument('name'));

        $path = app_path("Repositories/{$name}.php");
        $directory = dirname($path);

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true, true);
        }

        if (File::exists($path)) {
            $this->error("❌ Repository already exists!");
            return SymfonyCommand::FAILURE;
        }

        $className = class_basename($name);
        $namespace = $this->getNamespace($name);

        File::put($path, $this->getStub($namespace, $className));

        $this->info("✅ Repository created: {$namespace}\\{$className}");

        return SymfonyCommand::SUCCESS;
    }

    protected function getNamespace($name)
    {
        $parts = explode('/', $name);
        array_pop($parts);

        return 'App\\Repositories' . (count($parts) ? '\\' . implode('\\', $parts) : '');
    }

    protected function getStub($namespace, $className)
    {
        return <<<PHP
        <?php

        namespace {$namespace};

        use Illuminate\Database\Eloquent\Model;

        class {$className}
        {
            protected \$model;

            public function __construct(Model \$model)
            {
                \$this->model = \$model;
            }

            public function all()
            {
                return \$this->model->all();
            }

            public function find(\$id)
            {
                return \$this->model->findOrFail(\$id);
            }

            public function create(array \$data)
            {
                return \$this->model->create(\$data);
            }

            public function update(\$id, array \$data)
            {
                \$model = \$this->find(\$id);
                \$model->update(\$data);
                return \$model;
            }

            public function delete(\$id)
            {
                \$model = \$this->find(\$id);
                return \$model->delete();
            }
        }
        PHP;
    }
}