<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use MyParcelCom\JsonApi\Exceptions\InvalidSecretException;

class VerifySecret
{
    /**
     * Handle an incoming request.
     *
     * @throws InvalidSecretException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $receivedSecret = $request->header('X-MYPARCELCOM-SECRET');
        $expectedSecret = config('app.secret');

        if (!isset($receivedSecret) || $receivedSecret !== $expectedSecret) {
            throw new InvalidSecretException();
        }

        return $next($request);
    }
}
