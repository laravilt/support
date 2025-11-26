<?php

namespace Laravilt\Support\LaraviltCore\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravilt\Support\LaraviltCore\LaraviltCore;
use Symfony\Component\HttpFoundation\Response;

/**
 * Laravilt Middleware
 *
 * Handles Laravilt SPA requests and responses.
 */
class LaraviltMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only process Laravilt requests
        if (! LaraviltCore::isLaraviltRequest()) {
            return $response;
        }

        // If response is already JSON, return as-is
        if ($response->headers->get('Content-Type') === 'application/json') {
            return $response;
        }

        // Convert HTML response to JSON for SPA
        if ($response->getStatusCode() === 200 && method_exists($response, 'getContent')) {
            $content = $response->getContent();

            return response()->json([
                'html' => $content,
                'dynamics' => [],
                'laravilt' => [
                    'head' => [
                        'title' => $this->extractTitle($content),
                    ],
                    'shared' => [],
                    'flash' => [],
                    'errors' => session()->get('errors', new \Illuminate\Support\MessageBag)->toArray(),
                    'toasts' => [],
                    'persistentLayout' => null,
                    'modal' => false,
                    'preventRefresh' => false,
                ],
            ]);
        }

        return $response;
    }

    /**
     * Extract title from HTML content.
     */
    protected function extractTitle(string $html): ?string
    {
        if (preg_match('/<title>(.*?)<\/title>/i', $html, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
