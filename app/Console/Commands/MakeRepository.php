<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class MakeRepository extends Command
{
    // ✅ NOW SUPPORT MULTIPLE ARGUMENTS
    protected $signature = 'make:repository {names*}';

    protected $description = 'Create one or multiple repository classes';

    public function handle()
    {
        $names = $this->argument('names');

        foreach ($names as $name) {

            $name = str_replace('\\', '/', $name);

            // ✅ Auto append Repository if not provided
            if (!str_ends_with($name, 'Repository')) {
                $name .= 'Repository';
            }

            $path = app_path("Repositories/{$name}.php");
            $directory = dirname($path);

            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }

            if (File::exists($path)) {
                $this->warn("⚠️ Already exists: {$name}");
                continue;
            }

            $className = class_basename($name);
            $namespace = $this->getNamespace($name);

            File::put($path, $this->getStub($namespace, $className));

            $this->info("✅ Created: {$namespace}\\{$className}");
        }

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