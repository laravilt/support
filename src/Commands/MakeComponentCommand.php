<?php

namespace Laravilt\Support\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

/**
 * Make Component Command
 *
 * Generates a new Laravilt component with view template.
 */
class MakeComponentCommand extends Command
{
    protected $signature = 'laravilt:component {name} {--force : Overwrite existing component}';

    protected $description = 'Generate a new Laravilt component';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle(): int
    {
        $name = $this->argument('name');
        $className = Str::studly($name);
        $componentName = Str::kebab($name);

        $this->info("Creating Laravilt component: {$className}");

        // Create component class
        $componentPath = $this->getComponentPath($className);
        if ($this->files->exists($componentPath) && ! $this->option('force')) {
            $this->error("Component {$className} already exists!");

            return self::FAILURE;
        }

        $this->createComponent($componentPath, $className, $componentName);
        $this->info("✓ Created component: {$componentPath}");

        // Create view file
        $viewPath = $this->getViewPath($componentName);
        if ($this->files->exists($viewPath) && ! $this->option('force')) {
            $this->warn("View already exists: {$viewPath}");
        } else {
            $this->createView($viewPath, $componentName);
            $this->info("✓ Created view: {$viewPath}");
        }

        // Success message
        $this->newLine();
        $this->info('Component created successfully!');
        $this->newLine();
        $this->comment('Next steps:');
        $this->comment("1. Customize {$className} in {$componentPath}");
        $this->comment("2. Edit the view template in {$viewPath}");
        $this->comment("3. Use in Blade: {!! {$className}::make('my-id')->render() !!}");

        return self::SUCCESS;
    }

    protected function getComponentPath(string $className): string
    {
        return app_path("Components/{$className}.php");
    }

    protected function getViewPath(string $componentName): string
    {
        return resource_path("views/components/{$componentName}.blade.php");
    }

    protected function createComponent(string $path, string $className, string $componentName): void
    {
        $directory = dirname($path);
        if (! $this->files->isDirectory($directory)) {
            $this->files->makeDirectory($directory, 0755, true);
        }

        $stub = $this->getComponentStub();
        $content = str_replace(
            ['{{className}}', '{{componentName}}'],
            [$className, $componentName],
            $stub
        );

        $this->files->put($path, $content);
    }

    protected function createView(string $path, string $componentName): void
    {
        $directory = dirname($path);
        if (! $this->files->isDirectory($directory)) {
            $this->files->makeDirectory($directory, 0755, true);
        }

        $stub = $this->getViewStub();
        $content = str_replace('{{componentName}}', $componentName, $stub);

        $this->files->put($path, $content);
    }

    protected function getComponentStub(): string
    {
        return <<<'STUB'
<?php

namespace App\Components;

use Laravilt\Support\Component;

class {{className}} extends Component
{
    protected string $view = 'components.{{componentName}}';

    protected string $title = '';
    protected string $description = '';

    public function title(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function description(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->evaluate($this->title);
    }

    public function getDescription(): string
    {
        return $this->evaluate($this->description);
    }

    public function toLaraviltProps(): array
    {
        return array_merge(parent::toLaraviltProps(), [
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
        ]);
    }
}

STUB;
    }

    protected function getViewStub(): string
    {
        return <<<'STUB'
<div class="laravilt-{{componentName}}" data-component="{{ $component->getId() }}">
    @if ($component->getLabel())
        <label class="component-label">{{ $component->getLabel() }}</label>
    @endif

    <div class="component-content">
        @if ($component->getTitle())
            <h3>{{ $component->getTitle() }}</h3>
        @endif

        @if ($component->getDescription())
            <p>{{ $component->getDescription() }}</p>
        @endif

        <div class="component-state">
            {!! $component->getState() !!}
        </div>
    </div>

    @if ($component->getHelperText())
        <small class="helper-text">{{ $component->getHelperText() }}</small>
    @endif
</div>

STUB;
    }
}
