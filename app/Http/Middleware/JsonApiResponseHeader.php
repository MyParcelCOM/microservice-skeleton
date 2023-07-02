<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JsonApiResponseHeader
{
    /**
     * Handle an outgoing response.
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        /** @var JsonResponse $response */
        $response = $next($request);

        if ($response->headers->get('Content-Type') === 'application/json') {
            $response->headers->set('Content-Type', 'application/vnd.api+json');
        }

        return $response;
    }
}
