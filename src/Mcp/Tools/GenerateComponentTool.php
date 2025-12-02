<?php

namespace Laravilt\Support\Mcp\Tools;

use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class GenerateComponentTool extends Tool
{
    protected string $description = 'Generate a custom component class extending the base Component';

    public function handle(Request $request): Response
    {
        $name = $request->string('name');

        $command = 'php '.base_path('artisan').' make:component "'.$name.'" --no-interaction';

        if ($request->boolean('force', false)) {
            $command .= ' --force';
        }

        exec($command, $output, $returnCode);

        if ($returnCode === 0) {
            $response = "✅ Component '{$name}' created successfully!\n\n";
            $response .= "📖 Location: app/Components/{$name}.php\n\n";
            $response .= "📦 Available concerns: CanBeDisabled, HasLabel, HasState, HasVisibility, and more\n";

            return Response::text($response);
        } else {
            return Response::text('❌ Failed to create component: '.implode("\n", $output));
        }
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()
                ->description('Component class name in StudlyCase (e.g., "RatingInput")')
                ->required(),
            'force' => $schema->boolean()
                ->description('Overwrite existing file')
                ->default(false),
        ];
    }
}
