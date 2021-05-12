<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use MyParcelCom\Microservice\Carrier\CarrierApiGatewayInterface;
use MyParcelCom\Microservice\Http\ShipmentRequest;
use MyParcelCom\Microservice\Shipments\ShipmentRepository;

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

        app()
            ->when([ShipmentRequest::class, ShipmentRepository::class])
            ->needs('$suspendValidation')
            ->give(function () use ($credentials) {
                // FIXME: Get this value from somewhere else
                return Arr::get($credentials, 'suspend_validation', false);
            });

        $this->gateway->setCredentials($credentials);

        return $next($request);
    }
}
