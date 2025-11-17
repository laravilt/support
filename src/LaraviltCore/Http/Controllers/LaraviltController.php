<?php

namespace Laravilt\Support\LaraviltCore\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * Laravilt Controller
 *
 * Base controller for Laravilt SPA requests.
 */
class LaraviltController extends Controller
{
    /**
     * Return JSON response for Laravilt SPA.
     */
    protected function laraviltResponse(string $html, array $data = []): JsonResponse
    {
        return response()->json(array_merge([
            'html' => $html,
            'laravilt' => true,
        ], $data));
    }

    /**
     * Return error response for Laravilt SPA.
     */
    protected function laraviltError(string $message, int $status = 400): JsonResponse
    {
        return response()->json([
            'error' => $message,
            'laravilt' => true,
        ], $status);
    }
}
