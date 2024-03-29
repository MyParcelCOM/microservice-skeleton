<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use MyParcelCom\JsonApi\Exceptions\InvalidHeaderException;
use MyParcelCom\Microservice\Carrier\CarrierApiGatewayInterface;
use MyParcelCom\Microservice\Http\ShipmentRequest;
use MyParcelCom\Microservice\Shipments\ShipmentRepository;

class ExtractCredentials
{
    public function __construct(
        private readonly CarrierApiGatewayInterface $gateway,
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @throws InvalidHeaderException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!$request->hasHeader('X-MYPARCELCOM-CREDENTIALS')) {
            throw new InvalidHeaderException('Missing `X-MYPARCELCOM-CREDENTIALS` header.');
        }

        $credentials = json_decode($request->header('X-MYPARCELCOM-CREDENTIALS'), true);

        app()
            ->when([ShipmentRequest::class, ShipmentRepository::class])
            ->needs('$suspendValidation')
            ->give(function () use ($credentials) {
                return Arr::get($credentials, 'suspend_validation', false);
            });

        $this->gateway->setCredentials($credentials);

        return $next($request);
    }
}
