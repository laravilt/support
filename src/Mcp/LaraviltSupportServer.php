<?php

namespace Laravilt\Support\Mcp;

use Laravel\Mcp\Server;
use Laravilt\Support\Mcp\Tools\GenerateComponentTool;
use Laravilt\Support\Mcp\Tools\SearchDocsTool;

class LaraviltSupportServer extends Server
{
    protected string $name = 'Laravilt Support';

    protected string $version = '1.0.0';

    protected string $instructions = <<<'MARKDOWN'
        This server provides support and component capabilities for Laravilt projects.

        You can:
        - Generate custom component classes
        - Search support documentation
        - Access information about base components, concerns, and utilities

        Support is the foundation providing base components, traits, and utilities.
    MARKDOWN;

    protected array $tools = [
        GenerateComponentTool::class,
        SearchDocsTool::class,
    ];
}
