<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Http\Middleware;

use Closure;
use MyParcelCom\Exceptions\InvalidSecretException;

class VerifySecret
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     * @throws InvalidSecretException
     */
    public function handle($request, Closure $next)
    {
        $receivedSecret = $request->header('X-MYPARCELCOM-SECRET');
        $expectedSecret = config('app.secret');

        if (!isset($receivedSecret) || $receivedSecret !== $expectedSecret) {
            throw new InvalidSecretException();
        }

        return $next($request);
    }
}
