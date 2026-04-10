<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeResumeWizard extends Command
{
    protected $signature = 'make:resume-wizard {name}';
    protected $description = 'Generate Resume Wizard Step Requests (Store + Update)';

    public function handle()
    {
        $name = $this->argument('name');

        $basePath = app_path("Http/Requests/Backend/{$name}");

        $steps = [1, 2, 3, 4];

        foreach ($steps as $step) {

            $this->createRequest($basePath, $name, $step, 'Store');
            $this->createRequest($basePath, $name, $step, 'Update');
        }

        $this->info("Resume Wizard Requests created successfully!");
    }

    private function createRequest($basePath, $name, $step, $type)
    {
        $className = "{$type}{$name}Step{$step}Request";
        $directory = "{$basePath}";

        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filePath = "{$directory}/{$className}.php";

        if (File::exists($filePath)) {
            $this->warn("Already exists: {$className}");
            return;
        }

        File::put($filePath, $this->template($name, $step, $type));

        $this->info("Created: {$className}");
    }

    private function template($name, $step, $type)
    {
        $namespace = "App\\Http\\Requests\\Backend\\{$name}";

        return <<<PHP
<?php

namespace {$namespace};

use Illuminate\Foundation\Http\FormRequest;

class {$type}{$name}Step{$step}Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            //
            // STEP {$step} {$type}
        ];
    }
}
PHP;
    }
}