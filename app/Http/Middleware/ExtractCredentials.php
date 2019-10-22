<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use MyParcelCom\Microservice\Carrier\CarrierApiGatewayInterface;

class ExtractCredentials
{
    /** @var CarrierApiGatewayInterface */
    private $gateway;

    /**
     * @param CarrierApiGatewayInterface $gateway
     */
    public function __construct(CarrierApiGatewayInterface $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $credentials = json_decode($request->header('X-MYPARCELCOM-CREDENTIALS'), true);

        $this->gateway->setCredentials($credentials);

        return $next($request);
    }
}
