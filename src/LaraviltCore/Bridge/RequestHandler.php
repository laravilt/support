<?php

namespace Laravilt\Support\LaraviltCore\Bridge;

use Illuminate\Http\Request;

/**
 * Request Handler
 *
 * Handles incoming Laravilt requests.
 */
class RequestHandler
{
    /**
     * Check if the current request is a Laravilt request.
     */
    public static function isLaraviltRequest(Request $request): bool
    {
        return $request->header('X-Laravilt') === 'true';
    }

    /**
     * Check if the request wants JSON.
     */
    public static function wantsJson(Request $request): bool
    {
        return static::isLaraviltRequest($request) || $request->wantsJson();
    }

    /**
     * Get request data for component hydration.
     */
    public static function getComponentData(Request $request): array
    {
        return [
            'route' => $request->route()?->getName(),
            'url' => $request->url(),
            'method' => $request->method(),
            'params' => $request->all(),
            'laravilt' => static::isLaraviltRequest($request),
        ];
    }

    /**
     * Extract component props from request.
     */
    public static function extractProps(Request $request, string $key = 'component'): ?array
    {
        $data = $request->input($key);

        if (is_string($data)) {
            return json_decode($data, true);
        }

        return $data;
    }
}
